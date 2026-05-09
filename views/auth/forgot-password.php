<div class="auth-card">
    <div class="auth-logo">
        <div class="logo-box">K</div>
        <h1>Mot de passe oublié</h1>
        <p>Entrez votre numéro de téléphone pour réinitialiser</p>
    </div>

    <?php include BASE_PATH . '/views/partials/alerts.php'; ?>

    <form class="auth-form" method="POST" action="<?= url('forgot-password') ?>">
        <?= CSRF::field() ?>

        <div class="form-group-k">
            <label class="form-label-k" for="phone">Numéro de téléphone</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-phone"></i>
                <input type="tel" id="phone" name="phone" class="form-control-k" 
                       placeholder="699 000 001" required autofocus>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-send"></i> Envoyer le lien de réinitialisation
        </button>
    </form>

    <div class="auth-footer">
        <a href="<?= url('login') ?>">← Retour à la connexion</a>
    </div>
</div>
