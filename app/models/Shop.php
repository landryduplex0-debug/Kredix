<?php
/**
 * Modèle Shop
 */
class Shop extends Model
{
    protected string $table = 'shops';

    /**
     * Obtenir la boutique de l'utilisateur
     */
    public function getByUser(int $userId): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM shops WHERE user_id = ? AND is_active = 1 LIMIT 1",
            [$userId]
        );
    }

    /**
     * Créer la boutique par défaut pour un nouvel utilisateur
     */
    public function createDefault(int $userId, string $shopName, string $phone = ''): int
    {
        return $this->create([
            'user_id'  => $userId,
            'name'     => $shopName,
            'phone'    => $phone,
            'type'     => 'boutique',
            'city'     => 'Douala',
            'currency' => 'XAF'
        ]);
    }
}
