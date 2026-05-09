<div class="auth-card">
    <div class="auth-logo">
        <div class="logo-box">K</div>
        <h1>Créer un compte</h1>
        <p>Commencez à gérer vos crédits gratuitement</p>
    </div>

    <?php include BASE_PATH . '/views/partials/alerts.php'; ?>

    <form class="auth-form" method="POST" action="<?= url('register') ?>">
        <?= CSRF::field() ?>

        <div class="form-group-k">
            <label class="form-label-k" for="full_name">Votre nom complet *</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-person"></i>
                <input type="text" id="full_name" name="full_name" class="form-control-k" 
                       placeholder="Ex: Mama Rose" value="<?= old('full_name') ?>" required>
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="phone">Numéro de téléphone *</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-phone"></i>
                <input type="tel" id="phone" name="phone" class="form-control-k" 
                       placeholder="699 000 001" value="<?= old('phone') ?>" required>
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="email">Email (optionnel)</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-envelope"></i>
                <input type="email" id="email" name="email" class="form-control-k" 
                       placeholder="email@exemple.com" value="<?= old('email') ?>">
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="shop_name">Nom de votre boutique *</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-shop"></i>
                <input type="text" id="shop_name" name="shop_name" class="form-control-k" 
                       placeholder="Ex: Boutique Mama Rose" value="<?= old('shop_name') ?>" required>
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="password">Mot de passe * (6 caractères min.)</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" class="form-control-k" 
                       placeholder="Créez un mot de passe" required minlength="6">
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="password_confirm">Confirmer le mot de passe *</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="password_confirm" name="password_confirm" class="form-control-k" 
                       placeholder="Répétez le mot de passe" required>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-rocket-takeoff"></i> Créer mon compte gratuit
        </button>
    </form>

    <div class="auth-footer">
        Vous avez déjà un compte ? <a href="<?= url('login') ?>">Se connecter</a>
    </div>
</div>
