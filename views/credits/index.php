<?php /** @var array $credits */ ?>

<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="<?= url('credits') ?>" class="btn-k btn-sm-k <?= empty($currentStatus) ? 'btn-primary-k' : 'btn-outline-k' ?>">Tous</a>
        <a href="<?= url('credits?status=pending') ?>" class="btn-k btn-sm-k <?= $currentStatus === 'pending' ? 'btn-primary-k' : 'btn-outline-k' ?>">En attente</a>
        <a href="<?= url('credits?status=partial') ?>" class="btn-k btn-sm-k <?= $currentStatus === 'partial' ? 'btn-primary-k' : 'btn-outline-k' ?>">Partiel</a>
        <a href="<?= url('credits?status=paid') ?>" class="btn-k btn-sm-k <?= $currentStatus === 'paid' ? 'btn-primary-k' : 'btn-outline-k' ?>">Payé</a>
        <a href="<?= url('credits?status=overdue') ?>" class="btn-k btn-sm-k <?= $currentStatus === 'overdue' ? 'btn-primary-k' : 'btn-outline-k' ?>">En retard</a>
    </div>
    <a href="<?= url('credits/create') ?>" class="btn-k btn-primary-k btn-sm-k">
        <i class="bi bi-plus"></i> Nouveau Crédit
    </a>
</div>

<div class="card-k">
    <?php if (empty($credits['items'])): ?>
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h4>Aucun crédit</h4>
            <p>Enregistrez votre premier crédit pour commencer le suivi.</p>
            <a href="<?= url('credits/create') ?>" class="btn-k btn-primary-k">
                <i class="bi bi-plus-circle"></i> Nouveau Crédit
            </a>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table-k">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th class="hide-mobile">Description</th>
                        <th>Montant</th>
                        <th>Reste</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($credits['items'] as $credit): 
                        $prog = paymentProgress($credit->amount, $credit->amount_paid);
                    ?>
                    <tr>
                        <td>
                            <div style="font-weight:600;"><?= e($credit->customer_name) ?></div>
                            <div style="font-size:11px;color:var(--text-muted);"><?= timeAgo($credit->created_at) ?></div>
                        </td>
                        <td class="hide-mobile"><?= e(truncate($credit->description ?? '-', 30)) ?></td>
                        <td style="font-weight:600;"><?= formatMoney($credit->amount) ?></td>
                        <td style="font-weight:700;color:<?= $credit->balance > 0 ? 'var(--danger)' : 'var(--success)' ?>;">
                            <?= formatMoney($credit->balance) ?>
                        </td>
                        <td><?= statusBadge($credit->status) ?></td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <?php if ($credit->status !== 'paid' && $credit->status !== 'cancelled'): ?>
                                    <a href="<?= url('payments/create/' . $credit->id) ?>" class="btn-k btn-success-k btn-sm-k" title="Payer">
                                        <i class="bi bi-wallet2"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= url('credits/show/' . $credit->id) ?>" class="btn-k btn-outline-k btn-sm-k">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($credits['total_pages'] > 1): ?>
            <div style="padding:16px 20px;">
                <div class="pagination-k">
                    <?php if ($credits['has_prev']): ?>
                        <a href="<?= url('credits?page=' . ($credits['page'] - 1) . ($currentStatus ? '&status=' . $currentStatus : '')) ?>">←</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $credits['total_pages']; $i++): ?>
                        <?php if ($i === $credits['page']): ?>
                            <span class="active-page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= url('credits?page=' . $i . ($currentStatus ? '&status=' . $currentStatus : '')) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($credits['has_next']): ?>
                        <a href="<?= url('credits?page=' . ($credits['page'] + 1) . ($currentStatus ? '&status=' . $currentStatus : '')) ?>">→</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
