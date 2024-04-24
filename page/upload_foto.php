<?php
// Periksa apakah pengguna telah login
session_start();
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Sambungkan ke database
include 'koneksi.php';

// Ambil nama pengguna dari sesi
$username = $_SESSION['username'];

// Direktori tempat menyimpan foto profil
$upload_dir = 'uploads/';

// Ambil informasi file yang diunggah
$uploaded_file = $_FILES['foto'];
$filename = $uploaded_file['name'];
$file_tmp_name = $uploaded_file['tmp_name'];
$file_type = $uploaded_file['type'];
$file_size = $uploaded_file['size'];
$file_error = $uploaded_file['error'];

// Pastikan tidak ada error saat mengunggah file
if ($file_error !== UPLOAD_ERR_OK) {
    // Jika ada error, tampilkan pesan dan redirect ke halaman profil
    $_SESSION['upload_error'] = "Gagal mengunggah foto profil. Silakan coba lagi.";
    header("Location: ?url=profil");
    exit();
}

// Periksa tipe file yang diunggah
$allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
if (!in_array($file_type, $allowed_types)) {
    // Jika tipe file tidak diizinkan, tampilkan pesan dan redirect ke halaman profil
    $_SESSION['upload_error'] = "Jenis file tidak didukung. Hanya file JPG, JPEG, dan PNG yang diizinkan.";
    header("Location: ?url=profil");
    exit();
}

// Periksa ukuran file
$max_file_size = 5 * 1024 * 1024; // 5MB
if ($file_size > $max_file_size) {
    // Jika ukuran file melebihi batas, tampilkan pesan dan redirect ke halaman profil
    $_SESSION['upload_error'] = "Ukuran file terlalu besar. Maksimum 5MB.";
    header("Location: ?url=profil");
    exit();
}

// Generate nama unik untuk file yang diunggah
$new_filename = uniqid('user_' . $username . '_') . '_' . $filename;

// Pindahkan file ke direktori upload
$upload_path = $upload_dir . $new_filename;
if (move_uploaded_file($file_tmp_name, $upload_path)) {
    // Jika berhasil diunggah, update nama file di database
    $update_query = "UPDATE userfoto SET FotoUser='$new_filename' WHERE Username='$username'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Redirect ke halaman profil dengan pesan sukses
        $_SESSION['upload_success'] = "Foto profil berhasil diperbarui.";
        header("Location: ?url=profil");
        exit();
    } else {
        // Jika gagal update di database, hapus file yang sudah diunggah
        unlink($upload_path);
        $_SESSION['upload_error'] = "Gagal mengupdate foto profil. Silakan coba lagi.";
        header("Location: ?url=profil");
        exit();
    }
} else {
    // Jika gagal memindahkan file, tampilkan pesan error
    $_SESSION['upload_error'] = "Gagal mengunggah foto profil. Silakan coba lagi.";
    header("Location: ?url=profil");
    exit();
}
?>
