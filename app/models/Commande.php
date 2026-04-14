<?php
require_once ROOT_PATH . '/core/Model.php';

/**
 * CommandeModel - Gestion des commandes et de leurs lignes
 */
class Commande extends Model
{
    protected string $table = 'commandes';

    /** Crée une commande et retourne son id */
    public function creer(array $data): int
    {
        $sql  = "INSERT INTO commandes
                    (acheteur_id, statut, total, adresse_livraison, zone_livraison, frais_livraison)
                 VALUES (:aid, 'en_attente', :total, :adresse, :zone, :frais)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':aid'    => $data['acheteur_id'],
            ':total'  => $data['total'],
            ':adresse'=> $data['adresse_livraison'],
            ':zone'   => $data['zone_livraison'],
            ':frais'  => $data['frais_livraison'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** Ajoute une ligne à une commande */
    public function ajouterLigne(int $commandeId, int $produitId, int $qte, float $prixUnit): bool
    {
        $sql  = "INSERT INTO commande_lignes (commande_id, produit_id, quantite, prix_unitaire)
                 VALUES (:cid, :pid, :qte, :prix)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cid'  => $commandeId,
            ':pid'  => $produitId,
            ':qte'  => $qte,
            ':prix' => $prixUnit,
        ]);
    }

    /** Toutes les commandes d'un acheteur */
    public function parAcheteur(int $acheteurId): array
    {
        $sql  = "SELECT c.*, COUNT(cl.id) AS nb_articles
                 FROM commandes c
                 LEFT JOIN commande_lignes cl ON cl.commande_id = c.id
                 WHERE c.acheteur_id = :aid
                 GROUP BY c.id
                 ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':aid' => $acheteurId]);
        return $stmt->fetchAll();
    }

    /** Toutes les commandes contenant des produits d'un vendeur */
    public function parVendeur(int $vendeurId): array
    {
        $sql  = "SELECT DISTINCT c.*, u.nom AS acheteur_nom
                 FROM commandes c
                 INNER JOIN commande_lignes cl ON cl.commande_id = c.id
                 INNER JOIN produits p ON p.id = cl.produit_id
                 INNER JOIN users u ON u.id = c.acheteur_id
                 WHERE p.vendeur_id = :vid
                 ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vid' => $vendeurId]);
        return $stmt->fetchAll();
    }

    /** Détail d'une commande avec infos acheteur */
    public function detail(int $commandeId): array|false
    {
        $sql  = "SELECT c.*, u.nom AS acheteur_nom, u.email AS acheteur_email, u.telephone AS acheteur_tel
                 FROM commandes c
                 INNER JOIN users u ON u.id = c.acheteur_id
                 WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $commandeId]);
        return $stmt->fetch();
    }

    /** Lignes d'une commande avec détails produit */
    public function lignes(int $commandeId): array
    {
        $sql  = "SELECT cl.*, p.nom AS produit_nom, p.image, p.unite
                 FROM commande_lignes cl
                 INNER JOIN produits p ON p.id = cl.produit_id
                 WHERE cl.commande_id = :cid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cid' => $commandeId]);
        return $stmt->fetchAll();
    }

    /** Met à jour le statut d'une commande */
    public function changerStatut(int $commandeId, string $statut): bool
    {
        $stmt = $this->db->prepare("UPDATE commandes SET statut = :statut WHERE id = :id");
        return $stmt->execute([':statut' => $statut, ':id' => $commandeId]);
    }

    /** Chiffre d'affaires d'un vendeur */
    public function chiffreAffaires(int $vendeurId): float
    {
        $sql  = "SELECT COALESCE(SUM(cl.quantite * cl.prix_unitaire), 0)
                 FROM commandes c
                 INNER JOIN commande_lignes cl ON cl.commande_id = c.id
                 INNER JOIN produits p ON p.id = cl.produit_id
                 WHERE p.vendeur_id = :vid AND c.statut != 'annulee'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vid' => $vendeurId]);
        return (float) $stmt->fetchColumn();
    }
}