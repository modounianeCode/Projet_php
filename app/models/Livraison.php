<?php
require_once ROOT_PATH . '/core/Model.php';

/**
 * LivraisonModel - Gestion des livraisons
 */
class Livraison extends Model
{
    protected string $table = 'livraisons';

    /** Zones avec frais et délais (en jours) */
    public const ZONES = [
        'Dakar'       => ['frais' => 1500, 'delai' => 1],
        'Pikine'      => ['frais' => 1500, 'delai' => 1],
        'Guediawaye'  => ['frais' => 2000, 'delai' => 1],
        'Thiès'       => ['frais' => 3000, 'delai' => 2],
        'Mbour'       => ['frais' => 3500, 'delai' => 2],
        'Kaolack'     => ['frais' => 4000, 'delai' => 2],
        'Diourbel'    => ['frais' => 4000, 'delai' => 2],
        'Touba'       => ['frais' => 4500, 'delai' => 3],
        'Saint-Louis' => ['frais' => 5000, 'delai' => 3],
        'Ziguinchor'  => ['frais' => 7000, 'delai' => 4],
    ];

    /** Crée une livraison */
    public function creer(int $commandeId, string $zone): bool
    {
        $frais      = self::ZONES[$zone]['frais'] ?? 2000;
        $delai      = self::ZONES[$zone]['delai'] ?? 2;
        $datePrevue = date('Y-m-d', strtotime("+{$delai} days"));

        $sql  = "INSERT INTO livraisons (commande_id, zone, frais, statut, date_prevue)
                 VALUES (:cid, :zone, :frais, 'en_attente', :date)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cid'   => $commandeId,
            ':zone'  => $zone,
            ':frais' => $frais,
            ':date'  => $datePrevue,
        ]);
    }

    /** Met à jour le statut d'une livraison */
    public function changerStatut(int $commandeId, string $statut): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE livraisons SET statut = :statut WHERE commande_id = :cid"
        );
        return $stmt->execute([':statut' => $statut, ':cid' => $commandeId]);
    }

    /** Retourne la livraison d'une commande */
    public function parCommande(int $commandeId): array|false
    {
        return $this->findOneBy('commande_id', $commandeId);
    }

    /** Toutes les livraisons d'un acheteur */
    public function parAcheteur(int $acheteurId): array
    {
        $sql  = "SELECT l.*
                 FROM livraisons l
                 INNER JOIN commandes c ON c.id = l.commande_id
                 WHERE c.acheteur_id = :aid
                 ORDER BY l.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':aid' => $acheteurId]);
        return $stmt->fetchAll();
    }

    /** Frais pour une zone */
    public static function frais(string $zone): int
    {
        return self::ZONES[$zone]['frais'] ?? 2000;
    }
}