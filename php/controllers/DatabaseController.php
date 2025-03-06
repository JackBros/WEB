<?php
class DatabaseController {
    // Veritabanı verileri için index metodu
    public function index() {
        // Çıktıyı temizle
        //ob_clean(); 

        // database.php dosyasını yükle
        require 'core/database.php';

        // Sayfanın devam etmesini engelle
        exit();
    }
}
?>
