<?php
include 'koneksi.php';


// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak, redirect ke halaman login atau tindakan lain yang sesuai
    header("Location: login.php");
    exit();
}

// Periksa apakah ada unggahan file
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $judul_foto = $_POST['judul_foto'];
    $deskripsi_foto = $_POST['deskripsi_foto'];
    $nama_file = $_FILES['namafile']['name'];
    $tmp_foto = $_FILES['namafile']['tmp_name'];
    $tanggal_unggah = date('Y-m-d');
    // Periksa apakah album_id tersedia dalam $_POST sebelum mengambil nilainya
    $album_id = isset($_POST['album_id']) ? $_POST['album_id'] : null;
    $user_id = $_SESSION['user_id'];

    // Pastikan file yang diunggah adalah gambar
    $allowed_extensions = array('jpg', 'png', 'gif');
    $file_extension = pathinfo($nama_file, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $allowed_extensions)) {
        echo '<script>alert("File harus berupa: *.jpg, *.png, *.gif")</script>';
        exit();
    }

    // Pindahkan file yang diunggah ke direktori tujuan
    $upload_directory = 'uploads/';
    $file_destination = $upload_directory . $nama_file;
    if (move_uploaded_file($tmp_foto, $file_destination)) {
        // Lakukan pengecekan kesalahan saat melakukan query
        // Periksa apakah album_id valid sebelum menambahkan data ke tabel foto
        if ($album_id !== null) {
            $insert = mysqli_query($conn, "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, NamaFile, AlbumID, UserID) VALUES ('$judul_foto', '$deskripsi_foto', '$tanggal_unggah', '$nama_file', '$album_id', '$user_id')");
            if ($insert) {
                echo '<script>alert("Gambar Berhasil Di Simpan")</script>';
            } else {
                echo '<script>alert("Gambar Gagal Di Simpan")</script>';
            }
        } else {
            echo '<script>alert("Pilih album terlebih dahulu")</script>';
        }
    } else {
        echo '<script>alert("Gagal mengunggah file")</script>';
    }
}

// Ambil daftar album yang dimiliki oleh pengguna yang login
$user_id = $_SESSION['user_id'];
$query_album = mysqli_query($conn, "SELECT * FROM album WHERE UserID = '$user_id'");
?>
<!-- Your HTML content -->

<!-- Your HTML content -->


<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h1>Upload Gambar</h1>
                    <form action="" method="post" enctype="multipart/form-data">
                    
                        <div class="mb-3">
                            <label>Judul Foto</label>
                            <div class="input-group">
                                <input type="text" class="form-control" required name="judul_foto">
                            </div>
                        </div>
                    
                        <div class="mb-3">
                            <label>Deskripsi Foto</label>
                            <div class="input-group">
                                <textarea name="deskripsi_foto" class="form-control" required cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    
                        <br>
                   
                        <div class="mb-3">
                            <label>Pilih Gambar</label>
                            <div class="input-group">
                                <input type="file" name="namafile" class="form-control" required accept="image/jpeg, image/png, image/gif">
                            </div>
                            <small class="text-danger">File harus berupa: *.jpg, *.png, *.gif</small>
                        </div>
                    
                        <br>
                    
                        
                        <div class="mb-3">
    <label>Pilih Album</label>
    <div class="input-group">
        <select name="album_id" class="form-select">
            <?php 
            // Ambil daftar album yang dimiliki oleh pengguna yang login
            $user_id = $_SESSION['user_id'];
            $query_album = mysqli_query($conn, "SELECT * FROM album WHERE UserID = '$user_id'");
            
            // Periksa apakah ada album yang tersedia
            if(mysqli_num_rows($query_album) > 0) {
                // Tampilkan setiap album dalam dropdown
                while ($row = mysqli_fetch_assoc($query_album)): 
            ?>
                    <option value="<?= $row['AlbumID'] ?>"><?= $row['NamaAlbum'] ?></option>
            <?php 
                endwhile; 
            } else {
                // Jika tidak ada album, tampilkan pesan
                echo '<option disabled selected>Tidak ada album yang tersedia</option>';
            }
            ?>
        </select>
    </div>
</div>

                        </div>
                    
                        <input type="submit" value="Simpan" name="submit" class="btn btn-success my-3">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
