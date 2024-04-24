<?php
include 'koneksi.php';
session_start();

// Jika form login disubmit
if (isset($_POST['submit'])) {
    // Mendapatkan username dan password yang dimasukkan pengguna
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Melakukan hashing password yang dimasukkan untuk membandingkannya dengan yang disimpan di database
    $hashed_password = md5($password);

    // Melakukan query untuk mencari pengguna dengan username yang diberikan
    $query = "SELECT * FROM userfoto WHERE Username='$username'";
    $result = mysqli_query($conn, $query);

    // Memeriksa apakah pengguna ditemukan dalam database
    if (mysqli_num_rows($result) > 0) {
        // Mengambil data pengguna
        $user = mysqli_fetch_assoc($result);
        
        // Memeriksa apakah password yang dimasukkan cocok dengan yang disimpan di database
        if ($hashed_password === $user['Password']) {
            // Password cocok, atur sesi dan redirect ke halaman yang diinginkan
            $_SESSION['username'] = $user['Username'];
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['nama_lengkap'] = $user['NamaLengkap'];
            $_SESSION['password'] = $password; // Simpan password dalam session

            // Reset session password jika berhasil login setelah reset password
            if (isset($_SESSION['password_reset']) && $_SESSION['password_reset'] === true) {
                $_SESSION['password'] = $new_password;
                $_SESSION['password_reset'] = false; // Reset status reset password
            }

            // Redirect ke halaman setelah login berhasil
            header("Location: ./?error=false"); // Pemberitahuan login berhasil
            exit();
        } else {
            // Password tidak cocok, tampilkan pesan kesalahan
            header("Location: ./?error=true"); // Pemberitahuan login gagal
            exit();
        }
    } else {
        // Username tidak ditemukan dalam database, tampilkan pesan kesalahan
        header("Location: ./?error=true"); // Pemberitahuan login gagal
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
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
                        <h2 class="title-4 mb-2">Login</h2>
                        <p class="desc-content">Please login using account detail bellow.</p>
                        <?php if (isset($_GET['error']) && $_GET['error'] == 'true'): ?>
                            <div class="alert alert-danger" role="alert">
                                Login failed. Please check your username and password.
                            </div>
                        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'false'): ?>
                            <div class="alert alert-success" role="alert">
                                Login successful. Welcome back, <?php echo $_SESSION['username']; ?>!
                            </div>
                        <?php endif; ?>
                    </div>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Login" class="btn btn-danger my-3" name="submit">
                        </div>
                        <p>Belum punya akun? <a href="registrasi.php" class="link-danger">Registrasi sekarang</a></p>
                    </form>
                </div> 
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
