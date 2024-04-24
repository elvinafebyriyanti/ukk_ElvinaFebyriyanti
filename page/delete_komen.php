<?php
include 'koneksi.php'; // Sertakan file koneksi.php

// Pastikan user_id tersedia dalam sesi
if (isset($_SESSION['user_id'])) {
    
    // Memeriksa apakah permintaan untuk menghapus komentar dikirim dan comment_id tersedia
    if (isset($_GET['comment_id'])) {
        $comment_id = $_GET['comment_id'];

        // Menghapus komentar dari database
        $delete_comment_query = mysqli_query($conn, "DELETE FROM komentar WHERE KomentarID='$comment_id'");

        // Memeriksa apakah komentar berhasil dihapus
        if ($delete_comment_query) {
            // Redirect kembali ke halaman detail setelah menghapus komentar
            header("Location: detail.php?id={$_GET['photo_id']}");
            exit();
        } else {
            // Kesalahan saat menghapus komentar
            echo "Gagal menghapus komentar. Silakan coba lagi.";
        }
    } else {
        // Jika comment_id tidak tersedia dalam permintaan
        echo "Komentar tidak ditemukan.";
    }
} else {
    // Jika user belum login
    echo "Anda belum login. Silakan <a href='login.php'>login</a> terlebih dahulu.";
}
?>
