<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Commande.php';
require_once APP_PATH  . '/models/Paiement.php';

/**
 * PaiementController - Historique des paiements
 */
class PaiementController extends Controller
{
    private Commande $commandeModel;
    private Paiement $paiementModel;

    public function __construct()
    {
        $this->commandeModel = new Commande();
        $this->paiementModel = new Paiement();
    }

    // ──────────────────────────────────────────────────────
    // GET  /paiement  → Liste des paiements de l'acheteur
    // ──────────────────────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        $acheteurId = $_SESSION['user_id'];
        $commandes  = $this->commandeModel->parAcheteur($acheteurId);
        $paiements  = [];

        foreach ($commandes as $commande) {
            $paiement = $this->paiementModel->parCommande($commande['id']);
            if ($paiement) {
                $paiements[] = array_merge($paiement, ['commande' => $commande]);
            }
        }

        $this->render('paiement/index', ['paiements' => $paiements]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /paiement/detail/{id}  → Détail d'un paiement
    // ──────────────────────────────────────────────────────
    public function detail(string $id = '0'): void
    {
        $this->requireAuth();

        $paiement = $this->paiementModel->parCommande((int)$id);
        if (!$paiement) {
            $this->flash('error', 'Paiement introuvable.');
            $this->redirect('paiement');
        }

        $commande = $this->commandeModel->detail((int)$id);
        if (!$commande || $commande['acheteur_id'] != $_SESSION['user_id']) {
            $this->flash('error', 'Accès non autorisé.');
            $this->redirect('paiement');
        }

        $this->render('paiement/detail', [
            'paiement'  => $paiement,
            'commande'  => $commande,
        ]);
    }
}
