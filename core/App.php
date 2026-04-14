<?php
class App {
    protected $controller = 'ProduitController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        $controllerName = ucfirst($url[0] ?? 'produit') . 'Controller';
        $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $controllerName();

            $this->method = $url[1] ?? 'index';
            if (!method_exists($this->controller, $this->method)) {
                $this->method = 'index';
            }

            $this->params = array_slice($url, 2);
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['produit'];
    }
}