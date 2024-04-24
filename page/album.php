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
    <title>Album Foto</title>
    
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Gaya tambahan jika diperlukan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
    /* Gaya untuk judul album */
h3 {
    margin-bottom: 25px;
}

/* Gaya untuk card foto */
.card {
    border: none;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    max-height: 200px;
    object-fit: cover;
}

.card-title {
    font-size: 18px;
    font-weight: bold;
}

.btn-primary {
    background-color: #007bff;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}
/* Gaya untuk judul album */
.album-title {
    margin-bottom: 20px; /* Jarak bawah antara judul album dan tabel foto */
}

/* Gaya untuk setiap album */
.album-container {
    margin-bottom: 40px; /* Jarak bawah antara setiap album */
}

/* Gaya untuk nama album button */
.album-name {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.album-name:hover {
    background-color: #0056b3;
}

/* Gaya untuk kotak pembatas antar album */
.album-separator {
    border: 1px solid #dddddd;
    margin-bottom: 30px; /* Jarak bawah antara album */
}

</style>
<body>

<div class="container mt-3">
    <div class="row">
        <div class="col-3">
            <h2>Album Foto</h2>
        </div>
    </div>
    <br><br>
    <?php
    // Query untuk mendapatkan album yang terkait dengan pengguna yang login
    $query_albums = mysqli_query($conn, "SELECT * FROM album WHERE UserID = '$user_id'");
    if (mysqli_num_rows($query_albums) > 0) {
        while ($album = mysqli_fetch_assoc($query_albums)) {
    ?>
    <div class="row mb-3">
        <div class="col-12">
            <h3><?= $album['NamaAlbum'] ?></h3>
            <div class="row">
                <?php
                // Ambil data foto dalam setiap album
                $query_photos = mysqli_query($conn, "SELECT * FROM foto WHERE AlbumID = '" . $album['AlbumID'] . "'");
                while ($photo = mysqli_fetch_assoc($query_photos)) {
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="uploads/<?= $photo['NamaFile'] ?>" class="card-img-top" alt="<?= $photo['JudulFoto'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $photo['JudulFoto'] ?></h5>
                            <a href="?url=detail&id=<?= $photo['FotoID'] ?>" class="btn btn-primary">Details <i class="fas fa-long-arrow-alt-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
        }
    } else {
        // Tampilkan pesan jika tidak ada album yang ditemukan
        echo "<div class='row'><div class='col-12'><p>Belum ada album yang dibuat.</p></div></div>";
    }
    ?>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
