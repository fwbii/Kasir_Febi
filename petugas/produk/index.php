<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Stok - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7f6; }
        .card { border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #f8f9fe; color: #8898aa; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        .table td { vertical-align: middle; }
        .stok-number { font-weight: 700; font-size: 1.1rem; }
        .progress { height: 8px; border-radius: 10px; margin-top: 5px; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #e2e5e9; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark m-0">Manajemen Inventaris</h3>
                    <p class="text-muted small">Pantau dan kelola ketersediaan produk Anda</p>
                </div>
                <button class="btn btn-primary shadow-sm rounded-pill px-4" onclick="window.location.reload();">
                    <i class="fas fa-sync-alt me-2"></i> Refresh Data
                </button>
            </div>

            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelStok">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Produk</th>
                                    <th>Harga Jual</th>
                                    <th width="200">Ketersediaan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $sql = mysqli_query($conn, "SELECT * FROM produk ORDER BY Stok ASC");
                                while($d = mysqli_fetch_array($sql)){
                                    // Logika Pewarnaan
                                    if($d['Stok'] <= 0) {
                                        $color = "danger";
                                        $status_text = "Habis";
                                        $percent = 0;
                                    } elseif($d['Stok'] <= 10) {
                                        $color = "warning";
                                        $status_text = "Hampir Habis";
                                        $percent = 40;
                                    } else {
                                        $color = "success";
                                        $status_text = "Tersedia";
                                        $percent = 100;
                                    }
                                ?>
                                <tr>
                                    <td><span class="text-muted"><?= $no++; ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-<?= $color; ?>-subtle text-<?= $color; ?> rounded-3 p-2 me-3">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <span class="fw-bold text-dark"><?= $d['NamaProduk']; ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="stok-number text-<?= $color; ?>"><?= $d['Stok']; ?></span>
                                            <small class="text-muted">Unit</small>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-<?= $color; ?>" style="width: <?= $percent; ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-<?= $color; ?>-subtle text-<?= $color; ?> px-3 py-2">
                                            <i class="fas fa-circle fs-small me-1" style="font-size: 8px;"></i> <?= $status_text; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelStok').DataTable({
                "language": {
                    "search": "Cari Produk:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
                    "paginate": {
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    }
                },
                "pageLength": 10,
                "ordering": true
            });
        });
    </script>
</body>
</html>