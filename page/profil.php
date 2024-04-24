<?php
// Periksa apakah pengguna telah login

if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Sambungkan ke database
include 'koneksi.php';

// Ambil informasi pengguna dari database berdasarkan sesi
$username = $_SESSION['username'];
$query = "SELECT * FROM userfoto WHERE Username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Profil Pengguna</h4>
                        Tampilkan foto profil pengguna
                       

                        <p><strong>Username:</strong> <?php echo $user['Username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['Email']; ?></p>
                        <p><strong>Nama Lengkap:</strong> <?php echo $user['NamaLengkap']; ?></p>
                        <p><strong>Alamat:</strong> <?php echo $user['Alamat']; ?></p>
                       
                        <a href="?url=editprofil" class="btn btn-primary mt-3">Edit Profil</a>
                        <a href="?url=reset" class="btn btn-primary mt-3">Reset Password</a>

                        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
