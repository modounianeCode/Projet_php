<?php
require_once ROOT_PATH . '/core/Model.php';

/**
 * PaiementModel - Gestion des paiements
 */
class Paiement extends Model
{
    protected string $table = 'paiements';

    /** Enregistre un paiement en attente */
    public function creer(int $commandeId, string $methode, float $montant): string
    {
        $reference = 'AGR-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(100, 999);

        $sql  = "INSERT INTO paiements (commande_id, methode, statut, montant, reference)
                 VALUES (:cid, :methode, 'en_attente', :montant, :ref)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':cid'     => $commandeId,
            ':methode' => $methode,
            ':montant' => $montant,
            ':ref'     => $reference,
        ]);
        return $reference;
    }

    /** Confirme un paiement */
    public function confirmer(int $commandeId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE paiements SET statut = 'confirme' WHERE commande_id = :cid"
        );
        return $stmt->execute([':cid' => $commandeId]);
    }

    /** Retourne le paiement d'une commande */
    public function parCommande(int $commandeId): array|false
    {
        return $this->findOneBy('commande_id', $commandeId);
    }
}