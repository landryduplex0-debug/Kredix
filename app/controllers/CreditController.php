<?php
/**
 * Contrôleur des crédits
 */
class CreditController extends Controller
{
    private Credit $creditModel;
    private Customer $customerModel;
    private Notification $notificationModel;
    private Subscription $subscriptionModel;

    public function __construct()
    {
        parent::__construct();
        $this->creditModel = new Credit();
        $this->customerModel = new Customer();
        $this->notificationModel = new Notification();
        $this->subscriptionModel = new Subscription();
    }

    /**
     * Liste des crédits
     */
    public function index(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $page = max(1, (int)($this->query('page') ?? 1));
        $status = $this->query('status', '');

        $credits = $this->creditModel->getByShop($shop->id, $page, 15, $status);

        $this->view('credits.index', [
            'title'         => 'Mes Crédits',
            'credits'       => $credits,
            'shop'          => $shop,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(?string $customer_id = null): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        // Vérifier la limite mensuelle
        $plan = $this->subscriptionModel->getCurrentPlan($this->user()->id);
        $monthCount = $this->creditModel->countThisMonth($shop->id);
        
        if (!$this->subscriptionModel->checkLimit($plan, 'credits_monthly', $monthCount)) {
            flash('warning', 'Limite mensuelle de crédits atteinte. Passez au plan supérieur.');
            $this->redirect('subscription');
            return;
        }

        // Liste des clients pour le select
        $customersData = $this->customerModel->getByShop($shop->id, 1, 500);
        $customers = $customersData['items'];

        $selectedCustomer = null;
        if ($customer_id) {
            $selectedCustomer = $this->customerModel->find((int)$customer_id);
        }

        $this->view('credits.create', [
            'title'            => 'Nouveau Crédit',
            'customers'        => $customers,
            'selectedCustomer' => $selectedCustomer,
            'shop'             => $shop,
        ]);
    }

    /**
     * Enregistrer un crédit
     */
    public function store(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('credits/create');
            return;
        }

        $shop = $this->shop();

        $validator = new Validator($_POST);
        $validator->required('customer_id', 'Client')
                  ->required('amount', 'Montant')
                  ->positiveAmount('amount', 'Montant');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            storeOldInput();
            $this->redirect('credits/create');
            return;
        }

        // Vérifier que le client appartient à la boutique
        $customer = $this->customerModel->find((int)$this->input('customer_id'));
        if (!$customer || $customer->shop_id !== $shop->id) {
            flash('error', 'Client invalide.');
            $this->redirect('credits/create');
            return;
        }

        $creditId = $this->creditModel->addCredit([
            'shop_id'     => $shop->id,
            'customer_id' => (int)$this->input('customer_id'),
            'amount'      => (float)$_POST['amount'],
            'description' => $this->input('description'),
            'due_date'    => $this->input('due_date') ?: null,
        ]);

        // Mettre à jour les totaux du client
        $this->customerModel->updateTotals($customer->id);

        // Notification
        $this->notificationModel->notify(
            $shop->id,
            'Nouveau crédit',
            formatMoney($_POST['amount']) . ' accordé à ' . $customer->full_name,
            'system',
            $customer->id,
            $creditId
        );

        clearOldInput();
        flash('success', 'Crédit de ' . formatMoney($_POST['amount']) . ' enregistré pour ' . $customer->full_name . ' ! 📝');
        $this->redirect('credits/show/' . $creditId);
    }

    /**
     * Détail d'un crédit
     */
    public function show(string $id): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        $credit = $this->creditModel->getWithDetails((int)$id, $shop->id);
        
        if (!$credit) {
            flash('error', 'Crédit non trouvé.');
            $this->redirect('credits');
            return;
        }

        // Historique des paiements pour ce crédit
        $paymentModel = new Payment();
        $payments = $paymentModel->getByCredit((int)$id);

        $this->view('credits.show', [
            'title'    => 'Crédit #' . $id,
            'credit'   => $credit,
            'payments' => $payments,
            'shop'     => $shop,
        ]);
    }

    /**
     * Annuler un crédit
     */
    public function cancel(string $id): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('credits/show/' . $id);
            return;
        }

        $shop = $this->shop();
        $credit = $this->creditModel->getWithDetails((int)$id, $shop->id);
        
        if (!$credit) {
            flash('error', 'Crédit non trouvé.');
            $this->redirect('credits');
            return;
        }

        $this->creditModel->update((int)$id, ['status' => 'cancelled']);
        $this->customerModel->updateTotals($credit->customer_id);

        flash('success', 'Crédit annulé.');
        $this->redirect('credits');
    }
}
