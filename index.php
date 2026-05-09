<?php
/**
 * KREDIX - SaaS de Gestion de Crédits
 * Front Controller - Point d'entrée unique
 */

// Démarrer la session
session_start();

// Define base path and dynamic URL
define('BASE_PATH', __DIR__);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $scriptDir === '/' ? '' : $scriptDir);

// Autoload des classes
spl_autoload_register(function ($class) {
    // Chercher dans core/
    $coreFile = BASE_PATH . '/core/' . $class . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }
    
    // Chercher dans app/controllers/
    $controllerFile = BASE_PATH . '/app/controllers/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
    
    // Chercher dans app/models/
    $modelFile = BASE_PATH . '/app/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    
    // Chercher dans app/middlewares/
    $middlewareFile = BASE_PATH . '/app/middlewares/' . $class . '.php';
    if (file_exists($middlewareFile)) {
        require_once $middlewareFile;
        return;
    }
});

// Charger les helpers
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/format.php';
require_once BASE_PATH . '/app/helpers/flash.php';

// Charger la configuration
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Initialiser la protection CSRF
CSRF::init();

// Charger et exécuter le routeur
require_once BASE_PATH . '/config/routes.php';
