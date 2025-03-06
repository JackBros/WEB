<?php
class Router {
    // Yönlendirme kurallarını tanımla
    protected $routes = [
        '/' => 'HomeController@index',
        '/about' => 'AboutController@index',
        '/database' => 'DatabaseController@index'
    ];

    // URI'yi yönlendir
    public function direct($uri) {
        if (array_key_exists($uri, $this->routes)) {
            // Controller ve action'ı çağır
            $this->callAction(
                ...explode('@', $this->routes[$uri])
            );
        } else {
            echo "404 Not Found";
        }
    }

    // Controller ve action'ı çağır
    protected function callAction($controller, $action) {
        $controllerFile = "controllers/{$controller}.php";
        
        if (file_exists($controllerFile)) {
            require $controllerFile;
            $controllerInstance = new $controller;
            if (method_exists($controllerInstance, $action)) {
                $controllerInstance->$action();
            } else {
                die("Hata: {$controller} sınıfında '{$action}' metodu bulunamadı.");
            }
        } else {
            die("Hata: {$controllerFile} dosyası bulunamadı.");
        }
    }
}
?>
