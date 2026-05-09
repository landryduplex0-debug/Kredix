<?php
/**
 * Contrôleur des paramètres
 */
class SettingsController extends Controller
{
    private User $userModel;
    private Shop $shopModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->shopModel = new Shop();
    }

    /**
     * Page des paramètres
     */
    public function index(): void
    {
        $this->requireAuth();
        $user = $this->user();
        $shop = $this->shop();
        $subscription = (new Subscription())->getActive($user->id);

        $this->view('settings.index', [
            'title'        => 'Paramètres',
            'user'         => $user,
            'shop'         => $shop,
            'subscription' => $subscription,
        ]);
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('settings');
            return;
        }

        $user = $this->user();

        $validator = new Validator($_POST);
        $validator->required('full_name', 'Nom complet')
                  ->required('phone', 'Téléphone')
                  ->phone('phone', 'Téléphone');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            $this->redirect('settings');
            return;
        }

        // Vérifier unicité du téléphone
        if ($this->userModel->phoneExists($this->input('phone'), $user->id)) {
            flash('error', 'Ce numéro est déjà utilisé par un autre compte.');
            $this->redirect('settings');
            return;
        }

        $this->userModel->update($user->id, [
            'full_name' => $this->input('full_name'),
            'phone'     => $this->input('phone'),
            'email'     => $this->input('email'),
        ]);

        flash('success', 'Profil mis à jour ! ✅');
        $this->redirect('settings');
    }

    /**
     * Mettre à jour la boutique
     */
    public function updateShop(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('settings');
            return;
        }

        $shop = $this->shop();

        $validator = new Validator($_POST);
        $validator->required('name', 'Nom de la boutique');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            $this->redirect('settings');
            return;
        }

        $this->shopModel->update($shop->id, [
            'name'    => $this->input('name'),
            'type'    => $this->input('type'),
            'phone'   => $this->input('shop_phone'),
            'address' => $this->input('address'),
            'city'    => $this->input('city'),
        ]);

        flash('success', 'Boutique mise à jour ! 🏪');
        $this->redirect('settings');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(): void
    {
        $this->requireAuth();
        if (!$this->validateCSRF()) {
            $this->redirect('settings');
            return;
        }

        $user = $this->user();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('settings');
            return;
        }

        if (!password_verify($currentPassword, $user->password_hash)) {
            flash('error', 'Le mot de passe actuel est incorrect.');
            $this->redirect('settings');
            return;
        }

        if (strlen($newPassword) < 6) {
            flash('error', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
            $this->redirect('settings');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            flash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('settings');
            return;
        }

        $this->userModel->updatePassword($user->id, $newPassword);
        flash('success', 'Mot de passe modifié ! 🔒');
        $this->redirect('settings');
    }
}
