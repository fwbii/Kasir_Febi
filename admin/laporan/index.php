<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya Admin yang boleh masuk
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? mysqli_real_escape_string($conn, $_GET['tgl_mulai']) : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? mysqli_real_escape_string($conn, $_GET['tgl_selesai']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --glass: rgba(255, 255, 255, 0.9);
            --primary-grad: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --secondary-grad: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
        }

        .main-content { padding: 30px; width: 100%; }

        /* Card Customization */
        .card-stat {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .card-stat:hover { transform: translateY(-5px); }

        .filter-box {
            border-radius: 20px;
            background: white;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .table thead th {
            background-color: #f8faff;
            color: #8898aa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 15px;
            border-bottom: 2px solid #edf2f9;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            line-height: 35px;
            padding: 0;
            border-radius: 10px;
        }

        /* Print Optimization */
        @media print {
            .no-print, .sidebar, .btn, .filter-box { display: none !important; }
            body { background: white !important; }
            .main-content { padding: 0; }
            .card-stat { border: 1px solid #eee !important; box-shadow: none !important; }
            .d-print-header { display: block !important; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h3 class="fw-bold mb-1">Laporan Penjualan</h3>
                <p class="text-muted small mb-0">Kelola dan pantau performa bisnismu</p>
            </div>
            <button class="btn btn-dark btn-lg px-4 rounded-pill shadow-sm fw-bold" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
        </div>

        <div class="card filter-box mb-4 no-print p-2">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">DARI TANGGAL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tgl_mulai" class="form-control border-0 bg-light shadow-none" value="<?= $tgl_mulai ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">SAMPAI TANGGAL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tgl_selesai" class="form-control border-0 bg-light shadow-none" value="<?= $tgl_selesai ?>">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold rounded-3">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary px-3 rounded-3">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php 
        $where = "";
        if($tgl_mulai != '' && $tgl_selesai != '') {
            $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
        }
        $summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
        $ds = mysqli_fetch_assoc($summary);
        ?>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card card-stat shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-bold mb-2">OMSET PENJUALAN</h6>
                                <h2 class="fw-bold mb-0">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></h2>
                            </div>
                            <div class="icon-shape bg-success-subtle text-success p-3 rounded-circle">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card card-stat shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-bold mb-2">TOTAL TRANSAKSI</h6>
                                <h2 class="fw-bold mb-0"><?= number_format($ds['jml'] ?? 0); ?> <small class="text-muted h6">Nota</small></h2>
                            </div>
                            <div class="icon-shape bg-primary-subtle text-primary p-3 rounded-circle">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="text-center my-4 d-none d-print-block">
                    <h2 class="fw-bold">LAPORAN PENJUALAN KASIR PRO</h2>
                    <?php if($tgl_mulai != ''): ?>
                        <p class="mb-1">Periode: <?= date('d M Y', strtotime($tgl_mulai)) ?> s/d <?= date('d M Y', strtotime($tgl_selesai)) ?></p>
                    <?php else: ?>
                        <p class="mb-1">Periode: Semua Waktu</p>
                    <?php endif; ?>
                    <p class="small text-muted italic">Dicetak secara sistem pada: <?= date('d/m/Y H:i'); ?></p>
                    <hr>
                </div>

                <div class="table-responsive p-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>TANGGAL & WAKTU</th>
                                <th>NAMA PELANGGAN</th>
                                <th>ALAMAT</th>
                                <th>NomorTelepon</th>
                                <th class="text-end">NOMINAL</th>
                                <th class="no-print text-center">OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM penjualan 
                                     JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                     $where ORDER BY TanggalPenjualan DESC");
                            
                            if(mysqli_num_rows($query) == 0) : ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/gray/no-messages.svg" style="width: 150px;" class="mb-3">
                                        <p class="text-muted">Tidak ada transaksi ditemukan pada periode ini.</p>
                                    </td>
                                </tr>
                            <?php endif;

                            while($d = mysqli_fetch_array($query)): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle text-center" style="width: 35px; height: 35px; line-height: 35px;">
                                            <i class="fas fa-user text-muted small"></i>
                                        </div>
                                        <span class="fw-semibold text-dark"><?= $d['NamaPelanggan']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $d['Alamat']; ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $d['NomorTelepon']; ?></div>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                </td>
                                <td class="no-print text-center">
                                    <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-light btn-action" title="Lihat Detail">
                                        <i class="fas fa-external-link-alt text-primary small"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>