<?php
/**
 * Contrôleur des abonnements
 */
class SubscriptionController extends Controller
{
    private Subscription $subscriptionModel;

    public function __construct()
    {
        parent::__construct();
        $this->subscriptionModel = new Subscription();
    }

    /**
     * Page des abonnements
     */
    public function index(): void
    {
        $this->requireAuth();
        $user = $this->user();
        $currentPlan = $this->subscriptionModel->getCurrentPlan($user->id);
        $subscription = $this->subscriptionModel->getActive($user->id);

        $this->view('subscription.index', [
            'title'        => 'Mon Abonnement',
            'currentPlan'  => $currentPlan,
            'subscription' => $subscription,
            'shop'         => $this->shop(),
        ]);
    }

    /**
     * Mettre à niveau l'abonnement
     */
    public function upgrade(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('subscription');
            return;
        }

        $plan = $this->input('plan');
        
        if (!in_array($plan, ['starter', 'premium'])) {
            flash('error', 'Plan invalide.');
            $this->redirect('subscription');
            return;
        }

        $user = $this->user();
        $price = $plan === 'starter' ? STARTER_PRICE : PREMIUM_PRICE;

        // En production, ici on intégrerait Mobile Money
        // Pour l'instant, on simule un upgrade
        
        // Désactiver l'ancien abonnement
        $oldSub = $this->subscriptionModel->getActive($user->id);
        if ($oldSub) {
            $this->subscriptionModel->update($oldSub->id, ['is_active' => 0]);
        }

        // Créer le nouvel abonnement
        $this->subscriptionModel->create([
            'user_id'    => $user->id,
            'plan'       => $plan,
            'price'      => $price,
            'currency'   => 'XAF',
            'starts_at'  => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);

        flash('success', 'Abonnement mis à jour vers le plan ' . ucfirst($plan) . ' ! 🎉');
        $this->redirect('subscription');
    }
}
