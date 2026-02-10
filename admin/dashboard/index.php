<?php 
session_start();
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
include '../../main/connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kasir Fwbi</title>
    
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

        /* Sidebar Glass Effect */
        #sidebar-wrapper {
            background: var(--primary-gradient) !important;
            border-right: none;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
        }

        /* Main Content Style */
        .main-content {
            padding: 30px;
        }

        /* Hero Welcome Section */
        .welcome-card {
            background: var(--primary-gradient);
            color: white;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            border: none;
            box-shadow: 0 15px 35px rgba(118, 75, 162, 0.2);
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        /* Stats Card Modern */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 20px;
            padding: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .icon-shape {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        /* Table Design */
        .table-container {
            background: white;
            border-radius: 24px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: none;
        }

        .table thead th {
            border: none;
            color: #a0aec0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 20px;
        }

        .table tbody td {
            padding: 20px;
            border-bottom: 1px solid #f7fafc;
            vertical-align: middle;
        }

        .badge-stok {
            background: #fff5f5;
            color: #e53e3e;
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-update {
            background: #f4f7fe;
            color: #764ba2;
            border: none;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-update:hover {
            background: #764ba2;
            color: white;
        }
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">
    <?php include '../../template/sidebar.php'; ?>

    <div class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Ringkasan Bisnis</h4>
                <p class="text-muted small">Update terakhir: <?php echo date('d M Y, H:i'); ?></p>
            </div>
            <div class="d-flex align-items-center">
                <div class="text-end me-3">
                    <span class="d-block fw-bold text-dark"><?php echo $_SESSION['username']; ?></span>
                    <span class="badge bg-success-soft text-success small">Admin Active</span>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=764ba2&color=fff" class="rounded-circle shadow-sm" width="45">
            </div>
        </div>

        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold">Selamat Datang di Kasir FWBI !</h2>
                    <p class="opacity-75 mb-0">Kelola stok, pantau penjualan, dan tingkatkan performa tokomu.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Total Produk</span>
                    <?php 
                        $data_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"));
                        echo "<h2 class='fw-bold mb-0 mt-2'>" . $data_produk['total'] . "</h2>";
                    ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Order Hari Ini</span>
                    <?php 
                        date_default_timezone_set('Asia/Jakarta'); $tgl = date('Y-m-d');
                        $data_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl%'"));
                        echo "<h2 class='fw-bold mb-0 mt-2'>" . ($data_hari_ini['total'] ?? 0) . "</h2>";
                    ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Pelanggan</span>
                    <?php 
                        $jml_plg = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT PelangganID FROM penjualan"));
                        echo "<h2 class='fw-bold mb-0 mt-2'>$jml_plg</h2>";
                    ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="text-muted small fw-bold text-uppercase">Petugas</span>
                    <?php 
                        $hasil_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user"));
                        echo "<h2 class='fw-bold mb-0 mt-2'>" . $hasil_user['total'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Stok Hampir Habis
                </h5>
                <a href="../produk/index.php" class="btn btn-sm btn-link text-decoration-none fw-bold">Lihat Semua Produk</a>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Status Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stok_low = mysqli_query($conn, "SELECT * FROM produk WHERE Stok < 10 ORDER BY Stok ASC");
                        while($d = mysqli_fetch_assoc($stok_low)){
                        ?>
                        <tr>
                            <td>
                                <span class="fw-bold text-dark d-block"><?php echo $d['NamaProduk']; ?></span>
                                <span class="text-muted small">ID: <?php echo $d['ProdukID']; ?></span>
                            </td>
                            <td class="fw-bold">Rp <?php echo number_format($d['Harga']); ?></td>
                            <td>
                                <span class="badge-stok">
                                    Sisa <?php echo $d['Stok']; ?> Unit
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="../produk/edit.php?id=<?= $d['ProdukID']; ?>" class="btn btn-update btn-sm">
                                    Update Stok
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../template/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>