<?php /** @var object $credit */ /** @var array $payments */
$progress = paymentProgress($credit->amount, $credit->amount_paid);
?>

<a href="<?= url('credits') ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
    <i class="bi bi-arrow-left"></i> Retour aux crédits
</a>

<!-- Credit Header -->
<div class="card-k" style="margin-bottom:20px;">
    <div class="card-body-k">
        <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:12px;">
            <div>
                <h2 style="font-size:20px;font-weight:700;"><?= formatMoney($credit->amount) ?></h2>
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">
                    <a href="<?= url('customers/show/' . $credit->customer_id) ?>" style="color:var(--primary);text-decoration:none;font-weight:600;">
                        <i class="bi bi-person"></i> <?= e($credit->customer_name) ?>
                    </a>
                    · <?= formatDate($credit->created_at) ?>
                </div>
                <?php if ($credit->description): ?>
                    <div style="margin-top:8px;font-size:13px;color:var(--text);"><?= e($credit->description) ?></div>
                <?php endif; ?>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <?= statusBadge($credit->status) ?>
                <?php if ($credit->status !== 'paid' && $credit->status !== 'cancelled'): ?>
                    <a href="<?= url('payments/create/' . $credit->id) ?>" class="btn-k btn-success-k btn-sm-k">
                        <i class="bi bi-wallet2"></i> Payer
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Progress -->
        <div style="margin-top:16px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
                <span>Payé : <?= formatMoney($credit->amount_paid) ?></span>
                <span>Reste : <strong style="color:var(--danger);"><?= formatMoney($credit->balance) ?></strong></span>
            </div>
            <div class="progress-k" style="height:8px;">
                <div class="progress-bar-k <?= $progress['class'] ?>" style="width:<?= $progress['percent'] ?>%;"></div>
            </div>
            <div style="text-align:center;margin-top:4px;font-size:12px;font-weight:600;"><?= $progress['percent'] ?>%</div>
        </div>

        <?php if ($credit->due_date): ?>
            <div style="margin-top:12px;padding:8px 12px;border-radius:8px;background:<?= strtotime($credit->due_date) < time() ? 'rgba(231,76,60,0.1)' : 'rgba(253,203,110,0.2)' ?>;font-size:12px;">
                <i class="bi bi-calendar3"></i> Date limite : <strong><?= formatDate($credit->due_date) ?></strong>
                <?php if (strtotime($credit->due_date) < time() && $credit->status !== 'paid'): ?>
                    <span style="color:var(--danger);font-weight:700;"> — EN RETARD</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payment History -->
<div class="card-k">
    <div class="card-header-k">
        <h3><i class="bi bi-clock-history"></i> Historique des Paiements (<?= count($payments) ?>)</h3>
    </div>
    <?php if (empty($payments)): ?>
        <div class="card-body-k">
            <div class="empty-state" style="padding:24px;">
                <div class="empty-icon">💰</div>
                <p>Aucun paiement reçu pour ce crédit</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card-body-k" style="padding:8px 16px;">
            <?php foreach ($payments as $p): ?>
                <div class="list-item">
                    <div class="item-avatar" style="background:rgba(0,184,148,0.12);color:var(--success);">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-name"><?= paymentMethodBadge($p->payment_method ?? 'cash') ?></div>
                        <div class="item-sub"><?= formatDateTime($p->paid_at) ?> <?= $p->notes ? '· ' . e($p->notes) : '' ?></div>
                    </div>
                    <div class="item-value text-success">+<?= formatMoney($p->amount) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($credit->status !== 'paid' && $credit->status !== 'cancelled'): ?>
<div style="margin-top:16px;">
    <form method="POST" action="<?= url('credits/cancel/' . $credit->id) ?>" onsubmit="return confirm('Annuler ce crédit ?');">
        <?= CSRF::field() ?>
        <button type="submit" class="btn-k btn-outline-k btn-sm-k" style="color:var(--danger);border-color:var(--danger);">
            <i class="bi bi-x-circle"></i> Annuler ce crédit
        </button>
    </form>
</div>
<?php endif; ?>
