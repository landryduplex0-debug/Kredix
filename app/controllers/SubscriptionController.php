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
        
        // Si c'est un POST manuel, valider CSRF. Si c'est un retour GET Monetbil, on passe.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->validateCSRF()) {
            $this->redirect('subscription');
            return;
        }

        $plan = $_GET['plan'] ?? $this->input('plan');
        
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
    /**
     * Initier le paiement Monetbil (Mobile Money)
     */
    public function pay(): void
    {
        $this->requireAuth();
        $plan = $_GET['plan'] ?? '';
        
        if (!in_array($plan, ['starter', 'premium'])) {
            flash('error', 'Plan invalide.');
            $this->redirect('subscription');
            return;
        }

        $user = $this->user();
        $price = $plan === 'starter' ? STARTER_PRICE : PREMIUM_PRICE;
        
        $serviceKey = 'PA5dcwD0KXBsM4zJUuSB2ogQDu0tTqLb'; // Clé fournie par l'utilisateur
        
        // Préparer la requête Monetbil
        $paymentRef = time() . '_' . $user->id;
        
        $postData = [
            'amount' => $price,
            'item_ref' => $plan,
            'payment_ref' => $paymentRef,
            'user' => $user->email ?? $user->id,
            'return_url' => url("subscription/upgrade?plan={$plan}&ref={$paymentRef}"),
            'notify_url' => url("api/webhook/monetbil"),
            'currency' => 'XAF'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.monetbil.com/widget/v2.1/" . $serviceKey);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($result && isset($result['payment_url'])) {
            // Rediriger vers l'URL Monetbil, ce qui affichera le widget dans l'iframe
            header('Location: ' . $result['payment_url']);
            exit;
        } else {
            flash('error', 'Erreur lors de l\'initialisation du paiement Mobile Money.');
            $this->redirect('subscription');
        }
    }
}
