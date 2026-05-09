<?php
$user = Session::getUser();
$currentShop = null;
if ($user) {
    $currentShop = Database::getInstance()->fetch("SELECT * FROM shops WHERE user_id = ? AND is_active = 1 LIMIT 1", [$user->id]);
}
$plan = 'free';
if ($user) {
    $subModel = new Subscription();
    $plan = $subModel->getCurrentPlan($user->id);
}
$notifModel = new Notification();
$notifCount = $currentShop ? $notifModel->countUnread($currentShop->id) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Kredix — Gérez vos crédits boutique simplement.">
    <title><?= e($title ?? 'Dashboard') ?> — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon">K</div>
        <div>
            <h2>Kredix</h2>
            <small><?= APP_TAGLINE ?></small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Principal</div>
        <a href="<?= url('dashboard') ?>" class="nav-link <?= activeClass('dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
        </a>
        <a href="<?= url('customers') ?>" class="nav-link <?= activeClass('customers') ?>">
            <i class="bi bi-people-fill"></i> Clients
        </a>
        <a href="<?= url('credits') ?>" class="nav-link <?= activeClass('credits') ?>">
            <i class="bi bi-cash-stack"></i> Crédits
        </a>
        <a href="<?= url('payments') ?>" class="nav-link <?= activeClass('payments') ?>">
            <i class="bi bi-wallet2"></i> Paiements
        </a>

        <div class="nav-label">Analyse</div>
        <a href="<?= url('reports') ?>" class="nav-link <?= activeClass('reports') ?>">
            <i class="bi bi-bar-chart-fill"></i> Rapports
        </a>

        <div class="nav-label">Compte</div>
        <a href="<?= url('subscription') ?>" class="nav-link <?= activeClass('subscription') ?>">
            <i class="bi bi-gem"></i> Abonnement
            <?php if ($plan === 'free'): ?>
                <span style="margin-left:auto;font-size:10px;background:var(--primary);padding:2px 8px;border-radius:10px;color:#fff;">Gratuit</span>
            <?php endif; ?>
        </a>
        <a href="<?= url('settings') ?>" class="nav-link <?= activeClass('settings') ?>">
            <i class="bi bi-gear-fill"></i> Paramètres
        </a>
        <a href="<?= url('logout') ?>" class="nav-link" style="color:rgba(231,76,60,0.7);">
            <i class="bi bi-box-arrow-left"></i> Déconnexion
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar"><?= $user ? initials($user->full_name) : '?' ?></div>
            <div class="user-info">
                <div class="name"><?= $user ? e($user->full_name) : '' ?></div>
                <div class="plan"><?= $currentShop ? e($currentShop->name) : '' ?></div>
            </div>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="page-title"><?= e($title ?? 'Dashboard') ?></div>
        </div>
        <div class="topbar-actions">
            <a href="<?= url('credits/create') ?>" class="btn-icon hide-mobile" title="Nouveau crédit">
                <i class="bi bi-plus-lg"></i>
            </a>
            <div class="btn-icon" style="cursor:default;">
                <i class="bi bi-bell"></i>
                <?php if ($notifCount > 0): ?>
                    <span class="badge-dot"></span>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="page-content animate-fade">
        <?php include BASE_PATH . '/views/partials/alerts.php'; ?>
        <?= $content ?>
    </div>
</main>

<!-- FAB (mobile) -->
<a href="<?= url('credits/create') ?>" class="fab" title="Nouveau crédit">
    <i class="bi bi-plus-lg"></i>
</a>

<!-- Bottom Navigation (mobile) -->
<nav class="bottom-nav">
    <a href="<?= url('dashboard') ?>" class="<?= activeClass('dashboard') ?>">
        <i class="bi bi-grid-1x2-fill"></i> Accueil
    </a>
    <a href="<?= url('customers') ?>" class="<?= activeClass('customers') ?>">
        <i class="bi bi-people-fill"></i> Clients
    </a>
    <a href="<?= url('credits') ?>" class="<?= activeClass('credits') ?>">
        <i class="bi bi-cash-stack"></i> Crédits
    </a>
    <a href="<?= url('payments') ?>" class="<?= activeClass('payments') ?>">
        <i class="bi bi-wallet2"></i> Paiements
    </a>
    <a href="<?= url('settings') ?>" class="<?= activeClass('settings') ?>">
        <i class="bi bi-gear-fill"></i> Plus
    </a>
</nav>

<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
