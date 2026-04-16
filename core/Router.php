<?php
/**
 * Router - Analyse l'URL et dispatche vers le bon contrôleur/méthode
 *
 * Format d'URL : /controleur/methode/param1/param2
 * Exemple      : /produit/detail/5  →  ProduitController::detail(5)
 */
class Router
{
    /** Table de correspondance segment → contrôleur */
    private array $routes = [
        ''          => 'ProduitController',
        'produit'   => 'ProduitController',
        'auth'      => 'AuthController',
        'panier'    => 'PanierController',
        'commande'  => 'CommandeController',
        'dashboard' => 'DashboardController',
        'paiement'  => 'PaiementController',
        'livraison' => 'LivraisonController',
    ];

    public function __construct()
    {
        // 1. Décomposer l'URL en segments
        $segments = $this->parseUrl();

        // 2. Identifier le contrôleur
        $segment        = strtolower($segments[0] ?? '');
        $controllerName = $this->routes[$segment] ?? 'ProduitController';
        $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            $this->show404();
            return;
        }

        require_once $controllerFile;
        $controller = new $controllerName();

        // 3. Identifier la méthode
        $method = $segments[1] ?? 'index';
        if (!method_exists($controller, $method)) {
            $method = 'index';
        }

        // 4. Récupérer les paramètres restants
        $params = array_slice($segments, 2);

        // 5. Appeler controller->method(params...)
        call_user_func_array([$controller, $method], $params);
    }

    /** Découpe et sanitize l'URL */
    private function parseUrl(): array
    {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url !== '' ? explode('/', $url) : [''];
    }

    private function show404(): void
    {
        require APP_PATH . '/views/layouts/header.php';
        require APP_PATH . '/views/errors/404.php';
        require APP_PATH . '/views/layouts/footer.php';
    }
}
