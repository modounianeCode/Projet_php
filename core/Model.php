<?php
/**
 * Model - Classe abstraite de base
 * Tous les modèles héritent de cette classe.
 * Fournit les opérations CRUD génériques via PDO.
 */
abstract class Model
{
    protected PDO    $db;
    protected string $table;   // défini dans chaque sous-classe

    public function __construct()
    {
        $this->db = Database::getInstance()->getPdo();
    }

    /** Retourne tous les enregistrements */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /** Retourne un enregistrement par son id */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Retourne plusieurs enregistrements filtrés par une colonne */
    public function findAllBy(string $column, mixed $value): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :val");
        $stmt->execute([':val' => $value]);
        return $stmt->fetchAll();
    }

    /** Retourne un seul enregistrement filtré par une colonne */
    public function findOneBy(string $column, mixed $value): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :val LIMIT 1");
        $stmt->execute([':val' => $value]);
        return $stmt->fetch();
    }

    /** Supprime un enregistrement */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Compte tous les enregistrements */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    /** Retourne le dernier id inséré */
    public function lastInsertId(): int
    {
        return (int) $this->db->lastInsertId();
    }
}