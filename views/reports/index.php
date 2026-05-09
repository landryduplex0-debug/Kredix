<?php /** @var object $stats */ ?>

<!-- Stats Summary -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px;">
    <div class="stat-card primary" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($stats->total_credits ?? 0) ?></div>
        <div class="stat-label">Total Crédits</div>
    </div>
    <div class="stat-card success" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($stats->total_paid ?? 0) ?></div>
        <div class="stat-label">Total Récupéré</div>
    </div>
    <div class="stat-card danger" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($stats->total_balance ?? 0) ?></div>
        <div class="stat-label">Impayés</div>
    </div>
    <div class="stat-card info" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= $customerCount ?? 0 ?></div>
        <div class="stat-label">Clients</div>
    </div>
</div>

<!-- Status Breakdown -->
<div class="card-k" style="margin-bottom:20px;">
    <div class="card-header-k"><h3><i class="bi bi-pie-chart"></i> Répartition des Crédits</h3></div>
    <div class="card-body-k">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;">
            <div style="text-align:center;padding:12px;border-radius:10px;background:rgba(253,203,110,0.15);">
                <div style="font-size:24px;font-weight:800;color:#b8860b;"><?= $stats->pending_count ?? 0 ?></div>
                <div style="font-size:11px;color:var(--text-muted);">En attente</div>
            </div>
            <div style="text-align:center;padding:12px;border-radius:10px;background:rgba(9,132,227,0.1);">
                <div style="font-size:24px;font-weight:800;color:var(--info);"><?= $stats->partial_count ?? 0 ?></div>
                <div style="font-size:11px;color:var(--text-muted);">Partiels</div>
            </div>
            <div style="text-align:center;padding:12px;border-radius:10px;background:rgba(0,184,148,0.1);">
                <div style="font-size:24px;font-weight:800;color:var(--success);"><?= $stats->paid_count ?? 0 ?></div>
                <div style="font-size:11px;color:var(--text-muted);">Soldés</div>
            </div>
            <div style="text-align:center;padding:12px;border-radius:10px;background:rgba(231,76,60,0.1);">
                <div style="font-size:24px;font-weight:800;color:var(--danger);"><?= $stats->overdue_count ?? 0 ?></div>
                <div style="font-size:11px;color:var(--text-muted);">En retard</div>
            </div>
        </div>
    </div>
</div>

<!-- Recovery Rate -->
<?php 
$rate = ($stats->total_credits ?? 0) > 0 ? round((($stats->total_paid ?? 0) / $stats->total_credits) * 100) : 0;
$rateColor = $rate >= 70 ? 'var(--success)' : ($rate >= 40 ? 'var(--warning)' : 'var(--danger)');
?>
<div class="card-k" style="margin-bottom:20px;">
    <div class="card-header-k"><h3><i class="bi bi-graph-up"></i> Taux de Recouvrement</h3></div>
    <div class="card-body-k" style="text-align:center;padding:24px;">
        <div style="font-size:48px;font-weight:800;color:<?= $rateColor ?>;"><?= $rate ?>%</div>
        <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">de vos crédits sont récupérés</div>
        <div class="progress-k" style="height:10px;margin-top:16px;max-width:300px;margin-left:auto;margin-right:auto;">
            <div class="progress-bar-k" style="width:<?= $rate ?>%;background:<?= $rateColor ?>;"></div>
        </div>
    </div>
</div>

<!-- Top Debtors -->
<div class="card-k">
    <div class="card-header-k"><h3><i class="bi bi-sort-down"></i> Top 10 Débiteurs</h3></div>
    <?php if (empty($topDebtors)): ?>
        <div class="card-body-k"><div class="empty-state" style="padding:24px;"><p>Aucun débiteur 🎉</p></div></div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table-k">
                <thead><tr><th>#</th><th>Client</th><th>Téléphone</th><th>Dette</th></tr></thead>
                <tbody>
                    <?php foreach ($topDebtors as $i => $d): ?>
                    <tr>
                        <td style="font-weight:700;color:var(--danger);"><?= $i + 1 ?></td>
                        <td style="font-weight:600;"><?= e($d->full_name) ?></td>
                        <td><?= formatPhone($d->phone ?? '') ?></td>
                        <td style="font-weight:700;color:var(--danger);"><?= formatMoney($d->total_debt) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
