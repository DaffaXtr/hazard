<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Notifikasi - HazardLIDM</title>
  
  <!-- CSS Stylesheet -->
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    body {
      background-color: var(--color-bg);
      color: var(--color-text);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .app-container {
      min-height: 100vh;
      width: 100vw;
      padding: var(--spacing-2xl);
      display: flex;
      flex-direction: column;
      gap: var(--spacing-xl);
      background: radial-gradient(circle at 50% 20%, rgba(100, 255, 180, 0.02) 0%, transparent 50%), var(--color-bg);
      box-sizing: border-box;
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid var(--color-border);
      padding-bottom: var(--spacing-lg);
      margin-bottom: var(--spacing-md);
    }

    .header-title-area {
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: var(--radius-md);
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--color-border);
      color: var(--color-text-muted);
      transition: all var(--transition-fast);
      cursor: pointer;
    }

    .back-btn:hover {
      background: var(--color-primary-dim);
      border-color: var(--color-primary);
      color: var(--color-primary);
    }

    .header-title-area h1 {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      font-weight: 850;
      color: #FFF;
      margin: 0;
    }

    .notification-list {
      display: flex;
      flex-direction: column;
      gap: var(--spacing-md);
      max-width: 800px;
      width: 100%;
      margin: 0 auto;
    }

    .notif-card {
      background: rgba(13, 21, 16, 0.7);
      backdrop-filter: blur(12px);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: var(--spacing-lg);
      display: flex;
      gap: var(--spacing-md);
      position: relative;
      overflow: hidden;
      transition: transform var(--transition-fast), border-color var(--transition-fast);
    }

    .notif-card:hover {
      border-color: var(--color-border-hover);
      transform: translateY(-2px);
    }

    .notif-card::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 4px;
    }

    /* Types of notifications */
    .notif-card.info::before { background: var(--color-primary); }
    .notif-card.warning::before { background: var(--color-warning); }
    .notif-card.danger::before { background: var(--color-danger); }
    .notif-card.success::before { background: #A8FF78; }

    .notif-icon-box {
      width: 40px;
      height: 40px;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .notif-card.info .notif-icon-box { background: rgba(100, 255, 180, 0.1); color: var(--color-primary); }
    .notif-card.warning .notif-icon-box { background: rgba(255, 184, 48, 0.1); color: var(--color-warning); }
    .notif-card.danger .notif-icon-box { background: rgba(255, 68, 68, 0.1); color: var(--color-danger); }
    .notif-card.success .notif-icon-box { background: rgba(168, 255, 120, 0.1); color: #A8FF78; }

    .notif-content {
      flex: 1;
    }

    .notif-content h3 {
      font-size: 0.95rem;
      font-weight: 750;
      color: #FFF;
      margin-bottom: 0.25rem;
    }

    .notif-content p {
      font-size: 0.82rem;
      color: var(--color-text-muted);
      line-height: 1.4;
      margin-bottom: 0.5rem;
    }

    .notif-time {
      font-size: 0.72rem;
      color: rgba(232, 245, 238, 0.35);
      font-family: var(--font-mono);
    }

    /* Unread Indicator */
    .unread-dot {
      position: absolute;
      right: var(--spacing-lg);
      top: var(--spacing-lg);
      width: 8px;
      height: 8px;
      background: var(--color-primary);
      border-radius: 50%;
    }

    .empty-state {
      text-align: center;
      padding: var(--spacing-3xl) var(--spacing-xl);
      color: var(--color-text-muted);
    }

    .empty-state i {
      color: rgba(255, 255, 255, 0.1);
      margin-bottom: var(--spacing-md);
    }
  </style>
</head>
<body>

  <div class="app-container">
    
    <!-- HEADER -->
    <header class="header-row">
      <div class="header-title-area">
        <a href="dashboard.html" class="back-btn" aria-label="Kembali ke Dashboard">
          <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1>Notifikasi</h1>
      </div>
      <div>
        <button type="button" class="btn btn-ghost btn-sm" id="btn-mark-read" style="border-radius: 8px; font-size: 0.75rem; height: 36px; padding: 0 1rem;">
          <i data-lucide="check-check" class="w-4 h-4"></i>
          <span>Tandai Semua Dibaca</span>
        </button>
      </div>
    </header>

    <!-- NOTIFICATION LIST -->
    <div class="notification-list">
      
      <!-- Notification Item 1 (Danger) -->
      <div class="notif-card danger">
        <div class="unread-dot"></div>
        <div class="notif-icon-box">
          <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </div>
        <div class="notif-content">
          <h3>Skenario Klorin Terbuka!</h3>
          <p>Tugas K3 baru ditambahkan. Skenario Klorin (Cl₂) dengan mitigasi menggunakan Capping Kit wajib diselesaikan sebelum praktikum berakhir.</p>
          <span class="notif-time">Baru Saja</span>
        </div>
      </div>

      <!-- Notification Item 2 (Success) -->
      <div class="notif-card success">
        <div class="notif-icon-box">
          <i data-lucide="check-circle" class="w-5 h-5"></i>
        </div>
        <div class="notif-content">
          <h3>Simulasi Amonia Berhasil</h3>
          <p>Selamat! Anda berhasil bertahan dalam Skenario Amonia dengan tingkat paparan aman dan menggunakan APD Full-Face sesuai SOP.</p>
          <span class="notif-time">2 Jam Yang Lalu</span>
        </div>
      </div>

      <!-- Notification Item 3 (Info) -->
      <div class="notif-card info">
        <div class="notif-icon-box">
          <i data-lucide="info" class="w-5 h-5"></i>
        </div>
        <div class="notif-content">
          <h3>Papan Pengumuman K3 Diperbarui</h3>
          <p>Fokus praktikum minggu ini: Pemahaman fungsi filter Cartridge untuk gas asam korosif. Silakan pelajari di Ensiklopedi Karakteristik Gas.</p>
          <span class="notif-time">1 Hari Yang Lalu</span>
        </div>
      </div>

      <!-- Notification Item 4 (Warning) -->
      <div class="notif-card warning">
        <div class="notif-icon-box">
          <i data-lucide="shield-alert" class="w-5 h-5"></i>
        </div>
        <div class="notif-content">
          <h3>Nilai Pre-Test Rendah</h3>
          <p>Skor pre-test terakhir Anda kurang dari 70. Silakan tinjau kembali materi mitigasi darurat amonia sebelum memulai ujian berikutnya.</p>
          <span class="notif-time">3 Hari Yang Lalu</span>
        </div>
      </div>

    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      lucide.createIcons();

      // Simple click to mark read helper
      document.getElementById('btn-mark-read').addEventListener('click', () => {
        document.querySelectorAll('.unread-dot').forEach(dot => {
          dot.style.display = 'none';
        });
        // Clear local storage notification indicator if needed
        localStorage.setItem('hazard_notifications_read', 'true');
      });
    });
  </script>
</body>
</html>
