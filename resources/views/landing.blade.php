<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="description" content="HazardLIDM — Platform simulasi WebAR mitigasi kebocoran gas berbahaya (Amonia & Klorin) untuk mahasiswa K3." />
  <title>HazardLIDM — Simulasi Mitigasi Gas K3</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="landing-page">

  <!-- Background animated particles -->
  <div class="bg-particles" id="bg-particles">
    <div class="particle" style="--x:10%;--y:20%;--size:6px;--delay:0s;--dur:8s;--color:rgba(168,255,120,0.4)"></div>
    <div class="particle" style="--x:80%;--y:15%;--size:4px;--delay:1s;--dur:10s;--color:rgba(100,220,180,0.3)"></div>
    <div class="particle" style="--x:50%;--y:70%;--size:8px;--delay:2s;--dur:7s;--color:rgba(200,255,100,0.35)"></div>
    <div class="particle" style="--x:25%;--y:80%;--size:5px;--delay:0.5s;--dur:9s;--color:rgba(120,255,160,0.4)"></div>
    <div class="particle" style="--x:70%;--y:55%;--size:7px;--delay:3s;--dur:11s;--color:rgba(80,200,140,0.3)"></div>
    <div class="particle" style="--x:90%;--y:85%;--size:4px;--delay:1.5s;--dur:8s;--color:rgba(180,255,80,0.35)"></div>
    <div class="particle" style="--x:15%;--y:50%;--size:6px;--delay:2.5s;--dur:12s;--color:rgba(140,240,200,0.3)"></div>
    <div class="particle" style="--x:60%;--y:30%;--size:5px;--delay:4s;--dur:9s;--color:rgba(100,200,100,0.4)"></div>
  </div>

  <!-- Navbar -->
  <nav class="navbar" id="navbar">
    <div class="nav-brand">
      <div class="nav-logo">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
          <circle cx="16" cy="16" r="14" stroke="url(#grad1)" stroke-width="2" fill="rgba(100,220,150,0.1)"/>
          <path d="M10 22 L16 10 L22 22 Z" fill="url(#grad1)" opacity="0.9"/>
          <circle cx="16" cy="17" r="3" fill="white" opacity="0.7"/>
          <defs>
            <linearGradient id="grad1" x1="0" y1="0" x2="32" y2="32">
              <stop offset="0%" stop-color="#64FFDA"/>
              <stop offset="100%" stop-color="#A8FF78"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      <span class="nav-title">HazardLIDM</span>
    </div>
    <div class="nav-links">
      <a href="#features" class="nav-link">Fitur</a>
      <a href="#about" class="nav-link">Tentang</a>
      <a href="#" class="nav-link nav-link--login" id="btn-navbar-login">Masuk</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-content">
      <div class="hero-badge">
        <span class="badge-dot"></span>
        Simulasi Keselamatan & Kesehatan Kerja
      </div>
      <h1 class="hero-title">
        Hadapi Krisis Gas<br/>
        <span class="hero-gradient-text">Amonia & Klorin</span><br/>
        dalam Realitas Tertambah
      </h1>
      <p class="hero-subtitle">
        Platform simulasi WebAR berbasis AI yang melatih kemampuan mitigasi kebocoran gas berbahaya secara imersif langsung dari browser smartphone Anda — tanpa aplikasi tambahan.
      </p>
      <div class="hero-cta">
        <a href="dashboard.html" class="btn btn-primary" id="btn-mulai-simulasi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="5 3 19 12 5 21 5 3"/>
          </svg>
          Mulai Simulasi
        </a>
        <a href="#features" class="btn btn-ghost">
          Pelajari Lebih Lanjut
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M7 17l9.2-9.2M17 17V7H7"/>
          </svg>
        </a>
      </div>
      <div class="hero-stats">
        <div class="stat-item">
          <span class="stat-value" id="stat-mahasiswa">0</span>
          <span class="stat-label">Mahasiswa Terlatih</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <span class="stat-value" id="stat-simulasi">0</span>
          <span class="stat-label">Simulasi Selesai</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <span class="stat-value" id="stat-survival">0%</span>
          <span class="stat-label">Survival Rate</span>
        </div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="ar-mockup">
        <div class="mockup-phone">
          <div class="mockup-screen">
            <div class="ar-scene">
              <div class="gas-cloud gas-cloud--1"></div>
              <div class="gas-cloud gas-cloud--2"></div>
              <div class="gas-cloud gas-cloud--3"></div>
              <div class="ar-hud">
                <div class="hud-item hud-item--ppm">
                  <span class="hud-label">PPM</span>
                  <span class="hud-value" id="demo-ppm">0</span>
                </div>
                <div class="hud-item hud-item--timer">
                  <span class="hud-label">WAKTU</span>
                  <span class="hud-value">02:30</span>
                </div>
              </div>
              <div class="barricade-indicator">
                <div class="barricade-icon">🛡️</div>
                <span>Ketuk untuk letakkan barikade</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mockup-glow"></div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Mengapa HazardLIDM?</h2>
        <p class="section-subtitle">Teknologi terdepan untuk pelatihan keselamatan industri yang efektif dan terukur</p>
      </div>
      <div class="features-grid">
        <div class="feature-card feature-card--highlight">
          <div class="feature-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
              <line x1="12" y1="22.08" x2="12" y2="12"/>
            </svg>
          </div>
          <h3>WebAR Real-World</h3>
          <p>Simulasi gas langsung di ruangan nyata Anda menggunakan teknologi WebXR. Tidak perlu install aplikasi apapun.</p>
          <div class="feature-tag">Three.js + WebXR</div>
        </div>
        <div class="feature-card">
          <div class="feature-icon feature-icon--amonia">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v4M12 16h.01"/>
            </svg>
          </div>
          <h3>Dua Skenario Gas</h3>
          <p>Amonia (NH₃) dan Klorin (Cl₂) dengan karakteristik penyebaran, warna, dan ambang batas PPM yang berbeda sesuai standar NIOSH.</p>
          <div class="feature-tag">K3 Compliant</div>
        </div>
        <div class="feature-card">
          <div class="feature-icon feature-icon--data">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="18" y1="20" x2="18" y2="10"/>
              <line x1="12" y1="20" x2="12" y2="4"/>
              <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
          </div>
          <h3>Dashboard Analytics</h3>
          <p>Dosen K3 dapat memantau performa mahasiswa secara real-time dengan grafik survival rate, durasi mitigasi, dan tingkat paparan PPM.</p>
          <div class="feature-tag">Laravel + Chart.js</div>
        </div>
        <div class="feature-card">
          <div class="feature-icon feature-icon--tunnel">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="12" r="1"/>
              <circle cx="12" cy="12" r="5"/>
              <circle cx="12" cy="12" r="9"/>
            </svg>
          </div>
          <h3>Efek Tunnel Vision</h3>
          <p>Simulasi fisiologis keracunan gas yang realistis. Layar menghitam dari tepi ke tengah jika PPM melewati ambang batas kritis.</p>
          <div class="feature-tag">GLSL Shader</div>
        </div>
        <div class="feature-card">
          <div class="feature-icon feature-icon--eval">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
          </div>
          <h3>Evaluasi Kuantitatif</h3>
          <p>Rekam metrik kecepatan respons (detik), akurasi penempatan barikade, dan PPM final untuk penilaian kompetensi K3 yang objektif.</p>
          <div class="feature-tag">Data-Driven</div>
        </div>
        <div class="feature-card">
          <div class="feature-icon feature-icon--pre">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
          </div>
          <h3>Pre-test Teori</h3>
          <p>Ujian teori singkat berbasis soal K3 sebelum simulasi AR untuk mengukur pemahaman awal mahasiswa tentang bahaya gas industri.</p>
          <div class="feature-tag">Adaptive Assessment</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gas Types Section -->
  <section class="gas-types" id="about">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Skenario Simulasi</h2>
        <p class="section-subtitle">Dua jenis gas berbahaya industri dengan karakteristik dan prosedur mitigasi yang berbeda</p>
      </div>
      <div class="gas-cards">
        <!-- Amonia Card -->
        <div class="gas-card gas-card--amonia">
          <div class="gas-visual gas-visual--amonia">
            <div class="gas-bubble"></div><div class="gas-bubble"></div><div class="gas-bubble"></div>
            <div class="gas-formula">NH₃</div>
          </div>
          <div class="gas-info">
            <h3 class="gas-name">Amonia (NH₃)</h3>
            <div class="gas-specs">
              <div class="spec-row">
                <span class="spec-label">TLV-TWA</span>
                <span class="spec-value amonia-color">25 PPM</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">IDLH</span>
                <span class="spec-value danger-color">300 PPM</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">Warna Gas</span>
                <span class="spec-value">Kuning Kehijauan Tipis</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">Mitigasi</span>
                <span class="spec-value">Absorben Asam (H₂SO₄)</span>
              </div>
            </div>
            <p class="gas-desc">Gas tidak berwarna dengan bau menyengat khas. Berat lebih ringan dari udara, mengapung ke atas. Iritatif pada saluran pernapasan dan mata.</p>
          </div>
        </div>

        <!-- Klorin Card -->
        <div class="gas-card gas-card--klorin">
          <div class="gas-visual gas-visual--klorin">
            <div class="gas-bubble"></div><div class="gas-bubble"></div><div class="gas-bubble"></div>
            <div class="gas-formula">Cl₂</div>
          </div>
          <div class="gas-info">
            <h3 class="gas-name">Klorin (Cl₂)</h3>
            <div class="gas-specs">
              <div class="spec-row">
                <span class="spec-label">TLV-TWA</span>
                <span class="spec-value klorin-color">0.5 PPM</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">IDLH</span>
                <span class="spec-value danger-color">10 PPM</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">Warna Gas</span>
                <span class="spec-value">Hijau Kekuningan Pekat</span>
              </div>
              <div class="spec-row">
                <span class="spec-label">Mitigasi</span>
                <span class="spec-value">Absorben Basa (NaOH)</span>
              </div>
            </div>
            <p class="gas-desc">Gas kuning-hijau pekat dengan bau khas yang menyengat. Lebih berat dari udara, mengumpul di lantai. Sangat toksik bahkan pada konsentrasi rendah.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-card">
        <div class="cta-glow"></div>
        <h2>Siap Menghadapi Krisis?</h2>
        <p>Mulai simulasi sekarang dan buktikan kemampuan mitigasi Anda dalam skenario kebocoran gas yang realistis.</p>
        <div class="cta-buttons">
          <a href="dashboard.html" class="btn btn-primary btn-lg" id="btn-cta-simulasi">
            Mulai Pre-test & Simulasi
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <span class="nav-title">HazardLIDM</span>
          <p>Platform simulasi WebAR mitigasi kebocoran gas K3 untuk pendidikan vokasi dan perguruan tinggi.</p>
        </div>
        <div class="footer-links">
          <span>Universitas Airlangga &copy; 2026</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Login Modal -->
  <div class="modal-overlay" id="modal-login" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <button class="modal-close" id="btn-close-modal" aria-label="Tutup">✕</button>
      <div class="modal-header">
        <div class="modal-icon">🔐</div>
        <h2 id="modal-title">Masuk ke Akun</h2>
        <p>Masukkan kredensial Anda untuk melanjutkan ke simulasi</p>
      </div>
      <form class="modal-form" id="form-login">
        <div class="form-group">
          <label for="input-email">Email</label>
          <input type="email" id="input-email" placeholder="nama@email.com" required autocomplete="email" />
        </div>
        <div class="form-group">
          <label for="input-password">Password</label>
          <input type="password" id="input-password" placeholder="••••••••" required autocomplete="current-password" />
        </div>
        <div class="form-error" id="form-error" hidden></div>
        <button type="submit" class="btn btn-primary btn-full" id="btn-submit-login">
          <span class="btn-text">Masuk</span>
          <span class="btn-spinner" hidden>⟳</span>
        </button>
        <p class="modal-register">Belum punya akun? <a href="#" id="link-register">Daftar di sini</a></p>
      </form>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal-overlay" id="modal-register" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-reg-title">
      <button class="modal-close" id="btn-close-modal-reg" aria-label="Tutup">✕</button>
      <div class="modal-header">
        <div class="modal-icon">📝</div>
        <h2 id="modal-reg-title">Buat Akun Baru</h2>
        <p>Daftar sebagai mahasiswa untuk mengakses simulasi</p>
      </div>
      <form class="modal-form" id="form-register">
        <div class="form-group">
          <label for="reg-name">Nama Lengkap</label>
          <input type="text" id="reg-name" placeholder="Nama Lengkap Anda" required />
        </div>
        <div class="form-group">
          <label for="reg-email">Email</label>
          <input type="email" id="reg-email" placeholder="nama@email.com" required />
        </div>
        <div class="form-group">
          <label for="reg-password">Password</label>
          <input type="password" id="reg-password" placeholder="Min. 8 karakter" required minlength="8" />
        </div>
        <div class="form-error" id="reg-error" hidden></div>
        <button type="submit" class="btn btn-primary btn-full" id="btn-submit-register">
          <span class="btn-text">Buat Akun</span>
        </button>
        <p class="modal-register">Sudah punya akun? <a href="#" id="link-login-back">Masuk di sini</a></p>
      </form>
    </div>
  </div>

  <script src="js/api.js"></script>
  <script>
    // ─── Animated Counter ─────────────────────────────────────────────────────
    async function loadStats() {
      try {
        const res = await API.get('/stats');
        animateCounter('stat-mahasiswa', res.total_users || 247);
        animateCounter('stat-simulasi', res.total_simulations || 1832);
        animateCounter('stat-survival', (res.survival_rate || 68), true);
      } catch {
        animateCounter('stat-mahasiswa', 247);
        animateCounter('stat-simulasi', 1832);
        animateCounter('stat-survival', 68, true);
      }
    }

    function animateCounter(id, target, isPercent = false) {
      const el = document.getElementById(id);
      let current = 0;
      const step = Math.ceil(target / 60);
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = isPercent ? current + '%' : current.toLocaleString();
        if (current >= target) clearInterval(timer);
      }, 30);
    }

    // Demo PPM counter in hero mockup
    let demoPPM = 0;
    setInterval(() => {
      demoPPM = (demoPPM + 1.8) % 280;
      document.getElementById('demo-ppm').textContent = Math.floor(demoPPM);
    }, 80);

    // ─── Modal Logic ──────────────────────────────────────────────────────────
    const modalLogin = document.getElementById('modal-login');
    const modalReg = document.getElementById('modal-register');

    function openModal(modal) {
      modal.removeAttribute('aria-hidden');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    function closeModal(modal) {
      modal.setAttribute('aria-hidden', 'true');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    document.getElementById('btn-close-modal').onclick = () => closeModal(modalLogin);
    document.getElementById('btn-close-modal-reg').onclick = () => closeModal(modalReg);
    document.getElementById('link-register').onclick = (e) => { e.preventDefault(); closeModal(modalLogin); openModal(modalReg); };
    document.getElementById('link-login-back').onclick = (e) => { e.preventDefault(); closeModal(modalReg); openModal(modalLogin); };
    document.querySelectorAll('.modal-overlay').forEach(m => {
      m.addEventListener('click', (e) => { if (e.target === m) closeModal(m); });
    });

    // Bind click to navbar login
    document.getElementById('btn-navbar-login').onclick = (e) => {
      e.preventDefault();
      openModal(modalLogin);
    };

    // Check auth & redirect if already logged in
    document.getElementById('btn-mulai-simulasi').addEventListener('click', (e) => {
      e.preventDefault();
      const token = localStorage.getItem('hazard_token');
      const userRaw = localStorage.getItem('hazard_user');
      if (token && userRaw) {
        const user = JSON.parse(userRaw);
        window.location.href = user.role === 'dosen' ? '/dashboard' : 'dashboard.html';
      } else {
        openModal(modalLogin);
      }
    });

    document.getElementById('btn-cta-simulasi').addEventListener('click', (e) => {
      e.preventDefault();
      const token = localStorage.getItem('hazard_token');
      const userRaw = localStorage.getItem('hazard_user');
      if (token && userRaw) {
        const user = JSON.parse(userRaw);
        window.location.href = user.role === 'dosen' ? '/dashboard' : 'dashboard.html';
      } else {
        openModal(modalLogin);
      }
    });

    // ─── Login Form (Unified Web Session + Token login) ──────────────────────────
    document.getElementById('form-login').addEventListener('submit', async (e) => {
      e.preventDefault();
      const errEl = document.getElementById('form-error');
      const btnText = document.querySelector('#btn-submit-login .btn-text');
      const btnSpinner = document.querySelector('#btn-submit-login .btn-spinner');
      errEl.hidden = true;
      btnText.hidden = true;
      btnSpinner.hidden = false;

      try {
        const response = await fetch('/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            email: document.getElementById('input-email').value,
            password: document.getElementById('input-password').value,
          })
        });

        const data = await response.json();
        if (!response.ok) {
          throw new Error(data.message || 'Email atau password salah.');
        }

        localStorage.setItem('hazard_token', data.token);
        localStorage.setItem('hazard_user', JSON.stringify(data.user));
        window.location.href = data.redirect;
      } catch (err) {
        errEl.textContent = err.message || 'Email atau password salah.';
        errEl.hidden = false;
      } finally {
        btnText.hidden = false;
        btnSpinner.hidden = true;
      }
    });

    // ─── Register Form ────────────────────────────────────────────────────────
    document.getElementById('form-register').addEventListener('submit', async (e) => {
      e.preventDefault();
      const errEl = document.getElementById('reg-error');
      errEl.hidden = true;
      try {
        const data = await API.post('/register', {
          name: document.getElementById('reg-name').value,
          email: document.getElementById('reg-email').value,
          password: document.getElementById('reg-password').value,
        });
        localStorage.setItem('hazard_token', data.token);
        localStorage.setItem('hazard_user', JSON.stringify(data.user));
        window.location.href = 'dashboard.html';
      } catch (err) {
        errEl.textContent = err.message || 'Registrasi gagal. Coba lagi.';
        errEl.hidden = false;
      }
    });

    // Auto-open login modal if URL has ?login=1 (e.g. from redirect or direct access)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login') === '1') {
      openModal(modalLogin);
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', (e) => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
      });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      document.getElementById('navbar').classList.toggle('navbar--scrolled', window.scrollY > 50);
    });

    loadStats();
  </script>
</body>
</html>
