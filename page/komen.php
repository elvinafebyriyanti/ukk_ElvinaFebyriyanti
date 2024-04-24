<?php
include 'koneksi.php';
session_start();

// Memeriksa apakah formulir komentar telah disubmit
if (isset($_POST['submit'])) {
    // Menyimpan data komentar ke dalam database
    $isi_komentar = $_POST['isi_komentar'];
    $user_id = $_SESSION['user_id'];
    $foto_id = $_POST['foto_id'];
    $tanggal_komentar = date('Y-m-d');
    
    // Cek apakah komentar sudah pernah disimpan sebelumnya
    $query_check_comment = mysqli_query($conn, "SELECT * FROM komentar WHERE FotoID='$foto_id' AND UserID='$user_id' AND IsiKomentar='$isi_komentar'");
    if(mysqli_num_rows($query_check_comment) == 1) { // Jika komentar belum ada, simpan komentar
        // Query untuk menyimpan komentar ke dalam database
        $query_insert_komentar = "INSERT INTO komentar (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$foto_id', '$user_id', '$isi_komentar', '$tanggal_komentar')";
        mysqli_query($conn, $query_insert_komentar);
        
        // Redirect ke halaman detail setelah menyimpan komentar
        header("Location: ?url=detail&id=$foto_id");
        exit();
    } else { // Jika komentar sudah ada, beri pesan bahwa komentar telah terkirim sebelumnya
        echo "<script>alert('Komentar sudah terkirim sebelumnya.');</script>";
    }
}

?>

