<?php 
session_start();
include '../../main/connect.php';

// Proteksi Login & Role Admin
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

// Pastikan ID ada
if(!isset($_GET['id'])) header("location:index.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM user WHERE UserID='$id'");
$d = mysqli_fetch_array($query);

// Jika user tidak ditemukan
if(!$d) header("location:index.php");

// Proses Update
if(isset($_POST['update'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];

    if(empty($password)){
        // Update tanpa mengganti password
        $sql = "UPDATE user SET Username='$username', Role='$role' WHERE UserID='$id'";
    } else {
        // Update termasuk password (disarankan gunakan password_hash jika sistemmu mendukung)
        // Jika masih menggunakan MD5: $pass_fix = md5($password);
        $sql = "UPDATE user SET Username='$username', Role='$role', Password='$password' WHERE UserID='$id'";
    }

    if(mysqli_query($conn, $sql)){
        echo "<script>window.location='index.php?pesan=update_sukses';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Kasir Pro Elite</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f2f5; }
        .main-content { padding: 30px; width: 100%; }
        .card-custom { border-radius: 20px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.1); background: #fff; }
        .form-label { font-weight: 600; color: #525f7f; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px 15px; border: 1px solid #e9ecef; background-color: #f8f9fe; }
        .form-control:focus { border-color: #5e72e4; box-shadow: 0 3px 9px rgba(94, 114, 228, 0.1); }
        .input-group-text { background-color: #f8f9fe; border-radius: 12px; color: #adb5bd; }
        .btn-update { background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%); border: none; border-radius: 12px; padding: 12px; color: white; font-weight: bold; }
        .btn-update:hover { transform: translateY(-2px); box-shadow: 0 7px 14px rgba(45, 206, 137, 0.2); opacity: 0.9; color: white; }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-content d-flex align-items-center justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom p-3">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                            <h4 class="fw-bold m-0">Edit Profil User</h4>
                            <p class="text-muted small">Update informasi login petugas</p>
                        </div>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" class="form-control border-start-0 ps-0" value="<?= $d['Username']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role / Hak Akses</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-user-shield"></i></span>
                                    <select name="role" class="form-select border-start-0 ps-0">
                                        <option value="admin" <?= $d['Role'] == 'admin' ? 'selected' : ''; ?>>Admin (Akses Penuh)</option>
                                        <option value="petugas" <?= $d['Role'] == 'petugas' ? 'selected' : ''; ?>>Petugas (Kasir)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Ganti Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Biarkan kosong jika tetap">
                                </div>
                                <div class="form-text text-danger italic" style="font-size: 0.75rem;">
                                    *Hanya isi jika ingin merubah password lama.
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" name="update" class="btn btn-update">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                                <a href="index.php" class="btn btn-link text-muted text-decoration-none small mt-1">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>