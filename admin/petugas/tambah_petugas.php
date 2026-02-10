<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya admin yang boleh mengakses halaman ini
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
        }

        .main-content { padding: 30px; width: 100%; }

        .card-custom {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            background: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #525f7f;
            font-size: 0.85rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e9ecef;
            background-color: #f8f9fe;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: #5e72e4;
            box-shadow: 0 3px 9px rgba(94, 114, 228, 0.15);
        }

        .input-group-text {
            background-color: #f8f9fe;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            color: #adb5bd;
        }

        .btn-register {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(94, 114, 228, 0.25);
            opacity: 0.9;
        }

        .btn-cancel {
            border-radius: 12px;
            padding: 12px;
            color: #8898aa;
            font-weight: 600;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: rgba(94, 114, 228, 0.1);
            color: #5e72e4;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-content d-flex align-items-center justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom p-3">
                    <div class="card-body">
                        <div class="header-icon">
                            <i class="fas fa-user-plus fa-lg"></i>
                        </div>
                        <h4 class="fw-bold text-center mb-1">Registrasi Akun</h4>
                        <p class="text-muted text-center small mb-4">Tambahkan akses baru untuk tim Anda</p>
                        
                        <form action="proses_tambah.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-user"></i></span>
                                    <input type="text" name="Username" class="form-control border-start-0 ps-0" placeholder="Masukkan username" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="Password" class="form-control border-start-0 ps-0" placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Role / Level Akses</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-user-shield"></i></span>
                                    <select name="Role" class="form-select border-start-0 ps-0" required>
                                        <option value="" selected disabled>-- Pilih Level --</option>
                                        <option value="admin">Admin (Akses Penuh)</option>
                                        <option value="petugas">Petugas (Akses Kasir)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-register fw-bold text-white">
                                    <i class="fas fa-check-circle me-2"></i>Daftarkan Akun
                                </button>
                                <a href="index.php" class="btn btn-link btn-cancel text-decoration-none mt-1">
                                    Batalkan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>