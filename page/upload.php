<?php
include 'koneksi.php';


// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect ke halaman login jika pengguna belum login
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID pengguna yang sedang login
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Tambahkan link CSS Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        /* Gaya untuk tombol */
        .btn {
            padding: 8px 16px;
            margin-right: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Gaya untuk gambar */
        .thumbnail {
            max-width: 100px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
</style>

    
</head>
<body>




<br><br><br><br>
<!-- Modal untuk mengedit album -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Daftar Gambar yang Diunggah</h1>
            <!-- Tambahkan tombol untuk kembali ke halaman upload -->
            <a href="?url=tambah1" class="btn btn-primary mb-3">Upload Gambar Baru</a>
            <a href="?url=tambah2" class="btn btn-primary mb-3">Tambah Album Baru</a>


            <!-- Tabel untuk menampilkan gambar yang diunggah -->
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Tanggal Unggah</th>
                        <th>Album</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data gambar yang diunggah oleh pengguna yang sedang login
                    $query = mysqli_query($conn, "SELECT * FROM foto WHERE UserID = '$user_id'");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Ambil informasi album dari database berdasarkan AlbumID pada setiap gambar
                        $album_info = mysqli_query($conn, "SELECT * FROM album WHERE AlbumID = '" . $row['AlbumID'] . "'");
                        $album_data = mysqli_fetch_assoc($album_info);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['JudulFoto'] ?></td>
                            <td><?= $row['DeskripsiFoto'] ?></td>
                            <td><img src="uploads/<?= $row['NamaFile'] ?>" alt="<?= $row['JudulFoto'] ?>" style="max-width: 100px;"></td>
                            <td><?= $row['TanggalUnggah'] ?></td>
                            <td><?= $album_data['NamaAlbum'] ?></td>
                            <td>
                            <a href="?url=edit&&id=<?= $row['FotoID'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <a href="?url=delete&&id=<?= $row['FotoID'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')"><i class="fas fa-trash"></i></a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>
