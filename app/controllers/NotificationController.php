<?php
/**
 * Contrôleur des notifications
 */
class NotificationController extends Controller
{
    private Notification $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->notificationModel = new Notification();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllRead(): void
    {
        $this->requireAuth();
        $shop = $this->shop();
        $this->notificationModel->markAllAsRead($shop->id);
        $this->json(['success' => true]);
    }
}
