<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Kasir Fwbi #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f5f9;
            font-family: "Segoe UI", sans-serif;
        }

        .card-nota {
            max-width: 420px;
            margin: 30px auto;
            border-radius: 15px;
            border: none;
        }

        .nota-header {
            text-align: center;
        }

        .dashed-line {
            border-top: 1.5px dashed #444;
            margin: 12px 0;
        }

        .total-box {
            background: #f1f3f7;
            padding: 10px;
            border-radius: 10px;
        }

        .btn-print {
            background: linear-gradient(135deg,#0d6efd,#0a58ca);
            border: none;
        }

        .btn-print:hover {
            background: #0a58ca;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card-nota {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card card-nota shadow">
        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="nota-header mb-3">
                <h5 class="fw-bold mb-0">KASIR FWBI</h5>
                <small class="text-muted">Pekanbaru, Riau</small><br>
                <small class="text-muted">Telp: 0812-XXXX-XXXX</small>
                <div class="dashed-line"></div>
            </div>

            <!-- INFO -->
            <div class="row small mb-2">
                <div class="col-6">Nota: <b>#<?= $id; ?></b></div>
                <div class="col-6 text-end"><?= date('d/m/Y H:i', strtotime($data['TanggalPenjualan'])); ?></div>
                <div class="col-12 fw-bold text-uppercase mt-1">Pelanggan: <?= $data['NamaPelanggan']; ?></div>
                <div class="col-12">Alamat: <?= $data['Alamat']; ?></div>
                <div class="col-12">No. Telp: <?= $data['NomorTelepon']; ?></div>
            </div>

            <div class="dashed-line"></div>

            <!-- DETAIL -->
            <table class="table table-sm table-borderless small">
                <tbody>
                    <?php 
                    $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                              JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                              WHERE PenjualanID = '$id'");
                    while($d = mysqli_fetch_array($detail)){
                    ?>
                    <tr>
                        <td><?= $d['NamaProduk']; ?> <span class="text-muted">x<?= $d['JumlahProduk']; ?></span></td>
                        <td class="text-end">Rp <?= number_format($d['Subtotal'],0,',','.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="dashed-line"></div>

            <!-- TOTAL -->
            <div class="total-box d-flex justify-content-between fw-bold">
                <span>TOTAL</span>
                <span>Rp <?= number_format($data['TotalHarga'],0,',','.'); ?></span>
            </div>

            <!-- FOOTER -->
            <div class="text-center mt-4">
                <p class="small fw-bold mb-1">-- TERIMA KASIH --</p>
                <p class="small text-muted">Barang yang sudah dibeli tidak dapat dikembalikan</p>
            </div>

            <!-- BUTTON -->
            <div class="no-print mt-4 d-grid gap-2">
                <button onclick="window.print()" class="btn btn-print btn-lg text-white fw-bold">
                    <i class="fas fa-print me-2"></i> CETAK STRUK
                </button>
                <a href="index.php" class="btn btn-outline-secondary fw-bold">
                    <i class="fas fa-rotate-left me-2"></i> TRANSAKSI BARU
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
