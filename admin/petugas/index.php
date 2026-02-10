<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya admin yang boleh mengelola user
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Kasir Fwbi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
        }

        .main-content { padding: 30px; width: 100%; }

        /* Card Style */
        .card-custom {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        /* Table Style */
        .table thead th {
            background-color: #f8faff;
            color: #8898aa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 15px;
            border-bottom: 2px solid #edf2f9;
        }

        .table tbody td {
            padding: 15px;
            color: #525f7f;
            vertical-align: middle;
        }

        /* Avatar Placeholder */
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 12px;
        }

        /* Badge Custom */
        .badge-role {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        /* Action Buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-action:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Manajemen User</h3>
                    <p class="text-muted small mb-0">Atur hak akses dan akun petugas kasir Anda</p>
                </div>
                <a href="tambah_petugas.php" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fas fa-user-plus me-2"></i>Tambah User
                </a>
            </div>

            <div class="card card-custom">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="80">NO</th>
                                    <th>USER INFO</th>
                                    <th>HAK AKSES</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM user");
                                while($d = mysqli_fetch_array($query)){
                                    // Ambil inisial untuk avatar
                                    $initial = strtoupper(substr($d['Username'], 0, 1));
                                ?>
                                <tr>
                                    <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle shadow-sm">
                                                <?= $initial ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= $d['Username']; ?></div>
                                                <small class="text-muted">ID: #USR-<?= $d['UserID']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($d['Role'] == 'admin'): ?>
                                            <span class="badge badge-role bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="fas fa-user-shield me-1"></i> ADMIN
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-role bg-info-subtle text-info border border-info-subtle">
                                                <i class="fas fa-user-tag me-1"></i> PETUGAS
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit_petugas.php?id=<?= $d['UserID']; ?>" 
                                               class="btn btn-action btn-outline-primary" 
                                               title="Edit User">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <button onclick="confirmDelete(<?= $d['UserID']; ?>)" 
                                                    class="btn btn-action btn-outline-danger" 
                                                    title="Hapus User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
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

    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus User?',
            text: "User yang dihapus tidak dapat mengakses sistem kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4757',
            cancelButtonColor: '#a4b0be',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            border: 'none',
            borderRadius: '20px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        })
    }
    </script>

    <?php if(isset($_GET['pesan'])): ?>
        <?php if($_GET['pesan'] == 'sukses'): ?>
            <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'User baru telah didaftarkan.', borderRadius: '20px' });</script>
        <?php elseif($_GET['pesan'] == 'hapus'): ?>
            <script>Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Data user telah dihapus.', borderRadius: '20px' });</script>
        <?php endif; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>