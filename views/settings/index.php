<?php /** @var object $user */ /** @var object $shop */ ?>

<div style="max-width:600px;">
    <!-- Profile -->
    <div class="card-k" style="margin-bottom:20px;">
        <div class="card-header-k"><h3><i class="bi bi-person"></i> Mon Profil</h3></div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('settings/update-profile') ?>">
                <?= CSRF::field() ?>
                <div class="form-group-k">
                    <label class="form-label-k" for="full_name">Nom complet</label>
                    <input type="text" id="full_name" name="full_name" class="form-control-k" value="<?= e($user->full_name) ?>" required>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-control-k" value="<?= e($user->phone) ?>" required>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control-k" value="<?= e($user->email ?? '') ?>">
                </div>
                <button type="submit" class="btn-k btn-primary-k"><i class="bi bi-check"></i> Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Shop -->
    <div class="card-k" style="margin-bottom:20px;">
        <div class="card-header-k"><h3><i class="bi bi-shop"></i> Ma Boutique</h3></div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('settings/update-shop') ?>">
                <?= CSRF::field() ?>
                <div class="form-group-k">
                    <label class="form-label-k" for="name">Nom de la boutique</label>
                    <input type="text" id="name" name="name" class="form-control-k" value="<?= e($shop->name ?? '') ?>" required>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="type">Type</label>
                    <select id="type" name="type" class="form-control-k">
                        <option value="boutique" <?= ($shop->type ?? '') === 'boutique' ? 'selected' : '' ?>>Boutique</option>
                        <option value="alimentation" <?= ($shop->type ?? '') === 'alimentation' ? 'selected' : '' ?>>Alimentation</option>
                        <option value="quincaillerie" <?= ($shop->type ?? '') === 'quincaillerie' ? 'selected' : '' ?>>Quincaillerie</option>
                        <option value="pharmacie" <?= ($shop->type ?? '') === 'pharmacie' ? 'selected' : '' ?>>Pharmacie</option>
                        <option value="autre" <?= ($shop->type ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="shop_phone">Téléphone boutique</label>
                    <input type="tel" id="shop_phone" name="shop_phone" class="form-control-k" value="<?= e($shop->phone ?? '') ?>">
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="address">Adresse</label>
                    <input type="text" id="address" name="address" class="form-control-k" value="<?= e($shop->address ?? '') ?>">
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="city">Ville</label>
                    <input type="text" id="city" name="city" class="form-control-k" value="<?= e($shop->city ?? 'Douala') ?>">
                </div>
                <button type="submit" class="btn-k btn-primary-k"><i class="bi bi-check"></i> Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Password -->
    <div class="card-k">
        <div class="card-header-k"><h3><i class="bi bi-lock"></i> Changer le mot de passe</h3></div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('settings/update-password') ?>">
                <?= CSRF::field() ?>
                <div class="form-group-k">
                    <label class="form-label-k" for="current_password">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" class="form-control-k" required>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="new_password">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" class="form-control-k" required minlength="6">
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="confirm_password">Confirmer</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control-k" required>
                </div>
                <button type="submit" class="btn-k btn-primary-k"><i class="bi bi-lock"></i> Modifier</button>
            </form>
        </div>
    </div>
</div>
