

<?php
session_start();
include 'koneksi.php';

// Periksa apakah user_id telah diset
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Jika user_id tidak tersedia dalam sesi, atur ke nilai default (misalnya, user_id anonim)
    $user_id = 14; // Atur ke nilai user_id yang sesuai untuk pengguna anonim
}

// Periksa apakah ada parameter FotoID yang diberikan
if (isset($_GET['FotoID'])) {
    $foto_id = $_GET['FotoID'];

    // Periksa apakah pengguna sudah memberikan "Like" pada foto
    $ceksuka = mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='$foto_id' AND UserID='$user_id'");
    if (!$ceksuka) {
        die('Error: ' . mysqli_error($conn)); // Cetak pesan kesalahan secara lengkap
    }

    if(mysqli_num_rows($ceksuka) == 1) {
        // Hapus "Like" jika sudah diberikan sebelumnya
        $query = mysqli_query($conn, "DELETE FROM likes WHERE FotoID='$foto_id' AND UserID='$user_id'");
    } else {
        // Tambahkan "Like" jika belum diberikan sebelumnya
        $tanggal_like = date('Y-m-d');
        $query = mysqli_query($conn, "INSERT INTO likes (FotoID, UserID, TanggalLike) VALUES ('$foto_id', '$user_id', '$tanggal_like')");
    }

    // Redirect kembali ke halaman home setelah menambah atau menghapus "Like"
    header("Location: ../page/home.php");
    exit();
} else {
    // Jika parameter FotoID tidak tersedia, redirect kembali ke halaman home
    header("Location: ../page/home.php");
    exit();
}
?>
