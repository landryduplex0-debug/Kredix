<div style="max-width:500px;">
    <a href="<?= url('customers') ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
        <i class="bi bi-arrow-left"></i> Retour aux clients
    </a>

    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-person-plus"></i> Nouveau Client</h3>
        </div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('customers/store') ?>">
                <?= CSRF::field() ?>

                <div class="form-group-k">
                    <label class="form-label-k" for="full_name">Nom complet *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control-k" 
                           placeholder="Ex: Jean Kamga" value="<?= old('full_name') ?>" required>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-control-k" 
                           placeholder="699 000 001" value="<?= old('phone') ?>">
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="address">Adresse / Quartier</label>
                    <input type="text" id="address" name="address" class="form-control-k" 
                           placeholder="Ex: Akwa, Douala" value="<?= old('address') ?>">
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control-k" rows="3" 
                              placeholder="Notes sur ce client..."><?= old('notes') ?></textarea>
                </div>

                <button type="submit" class="btn-k btn-primary-k" style="width:100%;justify-content:center;">
                    <i class="bi bi-check-lg"></i> Ajouter le client
                </button>
            </form>
        </div>
    </div>
</div>
