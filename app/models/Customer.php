<?php
/**
 * Modèle Customer
 */
class Customer extends Model
{
    protected string $table = 'customers';

    /**
     * Obtenir tous les clients d'une boutique
     */
    public function getByShop(int $shopId, int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        
        $total = $this->count('shop_id = ? AND is_active = 1', [$shopId]);
        $totalPages = ceil($total / $perPage);
        
        $items = $this->db->fetchAll(
            "SELECT * FROM customers WHERE shop_id = ? AND is_active = 1 ORDER BY full_name ASC LIMIT ? OFFSET ?",
            [$shopId, $perPage, $offset]
        );

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages
        ];
    }

    /**
     * Rechercher des clients
     */
    public function search(int $shopId, string $query): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM customers 
             WHERE shop_id = ? AND is_active = 1 
             AND (full_name LIKE ? OR phone LIKE ?) 
             ORDER BY full_name ASC LIMIT 20",
            [$shopId, "%{$query}%", "%{$query}%"]
        );
    }

    /**
     * Obtenir un client avec ses statistiques
     */
    public function getWithStats(int $id, int $shopId): ?object
    {
        return $this->db->fetch(
            "SELECT c.*, 
                    COALESCE(SUM(cr.amount), 0) as total_credits,
                    COALESCE(SUM(cr.amount_paid), 0) as total_paid,
                    COALESCE(SUM(cr.balance), 0) as total_balance,
                    COUNT(cr.id) as credit_count
             FROM customers c 
             LEFT JOIN credits cr ON c.id = cr.customer_id AND cr.status != 'cancelled'
             WHERE c.id = ? AND c.shop_id = ? AND c.is_active = 1
             GROUP BY c.id",
            [$id, $shopId]
        );
    }

    /**
     * Mettre à jour les totaux du client
     */
    public function updateTotals(int $customerId): void
    {
        $this->db->execute(
            "UPDATE customers SET 
                total_credits = (SELECT COALESCE(SUM(amount), 0) FROM credits WHERE customer_id = ? AND status != 'cancelled'),
                total_paid = (SELECT COALESCE(SUM(amount_paid), 0) FROM credits WHERE customer_id = ? AND status != 'cancelled')
             WHERE id = ?",
            [$customerId, $customerId, $customerId]
        );
    }

    /**
     * Compter les clients d'une boutique
     */
    public function countByShop(int $shopId): int
    {
        return $this->count('shop_id = ? AND is_active = 1', [$shopId]);
    }

    /**
     * Top débiteurs
     */
    public function topDebtors(int $shopId, int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, 
                    COALESCE(SUM(cr.balance), 0) as total_debt
             FROM customers c 
             JOIN credits cr ON c.id = cr.customer_id 
             WHERE c.shop_id = ? AND c.is_active = 1 AND cr.status IN ('pending', 'partial', 'overdue')
             GROUP BY c.id 
             HAVING total_debt > 0
             ORDER BY total_debt DESC 
             LIMIT ?",
            [$shopId, $limit]
        );
    }
}
