<?php
/**
 * Contrôleur des clients
 */
class CustomerController extends Controller
{
    private Customer $customerModel;
    private Credit $creditModel;
    private Subscription $subscriptionModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new Customer();
        $this->creditModel = new Credit();
        $this->subscriptionModel = new Subscription();
    }

    /**
     * Liste des clients
     */
    public function index(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $page = (int)($this->query('page') ?? 1);
        $page = max(1, $page);

        $customers = $this->customerModel->getByShop($shop->id, $page);

        $this->view('customers.index', [
            'title'     => 'Mes Clients',
            'customers' => $customers,
            'shop'      => $shop,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        // Vérifier la limite du plan
        $plan = $this->subscriptionModel->getCurrentPlan($this->user()->id);
        $currentCount = $this->customerModel->countByShop($shop->id);
        
        if (!$this->subscriptionModel->checkLimit($plan, 'customers', $currentCount)) {
            flash('warning', 'Vous avez atteint la limite de clients de votre plan. Passez au plan supérieur pour en ajouter plus.');
            $this->redirect('subscription');
            return;
        }

        $this->view('customers.create', [
            'title' => 'Nouveau Client',
            'shop'  => $shop,
        ]);
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('customers/create');
            return;
        }

        $shop = $this->shop();

        $validator = new Validator($_POST);
        $validator->required('full_name', 'Nom complet');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            storeOldInput();
            $this->redirect('customers/create');
            return;
        }

        $this->customerModel->create([
            'shop_id'   => $shop->id,
            'full_name' => $this->input('full_name'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'notes'     => $this->input('notes'),
        ]);

        clearOldInput();
        flash('success', 'Client ajouté avec succès ! 🎉');
        $this->redirect('customers');
    }

    /**
     * Fiche d'un client
     */
    public function show(string $id): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        $customer = $this->customerModel->getWithStats((int)$id, $shop->id);
        
        if (!$customer) {
            flash('error', 'Client non trouvé.');
            $this->redirect('customers');
            return;
        }

        $credits = $this->creditModel->getByCustomer((int)$id);

        $this->view('customers.show', [
            'title'    => $customer->full_name,
            'customer' => $customer,
            'credits'  => $credits,
            'shop'     => $shop,
        ]);
    }

    /**
     * Formulaire de modification
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        $customer = $this->customerModel->getWithStats((int)$id, $shop->id);
        
        if (!$customer) {
            flash('error', 'Client non trouvé.');
            $this->redirect('customers');
            return;
        }

        $this->view('customers.edit', [
            'title'    => 'Modifier ' . $customer->full_name,
            'customer' => $customer,
            'shop'     => $shop,
        ]);
    }

    /**
     * Mettre à jour un client
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('customers/edit/' . $id);
            return;
        }

        $shop = $this->shop();
        $customer = $this->customerModel->findBy('id', (int)$id);

        if (!$customer || $customer->shop_id !== $shop->id) {
            flash('error', 'Client non trouvé.');
            $this->redirect('customers');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('full_name', 'Nom complet');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            $this->redirect('customers/edit/' . $id);
            return;
        }

        $this->customerModel->update((int)$id, [
            'full_name' => $this->input('full_name'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'notes'     => $this->input('notes'),
        ]);

        flash('success', 'Client mis à jour ! ✅');
        $this->redirect('customers/show/' . $id);
    }

    /**
     * Supprimer un client (soft delete)
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('customers');
            return;
        }

        $shop = $this->shop();
        $customer = $this->customerModel->findBy('id', (int)$id);

        if (!$customer || $customer->shop_id !== $shop->id) {
            flash('error', 'Client non trouvé.');
            $this->redirect('customers');
            return;
        }

        $this->customerModel->update((int)$id, ['is_active' => 0]);
        flash('success', 'Client supprimé.');
        $this->redirect('customers');
    }

    /**
     * Recherche de clients
     */
    public function search(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $query = $this->query('q', '');

        $customers = $this->customerModel->search($shop->id, $query);

        $this->view('customers.index', [
            'title'       => 'Recherche : ' . $query,
            'customers'   => ['items' => $customers, 'total' => count($customers), 'page' => 1, 'total_pages' => 1, 'has_prev' => false, 'has_next' => false],
            'shop'        => $shop,
            'searchQuery' => $query,
        ]);
    }

    /**
     * API: Recherche AJAX
     */
    public function apiSearch(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $query = $this->query('q', '');

        $customers = $this->customerModel->search($shop->id, $query);

        $this->json(['customers' => $customers]);
    }
}
