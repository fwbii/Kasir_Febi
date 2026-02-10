<?php
// Mengambil nama folder aktif untuk menentukan menu mana yang 'active'
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
// Pastikan session sudah dimulai di file utama
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --sidebar-bg: #111827;
        --accent-color: #5e72e4;
        --hover-bg: rgba(255, 255, 255, 0.05);
        --text-gray: #9ca3af;
    }

    .sidebar-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--sidebar-bg);
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease;
        border-right: 1px solid rgba(255,255,255,0.05);
        display: flex;
        flex-direction: column;
    }

    .brand-logo {
        padding: 2rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(94, 114, 228, 0.3);
    }

    .brand-text {
        font-weight: 700;
        letter-spacing: -0.5px;
        font-size: 1.15rem;
        color: #fff;
        line-height: 1.2;
    }

    .nav-menu {
        padding: 0 1rem;
        flex-grow: 1;
        overflow-y: auto;
    }

    .nav-label {
        color: #6b7280;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 1.5rem 0 0.75rem 0.5rem;
    }

    .nav-link-custom {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: var(--text-gray) !important;
        text-decoration: none;
        border-radius: 12px;
        margin-bottom: 4px;
        font-size: 0.925rem;
        font-weight: 500;
        transition: all 0.25s ease;
    }

    .nav-link-custom i:first-child {
        width: 24px;
        font-size: 1.1rem;
        margin-right: 12px;
    }

    .nav-link-custom:hover {
        background-color: var(--hover-bg);
        color: #fff !important;
        transform: translateX(4px);
    }

    .nav-link-custom.active {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(94, 114, 228, 0.25);
    }

    .logout-container {
        padding: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        border-radius: 12px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444 !important;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid rgba(239, 68, 68, 0.2);
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-logout:hover {
        background: #ef4444;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .sidebar-spacer {
        width: 260px;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .sidebar-wrapper { transform: translateX(-100%); }
        .sidebar-spacer { display: none; }
    }
</style>

<div class="sidebar-wrapper shadow-lg">
    <div class="brand-logo">
        <div class="logo-icon">
           <i class="fa-brands fa-optin-monster"></i>
        </div>
        <div class="brand-info">
            <div class="brand-text">KASIR FWBI</div>
            <div style="font-size: 0.65rem; color: #5e72e4; font-weight: 700; letter-spacing: 0.5px;">
                <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i> 
                <?= isset($_SESSION['role']) ? strtoupper($_SESSION['role']) : 'GUEST'; ?> MODE
            </div>
        </div>
    </div>

    <div class="nav-menu">
        <div class="nav-label">Main Menu</div>
        <a href="../dashboard/index.php" class="nav-link-custom <?= ($current_dir == 'dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-house-chimney"></i> Dashboard
        </a>
        <a href="../penjualan/index.php" class="nav-link-custom <?= ($current_dir == 'penjualan') ? 'active' : ''; ?>">
            <i class="fa-solid fa-cart-plus"></i> Penjualan
        </a>
        <a href="../produk/index.php" class="nav-link-custom <?= ($current_dir == 'produk') ? 'active' : ''; ?>">
            <i class="fa-solid fa-box-archive"></i> Data Produk
        </a>
        <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'petugas' || $_SESSION['role'] == 'admin')): ?>
        <a href="../pelanggan/index.php" class="nav-link-custom <?= ($current_dir == 'pelanggan') ? 'active' : ''; ?>">
            <i class="fa-solid fa-people-group"></i> Pelanggan
        </a>
        <?php endif; ?>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <div class="nav-label">Administrator</div>
        <a href="../petugas/index.php" class="nav-link-custom <?= ($current_dir == 'petugas') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-gear"></i> Manajemen User
        </a>
        <?php endif; ?>

        <div class="nav-label">Reporting</div>
        <a href="../laporan/index.php" class="nav-link-custom <?= ($current_dir == 'laporan') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i> Laporan Keuangan
        </a>
    </div>

    <div class="logout-container">
        <a href="../../auth/logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-power-off me-2"></i> Keluar Sistem
        </a>
    </div>
</div>

<div class="sidebar-spacer"></div>