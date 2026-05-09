<?php /** @var array $payments */ ?>

<div class="card-k">
    <div class="card-header-k">
        <h3><i class="bi bi-wallet2"></i> Tous les Paiements</h3>
        <span style="font-size:12px;color:var(--text-muted);"><?= $payments['total'] ?> paiement(s)</span>
    </div>
    <?php if (empty($payments['items'])): ?>
        <div class="empty-state">
            <div class="empty-icon">💰</div>
            <h4>Aucun paiement</h4>
            <p>Les paiements apparaîtront ici quand vous enregistrerez des remboursements.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table-k">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th class="hide-mobile">Crédit</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments['items'] as $p): ?>
                    <tr>
                        <td style="font-weight:600;"><?= e($p->customer_name) ?></td>
                        <td class="hide-mobile"><?= e(truncate($p->credit_description ?? '-', 25)) ?></td>
                        <td style="font-weight:700;color:var(--success);">+<?= formatMoney($p->amount) ?></td>
                        <td><?= paymentMethodBadge($p->payment_method) ?></td>
                        <td style="font-size:12px;color:var(--text-muted);"><?= timeAgo($p->paid_at) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
