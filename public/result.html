<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hasil Simulasi - HazardLIDM</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(ellipse at 50% 0%, rgba(16, 185, 129, 0.08) 0%, transparent 60%),
                  #080d0a;
      padding: 24px;
    }

    .result-card {
      width: 100%;
      max-width: 480px;
      background: rgba(8, 13, 10, 0.95);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 24px;
      padding: 40px 32px;
      text-align: center;
      animation: slideUp 0.5s cubic-bezier(0.16,1,0.3,1);
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(32px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .result-icon {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 24px;
      border: 3px solid;
    }

    .result-icon.survived {
      border-color: #10b981;
      background: rgba(16,185,129,0.1);
      color: #10b981;
    }

    .result-icon.failed {
      border-color: #ef4444;
      background: rgba(239,68,68,0.1);
      color: #ef4444;
    }

    .result-title {
      font-size: 1.8rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 8px;
    }

    .result-title.survived { color: #34d399; }
    .result-title.failed   { color: #f87171; }

    .result-subtitle {
      color: #64748b;
      font-size: 0.85rem;
      margin-bottom: 32px;
      line-height: 1.5;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      text-align: left;
      margin-bottom: 24px;
    }

    .stat-card {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 14px;
      padding: 14px;
    }

    .stat-label {
      display: block;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #475569;
      margin-bottom: 6px;
    }

    .stat-value {
      font-size: 0.9rem;
      font-weight: 700;
      color: #f1f5f9;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .failure-box {
      background: rgba(239,68,68,0.08);
      border: 1px solid rgba(239,68,68,0.2);
      border-radius: 14px;
      padding: 14px 16px;
      text-align: left;
      font-size: 0.78rem;
      color: #fca5a5;
      margin-bottom: 24px;
      line-height: 1.5;
    }

    .failure-box strong {
      color: #ef4444;
      display: block;
      margin-bottom: 4px;
    }

    .btn-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .btn-primary-full {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 52px;
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      border: none;
      border-radius: 14px;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: opacity 0.2s, transform 0.15s;
    }

    .btn-primary-full:hover {
      opacity: 0.9;
      transform: translateY(-1px);
    }

    .btn-ghost-full {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 44px;
      background: transparent;
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 14px;
      color: #64748b;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: border-color 0.2s, color 0.2s;
    }

    .btn-ghost-full:hover {
      border-color: rgba(255,255,255,0.25);
      color: #f1f5f9;
    }

    .badge-practice {
      display: inline-block;
      background: rgba(99,102,241,0.15);
      border: 1px solid rgba(99,102,241,0.3);
      color: #a5b4fc;
      font-size: 10px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 100px;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="result-card">
    <!-- Badge mode -->
    <div class="badge-practice" id="badge-mode">LATIHAN</div>

    <!-- Icon -->
    <div class="result-icon" id="result-icon">
      <i data-lucide="check" style="width:32px;height:32px;"></i>
    </div>

    <!-- Title -->
    <h1 class="result-title" id="result-title">Simulasi Berhasil!</h1>
    <p class="result-subtitle" id="result-subtitle">
      Anda sukses melakukan mitigasi kebocoran gas berbahaya secara K3.
    </p>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <span class="stat-label">Jenis Gas</span>
        <p class="stat-value" id="stat-gas">—</p>
      </div>
      <div class="stat-card">
        <span class="stat-label">Durasi Simulasi</span>
        <p class="stat-value" id="stat-duration">—</p>
      </div>
      <div class="stat-card">
        <span class="stat-label">APD Dipilih</span>
        <p class="stat-value" id="stat-ppe">—</p>
      </div>
      <div class="stat-card">
        <span class="stat-label">PPM Tertinggi</span>
        <p class="stat-value" id="stat-ppm">—</p>
      </div>
    </div>

    <!-- Failure reason (hanya jika gagal) -->
    <div class="failure-box" id="failure-box" style="display:none;">
      <strong>⚠️ Penyebab Kegagalan:</strong>
      <span id="failure-reason">—</span>
    </div>

    <!-- Actions -->
    <div class="btn-group">
      <button class="btn-primary-full" id="btn-back" onclick="window.location.href='dashboard.html'">
        <i data-lucide="home" style="width:18px;height:18px;"></i>
        Kembali ke Dashboard
      </button>
      <button class="btn-ghost-full" id="btn-retry" onclick="window.history.back()">
        <i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i>
        Coba Lagi
      </button>
    </div>
  </div>

  <script>
    // Ambil hasil dari localStorage
    const raw = localStorage.getItem('simulation_result');
    if (!raw) {
      window.location.href = 'dashboard.html';
    } else {
      const data = JSON.parse(raw);
      localStorage.removeItem('simulation_result');

      const survived = data.status === 'survived';

      // Icon & warna
      const icon = document.getElementById('result-icon');
      icon.className = `result-icon ${survived ? 'survived' : 'failed'}`;
      icon.innerHTML = survived
        ? '<i data-lucide="shield-check" style="width:32px;height:32px;"></i>'
        : '<i data-lucide="skull" style="width:32px;height:32px;"></i>';

      // Judul
      const title = document.getElementById('result-title');
      title.textContent  = survived ? 'Simulasi Berhasil!' : 'Simulasi Gagal';
      title.className    = `result-title ${survived ? 'survived' : 'failed'}`;

      document.getElementById('result-subtitle').textContent = survived
        ? 'Anda sukses melakukan mitigasi kebocoran gas berbahaya secara prosedur K3.'
        : 'Tindakan mitigasi terlambat atau paparan gas terlalu tinggi.';

      // Badge latihan / ujian
      document.getElementById('badge-mode').textContent = data.is_practice ? 'LATIHAN' : 'UJIAN RESMI';

      // Stats
      document.getElementById('stat-gas').textContent      = data.gas_type === 'amonia' ? 'Amonia (NH₃)' : 'Klorin (Cl₂)';
      document.getElementById('stat-duration').textContent = `${data.duration} Detik`;
      document.getElementById('stat-ppe').textContent      = data.ppe_selected || '—';
      document.getElementById('stat-ppm').textContent      = `${data.max_ppm} PPM`;

      // Kegagalan
      if (!survived && data.failure_reason) {
        document.getElementById('failure-box').style.display = 'block';
        document.getElementById('failure-reason').textContent = data.failure_reason;
      }
    }

    lucide.createIcons();
  </script>
</body>
</html>
