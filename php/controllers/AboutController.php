<?php
// AboutController sınıfı, hakkında sayfasını yönetir
class AboutController {
    // Hakkında sayfası için index metodu
    public function index() {
        // Başlık ve mesajı tanımla
        $title = "Hakkında"; // Sayfa başlığı
        $message = "Bu, hakkında sayfasıdır."; // Sayfa mesajı

        // Hakkında sayfası görünümünü yükle
        require 'views/about.view.php'; // Görünüm dosyasını yükle
    }
}
?>
