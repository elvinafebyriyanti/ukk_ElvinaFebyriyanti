<?php
include 'koneksi.php'; 

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil data pengguna dari database
$username = $_SESSION['username'];
$query = "SELECT * FROM userfoto WHERE Username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Cek apakah form disubmit
if(isset($_POST['submit'])) {
    // Ambil password lama dan password baru yang dimasukkan oleh pengguna
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Melakukan hashing password lama dan password baru
    $hashed_old_password = md5($old_password);
    $hashed_new_password = md5($new_password);

    // Memeriksa apakah password lama yang dimasukkan sesuai dengan yang ada di database
    if ($hashed_old_password === $user['Password']) {
        // Password lama cocok, melakukan update password baru
        $query = "UPDATE userfoto SET Password='$hashed_new_password' WHERE Username='$username'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            
            // Password berhasil diubah, redirect ke halaman profil
            echo '<script>alert("Berhasil Mereset Password")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profil">';
        } else {
            // Password gagal diubah, tampilkan pesan kesalahan

            echo '<script>alert("Gagal Mereset Password")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profil">';
        }
    } else {
        // Password lama tidak cocok, tampilkan pesan kesalahan
        $error_message = "Password lama yang dimasukkan tidak sesuai.";
    }
}
?>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reset Password</h4>
                        <?php if(isset($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input type="password" class="form-control" id="old_password" name="old_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
