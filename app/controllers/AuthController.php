<?php
/**
 * Contrôleur d'authentification
 */
class AuthController extends Controller
{
    private User $userModel;
    private Shop $shopModel;
    private Subscription $subscriptionModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->shopModel = new Shop();
        $this->subscriptionModel = new Subscription();
    }

    /**
     * Afficher la page de connexion
     */
    public function showLogin(): void
    {
        $this->requireGuest();
        $this->view('auth.login', [
            'title' => 'Connexion'
        ], 'auth');
    }

    /**
     * Traiter la connexion
     */
    public function login(): void
    {
        $this->requireGuest();
        
        if (!$this->validateCSRF()) {
            $this->redirect('login');
            return;
        }

        $login = $this->input('login');
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('login');
            return;
        }

        $user = $this->userModel->authenticate($login, $password);

        if ($user) {
            Session::login($user);
            flash('success', 'Bienvenue, ' . $user->full_name . ' ! 👋');
            $this->redirect('dashboard');
        } else {
            flash('error', 'Identifiants incorrects. Vérifiez votre téléphone/email et mot de passe.');
            storeOldInput();
            $this->redirect('login');
        }
    }

    /**
     * Afficher la page d'inscription
     */
    public function showRegister(): void
    {
        $this->requireGuest();
        $this->view('auth.register', [
            'title' => 'Inscription'
        ], 'auth');
    }

    /**
     * Traiter l'inscription
     */
    public function register(): void
    {
        $this->requireGuest();
        
        if (!$this->validateCSRF()) {
            $this->redirect('register');
            return;
        }

        $data = [
            'full_name' => $this->input('full_name'),
            'phone'     => $this->input('phone'),
            'email'     => $this->input('email'),
            'shop_name' => $this->input('shop_name'),
            'password'  => $_POST['password'] ?? '',
        ];

        // Validation
        $validator = new Validator($_POST);
        $validator->required('full_name', 'Nom complet')
                  ->required('phone', 'Téléphone')
                  ->phone('phone', 'Téléphone')
                  ->required('shop_name', 'Nom de la boutique')
                  ->required('password', 'Mot de passe')
                  ->minLength('password', 6, 'Mot de passe')
                  ->matches('password', 'password_confirm', 'Confirmation');

        if ($validator->fails()) {
            flash('error', $validator->firstError());
            storeOldInput();
            $this->redirect('register');
            return;
        }

        // Vérifier l'unicité du téléphone
        if ($this->userModel->phoneExists($data['phone'])) {
            flash('error', 'Ce numéro de téléphone est déjà utilisé.');
            storeOldInput();
            $this->redirect('register');
            return;
        }

        // Vérifier l'unicité de l'email
        if (!empty($data['email']) && $this->userModel->emailExists($data['email'])) {
            flash('error', 'Cette adresse email est déjà utilisée.');
            storeOldInput();
            $this->redirect('register');
            return;
        }

        // Créer l'utilisateur
        $userId = $this->userModel->register($data);

        // Créer la boutique
        $this->shopModel->createDefault($userId, $data['shop_name'], $data['phone']);

        // Créer l'abonnement gratuit
        $this->subscriptionModel->createFree($userId);

        // Connecter automatiquement
        $user = $this->userModel->find($userId);
        Session::login($user);

        clearOldInput();
        flash('success', 'Bienvenue sur Kredix ! 🎉 Votre boutique est prête.');
        $this->redirect('dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        Session::logout();
        flash('success', 'Vous avez été déconnecté avec succès.');
        $this->redirect('login');
    }

    /**
     * Afficher la page mot de passe oublié
     */
    public function showForgotPassword(): void
    {
        $this->requireGuest();
        $this->view('auth.forgot-password', [
            'title' => 'Mot de passe oublié'
        ], 'auth');
    }

    /**
     * Traiter la demande de réinitialisation
     */
    public function forgotPassword(): void
    {
        flash('info', 'Si un compte existe avec ces informations, vous recevrez un SMS de réinitialisation.');
        $this->redirect('login');
    }
}
