<?php
/**
 * Controller - Classe abstraite de base
 * Tous les contrôleurs héritent de cette classe.
 * Fournit : chargement de vues, redirections, flash, sécurité.
 */
abstract class Controller
{
    /**
     * Charge une vue en l'enveloppant dans le layout (header + footer)
     * @param string $view   Chemin relatif depuis app/views/  ex: 'produits/liste'
     * @param array  $data   Variables injectées dans la vue
     */
    protected function render(string $view, array $data = []): void
    {
        // Rend chaque clé du tableau disponible comme variable dans la vue
        extract($data);

        $viewFile = APP_PATH . '/views/' . $view . '.php';

        require APP_PATH . '/views/layouts/header.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            require APP_PATH . '/views/errors/404.php';
        }

        require APP_PATH . '/views/layouts/footer.php';
    }

    /**
     * Redirige vers une URL relative à BASE_URL
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit();
    }

    /**
     * Stocke un message flash en session
     * @param string $type  'success' | 'error' | 'warning' | 'info'
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Redirige vers login si non connecté
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->flash('warning', 'Connectez-vous pour accéder à cette page.');
            $this->redirect('auth/login');
        }
    }

    /**
     * Vérifie que l'utilisateur a le rôle requis
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') !== $role) {
            $this->flash('error', 'Accès non autorisé.');
            $this->redirect('');
        }
    }

    /**
     * Retourne true si la requête est POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Nettoie une chaîne pour éviter les failles XSS
     */
    protected function clean(string $str): string
    {
        return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Envoie une réponse JSON (pour les requêtes AJAX)
     */
    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    /**
     * Upload une image et retourne son nom de fichier
     */
    protected function uploadImage(array $file, string $prefix = 'img'): string|false
    {
        // Vérifier que le fichier a été uploadé sans erreur
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("Upload error: " . $file['error']);
            return false;
        }
        
        // Vérifier la taille (max 2 MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            error_log("File too large: " . $file['size']);
            return false;
        }
        
        // Vérifier que c'est vraiment une image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            error_log("getimagesize failed for: " . $file['tmp_name']);
            return false;
        }
        
        // Vérifier le type MIME détecté
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($imageInfo['mime'], $allowed)) {
            error_log("Invalid MIME type: " . $imageInfo['mime']);
            return false;
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . uniqid() . '.' . $ext;
        $dest     = UPLOAD_DIR . $filename;

        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            error_log("move_uploaded_file failed");
            return false;
        }
        
        return $filename;
    }
}