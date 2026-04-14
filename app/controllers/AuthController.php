<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/User.php';

/**
 * AuthController - Inscription, Connexion, Déconnexion
 */
class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ──────────────────────────────────────────────────────
    // GET/POST  /auth/login
    // ──────────────────────────────────────────────────────
    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard/index');
        }

        $erreurs = [];

        if ($this->isPost()) {
            $email = trim($_POST['email']        ?? '');
            $mdp   = trim($_POST['mot_de_passe'] ?? '');

            $user = $this->userModel->findByEmail($email);

            if ($user && $this->userModel->verifyPassword($mdp, $user['mot_de_passe'])) {
                // Ouverture de session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['role']     = $user['role'];

                $this->flash('success', 'Bienvenue ' . $user['nom'] . ' !');
                $this->redirect('dashboard/index');
            } else {
                $erreurs[] = 'Email ou mot de passe incorrect.';
            }
        }

        $this->render('auth/login', ['erreurs' => $erreurs]);
    }

    // ──────────────────────────────────────────────────────
    // GET/POST  /auth/inscription
    // ──────────────────────────────────────────────────────
    public function inscription(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard/index');
        }

        $erreurs = [];
        $ancien  = [];

        if ($this->isPost()) {
            $ancien = [
                'nom'       => $this->clean($_POST['nom']       ?? ''),
                'email'     => trim($_POST['email']             ?? ''),
                'role'      => $_POST['role']                   ?? 'acheteur',
                'telephone' => $this->clean($_POST['telephone'] ?? ''),
                'adresse'   => $this->clean($_POST['adresse']   ?? ''),
            ];
            $mdp = $_POST['mot_de_passe'] ?? '';

            // Validations
            if (empty($ancien['nom']))                          $erreurs[] = 'Le nom est obligatoire.';
            if (!filter_var($ancien['email'], FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Adresse email invalide.';
            if (strlen($mdp) < 6)                              $erreurs[] = 'Mot de passe : 6 caractères minimum.';
            if (!in_array($ancien['role'], ['acheteur','vendeur'])) $ancien['role'] = 'acheteur';
            if ($this->userModel->emailExists($ancien['email'])) $erreurs[] = 'Cet email est déjà utilisé.';

            if (empty($erreurs)) {
                $this->userModel->create(array_merge($ancien, ['mot_de_passe' => $mdp]));
                $this->flash('success', 'Compte créé avec succès ! Connectez-vous.');
                $this->redirect('auth/login');
            }
        }

        $this->render('auth/inscription', ['erreurs' => $erreurs, 'ancien' => $ancien]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /auth/deconnexion
    // ──────────────────────────────────────────────────────
    public function deconnexion(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login');
        exit();
    }
}