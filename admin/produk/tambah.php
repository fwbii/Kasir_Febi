<?php 
session_start();
include '../../main/connect.php';
// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            background-color: #fcfaff;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #764ba2;
            box-shadow: 0 0 0 4px rgba(118, 75, 162, 0.1);
        }

        .input-group-text {
            border-radius: 14px 0 0 14px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0 18px;
        }

        .input-group .form-control {
            border-radius: 0 14px 14px 0;
        }

        /* Button Styling */
        .btn-simpan {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-simpan:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }

        .btn-batal {
            background: #f4f7fe;
            color: #718096;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-batal:hover {
            background: #edf2f7;
            color: #4a5568;
        }

        /* Custom Icon Box */
        .icon-box {
            width: 45px;
            height: 45px;
            background: rgba(118, 75, 162, 0.1);
            color: #764ba2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
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
                    <li class="breadcrumb-item active fw-bold text-dark">Tambah Baru</li>
                </ol>
            </nav>
        </div>

        <div class="content-card">
            <div class="card-header-custom d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0 text-dark">Entry Produk Baru</h5>
                    <p class="text-muted small mb-0">Lengkapi data inventaris dengan benar</p>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form id="formTambah" action="proses_tambah.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap Produk</label>
                        <input type="text" name="NamaProduk" class="form-control" placeholder="Masukkan nama produk..." required autocomplete="off">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-7">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="Harga" class="form-control" placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="Stok" class="form-control" placeholder="0" min="0" required>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <button type="button" onclick="confirmAdd()" class="btn-simpan w-100 shadow-sm">
                                <i class="fas fa-check-circle me-2"></i>Simpan Produk
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="index.php" class="btn-batal w-100 text-center text-decoration-none d-block">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmAdd() {
    const form = document.getElementById('formTambah');
    const nama = form.NamaProduk.value;
    const harga = form.Harga.value;
    const stok = form.Stok.value;

    if(!nama || !harga || !stok) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops!',
            text: 'Tolong isi semua data produk ya.',
            confirmButtonColor: '#764ba2',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    if(harga < 0 || stok < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Nilai Negatif',
            text: 'Harga atau stok tidak boleh kurang dari 0.',
            confirmButtonColor: '#764ba2',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Simpan',
        text: `Ingin menambahkan "${nama}" ke dalam stok?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#764ba2',
        cancelButtonColor: '#cbd5e0',
        confirmButtonText: 'Ya, Tambahkan!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>