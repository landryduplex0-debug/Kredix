<?php
/**
 * Classe CSRF - Protection contre les attaques CSRF
 */
class CSRF
{
    /**
     * Initialiser le token CSRF
     */
    public static function init(): void
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Obtenir le token CSRF
     */
    public static function token(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    /**
     * Générer le champ HTML caché
     */
    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::token() . '">';
    }

    /**
     * Valider le token CSRF
     */
    public static function validate(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Régénérer le token
     */
    public static function regenerate(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
