<?php

include 'koneksi.php'; // Sertakan file koneksi.php

// Ambil data foto berdasarkan ID yang diberikan
$details = mysqli_query($conn, "SELECT * FROM foto INNER JOIN userfoto ON foto.UserID=userfoto.UserID WHERE foto.FotoID='{$_GET['id']}'");
$data = mysqli_fetch_array($details);

// Fungsi untuk mengirim notifikasi
function sendNotification($message) {
    echo "<script>alert('$message')</script>";
}

// Memeriksa apakah formulir komentar telah disubmit
if (isset($_POST['submit'])) {
    // Menyimpan data komentar ke dalam database
    $isi_komentar = $_POST['isi_komentar'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 14; // Gunakan user_id 14 jika tidak ada yang login
    $foto_id = $_POST['foto_id'];
    $tanggal_komentar = date('Y-m-d');

    // Query untuk menyimpan komentar ke dalam database
    $query_insert_komentar = "INSERT INTO komentar (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$foto_id', '$user_id', '$isi_komentar', '$tanggal_komentar')";
    $insert_result = mysqli_query($conn, $query_insert_komentar);

    // Memeriksa apakah komentar berhasil disimpan
    if ($insert_result) {
        // Kirim notifikasi
        sendNotification("Komentar berhasil ditambahkan!");

        // Redirect ke halaman detail setelah menyimpan komentar
        header("Refresh:0");
        exit();
    } else {
        // Kirim notifikasi
        sendNotification("Gagal menambahkan komentar. Silakan coba lagi.");
    }
}

// Ambil komentar dari database berdasarkan FotoID
$query_komentar = mysqli_query($conn, "SELECT komentar.*, userfoto.Username FROM komentar INNER JOIN userfoto ON komentar.UserID = userfoto.UserID WHERE komentar.FotoID = '{$data['FotoID']}'");

// Periksa apakah pengguna sudah login
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 14;

// Periksa apakah pengguna sudah memberikan "Like" pada foto
$ceksuka = mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='{$data['FotoID']}' AND UserID='$user_id'");
$liked = mysqli_num_rows($ceksuka) > 0;

// Hitung total "Like" pada foto
$total_likes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='{$data['FotoID']}'"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto</title>
    <!-- Tambahkan link CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Tambahkan link CSS Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Gaya CSS kustom -->
    <link rel="stylesheet" href="ujikom/assets/css/style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fafafa;
        margin: 0;
        padding: 0;
    }
    .container {
        margin-top: 20px;
    }
    .card {
        margin-bottom: 20px;
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .image-container {
        position: relative;
        margin-bottom: 20px;
    }
    .image-container img {
        width: 100%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .image-details {
        padding: 20px;
    }
    .image-details h3 {
        margin-top: 0;
    }
    .image-details p {
        margin-bottom: 0;
    }
    .comment-container {
        margin-top: 20px;
    }
    .comment {
        padding: 15px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }
    .comment strong {
        font-weight: bold;
        color: #333;
    }
    .comment .date {
        color: #888;
        font-size: 12px;
        margin-left: 5px;
    }
    .comment-form {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .comment-form input[type="text"] {
        width: calc(100% - 40px);
        border: none;
        padding: 10px;
        border-radius: 30px;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .comment-form .btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
    }
    .back-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
    .like-btn {
        color: <?= $liked ? 'red' : '#333' ?>;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="image-container">
                        <img src="uploads/<?= $data['NamaFile'] ?>" alt="<?= $data['JudulFoto'] ?>">
                    </div>
                    <!-- Tampilkan jumlah like dan komentar -->
                    <div class="image-details">
                        <h3><?= $data['JudulFoto'] ?></h3>
                        <p class="text-muted">by: <?= $data['Username'] ?>, <?= $data['TanggalUnggah'] ?></p><br>
                        <p><?= $data['DeskripsiFoto'] ?></p><br>
                        <div style="display: flex; align-items: center;">
                            <div style="margin-right: 20px;">
                                <a href="?id=<?= $data['FotoID'] ?>&like=1" class="like-btn"><i class="fas fa-heart"></i></a>
                                <?= $total_likes ?>
                            </div>
                            <div>
                                <i class="far fa-comment-alt"></i>
                                <?php
                                // Hitung total komentar pada foto
                                $total_comments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM komentar WHERE FotoID='{$data['FotoID']}'"));
                                echo $total_comments;
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Tampilkan komentar -->
                    <div class="comment-container">
                        <?php while ($komentar = mysqli_fetch_assoc($query_komentar)) : ?>
                            <div class="comment">
                                <strong><?= $komentar['Username'] ?></strong> - <span class="date"><?= $komentar['TanggalKomentar'] ?></span><br>
                                <?= $komentar['IsiKomentar'] ?>
                                <br>
                                <!-- Tampilkan ikon edit dan hapus hanya untuk komentar yang dimiliki oleh pengguna saat ini -->
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $komentar['UserID']) : ?>
                                    <div class="edit-delete">

                                        <!-- Tautan untuk mengedit komentar -->
                                        <a href="edit_komen.php?foto_id=<?= $data['FotoID'] ?>&edit_komen=<?= $komentar['KomentarID'] ?>"><i class="fas fa-edit"></i></a>
                                        <!-- Tautan untuk menghapus komentar -->
                                        <a href="delete_komen.php?foto_id=<?= $data['FotoID'] ?>&delete_komen=<?= $komentar['KomentarID'] ?>"><i class="fas fa-trash"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <!-- Form untuk menambahkan komentar -->
                    <div class="comment-form">
                        <form method="post" class="comment-form" action="">
                            <input type="hidden" name="foto_id" value="<?= $data['FotoID'] ?>">
                            <input type="text" name="isi_komentar" class="form-control" placeholder="Masukkan Komentar...">
                            <button type="submit" name="submit" class="btn btn-dark"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menu untuk kembali ke home.php -->
    <a href="?url=home.php" class="back-btn">Kembali</a>
</body>
</html>