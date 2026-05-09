<?php
/**
 * Modèle Subscription
 */
class Subscription extends Model
{
    protected string $table = 'subscriptions';

    /**
     * Obtenir l'abonnement actif d'un utilisateur
     */
    public function getActive(int $userId): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM subscriptions 
             WHERE user_id = ? AND is_active = 1 
             AND (expires_at IS NULL OR expires_at > NOW())
             ORDER BY id DESC LIMIT 1",
            [$userId]
        );
    }

    /**
     * Obtenir le plan actuel
     */
    public function getCurrentPlan(int $userId): string
    {
        $sub = $this->getActive($userId);
        return $sub ? $sub->plan : 'free';
    }

    /**
     * Créer l'abonnement gratuit par défaut
     */
    public function createFree(int $userId): int
    {
        return $this->create([
            'user_id' => $userId,
            'plan'    => 'free',
            'price'   => 0,
        ]);
    }

    /**
     * Vérifier les limites du plan
     */
    public function checkLimit(string $plan, string $limitType, int $currentCount): bool
    {
        $limits = [
            'free' => [
                'customers' => FREE_MAX_CUSTOMERS,
                'credits_monthly' => FREE_MAX_CREDITS_PER_MONTH,
            ],
            'starter' => [
                'customers' => STARTER_MAX_CUSTOMERS,
                'credits_monthly' => STARTER_MAX_CREDITS_PER_MONTH,
            ],
            'premium' => [
                'customers' => PHP_INT_MAX,
                'credits_monthly' => PHP_INT_MAX,
            ]
        ];

        $max = $limits[$plan][$limitType] ?? PHP_INT_MAX;
        return $currentCount < $max;
    }
}
