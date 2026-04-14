<?php
require_once ROOT_PATH . '/core/Model.php';

/**
 * ProduitModel - Gestion des produits agricoles
 */
class Produit extends Model
{
    protected string $table = 'produits';

    /** Liste tous les produits avec le nom du vendeur, paginés */
    public function lister(int $limite = 12, int $offset = 0): array
    {
        $sql = "SELECT p.*, u.nom AS vendeur_nom
                FROM produits p
                INNER JOIN users u ON u.id = p.vendeur_id
                WHERE p.stock > 0
                ORDER BY p.created_at DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite',  $limite,  PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Compte les produits en stock */
    public function compterActifs(): int
    {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM produits WHERE stock > 0"
        )->fetchColumn();
    }

    /** Recherche avec filtres (nom, catégorie, prix min/max) */
    public function rechercher(string $q, string $cat, float $pMin, float $pMax): array
    {
        $sql  = "SELECT p.*, u.nom AS vendeur_nom
                 FROM produits p
                 INNER JOIN users u ON u.id = p.vendeur_id
                 WHERE p.stock > 0
                   AND (p.nom LIKE :q OR p.description LIKE :q2)
                   AND p.prix BETWEEN :pMin AND :pMax";
        $args = [
            ':q'    => "%{$q}%",
            ':q2'   => "%{$q}%",
            ':pMin' => $pMin,
            ':pMax' => $pMax,
        ];
        if ($cat !== '') {
            $sql  .= " AND p.categorie = :cat";
            $args[':cat'] = $cat;
        }
        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }

    /** Détail d'un produit avec infos vendeur */
    public function detail(int $id): array|false
    {
        $sql = "SELECT p.*, u.nom AS vendeur_nom, u.telephone AS vendeur_tel, u.adresse AS vendeur_adresse
                FROM produits p
                INNER JOIN users u ON u.id = p.vendeur_id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Produits par catégorie (pour les similaires) */
    public function parCategorie(string $categorie, int $limite = 4): array
    {
        $sql  = "SELECT p.*, u.nom AS vendeur_nom
                 FROM produits p
                 INNER JOIN users u ON u.id = p.vendeur_id
                 WHERE p.categorie = :cat AND p.stock > 0
                 ORDER BY RAND()
                 LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat', $categorie);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Produits vedettes (8 plus récents) */
    public function vedettes(int $limite = 8): array
    {
        $sql  = "SELECT p.*, u.nom AS vendeur_nom
                 FROM produits p
                 INNER JOIN users u ON u.id = p.vendeur_id
                 WHERE p.stock > 0
                 ORDER BY p.created_at DESC
                 LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Produits d'un vendeur précis */
    public function parVendeur(int $vendeurId): array
    {
        return $this->findAllBy('vendeur_id', $vendeurId);
    }

    /** Toutes les catégories distinctes */
    public function categories(): array
    {
        return $this->db->query(
            "SELECT DISTINCT categorie FROM produits ORDER BY categorie"
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Ajoute un produit */
    public function ajouter(array $data): bool
    {
        $sql  = "INSERT INTO produits (vendeur_id, nom, description, prix, unite, stock, categorie, image)
                 VALUES (:vid, :nom, :desc, :prix, :unite, :stock, :cat, :img)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':vid'   => $data['vendeur_id'],
            ':nom'   => $data['nom'],
            ':desc'  => $data['description'],
            ':prix'  => $data['prix'],
            ':unite' => $data['unite'],
            ':stock' => $data['stock'],
            ':cat'   => $data['categorie'],
            ':img'   => $data['image'],
        ]);
    }

    /** Modifie un produit (seulement si le vendeur est propriétaire) */
    public function modifier(int $id, int $vendeurId, array $data): bool
    {
        $sql  = "UPDATE produits
                 SET nom=:nom, description=:desc, prix=:prix, unite=:unite,
                     stock=:stock, categorie=:cat, image=:img
                 WHERE id=:id AND vendeur_id=:vid";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'   => $data['nom'],
            ':desc'  => $data['description'],
            ':prix'  => $data['prix'],
            ':unite' => $data['unite'],
            ':stock' => $data['stock'],
            ':cat'   => $data['categorie'],
            ':img'   => $data['image'],
            ':id'    => $id,
            ':vid'   => $vendeurId,
        ]);
    }

    /** Supprime un produit (seulement si le vendeur est propriétaire) */
    public function supprimer(int $id, int $vendeurId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM produits WHERE id=:id AND vendeur_id=:vid"
        );
        return $stmt->execute([':id' => $id, ':vid' => $vendeurId]);
    }

    /** Décrémente le stock lors d'un achat */
    public function decrementerStock(int $id, int $qte): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE produits SET stock = stock - ? WHERE id = ? AND stock >= ?"
        );
        return $stmt->execute([$qte, $id, $qte]);
    }
}