<?php /** @var object $customer */ ?>
<div style="max-width:500px;">
    <a href="<?= url('customers/show/' . $customer->id) ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>

    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-pencil"></i> Modifier <?= e($customer->full_name) ?></h3>
        </div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('customers/update/' . $customer->id) ?>">
                <?= CSRF::field() ?>
                <div class="form-group-k">
                    <label class="form-label-k" for="full_name">Nom complet *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control-k" value="<?= e($customer->full_name) ?>" required>
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-control-k" value="<?= e($customer->phone ?? '') ?>">
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="address">Adresse</label>
                    <input type="text" id="address" name="address" class="form-control-k" value="<?= e($customer->address ?? '') ?>">
                </div>
                <div class="form-group-k">
                    <label class="form-label-k" for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control-k" rows="3"><?= e($customer->notes ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-k btn-primary-k" style="width:100%;justify-content:center;">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
            </form>
        </div>
    </div>

    <div style="margin-top:16px;">
        <form method="POST" action="<?= url('customers/delete/' . $customer->id) ?>" onsubmit="return confirm('Supprimer ce client ? Cette action est irréversible.');">
            <?= CSRF::field() ?>
            <button type="submit" class="btn-k btn-danger-k btn-sm-k" style="width:100%;justify-content:center;">
                <i class="bi bi-trash"></i> Supprimer ce client
            </button>
        </form>
    </div>
</div>
