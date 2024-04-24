<?php
include 'koneksi.php'; // Sertakan file koneksi.php

// Pastikan user_id tersedia dalam sesi
if (isset($_SESSION['user_id'])) {
    
    // Memeriksa apakah permintaan untuk mengedit komentar dikirim dan comment_id tersedia
    if (isset($_GET['comment_id'])) {
        $comment_id = $_GET['comment_id'];
        $edit_comment_query = mysqli_query($conn, "SELECT * FROM komentar WHERE KomentarID='$comment_id'");
        $comment_data = mysqli_fetch_assoc($edit_comment_query);

        // Memeriksa apakah komentar ditemukan
        if ($comment_data) {
            // Memeriksa apakah pengguna memiliki izin untuk mengedit komentar
            if ($comment_data['UserID'] == $_SESSION['user_id']) {
                if (isset($_POST['submit_edit'])) {
                    // Mengambil data yang diedit dari formulir
                    $edited_comment = $_POST['edited_comment'];

                    // Query untuk mengupdate komentar
                    $update_comment_query = mysqli_query($conn, "UPDATE komentar SET IsiKomentar='$edited_comment' WHERE KomentarID='$comment_id'");

                    if ($update_comment_query) {
                        // Redirect kembali ke halaman detail setelah mengedit komentar
                        header("Location: detail.php?id={$comment_data['FotoID']}");
                        exit();
                    } else {
                        // Kesalahan saat mengupdate komentar
                        echo "Gagal mengupdate komentar. Silakan coba lagi.";
                    }
                }

                // Formulir untuk mengedit komentar
                echo "<form method='post' action=''>";
                echo "<div class='form-group'>";
                echo "<input type='text' name='edited_comment' class='form-control' value='{$comment_data['IsiKomentar']}' placeholder='Edit Komentar...'>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<button type='submit' name='submit_edit' class='btn btn-primary'>Simpan Perubahan</button>";
                echo "</div>";
                echo "</form>";
            } else {
                // Pengguna tidak memiliki izin untuk mengedit komentar
                echo "Anda tidak memiliki izin untuk mengedit komentar ini.";
            }
        } else {
            // Komentar tidak ditemukan
            echo "Komentar tidak ditemukan.";
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
