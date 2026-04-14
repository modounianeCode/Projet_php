<?php
require_once ROOT_PATH . '/core/Model.php';

/**
 * UserModel - Gestion des utilisateurs
 */
class User extends Model
{
    protected string $table = 'users';

    /** Crée un nouvel utilisateur (mot de passe hashé) */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO users (nom, email, mot_de_passe, role, telephone, adresse)
                VALUES (:nom, :email, :mdp, :role, :tel, :adresse)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'    => $data['nom'],
            ':email'  => $data['email'],
            ':mdp'    => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            ':role'   => $data['role'],
            ':tel'    => $data['telephone'] ?? null,
            ':adresse'=> $data['adresse']   ?? null,
        ]);
    }

    /** Retourne un utilisateur par son email */
    public function findByEmail(string $email): array|false
    {
        return $this->findOneBy('email', $email);
    }

    /** Vérifie si un email existe déjà */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Vérifie le mot de passe */
    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /** Met à jour le profil */
    public function updateProfile(int $id, array $data): bool
    {
        $sql  = "UPDATE users SET nom = :nom, telephone = :tel, adresse = :adresse WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'    => $data['nom'],
            ':tel'    => $data['telephone'] ?? null,
            ':adresse'=> $data['adresse']   ?? null,
            ':id'     => $id,
        ]);
    }
}