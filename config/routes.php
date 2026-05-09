<?php
/**
 * Définition des routes de l'application
 */

$router = new Router();

// Routes d'authentification (accessibles sans connexion)
$router->get('', 'AuthController', 'showLogin');
$router->get('login', 'AuthController', 'showLogin');
$router->post('login', 'AuthController', 'login');
$router->get('register', 'AuthController', 'showRegister');
$router->post('register', 'AuthController', 'register');
$router->get('logout', 'AuthController', 'logout');
$router->get('forgot-password', 'AuthController', 'showForgotPassword');
$router->post('forgot-password', 'AuthController', 'forgotPassword');

// Routes protégées (nécessitent une connexion)
$router->get('dashboard', 'DashboardController', 'index');

// Clients
$router->get('customers', 'CustomerController', 'index');
$router->get('customers/create', 'CustomerController', 'create');
$router->post('customers/store', 'CustomerController', 'store');
$router->get('customers/show/{id}', 'CustomerController', 'show');
$router->get('customers/edit/{id}', 'CustomerController', 'edit');
$router->post('customers/update/{id}', 'CustomerController', 'update');
$router->post('customers/delete/{id}', 'CustomerController', 'delete');
$router->get('customers/search', 'CustomerController', 'search');

// Crédits
$router->get('credits', 'CreditController', 'index');
$router->get('credits/create', 'CreditController', 'create');
$router->get('credits/create/{customer_id}', 'CreditController', 'create');
$router->post('credits/store', 'CreditController', 'store');
$router->get('credits/show/{id}', 'CreditController', 'show');
$router->post('credits/cancel/{id}', 'CreditController', 'cancel');

// Paiements
$router->get('payments', 'PaymentController', 'index');
$router->get('payments/create/{credit_id}', 'PaymentController', 'create');
$router->post('payments/store', 'PaymentController', 'store');

// Rapports
$router->get('reports', 'ReportController', 'index');

// Paramètres
$router->get('settings', 'SettingsController', 'index');
$router->post('settings/update-profile', 'SettingsController', 'updateProfile');
$router->post('settings/update-shop', 'SettingsController', 'updateShop');
$router->post('settings/update-password', 'SettingsController', 'updatePassword');

// Abonnement
$router->get('subscription', 'SubscriptionController', 'index');
$router->get('subscription/pay', 'SubscriptionController', 'pay');
$router->get('subscription/upgrade', 'SubscriptionController', 'upgrade');
$router->post('subscription/upgrade', 'SubscriptionController', 'upgrade');
$router->post('api/webhook/monetbil', 'SubscriptionController', 'webhook');

// API pour recherche AJAX
$router->get('api/customers/search', 'CustomerController', 'apiSearch');
$router->get('api/dashboard/stats', 'DashboardController', 'apiStats');

// Exécuter le routeur
$router->dispatch();
