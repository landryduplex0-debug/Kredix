<?php
/**
 * Fonctions utilitaires globales
 */

/**
 * Générer une URL complète
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * URL vers un asset public
 */
function asset(string $path): string
{
    return BASE_URL . '/public/' . ltrim($path, '/');
}

/**
 * Échapper une valeur pour l'affichage HTML
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Vérifier si la route courante correspond
 */
function isRoute(string $route): bool
{
    $currentUrl = $_GET['url'] ?? '';
    $currentUrl = rtrim($currentUrl, '/');
    return $currentUrl === $route || strpos($currentUrl, $route) === 0;
}

/**
 * Classe CSS active pour la navigation
 */
function activeClass(string $route): string
{
    return isRoute($route) ? 'active' : '';
}

/**
 * Obtenir la valeur ancienne d'un champ (pour repopuler les formulaires)
 */
function old(string $key, string $default = ''): string
{
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Stocker les anciennes valeurs de formulaire
 */
function storeOldInput(): void
{
    $_SESSION['old_input'] = $_POST;
}

/**
 * Effacer les anciennes valeurs
 */
function clearOldInput(): void
{
    unset($_SESSION['old_input']);
}

/**
 * Générer un identifiant unique
 */
function generateRef(string $prefix = 'KRX'): string
{
    return $prefix . '-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(100, 999);
}

/**
 * Tronquer un texte
 */
function truncate(string $text, int $length = 50): string
{
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Obtenir les initiales d'un nom
 */
function initials(string $name): string
{
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $initials;
}

/**
 * Temps écoulé en français
 */
function timeAgo(string $datetime): string
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return "il y a " . $diff->y . " an" . ($diff->y > 1 ? "s" : "");
    if ($diff->m > 0) return "il y a " . $diff->m . " mois";
    if ($diff->d > 0) return "il y a " . $diff->d . " jour" . ($diff->d > 1 ? "s" : "");
    if ($diff->h > 0) return "il y a " . $diff->h . "h";
    if ($diff->i > 0) return "il y a " . $diff->i . " min";
    return "à l'instant";
}
