<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Commande.php';
require_once APP_PATH  . '/models/Produit.php';
require_once APP_PATH  . '/models/User.php';
require_once APP_PATH  . '/models/Livraison.php';

class DashboardController extends Controller {
    private Commande  $commandeModel;
    private Produit   $produitModel;
    private User      $userModel;
    private Livraison $livraisonModel;

    public function __construct() {
        $this->commandeModel  = new Commande();
        $this->produitModel   = new Produit();
        $this->userModel      = new User();
        $this->livraisonModel = new Livraison();
    }

    public function index(): void {
        $this->requireAuth();
        if ($_SESSION['role'] === 'vendeur') {
            $this->vendeur();
        } else {
            $this->acheteur();
        }
    }

    public function vendeur(): void {
        $this->requireRole('vendeur');
        $id        = $_SESSION['user_id'];
        $produits  = $this->produitModel->parVendeur($id);
        $commandes = $this->commandeModel->parVendeur($id);
        $ca        = $this->commandeModel->chiffreAffaires($id);
        $stockFaible = array_filter($produits, fn($p) => $p['stock'] < 10);
        $stats     = [
            'total_produits'  => count($produits),
            'total_commandes' => count($commandes),
            'chiffre_affaires'=> $ca,
            'stock_faible'    => $stockFaible,
        ];
        $this->render('dashboard/vendeur', compact('produits', 'commandes', 'ca', 'stats', 'stockFaible'));
    }

    public function acheteur(): void {
        $this->requireAuth();
        $id         = $_SESSION['user_id'];
        $commandes  = $this->commandeModel->parAcheteur($id);
        $livraisons = $this->livraisonModel->parAcheteur($id);
        $this->render('dashboard/acheteur', compact('commandes', 'livraisons'));
    }

    public function updateStatutCommande(string $id = '0'): void {
        $this->requireRole('vendeur');
        if ($this->isPost()) {
            $statut  = $_POST['statut'] ?? '';
            $allowed = ['en_attente', 'payee', 'en_livraison', 'livree', 'annulee'];
            if (in_array($statut, $allowed, true)) {
                $this->commandeModel->changerStatut((int)$id, $statut);
                $this->flash('success', 'Statut mis à jour.');
            }
        }
        $this->redirect('dashboard/vendeur');
    }
}