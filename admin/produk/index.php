<?php 
session_start();
include '../../main/connect.php';
// Cek login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kasir Fwbi</title>
    
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

        .main-content {
            padding: 30px;
            width: 100%;
        }

        /* Card Container Styling */
        .content-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 25px 30px;
            background: white;
            border-bottom: 1px solid #f0f2f5;
        }

        /* Modern Table Styling */
        .table-responsive {
            padding: 0 20px 20px 20px;
        }

        .table thead th {
            background-color: #f8f9fa;
            border: none;
            color: #a0aec0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            font-weight: 700;
            padding: 15px 20px;
        }

        .table tbody td {
            padding: 18px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .table tbody tr:hover {
            background-color: rgba(118, 75, 162, 0.02);
        }

        /* Button Styling */
        .btn-add {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-edit { background: #fff8eb; color: #f6ad55; }
        .btn-edit:hover { background: #f6ad55; color: white; }
        
        .btn-delete { background: #fff5f5; color: #fc8181; }
        .btn-delete:hover { background: #fc8181; color: white; }

        /* Badge Custom */
        .badge-stock {
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .footer-assets {
            background: #fcfaff;
            border-radius: 0 0 24px 24px;
            padding: 20px 30px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="main-content">
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Manajemen Produk</h4>
            <p class="text-muted small">Atur dan pantau ketersediaan stok barang Anda.</p>
        </div>

        <div class="content-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>Daftar Inventaris
                </h5>
                <a href="tambah.php" class="btn-add">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </a>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $total_aset = 0;
                        $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                        
                        if(mysqli_num_rows($query) == 0){
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Belum ada data produk yang tersimpan.</td></tr>";
                        }

                        while($d = mysqli_fetch_array($query)){
                            $subtotal_produk = $d['Harga'] * $d['Stok'];
                            $total_aset += $subtotal_produk;
                        ?>
                        <tr>
                            <td><span class="text-muted fw-bold"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span></td>
                            <td>
                                <span class="fw-bold text-dark d-block"><?= $d['NamaProduk']; ?></span>
                                <span class="text-muted" style="font-size: 11px;">ID: PRD-<?= $d['ProdukID']; ?></span>
                            </td>
                            <td class="fw-bold">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <span class="badge-stock <?= $d['Stok'] < 10 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success'; ?>">
                                    <?= $d['Stok']; ?> Unit
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?= $d['ProdukID']; ?>" class="action-btn btn-edit" title="Edit Data">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $d['ProdukID']; ?>" class="action-btn btn-delete" title="Hapus Produk" onclick="return confirm('Menghapus produk akan berpengaruh pada data transaksi terkait. Yakin?')">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if(mysqli_num_rows($query) > 0): ?>
            <div class="footer-assets d-flex justify-content-between align-items-center">
                <span class="text-muted fw-semibold">Total Estimasi Nilai Aset:</span>
                <h4 class="text-primary fw-bold mb-0">Rp <?= number_format($total_aset, 0, ',', '.'); ?></h4>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>