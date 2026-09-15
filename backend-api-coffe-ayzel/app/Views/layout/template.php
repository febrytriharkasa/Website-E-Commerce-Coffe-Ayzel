<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f5f8fc;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }
        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background-color: #1c2536; /* Warna gelap ala Dash UI */
            color: #94a3b8;
            transition: all 0.3s;
        }
        .sidebar-brand {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
            padding: 1.5rem;
            text-decoration: none;
            display: block;
        }
        .sidebar-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            color: #64748b;
            padding: 1.5rem 1.5rem 0.5rem;
            margin: 0;
        }
        .sidebar-nav {
            padding: 0;
            list-style: none;
        }
        .sidebar-link {
            padding: 0.65rem 1.5rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.9rem;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }
        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .sidebar-link .arrow {
            margin-left: auto;
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }
        .sidebar-link[aria-expanded="true"] .arrow {
            transform: rotate(180deg);
        }
        /* Dropdown Menu */
        .sidebar-dropdown {
            list-style: none;
            padding-left: 0;
            background-color: #151c2b;
        }
        .sidebar-dropdown .sidebar-link {
            padding: 0.5rem 1.5rem 0.5rem 3.2rem;
            font-size: 0.85rem;
        }
        
        /* Content Styling */
        #content {
            width: 100%;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <a href="/" class="sidebar-brand">Dash UI</a>

        <ul class="sidebar-nav">
            <!-- Main Link -->
            <li class="sidebar-item">
                <a href="/" class="sidebar-link <?= (url_is('/')) ? 'active' : '' ?>">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>

            <!-- Section 1 -->
            <li class="sidebar-heading">Layouts & Pages</li>
            
             <li class="sidebar-item">
                <a href="/product" class="sidebar-link <?= (url_is('product*')) ? 'active' : '' ?>">
                    <i class="bi bi-layout-sidebar"></i> Data Produk
                </a>
            </li>

            <li class="sidebar-item">
                <a href="/sizes-product" class="sidebar-link <?= (url_is('/sizes-product*')) ? 'active' : '' ?>">
                    <i class="bi bi-layout-sidebar"></i> Atur Varian & Stok
                </a>
            </li>

            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-layout-sidebar"></i> Keuangan
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div id="content">
        <!-- Top Navbar (Opsional, untuk melengkapi UI Dashboard) -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 shadow-sm mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1 d-none d-lg-block">Dashboard</span>
            </div>
        </nav>

        <!-- Area render dari views (index.php) -->
        <div class="container-fluid px-4">
            <?= $this->renderSection('content'); ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS bundle (Termasuk Popper untuk Dropdown Collapse) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>