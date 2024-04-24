<?php
include 'koneksi.php';

// Mulai sesi


// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak, redirect ke halaman login atau tindakan lain yang sesuai
    header("Location: login.php");
    exit();
}

// Periksa apakah ada parameter ID yang dikirimkan melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil data gambar berdasarkan ID
    $query = mysqli_query($conn, "SELECT * FROM foto WHERE FotoID = '$id'");
    $data = mysqli_fetch_assoc($query);

    // Ambil daftar album yang dimiliki oleh pengguna yang login
    $user_id = $_SESSION['user_id'];
    $albums = mysqli_query($conn, "SELECT * FROM album WHERE UserID = '$user_id'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Tangkap data yang dikirimkan melalui form
        $judul_foto = $_POST['judul_foto'];
        $deskripsi_foto = $_POST['deskripsi_foto'];
        $album_id = $_POST['album_id'];

        // Cek apakah ada upload file gambar baru
        if ($_FILES['namafile']['name']) {
            $nama_file = $_FILES['namafile']['name'];
            $tmp_foto = $_FILES['namafile']['tmp_name'];
            // Simpan file gambar ke folder yang diinginkan
            move_uploaded_file($tmp_foto, "uploads/" . $nama_file);
        } else {
            // Jika tidak ada upload gambar baru, gunakan gambar yang sudah ada
            $nama_file = $data['NamaFile'];
        }

        // Lakukan proses update data gambar
        $update = mysqli_query($conn, "UPDATE foto SET JudulFoto = '$judul_foto', DeskripsiFoto = '$deskripsi_foto', NamaFile= '$nama_file', AlbumID = '$album_id' WHERE FotoID = '$id'");
        if ($update) {
            // Redirect kembali ke halaman utama setelah berhasil melakukan update
            echo '<script>alert("Berhasil Mengedit Data")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=home">';
        } else {
            echo '<script>alert("Gagal Mengedit Data")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=edit&&id">';
        }
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gambar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-size: 14px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
            max-height: 300px; /* Tambahkan untuk membatasi tinggi gambar */
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-3">Edit Gambar</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Foto</label>
            <input type="text" class="form-control" name="judul_foto" value="<?= $data['JudulFoto'] ?>" required>
        </div>
        <div class="form-group">
            <label>Deskripsi Foto</label>
            <textarea name="deskripsi_foto" class="form-control" required><?= $data['DeskripsiFoto'] ?></textarea>
        </div>
        <div class="form-group">
            <label>Gambar Saat Ini</label><br>
            <img src="uploads/<?= $data['NamaFile'] ?>" alt="<?= $data['JudulFoto'] ?>">
        </div>
        <div class="form-group">
            <label>Pilih Gambar Baru (Opsional)</label>
            <input type="file" name="namafile" class="form-control" accept="image/jpeg, image/png, image/gif">
        </div>
        <div class="form-group">
            <label>Pilih Album</label>
            <select name="album_id" class="form-control">
                <?php while ($album = mysqli_fetch_assoc($albums)): ?>
                    <option value="<?= $album['AlbumID'] ?>" <?= ($album['AlbumID'] == $data['AlbumID']) ? 'selected' : '' ?>><?= $album['NamaAlbum'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
