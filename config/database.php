<?php
/**
 * Configuration de la base de données
 */

// Si les variables d'environnement existent, on les utilise (utile pour Vercel)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'kredix');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
