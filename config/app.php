<?php
/**
 * Configuration générale de l'application
 */

// Informations de l'application
define('APP_NAME', 'Kredix');
define('APP_VERSION', '1.0.0');
define('APP_TAGLINE', 'Krédi numérique, zéro souci.');
define('APP_CURRENCY', 'FCFA');
define('APP_LOCALE', 'fr_FR');

// Mode debug (désactiver en production)
define('APP_DEBUG', true);

// Fuseau horaire
date_default_timezone_set('Africa/Douala');

// Gestion des erreurs
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Limites du plan gratuit
define('FREE_MAX_CUSTOMERS', 20);
define('FREE_MAX_CREDITS_PER_MONTH', 50);

// Limites du plan Starter
define('STARTER_MAX_CUSTOMERS', 100);
define('STARTER_MAX_CREDITS_PER_MONTH', 300);
define('STARTER_PRICE', 2000);

// Plan Premium
define('PREMIUM_PRICE', 5000);
