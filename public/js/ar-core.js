/* ============================================================
   HazardLIDM — AR Core Engine (js/ar-core.js)
   Mengelola inisialisasi Three.js, sesi WebXR, raycasting,
   dan fallback mode 3D untuk desktop / non-AR.
   ============================================================ */

const ARCore = {
  scene: null,
  camera: null,
  renderer: null,
  controls: null,
  floorMesh: null,
  cylinderMesh: null,
  cappingKitMesh: null,
  waterParticles: null,
  waterPositions: null,
  waterVelocities: null,
  isSpraying: false,
  isARMode: false,
  simulationActive: false,
  startTime: null,
  
  // WebXR session variables
  xrSession: null,
  hitTestSource: null,
  localReferenceSpace: null,
  viewerReferenceSpace: null,
  reticle: null,

  /**
   * Inisialisasi utama engine 3D/AR
   */
  async init() {
    this.setupThreeJS();
    
    // Periksa dukungan WebXR
    const isSupported = navigator.xr && typeof navigator.xr.isSessionSupported === 'function' 
      ? await navigator.xr.isSessionSupported('immersive-ar').catch(() => false)
      : false;

    if (isSupported && window.location.protocol === 'https:') {
      this.setupWebXRButton();
    } else {
      alert("Perangkat atau browser Anda tidak mendukung WebXR (Immersive AR). Silakan gunakan Google Chrome pada perangkat Android yang mendukung Google Play Services for AR (ARCore) dan pastikan koneksi menggunakan HTTPS.");
      window.location.href = 'dashboard.html';
    }
  },

  /**
   * Konfigurasi standar Three.js
   */
  setupThreeJS() {
    this.scene = new THREE.Scene();
    
    // Set aspect ratio dan render canvas
    const width = window.innerWidth;
    const height = window.innerHeight;
    
    this.camera = new THREE.PerspectiveCamera(60, width / height, 0.1, 1000);
    this.camera.position.set(0, 2.5, 4); // Tampilan atas desktop
    
    this.renderer = new THREE.WebGLRenderer({
      canvas: document.getElementById('three-canvas'),
      antialias: true,
      alpha: true // transparansi untuk camera stream overlay
    });
    this.renderer.setSize(width, height);
    this.renderer.setPixelRatio(window.devicePixelRatio);
    this.renderer.shadowMap.enabled = true;

    // Tambahkan pencahayaan
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    this.scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
    dirLight.position.set(5, 15, 5);
    dirLight.castShadow = true;
    dirLight.shadow.mapSize.width = 1024;
    dirLight.shadow.mapSize.height = 1024;
    this.scene.add(dirLight);

    // Event listener resize window
    window.addEventListener('resize', () => this.onWindowResize());
  },

  /**
   * Responsive resize
   */
  onWindowResize() {
    this.camera.aspect = window.innerWidth / window.innerHeight;
    this.camera.updateProjectionMatrix();
    this.renderer.setSize(window.innerWidth, window.innerHeight);
  },

  /**
   * Setup visual partikel semprotan air untuk Amonia
   */
  setupWaterSpray() {
    const particleCount = 200;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const velocities = new Float32Array(particleCount * 3);
    
    for (let i = 0; i < particleCount; i++) {
      positions[i*3] = this.cylinderMesh.position.x + (Math.random() - 0.5) * 1.5;
      positions[i*3+1] = this.cylinderMesh.position.y + 2.0;
      positions[i*3+2] = this.cylinderMesh.position.z + (Math.random() - 0.5) * 1.5;
      
      velocities[i*3] = (Math.random() - 0.5) * 0.2;
      velocities[i*3+1] = -1.5 - Math.random() * 1.5; // jatuh ke bawah
      velocities[i*3+2] = (Math.random() - 0.5) * 0.2;
    }
    
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('aVelocity', new THREE.BufferAttribute(velocities, 3));
    
    const material = new THREE.PointsMaterial({
      color: 0x38bdf8, // light blue
      size: 0.12,
      transparent: true,
      opacity: 0.7,
      blending: THREE.AdditiveBlending,
      depthWrite: false
    });
    
    this.waterParticles = new THREE.Points(geometry, material);
    this.waterPositions = positions;
    this.waterVelocities = velocities;
    this.waterParticles.visible = false;
    this.scene.add(this.waterParticles);
  },

  /**
   * Setup interaksi input holding-tap (Amonia) atau clicking tabung (Klorin)
   */
  setupInteractions() {
    const layer = document.getElementById('interaction-layer');
    const isAmonia = window.SimulationConfig.gas_type === 'amonia';

    if (isAmonia) {
      layer.addEventListener('pointerdown', (e) => {
        if (!this.simulationActive) return;
        this.isSpraying = true;
        if (window.PPMCalculator && window.PPMCalculator.setMitigationActive) {
          window.PPMCalculator.setMitigationActive(true);
        }
      });

      const stopSpray = () => {
        if (!this.simulationActive) return;
        this.isSpraying = false;
        if (window.PPMCalculator && window.PPMCalculator.setMitigationActive) {
          window.PPMCalculator.setMitigationActive(false);
        }
      };

      layer.addEventListener('pointerup', stopSpray);
      layer.addEventListener('pointerleave', stopSpray);
      layer.addEventListener('pointercancel', stopSpray);
    } else {
      // Klorin: Raycast tap pada tabung
      layer.addEventListener('pointerdown', (e) => {
        if (!this.simulationActive || this.cappingKitMesh) return;

        const rect = this.renderer.domElement.getBoundingClientRect();
        const mouse = new THREE.Vector2(
          ((e.clientX - rect.left) / rect.width) * 2 - 1,
          -((e.clientY - rect.top) / rect.height) * 2 + 1
        );

        const raycaster = new THREE.Raycaster();
        raycaster.setFromCamera(mouse, this.camera);
        
        const intersects = raycaster.intersectObject(this.cylinderMesh, true);
        if (intersects.length > 0) {
          const distance = this.camera.position.distanceTo(this.cylinderMesh.position);
          if (distance < 1.5) {
            this.spawnCappingKit();
            if (window.PPMCalculator && window.PPMCalculator.setMitigationActive) {
              window.PPMCalculator.setMitigationActive(true);
            }
            document.getElementById('instruction-text').textContent = 'Capping Kit terpasang! Kebocoran klorin berhasil dibendung.';
            document.getElementById('instruction-icon').textContent = '✅';
          } else {
            document.getElementById('instruction-text').textContent = 'Terlalu jauh! Dekati tabung gas (< 1.5 meter) untuk memasang Capping Kit.';
            setTimeout(() => {
              if (this.simulationActive && !this.cappingKitMesh) {
                document.getElementById('instruction-text').textContent = 'Dekati tabung gas, lalu klik/tap pada katup tabung (atas) untuk memasang Capping Kit.';
              }
            }, 3000);
          }
        }
      });
    }
  },

  /**
   * Spawn model 3D capping kit di atas valve tabung klorin
   */
  spawnCappingKit() {
    const cappingGroup = new THREE.Group();
    
    // Klem luar
    const capGeo = new THREE.BoxGeometry(0.35, 0.2, 0.35);
    const capMat = new THREE.MeshStandardMaterial({
      color: 0x10b981, // Hijau K3 / emerald
      metalness: 0.8,
      roughness: 0.2
    });
    const capMesh = new THREE.Mesh(capGeo, capMat);
    capMesh.position.y = 0.95; // Pas di atas valve tabung
    capMesh.castShadow = true;
    cappingGroup.add(capMesh);
    
    // Baut pengencang di atas klem
    const boltGeo = new THREE.CylinderGeometry(0.04, 0.04, 0.15, 8);
    const boltMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8, metalness: 0.9 });
    const boltMesh = new THREE.Mesh(boltGeo, boltMat);
    boltMesh.position.y = 1.1;
    cappingGroup.add(boltMesh);
    
    this.cappingKitMesh = cappingGroup;
    this.cylinderMesh.add(this.cappingKitMesh);
  },

  /**
   * Spawn tangki kimia 3D (Cylinder kuning berlogo biohazard sederhana)
   */
  spawnGasCylinder(position) {
    // 1. Group Cylinder
    const cylinderGroup = new THREE.Group();
    cylinderGroup.position.copy(position);

    // 2. Tabung Utama
    const cylinderGeo = new THREE.CylinderGeometry(0.25, 0.25, 0.9, 32);
    const cylinderMat = new THREE.MeshStandardMaterial({
      color: 0xeab308, // Kuning cerah/biohazard
      metalness: 0.7,
      roughness: 0.3
    });
    const cylinder = new THREE.Mesh(cylinderGeo, cylinderMat);
    cylinder.position.y = 0.45; // letakkan alasnya di lantai
    cylinder.castShadow = true;
    cylinderGroup.add(cylinder);

    // 3. Kepala Valve Tangki (di atas)
    const valveGeo = new THREE.CylinderGeometry(0.1, 0.1, 0.15, 16);
    const valveMat = new THREE.MeshStandardMaterial({ color: 0x334155, metalness: 0.8 });
    const valve = new THREE.Mesh(valveGeo, valveMat);
    valve.position.y = 0.95;
    cylinderGroup.add(valve);

    this.cylinderMesh = cylinderGroup;
    this.scene.add(this.cylinderMesh);

    // Hubungkan tangki ke pemancar partikel gas
    if (window.GasSystem && window.GasSystem.init) {
      window.GasSystem.init(this.scene, position);
    }
  },

  /**
   * Mulai loop aktif simulasi
   */
  startSimulationActive() {
    this.simulationActive = true;
    this.startTime = Date.now();

    if (window.PPMCalculator && window.PPMCalculator.start) {
      window.PPMCalculator.start(window.SimulationConfig);
    }

    // Jalankan Loop Animasi menggunakan setAnimationLoop (mendukung WebXR & Non-AR)
    this.renderer.setAnimationLoop((timestamp, frame) => this.animate(timestamp, frame));
  },

  /**
   * Render loop frame-by-frame
   */
  animate(timestamp, frame) {
    if (!this.simulationActive) return;

    const deltaTime = 0.016; // Asumsikan 60 FPS untuk kalkulasi waktu aman

    // 1. Update Kontrol Navigasi
    if (this.controls) {
      this.controls.update();
    }

    // 2. WebXR Hit-Testing (Kunci Reticle pada Lantai Nyata)
    if (this.isARMode && frame && this.hitTestSource) {
      const hitTestResults = frame.getHitTestResults(this.hitTestSource);
      if (hitTestResults.length > 0) {
        const hit = hitTestResults[0];
        const pose = hit.getPose(this.localReferenceSpace);
        this.reticle.visible = true;
        this.reticle.matrix.fromArray(pose.transform.matrix);
      } else {
        this.reticle.visible = false;
      }
    }

    // 3. Update Sistem Gas
    if (window.GasSystem && window.GasSystem.update) {
      window.GasSystem.update(deltaTime);
    }

    // 4. Update Visual Water Spray
    if (this.isSpraying && this.waterParticles) {
      this.waterParticles.visible = true;
      const positions = this.waterParticles.geometry.attributes.position.array;
      for (let i = 0; i < 200; i++) {
        positions[i*3] += this.waterVelocities[i*3] * deltaTime;
        positions[i*3+1] += this.waterVelocities[i*3+1] * deltaTime;
        positions[i*3+2] += this.waterVelocities[i*3+2] * deltaTime;
        
        // Loop jika mencapai lantai
        if (positions[i*3+1] < 0.0) {
          positions[i*3] = this.cylinderMesh.position.x + (Math.random() - 0.5) * 1.5;
          positions[i*3+1] = this.cylinderMesh.position.y + 2.0;
          positions[i*3+2] = this.cylinderMesh.position.z + (Math.random() - 0.5) * 1.5;
        }
      }
      this.waterParticles.geometry.attributes.position.needsUpdate = true;
    } else if (this.waterParticles) {
      this.waterParticles.visible = false;
    }

    // 5. Hitung Jarak Kamera/Sensor ke Tangki Bocor untuk Kalkulasi PPM Lokasi
    let distanceToLeakingSource = 1.5; // Default safe distance
    if (this.camera && this.cylinderMesh) {
      distanceToLeakingSource = this.camera.position.distanceTo(this.cylinderMesh.position);
    }

    // 6. Update Kalkulator PPM
    if (window.PPMCalculator && window.PPMCalculator.update) {
      window.PPMCalculator.update(deltaTime, distanceToLeakingSource);
    }

    // 7. Render Scene
    this.renderer.render(this.scene, this.camera);
  },

  /**
   * Setup tombol AR (jika browser mendukung immersive-ar)
   */
  setupWebXRButton() {
    this.isARMode = true;

    // Sesuaikan UI
    document.getElementById('mode-text').textContent = 'Mode WebXR AR';
    document.getElementById('mode-dot').className = 'w-2 h-2 rounded-full bg-teal-500 animate-pulse';
    document.getElementById('ar-instructions').style.display = 'block';
    document.getElementById('instruction-text').textContent = 'Memindai permukaan lantai nyata...';
    document.getElementById('instruction-icon').textContent = '📱';

    // Aktifkan XR pada renderer Three.js
    this.renderer.xr.enabled = true;

    // Buat Reticle (lingkaran indikator peletakan tabung)
    const reticleGeo = new THREE.RingGeometry(0.12, 0.15, 32).rotateX(-Math.PI / 2);
    const reticleMat = new THREE.MeshBasicMaterial({ color: 0x64FFB4 });
    this.reticle = new THREE.Mesh(reticleGeo, reticleMat);
    this.reticle.matrixAutoUpdate = false;
    this.reticle.visible = false;
    this.scene.add(this.reticle);

    // Minta sesi immersive-ar dengan fitur hit-test
    navigator.xr.requestSession('immersive-ar', {
      requiredFeatures: ['local-floor', 'hit-test']
    }).then((session) => {
      this.xrSession = session;
      this.renderer.xr.setSession(session);

      // Minta reference space
      session.requestReferenceSpace('viewer').then((refSpace) => {
        session.requestHitTestSource({ space: refSpace }).then((source) => {
          this.hitTestSource = source;
        });
      });

      session.requestReferenceSpace('local-floor').then((refSpace) => {
        this.localReferenceSpace = refSpace;
      });

      session.addEventListener('end', () => {
        this.xrSession = null;
        this.stopSimulation();
      });

      // Tap layar di WebXR memicu event 'select' untuk meletakkan tabung gas
      session.addEventListener('select', () => {
        if (this.reticle.visible && !this.cylinderMesh) {
          const position = new THREE.Vector3();
          position.setFromMatrixPosition(this.reticle.matrix);
          this.spawnGasCylinder(position);
          this.reticle.visible = false;
          this.hitTestSource = null; // Selesai hit-test setelah diletakkan

          const isAmonia = window.SimulationConfig.gas_type === 'amonia';
          if (isAmonia) {
            document.getElementById('instruction-text').textContent = 'Tahan sentuh layar untuk menyemprotkan Water Curtain (Tirai Air).';
            document.getElementById('instruction-icon').textContent = '💧';
            this.setupWaterSpray();
          } else {
            document.getElementById('instruction-text').textContent = 'Dekati tabung gas, lalu tap pada katup tabung (atas) untuk memasang Capping Kit.';
            document.getElementById('instruction-icon').textContent = '🔧';
          }
          this.setupInteractions();
        }
      });

      this.startSimulationActive();
    }).catch((err) => {
      console.warn("Gagal memulai sesi WebXR:", err);
      alert("Gagal memulai sesi WebXR AR: " + err.message + ". Pastikan perangkat Anda mendukung ARCore.");
      window.location.href = 'dashboard.html';
    });
  },

  /**
   * Mengakhiri jalannya simulasi
   */
  stopSimulation() {
    this.simulationActive = false;
    this.isSpraying = false;
    if (this.waterParticles) {
      this.waterParticles.visible = false;
    }
    this.renderer.setAnimationLoop(null); // Matikan loop rendering
  }
};

// Expose globally
window.ARCore = ARCore;
