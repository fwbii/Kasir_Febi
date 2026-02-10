<?php 
session_start();
include '../../main/connect.php';

// Proteksi Login & Role
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') {
    header("location:../../admin/dashboard/index.php");
}

$username = $_SESSION['username'];
date_default_timezone_set('Asia/Jakarta');
$tgl_hari_ini = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f2f5; }
        .main-content { padding: 30px; width: 100%; }
        
        /* Welcome Box Modern */
        .welcome-banner {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            border-radius: 24px;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(94, 114, 228, 0.2);
        }
        .welcome-banner::after {
            content: "";
            position: absolute;
            right: -50px;
            top: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        /* Stats Card */
        .card-stats {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #fff;
        }
        .card-stats:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
        
        .icon-shape {
            width: 48px;
            height: 48px;
            background: #f6f9fc;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5e72e4;
        }

        /* Table Style */
        .table-custom { background: #fff; border-radius: 20px; overflow: hidden; }
        .table-custom thead th { background: #f8f9fe; border-bottom: none; color: #8898aa; font-size: 0.8rem; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="welcome-banner mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold mb-2">Halo, <?= strtoupper($username); ?>! 👋</h1>
                        <p class="opacity-75 mb-4">Senang melihatmu kembali. Hari ini adalah waktu yang tepat untuk memberikan pelayanan terbaik bagi pelanggan.</p>
                        <a href="../penjualan/index.php" class="btn btn-white text-light fw-bold rounded-pill px-4 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Mulai Transaksi Baru
                        </a>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="fas fa-cash-register fa-8x opacity-25"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card card-stats shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Transaksi Hari Ini</h6>
                                <?php 
                                    $query_trx = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                                    $data_trx = mysqli_fetch_assoc($query_trx);
                                ?>
                                <h2 class="fw-bold mb-0"><?= $data_trx['total']; ?> <span class="fs-6 fw-normal text-muted">Nota</span></h2>
                            </div>
                            <div class="icon-shape shadow-sm bg-primary-subtle">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card card-stats shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Omzet Hari Ini</h6>
                                <?php 
                                    $query_harian = mysqli_query($conn, "SELECT SUM(TotalHarga) as total_hari FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                                    $data_harian = mysqli_fetch_assoc($query_harian);
                                    $total_hari = $data_harian['total_hari'] ?? 0;
                                ?>
                                <h2 class="fw-bold mb-0 text-success">Rp <?= number_format($total_hari, 0, ',', '.'); ?></h2>
                            </div>
                            <div class="icon-shape shadow-sm bg-success-subtle text-success">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table-custom border-0 shadow-sm">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="fas fa-clock-rotate-left me-2 text-primary"></i>5 Transaksi Terakhir
                    </h5>
                    <a href="../laporan/index.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="ps-4">Waktu</th>
                                    <th>Pelanggan</th>
                                    <th>Total Bayar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $log = mysqli_query($conn, "SELECT * FROM penjualan 
                                       JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                       ORDER BY PenjualanID DESC LIMIT 5");
                                while($l = mysqli_fetch_array($log)){
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= date('H:i', strtotime($l['TanggalPenjualan'])); ?></div>
                                        <small class="text-muted"><?= date('d M Y', strtotime($l['TanggalPenjualan'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle p-2 me-2 text-center" style="width: 32px; height: 32px; line-height: 16px;">
                                                <i class="fas fa-user-circle text-muted"></i>
                                            </div>
                                            <span class="fw-semibold"><?= $l['NamaPelanggan']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">
                                            Rp <?= number_format($l['TotalHarga'], 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="../penjualan/detail.php?id=<?= $l['PenjualanID']; ?>" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Detail">
                                            <i class="fas fa-chevron-right text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php include '../../template/footer.php'; ?>
        </div>
    </div>
</body>
</html>