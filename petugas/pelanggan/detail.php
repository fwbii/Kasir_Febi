<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya Admin yang boleh masuk
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') header("location:../../petugas/dashboard/index.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data penjualan & pelanggan
$query = mysqli_query($conn, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $id; ?> - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
        }

        .main-content { padding: 30px; width: 100%; }

        .invoice-card {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .invoice-header {
            background: var(--primary-gradient);
            padding: 40px;
            color: white;
        }

        .table thead th {
            background: #f8faff;
            color: #718096;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        .table tbody td {
            padding: 18px 12px;
            border-bottom: 1px solid #f1f4f8;
            color: #2d3748;
        }

        .total-section {
            background: #fcfaff;
            border-radius: 15px;
            padding: 20px;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            padding: 8px 20px;
            border-radius: 10px;
            transition: 0.3s;
        }

        /* --- BAGIAN PERBAIKAN PRINT --- */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .invoice-card { box-shadow: none !important; margin: 0; border: 1px solid #eee; }
            /* Perbaikan baris yang error: pastikan di dalam kurung kurawal @media print */
            .invoice-header { 
                background: #764ba2 !important; 
                color: white !important;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important;
            }
            .main-content { padding: 0; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="main-content">
        <div class="container py-2">
            <div class="invoice-card animate__animated animate__fadeIn">
                <div class="invoice-header d-flex justify-content-between align-items-start">
                    <div>
                        <a href="index.php" class="btn btn-back no-print mb-3 text-decoration-none small">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <h2 class="fw-bold mb-1">INVOICE</h2>
                        <p class="mb-0 opacity-75">Nota Pembelian #INV-<?= $id; ?></p>
                    </div>
                    <div class="text-end">
                        <h3 class="fw-bold mb-1">KASIR FWBI</h3>
                        <p class="mb-0 opacity-75 small">Sistem Kasir Elite</p>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row mb-5">
                        <div class="col-6">
                            <h6 class="text-muted small text-uppercase fw-bold mb-3">Ditujukan Untuk:</h6>
                            <h5 class="fw-bold text-dark mb-1"><?= $data['NamaPelanggan']; ?></h5>
                            <p class="text-muted mb-0"><?= $data['Alamat'] ?? 'Pelanggan Setia Kasir Pro'; ?></p>
                            <p class="text-muted small"><?= $data['NomorTelepon'] ?? ''; ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="text-muted small text-uppercase fw-bold mb-3">Detail Transaksi:</h6>
                            <p class="text-dark mb-1 fw-semibold">Tanggal: <?= date('d M Y', strtotime($data['TanggalPenjualan'])); ?></p>
                            <p class="text-dark mb-1">Waktu: <?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</p>
                            <p class="text-dark">Status: <span class="badge bg-success-subtle text-success border border-success-subtle">LUNAS</span></p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Produk / Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                          JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                          WHERE PenjualanID = '$id'");
                                while($d = mysqli_fetch_array($detail)){
                                ?>
                                <tr>
                                    <td class="text-muted"><?= $no++; ?></td>
                                    <td>
                                        <div class="fw-bold"><?= $d['NamaProduk']; ?></div>
                                        <small class="text-muted">ID: <?= $d['ProdukID']; ?></small>
                                    </td>
                                    <td class="text-center fw-semibold"><?= $d['JumlahProduk']; ?></td>
                                    <td class="text-end text-muted">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="total-section">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Metode Pembayaran</span>
                                    <span class="fw-bold text-dark">Tunai / Cash</span>
                                </div>
                                <hr class="my-3 opacity-50">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h6 mb-0 fw-bold">TOTAL BAYAR</span>
                                    <span class="h4 mb-0 fw-bold text-primary">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Terima kasih telah berbelanja di Kasir Fwbi.<br>Simpan nota ini sebagai bukti transaksi yang sah.</p>
                        <div class="no-print mt-4 d-flex justify-content-center gap-2">
                            <button onclick="window.print()" class="btn btn-dark btn-lg px-5 rounded-pill shadow-sm">
                                <i class="fas fa-print me-2"></i>Cetak Nota
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>