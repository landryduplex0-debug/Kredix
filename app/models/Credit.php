<?php
/**
 * Modèle Credit
 */
class Credit extends Model
{
    protected string $table = 'credits';

    /**
     * Obtenir les crédits d'une boutique avec les infos client
     */
    public function getByShop(int $shopId, int $page = 1, int $perPage = 15, string $status = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $condition = 'cr.shop_id = ?';
        $params = [$shopId];

        if ($status) {
            $condition .= ' AND cr.status = ?';
            $params[] = $status;
        }

        $totalResult = $this->db->fetch(
            "SELECT COUNT(*) as total FROM credits cr WHERE {$condition}",
            $params
        );
        $total = $totalResult->total;
        $totalPages = ceil($total / $perPage);

        $items = $this->db->fetchAll(
            "SELECT cr.*, c.full_name as customer_name, c.phone as customer_phone
             FROM credits cr 
             JOIN customers c ON cr.customer_id = c.id 
             WHERE {$condition}
             ORDER BY cr.created_at DESC 
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
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
     * Obtenir les crédits d'un client
     */
    public function getByCustomer(int $customerId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM credits WHERE customer_id = ? AND status != 'cancelled' ORDER BY created_at DESC",
            [$customerId]
        );
    }

    /**
     * Obtenir un crédit avec détails
     */
    public function getWithDetails(int $id, int $shopId): ?object
    {
        return $this->db->fetch(
            "SELECT cr.*, c.full_name as customer_name, c.phone as customer_phone, c.id as customer_id
             FROM credits cr 
             JOIN customers c ON cr.customer_id = c.id 
             WHERE cr.id = ? AND cr.shop_id = ?",
            [$id, $shopId]
        );
    }

    /**
     * Enregistrer un nouveau crédit
     */
    public function addCredit(array $data): int
    {
        return $this->create([
            'shop_id'     => $data['shop_id'],
            'customer_id' => $data['customer_id'],
            'amount'      => $data['amount'],
            'description' => $data['description'] ?? '',
            'due_date'    => $data['due_date'] ?? null,
            'status'      => 'pending'
        ]);
    }

    /**
     * Mettre à jour après un paiement
     */
    public function updateAfterPayment(int $creditId): void
    {
        // Recalculer le montant payé
        $result = $this->db->fetch(
            "SELECT COALESCE(SUM(amount), 0) as total_paid FROM payments WHERE credit_id = ?",
            [$creditId]
        );

        $credit = $this->find($creditId);
        $totalPaid = (float)$result->total_paid;
        $amount = (float)$credit->amount;

        // Déterminer le statut
        if ($totalPaid >= $amount) {
            $status = 'paid';
            $paidAt = date('Y-m-d H:i:s');
        } elseif ($totalPaid > 0) {
            $status = 'partial';
            $paidAt = null;
        } else {
            $status = 'pending';
            $paidAt = null;
        }

        $this->db->execute(
            "UPDATE credits SET amount_paid = ?, status = ?, paid_at = ? WHERE id = ?",
            [$totalPaid, $status, $paidAt, $creditId]
        );
    }

    /**
     * Statistiques globales de la boutique
     */
    public function getShopStats(int $shopId): object
    {
        return $this->db->fetch(
            "SELECT 
                COALESCE(SUM(amount), 0) as total_credits,
                COALESCE(SUM(amount_paid), 0) as total_paid,
                COALESCE(SUM(balance), 0) as total_balance,
                COUNT(*) as credit_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'partial' THEN 1 ELSE 0 END) as partial_count,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_count
             FROM credits 
             WHERE shop_id = ? AND status != 'cancelled'",
            [$shopId]
        );
    }

    /**
     * Crédits récents
     */
    public function getRecent(int $shopId, int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT cr.*, c.full_name as customer_name 
             FROM credits cr 
             JOIN customers c ON cr.customer_id = c.id 
             WHERE cr.shop_id = ? AND cr.status != 'cancelled'
             ORDER BY cr.created_at DESC 
             LIMIT ?",
            [$shopId, $limit]
        );
    }

    /**
     * Statistiques mensuelles
     */
    public function monthlyStats(int $shopId, int $months = 6): array
    {
        return $this->db->fetchAll(
            "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COALESCE(SUM(amount), 0) as total_credits,
                COALESCE(SUM(amount_paid), 0) as total_paid
             FROM credits 
             WHERE shop_id = ? AND status != 'cancelled'
             AND created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC",
            [$shopId, $months]
        );
    }

    /**
     * Compter les crédits du mois courant
     */
    public function countThisMonth(int $shopId): int
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM credits 
             WHERE shop_id = ? AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())",
            [$shopId]
        );
        return (int)$result->total;
    }
}
