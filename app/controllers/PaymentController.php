<?php
/**
 * Contrôleur des paiements
 */
class PaymentController extends Controller
{
    private Payment $paymentModel;
    private Credit $creditModel;
    private Customer $customerModel;
    private Notification $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->paymentModel = new Payment();
        $this->creditModel = new Credit();
        $this->customerModel = new Customer();
        $this->notificationModel = new Notification();
    }

    /**
     * Liste des paiements
     */
    public function index(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $page = max(1, (int)($this->query('page') ?? 1));

        $payments = $this->paymentModel->getByShop($shop->id, $page);

        $this->view('payments.index', [
            'title'    => 'Paiements',
            'payments' => $payments,
            'shop'     => $shop,
        ]);
    }

    /**
     * Formulaire de paiement
     */
    public function create(string $credit_id): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        $credit = $this->creditModel->getWithDetails((int)$credit_id, $shop->id);
        
        if (!$credit) {
            flash('error', 'Crédit non trouvé.');
            $this->redirect('credits');
            return;
        }

        if ($credit->status === 'paid' || $credit->status === 'cancelled') {
            flash('warning', 'Ce crédit est déjà soldé ou annulé.');
            $this->redirect('credits/show/' . $credit_id);
            return;
        }

        $this->view('payments.create', [
            'title'  => 'Enregistrer un paiement',
            'credit' => $credit,
            'shop'   => $shop,
        ]);
    }

    /**
     * Enregistrer un paiement
     */
    public function store(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('credits');
            return;
        }

        $shop = $this->shop();
        $creditId = (int)$this->input('credit_id');

        $credit = $this->creditModel->getWithDetails($creditId, $shop->id);
        
        if (!$credit) {
            flash('error', 'Crédit non trouvé.');
            $this->redirect('credits');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('amount', 'Montant')
                  ->positiveAmount('amount', 'Montant');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            $this->redirect('payments/create/' . $creditId);
            return;
        }

        $amount = (float)$_POST['amount'];
        $balance = (float)$credit->balance;

        // Vérifier que le montant ne dépasse pas le solde
        if ($amount > $balance) {
            flash('error', 'Le montant ne peut pas dépasser le solde restant de ' . formatMoney($balance));
            $this->redirect('payments/create/' . $creditId);
            return;
        }

        // Enregistrer le paiement
        $this->paymentModel->addPayment([
            'shop_id'        => $shop->id,
            'credit_id'      => $creditId,
            'customer_id'    => $credit->customer_id,
            'amount'         => $amount,
            'payment_method' => $this->input('payment_method') ?? 'cash',
            'notes'          => $this->input('notes'),
        ]);

        // Mettre à jour le crédit
        $this->creditModel->updateAfterPayment($creditId);
        
        // Mettre à jour les totaux du client
        $this->customerModel->updateTotals($credit->customer_id);

        // Notification
        $statusMessage = $amount >= $balance ? '✅ Crédit soldé !' : 'Paiement partiel reçu';
        $this->notificationModel->notify(
            $shop->id,
            $statusMessage,
            formatMoney($amount) . ' reçu de ' . $credit->customer_name,
            'payment',
            $credit->customer_id,
            $creditId
        );

        flash('success', 'Paiement de ' . formatMoney($amount) . ' enregistré ! 💰');
        $this->redirect('credits/show/' . $creditId);
    }
}
