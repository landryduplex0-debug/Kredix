<?php
/**
 * Contrôleur du tableau de bord
 */
class DashboardController extends Controller
{
    private Credit $creditModel;
    private Customer $customerModel;
    private Payment $paymentModel;
    private Notification $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->creditModel = new Credit();
        $this->customerModel = new Customer();
        $this->paymentModel = new Payment();
        $this->notificationModel = new Notification();
    }

    /**
     * Afficher le tableau de bord
     */
    public function index(): void
    {
        $this->requireAuth();
        $shop = $this->shop();

        if (!$shop) {
            flash('error', 'Aucune boutique trouvée. Veuillez configurer votre boutique.');
            $this->redirect('settings');
            return;
        }

        // Statistiques globales
        $stats = $this->creditModel->getShopStats($shop->id);
        
        // Nombre de clients
        $customerCount = $this->customerModel->countByShop($shop->id);
        
        // Paiements du jour
        $todayPayments = $this->paymentModel->todayTotal($shop->id);
        
        // Crédits récents
        $recentCredits = $this->creditModel->getRecent($shop->id, 5);
        
        // Paiements récents
        $recentPayments = $this->paymentModel->getRecent($shop->id, 5);
        
        // Top débiteurs
        $topDebtors = $this->customerModel->topDebtors($shop->id, 5);
        
        // Stats mensuelles pour graphique
        $monthlyStats = $this->creditModel->monthlyStats($shop->id, 6);
        
        // Notifications
        $notifications = $this->notificationModel->getUnread($shop->id, 5);
        $notificationCount = $this->notificationModel->countUnread($shop->id);

        $this->view('dashboard.index', [
            'title'             => 'Tableau de bord',
            'shop'              => $shop,
            'stats'             => $stats,
            'customerCount'     => $customerCount,
            'todayPayments'     => $todayPayments,
            'recentCredits'     => $recentCredits,
            'recentPayments'    => $recentPayments,
            'topDebtors'        => $topDebtors,
            'monthlyStats'      => $monthlyStats,
            'notifications'     => $notifications,
            'notificationCount' => $notificationCount,
        ]);
    }

    /**
     * API: Statistiques en JSON
     */
    public function apiStats(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        
        if (!$shop) {
            $this->json(['error' => 'Boutique non trouvée'], 404);
            return;
        }

        $stats = $this->creditModel->getShopStats($shop->id);
        $monthlyStats = $this->creditModel->monthlyStats($shop->id, 6);

        $this->json([
            'stats'        => $stats,
            'monthlyStats' => $monthlyStats
        ]);
    }
}
