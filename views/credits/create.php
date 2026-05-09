<?php /** @var array $customers */ ?>
<div style="max-width:500px;">
    <a href="<?= url('credits') ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
        <i class="bi bi-arrow-left"></i> Retour aux crédits
    </a>

    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-plus-circle"></i> Nouveau Crédit</h3>
        </div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('credits/store') ?>">
                <?= CSRF::field() ?>

                <div class="form-group-k">
                    <label class="form-label-k" for="customer_id">Client *</label>
                    <select id="customer_id" name="customer_id" class="form-control-k" required>
                        <option value="">-- Sélectionner un client --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c->id ?>" <?= ($selectedCustomer && $selectedCustomer->id == $c->id) ? 'selected' : '' ?>>
                                <?= e($c->full_name) ?> <?= $c->phone ? '(' . formatPhone($c->phone) . ')' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="amount">Montant (FCFA) *</label>
                    <input type="number" id="amount" name="amount" class="form-control-k" 
                           placeholder="Ex: 15000" value="<?= old('amount') ?>" min="1" step="1" required>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="description">Description</label>
                    <textarea id="description" name="description" class="form-control-k" rows="3" 
                              placeholder="Ex: Riz 25kg + Huile + Sucre"><?= old('description') ?></textarea>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="due_date">Date limite (optionnel)</label>
                    <input type="date" id="due_date" name="due_date" class="form-control-k" value="<?= old('due_date') ?>">
                </div>

                <button type="submit" class="btn-k btn-primary-k" style="width:100%;justify-content:center;">
                    <i class="bi bi-check-lg"></i> Enregistrer le crédit
                </button>
            </form>
        </div>
    </div>
</div>
