<?php
/**
 * Classe Controller de base
 */
class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Rendre une vue avec un layout
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'app'): void
    {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Capturer le contenu de la vue
        ob_start();
        $viewFile = BASE_PATH . '/views/' . str_replace('.', '/', $viewPath) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("Vue non trouvée : {$viewPath}");
        }
        
        require $viewFile;
        $content = ob_get_clean();

        // Charger le layout
        $layoutFile = BASE_PATH . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Vérifier que l'utilisateur est connecté
     */
    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            flash('error', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect('login');
        }
    }

    /**
     * Vérifier que l'utilisateur est un invité (non connecté)
     */
    protected function requireGuest(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    protected function user(): ?object
    {
        return Session::getUser();
    }

    /**
     * Obtenir la boutique de l'utilisateur connecté
     */
    protected function shop(): ?object
    {
        $user = $this->user();
        if (!$user) return null;

        return $this->db->fetch(
            "SELECT * FROM shops WHERE user_id = ? AND is_active = 1 LIMIT 1",
            [$user->id]
        );
    }

    /**
     * Valider le token CSRF
     */
    protected function validateCSRF(): bool
    {
        if (!CSRF::validate()) {
            flash('error', 'Token de sécurité invalide. Veuillez réessayer.');
            return false;
        }
        return true;
    }

    /**
     * Obtenir une donnée POST nettoyée
     */
    protected function input(string $key, $default = null)
    {
        if (isset($_POST[$key])) {
            return trim(htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8'));
        }
        return $default;
    }

    /**
     * Obtenir une donnée GET nettoyée
     */
    protected function query(string $key, $default = null)
    {
        if (isset($_GET[$key])) {
            return trim(htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8'));
        }
        return $default;
    }
}
