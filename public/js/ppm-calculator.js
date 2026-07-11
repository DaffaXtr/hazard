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
    this.currentPPM = 1.0;
    this.maxPPM = 1.0;
    this.mitigationActive = false;
    this.elapsedSeconds = 0;
    this.isFinished = false;

    // Reset interface elements
    document.getElementById('hud-ppm').textContent = '1.0';
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
    const ppeFactor = this.config.is_ppe_correct ? 1.0 : 2.0; // 2x penalty jika APD salah (lebih masuk akal)
    
    let netEmissionRate = baseEmissionRate;
    if (this.mitigationActive) {
      if (this.config.gas_type === 'amonia') {
        netEmissionRate = 0.0; // Water spray menghentikan emisi baru
      } else {
        netEmissionRate = 0.0; // capping kit halts chlorine emission
      }
    }

    // Kenaikan PPM per detik (dibatasi agar tidak meledak)
    let ppmIncrease = netEmissionRate * ppeFactor * deltaTime * 10;

    // 3. Modulasi PPM berdasarkan jarak (lebih lunak)
    let distanceMultiplier = 1.0;
    if (distanceToSource < 1.0) {
      distanceMultiplier = 2.0;
    } else if (distanceToSource < 2.0) {
      distanceMultiplier = 1.0;
    } else {
      distanceMultiplier = 0.5;
    }

    // Terapkan modulasi jarak ke konsentrasi PPM
    this.currentPPM += ppmIncrease * distanceMultiplier;
    
    // Jika mitigasi aktif, PPM turun CEPAT (bisa padam ~30 detik)
    if (this.mitigationActive) {
      const decayRate = this.config.gas_type === 'amonia' ? 8.0 : 5.0; // PPM/detik
      this.currentPPM -= decayRate * deltaTime;
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
    // Batas waktu bertahan simulasi adalah 20 detik
    else if (this.elapsedSeconds >= 20) {
      this.finishSimulation('survived');
    }
  },

  /**
   * Menyelesaikan jalannya simulasi dan menampilkan modal hasil
   */
  finishSimulation(status, failureReason = null) {
    this.isFinished = true;

    // Hentikan render loop
    if (window.ARCore?.stopSimulation) {
      window.ARCore.stopSimulation();
    }

    // Simpan hasil ke localStorage
    const result = {
      status,
      failure_reason: failureReason,
      gas_type:       this.config.gas_type,
      ppe_selected:   this.config.ppe_selected,
      mitigation:     this.config.mitigation_action,
      duration:       Math.round(this.elapsedSeconds),
      max_ppm:        Math.round(this.maxPPM),
      final_ppm:      Math.round(this.currentPPM),
      is_practice:    this.config.is_practice
    };
    localStorage.setItem('simulation_result', JSON.stringify(result));

    // Redirect ke halaman hasil
    setTimeout(() => {
      window.location.href = 'result.html';
    }, 400); // sedikit delay agar render stop dahulu
  }

};

// Expose globally
window.PPMCalculator = PPMCalculator;
