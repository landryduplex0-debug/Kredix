<?php
/**
 * Contrôleur des rapports
 */
class ReportController extends Controller
{
    private Credit $creditModel;
    private Customer $customerModel;
    private Payment $paymentModel;

    public function __construct()
    {
        parent::__construct();
        $this->creditModel = new Credit();
        $this->customerModel = new Customer();
        $this->paymentModel = new Payment();
    }

    /**
     * Page des rapports
     */
    public function index(): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        $stats = $this->creditModel->getShopStats($shop->id);
        $monthlyStats = $this->creditModel->monthlyStats($shop->id, 12);
        $topDebtors = $this->customerModel->topDebtors($shop->id, 10);
        $customerCount = $this->customerModel->countByShop($shop->id);

        $this->view('reports.index', [
            'title'         => 'Rapports & Statistiques',
            'stats'         => $stats,
            'monthlyStats'  => $monthlyStats,
            'topDebtors'    => $topDebtors,
            'customerCount' => $customerCount,
            'shop'          => $shop,
        ]);
    }
}
