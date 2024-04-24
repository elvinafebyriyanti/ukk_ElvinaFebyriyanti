<?php
include 'koneksi.php'; 
session_start();

// Cek apakah ada permintaan untuk logout
if(isset($_GET['url']) && $_GET['url'] == 'logout') {
    include 'logout.php';
    exit(); // Hentikan eksekusi script setelah logout
}






?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="?url=home">Gallery Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        ...
        <div class="navbar-nav ms-auto">
    <?php if(isset($_SESSION['user_id'])): ?>
        <a class="nav-link" href="?url=index">Home</a> 
        <a class="nav-link" href="?url=upload">Upload</a>
        <a class="nav-link" href="?url=album">Album</a>
        <a class="nav-link" href="?url=profile">Profile <?= ucwords($_SESSION['username'])?></a>
        <a class="nav-link" href="?url=logout">Logout</a>
    <?php else: ?>
        <a class="nav-link" href="?url=home">Home</a> 
       
        <a class="nav-link" href="login.php">Login</a>
        <a class="nav-link" href="registrasi.php">Registrasi</a>
    <?php endif; ?>
</div>

...

    </div>
</nav>

<?php
$url=@$_GET["url"];
if($url=='home'){
    include 'page/home.php';
} else if($url=='profile'){
    include 'page/profil.php';
} else if($url=='upload'){
    include 'page/upload.php';
}else if($url=='dashboard'){
    include 'page/dashboard.php';
} else if($url=='album'){
    include 'page/album.php';
}else if($url=='logout'){
    include 'logout.php';
}else if($url=='detail'){
  include 'page/detail.php';
}else if($url=='edit'){
  include 'page/edit_image.php';
}else if($url=='delete'){
  include 'page/delete_image.php';
}else if($url=='tambah1'){
  include 'page/tambah1.php';
}else if($url=='tambah2'){
  include 'page/tambah2.php';
}else if($url=='editprofil'){
  include 'page/edit_profil.php';
}else if($url=='edit1'){
    include 'page/edit_komen.php';
}else if($url=='delete'){
    include 'page/edelete_komen.php';
}else if($url=='reset'){
  include 'page/reset_password.php';
}else if($url=='like'){
    include 'page/like.php';
 }else if($url=='home1'){
    include 'page/home1.php';
 }
 else if($url=='detail1'){
    include 'page/detail1.php';
 }else if($url=='album1'){
    include 'page/album1.php';
 }else if($url=='profile2'){
    include 'page/profile2.php';
 }else{
    include 'page/home.php';
}
?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

