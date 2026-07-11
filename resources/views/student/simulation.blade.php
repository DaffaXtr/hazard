<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Simulasi Mitigasi Gas K3 - HazardLIDM</title>
  
  <!-- CSS Stylesheet -->
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Three.js Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <!-- OrbitControls for Non-AR Fallback -->
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
</head>
<body class="simulation-page">

  <!-- CONFIGURATION OVERLAY (Choose gas & APD before simulation starts) -->
  <div id="config-overlay" class="modal-overlay active" style="z-index: 100; backdrop-filter: blur(12px);">
    <div class="modal-content text-center p-6 md:p-8" style="max-width: 480px; background: rgba(8, 13, 10, 0.95); border: 1px solid var(--color-border-hover);">
      <div class="p-3 bg-gradient-to-tr from-teal-500 to-emerald-500 rounded-2xl shadow-xl shadow-teal-500/10 mb-4 inline-flex mx-auto">
        <i data-lucide="shield-alert" class="w-8 h-8 text-slate-950 stroke-[2.5]"></i>
      </div>
      
      <!-- Step 1: Select Gas (Choose Gas) -->
      <div id="select-gas-step">
        <h2 class="hero-title mb-2" style="font-size: 1.5rem;">Skenario Kebocoran Gas</h2>
        <p class="text-slate-400 text-xs mb-6">Pilih jenis gas berbahaya untuk memulai simulasi penanganan darurat K3.</p>
        
        <div class="flex flex-col gap-3">
          <!-- Amonia Card Option -->
          <button type="button" id="btn-select-amonia" class="btn w-full text-left p-4 rounded-2xl border hover:border-amonia transition-all flex items-center justify-between" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm" style="color: var(--color-amonia)">Amonia (NH₃)</h4>
              <p class="text-[10px] text-slate-550 mt-0.5">Ringan, kuning-hijau pudar, batas fatal 300 PPM</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-yellow-500/10 text-yellow-400 rounded-md">Batas: 300 PPM</span>
          </button>

          <!-- Klorin Card Option -->
          <button type="button" id="btn-select-klorin" class="btn w-full text-left p-4 rounded-2xl border hover:border-klorin transition-all flex items-center justify-between" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm" style="color: var(--color-primary)">Klorin (Cl₂)</h4>
              <p class="text-[10px] text-slate-550 mt-0.5">Berat (di lantai), hijau pekat, batas fatal 10 PPM</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-rose-500/10 text-rose-450 rounded-md">Batas: 10 PPM</span>
          </button>
        </div>
      </div>

      <!-- Step 2: Select APD/PPE -->
      <div id="select-ppe-step" style="display: none;">
        <h2 class="hero-title mb-2" style="font-size: 1.5rem;">Pilih Alat Pelindung Diri (APD)</h2>
        <p class="text-slate-400 text-xs mb-6">Pilih APD yang paling sesuai untuk keselamatan Anda.</p>
        
        <!-- APD Options for Amonia -->
        <div id="ppe-options-amonia" class="flex flex-col gap-3" style="display: none;">
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-teal-500/40 transition-all flex items-center justify-between" data-ppe="Respirator Full-Face (Filter K)" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">Respirator Full-Face (Filter K)</h4>
              <p class="text-[10px] text-teal-400 mt-0.5">Filter khusus Amonia & gas alkali</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-teal-500/10 text-teal-400 rounded-md">Direkomendasikan</span>
          </button>
          
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-rose-500/40 transition-all flex items-center justify-between" data-ppe="Masker Bedah" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">Masker Bedah</h4>
              <p class="text-[10px] text-rose-400 mt-0.5">Hanya menyaring partikel debu/cairan (Tidak Layak)</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-rose-500/10 text-rose-450 rounded-md">Bahaya</span>
          </button>
          
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-rose-500/40 transition-all flex items-center justify-between" data-ppe="Respirator Half-Mask (Filter A)" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">Respirator Half-Mask (Filter A)</h4>
              <p class="text-[10px] text-rose-400 mt-0.5">Khusus gas organik/uap pelarut (Tidak Layak)</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-rose-500/10 text-rose-450 rounded-md">Bahaya</span>
          </button>
        </div>

        <!-- APD Options for Klorin -->
        <div id="ppe-options-klorin" class="flex flex-col gap-3" style="display: none;">
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-teal-500/40 transition-all flex items-center justify-between" data-ppe="SCBA + Hazmat Level A" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">SCBA + Hazmat Level A</h4>
              <p class="text-[10px] text-teal-400 mt-0.5">Pelindung pernapasan mandiri & baju kedap gas</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-teal-500/10 text-teal-400 rounded-md">Direkomendasikan</span>
          </button>
          
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-rose-500/40 transition-all flex items-center justify-between" data-ppe="Masker Bedah" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">Masker Bedah</h4>
              <p class="text-[10px] text-rose-400 mt-0.5">Hanya menyaring partikel debu/cairan (Tidak Layak)</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-rose-500/10 text-rose-450 rounded-md">Bahaya</span>
          </button>
          
          <button type="button" class="btn ppe-opt-btn w-full text-left p-4 rounded-2xl border hover:border-rose-500/40 transition-all flex items-center justify-between" data-ppe="Respirator Full-Face (Filter K)" style="background: rgba(255,255,255,0.02); height: 75px;">
            <div>
              <h4 class="font-bold text-white text-sm">Respirator Full-Face (Filter K)</h4>
              <p class="text-[10px] text-rose-400 mt-0.5">Khusus Amonia, tidak cocok untuk Klorin (Tidak Layak)</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-rose-500/10 text-rose-450 rounded-md">Bahaya</span>
          </button>
        </div>

        <button type="button" id="btn-back-to-gas" class="btn btn-ghost mt-5 text-xs inline-flex items-center gap-1.5" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; height: 40px; justify-content: center; width: 100%;">
          <i data-lucide="arrow-left" class="w-4 h-4"></i>
          <span>Kembali ke Pilih Gas</span>
        </button>
      </div>

      <!-- Step 3: Auto-start from Dashboard -->
      <div id="auto-start-step" style="display: none;">
        <h2 class="hero-title mb-2" style="font-size: 1.5rem;">Skenario Siap</h2>
        <p class="text-slate-400 text-xs mb-6">Skenario praktikum telah disiapkan dari Dashboard. Silakan klik tombol di bawah untuk mengaktifkan kamera AR.</p>
        <button type="button" id="btn-auto-start-ar" class="btn btn-primary w-full justify-center" style="height: 52px; font-size: 1rem; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 8px;">
          <i data-lucide="play" class="w-5 h-5"></i>
          <span>Mulai Sesi AR</span>
        </button>
      </div>

      <div class="mt-6 border-t border-slate-900 pt-4 flex justify-between items-center text-[10px] text-slate-550">
        <span>Pengujian Teori Pre-Test: <strong class="text-primary font-bold" id="pretest-badge">-</strong></span>
        <a href="dashboard.html" class="hover:text-white transition-colors">Batal</a>
      </div>
    </div>
  </div>

  <!-- AR WORKSPACE INTERFACE -->
  <!-- WebXR camera stream backdrop (hidden if fallback is active) -->
  <video id="ar-video" autoplay playsinline style="display: none;"></video>
  
  <!-- WebGL Canvas for Three.js rendering overlay -->
  <canvas id="three-canvas"></canvas>

  <!-- Tap Interaction Layer -->
  <div id="interaction-layer"></div>

  <!-- Tunnel Vision Opacity Vignette Overlay -->
  <div id="vignette-overlay"></div>

  <!-- HUD Control panel -->
  <div class="ar-hud">
    <div class="hud-item hud-item--ppm">
      <span class="hud-label">KONSENTRASI GAS</span>
      <p class="hud-value"><span id="hud-ppm">0.0</span> <span class="text-xs font-medium text-slate-450">PPM</span></p>
    </div>
    
    <div class="hud-item hud-item--timer">
      <span class="hud-label">DURASI</span>
      <p class="hud-value" id="hud-timer">00:00</p>
    </div>

    <div class="hud-item hud-item--mitigation">
      <span class="hud-label" id="hud-mitigation-label">MITIGASI K3</span>
      <p class="hud-value" style="font-size: 0.95rem; line-height: 1.8;" id="hud-mitigation-value">READY</p>
    </div>
  </div>

  <!-- HUD Instruction overlay -->
  <div class="barricade-indicator" id="ar-instructions">
    <div class="barricade-icon animate-pulse" id="instruction-icon">📱</div>
    <span id="instruction-text">Mendeteksi permukaan... Gerakkan ponsel Anda ke kiri dan kanan.</span>
  </div>

  <!-- Mode Fallback Indicator (Floating badge in corner) -->
  <div id="mode-badge" class="fixed top-4 left-4 z-40 bg-slate-950/80 border border-slate-800/80 rounded-xl px-3 py-1.5 flex items-center gap-2 text-xs font-semibold">
    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse" id="mode-dot"></span>
    <span id="mode-text">Mode WebXR AR</span>
  </div>

  <!-- SIMULATION RESULT MODAL OVERLAY (Survived / Failed) -->
  <div id="result-overlay" class="modal-overlay" style="z-index: 120; display: none; backdrop-filter: blur(16px);">
    <div class="modal-content text-center p-6 md:p-8" style="max-width: 440px; background: rgba(8, 13, 10, 0.96); border: 1px solid var(--color-border-hover);">
      
      <!-- Result Icon Circle -->
      <div id="result-icon-container" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5 border-4">
        <!-- SVG icon inserted by JS -->
      </div>

      <h2 class="hero-title mb-1" id="result-title" style="font-size: 1.7rem;">Berhasil Bertahan</h2>
      <p class="text-slate-400 text-xs mb-6" id="result-subtitle">Anda berhasil menahan laju emisi gas virtual.</p>

      <!-- Logs info grid -->
      <div class="grid grid-cols-2 gap-3 text-left mb-6">
        <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl">
          <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Metrik Gas</span>
          <p class="font-bold text-white text-sm mt-0.5" id="res-gas-type">Amonia</p>
        </div>
        <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl">
          <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Durasi Simulasi</span>
          <p class="font-bold text-white text-sm mt-0.5" id="res-duration">120 Detik</p>
        </div>
        <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl">
          <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">APD Dipilih</span>
          <p class="font-bold text-white text-xs mt-0.5 truncate" id="res-ppe-selected">-</p>
        </div>
        <div class="p-3 bg-slate-950/60 border border-slate-850 rounded-xl">
          <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Aksi Mitigasi</span>
          <p class="font-bold text-white text-xs mt-0.5 truncate" id="res-mitigation">-</p>
        </div>
      </div>

      <!-- Failure Reason message if any -->
      <div id="res-failure-reason-box" class="p-3.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-xs font-semibold mb-6 text-left hidden">
        <strong>Penyebab Fatal:</strong> <span id="res-failure-reason">Paparan gas kritis.</span>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col gap-2.5">
        <button type="button" id="btn-submit-log" class="btn btn-primary w-full justify-center">
          <i data-lucide="upload-cloud" class="w-5 h-5"></i>
          <span>Kirim Laporan Simulasi</span>
        </button>
        <a href="dashboard.html" class="btn btn-ghost w-full justify-center">Tolak & Kembali</a>
      </div>

    </div>
  </div>

  <!-- API Integration Client -->
  <script src="js/api.js"></script>
  
  <!-- Simulation engine modules -->
  <script src="js/ppm-calculator.js"></script>
  <script src="js/gas-system.js"></script>
  <script src="js/ar-core.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // 1. Authenticate check
      if (!API.isAuthenticated()) {
        window.location.href = 'index.html';
        return;
      }

      // Check if there is a config passed from the dashboard wizard
      const autoConfigRaw = localStorage.getItem('active_simulation_config');
      if (autoConfigRaw) {
        const autoConfig = JSON.parse(autoConfigRaw);
        
        // Show auto-start step in config overlay
        document.getElementById('select-gas-step').style.display = 'none';
        document.getElementById('select-ppe-step').style.display = 'none';
        document.getElementById('auto-start-step').style.display = 'block';

        document.getElementById('btn-auto-start-ar').onclick = () => {
          localStorage.removeItem('active_simulation_config');
          
          // Hide config overlay
          const overlay = document.getElementById('config-overlay');
          overlay.classList.remove('active');
          overlay.style.display = 'none';

          window.SimulationConfig = {
            gas_type: autoConfig.gas_type,
            ppe_selected: autoConfig.ppe_selected,
            is_ppe_correct: autoConfig.is_ppe_correct,
            mitigation_action: autoConfig.mitigation_action,
            max_ppm_limit: autoConfig.max_ppm_limit,
            mitigation_factor: autoConfig.mitigation_factor,
            emission_rate: autoConfig.emission_rate,
            is_practice: autoConfig.is_practice
          };
          
          // Call main ARCore setup inside the user click gesture context!
          if (window.ARCore && window.ARCore.init) {
            window.ARCore.init();
          }
        };
        return;
      }

      // Display pretest score in config Overlay
      const score = localStorage.getItem('hazard_pretest_score') || '-';
      document.getElementById('pretest-badge').textContent = `${score}/100`;

      // 2. Select gas and APD event listeners
      let selectedGas = 'amonia';
      
      document.getElementById('btn-select-amonia').addEventListener('click', () => showPpeStep('amonia'));
      document.getElementById('btn-select-klorin').addEventListener('click', () => showPpeStep('klorin'));
      document.getElementById('btn-back-to-gas').addEventListener('click', showGasStep);

      function showPpeStep(gasType) {
        selectedGas = gasType;
        document.getElementById('select-gas-step').style.display = 'none';
        document.getElementById('select-ppe-step').style.display = 'block';
        
        if (gasType === 'amonia') {
          document.getElementById('ppe-options-amonia').style.display = 'flex';
          document.getElementById('ppe-options-klorin').style.display = 'none';
        } else {
          document.getElementById('ppe-options-amonia').style.display = 'none';
          document.getElementById('ppe-options-klorin').style.display = 'flex';
        }
      }

      function showGasStep() {
        document.getElementById('select-ppe-step').style.display = 'none';
        document.getElementById('select-gas-step').style.display = 'block';
      }

      // Bind dynamic PPE clicks
      document.querySelectorAll('.ppe-opt-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const ppeSelected = btn.getAttribute('data-ppe');
          startSimulation(selectedGas, ppeSelected);
        });
      });

      function startSimulation(gasType, ppeSelected) {
        // Hide config overlay
        document.getElementById('config-overlay').classList.remove('active');
        
        // Define correct APD
        const isPpeCorrect = (gasType === 'amonia' && ppeSelected === 'Respirator Full-Face (Filter K)') || 
                             (gasType === 'klorin' && ppeSelected === 'SCBA + Hazmat Level A');
        
        // Initialize simulation components
        window.SimulationConfig = {
          gas_type: gasType,
          ppe_selected: ppeSelected,
          is_ppe_correct: isPpeCorrect,
          mitigation_action: gasType === 'amonia' ? 'water_spray' : 'capping_kit',
          max_ppm_limit: gasType === 'amonia' ? 300 : 10, // Klorin IDLH limits are lower/deadlier (10 PPM)
          mitigation_factor: gasType === 'amonia' ? 25 : 30, 
          emission_rate: gasType === 'amonia' ? 2.5 : 0.85,
          is_practice: true
        };
        
        // Call main ARCore setup
        if (window.ARCore && window.ARCore.init) {
          window.ARCore.init();
        }
      }
    });
  </script>
</body>
</html>
