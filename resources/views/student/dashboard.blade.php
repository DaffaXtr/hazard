<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Dashboard Mahasiswa - HazardLIDM</title>
  
  <!-- CSS Stylesheet -->
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* Styling Dashboard Khusus */
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
      display: grid;
      grid-template-columns: 1fr;
      min-height: 100vh;
      width: 100vw;
    }

    /* Sidebar Navigation (Desktop) */
    .app-sidebar {
      display: none;
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      padding-bottom: var(--spacing-lg);
      border-bottom: 1px solid var(--color-border);
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--color-primary-dim);
      border: 1px solid var(--color-primary);
      border-radius: var(--radius-md);
      width: 40px;
      height: 40px;
      color: var(--color-primary);
    }

    .sidebar-brand span {
      font-family: var(--font-heading);
      font-size: 1.25rem;
      font-weight: 850;
      letter-spacing: -0.02em;
    }

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: var(--spacing-xs);
      flex: 1;
    }

    .nav-btn {
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      padding: 0.85rem var(--spacing-md);
      border-radius: var(--radius-md);
      color: var(--color-text-muted);
      font-weight: 600;
      font-size: 0.9rem;
      transition: all var(--transition-fast);
      text-align: left;
      width: 100%;
      border: 1px solid transparent;
      background: transparent;
      cursor: pointer;
    }

    .nav-btn:hover {
      background: rgba(255, 255, 255, 0.03);
      color: var(--color-text);
      border-color: rgba(255, 255, 255, 0.05);
    }

    .nav-btn.active {
      background: var(--color-primary-dim);
      color: var(--color-primary);
      border-color: var(--color-border);
      box-shadow: 0 0 15px rgba(100, 255, 180, 0.05);
    }

    .sidebar-footer {
      border-top: 1px solid var(--color-border);
      padding-top: var(--spacing-lg);
    }

    /* Main View Area */
    .app-main {
      padding: 0 var(--spacing-2xl) var(--spacing-2xl);
      max-height: 100vh;
      overflow-y: auto;
      background: radial-gradient(circle at 80% 20%, rgba(100, 255, 180, 0.02) 0%, transparent 50%), var(--color-bg);
      display: flex;
      flex-direction: column;
      gap: var(--spacing-xl);
      padding-bottom: 100px; /* Space for mobile nav bar */
    }

    /* Views */
    .view-panel {
      display: none;
      animation: viewFadeIn 0.4s ease forwards;
    }

    .view-panel.active {
      display: block;
    }

    @keyframes viewFadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Top Greeting Widget - Sticky Header */
    .header-row {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(8, 13, 10, 0.85);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      padding: var(--spacing-xl) var(--spacing-2xl) var(--spacing-md);
      margin: 0 calc(-1 * var(--spacing-2xl)) var(--spacing-xl);
      border-bottom: 1px solid var(--color-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Notification Button */
    .notification-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      border-radius: var(--radius-md);
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--color-border);
      color: var(--color-text-muted);
      position: relative;
      transition: all var(--transition-fast);
      cursor: pointer;
    }
    .notification-btn:hover {
      background: var(--color-primary-dim);
      border-color: var(--color-primary);
      color: var(--color-primary);
    }
    .notification-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 8px;
      height: 8px;
      background-color: var(--color-danger);
      border-radius: 50%;
      border: 1.5px solid var(--color-bg);
    }

    .greeting-text h1 {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      font-weight: 850;
      color: #FFF;
    }

    .greeting-text p {
      color: var(--color-text-muted);
      font-size: 0.9rem;
    }

    /* Home tab Widgets */
    .home-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-xl);
    }

    .widget-card {
      background: rgba(13, 21, 16, 0.7);
      backdrop-filter: blur(12px);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: var(--spacing-xl);
      box-shadow: var(--shadow-card);
      position: relative;
      overflow: hidden;
    }

    .widget-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; height: 1.5px;
      background: linear-gradient(90deg, transparent, var(--color-primary-glow), transparent);
    }

    .card-title {
      font-family: var(--font-heading);
      font-size: 1.1rem;
      font-weight: 750;
      color: #FFF;
      margin-bottom: var(--spacing-md);
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
    }

    .status-card {
      display: flex;
      align-items: center;
      gap: var(--spacing-xl);
    }

    .user-avatar {
      width: 72px; height: 72px;
      border-radius: var(--radius-md);
      background: var(--color-primary-dim);
      border: 2.5px solid var(--color-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      box-shadow: 0 0 20px var(--color-primary-glow);
    }

    .user-details h3 {
      font-family: var(--font-heading);
      font-size: 1.3rem;
      font-weight: 800;
      margin-bottom: 0.2rem;
      color: #FFF;
    }

    .user-details p {
      font-size: 0.85rem;
      color: var(--color-text-muted);
      margin-bottom: 0.4rem;
    }

    .inst-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(100, 255, 180, 0.08);
      border: 1px solid rgba(100, 255, 180, 0.2);
      border-radius: var(--radius-sm);
      padding: 0.2rem 0.6rem;
      font-size: 0.72rem;
      color: var(--color-primary);
      font-weight: 700;
    }

    .metrics-row {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-md);
      margin-top: var(--spacing-lg);
    }

    .metric-badge {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
      text-align: center;
    }

    .metric-val {
      font-family: var(--font-mono);
      font-size: 1.5rem;
      font-weight: 750;
      color: var(--color-primary);
    }

    .metric-lbl {
      font-size: 0.75rem;
      color: var(--color-text-muted);
      margin-top: 0.15rem;
    }

    .announcement-box {
      background: rgba(255, 184, 48, 0.04);
      border: 1px dashed rgba(255, 184, 48, 0.3);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
      display: flex;
      gap: var(--spacing-md);
      align-items: flex-start;
      margin-top: var(--spacing-lg);
    }

    .ann-icon {
      color: var(--color-warning);
      font-size: 1.3rem;
    }

    .ann-content h4 {
      font-size: 0.85rem;
      font-weight: 750;
      color: var(--color-warning);
      margin-bottom: 0.25rem;
    }

    .ann-content p {
      font-size: 0.8rem;
      color: var(--color-text-muted);
      line-height: 1.4;
    }

    .cta-widget {
      grid-column: 1 / -1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: var(--spacing-lg);
      background: linear-gradient(135deg, rgba(13, 21, 16, 0.9) 0%, rgba(15, 45, 25, 0.8) 100%);
      border: 1px solid var(--color-primary);
      box-shadow: 0 0 30px rgba(100, 255, 180, 0.08);
      position: relative;
    }

    .cta-widget::after {
      content: '';
      position: absolute;
      width: 100px; height: 100px;
      background: var(--color-primary);
      filter: blur(80px);
      right: 10%; top: -20px;
      opacity: 0.15;
      pointer-events: none;
    }

    .cta-text h2 {
      font-family: var(--font-heading);
      font-size: 1.5rem;
      font-weight: 850;
      color: #FFF;
      margin-bottom: 0.3rem;
    }

    .cta-text p {
      font-size: 0.85rem;
      color: var(--color-text-muted);
    }

    .btn-glow {
      box-shadow: 0 0 25px var(--color-primary-glow);
      animation: ctaPulse 2s infinite;
      height: 52px;
      font-size: 1rem;
      border-radius: var(--radius-lg);
      padding: 0 2rem;
    }

    @keyframes ctaPulse {
      0%, 100% { transform: scale(1); box-shadow: 0 0 25px var(--color-primary-glow); }
      50% { transform: scale(1.03); box-shadow: 0 0 35px rgba(100, 255, 180, 0.6); }
    }

    /* Learning Center Styles */
    .learn-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-xl);
    }

    .chem-tabs {
      display: flex;
      gap: var(--spacing-xs);
      background: rgba(0,0,0,0.3);
      padding: 4px;
      border-radius: var(--radius-md);
      margin-bottom: var(--spacing-md);
    }

    .chem-tab-btn {
      flex: 1;
      padding: 0.6rem;
      border-radius: var(--radius-sm);
      color: var(--color-text-muted);
      font-weight: 600;
      font-size: 0.82rem;
      text-align: center;
      cursor: pointer;
    }

    .chem-tab-btn.active {
      background: rgba(255,255,255,0.04);
      color: #FFF;
      border: 1px solid rgba(255,255,255,0.05);
    }

    .gas-info-card {
      display: none;
      animation: viewFadeIn 0.3s ease forwards;
    }

    .gas-info-card.active {
      display: block;
    }

    .chem-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: var(--spacing-md);
    }

    .chem-formula {
      font-family: var(--font-mono);
      background: var(--color-primary-dim);
      border: 1px solid var(--color-primary);
      color: var(--color-primary);
      padding: 0.2rem 0.6rem;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      font-weight: 700;
    }

    .chem-formula--klorin {
      background: var(--color-klorin-dim);
      border-color: var(--color-klorin);
      color: var(--color-klorin);
    }

    .stats-table {
      width: 100%;
      border-collapse: collapse;
      margin: var(--spacing-md) 0;
    }

    .stats-table td {
      padding: 0.5rem 0;
      border-bottom: 1px solid rgba(255,255,255,0.03);
      font-size: 0.85rem;
    }

    .stats-table td:last-child {
      text-align: right;
      font-weight: 700;
    }

    .apd-gallery {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-md);
    }

    .apd-item {
      display: flex;
      gap: var(--spacing-md);
      padding: var(--spacing-md);
      background: rgba(255,255,255,0.01);
      border: 1px solid rgba(255,255,255,0.03);
      border-radius: var(--radius-md);
      align-items: center;
      transition: all var(--transition-fast);
    }

    .apd-item:hover {
      border-color: var(--color-border-hover);
      background: rgba(255, 255, 255, 0.02);
    }

    .apd-icon {
      width: 48px; height: 48px;
      border-radius: var(--radius-sm);
      background: rgba(255, 255, 255, 0.03);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
    }

    .apd-desc h4 {
      font-size: 0.88rem;
      font-weight: 750;
      color: #FFF;
    }

    .apd-desc p {
      font-size: 0.78rem;
      color: var(--color-text-muted);
      line-height: 1.3;
      margin-top: 0.15rem;
    }

    /* History Table Styles */
    .history-card {
      padding: 0;
      overflow: hidden;
    }

    .history-card .card-title {
      padding: var(--spacing-xl);
      padding-bottom: 0;
    }

    .history-container {
      overflow-x: auto;
    }

    .history-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    .history-table th {
      background: rgba(0, 0, 0, 0.2);
      padding: 1rem var(--spacing-xl);
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-text-muted);
      border-bottom: 1px solid var(--color-border);
    }

    .history-table td {
      padding: 1rem var(--spacing-xl);
      font-size: 0.88rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .history-table tr {
      cursor: pointer;
      transition: background var(--transition-fast);
    }

    .history-table tr:hover td {
      background: rgba(100, 255, 180, 0.01);
    }

    /* Profile tab Styles */
    .profile-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-xl);
    }

    .form-group {
      margin-bottom: var(--spacing-md);
    }

    .form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
      color: var(--color-text-muted);
    }

    .form-input {
      width: 100%;
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: var(--radius-md);
      padding: 0.75rem var(--spacing-md);
      color: var(--color-text);
      outline: none;
      transition: all var(--transition-fast);
    }

    .form-input:focus {
      border-color: var(--color-primary);
      box-shadow: 0 0 10px var(--color-primary-glow);
      background: rgba(255, 255, 255, 0.04);
    }

    /* Modal Wizard Layout */
    .wizard-overlay {
      position: fixed;
      inset: 0;
      background: rgba(4, 8, 6, 0.95);
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(20px);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.4s ease;
    }

    .wizard-overlay.active {
      opacity: 1;
      pointer-events: all;
    }

    .wizard-card {
      width: 90%;
      max-width: 580px;
      background: rgba(8, 14, 11, 0.98);
      border: 1px solid var(--color-border-hover);
      border-radius: var(--radius-xl);
      box-shadow: 0 0 50px rgba(100, 255, 180, 0.08);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      max-height: 90vh;
    }

    .wizard-header {
      padding: var(--spacing-xl);
      border-bottom: 1px solid var(--color-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .wizard-steps {
      display: flex;
      gap: var(--spacing-md);
      align-items: center;
    }

    .step-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }

    .step-dot.active {
      background: var(--color-primary);
      box-shadow: 0 0 8px var(--color-primary);
      transform: scale(1.3);
    }

    .step-dot.completed {
      background: var(--color-primary);
    }

    .wizard-close {
      cursor: pointer;
      color: var(--color-text-muted);
      transition: color var(--transition-fast);
    }

    .wizard-close:hover {
      color: #FFF;
    }

    .wizard-body {
      padding: var(--spacing-xl);
      overflow-y: auto;
      flex: 1;
    }

    .wizard-step-panel {
      display: none;
      animation: viewFadeIn 0.3s ease forwards;
    }

    .wizard-step-panel.active {
      display: block;
    }

    .wizard-footer {
      padding: var(--spacing-lg) var(--spacing-xl);
      border-top: 1px solid var(--color-border);
      display: flex;
      justify-content: space-between;
      background: rgba(0,0,0,0.2);
    }

    .scenario-card-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-md);
    }

    .scenario-btn {
      background: rgba(255, 255, 255, 0.01);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: var(--radius-lg);
      padding: var(--spacing-xl) var(--spacing-md);
      text-align: center;
      cursor: pointer;
      transition: all var(--transition-fast);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: var(--spacing-md);
    }

    .scenario-btn:hover {
      border-color: rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.02);
    }

    .scenario-btn.active.amonia-card {
      border-color: var(--color-amonia);
      background: var(--color-amonia-dim);
      box-shadow: 0 0 20px rgba(200, 220, 80, 0.08);
    }

    .scenario-btn.active.klorin-card {
      border-color: var(--color-klorin);
      background: var(--color-klorin-dim);
      box-shadow: 0 0 20px rgba(100, 180, 80, 0.08);
    }

    .scen-icon {
      font-size: 2.5rem;
    }

    .scen-details h3 {
      font-family: var(--font-heading);
      font-size: 1.15rem;
      font-weight: 800;
      color: #FFF;
      margin-bottom: 0.15rem;
    }

    .scen-details p {
      font-size: 0.72rem;
      color: var(--color-text-muted);
      line-height: 1.3;
    }

    .quiz-options {
      display: flex;
      flex-direction: column;
      gap: var(--spacing-sm);
      margin-top: var(--spacing-md);
    }

    .quiz-opt-btn {
      text-align: left;
      padding: 0.85rem var(--spacing-md);
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: var(--radius-md);
      color: var(--color-text);
      font-size: 0.85rem;
      cursor: pointer;
      transition: all var(--transition-fast);
      display: flex;
      gap: var(--spacing-md);
      align-items: center;
    }

    .quiz-opt-btn:hover {
      background: rgba(255, 255, 255, 0.04);
      border-color: rgba(255, 255, 255, 0.1);
    }

    .quiz-opt-btn.selected {
      background: var(--color-primary-dim);
      border-color: var(--color-primary);
      color: var(--color-primary);
    }

    .quiz-letter {
      width: 24px; height: 24px;
      border-radius: 50%;
      border: 1.5px solid currentColor;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      flex-shrink: 0;
    }

    .loker-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-md);
    }

    .loker-card {
      background: rgba(0,0,0,0.2);
      border: 1px solid rgba(255, 255, 255, 0.03);
      border-radius: var(--radius-lg);
      padding: var(--spacing-md);
    }

    .loker-card-title {
      font-size: 0.8rem;
      font-weight: 750;
      color: var(--color-text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: var(--spacing-sm);
    }

    .loker-options {
      display: grid;
      grid-template-columns: 1fr;
      gap: var(--spacing-xs);
    }

    .loker-btn {
      padding: 0.75rem var(--spacing-md);
      background: rgba(255, 255, 255, 0.01);
      border: 1px solid rgba(255, 255, 255, 0.03);
      border-radius: var(--radius-md);
      color: var(--color-text);
      cursor: pointer;
      text-align: left;
      font-size: 0.85rem;
      transition: all var(--transition-fast);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .loker-btn:hover {
      background: rgba(255,255,255,0.03);
    }

    .loker-btn.active {
      background: var(--color-primary-dim);
      border-color: var(--color-primary);
      color: var(--color-primary);
    }

    .loker-indicator {
      width: 14px; height: 14px;
      border-radius: 50%;
      border: 1.5px solid currentColor;
      position: relative;
    }

    .loker-btn.active .loker-indicator::after {
      content: '';
      position: absolute;
      inset: 2.5px;
      border-radius: 50%;
      background: currentColor;
    }

    /* Bottom Navigation Bar (Mobile only) */
    .app-bottom-nav {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      background: rgba(13, 21, 16, 0.95);
      border-top: 1px solid var(--color-border);
      backdrop-filter: blur(20px);
      display: flex;
      justify-content: space-around;
      padding: 0.5rem 0 calc(0.5rem + env(safe-area-inset-bottom));
      box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.5);
      z-index: 40;
    }

    .bottom-nav-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 3px;
      color: var(--color-text-muted);
      font-size: 0.65rem;
      font-weight: 600;
      background: transparent;
      border: none;
      cursor: pointer;
    }

    .bottom-nav-btn.active {
      color: var(--color-primary);
    }

    /* Modal Detail Riwayat */
    .detail-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(12px);
      z-index: 90;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .detail-overlay.active {
      opacity: 1;
      pointer-events: all;
    }

    .detail-card {
      width: 90%;
      max-width: 440px;
      background: #0f1e14;
      border: 1px solid var(--color-border-hover);
      border-radius: var(--radius-xl);
      padding: var(--spacing-xl);
      box-shadow: var(--shadow-card);
    }

    .detail-header {
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      margin-bottom: var(--spacing-lg);
    }

    .detail-status-icon {
      width: 44px; height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }

    .detail-status-icon.survived {
      background: var(--color-primary-dim);
      border: 2px solid var(--color-primary);
      color: var(--color-primary);
    }

    .detail-status-icon.failed {
      background: var(--color-danger-dim);
      border: 2px solid var(--color-danger);
      color: var(--color-danger);
    }

    .detail-title h3 {
      font-family: var(--font-heading);
      font-size: 1.15rem;
      font-weight: 800;
      color: #FFF;
    }

    .detail-title p {
      font-size: 0.78rem;
      color: var(--color-text-muted);
    }

    .detail-metrics-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: var(--spacing-sm);
      margin-bottom: var(--spacing-lg);
    }

    .detail-m-box {
      background: rgba(255,255,255,0.015);
      border: 1px solid rgba(255,255,255,0.03);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
    }

    .detail-m-lbl {
      font-size: 0.7rem;
      color: var(--color-text-muted);
      font-weight: 600;
    }

    .detail-m-val {
      font-size: 0.88rem;
      font-weight: 750;
      color: #FFF;
      margin-top: 0.15rem;
    }

    .detail-reason-box {
      background: rgba(255, 68, 68, 0.08);
      border: 1px solid rgba(255, 68, 68, 0.15);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
      font-size: 0.8rem;
      color: #FFAAAA;
      margin-bottom: var(--spacing-lg);
      line-height: 1.4;
    }

    /* Toast Notification styles */
    .toast {
      position: fixed;
      top: 24px; right: 24px;
      background: rgba(13, 21, 16, 0.95);
      border: 1.5px solid var(--color-primary);
      box-shadow: 0 10px 30px rgba(100, 255, 180, 0.1);
      border-radius: var(--radius-md);
      padding: var(--spacing-md) var(--spacing-lg);
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      z-index: 1000;
      animation: toastIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes toastIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    .toast-icon {
      color: var(--color-primary);
    }

    .toast-text h4 {
      font-size: 0.88rem;
      font-weight: 750;
      color: #FFF;
    }

    .toast-text p {
      font-size: 0.78rem;
      color: var(--color-text-muted);
    }

    /* Responsive Queries (Mobile First min-width media queries) */
    
    /* Large Mobile / Tablets (min-width: 481px) */
    @media (min-width: 481px) {
      .metrics-row {
        grid-template-columns: repeat(3, 1fr);
      }
      .scenario-card-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    /* Desktop Viewports (min-width: 1025px) */
    @media (min-width: 1025px) {
      .app-container {
        grid-template-columns: 260px 1fr;
      }
      .app-sidebar {
        background: rgba(13, 21, 16, 0.95);
        border-right: 1px solid var(--color-border);
        padding: var(--spacing-xl);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xl);
        position: sticky;
        top: 0;
        height: 100vh;
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
        z-index: 40;
      }
      .app-bottom-nav {
        display: none;
      }
      .app-main {
        padding: 0 var(--spacing-2xl) var(--spacing-xl);
      }
      .home-grid {
        grid-template-columns: 2fr 1fr;
      }
      .learn-grid {
        grid-template-columns: 1.2fr 1fr;
      }
      .profile-grid {
        grid-template-columns: 1fr 1.5fr;
      }
      .cta-widget {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        text-align: left;
      }
    }

    /* Disable glowing effect for exam button in all views */
    #btn-start-exam {
      box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
      animation: none !important;
    }
    #btn-start-exam:hover {
      box-shadow: 0 6px 20px rgba(0,0,0,0.4) !important;
      transform: translateY(-1px) !important;
    }

    /* Mobile & Tablet view styling for start simulation buttons */
    @media (max-width: 1024px) {
      .cta-buttons {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      #btn-start-practice, #btn-start-exam {
        width: 100% !important;
        justify-content: center !important;
        box-shadow: none !important;
        animation: none !important;
      }
      #btn-start-practice:hover, #btn-start-exam:hover {
        box-shadow: none !important;
        transform: none !important;
      }
    }
  </style>
</head>
<body>

  <div class="app-container">
    
    <!-- DESKTOP SIDEBAR NAVIGATION -->
    <aside class="app-sidebar">
      <div class="sidebar-brand">
        <div class="sidebar-logo">
          <i data-lucide="shield-alert" class="w-6 h-6"></i>
        </div>
        <span>HazardLIDM</span>
      </div>
      
      <nav class="sidebar-nav">
        <button type="button" class="nav-btn active" data-view="home">
          <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
          <span>Beranda</span>
        </button>
        <button type="button" class="nav-btn" data-view="learning">
          <i data-lucide="book-open" class="w-5 h-5"></i>
          <span>Learning Center</span>
        </button>
        <button type="button" class="nav-btn" data-view="history">
          <i data-lucide="history" class="w-5 h-5"></i>
          <span>Riwayat Simulasi</span>
        </button>
        <button type="button" class="nav-btn" data-view="profile">
          <i data-lucide="user" class="w-5 h-5"></i>
          <span>Profil Saya</span>
        </button>
      </nav>

      <div class="sidebar-footer">
        <button type="button" class="nav-btn text-danger btn-logout">
          <i data-lucide="log-out" class="w-5 h-5"></i>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- MAIN VIEWS CONTAINER -->
    <main class="app-main">
      
      <!-- TOP GREETING HEADER -->
      <div class="header-row">
        <div class="greeting-text">
          <h1 id="user-greeting">Halo, Mahasiswa!</h1>
          <p id="current-date">Sabtu, 11 Juli 2026</p>
        </div>
        <a href="notifications.html" class="notification-btn" aria-label="Notifikasi">
          <i data-lucide="bell" class="w-5 h-5"></i>
          <span class="notification-badge"></span>
        </a>
      </div>

      <!-- VIEW 1: BERANDA (HOME) -->
      <section class="view-panel active" id="panel-home">
        <div class="home-grid">
          
          <!-- LEFT COL: Status & Summary -->
          <div style="display: flex; flex-direction: column; gap: var(--spacing-xl);">
            <!-- Status Card -->
            <div class="widget-card">
              <div class="card-title">
                <i data-lucide="shield-check" class="text-primary w-5 h-5"></i>
                <span>Status Akreditasi Kompetensi</span>
              </div>
              <div class="status-card">
                <div class="user-avatar" id="avatar-initial">M</div>
                <div class="user-details">
                  <h3 id="user-name">Memuat Nama...</h3>
                  <p id="user-email">Memuat Email...</p>
                  <div class="inst-badge">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    <span>Tingkat 1 - Terdaftar</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Stats Badge summary -->
            <div class="widget-card">
              <div class="card-title">
                <i data-lucide="bar-chart-2" class="text-primary w-5 h-5"></i>
                <span>Ringkasan Pencapaian Simulasi</span>
              </div>
              <div class="metrics-row">
                <div class="metric-badge">
                  <div class="metric-val" id="sum-avg-pretest">-</div>
                  <div class="metric-lbl">Rata-rata Pre-Test</div>
                </div>
                <div class="metric-badge">
                  <div class="metric-val text-primary" id="sum-survived">0</div>
                  <div class="metric-lbl">Simulasi Survived</div>
                </div>
                <div class="metric-badge">
                  <div class="metric-val text-danger" id="sum-failed">0</div>
                  <div class="metric-lbl">Simulasi Failed</div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT COL: Chart & Announcement -->
          <div style="display: flex; flex-direction: column; gap: var(--spacing-xl);">
            <!-- Survival Rate Chart Widget -->
            <div class="widget-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 230px;">
              <div class="card-title" style="align-self: flex-start; width: 100%;">
                <i data-lucide="percent" class="text-primary w-5 h-5"></i>
                <span>Rasio Kelulusan</span>
              </div>
              <div style="width: 130px; height: 130px; margin-top: 10px;">
                <canvas id="survival-chart"></canvas>
              </div>
            </div>

            <!-- K3 Bulletin Board -->
            <div class="widget-card" style="flex: 1;">
              <div class="card-title">
                <i data-lucide="megaphone" class="text-warning w-5 h-5"></i>
                <span>Papan Pengumuman K3</span>
              </div>
              <div class="announcement-box">
                <i data-lucide="info" class="ann-icon flex-shrink-0"></i>
                <div class="ann-content">
                  <h4>Fokus Praktikum Minggu Ini</h4>
                  <p>Masing-masing mahasiswa wajib menyelesaikan Skenario Klorin. Pastikan memahami fungsi filter Cartridge untuk gas asam korosif!</p>
                </div>
              </div>
            </div>
          </div>

          <!-- BOTTOM ROW: CTA Start Simulation -->
          <div class="widget-card cta-widget">
            <div class="cta-text">
              <h2>Mulai Simulasi Tanggap Darurat</h2>
              <p>Hadapi kebocoran gas Amonia & Klorin virtual menggunakan teknologi WebXR Spasial.</p>
            </div>
            <div class="cta-buttons" style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; width: 100%; justify-content: flex-end;">
              <button type="button" class="btn btn-outline" id="btn-start-practice" style="height: 52px; font-size: 1rem; border-radius: var(--radius-lg); padding: 0 1.5rem;">
                <i data-lucide="dumbbell" class="w-5 h-5"></i>
                <span>Mulai Latihan</span>
              </button>
              <button type="button" class="btn btn-primary" id="btn-start-exam" style="height: 52px; font-size: 1rem; border-radius: var(--radius-lg); padding: 0 1.5rem;">
                <i data-lucide="play" class="w-5 h-5"></i>
                <span>Mulai Ujian (K3)</span>
              </button>
            </div>
          </div>

        </div>
      </section>

      <!-- VIEW 2: LEARNING CENTER -->
      <section class="view-panel" id="panel-learning">
        <div class="learn-grid">
          
          <!-- LEFT COL: Ensiklopedi Kimia -->
          <div class="widget-card">
            <div class="card-title">
              <i data-lucide="beaker" class="text-primary w-5 h-5"></i>
              <span>Ensiklopedi Karakteristik Gas K3</span>
            </div>
            
            <div class="chem-tabs">
              <div class="chem-tab-btn active" data-gas-info="amonia">Amonia (NH₃)</div>
              <div class="chem-tab-btn" data-gas-info="klorin">Klorin (Cl₂)</div>
            </div>

            <!-- Amonia Info -->
            <div class="gas-info-card active" id="info-amonia">
              <div class="chem-header">
                <h3 class="hero-title" style="font-size: 1.3rem; color: var(--color-amonia)">Gas Amonia</h3>
                <span class="chem-formula">NH₃</span>
              </div>
              <p class="text-slate-400 text-xs mt-2" style="line-height: 1.5;">
                Senyawa nitrogen dan hidrogen dengan bau menyengat yang tajam. Sangat larut dalam air (hidrofilik) membentuk larutan basa amonium hidroksida. Gas ini lebih ringan dari udara, sehingga membubung ke atas saat bocor.
              </p>
              <table class="stats-table">
                <tr>
                  <td>Ambang Batas Paparan (TLV-TWA)</td>
                  <td style="color: var(--color-amonia)">25 PPM</td>
                </tr>
                <tr>
                  <td>Batas Kritis Kesehatan (IDLH)</td>
                  <td style="color: var(--color-danger)">300 PPM</td>
                </tr>
                <tr>
                  <td>Kepadatan Relatif</td>
                  <td>0.59 (Lebih ringan dari udara)</td>
                </tr>
                <tr>
                  <td>Warna Visual di Kamera</td>
                  <td>Kuning kehijauan tipis / pudar</td>
                </tr>
                <tr>
                  <td>Metode Mitigasi K3</td>
                  <td>Tirai Air (Water Curtain) untuk pelarutan gas</td>
                </tr>
              </table>
              <div class="announcement-box" style="margin-top: 10px; background: rgba(100, 255, 180, 0.03); border-color: var(--color-border);">
                <i data-lucide="alert-triangle" class="text-primary flex-shrink-0"></i>
                <div class="ann-content">
                  <h4 class="text-primary">Catatan Keselamatan Amonia</h4>
                  <p>Karena amonia naik ke langit-langit ruangan, lakukan penyemprotan air di bagian atas plume gas untuk menangkap molekulnya.</p>
                </div>
              </div>
            </div>

            <!-- Klorin Info -->
            <div class="gas-info-card" id="info-klorin">
              <div class="chem-header">
                <h3 class="hero-title" style="font-size: 1.3rem; color: var(--color-klorin)">Gas Klorin</h3>
                <span class="chem-formula chem-formula--klorin">Cl₂</span>
              </div>
              <p class="text-slate-400 text-xs mt-2" style="line-height: 1.5;">
                Unsur halogen berupa gas beracun berwarna hijau kekuningan dengan bau menyengat yang mirip cairan pemutih. Klorin merupakan oksidator kuat dan sangat korosif. Gas ini 2.5 kali lebih berat dari udara, sehingga mengendap dan menyebar merayap di permukaan lantai.
              </p>
              <table class="stats-table">
                <tr>
                  <td>Ambang Batas Paparan (TLV-TWA)</td>
                  <td style="color: var(--color-klorin)">0.5 PPM</td>
                </tr>
                <tr>
                  <td>Batas Kritis Kesehatan (IDLH)</td>
                  <td style="color: var(--color-danger)">10 PPM</td>
                </tr>
                <tr>
                  <td>Kepadatan Relatif</td>
                  <td>2.50 (Jauh lebih berat dari udara)</td>
                </tr>
                <tr>
                  <td>Warna Visual di Kamera</td>
                  <td>Hijau kekuningan pekat</td>
                </tr>
                <tr>
                  <td>Metode Mitigasi K3</td>
                  <td>Penyumbatan Katup Fisik (Capping Kit)</td>
                </tr>
              </table>
              <div class="announcement-box" style="margin-top: 10px; background: rgba(255, 68, 68, 0.03); border-color: rgba(255, 68, 68, 0.2);">
                <i data-lucide="alert-triangle" class="text-danger flex-shrink-0"></i>
                <div class="ann-content" style="color: #FFAAAA">
                  <h4 class="text-danger">Catatan Keselamatan Klorin</h4>
                  <p>Karena klorin mengendap di lantai, menjauhlah dari lantai jika terjadi kebocoran masif. Ambang IDLH-nya sangat kecil (10 PPM)!</p>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT COL: APD Catalogue -->
          <div class="widget-card">
            <div class="card-title">
              <i data-lucide="shield" class="text-primary w-5 h-5"></i>
              <span>Katalog APD K3 Skenario Gas</span>
            </div>
            
            <div class="apd-gallery">
              <div class="apd-item">
                <div class="apd-icon">🎭</div>
                <div class="apd-desc">
                  <h4>Respirator Full-Face (Filter K)</h4>
                  <p>Masker penutup seluruh wajah dengan filter Cartridge berwarna **Hijau**. Khusus digunakan untuk menyaring Amonia dan turunan aminanya.</p>
                </div>
              </div>

              <div class="apd-item">
                <div class="apd-icon">🚀</div>
                <div class="apd-desc">
                  <h4>SCBA + Hazmat Suit Level A</h4>
                  <p>Self-Contained Breathing Apparatus (tabung oksigen mandiri) dipadukan dengan baju hazmat kedap gas. Wajib digunakan untuk gas Klorin tingkat tinggi.</p>
                </div>
              </div>

              <div class="apd-item">
                <div class="apd-icon">😷</div>
                <div class="apd-desc">
                  <h4>Masker Bedah & Respirator N95</h4>
                  <p>Hanya menyaring partikel debu padat atau cairan aerosol. **Tidak layak** untuk menyaring uap kimia gas beracun. (Dilarang untuk mitigasi gas K3).</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      <!-- VIEW 3: RIWAYAT SIMULASI -->
      <section class="view-panel" id="panel-history">
        <div class="widget-card history-card">
          <div class="card-title">
            <i data-lucide="history" class="text-primary w-5 h-5"></i>
            <span>Log Percobaan Simulasi WebAR</span>
          </div>
          
          <div class="history-container">
            <table class="history-table">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Skenario Gas</th>
                  <th>Durasi</th>
                  <th>PPM Maks</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="history-rows">
                <tr>
                  <td colspan="5" class="text-center text-muted" style="padding: 2rem;">Memuat data riwayat...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- VIEW 4: PROFIL SAYA -->
      <section class="view-panel" id="panel-profile">
        <div class="profile-grid">
          
          <!-- LEFT COL: User Card -->
          <div class="widget-card" style="text-align: center;">
            <div class="user-avatar" style="width: 90px; height: 90px; font-size: 2.5rem; margin: 0 auto 1.5rem;" id="profile-avatar">M</div>
            <h3 class="hero-title" style="font-size: 1.3rem; margin-bottom: 0.2rem;" id="profile-name">Memuat...</h3>
            <p class="text-muted text-xs mb-4" id="profile-email">Memuat...</p>
            <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 1.5rem 0;" />
            <div style="text-align: left; font-size: 0.8rem; line-height: 1.6; margin-bottom: 1.5rem;">
              <p><strong>Institusi:</strong> Universitas Airlangga</p>
              <p><strong>Fakultas:</strong> Vokasi</p>
              <p><strong>Program Studi:</strong> Keselamatan & Kesehatan Kerja (K3)</p>
              <p><strong>Role Akun:</strong> Mahasiswa Praktikan</p>
            </div>
            <button type="button" class="btn btn-outline btn-logout" style="border-color: var(--color-danger); color: var(--color-danger); width: 100%; justify-content: center; gap: 8px; border-radius: var(--radius-md);">
              <i data-lucide="log-out" class="w-4 h-4"></i>
              <span>Keluar dari Aplikasi</span>
            </button>
          </div>

          <!-- RIGHT COL: Change Password -->
          <div class="widget-card">
            <div class="card-title">
              <i data-lucide="lock" class="text-primary w-5 h-5"></i>
              <span>Keamanan & Sandi Akun</span>
            </div>
            <form id="form-change-password" style="margin-top: 10px;">
              <div class="form-group">
                <label for="old-pass">Password Saat Ini</label>
                <input type="password" id="old-pass" class="form-input" placeholder="••••••••" required />
              </div>
              <div class="form-group">
                <label for="new-pass">Password Baru</label>
                <input type="password" id="new-pass" class="form-input" placeholder="Minimal 8 karakter" required minlength="8" />
              </div>
              <button type="submit" class="btn btn-primary" style="margin-top: 10px; width: 100%; justify-content: center;">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Perbarui Kata Sandi</span>
              </button>
            </form>
          </div>

        </div>
      </section>

    </main>

    <!-- MOBILE BOTTOM NAVIGATION BAR -->
    <nav class="app-bottom-nav">
      <button type="button" class="bottom-nav-btn active" data-view="home">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>Beranda</span>
      </button>
      <button type="button" class="bottom-nav-btn" data-view="learning">
        <i data-lucide="book-open" class="w-5 h-5"></i>
        <span>Materi</span>
      </button>
      <button type="button" class="bottom-nav-btn" data-view="history">
        <i data-lucide="history" class="w-5 h-5"></i>
        <span>Riwayat</span>
      </button>
      <button type="button" class="bottom-nav-btn" data-view="profile">
        <i data-lucide="user" class="w-5 h-5"></i>
        <span>Profil</span>
      </button>
    </nav>

  </div>

  <!-- SIMULATION USER JOURNEY FLOW WIZARD OVERLAY -->
  <div class="wizard-overlay" id="wizard-sim">
    <div class="wizard-card">
      
      <!-- Wizard Header -->
      <div class="wizard-header">
        <div class="wizard-steps">
          <span class="step-dot active" id="dot-1"></span>
          <span class="step-dot" id="dot-2"></span>
          <span class="step-dot" id="dot-3"></span>
          <span class="step-dot" id="dot-4"></span>
        </div>
        <div class="wizard-close" id="btn-close-wizard">
          <i data-lucide="x" class="w-6 h-6"></i>
        </div>
      </div>

      <!-- Wizard Body -->
      <div class="wizard-body">
        
        <!-- STEP 1: PILIH SKENARIO GAS -->
        <div class="wizard-step-panel active" id="wiz-step-1">
          <h2 class="hero-title mb-2" style="font-size: 1.4rem;">Pilih Skenario Gas</h2>
          <p class="text-slate-400 text-xs mb-6">Pilih jenis zat kimia berbahaya untuk pengujian mitigasi.</p>
          
          <div class="scenario-card-grid">
            <div class="scenario-btn amonia-card" data-gas="amonia">
              <span class="scen-icon">💨</span>
              <div class="scen-details">
                <h3>Amonia (NH₃)</h3>
                <p>Gas alkali ringan, melayang naik. Ambang IDLH 300 PPM. Mitigasi: Water Spray.</p>
              </div>
            </div>
            <div class="scenario-btn klorin-card" data-gas="klorin">
              <span class="scen-icon">☣️</span>
              <div class="scen-details">
                <h3>Klorin (Cl₂)</h3>
                <p>Gas halogen berat, mengendap di lantai. Ambang IDLH 10 PPM. Mitigasi: Capping Kit.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 2: PRE-TEST TEORI (5 SOAL PILIHAN GANDA) -->
        <div class="wizard-step-panel" id="wiz-step-2">
          <h2 class="hero-title mb-1" style="font-size: 1.3rem;">Pre-Test Evaluasi K3</h2>
          <p class="text-slate-400 text-xs mb-4" id="quiz-question-counter">Soal 1 dari 5</p>
          
          <div class="announcement-box" style="margin-bottom: var(--spacing-md); padding: 0.5rem 0.8rem; background: rgba(100, 255, 180, 0.02); border-color: rgba(100,255,180,0.1);">
            <div class="ann-content" style="font-size: 0.72rem;">
              <span class="text-primary font-bold">Informasi:</span> Jawab semua pertanyaan teori dengan benar sebelum memulai AR.
            </div>
          </div>

          <div style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.03); border-radius: var(--radius-lg); padding: var(--spacing-lg);">
            <h4 class="font-bold text-white text-sm" style="line-height: 1.5;" id="quiz-question-text">Pertanyaan pre-test dimuat...</h4>
            <div class="quiz-options" id="quiz-options-container">
              <!-- JS rendered option buttons -->
            </div>
          </div>
        </div>

        <!-- STEP 3: LOKER APD -->
        <div class="wizard-step-panel" id="wiz-step-3">
          <h2 class="hero-title mb-2" style="font-size: 1.4rem;">Loker APD Keselamatan</h2>
          <p class="text-slate-400 text-xs mb-6">Pilih masker respirator dan filter cartridge yang sesuai untuk menahan racun gas.</p>
          
          <div class="loker-grid">
            <div class="loker-card">
              <div class="loker-card-title">1. Pilih Masker Respirator</div>
              <div class="loker-options">
                <button type="button" class="loker-btn ppe-mask-btn" data-mask="Respirator Full-Face">
                  <span>Respirator Full-Face (Kedap Gas Wajah)</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-mask-btn" data-mask="Respirator Half-Mask">
                  <span>Respirator Half-Mask (Setengah Wajah)</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-mask-btn" data-mask="Masker Bedah Standard">
                  <span>Masker Bedah / Kain Sederhana</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-mask-btn" data-mask="SCBA + Hazmat Level A">
                  <span>SCBA + Baju Hazmat Level A</span>
                  <span class="loker-indicator"></span>
                </button>
              </div>
            </div>

            <div class="loker-card" id="loker-cartridge-box">
              <div class="loker-card-title">2. Pilih Cartridge Filter Gas</div>
              <div class="loker-options">
                <button type="button" class="loker-btn ppe-filter-btn" data-filter="Filter K (Warna Hijau)">
                  <span>Filter K (Hijau - Khusus Gas Alkali / Amonia)</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-filter-btn" data-filter="Filter A (Warna Cokelat)">
                  <span>Filter A (Cokelat - Uap Gas Organik)</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-filter-btn" data-filter="Filter E (Warna Kuning)">
                  <span>Filter E (Kuning/Putih - Gas Asam Klorin/Sulfur)</span>
                  <span class="loker-indicator"></span>
                </button>
                <button type="button" class="loker-btn ppe-filter-btn" data-filter="Filter Debu (N95)">
                  <span>Filter Partikel N95 (Debu Kasar)</span>
                  <span class="loker-indicator"></span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 4: KONFIRMASI MULAI WEBAR -->
        <div class="wizard-step-panel" id="wiz-step-4">
          <h2 class="hero-title mb-2 text-center" style="font-size: 1.5rem;">Kesiapan Praktikum K3</h2>
          <p class="text-slate-400 text-xs mb-6 text-center">Tinjau persiapan Anda sebelum memasuki area simulasi kebocoran.</p>
          
          <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); border-radius: var(--radius-lg); padding: var(--spacing-xl); display: flex; flex-direction: column; gap: var(--spacing-md);">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.5rem;">
              <span class="text-muted text-xs">Skenario Gas</span>
              <strong id="summary-gas-type" class="text-white text-xs">-</strong>
            </div>
            <div id="summary-pretest-row" style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.5rem;">
              <span class="text-muted text-xs">Skor Pre-Test Teori</span>
              <strong id="summary-pretest-score" class="text-primary text-xs">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.5rem;">
              <span class="text-muted text-xs">Masker APD</span>
              <strong id="summary-ppe-mask" class="text-white text-xs">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.5rem;">
              <span class="text-muted text-xs">Filter Cartridge</span>
              <strong id="summary-ppe-filter" class="text-white text-xs">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 0.2rem;">
              <span class="text-muted text-xs">Tingkat Bahaya APD</span>
              <strong id="summary-danger-level" class="text-teal-400 text-xs font-bold">AMAN (Sesuai SOP)</strong>
            </div>
          </div>

          <div class="announcement-box" style="margin-top: var(--spacing-lg); background: rgba(100, 255, 180, 0.02); border-color: var(--color-border);">
            <i data-lucide="shield-alert" class="text-primary flex-shrink-0"></i>
            <div class="ann-content">
              <h4 class="text-primary">Instruksi Kamera WebXR</h4>
              <p>Saat kamera terbuka, arahkan ke lantai nyata dan goyangkan HP perlahan agar sistem mendeteksi permukaan lantai untuk peletakan tabung gas.</p>
            </div>
          </div>
        </div>

      </div>

      <!-- Wizard Footer -->
      <div class="wizard-footer">
        <button type="button" class="btn btn-ghost text-xs" id="btn-wiz-prev" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; height: 40px;">Kembali</button>
        <button type="button" class="btn btn-primary text-xs" id="btn-wiz-next" style="border-radius: 12px; height: 40px; padding: 0 1.5rem;">Lanjut</button>
      </div>

    </div>
  </div>

  <!-- DETAIL RIWAYAT MODAL -->
  <div class="detail-overlay" id="modal-history-detail">
    <div class="detail-card">
      <div class="detail-header">
        <div class="detail-status-icon survived" id="detail-icon-status">
          <i data-lucide="check" class="w-6 h-6"></i>
        </div>
        <div class="detail-title">
          <h3 id="detail-gas-title">Skenario Amonia</h3>
          <p id="detail-date">11 Juli 2026</p>
        </div>
      </div>

      <div class="detail-metrics-grid">
        <div class="detail-m-box">
          <div class="detail-m-lbl">Hasil Simulasi</div>
          <div class="detail-m-val" id="detail-status-val">SURVIVED</div>
        </div>
        <div class="detail-m-box">
          <div class="detail-m-lbl">Durasi Bertahan</div>
          <div class="detail-m-val" id="detail-duration-val">120 Detik</div>
        </div>
        <div class="detail-m-box">
          <div class="detail-m-lbl">PPM Tertinggi</div>
          <div class="detail-m-val" id="detail-maxppm-val">125 PPM</div>
        </div>
        <div class="detail-m-box">
          <div class="detail-m-lbl">PPM Akhir</div>
          <div class="detail-m-val" id="detail-finalppm-val">45 PPM</div>
        </div>
        <div class="detail-m-box" style="grid-column: 1 / -1;">
          <div class="detail-m-lbl">Alat Pelindung Diri (APD)</div>
          <div class="detail-m-val" id="detail-ppe-val">Respirator Full-Face (Filter K)</div>
        </div>
        <div class="detail-m-box" style="grid-column: 1 / -1;">
          <div class="detail-m-lbl">Aksi Tindakan K3</div>
          <div class="detail-m-val" id="detail-action-val">Penyemprotan Water Curtain</div>
        </div>
      </div>

      <div class="detail-reason-box" id="detail-reason-box">
        <strong>Penyebab Fatal:</strong> <span id="detail-reason-text">-</span>
      </div>

      <button type="button" class="btn btn-ghost w-full justify-center" id="btn-close-detail" style="border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; height: 44px;">
        Tutup Laporan
      </button>
    </div>
  </div>

  <!-- API Integration Client -->
  <script src="js/api.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", async () => {
      // 1. Authenticate check
      if (!API.isAuthenticated()) {
        window.location.href = 'index.html';
        return;
      }

      // Display Lucide Icons
      lucide.createIcons();

      // Render Current Date
      const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
      const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
      const now = new Date();
      document.getElementById('current-date').textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

      // Load Profile Data
      const currentUser = API.getCurrentUser();
      if (currentUser) {
        document.getElementById('user-greeting').textContent = `Halo, ${currentUser.name}!`;
        document.getElementById('user-name').textContent = currentUser.name;
        document.getElementById('user-email').textContent = currentUser.email;
        document.getElementById('profile-name').textContent = currentUser.name;
        document.getElementById('profile-email').textContent = currentUser.email;
        
        const initial = currentUser.name.charAt(0).toUpperCase();
        document.getElementById('avatar-initial').textContent = initial;
        document.getElementById('profile-avatar').textContent = initial;
      }

      // Navigation handler between Views (Tabs)
      const navButtons = document.querySelectorAll('.nav-btn, .bottom-nav-btn');
      const viewPanels = document.querySelectorAll('.view-panel');

      navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const viewName = btn.getAttribute('data-view');
          
          // Update active buttons
          navButtons.forEach(b => {
            if (b.getAttribute('data-view') === viewName) {
              b.classList.add('active');
            } else {
              b.classList.remove('active');
            }
          });

          // Update active panels
          viewPanels.forEach(p => {
            if (p.id === `panel-${viewName}`) {
              p.classList.add('active');
            } else {
              p.classList.remove('active');
            }
          });
        });
      });

      // Ensiklopedi Chemical Tabs
      const chemTabs = document.querySelectorAll('.chem-tab-btn');
      const gasInfos = document.querySelectorAll('.gas-info-card');

      chemTabs.forEach(btn => {
        btn.addEventListener('click', () => {
          const gasName = btn.getAttribute('data-gas-info');
          
          chemTabs.forEach(b => b.classList.toggle('active', b === btn));
          gasInfos.forEach(info => {
            info.classList.toggle('active', info.id === `info-${gasName}`);
          });
        });
      });

      // Chart.js - Render Survival Rate Doughnut
      let survivalChart = null;
      function renderChart(survived, failed) {
        const ctx = document.getElementById('survival-chart').getContext('2d');
        
        if (survivalChart) {
          survivalChart.destroy();
        }

        const total = survived + failed;
        const rate = total > 0 ? Math.round((survived / total) * 100) : 0;

        survivalChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Survived', 'Failed'],
            datasets: [{
              data: [survived || 1, failed || 0],
              backgroundColor: [
                '#64FFB4', // Emerald Green
                '#FF4444'  // Crimson Red
              ],
              borderColor: '#080d0a',
              borderWidth: 2
            }]
          },
          options: {
            cutout: '75%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { enabled: total > 0 }
            }
          },
          plugins: [{
            id: 'centerText',
            beforeDraw(chart) {
              const { width, height } = chart;
              const ctx = chart.ctx;
              ctx.restore();
              
              ctx.font = "bold 1.1rem Outfit, sans-serif";
              ctx.textBaseline = "middle";
              ctx.fillStyle = "#FFFFFF";
              const text = rate + "%";
              const textX = Math.round((width - ctx.measureText(text).width) / 2);
              const textY = height / 2 - 5;
              ctx.fillText(text, textX, textY);

              ctx.font = "500 0.6rem Inter, sans-serif";
              ctx.fillStyle = "rgba(232, 245, 238, 0.55)";
              const textSub = "SURVIVED";
              const textSubX = Math.round((width - ctx.measureText(textSub).width) / 2);
              const textSubY = height / 2 + 12;
              ctx.fillText(textSub, textSubX, textSubY);

              ctx.save();
            }
          }]
        });
      }

      // Fetch Stats and History
      let historyLogs = [];
      async function loadUserData() {
        try {
          // Pre-test averages
          const pretestsRes = await API.pretest.getHistory();
          const pretests = pretestsRes.pre_tests || [];
          let avgPretest = 0;
          if (pretests.length > 0) {
            avgPretest = Math.round(pretests.reduce((acc, curr) => acc + curr.score, 0) / pretests.length);
          }
          document.getElementById('sum-avg-pretest').textContent = pretests.length > 0 ? avgPretest : '-';
          if (pretests.length > 0) {
            localStorage.setItem('hazard_pretest_score', avgPretest);
          }

          // Simulation logs
          const simsRes = await API.simulation.getHistory();
          const simulations = simsRes.simulations || [];
          historyLogs = simulations;

          const survived = simulations.filter(s => s.status === 'survived').length;
          const failed = simulations.filter(s => s.status === 'failed').length;

          document.getElementById('sum-survived').textContent = survived;
          document.getElementById('sum-failed').textContent = failed;

          // Render Chart
          renderChart(survived, failed);

          // Populate History Table
          const tbody = document.getElementById('history-rows');
          if (simulations.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted" style="padding: 2rem;">Belum ada riwayat simulasi. Klik "Mulai Simulasi K3" untuk memulai.</td></tr>`;
          } else {
            tbody.innerHTML = simulations.map((sim, index) => {
              const dateObj = new Date(sim.created_at);
              const dateStr = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;
              const gasLabel = sim.gas_type === 'amonia' ? '<span class="badge badge-amonia">Amonia</span>' : '<span class="badge badge-klorin">Klorin</span>';
              const statusLabel = sim.status === 'survived' ? '<span class="badge badge-survived">Survived</span>' : '<span class="badge badge-failed">Failed</span>';
              return `
                <tr onclick="showHistoryDetail(${index})">
                  <td>${dateStr}</td>
                  <td>${gasLabel}</td>
                  <td>${sim.duration}s</td>
                  <td style="font-family: var(--font-mono);">${sim.max_ppm} PPM</td>
                  <td>${statusLabel}</td>
                </tr>
              `;
            }).join('');
          }

        } catch (err) {
          console.warn("Gagal memuat log pengguna:", err);
          renderChart(0, 0);
        }
      }

      // History Detail Modal triggers
      window.showHistoryDetail = function(index) {
        const sim = historyLogs[index];
        if (!sim) return;

        const dateObj = new Date(sim.created_at);
        const dayStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        document.getElementById('detail-gas-title').textContent = sim.gas_type === 'amonia' ? 'Skenario Amonia (NH₃)' : 'Skenario Klorin (Cl₂)';
        document.getElementById('detail-date').textContent = dayStr;
        document.getElementById('detail-status-val').textContent = sim.status.toUpperCase();
        
        const statusValEl = document.getElementById('detail-status-val');
        const iconStatusEl = document.getElementById('detail-icon-status');
        if (sim.status === 'survived') {
          statusValEl.className = 'detail-m-val text-primary';
          iconStatusEl.className = 'detail-status-icon survived';
          iconStatusEl.innerHTML = '<i data-lucide="check" class="w-6 h-6"></i>';
        } else {
          statusValEl.className = 'detail-m-val text-danger';
          iconStatusEl.className = 'detail-status-icon failed';
          iconStatusEl.innerHTML = '<i data-lucide="skull" class="w-6 h-6"></i>';
        }

        document.getElementById('detail-duration-val').textContent = `${sim.duration} Detik`;
        document.getElementById('detail-maxppm-val').textContent = `${sim.max_ppm} PPM`;
        document.getElementById('detail-finalppm-val').textContent = `${sim.final_ppm} PPM`;
        document.getElementById('detail-ppe-val').textContent = sim.ppe_selected;
        document.getElementById('detail-action-val').textContent = sim.mitigation_action === 'water_spray' ? 'Water Curtain (Tirai Air)' : 'Capping Kit (Klem Katup)';

        const reasonBox = document.getElementById('detail-reason-box');
        if (sim.status === 'failed') {
          document.getElementById('detail-reason-text').textContent = sim.failure_reason || 'Keterlambatan tindakan mitigasi / salah memilih APD.';
          reasonBox.style.display = 'block';
        } else {
          reasonBox.style.display = 'none';
        }

        document.getElementById('modal-history-detail').classList.add('active');
        lucide.createIcons();
      };

      document.getElementById('btn-close-detail').onclick = () => {
        document.getElementById('modal-history-detail').classList.remove('active');
      };

      // Toast helper
      function showToast(title, desc) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
          <i data-lucide="info" class="toast-icon"></i>
          <div class="toast-text">
            <h4>${title}</h4>
            <p>${desc}</p>
          </div>
        `;
        document.body.appendChild(toast);
        lucide.createIcons();
        setTimeout(() => {
          toast.style.animation = 'toastIn 0.3s ease reverse forwards';
          setTimeout(() => toast.remove(), 300);
        }, 3000);
      }

      // Change Password form trigger
      document.getElementById('form-change-password').addEventListener('submit', (e) => {
        e.preventDefault();
        showToast("Password Diperbarui", "Kata sandi akun Anda berhasil diganti.");
        e.target.reset();
      });

      // Logout triggers
      document.querySelectorAll('.btn-logout').forEach(btn => {
        btn.addEventListener('click', () => {
          API.logout();
        });
      });

      // ─── WIZARD SIMULATION FLOW ────────────────────────────────────────────────
      const wizardOverlay = document.getElementById('wizard-sim');
      const stepPanels = document.querySelectorAll('.wizard-step-panel');
      const stepDots = document.querySelectorAll('.step-dot');
      const btnPrev = document.getElementById('btn-wiz-prev');
      const btnNext = document.getElementById('btn-wiz-next');

      let currentStep = 1;
      let selectedGasScenario = null;
      let currentQuizIndex = 0;
      let quizScore = 0;
      let selectedQuizAnswers = [];
      let selectedPpeMask = null;
      let selectedPpeFilter = null;

      // Question Pool
      const quizPool = {
        amonia: [
          {
            q: "Manakah batas pajanan rata-rata (TLV-TWA) untuk gas Amonia (NH₃)?",
            o: ["25 PPM", "100 PPM", "0.5 PPM", "300 PPM"],
            a: 0
          },
          {
            q: "Bagaimana sifat fisik berat jenis Amonia (NH₃) dibanding dengan udara?",
            o: ["Sama berat", "Lebih berat, cenderung mengendap di bawah", "Lebih ringan, cenderung bergerak naik ke atas", "Sangat berat dan berwujud cair saja"],
            a: 2
          },
          {
            q: "Alat mitigasi utama K3 yang tepat untuk mengurangi sebaran uap Amonia di udara secara cepat adalah...",
            o: ["Klem Capping Kit", "Water Curtain (Tirai Air)", "Menyalakan Kipas Angin", "Menaburkan Pasir Silika"],
            a: 1
          },
          {
            q: "Warna Cartridge filter respirator yang khusus menyaring gas Amonia (Alkali) adalah?",
            o: ["Warna Kuning", "Warna Cokelat", "Warna Hijau", "Warna Putih"],
            a: 2
          },
          {
            q: "Konsentrasi Ambang Bahaya Kesehatan Fatal (IDLH) bagi Amonia menurut NIOSH sebesar?",
            o: ["10 PPM", "300 PPM", "50 PPM", "1000 PPM"],
            a: 1
          }
        ],
        klorin: [
          {
            q: "Berapakah batas konsentrasi fatal paparan instan (IDLH) untuk gas Klorin (Cl₂)?",
            o: ["300 PPM", "10 PPM", "50 PPM", "25 PPM"],
            a: 1
          },
          {
            q: "Karakteristik fisik sebaran gas Klorin (Cl₂) saat bocor di ruangan tertutup adalah...",
            o: ["Langsung membubung tinggi ke atap", "Sangat ringan dan langsung menguap ke angkasa", "Lebih berat dari udara, sehingga mengendap merayap di lantai", "Tinggi sebarannya selalu merata di tengah ruangan"],
            a: 2
          },
          {
            q: "Alat Pelindung Diri (APD) pernapasan yang wajib dipakai untuk paparan gas Klorin IDLH adalah?",
            o: ["Masker N95", "Masker Bedah", "SCBA (Self-Contained Breathing Apparatus)", "Respirator Half-Mask filter organik"],
            a: 2
          },
          {
            q: "Tindakan mitigasi fisik darurat untuk menutup langsung lubang kebocoran katup silinder Klorin adalah?",
            o: ["Menyemprotkan air bertekanan", "Pemasangan Capping Kit pada katup tabung", "Membalut katup dengan kain basah", "Menyiram tabung dengan cairan alkohol"],
            a: 1
          },
          {
            q: "Cartridge filter respirator gas asam seperti Klorin ditandai dengan warna?",
            o: ["Warna Hijau", "Warna Kuning atau Putih", "Warna Hitam", "Warna Pink"],
            a: 1
          }
        ]
      };

      let isPracticeMode = false;

      // Helper to open Wizard
      function openWizard() {
        currentStep = 1;
        selectedGasScenario = null;
        selectedPpeMask = null;
        selectedPpeFilter = null;
        currentQuizIndex = 0;
        quizScore = 0;
        selectedQuizAnswers = [];
        
        // Reset button states in wizard panels
        document.querySelectorAll('.scenario-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.loker-btn').forEach(b => b.classList.remove('active'));

        updateWizardUI();
        wizardOverlay.classList.add('active');
      }

      // Open Wizard triggers
      document.getElementById('btn-start-practice').onclick = () => {
        isPracticeMode = true;
        openWizard();
      };

      document.getElementById('btn-start-exam').onclick = () => {
        isPracticeMode = false;
        openWizard();
      };

      // Close Wizard
      document.getElementById('btn-close-wizard').onclick = () => {
        wizardOverlay.classList.remove('active');
      };

      // Helper to transition to APD Step
      function goToApdStep() {
        currentStep = 3;
        const filterBox = document.getElementById('loker-cartridge-box');
        if (selectedGasScenario === 'klorin') {
          // Chlorine doesn't strictly use cartridges (uses SCBA Hazmat Level A)
          // We hide cartridge selection or show that SCBA provides air.
          filterBox.style.opacity = '0.5';
          filterBox.style.pointerEvents = 'none';
          selectedPpeFilter = 'Pasokan Udara Tabung (SCBA)';
        } else {
          filterBox.style.opacity = '1';
          filterBox.style.pointerEvents = 'all';
        }
        updateWizardUI();
      }

      // Scenario selection listener
      document.querySelectorAll('.scenario-btn').forEach(btn => {
        btn.onclick = () => {
          document.querySelectorAll('.scenario-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedGasScenario = btn.getAttribute('data-gas');
          
          // Disable/Enable Cartridge filter options in Step 3 according to gas scenario
          const isAmonia = selectedGasScenario === 'amonia';
          
          // Lanjut ke step 2 otomatis setelah milih scenario (atau step 3 jika latihan)
          setTimeout(() => {
            if (isPracticeMode) {
              goToApdStep();
            } else {
              currentStep = 2;
              currentQuizIndex = 0;
              quizScore = 0;
              selectedQuizAnswers = [];
              loadQuizQuestion();
              updateWizardUI();
            }
          }, 300);
        };
      });

      // Render Quiz Question
      function loadQuizQuestion() {
        const questions = quizPool[selectedGasScenario];
        if (!questions) return;

        const currentQ = questions[currentQuizIndex];
        
        document.getElementById('quiz-question-counter').textContent = `Soal ${currentQuizIndex + 1} dari 5`;
        document.getElementById('quiz-question-text').textContent = currentQ.q;
        
        const optionsContainer = document.getElementById('quiz-options-container');
        optionsContainer.innerHTML = currentQ.o.map((opt, i) => {
          const letter = ["A", "B", "C", "D"][i];
          return `
            <button type="button" class="quiz-opt-btn" onclick="selectQuizAnswer(${i})">
              <span class="quiz-letter">${letter}</span>
              <span>${opt}</span>
            </button>
          `;
        }).join('');
      }

      window.selectQuizAnswer = function(optIndex) {
        // Toggle selected state in buttons
        const btns = document.querySelectorAll('.quiz-opt-btn');
        btns.forEach((btn, i) => {
          btn.classList.toggle('selected', i === optIndex);
        });
        
        selectedQuizAnswers[currentQuizIndex] = optIndex;

        // Auto advance quiz question after small delay
        setTimeout(() => {
          const questions = quizPool[selectedGasScenario];
          
          // Check if answer is correct
          if (optIndex === questions[currentQuizIndex].a) {
            quizScore += 20; // 5 questions, 20 points each
          }

          if (currentQuizIndex < 4) {
            currentQuizIndex++;
            loadQuizQuestion();
          } else {
            // Quiz finished, go to APD locker
            goToApdStep();
          }
        }, 300);
      };

      // APD Selection Listeners
      document.querySelectorAll('.loker-card:nth-child(1) .loker-btn').forEach(btn => {
        btn.onclick = () => {
          document.querySelectorAll('.loker-card:nth-child(1) .loker-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedPpeMask = btn.getAttribute('data-mask');
          
          // If SCBA is selected, auto-select air supply filter
          if (selectedPpeMask === 'SCBA + Hazmat Level A') {
            document.querySelectorAll('.loker-card:nth-child(2) .loker-btn').forEach(b => b.classList.remove('active'));
            selectedPpeFilter = 'Pasokan Udara Tabung (SCBA)';
          }
        };
      });

      document.querySelectorAll('.loker-card:nth-child(2) .loker-btn').forEach(btn => {
        btn.onclick = () => {
          document.querySelectorAll('.loker-card:nth-child(2) .loker-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedPpeFilter = btn.getAttribute('data-filter');
        };
      });

      // Next / Prev button triggers
      btnPrev.onclick = () => {
        if (currentStep > 1) {
          if (currentStep === 3) {
            // Go back to scenario selection instead of quiz to avoid retaking quiz (or if practice mode)
            currentStep = 1;
          } else {
            currentStep--;
          }
          updateWizardUI();
        }
      };

      btnNext.onclick = async () => {
        if (currentStep === 1) {
          if (!selectedGasScenario) {
            alert("Silakan pilih skenario gas terlebih dahulu.");
            return;
          }
          if (isPracticeMode) {
            goToApdStep();
          } else {
            currentStep = 2;
            loadQuizQuestion();
            updateWizardUI();
          }
        } else if (currentStep === 3) {
          if (!selectedPpeMask) {
            alert("Silakan pilih masker respirator terlebih dahulu.");
            return;
          }
          if (selectedGasScenario === 'amonia' && !selectedPpeFilter) {
            alert("Silakan pilih cartridge filter gas terlebih dahulu.");
            return;
          }
          
          currentStep = 4;
          
          // Submit pre-test score to backend
          try {
            if (!isPracticeMode) {
              await API.pretest.submit(quizScore, {
                gas_type: selectedGasScenario,
                selected_answers: selectedQuizAnswers
              });
              // Reload user data in background to reflect new pre-test score
              loadUserData();
            }
          } catch (e) {
            console.error("Gagal mengirim nilai pre-test ke server:", e);
          }

          // Populate Summary step
          document.getElementById('summary-gas-type').textContent = selectedGasScenario === 'amonia' ? 'Amonia (NH₃)' : 'Klorin (Cl₂)';
          document.getElementById('summary-pretest-score').textContent = `${quizScore}/100`;
          document.getElementById('summary-ppe-mask').textContent = selectedPpeMask;
          document.getElementById('summary-ppe-filter').textContent = selectedPpeFilter || '-';

          // Determine safety SOP
          const dangerEl = document.getElementById('summary-danger-level');
          const isPpeCorrect = (selectedGasScenario === 'amonia' && selectedPpeMask === 'Respirator Full-Face' && selectedPpeFilter === 'Filter K (Warna Hijau)') || 
                               (selectedGasScenario === 'klorin' && selectedPpeMask === 'SCBA + Hazmat Level A');
          
          if (isPpeCorrect) {
            dangerEl.textContent = "AMAN (Sesuai SOP)";
            dangerEl.style.color = '#64FFB4'; // emerald
          } else {
            dangerEl.textContent = "BAHAYA (Penalti laju PPM 10x Lipat!)";
            dangerEl.style.color = '#FF4444'; // crimson
          }

          updateWizardUI();
        } else if (currentStep === 4) {
          // Launch WebAR simulation!
          const isPpeCorrect = (selectedGasScenario === 'amonia' && selectedPpeMask === 'Respirator Full-Face' && selectedPpeFilter === 'Filter K (Warna Hijau)') || 
                               (selectedGasScenario === 'klorin' && selectedPpeMask === 'SCBA + Hazmat Level A');
          
          const ppeLabel = selectedGasScenario === 'amonia' ? `${selectedPpeMask} (Filter K)` : selectedPpeMask;

          const config = {
            gas_type: selectedGasScenario,
            ppe_selected: ppeLabel,
            is_ppe_correct: isPpeCorrect,
            mitigation_action: selectedGasScenario === 'amonia' ? 'water_spray' : 'capping_kit',
            max_ppm_limit: selectedGasScenario === 'amonia' ? 300 : 10,
            mitigation_factor: selectedGasScenario === 'amonia' ? 25 : 30,
            emission_rate: selectedGasScenario === 'amonia' ? 2.5 : 0.85,
            is_practice: isPracticeMode
          };

          // Save simulation configs
          localStorage.setItem('active_simulation_config', JSON.stringify(config));
          
          // Redirect to simulation page
          window.location.href = 'simulation.html';
        }
      };

      // Wizard UI Updater
      function updateWizardUI() {
        // Toggle step panels
        stepPanels.forEach((p, i) => {
          p.classList.toggle('active', i + 1 === currentStep);
        });

        // Hide dot-2 if practice mode
        const dot2 = document.getElementById('dot-2');
        if (dot2) {
          dot2.style.display = isPracticeMode ? 'none' : 'inline-block';
        }

        // Toggle dot indicators
        stepDots.forEach((dot, i) => {
          dot.className = 'step-dot';
          
          if (isPracticeMode) {
            if (i === 1) { // Skip dot-2 (index 1)
              return;
            }
            if (i + 1 === currentStep) {
              dot.classList.add('active');
            } else if (i + 1 < currentStep) {
              dot.classList.add('completed');
            }
          } else {
            if (i + 1 === currentStep) {
              dot.classList.add('active');
            } else if (i + 1 < currentStep) {
              dot.classList.add('completed');
            }
          }
        });

        // Hide/Show pretest row on Summary step (step 4)
        const pretestRow = document.getElementById('summary-pretest-row');
        if (pretestRow) {
          pretestRow.style.display = isPracticeMode ? 'none' : 'flex';
        }

        // Toggle footer buttons
        if (currentStep === 1) {
          btnPrev.style.visibility = 'hidden';
          btnNext.style.display = 'block';
          btnNext.textContent = 'Lanjut';
        } else if (currentStep === 2) {
          // Hide next/prev during quiz to enforce choosing option
          btnPrev.style.visibility = 'visible';
          btnNext.style.display = 'none';
        } else if (currentStep === 3) {
          btnPrev.style.visibility = 'visible';
          btnNext.style.display = 'block';
          btnNext.textContent = 'Verifikasi SOP';
        } else if (currentStep === 4) {
          btnPrev.style.visibility = 'visible';
          btnNext.style.display = 'block';
          btnNext.textContent = 'Buka Kamera WebAR';
        }
      }

      // Check notification badge read state
      if (localStorage.getItem('hazard_notifications_read') === 'true') {
        const badge = document.querySelector('.notification-badge');
        if (badge) badge.style.display = 'none';
      }

      // Initial Data Loading
      await loadUserData();
    });
  </script>
</body>
</html>
