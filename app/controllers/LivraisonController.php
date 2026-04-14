<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Commande.php';
require_once APP_PATH  . '/models/Livraison.php';

/**
 * LivraisonController - Suivi des livraisons
 */
class LivraisonController extends Controller
{
    private Commande $commandeModel;
    private Livraison $livraisonModel;

    public function __construct()
    {
        $this->commandeModel = new Commande();
        $this->livraisonModel = new Livraison();
    }

    // ──────────────────────────────────────────────────────
    // GET  /livraison  → Liste des livraisons de l'acheteur
    // ──────────────────────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        $acheteurId = $_SESSION['user_id'];
        $livraisons = $this->livraisonModel->parAcheteur($acheteurId);

        $this->render('livraison/index', ['livraisons' => $livraisons]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /livraison/detail/{id}  → Détail d'une livraison
    // ──────────────────────────────────────────────────────
    public function detail(string $id = '0'): void
    {
        $this->requireAuth();

        $livraison = $this->livraisonModel->parCommande((int)$id);
        if (!$livraison) {
            $this->flash('error', 'Livraison introuvable.');
            $this->redirect('livraison');
        }

        $commande = $this->commandeModel->detail((int)$id);
        if (!$commande || $commande['acheteur_id'] != $_SESSION['user_id']) {
            $this->flash('error', 'Accès non autorisé.');
            $this->redirect('livraison');
        }

        $lignes = $this->commandeModel->lignes((int)$id);

        $this->render('livraison/detail', [
            'livraison' => $livraison,
            'commande'  => $commande,
            'lignes'    => $lignes,
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /livraison/updateStatut/{id}  → Mise à jour du statut (vendeur)
    // ──────────────────────────────────────────────────────
    public function updateStatut(string $id = '0'): void
    {
        $this->requireRole('vendeur');
        if (!$this->isPost()) {
            $this->redirect('livraison');
        }

        $statut  = $_POST['statut'] ?? '';
        $allowed = ['en_attente', 'en_cours', 'livree', 'echec'];

        if (in_array($statut, $allowed, true)) {
            $this->livraisonModel->changerStatut((int)$id, $statut);
            $this->flash('success', 'Statut de livraison mis à jour.');
        }

        $this->redirect('dashboard/vendeur');
    }
}
