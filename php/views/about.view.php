<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<body>
    <!-- Navigasyon menüsü -->
    <nav>
        <a href="/">Ana Sayfa</a> | 
        <a href="/about">Hakkında</a> | 
        <a href="/database">Veritabanı Verileri</a>
    </nav>
    <!-- Başlık ve mesaj -->
    <h1><?php echo $title; ?></h1>
    <p><?php echo $message; ?></p>
</body>
</html>
