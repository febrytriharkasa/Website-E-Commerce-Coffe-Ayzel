<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ayzel Coffe Admin Dashboard</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= base_url('/logo.png'); ?>">

  <!-- Local Third-Party Libraries -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/bootstrap-icons.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/apexcharts/apexcharts.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css'); ?>">

  <style>
  @media print {
      /* 1. Sembunyikan elemen UI website, tombol-tombol, dan Floating Icon di pojok layar */
      .navbar-custom, .sidebar-custom, .page-header, 
      .d-flex.justify-content-end, footer, .footer-custom,
      .btn, button, .position-fixed, .fixed-bottom, [class*="fixed"] {
          display: none !important;
      }

      /* 2. Setup Kertas */
      @page { margin: 0 !important; }

      *, *::before, *::after { box-sizing: border-box !important; }

      /* 3. PERBAIKAN FINAL: Paksa SELURUH div, main, dan section agar tidak memiliki batas tinggi (100vh) */
      html, body, div, main, section, article {
          height: auto !important;
          min-height: 0 !important;
          overflow: visible !important;
      }

      html, body {
          background-color: #fff !important;
          width: 80mm !important; 
          margin: 0 !important;
          padding: 4mm !important;
          font-family: 'Courier New', Courier, monospace !important; 
          color: #000 !important;
          font-size: 10px !important; 
      }

      /* 4. Hapus jarak, efek shadow, dan border radius bawaan Bootstrap */
      .card, .row, .col-md-10, .col-sm-6 {
          border: none !important;
          box-shadow: none !important;
          border-radius: 0 !important;  
          margin: 0 !important;
          padding: 0 !important;
          width: 100% !important;
          max-width: 100% !important;
      }

      /* 5. HEADER STRUK (Tengah) */
      .row.border-bottom {
          border-bottom: 1px dashed #000 !important;
          padding-bottom: 10px !important;
          margin-bottom: 5px !important;
          display: flex !important;
          flex-direction: column !important; 
          align-items: center !important;    
          text-align: center !important;
      }

      .text-sm-end {
          text-align: center !important;
          width: 100% !important;
      }

      h4.text-primary {
          font-size: 14px !important;
          color: #000 !important;
          margin: 0 0 2px 0 !important;
      }

      span.text-muted.fw-semibold {
          font-size: 10px !important;
          display: block !important;
          margin-bottom: 5px !important;
      }

      .badge {
          background: transparent !important;
          color: #000 !important;
          border: 1px dashed #000 !important;
          font-size: 10px !important;
          padding: 2px 5px !important;
          display: inline-block !important;
      }

      /* ==================================================
        6. FORMAT TABEL PRODUK
        ================================================== */
      .table-custom, .table-custom tbody, .table-custom tfoot {
          display: block !important;
          width: 100% !important;
          margin: 0 !important;
          border-collapse: collapse !important;
      }
      
      .table-custom thead { display: none !important; }

      .table-custom tbody tr {
          display: flex !important;
          flex-wrap: wrap !important; 
          align-items: center !important;
          border-bottom: 1px dashed #ccc !important;
          padding: 4px 0 !important;
          width: 100% !important;
      }

      .table-custom td {
          border: none !important;
          padding: 0 !important;
          font-size: 10px !important;
          color: #000 !important;
      }

      .table-custom tbody td:nth-child(1) { display: none !important; }

      .table-custom tbody td:nth-child(2) {
          flex: 0 0 100% !important;
          font-weight: bold !important;
          margin-bottom: 4px !important;
      }

      .table-custom tbody td:nth-child(3) { 
          flex: 0 0 40% !important; 
          text-align: left !important; 
      }
      
      .table-custom tbody td:nth-child(4) { 
          flex: 0 0 15% !important; 
          text-align: center !important; 
      }
      .table-custom tbody td:nth-child(4)::before { content: "x "; }
      
      .table-custom tbody td:nth-child(5) { 
          flex: 0 0 45% !important; 
          text-align: right !important; 
      }

      /* ==================================================
        7. FOOTER TOTAL PEMBAYARAN 
        ================================================== */
      .table-custom tfoot tr {
          display: flex !important;
          justify-content: space-between !important; 
          align-items: center !important;
          border-top: 1px dashed #000 !important; 
          padding-top: 5px !important;
          margin-top: 2px !important;
          width: 100% !important;
      }

      .table-custom tfoot td {
          font-size: 11px !important;
          font-weight: bold !important;
          border: none !important;
          padding: 0 !important;
      }

      .table-custom tfoot td:nth-child(1) { 
          text-align: left !important; 
      }
      
      .table-custom tfoot td:nth-child(2) { 
          text-align: right !important; 
      }
  }
  </style>
</head>

<body>
      <!-- ==========================================
         START: Sidebar Component
         Highly polished, dark-green sticky navigation
         ========================================== -->
  <div class="sidebar-wrapper" id="sidebar">
    <!-- Brand Logo / Identity -->
    <a href="#" class="sidebar-brand">
      <img src="<?= base_url('logo.png'); ?>" alt="Logo" class="sidebar-brand-img" style="max-height: 75px; width: auto;">
      <span>Ayzel Coffe Admin</span>
    </a>

    <!-- Navigation Menu -->
    <div class="flex-grow-1 overflow-y-auto">
      <!-- Group: Menu -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item <?= url_is('/dashboard*') ? 'active' : '' ?>">
            <a href="<?= base_url('/dashboard') ?>" class="sidebar-menu-link" id="menu-dashboard" title="Dashboard">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Group: Components -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Components</div>
        <ul class="sidebar-menu-list">
            
            <!-- Menu: Tambah Data Produk -->
            <li class="sidebar-menu-item <?= url_is('product*') ? 'active' : '' ?>">
            <a href="<?= base_url('product') ?>" class="sidebar-menu-link" id="menu-basictables" title="Tambah Data Produk">
                <i class="bi bi-table"></i>
                <span>Tambah Data Produk</span>
            </a>
            </li>

            <!-- Menu: Manajemen Produk -->
            <li class="sidebar-menu-item <?= (url_is('sizes-produk') || url_is('produk/kelola*')) ? 'active' : '' ?>">
            <a href="<?= base_url('sizes-product') ?>" class="sidebar-menu-link" id="menu-uiforms" title="Manajemen Produk">
                <i class="bi bi-input-cursor-text"></i>
                <span>Manajemen Produk</span>
            </a>
            </li>

            <!-- Menu: Manajemen Transaksi -->
            <li class="sidebar-menu-item <?= url_is('transaksi*') ? 'active' : '' ?>">
            <a href="<?= base_url('transaksi') ?>" class="sidebar-menu-link" id="menu-uibuttons" title="Manajemen Transaksi">
                <i class="bi bi-menu-button-wide-fill"></i>
                <span>Manajemen Transaksi</span>
            </a>
            </li>

            <li class="sidebar-menu-item <?= url_is('transaksi-approvel*') ? 'active' : '' ?>">
            <a href="<?= base_url('transaksi-approvel') ?>" class="sidebar-menu-link" id="menu-uibuttons" title="Manajemen Transaksi">
                <i class="bi bi-menu-button-wide-fill"></i>
                <span>Approvel Transaksi</span>
            </a>
            </li>

        </ul>
        </div>
    </div>

    <!-- Sidebar Profile Card (Dynamic Footer) -->
    <div class="sidebar-profile">
      <!-- <img src="assets/images/avatar.png" alt="Administrator" class="sidebar-profile-img"
        onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'"> -->
      <div class="sidebar-profile-info">
        <div class="sidebar-profile-name"><?= esc(session()->get('nama')); ?></div>
        <div class="sidebar-profile-email"><?= esc(session()->get('email')); ?></div>
      </div>
    </div>
  </div>

    <div class="main-wrapper">
        <!-- START: Top Navbar Component -->
      <header class="navbar-custom">
      <div class="navbar-left">
        <!-- Desktop sidebar toggle (visible on large screens only) -->
        <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
          id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
          <i class="bi bi-chevron-bar-left"></i>
        </button>
        <!-- Mobile sidebar toggle -->
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
          <i class="bi bi-list"></i>
        </button>

        <!-- Quick Actions Dropdown -->
        <div class="dropdown ms-2">
          <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
            id="quick-actions-dropdown">
            <i class="bi bi-plus-lg"></i>
            <span>Shortcuts Create</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-quick-action" aria-labelledby="quick-actions-dropdown">
            <li class="dropdown-header">Quick Action Shortcuts</li>
            <li><a class="dropdown-item" href="/product/create"><i class="bi bi-plus"></i> Tambah Produk</a></li>
            <li><a class="dropdown-item" href="/sizes-product/create"><i class="bi bi-plus"></i> Tambah Varian</a></li>
            <li><a class="dropdown-item" href="/transaksi/create"><i class="bi bi-plus"></i> Tambah Transaksi</a></li>
          </ul>
        </div>
      </div>

      <!-- Mid navbar: search pill -->
      <div class="navbar-search-wrapper">
        <input type="text" class="navbar-search-input" placeholder="Search anything in Spark..." id="main-search">
        <button class="navbar-search-btn" aria-label="Search">
          <i class="bi bi-search"></i>
        </button>
      </div>

      <!-- Right actions -->
      <div class="navbar-actions">
        <!-- Fullscreen Toggle -->
        <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>
        <div class="dropdown">
          <button class="navbar-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="btn-notifications" data-bs-auto-close="outside">
            <i class="bi bi-bell"></i>
            
            <!-- Tampilkan badge merah/dot hanya jika ada stok menipis -->
            <?php if (isset($low_stock_count) && $low_stock_count > 0): ?>
                <span class="navbar-action-badge bg-danger"></span>
            <?php endif; ?>
          </button>
          
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-notification p-0" aria-labelledby="btn-notifications">
            <div class="notification-header">
              <h6 class="notification-title">Notifikasi Sistem</h6>
              <button class="btn-clear-all" type="button">Tandai dibaca</button>
            </div>
            
            <div class="notification-list">
              <!-- Cek apakah variabel stok ada dan lebih dari 0 -->
              <?php if (isset($low_stock_items) && !empty($low_stock_items)): ?>
                  
                  <?php foreach($low_stock_items as $item): ?>
                      <a href="/produk" class="notification-item">
                        <div class="notification-icon bg-warning text-dark">
                          <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div class="notification-content">
                          <p class="notification-text">Stok menipis: <strong><?= $item['nama']; ?> (<?= $item['ukuran']; ?>)</strong></p>
                          <span class="notification-time text-danger fw-bold">Sisa: <?= $item['stok']; ?> Unit</span>
                        </div>
                        <span class="notification-unread-dot"></span>
                      </a>
                  <?php endforeach; ?>

              <?php else: ?>
                  <!-- Tampilan jika tidak ada notifikasi -->
                  <div class="p-4 text-center text-muted">
                      <i class="bi bi-bell-slash fs-3 d-block mb-2 text-light"></i>
                      <small>Belum ada notifikasi baru</small>
                  </div>
              <?php endif; ?>
            </div>
            
            <a href="/sizes-product" class="notification-footer">Lihat Semua Stok Produk</a>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown ms-2">
          <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="profile-dropdown">
            <!-- <img src="assets/images/avatar.png" alt="Profile Image" class="navbar-profile-img"> -->
            <span class="navbar-profile-name d-none d-md-inline"><?= esc(session()->get('nama')); ?></span>
            <i class="bi bi-chevron-down navbar-profile-caret"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
            <li class="dropdown-header">Welcome <?= esc(session()->get('nama')); ?>!</li>
            <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> My Account</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-lock"></i> Lock Screen</a></li> -->
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right"></i>
                Logout</a></li>
          </ul>
        </div>
      </div>
    </header>
        <!-- END: Top Navbar Component -->
        <!-- Main Content Area -->
        <div id="content">
            <!-- Area render dari views (index.php) -->
            <div class="container-fluid px-4">
                <?= $this->renderSection('content'); ?>
            </div>
            <!-- START: Footer Component -->
            <footer class="footer-custom px-4 pb-4">
              <div class="footer-left">
                <div class="footer-logo">
                  <i class="bi bi-asterisk"></i>
                  <span>Ayzel Coffe Admin</span>
                </div>
              </div>
              <div class="footer-right">
                <div class="footer-separator"></div>
                <div class="footer-copy">
                <a href="#" class="footer-link">Ayzel Coffe Admin</a>
                <span>2026</span>
                </div>
              </div>
            </footer>
            <!-- END: Footer Component -->
        </div>
     </div>
    </div>

  <!-- ==========================================
         END: Main Content Area
         ========================================== -->

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js'); ?>"></script>
  <script src="<?= base_url('assets/libs/flatpickr/flatpickr.min.js'); ?>"></script>

  <!-- Local dashboard interactions controller -->
  <script src="<?= base_url('assets/js/dashboard.js'); ?>"></script>

  <script>
    function previewImg() {
        const gambar = document.querySelector('#gambar');
        const imgPreview = document.querySelector('.img-preview');

        // Membaca file gambar yang dipilih
        const fileGambar = new FileReader();
        fileGambar.readAsDataURL(gambar.files[0]);

        fileGambar.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
    });
  </script>
</body>

</html>