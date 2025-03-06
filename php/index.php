<?php
/*
 * Bu, web sitesinin ana giriş noktasıdır.
 */

// config.php dosyasını dahil et
include 'config.php';

// Oturum başlat
session_start();

// Router sınıfını dahil et
include 'routes.php';

// Router'ı başlat
$router = new Router();
$router->direct($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz</title>
</head>
<body>
    <h1><?php echo SITE_TITLE; // .env dosyasından gelen başlık ?></h1>
    <p>
        <?php
        // Tarayıcıya mesaj çıktılar
        echo SITE_MESSAGE; // .env dosyasından gelen mesaj
        ?>
    </p>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Adınız:</label>
        <input type="text" id="name" name="name">
        <br>
        <label for="file">Dosya Yükle:</label>
        <input type="file" id="file" name="file">
        <br>
        <button type="submit">Gönder</button>
    </form>
</body>
</html>
