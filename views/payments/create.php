<?php /** @var object $credit */ ?>
<div style="max-width:500px;">
    <a href="<?= url('credits/show/' . $credit->id) ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
        <i class="bi bi-arrow-left"></i> Retour au crédit
    </a>

    <!-- Credit Summary -->
    <div class="card-k" style="margin-bottom:16px;">
        <div class="card-body-k" style="padding:14px 20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-weight:700;font-size:16px;"><?= e($credit->customer_name) ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?= e($credit->description ?? 'Sans description') ?></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px;color:var(--text-muted);">Reste à payer</div>
                    <div style="font-size:20px;font-weight:800;color:var(--danger);"><?= formatMoney($credit->balance) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-wallet2"></i> Enregistrer un Paiement</h3>
        </div>
        <div class="card-body-k">
            <form method="POST" action="<?= url('payments/store') ?>">
                <?= CSRF::field() ?>
                <input type="hidden" name="credit_id" value="<?= $credit->id ?>">

                <div class="form-group-k">
                    <label class="form-label-k" for="amount">Montant reçu (FCFA) *</label>
                    <input type="number" id="amount" name="amount" class="form-control-k" 
                           placeholder="Ex: 5000" min="1" max="<?= $credit->balance ?>" step="1" required
                           style="font-size:18px;font-weight:700;text-align:center;">
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;text-align:center;">
                        Maximum : <?= formatMoney($credit->balance) ?>
                    </div>
                </div>

                <!-- Quick amounts -->
                <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
                    <?php 
                    $quickAmounts = [1000, 2000, 5000, (int)$credit->balance];
                    $quickAmounts = array_unique(array_filter($quickAmounts, fn($a) => $a <= $credit->balance && $a > 0));
                    foreach ($quickAmounts as $qa): ?>
                        <button type="button" class="btn-k btn-outline-k btn-sm-k" onclick="document.getElementById('amount').value=<?= $qa ?>">
                            <?= formatMoney($qa) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="payment_method">Mode de paiement</label>
                    <select id="payment_method" name="payment_method" class="form-control-k">
                        <option value="cash">💵 Espèces</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="bank_transfer">🏦 Virement bancaire</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <div class="form-group-k">
                    <label class="form-label-k" for="notes">Note (optionnel)</label>
                    <input type="text" id="notes" name="notes" class="form-control-k" placeholder="Ex: Versement partiel">
                </div>

                <button type="submit" class="btn-k btn-success-k" style="width:100%;justify-content:center;font-size:15px;padding:14px;">
                    <i class="bi bi-check-lg"></i> Confirmer le paiement
                </button>
            </form>
        </div>
    </div>
</div>
