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

  <!-- runSimulationInit: dideklarasikan di head agar tersedia saat loadAll() selesai -->
  <script>
    window.runSimulationInit = function() {
      // 1. Authenticate check
      if (typeof API === 'undefined' || !API.isAuthenticated()) {
        window.location.href = 'index.html';
        return;
      }

      // 2. Ambil konfigurasi dari localStorage yang sudah disiapkan dashboard
      const autoConfigRaw = localStorage.getItem('active_simulation_config');
      if (!autoConfigRaw) {
        alert('Silakan pilih skenario simulasi terlebih dahulu dari Dashboard.');
        window.location.href = 'dashboard.html';
        return;
      }

      const autoConfig = JSON.parse(autoConfigRaw);
      localStorage.removeItem('active_simulation_config');

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

      // 3. Jalankan MindAR
      if (window.ARCore && window.ARCore.init) {
        window.ARCore.init();
      } else {
        console.error('ARCore tidak tersedia. Pastikan ar-core.js sudah dimuat.');
      }
    };
  </script>

  <!-- Import Map Polyfill for older browsers (SYNC — must be before importmap!) -->
  <script src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js"></script>

  <!-- Import Map for ES Modules (Three.js only, MindAR dihapus) -->
  <script type="importmap">
    {
      "imports": {
        "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
        "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
      }
    }
  </script>

  <!-- ES Module Loader & Script Bootstrapper -->
  <script type="module">
    import * as THREE from 'three';

    // Bind THREE to window for global access by static scripts
    window.THREE = THREE;
    console.log('[HazardLIDM] Three.js loaded:', THREE.REVISION);

    function loadScript(src) {
      return new Promise((resolve, reject) => {
        const s = document.createElement('script');
        s.src = src;
        s.onload = () => {
          console.log(`[HazardLIDM] Loaded: ${src}`);
          resolve();
        };
        s.onerror = () => reject(new Error(`[HazardLIDM] Failed to load: ${src}`));
        document.head.appendChild(s);
      });
    }

    async function loadAll() {
      try {
        await loadScript('js/api.js');
        await loadScript('js/ppm-calculator.js');
        await loadScript('js/gas-system.js');
        await loadScript('js/ar-core.js');
        console.log('[HazardLIDM] All scripts loaded. Running init...');
        if (typeof window.runSimulationInit === 'function') {
          window.runSimulationInit();
        } else {
          console.error('[HazardLIDM] runSimulationInit is not defined!');
        }
      } catch (err) {
        console.error('[HazardLIDM] Script load failed:', err);
        alert('Gagal memuat modul simulasi. Periksa koneksi internet Anda.');
      }
    }

    // Modul ES sudah defer by default, tapi pastikan DOM ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', loadAll);
    } else {
      loadAll();
    }
  </script>
  
  <style>
    #mindar-container {
      width: 100vw;
      height: 100vh;
      position: fixed;
      inset: 0;
      z-index: 1;
      overflow: hidden;
    }
    .ar-hud {
      z-index: 20 !important;
      position: relative;
    }
    .barricade-indicator {
      z-index: 20 !important;
      position: relative;
    }
    #mode-badge {
      z-index: 40 !important;
    }
    #interaction-layer {
      position: fixed;
      inset: 0;
      z-index: 10;
    }
    #vignette-overlay {
      position: fixed;
      inset: 0;
      z-index: 15;
      pointer-events: none;
    }
    #result-overlay {
      position: fixed !important;
      inset: 0;
      z-index: 999 !important;
      display: none;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(16px);
      background: rgba(0,0,0,0.6);
    }
  </style>
</head>
<body class="simulation-page">



  <!-- AR WORKSPACE INTERFACE (MindAR Container) -->
  <div id="mindar-container"></div>

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
    <span id="instruction-text">Mendeteksi permukaan... Gerakan kamera ke kiri dan ke kanan.</span>
  </div>

  <!-- Mode Fallback Indicator (Floating badge in corner) -->
  <div id="mode-badge" class="fixed top-4 left-4 z-40 bg-slate-950/80 border border-slate-800/80 rounded-xl px-3 py-1.5 flex items-center gap-2 text-xs font-semibold">
    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse" id="mode-dot"></span>
    <span id="mode-text">Mode WebXR AR</span>
  </div>

  <!-- SIMULATION RESULT MODAL OVERLAY (Survived / Failed) -->
  <div id="result-overlay" class="modal-overlay">
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


</body>
</html>
