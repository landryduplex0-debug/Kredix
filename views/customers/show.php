<?php /** @var object $customer */ /** @var array $credits */ 
$balance = ($customer->total_credits ?? 0) - ($customer->total_paid ?? 0);
$progress = paymentProgress($customer->total_credits ?? 0, $customer->total_paid ?? 0);
?>

<a href="<?= url('customers') ?>" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-bottom:16px;">
    <i class="bi bi-arrow-left"></i> Retour aux clients
</a>

<!-- Customer Header -->
<div class="card-k" style="margin-bottom:20px;">
    <div class="card-body-k">
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div class="item-avatar" style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--secondary),var(--secondary-light));color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;">
                <?= initials($customer->full_name) ?>
            </div>
            <div style="flex:1;min-width:200px;">
                <h2 style="font-size:20px;font-weight:700;margin-bottom:2px;"><?= e($customer->full_name) ?></h2>
                <div style="font-size:13px;color:var(--text-muted);">
                    <?php if ($customer->phone): ?><i class="bi bi-phone"></i> <?= formatPhone($customer->phone) ?> · <?php endif; ?>
                    <?php if ($customer->address): ?><i class="bi bi-geo-alt"></i> <?= e($customer->address) ?><?php endif; ?>
                </div>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="<?= url('credits/create/' . $customer->id) ?>" class="btn-k btn-primary-k btn-sm-k">
                    <i class="bi bi-plus"></i> Crédit
                </a>
                <a href="<?= url('customers/edit/' . $customer->id) ?>" class="btn-k btn-outline-k btn-sm-k">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:20px;">
    <div class="stat-card primary" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($customer->total_credits ?? 0) ?></div>
        <div class="stat-label">Total Crédits</div>
    </div>
    <div class="stat-card success" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($customer->total_paid ?? 0) ?></div>
        <div class="stat-label">Total Payé</div>
    </div>
    <div class="stat-card danger" style="text-align:center;">
        <div class="stat-value" style="font-size:18px;"><?= formatMoney($balance) ?></div>
        <div class="stat-label">Reste à Payer</div>
    </div>
</div>

<!-- Progress -->
<div class="card-k" style="margin-bottom:20px;">
    <div class="card-body-k" style="padding:14px 20px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
            <span>Progression de remboursement</span>
            <strong><?= $progress['percent'] ?>%</strong>
        </div>
        <div class="progress-k">
            <div class="progress-bar-k <?= $progress['class'] ?>" style="width:<?= $progress['percent'] ?>%;"></div>
        </div>
    </div>
</div>

<!-- Credits List -->
<div class="card-k">
    <div class="card-header-k">
        <h3><i class="bi bi-cash-stack"></i> Historique des Crédits (<?= count($credits) ?>)</h3>
    </div>
    <?php if (empty($credits)): ?>
        <div class="card-body-k">
            <div class="empty-state" style="padding:24px;">
                <div class="empty-icon">📝</div>
                <p>Aucun crédit pour ce client</p>
            </div>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table-k">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Montant</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($credits as $credit): ?>
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:13px;"><?= e($credit->description ?: 'Sans description') ?></div>
                            <div style="font-size:11px;color:var(--text-muted);"><?= formatDate($credit->created_at) ?></div>
                        </td>
                        <td style="font-weight:600;"><?= formatMoney($credit->amount) ?></td>
                        <td style="color:var(--success);"><?= formatMoney($credit->amount_paid) ?></td>
                        <td style="font-weight:700;color:var(--danger);"><?= formatMoney($credit->balance) ?></td>
                        <td><?= statusBadge($credit->status) ?></td>
                        <td>
                            <a href="<?= url('credits/show/' . $credit->id) ?>" class="btn-k btn-outline-k btn-sm-k">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
