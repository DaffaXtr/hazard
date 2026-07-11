/* ============================================================
   HazardLIDM — PPM Concentration Calculator (js/ppm-calculator.js)
   Mengelola aturan K3, kalkulasi paparan PPM berdasar jarak,
   pengendalian vignette tunnel vision, dan integrasi submit log.
   ============================================================ */

const PPMCalculator = {
  config: null,
  currentPPM: 10.0,
  maxPPM: 10.0,
  barricadesCount: 0,
  elapsedSeconds: 0,
  isFinished: false,

  /**
   * Mulai kalkulator
   */
  start(config) {
    this.config = config;
    this.currentPPM = 10.0;
    this.maxPPM = 10.0;
    this.mitigationActive = false;
    this.elapsedSeconds = 0;
    this.isFinished = false;

    // Reset interface elements
    document.getElementById('hud-ppm').textContent = '10.0';
    document.getElementById('hud-timer').textContent = '00:00';
    
    // Set HUD labels according to gas type
    const mitLabel = document.getElementById('hud-mitigation-label');
    const mitVal = document.getElementById('hud-mitigation-value');
    if (config.gas_type === 'amonia') {
      mitLabel.textContent = 'WATER SPRAY';
      mitVal.textContent = 'LEPAS';
      mitVal.className = 'hud-value text-amber-500';
    } else {
      mitLabel.textContent = 'CAPPING KIT';
      mitVal.textContent = 'JAUH';
      mitVal.className = 'hud-value text-red-500';
    }
    
    document.getElementById('vignette-overlay').style.opacity = '0';
  },

  /**
   * Mengubah status aktif mitigasi K3
   * @param {boolean} active
   */
  setMitigationActive(active) {
    this.mitigationActive = active;
    
    const mitVal = document.getElementById('hud-mitigation-value');
    if (this.config.gas_type === 'amonia') {
      if (active) {
        mitVal.textContent = 'AKTIF';
        mitVal.className = 'hud-value text-teal-400 font-bold';
      } else {
        mitVal.textContent = 'LEPAS';
        mitVal.className = 'hud-value text-amber-500';
      }
    } else {
      if (active) {
        mitVal.textContent = 'TERPASANG';
        mitVal.className = 'hud-value text-teal-400 font-bold';
      }
    }

    // Sinkronisasi kepadatan visual partikel di gas-system
    if (window.GasSystem && window.GasSystem.setMitigationActive) {
      window.GasSystem.setMitigationActive(active);
    }
  },

  /**
   * Update konsentrasi PPM per frame
   * @param {number} deltaTime - selisih detik
   * @param {number} distanceToSource - jarak pengguna/kamera ke tabung bocor (meter)
   */
  update(deltaTime, distanceToSource) {
    if (this.isFinished) return;

    // 1. Durasi simulasi berjalan
    this.elapsedSeconds += deltaTime;
    
    // Format timer MM:SS
    const minutes = Math.floor(this.elapsedSeconds / 60);
    const seconds = Math.floor(this.elapsedSeconds % 60);
    document.getElementById('hud-timer').textContent = 
      `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

    // Update status jarak mitigasi Klorin jika belum terpasang
    if (this.config.gas_type === 'klorin' && !this.mitigationActive) {
      const mitVal = document.getElementById('hud-mitigation-value');
      if (distanceToSource < 1.5) {
        mitVal.textContent = 'DEKAT - TAP';
        mitVal.className = 'hud-value text-yellow-400 font-bold animate-pulse';
      } else {
        mitVal.textContent = 'JAUH';
        mitVal.className = 'hud-value text-red-500';
      }
    }

    // 2. Hitung Emisi Gas & Efek Mitigasi K3
    const baseEmissionRate = this.config.emission_rate;
    const ppeFactor = this.config.is_ppe_correct ? 1.0 : 10.0; // 10x penalty if wrong PPE
    
    let netEmissionRate = baseEmissionRate;
    if (this.mitigationActive) {
      if (this.config.gas_type === 'amonia') {
        netEmissionRate = Math.max(baseEmissionRate - 2.0, 0.1);
      } else {
        netEmissionRate = 0.0; // capping kit halts chlorine emission
      }
    }

    // Kenaikan dasar PPM
    let ppmIncrease = netEmissionRate * ppeFactor * deltaTime * 60; // Dikalikan 60 untuk skala menit

    // 3. Modulasi PPM berdasarkan jarak (Local Exposure)
    let distanceMultiplier = 1.0;
    if (distanceToSource < 1.0) {
      distanceMultiplier = 3.5 / Math.max(distanceToSource, 0.35);
    } else if (distanceToSource < 2.5) {
      distanceMultiplier = 1.8 / distanceToSource;
    } else {
      distanceMultiplier = 0.8 / Math.max(distanceToSource - 1.5, 0.1);
    }

    // Terapkan modulasi jarak ke konsentrasi PPM
    this.currentPPM += ppmIncrease * distanceMultiplier;
    
    // Jika mitigasi aktif, PPM perlahan turun
    if (this.mitigationActive) {
      if (this.config.gas_type === 'amonia') {
        this.currentPPM -= 1.8 * 60 * deltaTime; // air melarutkan gas
      } else {
        this.currentPPM -= 0.6 * 60 * deltaTime; // dispersi setelah dicapping
      }
    }

    this.currentPPM = Math.max(this.currentPPM, 1.0); // ambient minimum 1.0 PPM

    // Simpan PPM tertinggi
    if (this.currentPPM > this.maxPPM) {
      this.maxPPM = this.currentPPM;
    }

    // Tampilkan nilai PPM di HUD
    document.getElementById('hud-ppm').textContent = this.currentPPM.toFixed(1);

    // 4. Hitung Efek Vignette Tunnel Vision
    // Menghitamnya sudut pandang jika PPM mendekati ambang batas kritis fatal
    const fatalLimit = this.config.max_ppm_limit;
    const exposureRatio = this.currentPPM / fatalLimit;
    
    const vignetteOverlay = document.getElementById('vignette-overlay');
    vignetteOverlay.style.opacity = Math.min(exposureRatio * 0.95, 1.0).toString();

    // 5. Evaluasi Batas Ambang K3 (Fatal vs Survival)
    if (this.currentPPM >= fatalLimit) {
      const gasName = this.config.gas_type === 'amonia' ? 'Amonia (NH₃)' : 'Klorin (Cl₂)';
      const reason = `Konsentrasi gas ${gasName} terhirup mencapai ${Math.round(this.currentPPM)} PPM, melebihi batas fatal paparan jangka pendek (IDLH) K3 sebesar ${fatalLimit} PPM. Pengguna kehilangan kesadaran karena keracunan akut.`;
      this.finishSimulation('failed', reason);
    } 
    // Batas waktu bertahan simulasi adalah 120 detik (~2 menit) untuk membuktikan mitigasi berhasil
    else if (this.elapsedSeconds >= 120) {
      this.finishSimulation('survived');
    }
  },

  /**
   * Menyelesaikan jalannya simulasi dan menampilkan modal hasil
   */
  finishSimulation(status, failureReason = null) {
    this.isFinished = true;

    // Hentikan Loop render/navigasi Three.js
    if (window.ARCore && window.ARCore.stopSimulation) {
      window.ARCore.stopSimulation();
    }

    // Sembunyikan instruksi HUD
    document.getElementById('ar-instructions').style.display = 'none';

    // Konfigurasi Modal Hasil
    const resultOverlay = document.getElementById('result-overlay');
    const resultIcon = document.getElementById('result-icon-container');
    const resultTitle = document.getElementById('result-title');
    const resultSubtitle = document.getElementById('result-subtitle');
    const reasonBox = document.getElementById('res-failure-reason-box');

    // Mengisi detail metrik log hasil
    document.getElementById('res-gas-type').textContent = this.config.gas_type === 'amonia' ? 'Amonia (NH₃)' : 'Klorin (Cl₂)';
    document.getElementById('res-duration').textContent = `${Math.round(this.elapsedSeconds)} Detik`;
    document.getElementById('res-ppe-selected').textContent = this.config.ppe_selected;
    document.getElementById('res-mitigation').textContent = this.config.gas_type === 'amonia' ? 'Water Spray' : 'Capping Kit';

    if (status === 'survived') {
      // Sukses bertahan
      resultTitle.textContent = 'Simulasi Berhasil!';
      resultSubtitle.textContent = 'Anda sukses melakukan mitigasi kebocoran gas berbahaya secara K3.';
      resultTitle.style.color = '#64FFB4'; // emerald green
      resultIcon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-teal-500 bg-teal-500/10 text-teal-400';
      resultIcon.innerHTML = '<i data-lucide="check" class="w-8 h-8"></i>';
      reasonBox.classList.add('hidden');
    } else {
      // Gagal / Fatal
      resultTitle.textContent = 'Simulasi Gagal (Fatal)';
      resultSubtitle.textContent = 'Tindakan mitigasi lambat atau paparan gas terlalu tinggi.';
      resultTitle.style.color = '#FF4444'; // rose red
      resultIcon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-rose-500 bg-rose-500/10 text-rose-450';
      resultIcon.innerHTML = '<i data-lucide="skull" class="w-8 h-8"></i>';
      
      document.getElementById('res-failure-reason').textContent = failureReason;
      reasonBox.classList.remove('hidden');
    }

    resultOverlay.style.display = 'flex';
    lucide.createIcons();

    // Bind Event klik tombol submit log
    const submitBtn = document.getElementById('btn-submit-log');
    // Bersihkan listener sebelumnya jika ada
    const newSubmitBtn = submitBtn.cloneNode(true);
    submitBtn.parentNode.replaceChild(newSubmitBtn, submitBtn);

    const isPractice = this.config.is_practice;

    if (isPractice) {
      newSubmitBtn.innerHTML = '<i data-lucide="check-circle" class="w-5 h-5"></i><span>Selesai Latihan & Kembali</span>';
      
      // Sembunyikan tombol "Tolak & Kembali" jika latihan
      const cancelButton = newSubmitBtn.nextElementSibling;
      if (cancelButton) {
        cancelButton.style.display = 'none';
      }
      
      lucide.createIcons();
      
      newSubmitBtn.addEventListener('click', () => {
        window.location.href = 'dashboard.html';
      });
    } else {
      newSubmitBtn.addEventListener('click', async () => {
        newSubmitBtn.disabled = true;
        newSubmitBtn.innerHTML = '<span>Mengirim Laporan...</span>';

        const payload = {
          gas_type: this.config.gas_type,
          ppe_selected: this.config.ppe_selected,
          mitigation_action: this.config.mitigation_action,
          duration: Math.round(this.elapsedSeconds),
          max_ppm: Math.round(this.maxPPM),
          final_ppm: Math.round(this.currentPPM),
          status: status,
          failure_reason: failureReason
        };

        try {
          await API.simulation.submit(payload);
          window.location.href = 'dashboard.html';
        } catch (err) {
          alert(`Gagal mengirim data simulasi: ${err.message}`);
          newSubmitBtn.disabled = false;
          newSubmitBtn.innerHTML = '<i data-lucide="upload-cloud" class="w-5 h-5"></i><span>Kirim Laporan Simulasi</span>';
          lucide.createIcons();
        }
      });
    }
  }
};

// Expose globally
window.PPMCalculator = PPMCalculator;
