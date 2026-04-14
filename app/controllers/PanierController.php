<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Produit.php';

/**
 * PanierController - Gestion du panier en session
 */
class PanierController extends Controller
{
    private Produit $produitModel;

    public function __construct()
    {
        $this->produitModel = new Produit();

        // Initialise le panier si vide
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];  // [produit_id => quantite]
        }
    }

    // ──────────────────────────────────────────────────────
    // GET  /panier  → Affiche le panier
    // ──────────────────────────────────────────────────────
    public function index(): void
    {
        [$lignes, $sousTotal] = $this->getLignes();
        $this->render('panier/index', ['lignes' => $lignes, 'sousTotal' => $sousTotal]);
    }

    // ──────────────────────────────────────────────────────
    // POST /panier/ajouter/{id}
    // ──────────────────────────────────────────────────────
    public function ajouter(string $id = '0'): void
    {
        $produit = $this->produitModel->findById((int)$id);

        if (!$produit) {
            $this->flash('error', 'Produit introuvable.');
            $this->redirect('produit/catalogue');
        }

        $qte = max(1, (int)($_POST['quantite'] ?? 1));

        if (isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id] += $qte;
        } else {
            $_SESSION['panier'][$id] = $qte;
        }

        // Ne pas dépasser le stock
        if ($_SESSION['panier'][$id] > $produit['stock']) {
            $_SESSION['panier'][$id] = $produit['stock'];
        }

        // Réponse AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json([
                'success' => true,
                'total'   => array_sum($_SESSION['panier']),
            ]);
        }

        $this->flash('success', $produit['nom'] . ' ajouté au panier !');
        $this->redirect('panier');
    }

    // ──────────────────────────────────────────────────────
    // POST /panier/modifier/{id}
    // ──────────────────────────────────────────────────────
    public function modifier(string $id = '0'): void
    {
        $qte = (int)($_POST['quantite'] ?? 0);

        if ($qte <= 0) {
            unset($_SESSION['panier'][$id]);
        } else {
            $produit = $this->produitModel->findById((int)$id);
            if ($produit) {
                $_SESSION['panier'][$id] = min($qte, $produit['stock']);
            }
        }

        $this->redirect('panier');
    }

    // ──────────────────────────────────────────────────────
    // GET  /panier/retirer/{id}
    // ──────────────────────────────────────────────────────
    public function retirer(string $id = '0'): void
    {
        unset($_SESSION['panier'][$id]);
        $this->flash('success', 'Article retiré du panier.');
        $this->redirect('panier');
    }

    // ──────────────────────────────────────────────────────
    // GET  /panier/vider
    // ──────────────────────────────────────────────────────
    public function vider(): void
    {
        $_SESSION['panier'] = [];
        $this->redirect('panier');
    }

    // ──────────────────────────────────────────────────────
    // Méthode privée : construit les lignes du panier
    // ──────────────────────────────────────────────────────
    private function getLignes(): array
    {
        $lignes    = [];
        $sousTotal = 0.0;

        foreach ($_SESSION['panier'] as $id => $qte) {
            $produit = $this->produitModel->findById((int)$id);
            if ($produit) {
                $sousTotalLigne = $produit['prix'] * $qte;
                $sousTotal     += $sousTotalLigne;
                $lignes[]       = [
                    'produit'    => $produit,
                    'quantite'   => $qte,
                    'sousTotal'  => $sousTotalLigne,
                ];
            }
        }

        return [$lignes, $sousTotal];
    }
}