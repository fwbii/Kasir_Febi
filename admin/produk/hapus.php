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
    <title>Proses Hapus - Kasir Pro Elite</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7fe; 
        }
        /* Custom SweetAlert Style agar senada dengan tema */
        .swal2-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
        }
        .swal2-title {
            font-weight: 700 !important;
            color: #2d3748 !important;
        }
    </style>
</head>
<body>

<?php 
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. CEK INTEGRITAS DATA: Apakah produk ini punya riwayat transaksi? 
    $cek_transaksi = mysqli_query($conn, "SELECT * FROM detailpenjualan WHERE ProdukID='$id' LIMIT 1");
    
    if (mysqli_num_rows($cek_transaksi) > 0) {
        // Notifikasi Gagal karena Foreign Key Integrity
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Aksi Ditolak!',
                text: 'Produk ini tidak bisa dihapus karena sudah memiliki riwayat transaksi dalam sistem.',
                confirmButtonColor: '#764ba2',
                confirmButtonText: 'Kembali',
                customClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            }).then(() => { window.location.href = 'index.php'; });
        </script>";
    } else {
        // 2. PROSES HAPUS (Hanya jika aman)
        $query = mysqli_query($conn, "DELETE FROM produk WHERE ProdukID='$id'");

        if ($query) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: 'Produk berhasil dibersihkan dari database.',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => { window.location.href = 'index.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Sistem',
                    text: 'Terjadi kendala teknis saat mencoba menghapus data.',
                    confirmButtonColor: '#764ba2'
                }).then(() => { window.location.href = 'index.php'; });
            </script>";
        }
    }
} else {
    header("location:index.php");
}
?>

</body>
</html>