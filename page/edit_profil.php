<?php
// Periksa apakah pengguna telah login
// Ambil informasi pengguna dari database berdasarkan sesi
$username = $_SESSION['username'];
$query = "SELECT * FROM userfoto WHERE Username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Ketika form disubmit
if(isset($_POST['submit'])) {
    // Ambil data yang dikirimkan dari form
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];

    // Proses unggah foto profil baru
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_name = $_FILES['foto']['name'];
        $foto_extension = pathinfo($foto_name, PATHINFO_EXTENSION);
        $new_foto_name = uniqid('user_') . '.' . $foto_extension;
        $foto_destination = 'uploads/' . $new_foto_name;

        // Pindahkan foto ke folder uploads dengan nama baru
        if(move_uploaded_file($foto_tmp, $foto_destination)) {
            // Update nama file foto baru ke dalam database
            $update_query = "UPDATE userfoto SET NamaLengkap='$nama_lengkap', Email='$email', Alamat='$alamat', FotoUser='$new_foto_name' WHERE Username='$username'";
            $update_result = mysqli_query($conn, $update_query);

            if($update_result) {
                // Jika berhasil diupdate, redirect ke halaman profil dengan pesan sukses
                header("Location: ?url=profil&success=true");
                exit();
            } else {
                // Jika gagal, tampilkan pesan kesalahan
                $error_message = "Gagal mengupdate profil. Silakan coba lagi.";
            }
        } else {
            // Jika gagal memindahkan foto, tampilkan pesan kesalahan
            $error_message = "Gagal mengunggah foto profil. Silakan coba lagi.";
        }
    } else {
        // Jika tidak ada foto yang diunggah, hanya update informasi profil
        $update_query = "UPDATE userfoto SET NamaLengkap='$nama_lengkap', Email='$email', Alamat='$alamat' WHERE Username='$username'";
        $update_result = mysqli_query($conn, $update_query);

        if($update_result) {
            // Jika berhasil diupdate, redirect ke halaman profil dengan pesan sukses
            echo '<script>alert("Berhasil Mengedit Data")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profil">';
           
        } else {
            // Jika gagal, tampilkan pesan kesalahan
            echo '<script>alert("Gagal mengupdate profil. Silakan coba lagi.")</script>';
            echo '<meta http-equiv="refresh" content="0.8; url=?url=profil">';
           
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Pengguna</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Profil Pengguna</h4>
                        <?php if(isset($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                       
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $user['NamaLengkap']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $user['Alamat']; ?></textarea>
                            </div>
                           
                            <button type="submit" class="btn btn-primary" name="submit">Simpan Perubahan</button>
                            <a href="?url=profil" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
