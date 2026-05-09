<?php
/**
 * Modèle Notification
 */
class Notification extends Model
{
    protected string $table = 'notifications';

    /**
     * Obtenir les notifications non lues d'une boutique
     */
    public function getUnread(int $shopId, int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM notifications 
             WHERE shop_id = ? AND is_read = 0 
             ORDER BY created_at DESC LIMIT ?",
            [$shopId, $limit]
        );
    }

    /**
     * Compter les notifications non lues
     */
    public function countUnread(int $shopId): int
    {
        return $this->count('shop_id = ? AND is_read = 0', [$shopId]);
    }

    /**
     * Marquer comme lue
     */
    public function markAsRead(int $id): void
    {
        $this->update($id, ['is_read' => 1]);
    }

    /**
     * Marquer toutes comme lues
     */
    public function markAllAsRead(int $shopId): void
    {
        $this->db->execute(
            "UPDATE notifications SET is_read = 1 WHERE shop_id = ? AND is_read = 0",
            [$shopId]
        );
    }

    /**
     * Créer une notification
     */
    public function notify(int $shopId, string $title, string $message, string $type = 'system', ?int $customerId = null, ?int $creditId = null): int
    {
        return $this->create([
            'shop_id'     => $shopId,
            'customer_id' => $customerId,
            'credit_id'   => $creditId,
            'type'        => $type,
            'channel'     => 'in_app',
            'title'       => $title,
            'message'     => $message,
        ]);
    }
}
