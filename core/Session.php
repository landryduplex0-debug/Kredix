<?php
/**
 * Classe Session - Gestion des sessions utilisateur
 */
class Session
{
    /**
     * Connecter un utilisateur
     */
    public static function login(object $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_phone'] = $user->phone;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }

    /**
     * Déconnecter l'utilisateur
     */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Obtenir l'ID de l'utilisateur connecté
     */
    public static function userId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Obtenir l'utilisateur connecté complet depuis la BD
     */
    public static function getUser(): ?object
    {
        if (!self::isLoggedIn()) return null;
        
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM users WHERE id = ?", [self::userId()]);
    }

    /**
     * Stocker une valeur en session
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Récupérer une valeur de session
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Supprimer une valeur de session
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
