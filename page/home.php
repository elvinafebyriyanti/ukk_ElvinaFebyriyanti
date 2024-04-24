<?php
include 'koneksi.php';

// Periksa apakah pengguna telah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

}else {
        // Jika tidak ada yang login, gunakan user_id 14 untuk pengguna anonim
        $user_id = 14;
    }

    if (isset($_GET['like'])) {
        $foto_id = $_GET['like'];
        // Periksa apakah pengguna sudah memberikan "Like" pada foto
        $ceksuka = mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='$foto_id' AND UserID='$user_id'");
        if (mysqli_num_rows($ceksuka) > 0) {
            // Hapus "Like" jika sudah diberikan sebelumnya
            $query = mysqli_query($conn, "DELETE FROM likes WHERE FotoID='$foto_id' AND UserID='$user_id'");
        } else {
            // Tambahkan "Like" jika belum diberikan sebelumnya
            $query = mysqli_query($conn, "INSERT INTO likes (FotoID, UserID) VALUES ('$foto_id', '$user_id')");
        }
        // Redirect kembali ke halaman home setelah menambah atau menghapus "Like"
        header("Location: ?url=home");
        exit();
    }

?>


<!-- Tampilkan konten seperti yang sebelumnya -->

<div class="product-area mt-text-2">

    <div class="container custom-area-2 overflow-hidden">
        <div class="row">
            <!--Section Title Start-->
            <div class="col-12 col-custom">
                <div class="section-title text-center mb-30">
                  
                    <h3 class="section-title-3">Selamat datang</h3>
                </div>
            </div>
            <!--Section Title End-->
        </div>
        <div class="row product-row">
            <?php
            $tampil = mysqli_query($conn, "SELECT * FROM foto INNER JOIN userfoto ON foto.UserID=userfoto.UserID");
            foreach ($tampil as $tampils):
                $foto_id = $tampils['FotoID'];
                $ceksuka = mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='$foto_id' AND UserID='$user_id'");
                $liked = mysqli_num_rows($ceksuka) > 0;
                $total_likes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likes WHERE FotoID='$foto_id'"));
                $total_comments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM komentar WHERE FotoID='$foto_id'"));
            ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="single-item">
                    <!--Single Product Start-->
                    <div class="single-product position-relative mb-30 p-3" style="border: 1px solid #e5e5e5; border-radius: 8px;">
                        <div class="product-image">
                            <a class="d-block" href="#">
                                <img src="uploads/<?= $tampils['NamaFile'] ?>" class="objek-fit-cover" width="100%">
                            </a>
                        </div>
                        <div class="product-content">
                            <div class="product-title">
                                <h4 class="title-2"><?= $tampils['JudulFoto'] ?></h4>
                            </div>
                            
                            <a href="?url=detail&id=<?= $tampils['FotoID'] ?>" class="btn detail">Details <i class="fa fa-long-arrow-right"></i></a>

                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 10px;">
                            <a href="?url=home&like=<?= $tampils['FotoID'] ?>" class="like-btn <?= $liked ? 'liked' : '' ?>"><i class="fas fa-heart"></i></a>

                                <?= $total_likes ?> Suka
                                <i class="fa-regular fa-comment"></i> <?= $total_comments ?> Komentar
                            </div>
                        </div>
                    </div>
                    <!--Single Product End-->
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

