<?php 
session_start();
include '../../main/connect.php';

// Proteksi Halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') header("location:../../petugas/dashboard/index.php");

$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,90%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 100%, hsla(225,39%,90%,1) 0, transparent 50%);
            min-height: 100vh;
        }

        .main-content { padding: 30px; width: 100%; }

        /* Card Laporan */
        .report-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .filter-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #edf2f7;
        }

        /* Table Styling */
        .table thead th {
            background: #f8faff;
            color: #718096;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px;
            border: none;
        }

        .table tbody td {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f4f8;
            vertical-align: middle;
        }

        .badge-nota {
            background: #f0ebf8;
            color: #764ba2;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
        }

        .total-row {
            background: #fdfdff;
            font-size: 1.1rem;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .report-card { border: none; box-shadow: none; }
            .main-content { padding: 0; }
        }

        .btn-print {
            background: #2d3748;
            color: white;
            border-radius: 12px;
            padding: 8px 20px;
            transition: 0.3s;
        }
        .btn-print:hover { background: #1a202c; color: white; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-0">Laporan Riwayat Transaksi</h4>
                <p class="text-muted small">Kelola dan pantau arus kas masuk Anda.</p>
            </div>
            <?php if($tgl_mulai != ''): ?>
            <button onclick="window.print()" class="btn btn-print no-print">
                <i class="fas fa-print me-2"></i> Cetak Laporan
            </button>
            <?php endif; ?>
        </div>

        <div class="filter-section no-print">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">DARI TANGGAL</label>
                    <input type="date" name="tgl_mulai" class="form-control rounded-3" value="<?= $tgl_mulai; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">SAMPAI TANGGAL</label>
                    <input type="date" name="tgl_selesai" class="form-control rounded-3" value="<?= $tgl_selesai; ?>" required>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm" style="background: var(--primary-gradient); border: none;">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="index.php" class="btn btn-light px-4 rounded-3 border">
                        <i class="fas fa-sync-alt me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="report-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="p-4 bg-white border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Data Penjualan Keseluruhan</h6>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Waktu Transaksi</th>
                            <th>Nama Pelanggan</th>
                            <th>ALAMAT</th>
                            <th>NO TELPON</th>
                            <th class="text-end">Total Pembayaran</th>
                            <th class="text-center no-print">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        if ($tgl_mulai != '' && $tgl_selesai != '') {
                            $query_str = "SELECT * FROM penjualan 
                                          JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                          WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
                                          ORDER BY PenjualanID DESC";
                        } else {
                            $query_str = "SELECT * FROM penjualan 
                                          JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                          ORDER BY PenjualanID DESC";
                        }
                        
                        $sql = mysqli_query($conn, $query_str);
                        
                        if(mysqli_num_rows($sql) == 0){
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted small'>Tidak ada data ditemukan.</td></tr>";
                        }

                        while($d = mysqli_fetch_array($sql)){
                            $grand_total += $d['TotalHarga'];
                        ?>
                        <tr>
                            <td><span class="badge-nota">#<?= $d['PenjualanID']; ?></span></td>
                            <td class="text-muted small"><?= date('d M Y, H:i', strtotime($d['TanggalPenjualan'])); ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= $d['NamaPelanggan']; ?></div>
                                <div class="text-muted" style="font-size: 11px;">Pelanggan ID: <?= $d['PelangganID']; ?></div>
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
                            <td class="text-center no-print">
                                <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-sm btn-light rounded-pill px-3" style="font-size: 12px;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="total-row">
                        <tr>
                            <th colspan="3" class="text-end py-4">Total Pendapatan :</th>
                            <th class="text-end py-4 text-primary fw-bold" style="font-size: 1.3rem;">
                                Rp <?= number_format($grand_total, 0, ',', '.'); ?>
                            </th>
                            <th class="no-print"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="d-none d-print-block mt-5">
            <div class="row">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    <p class="mb-5">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
                    <br><br>
                    <p class="fw-bold border-top pt-2">Manajer Toko</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>