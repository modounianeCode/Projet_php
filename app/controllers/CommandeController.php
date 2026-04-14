<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Commande.php';
require_once APP_PATH  . '/models/Produit.php';
require_once APP_PATH  . '/models/Paiement.php';
require_once APP_PATH  . '/models/Livraison.php';

/**
 * CommandeController - Tunnel de commande (livraison → paiement → confirmation)
 */
class CommandeController extends Controller
{
    private Commande  $commandeModel;
    private Produit   $produitModel;
    private Paiement  $paiementModel;
    private Livraison $livraisonModel;

    public function __construct()
    {
        $this->commandeModel  = new Commande();
        $this->produitModel   = new Produit();
        $this->paiementModel  = new Paiement();
        $this->livraisonModel = new Livraison();
    }

    // ──────────────────────────────────────────────────────
    // GET  /commande  → Étape 1 : choix de la livraison
    // ──────────────────────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();

        if (empty($_SESSION['panier'])) {
            $this->flash('warning', 'Votre panier est vide.');
            $this->redirect('panier');
        }

        $this->render('commande/livraison', [
            'zones'    => Livraison::ZONES,
            'lignes'   => $this->getLignesPanier(),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /commande/recapitulatif → Étape 2 : récap + paiement
    // ──────────────────────────────────────────────────────
    public function recapitulatif(): void
    {
        $this->requireAuth();
        if (!$this->isPost() || empty($_SESSION['panier'])) {
            $this->redirect('commande');
        }

        $zone    = $this->clean($_POST['zone']    ?? 'Dakar');
        $adresse = $this->clean($_POST['adresse'] ?? '');
        $frais   = Livraison::frais($zone);
        $lignes  = $this->getLignesPanier();
        $sous    = array_sum(array_column($lignes, 'sousTotal'));
        $total   = $sous + $frais;

        // Stocke en session pour l'étape suivante
        $_SESSION['commande_tmp'] = [
            'zone'    => $zone,
            'adresse' => $adresse,
            'frais'   => $frais,
            'sous'    => $sous,
            'total'   => $total,
            'lignes'  => $lignes,
        ];

        $this->render('commande/recapitulatif', [
            'zone'    => $zone,
            'adresse' => $adresse,
            'frais'   => $frais,
            'sous'    => $sous,
            'total'   => $total,
            'lignes'  => $lignes,
            'methodes'=> ['wave' => 'Wave', 'orange_money' => 'Orange Money',
                          'free_money' => 'Free Money', 'cash' => 'Cash à la livraison'],
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /commande/passer → Crée la commande + paiement + livraison
    // ──────────────────────────────────────────────────────
    public function passer(): void
    {
        $this->requireAuth();
        if (!$this->isPost() || empty($_SESSION['commande_tmp'])) {
            $this->redirect('commande');
        }

        $tmp     = $_SESSION['commande_tmp'];
        $methode = $this->clean($_POST['methode'] ?? 'cash');

        // 1. Créer la commande
        $commandeId = $this->commandeModel->creer([
            'acheteur_id'     => $_SESSION['user_id'],
            'total'           => $tmp['total'],
            'adresse_livraison' => $tmp['adresse'],
            'zone_livraison'  => $tmp['zone'],
            'frais_livraison' => $tmp['frais'],
        ]);

        // 2. Ajouter chaque ligne + décrémenter le stock
        foreach ($tmp['lignes'] as $ligne) {
            $this->commandeModel->ajouterLigne(
                $commandeId,
                $ligne['produit']['id'],
                $ligne['quantite'],
                $ligne['produit']['prix']
            );
            $this->produitModel->decrementerStock($ligne['produit']['id'], $ligne['quantite']);
        }

        // 3. Enregistrer le paiement
        $reference = $this->paiementModel->creer($commandeId, $methode, $tmp['total']);

        // 4. Simuler la confirmation (sauf cash)
        if ($methode !== 'cash') {
            $this->paiementModel->confirmer($commandeId);
            $this->commandeModel->changerStatut($commandeId, 'payee');
        }

        // 5. Créer la livraison
        $this->livraisonModel->creer($commandeId, $tmp['zone']);

        // 6. Si payé, mettre la livraison en cours
        if ($methode !== 'cash') {
            $this->livraisonModel->changerStatut($commandeId, 'en_cours');
        }

        // 7. Vider panier et données temporaires
        $_SESSION['panier']       = [];
        unset($_SESSION['commande_tmp']);

        $this->flash('success', "Commande #{$commandeId} confirmée ! Référence : {$reference}");
        $this->redirect("commande/confirmation/{$commandeId}");
    }

    // ──────────────────────────────────────────────────────
    // GET  /commande/confirmation/{id} → Page de succès
    // ──────────────────────────────────────────────────────
    public function confirmation(string $id = '0'): void
    {
        $this->requireAuth();

        $commande = $this->commandeModel->detail((int)$id);
        if (!$commande || $commande['acheteur_id'] != $_SESSION['user_id']) {
            $this->redirect('dashboard/acheteur');
        }

        $this->render('commande/confirmation', [
            'commande'  => $commande,
            'lignes'    => $this->commandeModel->lignes((int)$id),
            'paiement'  => $this->paiementModel->parCommande((int)$id),
            'livraison' => $this->livraisonModel->parCommande((int)$id),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // Méthode privée : lit le panier depuis la session
    // ──────────────────────────────────────────────────────
    private function getLignesPanier(): array
    {
        $lignes = [];
        foreach ($_SESSION['panier'] ?? [] as $id => $qte) {
            $p = $this->produitModel->findById((int)$id);
            if ($p) {
                $lignes[] = [
                    'produit'   => $p,
                    'quantite'  => $qte,
                    'sousTotal' => $p['prix'] * $qte,
                ];
            }
        }
        return $lignes;
    }
}