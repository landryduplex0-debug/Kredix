<?php /** @var object $stats */ /** @var object $shop */ ?>

<!-- Greeting -->
<div style="margin-bottom:24px;">
    <h2 style="font-size:22px;font-weight:800;margin-bottom:4px;">
        Bonjour, <?= e(explode(' ', $user->full_name ?? '')[0]) ?> ! 👋
    </h2>
    <p style="color:var(--text-muted);font-size:13px;">
        Voici le résumé de <strong><?= e($shop->name) ?></strong> — <?= formatDate(date('Y-m-d')) ?>
    </p>
</div>

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px;">
    <div class="stat-card primary">
        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-value"><?= formatMoneyShort($stats->total_credits ?? 0) ?></div>
        <div class="stat-label">Total Crédits</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
        <div class="stat-value"><?= formatMoneyShort($stats->total_paid ?? 0) ?></div>
        <div class="stat-label">Total Payé</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="bi bi-exclamation-circle"></i></div>
        <div class="stat-value"><?= formatMoneyShort($stats->total_balance ?? 0) ?></div>
        <div class="stat-label">Reste à Payer</div>
    </div>
    <div class="stat-card info">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div class="stat-value"><?= $customerCount ?? 0 ?></div>
        <div class="stat-label">Clients</div>
    </div>
</div>

<!-- Today + Quick Actions -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <div class="stat-card success" style="text-align:center;">
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">💰 Paiements aujourd'hui</div>
        <div class="stat-value" style="color:var(--success);"><?= formatMoney($todayPayments ?? 0) ?></div>
    </div>
    <div class="stat-card primary" style="text-align:center;">
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">📊 Crédits en cours</div>
        <div class="stat-value" style="color:var(--primary);"><?= ($stats->pending_count ?? 0) + ($stats->partial_count ?? 0) ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <a href="<?= url('credits/create') ?>" class="btn-k btn-primary-k btn-lg-k" style="justify-content:center;">
        <i class="bi bi-plus-circle"></i> Nouveau Crédit
    </a>
    <a href="<?= url('customers/create') ?>" class="btn-k btn-success-k btn-lg-k" style="justify-content:center;">
        <i class="bi bi-person-plus"></i> Nouveau Client
    </a>
</div>

<!-- Recent Credits & Top Debtors -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <!-- Recent Credits -->
    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-clock-history"></i> Derniers Crédits</h3>
            <a href="<?= url('credits') ?>" style="font-size:12px;color:var(--primary);text-decoration:none;">Voir tout →</a>
        </div>
        <div class="card-body-k" style="padding:8px 16px;">
            <?php if (empty($recentCredits)): ?>
                <div class="empty-state" style="padding:24px;">
                    <div class="empty-icon">📝</div>
                    <p>Aucun crédit pour le moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentCredits as $credit): ?>
                    <a href="<?= url('credits/show/' . $credit->id) ?>" class="list-item">
                        <div class="item-avatar" style="background:rgba(232,168,56,0.12);color:var(--primary);">
                            <?= initials($credit->customer_name) ?>
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?= e($credit->customer_name) ?></div>
                            <div class="item-sub"><?= e(truncate($credit->description ?? 'Sans description', 30)) ?> · <?= timeAgo($credit->created_at) ?></div>
                        </div>
                        <div class="item-value <?= $credit->status === 'paid' ? 'text-success' : 'text-danger' ?>">
                            <?= formatMoney($credit->balance) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top Debtors -->
    <div class="card-k">
        <div class="card-header-k">
            <h3><i class="bi bi-sort-down"></i> Top Débiteurs</h3>
        </div>
        <div class="card-body-k" style="padding:8px 16px;">
            <?php if (empty($topDebtors)): ?>
                <div class="empty-state" style="padding:24px;">
                    <div class="empty-icon">🎉</div>
                    <p>Aucun débiteur !</p>
                </div>
            <?php else: ?>
                <?php foreach ($topDebtors as $i => $debtor): ?>
                    <a href="<?= url('customers/show/' . $debtor->id) ?>" class="list-item">
                        <div class="item-avatar" style="background:rgba(231,76,60,0.12);color:var(--danger);font-size:12px;">
                            #<?= $i + 1 ?>
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?= e($debtor->full_name) ?></div>
                            <div class="item-sub"><?= e($debtor->phone ?? '') ?></div>
                        </div>
                        <div class="item-value text-danger">
                            <?= formatMoney($debtor->total_debt) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="card-k">
    <div class="card-header-k">
        <h3><i class="bi bi-wallet2"></i> Derniers Paiements</h3>
        <a href="<?= url('payments') ?>" style="font-size:12px;color:var(--primary);text-decoration:none;">Voir tout →</a>
    </div>
    <div class="card-body-k" style="padding:8px 16px;">
        <?php if (empty($recentPayments)): ?>
            <div class="empty-state" style="padding:24px;">
                <div class="empty-icon">💰</div>
                <p>Aucun paiement reçu</p>
            </div>
        <?php else: ?>
            <?php foreach ($recentPayments as $payment): ?>
                <div class="list-item">
                    <div class="item-avatar" style="background:rgba(0,184,148,0.12);color:var(--success);">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-name"><?= e($payment->customer_name) ?></div>
                        <div class="item-sub"><?= timeAgo($payment->created_at) ?></div>
                    </div>
                    <div class="item-value text-success">+<?= formatMoney($payment->amount) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Monthly stats data for charts (available if needed)
const monthlyStatsData = <?= json_encode($monthlyStats ?? []) ?>;
</script>
