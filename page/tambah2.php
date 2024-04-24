<?php
include 'koneksi.php';



// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak, redirect ke halaman login atau tindakan lain yang sesuai
    header("Location: login.php");
    exit();
}

// Periksa apakah ada permintaan untuk membuat album baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nama_album = $_POST['nama_album'];
    $deskripsi_album = $_POST['deskripsi_album'];
    $tanggal_dibuat = date('Y-m-d');
    $user_id = $_SESSION['user_id'];

    // Lakukan insert data album dengan menyertakan ID pengguna yang sedang login
    $insert = mysqli_query($conn, "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDiBuat, UserID) VALUES ('$nama_album', '$deskripsi_album', '$tanggal_dibuat', '$user_id')");
    if ($insert) {
        echo '<script>alert("Album berhasil dibuat")</script>';
    } else {
        echo '<script>alert("Gagal membuat album")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Album Baru</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #28a745;
            color: #fff;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1>Tambah Album Baru</h1>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nama_album">Nama Album</label>
                            <input type="text" class="form-control" id="nama_album" name="nama_album" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_album">Deskripsi Album</label>
                            <textarea class="form-control" id="deskripsi_album" name="deskripsi_album" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success">Tambah Album</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
