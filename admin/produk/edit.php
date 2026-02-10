<?php 
session_start();
include '../../main/connect.php';
// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);
$data = mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID='$id'");
$d = mysqli_fetch_array($data);

// Jika ID tidak ditemukan
if(!$d) header("location:index.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
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

        /* Card Styling */
        .content-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            max-width: 600px;
            margin: auto;
        }

        .card-header-custom {
            padding: 25px 30px;
            background: white;
            border-bottom: 1px solid #f0f2f5;
        }

        /* Form Styling */
        .form-label {
            font-weight: 700;
            color: #4a5568;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            background-color: #fcfaff;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #f6d365;
            box-shadow: 0 0 0 4px rgba(246, 211, 101, 0.15);
        }

        /* Button Styling */
        .btn-update {
            background: var(--warning-gradient);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(253, 160, 133, 0.4);
            color: white;
        }

        .btn-back {
            background: #f4f7fe;
            color: #718096;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        /* Badge ID */
        .id-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 800;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="col-md-8 mx-auto mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none fw-semibold">Produk</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark">Perbarui Data</li>
                </ol>
            </nav>
        </div>

        <div class="content-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3 text-warning fs-3">
                        <i class="fas fa-pen-nib"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-dark">Edit Detail Produk</h5>
                        <p class="text-muted small mb-0">Sesuaikan informasi stok dan harga</p>
                    </div>
                </div>
                <span class="id-badge">ID: PRD-<?= $d['ProdukID']; ?></span>
            </div>

            <div class="card-body p-4 p-md-5">
                <form id="formEdit" action="proses_edit.php" method="POST">
                    <input type="hidden" name="ProdukID" value="<?= $d['ProdukID']; ?>">
                    
                    <div class="mb-4">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="NamaProduk" class="form-control" value="<?= htmlspecialchars($d['NamaProduk']); ?>" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="Harga" class="form-control" value="<?= $d['Harga']; ?>" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="number" name="Stok" class="form-control" value="<?= $d['Stok']; ?>" min="0" required>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-5">
                        <button type="button" onclick="confirmEdit()" class="btn-update shadow-sm">
                            <i class="fas fa-sync-alt me-2"></i>Simpan Perubahan
                        </button>
                        <a href="index.php" class="btn-back">
                            <i class="fas fa-times me-2"></i>Batalkan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmEdit() {
    Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: "Pastikan data harga dan stok sudah sesuai.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#fda085',
        cancelButtonColor: '#cbd5e0',
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Cek Lagi',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEdit').submit();
        }
    })
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>