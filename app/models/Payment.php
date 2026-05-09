<?php
/**
 * Modèle Payment
 */
class Payment extends Model
{
    protected string $table = 'payments';

    /**
     * Obtenir les paiements d'une boutique
     */
    public function getByShop(int $shopId, int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;

        $totalResult = $this->db->fetch(
            "SELECT COUNT(*) as total FROM payments WHERE shop_id = ?",
            [$shopId]
        );
        $total = $totalResult->total;
        $totalPages = ceil($total / $perPage);

        $items = $this->db->fetchAll(
            "SELECT p.*, c.full_name as customer_name, cr.description as credit_description
             FROM payments p 
             JOIN customers c ON p.customer_id = c.id 
             JOIN credits cr ON p.credit_id = cr.id
             WHERE p.shop_id = ? 
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?",
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
     * Obtenir les paiements d'un crédit
     */
    public function getByCredit(int $creditId): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.full_name as customer_name 
             FROM payments p 
             JOIN customers c ON p.customer_id = c.id 
             WHERE p.credit_id = ? 
             ORDER BY p.created_at DESC",
            [$creditId]
        );
    }

    /**
     * Enregistrer un paiement
     */
    public function addPayment(array $data): int
    {
        return $this->create([
            'shop_id'        => $data['shop_id'],
            'credit_id'      => $data['credit_id'],
            'customer_id'    => $data['customer_id'],
            'amount'         => $data['amount'],
            'payment_method' => $data['payment_method'] ?? 'cash',
            'reference'      => $data['reference'] ?? null,
            'notes'          => $data['notes'] ?? '',
            'paid_at'        => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Total payé aujourd'hui
     */
    public function todayTotal(int $shopId): float
    {
        $result = $this->db->fetch(
            "SELECT COALESCE(SUM(amount), 0) as total 
             FROM payments 
             WHERE shop_id = ? AND DATE(paid_at) = CURDATE()",
            [$shopId]
        );
        return (float)$result->total;
    }

    /**
     * Paiements récents
     */
    public function getRecent(int $shopId, int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.full_name as customer_name 
             FROM payments p 
             JOIN customers c ON p.customer_id = c.id 
             WHERE p.shop_id = ? 
             ORDER BY p.created_at DESC 
             LIMIT ?",
            [$shopId, $limit]
        );
    }
}
