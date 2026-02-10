<?php 
session_start();
include '../../main/connect.php';

// 1. Proteksi Login & Role (Penting!)
if($_SESSION['status'] != "login") {
    header("location:../../auth/login.php");
    exit;
}

// 2. Keamanan: Cek apakah ID ada di URL dan bersihkan
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("location:index.php");
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. Ambil data utama (Penjualan + Pelanggan)
$query = mysqli_query($conn, "SELECT * FROM penjualan 
          JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
          WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// 4. Validasi jika data tidak ditemukan di database
if (!$data) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .card-detail { border-radius: 20px; border: none; overflow: hidden; }
        .sidebar-container { min-width: 250px; transition: all 0.3s; }
        
        /* Garis putus-putus ala struk belanja */
        .divider { border-top: 2px dashed #e9ecef; margin: 2rem 0; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .card-detail { box-shadow: none !important; border: 1px solid #eee; }
            .container-fluid { padding: 0 !important; }
            .main-content { width: 100% !important; margin: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print sidebar-container">
            <?php include '../../template/sidebar.php'; ?>
        </div>

        <div class="container-fluid p-4 main-content">
            <div class="col-md-10 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <a href="index.php" class="btn btn-white shadow-sm border rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-print me-2"></i>Cetak Struk
                    </button>
                </div>

                <div class="card card-detail shadow-lg animate__animated animate__fadeIn">
                    <div class="card-body p-4 p-md-5">
                        <div class="row mb-5">
                            <div class="col-6">
                                <h2 class="fw-bold text-dark mb-0">INVOICE</h2>
                                <p class="text-muted">#TRX-<?= str_pad($id, 5, '0', STR_PAD_LEFT); ?></p>
                            </div>
                            <div class="col-6 text-end">
                                <h4 class="fw-bold text-primary mb-0">KASIR FWBI</h4>
                                <p class="small text-muted mb-0">Solusi Bisnis Modern</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-6 mb-4 mb-sm-0">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3">Customer:</h6>
                                <h5 class="fw-bold mb-1"><?= $data['NamaPelanggan']; ?></h5>
                                <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-2"></i><?= $data['Alamat']; ?></p>
                                <p class="text-muted small"><i class="fas fa-phone me-2"></i><?= $data['NomorTelepon']; ?></p>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3">Tanggal:</h6>
                                <h5 class="fw-bold mb-1"><?= date('d M Y', strtotime($data['TanggalPenjualan'])); ?></h5>
                                <p class="text-muted small"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</p>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">LUNAS</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="border-bottom">
                                    <tr class="text-muted small text-uppercase">
                                        <th class="py-3">Item Deskripsi</th>
                                        <th class="text-center py-3">Harga</th>
                                        <th class="text-center py-3">Qty</th>
                                        <th class="text-end py-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                              JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                              WHERE PenjualanID = '$id'");
                                    while($d = mysqli_fetch_array($detail)){
                                    ?>
                                    <tr class="border-bottom">
                                        <td class="py-4">
                                            <span class="fw-bold d-block text-dark"><?= $d['NamaProduk']; ?></span>
                                            <small class="text-muted">ID: <?= $d['ProdukID']; ?></small>
                                        </td>
                                        <td class="text-center">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <span class="fw-bold"><?= $d['JumlahProduk']; ?></span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end py-4 fw-bold text-muted">TOTAL AKHIR</td>
                                        <td class="text-end py-4 h3 fw-bold text-primary">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="divider"></div>
                        
                        <div class="text-center">
                            <h6 class="fw-bold mb-2">Terima Kasih Atas Kunjungan Anda!</h6>
                            <p class="text-muted small mb-0">Barang yang sudah dibayar tidak dapat dikembalikan.</p>
                            <p class="text-muted small">Cetak digital: <?= date('d/m/Y H:i:s'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>