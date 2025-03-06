<?php

// HomeController sınıfı, ana sayfa ile ilgili işlemleri yönetir
class HomeController {
    // Ana sayfa için index metodu
    public function index() {
        // Başlık ve mesaj sabitlerini al
        $title = SITE_TITLE;
        $message = SITE_MESSAGE;

        // Ana sayfa görünümünü yükle
        require 'views/home.view.php';
    }
}
?>
