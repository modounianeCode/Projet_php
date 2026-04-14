<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once APP_PATH  . '/models/Produit.php';

/**
 * ProduitController - Catalogue, détail, CRUD vendeur
 */
class ProduitController extends Controller
{
    private Produit $produitModel;

    public function __construct()
    {
        $this->produitModel = new Produit();
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit  ou  /  → Page d'accueil
    // ──────────────────────────────────────────────────────
    public function index(): void
    {
        $vedettes   = $this->produitModel->vedettes(8);
        $categories = $this->produitModel->categories();

        $this->render('produits/accueil', [
            'vedettes'   => $vedettes,
            'categories' => $categories,
        ]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit/catalogue  → Liste filtrée + pagination
    // ──────────────────────────────────────────────────────
    public function catalogue(): void
    {
        $q      = $this->clean($_GET['q']   ?? '');
        $cat    = $this->clean($_GET['cat'] ?? '');
        $pMin   = max(0,      (float)($_GET['pMin'] ?? 0));
        $pMax   = max($pMin,  (float)($_GET['pMax'] ?? 999999));
        $page   = max(1,      (int)  ($_GET['page'] ?? 1));
        $parPage = 12;
        $offset  = ($page - 1) * $parPage;

        if ($q !== '' || $cat !== '' || $pMin > 0 || $pMax < 999999) {
            $tous     = $this->produitModel->rechercher($q, $cat, $pMin, $pMax);
            $total    = count($tous);
            $produits = array_slice($tous, $offset, $parPage);
        } else {
            $total    = $this->produitModel->compterActifs();
            $produits = $this->produitModel->lister($parPage, $offset);
        }

        $this->render('produits/catalogue', [
            'produits'   => $produits,
            'categories' => $this->produitModel->categories(),
            'total'      => $total,
            'page'       => $page,
            'nbPages'    => max(1, ceil($total / $parPage)),
            'q'          => $q,
            'cat'        => $cat,
            'pMin'       => $pMin,
            'pMax'       => $pMax,
        ]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit/detail/{id}
    // ──────────────────────────────────────────────────────
    public function detail(string $id = '0'): void
    {
        $produit = $this->produitModel->detail((int)$id);

        if (!$produit) {
            $this->flash('error', 'Produit introuvable.');
            $this->redirect('produit/catalogue');
        }

        $similaires = $this->produitModel->parCategorie($produit['categorie'], 4);

        $this->render('produits/detail', [
            'produit'    => $produit,
            'similaires' => $similaires,
        ]);
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit/ajouter  → Formulaire (vendeur)
    // ──────────────────────────────────────────────────────
    public function ajouter(): void
    {
        $this->requireRole('vendeur');
        $this->render('produits/formulaire', ['produit' => null]);
    }

    // ──────────────────────────────────────────────────────
    // POST /produit/enregistrer  → Sauvegarde (vendeur)
    // ──────────────────────────────────────────────────────
    public function enregistrer(): void
    {
        $this->requireRole('vendeur');
        if (!$this->isPost()) { $this->redirect('produit/ajouter'); }

        $image = 'default.svg';
        if (!empty($_FILES['image']['name'])) {
            $upload = $this->uploadImage($_FILES['image'], 'prod');
            if ($upload) {
                $image = $upload;
            } else {
                $this->flash('error', 'Image invalide (JPG/PNG, max 2 Mo).');
                $this->redirect('produit/ajouter');
            }
        }

        $this->produitModel->ajouter([
            'vendeur_id'  => $_SESSION['user_id'],
            'nom'         => $this->clean($_POST['nom']         ?? ''),
            'description' => $this->clean($_POST['description'] ?? ''),
            'prix'        => (float)($_POST['prix']             ?? 0),
            'unite'       => $this->clean($_POST['unite']       ?? 'kg'),
            'stock'       => (int)  ($_POST['stock']            ?? 0),
            'categorie'   => $this->clean($_POST['categorie']   ?? ''),
            'image'       => $image,
        ]);

        $this->flash('success', 'Produit publié avec succès !');
        $this->redirect('dashboard/vendeur');
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit/modifier/{id}  → Formulaire édition
    // ──────────────────────────────────────────────────────
    public function modifier(string $id = '0'): void
    {
        $this->requireRole('vendeur');
        $produit = $this->produitModel->findById((int)$id);

        if (!$produit || $produit['vendeur_id'] != $_SESSION['user_id']) {
            $this->flash('error', 'Produit introuvable.');
            $this->redirect('dashboard/vendeur');
        }

        $this->render('produits/formulaire', ['produit' => $produit]);
    }

    // ──────────────────────────────────────────────────────
    // POST /produit/mettreAJour/{id}  → Mise à jour
    // ──────────────────────────────────────────────────────
    public function mettreAJour(string $id = '0'): void
    {
        $this->requireRole('vendeur');
        if (!$this->isPost()) { $this->redirect('dashboard/vendeur'); }

        $produit = $this->produitModel->findById((int)$id);
        if (!$produit || $produit['vendeur_id'] != $_SESSION['user_id']) {
            $this->redirect('dashboard/vendeur');
        }

        $image = $produit['image'];
        if (!empty($_FILES['image']['name'])) {
            $upload = $this->uploadImage($_FILES['image'], 'prod');
            if ($upload) $image = $upload;
        }

        $this->produitModel->modifier((int)$id, $_SESSION['user_id'], [
            'nom'         => $this->clean($_POST['nom']         ?? ''),
            'description' => $this->clean($_POST['description'] ?? ''),
            'prix'        => (float)($_POST['prix']             ?? 0),
            'unite'       => $this->clean($_POST['unite']       ?? 'kg'),
            'stock'       => (int)  ($_POST['stock']            ?? 0),
            'categorie'   => $this->clean($_POST['categorie']   ?? ''),
            'image'       => $image,
        ]);

        $this->flash('success', 'Produit mis à jour.');
        $this->redirect('dashboard/vendeur');
    }

    // ──────────────────────────────────────────────────────
    // GET  /produit/supprimer/{id}
    // ──────────────────────────────────────────────────────
    public function supprimer(string $id = '0'): void
    {
        $this->requireRole('vendeur');
        $this->produitModel->supprimer((int)$id, $_SESSION['user_id']);
        $this->flash('success', 'Produit supprimé.');
        $this->redirect('dashboard/vendeur');
    }
}