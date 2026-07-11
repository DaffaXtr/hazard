/* ============================================================
   HazardLIDM — Gas System Shader (js/gas-system.js)
   Sistem partikel gas berbasis GPU GLSL Custom Shader
   untuk visualisasi kepulan gas Amonia & Klorin yang efisien.
   ============================================================ */

const GasSystem = {
  particleGeometry: null,
  particleMaterial: null,
  particlePoints: null,
  leakPosition: null,
  timeElapsed: 0,
  gasType: 'amonia',
  mitigationFactor: 1.0, // dikontrol oleh barricades count untuk mereduksi volume partikel secara dinamis

  /**
   * Inisialisasi Sistem Partikel Gas
   * @param {THREE.Scene} scene
   * @param {THREE.Vector3} position - koordinat tangki gas bocor
   */
  init(scene, position) {
    this.leakPosition = position;
    this.gasType = window.SimulationConfig ? window.SimulationConfig.gas_type : 'amonia';
    this.timeElapsed = 0;
    this.mitigationFactor = 1.0;

    const particleCount = 2000;
    this.particleGeometry = new THREE.BufferGeometry();

    const positions = new Float32Array(particleCount * 3);
    const velocities = new Float32Array(particleCount * 3);
    const sizes = new Float32Array(particleCount);

    // Dapatkan konfigurasi densitas sesuai tipe gas
    // Amonia: gas ringan, mengembang naik dengan cepat
    // Klorin: gas berat, mengendap di bawah menyebar mendatar
    const isAmonia = this.gasType === 'amonia';

    for (let i = 0; i < particleCount; i++) {
      // Posisi awal di lubang tangki bocor (sedikit di atas alas tangki)
      positions[i * 3] = position.x;
      positions[i * 3 + 1] = position.y + 0.45;
      positions[i * 3 + 2] = position.z;

      // Kecepatan semburan acak
      if (isAmonia) {
        // Amonia: Semprotan ke atas, menyebar melebar
        velocities[i * 3] = (Math.random() - 0.5) * 0.4;     // X spread
        velocities[i * 3 + 1] = 0.5 + Math.random() * 0.6;   // Y rise (fast)
        velocities[i * 3 + 2] = (Math.random() - 0.5) * 0.4; // Z spread
      } else {
        // Klorin: Semprotan mendatar berat, sedikit naik lalu mengendap mendatar
        velocities[i * 3] = (Math.random() - 0.5) * 0.8;     // X spread (wide)
        velocities[i * 3 + 1] = 0.1 + Math.random() * 0.25;  // Y rise (slow)
        velocities[i * 3 + 2] = (Math.random() - 0.5) * 0.8; // Z spread (wide)
      }

      // Ukuran partikel acak
      sizes[i] = 12.0 + Math.random() * 18.0;
    }

    this.particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    this.particleGeometry.setAttribute('aVelocity', new THREE.BufferAttribute(velocities, 3));
    this.particleGeometry.setAttribute('aSize', new THREE.BufferAttribute(sizes, 1));

    // Custom Shaders
    const vertexShader = `
      uniform float uTime;
      uniform float uSpeed;
      uniform float uRiseRate;
      uniform float uGasDensity; // 0 untuk Amonia (ringan), 1 untuk Klorin (berat)
      attribute float aSize;
      attribute vec3 aVelocity;
      varying vec3 vPosition;
      varying float vAge;

      // Fungsi Noise Sederhana untuk Turbulensi
      float hash(vec3 p) {
          p = fract(p * 0.3183099 + vec3(0.1, 0.1, 0.1));
          p *= 17.0;
          return fract(p.x * p.y * p.z * (p.x + p.y + p.z));
      }

      void main() {
        // Menghitung posisi partikel berdasarkan kecepatan dan waktu
        vec3 pos = position + aVelocity * uTime * uSpeed;
        
        // Amonia melayang ke atas, Klorin tetap melayang rendah
        if (uGasDensity < 0.5) {
          pos.y += uTime * uRiseRate;
        } else {
          // Klorin mengendap di bawah, menyebar radial horizontal seiring waktu
          pos.y += sin(uTime * 0.5) * 0.1; // sedikit gejolak vertikal rendah
        }

        // Looping partikel jika melampaui batas tinggi/jarak tertentu
        float maxPlumeHeight = 5.0;
        pos.y = mod(pos.y, maxPlumeHeight);

        // Semakin tinggi partikel naik, semakin menyebar melebar secara horizontal
        float heightFactor = pos.y / maxPlumeHeight;
        
        // Tambahkan turbulensi/efek acak (Noise) agar asap bergejolak
        float noise = hash(pos + uTime * 0.5);
        pos.x += sin(uTime + pos.y) * 0.15 * pos.y * noise;
        pos.z += cos(uTime + pos.y) * 0.15 * pos.y * noise;

        vPosition = pos;
        vAge = heightFactor; // Umur relatif partikel [0..1]

        vec4 mvPosition = modelViewMatrix * vec4(pos, 1.0);
        gl_Position = projectionMatrix * mvPosition;
        
        // Ukuran mengecil perlahan seiring bertambah tinggi (memudar)
        gl_PointSize = aSize * (300.0 / -mvPosition.z) * (1.0 - vAge);
      }
    `;

    const fragmentShader = `
      uniform vec3 uColor;
      uniform float uOpacityFactor;
      varying vec3 vPosition;
      varying float vAge;

      void main() {
        // Menggambar lingkaran partikel yang lembut (radial blur)
        float dist = distance(gl_PointCoord, vec2(0.5));
        if (dist > 0.5) discard;

        // Formula alpha decay: memudar di tepi partikel dan memudar di ujung plume (vAge)
        float alpha = smoothstep(0.5, 0.1, dist) * 0.45 * (1.0 - vAge) * uOpacityFactor;
        gl_FragColor = vec4(uColor, alpha);
      }
    `;

    // Tentukan warna gas neon biohazard
    // Amonia: Kuning-Kehijauan (lime/yellowish-green)
    // Klorin: Hijau-Kuningan pekat (deeper green)
    const gasColor = isAmonia ? new THREE.Color('#c8dc50') : new THREE.Color('#64b450');

    this.particleMaterial = new THREE.ShaderMaterial({
      vertexShader: vertexShader,
      fragmentShader: fragmentShader,
      uniforms: {
        uTime: { value: 0.0 },
        uSpeed: { value: 1.0 },
        uRiseRate: { value: isAmonia ? 0.8 : 0.15 },
        uGasDensity: { value: isAmonia ? 0.0 : 1.0 },
        uColor: { value: gasColor },
        uOpacityFactor: { value: 1.0 }
      },
      transparent: true,
      depthWrite: false,
      blending: THREE.NormalBlending
    });

    this.particlePoints = new THREE.Points(this.particleGeometry, this.particleMaterial);
    scene.add(this.particlePoints);
  },

  /**
   * Update parameter shader di loop render
   * @param {number} deltaTime
   */
  update(deltaTime) {
    if (!this.particleMaterial) return;

    this.timeElapsed += deltaTime;
    this.particleMaterial.uniforms.uTime.value = this.timeElapsed;
    
    // Kurangi opasitas partikel secara dinamis jika mitigasi aktif
    this.particleMaterial.uniforms.uOpacityFactor.value = this.mitigationFactor;
  },

  /**
   * Mengubah visual kepulan gas saat mitigasi aktif
   * @param {boolean} active
   */
  setMitigationActive(active) {
    if (active) {
      if (this.gasType === 'amonia') {
        this.mitigationFactor = 0.25; // Reduksi visual asap amonia
      } else {
        this.mitigationFactor = 0.05; // Reduksi visual asap klorin
      }
    } else {
      if (this.gasType === 'amonia') {
        this.mitigationFactor = 1.0; // Amonia kembali bocor jika water spray dilepas
      }
    }
  }
};

// Expose globally
window.GasSystem = GasSystem;
