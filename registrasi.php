<?php
include 'koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body class="">
<div class="login-register-area mt-no-text">
    <div class="container custom-area">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-custom">
                <div class="login-register-wrapper">
                    <div class="section-content text-center mb-5">
                        <h2 class="title-4 mb-2">Registrasi</h2>
                        <p class="desc-content">Please registrasi using account detail bellow.</p>
                    </div>
                        <?php
                        if (isset($_POST['submit'])) {
                            $username = $_POST['username'];
                            $password = md5($_POST['password']);
                            $email = $_POST['email'];
                            $nama_lengkap = $_POST['nama_lengkap'];
                            $alamat = $_POST['alamat'];
                            
                            // Periksa apakah username atau email sudah terdaftar
                            $result = mysqli_query($conn, "SELECT * FROM userfoto WHERE Username='$username' OR Email='$email'");
                            if (mysqli_num_rows($result) == 0) {
                                // Jika tidak ada akun yang sama, lakukan registrasi
                                $query = "INSERT INTO userfoto (Username, Password, Email, NamaLengkap, Alamat) VALUES ('$username', '$password', '$email', '$nama_lengkap', '$alamat')";
                                if (mysqli_query($conn, $query)) {
                                    // Registrasi berhasil, arahkan ke halaman login
                                    header("Location: login.php");
                                    exit();
                                } else {
                                    // Registrasi gagal karena kesalahan query
                                    echo "Registrasi gagal. Silakan coba lagi.";
                                }
                            } else {
                                // Registrasi gagal karena akun sudah terdaftar
                                echo "Akun sudah terdaftar.";
                            }
                        }
                        ?>
                        
                        <form action="registrasi.php" method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" name="alamat" required>
                            </div>
                            
                            <input type="submit" value="Registrasi" class="btn btn-danger my-3" name="submit">
                            <p>Sudah punya akun? <a href="login.php" class="link-danger">Login sekarang</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
