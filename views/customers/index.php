<?php /** @var array $customers */ ?>

<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <span style="color:var(--text-muted);font-size:13px;"><?= $customers['total'] ?> client(s)</span>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <form action="<?= url('customers/search') ?>" method="GET" style="display:flex;gap:8px;">
            <input type="text" name="q" class="form-control-k" placeholder="🔍 Chercher un client..." 
                   value="<?= e($searchQuery ?? '') ?>" style="width:220px;padding:8px 14px;font-size:13px;">
        </form>
        <a href="<?= url('customers/create') ?>" class="btn-k btn-primary-k btn-sm-k">
            <i class="bi bi-plus"></i> Nouveau
        </a>
    </div>
</div>

<div class="card-k">
    <?php if (empty($customers['items'])): ?>
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h4>Aucun client</h4>
            <p>Ajoutez votre premier client pour commencer à suivre les crédits.</p>
            <a href="<?= url('customers/create') ?>" class="btn-k btn-primary-k">
                <i class="bi bi-person-plus"></i> Ajouter un client
            </a>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table-k">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th class="hide-mobile">Téléphone</th>
                        <th>Crédits</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers['items'] as $customer): 
                        $balance = ($customer->total_credits ?? 0) - ($customer->total_paid ?? 0);
                    ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="item-avatar" style="background:rgba(27,107,58,0.12);color:var(--secondary);width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">
                                    <?= initials($customer->full_name) ?>
                                </div>
                                <div>
                                    <div style="font-weight:600;"><?= e($customer->full_name) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile"><?= formatPhone($customer->phone) ?></td>
                        <td><?= formatMoney($customer->total_credits ?? 0) ?></td>
                        <td style="color:var(--success);"><?= formatMoney($customer->total_paid ?? 0) ?></td>
                        <td style="font-weight:700;color:<?= $balance > 0 ? 'var(--danger)' : 'var(--success)' ?>;">
                            <?= formatMoney($balance) ?>
                        </td>
                        <td>
                            <a href="<?= url('customers/show/' . $customer->id) ?>" class="btn-k btn-outline-k btn-sm-k">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($customers['total_pages'] > 1): ?>
            <div style="padding:16px 20px;">
                <div class="pagination-k">
                    <?php if ($customers['has_prev']): ?>
                        <a href="<?= url('customers?page=' . ($customers['page'] - 1)) ?>">←</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $customers['total_pages']; $i++): ?>
                        <?php if ($i === $customers['page']): ?>
                            <span class="active-page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= url('customers?page=' . $i) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($customers['has_next']): ?>
                        <a href="<?= url('customers?page=' . ($customers['page'] + 1)) ?>">→</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
