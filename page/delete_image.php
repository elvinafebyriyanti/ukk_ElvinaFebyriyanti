<?php
include 'koneksi.php';

// Periksa apakah ada parameter ID yang dikirimkan melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus terlebih dahulu entri yang terkait di tabel likes
    $delete_likes = mysqli_query($conn, "DELETE FROM likes WHERE FotoID = '$id'");
    
    // Lakukan proses hapus data gambar hanya jika penghapusan entri di tabel likes berhasil
    if ($delete_likes) {
        $delete_foto = mysqli_query($conn, "DELETE FROM foto WHERE FotoID = '$id'");
        if ($delete_foto) {
            // Redirect kembali ke halaman utama setelah berhasil menghapus
            header("Location: index.php");
            exit();
        } else {
            echo "Gagal melakukan hapus gambar.";
        }
    } else {
        echo "Gagal menghapus entri terkait di tabel likes.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
