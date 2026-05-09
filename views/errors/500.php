<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur serveur — Kredix</title>
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '/saas' ?>/public/css/app.css">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;">
    <div>
        <div style="font-size:80px;opacity:0.3;">⚠️</div>
        <h1 style="font-size:48px;font-weight:800;color:var(--danger);margin-bottom:8px;">500</h1>
        <p style="color:var(--text-muted);margin-bottom:20px;">Une erreur est survenue. Veuillez réessayer.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/saas' ?>/dashboard" class="btn-k btn-primary-k">
            <i class="bi bi-house"></i> Retour à l'accueil
        </a>
    </div>
</body>
</html>
