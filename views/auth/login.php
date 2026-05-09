<div class="auth-card">
    <div class="auth-logo">
        <div class="logo-box">K</div>
        <h1>Kredix</h1>
        <p>Connectez-vous à votre compte</p>
    </div>

    <form class="auth-form" method="POST" action="<?= url('login') ?>">
        <?= CSRF::field() ?>

        <div class="form-group-k">
            <label class="form-label-k" for="login">Téléphone ou Email</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-phone"></i>
                <input type="text" id="login" name="login" class="form-control-k" 
                       placeholder="699 000 001 ou email@exemple.com" 
                       value="<?= old('login') ?>" required autofocus>
            </div>
        </div>

        <div class="form-group-k">
            <label class="form-label-k" for="password">Mot de passe</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" class="form-control-k" 
                       placeholder="Votre mot de passe" required>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
            <a href="<?= url('forgot-password') ?>" style="font-size:12px;color:var(--primary-dark);text-decoration:none;">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-box-arrow-in-right"></i> Se connecter
        </button>
    </form>

    <div class="auth-footer">
        Pas encore de compte ? <a href="<?= url('register') ?>">Créer un compte gratuit</a>
    </div>
</div>
