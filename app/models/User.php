<?php
/**
 * Modèle User
 */
class User extends Model
{
    protected string $table = 'users';

    /**
     * Créer un nouvel utilisateur avec hash du mot de passe
     */
    public function register(array $data): int
    {
        return $this->create([
            'full_name'     => $data['full_name'],
            'email'         => $data['email'] ?? null,
            'phone'         => $data['phone'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
    }

    /**
     * Authentifier un utilisateur
     */
    public function authenticate(string $login, string $password): ?object
    {
        // Chercher par email ou téléphone
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE (email = ? OR phone = ?) AND is_active = 1",
            [$login, $login]
        );

        if ($user && password_verify($password, $user->password_hash)) {
            // Mettre à jour la dernière connexion
            $this->update($user->id, ['last_login_at' => date('Y-m-d H:i:s')]);
            return $user;
        }

        return null;
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->fetch($sql, $params);
        return $result->total > 0;
    }

    /**
     * Vérifier si un téléphone existe déjà
     */
    public function phoneExists(string $phone, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE phone = ?";
        $params = [$phone];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->fetch($sql, $params);
        return $result->total > 0;
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(int $userId, string $newPassword): int
    {
        return $this->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }
}
