/* ============================================================
   HazardLIDM — AR Core Engine (js/ar-core.js)
   Mode: Camera + Three.js Overlay (No Marker Needed)
   Kamera dibuka langsung via getUserMedia, tabung gas 3D
   ditampilkan sebagai overlay Three.js di atas video feed.
   ============================================================ */

const ARCore = {
  scene: null,
  camera: null,       // THREE.PerspectiveCamera
  renderer: null,     // THREE.WebGLRenderer
  videoEl: null,      // <video> element kamera
  cylinderMesh: null,
  cappingKitMesh: null,
  waterParticles: null,
  waterPositions: null,
  waterVelocities: null,
  isSpraying: false,
  simulationActive: false,
  startTime: null,

  /**
   * Inisialisasi kamera + Three.js overlay
   */
  async init() {
    const container = document.getElementById('mindar-container');

    // ── 1. Buka kamera belakang via getUserMedia ──────────────────────────
    let stream;
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: { ideal: 'environment' }, // kamera belakang
          width: { ideal: 1280 },
          height: { ideal: 720 }
        },
        audio: false
      });
    } catch (err) {
      console.error('Gagal membuka kamera:', err);
      alert('Izin kamera ditolak atau kamera tidak tersedia. Pastikan menggunakan HTTPS / localhost.');
      window.location.href = 'dashboard.html';
      return;
    }

    // ── 2. Buat elemen <video> sebagai background kamera ─────────────────
    this.videoEl = document.createElement('video');
    this.videoEl.setAttribute('autoplay', '');
    this.videoEl.setAttribute('muted', '');
    this.videoEl.setAttribute('playsinline', '');
    this.videoEl.srcObject = stream;
    this.videoEl.style.cssText = `
      position: absolute; inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      z-index: 1;
    `;
    container.appendChild(this.videoEl);
    await this.videoEl.play();

    // ── 3. Setup Three.js Renderer (transparan di atas video) ─────────────
    const W = container.clientWidth  || window.innerWidth;
    const H = container.clientHeight || window.innerHeight;

    this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    this.renderer.setSize(W, H);
    this.renderer.setPixelRatio(window.devicePixelRatio);
    this.renderer.shadowMap.enabled = true;
    this.renderer.setClearColor(0x000000, 0); // transparan — video jadi background
    this.renderer.domElement.style.cssText = `
      position: absolute; inset: 0;
      width: 100%; height: 100%;
      z-index: 2;
      pointer-events: none;
    `;
    container.appendChild(this.renderer.domElement);

    // ── 4. Scene & Camera Three.js ────────────────────────────────────────
    this.scene = new THREE.Scene();

    this.camera = new THREE.PerspectiveCamera(70, W / H, 0.01, 100);
    this.camera.position.set(0, 0.5, 3); // posisi kamera virtual — tabung terlihat di depan

    // ── 5. Pencahayaan ────────────────────────────────────────────────────
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
    this.scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 1.0);
    dirLight.position.set(3, 8, 5);
    dirLight.castShadow = true;
    this.scene.add(dirLight);

    const rimLight = new THREE.DirectionalLight(0x38bdf8, 0.4);
    rimLight.position.set(-5, 2, -3);
    this.scene.add(rimLight);

    // ── 6. Spawn tabung gas di tengah scene ───────────────────────────────
    const sceneGroup = new THREE.Group();
    this.scene.add(sceneGroup);
    this.spawnGasCylinder(new THREE.Vector3(0, -0.5, 0), sceneGroup);
    this.setupWaterSpray(sceneGroup);
    this.setupInteractions();

    // ── 7. Handle resize ─────────────────────────────────────────────────
    window.addEventListener('resize', () => {
      const nW = container.clientWidth  || window.innerWidth;
      const nH = container.clientHeight || window.innerHeight;
      this.renderer.setSize(nW, nH);
      this.camera.aspect = nW / nH;
      this.camera.updateProjectionMatrix();
    });

    // ── 8. Update UI dan mulai simulasi ───────────────────────────────────
    document.getElementById('mode-text').textContent = 'Mode AR Kamera';
    document.getElementById('mode-dot').className = 'w-2 h-2 rounded-full bg-teal-500 animate-pulse';
    // Sembunyikan bar instruksi agar tidak mengganggu tampilan
    document.getElementById('ar-instructions').style.display = 'none';

    this.simulationActive = true;
    this.startTime = Date.now();

    if (window.PPMCalculator && window.PPMCalculator.start) {
      window.PPMCalculator.start(window.SimulationConfig);
    }

    // Mulai render loop
    this.renderer.setAnimationLoop((ts) => this.animate(ts));
  },

  /**
   * Setup visual partikel semprotan air untuk Amonia
   */
  setupWaterSpray(parentGroup) {
    const particleCount = 200;
    const geometry = new THREE.BufferGeometry();
    const positions  = new Float32Array(particleCount * 3);
    const velocities = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount; i++) {
      positions[i*3]   = (Math.random() - 0.5) * 1.5;
      positions[i*3+1] = 1.5;
      positions[i*3+2] = (Math.random() - 0.5) * 1.5;

      velocities[i*3]   = (Math.random() - 0.5) * 0.2;
      velocities[i*3+1] = -1.5 - Math.random() * 1.5;
      velocities[i*3+2] = (Math.random() - 0.5) * 0.2;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

    const material = new THREE.PointsMaterial({
      color: 0x38bdf8,
      size: 0.08,
      transparent: true,
      opacity: 0.75,
      blending: THREE.AdditiveBlending,
      depthWrite: false
    });

    this.waterParticles  = new THREE.Points(geometry, material);
    this.waterPositions  = positions;
    this.waterVelocities = velocities;
    this.waterParticles.visible = false;
    parentGroup.add(this.waterParticles);
  },

  /**
   * Setup interaksi: hold (Amonia) / tap tabung (Klorin)
   */
  setupInteractions() {
    const layer = document.getElementById('interaction-layer');
    // Aktifkan pointer events pada layer interaksi
    layer.style.pointerEvents = 'auto';

    const isAmonia = window.SimulationConfig.gas_type === 'amonia';

    if (isAmonia) {
      layer.addEventListener('pointerdown', () => {
        if (!this.simulationActive) return;
        this.isSpraying = true;
        if (window.PPMCalculator?.setMitigationActive) window.PPMCalculator.setMitigationActive(true);
      });

      const stopSpray = () => {
        this.isSpraying = false;
        if (window.PPMCalculator?.setMitigationActive) window.PPMCalculator.setMitigationActive(false);
      };
      layer.addEventListener('pointerup',     stopSpray);
      layer.addEventListener('pointerleave',  stopSpray);
      layer.addEventListener('pointercancel', stopSpray);

    } else {
      // Klorin: tap untuk pasang capping kit
      layer.addEventListener('pointerdown', (e) => {
        if (!this.simulationActive || this.cappingKitMesh) return;

        const rect  = this.renderer.domElement.getBoundingClientRect();
        const mouse = new THREE.Vector2(
          ((e.clientX - rect.left) / rect.width)  *  2 - 1,
          -((e.clientY - rect.top)  / rect.height) *  2 + 1
        );

        const raycaster = new THREE.Raycaster();
        raycaster.setFromCamera(mouse, this.camera);
        const intersects = raycaster.intersectObject(this.cylinderMesh, true);

        if (intersects.length > 0) {
          this.spawnCappingKit();
          if (window.PPMCalculator?.setMitigationActive) window.PPMCalculator.setMitigationActive(true);
          document.getElementById('instruction-text').textContent = '✅ Capping Kit terpasang! Kebocoran klorin berhasil dibendung.';
          document.getElementById('instruction-icon').textContent = '✅';
        } else {
          document.getElementById('instruction-text').textContent = '⚠️ Tap langsung pada tabung gas untuk memasang Capping Kit!';
        }
      });
    }
  },

  /**
   * Spawn capping kit di atas valve tabung klorin
   */
  spawnCappingKit() {
    const group = new THREE.Group();

    const capMesh = new THREE.Mesh(
      new THREE.BoxGeometry(0.35, 0.2, 0.35),
      new THREE.MeshStandardMaterial({ color: 0x10b981, metalness: 0.8, roughness: 0.2 })
    );
    capMesh.position.y = 0.95;
    capMesh.castShadow = true;
    group.add(capMesh);

    const boltMesh = new THREE.Mesh(
      new THREE.CylinderGeometry(0.04, 0.04, 0.15, 8),
      new THREE.MeshStandardMaterial({ color: 0x94a3b8, metalness: 0.9 })
    );
    boltMesh.position.y = 1.1;
    group.add(boltMesh);

    this.cappingKitMesh = group;
    this.cylinderMesh.add(this.cappingKitMesh);
  },

  /**
   * Spawn model 3D tabung gas kimia
   */
  spawnGasCylinder(position, parentGroup) {
    const cylinderGroup = new THREE.Group();
    cylinderGroup.position.copy(position);

    // Badan utama tabung
    const cylinder = new THREE.Mesh(
      new THREE.CylinderGeometry(0.25, 0.25, 0.9, 32),
      new THREE.MeshStandardMaterial({ color: 0xeab308, metalness: 0.7, roughness: 0.3 })
    );
    cylinder.position.y = 0.45;
    cylinder.castShadow = true;
    cylinderGroup.add(cylinder);

    // Alas tabung (kaki)
    const base = new THREE.Mesh(
      new THREE.CylinderGeometry(0.28, 0.3, 0.08, 32),
      new THREE.MeshStandardMaterial({ color: 0x475569, metalness: 0.6, roughness: 0.5 })
    );
    base.position.y = 0.04;
    cylinderGroup.add(base);

    // Kepala valve
    const valve = new THREE.Mesh(
      new THREE.CylinderGeometry(0.1, 0.1, 0.15, 16),
      new THREE.MeshStandardMaterial({ color: 0x334155, metalness: 0.8 })
    );
    valve.position.y = 0.95;
    cylinderGroup.add(valve);

    // Label warna gas (strip merah di badan)
    const strip = new THREE.Mesh(
      new THREE.CylinderGeometry(0.251, 0.251, 0.12, 32),
      new THREE.MeshStandardMaterial({
        color: window.SimulationConfig?.gas_type === 'klorin' ? 0x22c55e : 0xfacc15,
        metalness: 0.3,
        roughness: 0.6
      })
    );
    strip.position.y = 0.6;
    cylinderGroup.add(strip);

    this.cylinderMesh = cylinderGroup;
    parentGroup.add(this.cylinderMesh);

    // Hubungkan ke sistem partikel gas — pass scene & position
    if (window.GasSystem?.init) {
      window.GasSystem.init(this.scene, position);
    }
  },

  /**
   * Render loop per frame
   */
  animate(timestamp) {
    if (!this.simulationActive) return;

    const deltaTime = 0.016;

    // Rotasi tabung perlahan untuk efek visual
    if (this.cylinderMesh) {
      this.cylinderMesh.rotation.y += 0.003;
    }

    // Update partikel gas
    if (window.GasSystem?.update) {
      window.GasSystem.update(deltaTime);
    }

    // Update partikel air (semprotan)
    if (this.waterParticles) {
      if (this.isSpraying) {
        this.waterParticles.visible = true;
        const pos = this.waterParticles.geometry.attributes.position.array;
        for (let i = 0; i < 200; i++) {
          pos[i*3]   += this.waterVelocities[i*3]   * deltaTime;
          pos[i*3+1] += this.waterVelocities[i*3+1] * deltaTime;
          pos[i*3+2] += this.waterVelocities[i*3+2] * deltaTime;
          if (pos[i*3+1] < -0.5) {
            pos[i*3]   = (Math.random() - 0.5) * 1.5;
            pos[i*3+1] = 1.5;
            pos[i*3+2] = (Math.random() - 0.5) * 1.5;
          }
        }
        this.waterParticles.geometry.attributes.position.needsUpdate = true;
      } else {
        this.waterParticles.visible = false;
      }
    }

    // Jarak "virtual" tetap di 2 meter (jarak aman simulasi tanpa marker tracking)
    const distanceToLeakingSource = 2.0;

    if (window.PPMCalculator?.update) {
      window.PPMCalculator.update(deltaTime, distanceToLeakingSource);
    }

    this.renderer.render(this.scene, this.camera);
  },

  /**
   * Hentikan simulasi
   */
  stopSimulation() {
    this.simulationActive = false;
    this.isSpraying = false;
    if (this.waterParticles) this.waterParticles.visible = false;

    // Hentikan kamera
    if (this.videoEl?.srcObject) {
      this.videoEl.srcObject.getTracks().forEach(t => t.stop());
    }

    this.renderer?.setAnimationLoop(null);
  }
};

// Expose globally
window.ARCore = ARCore;
