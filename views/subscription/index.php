<?php /** @var string $currentPlan */ ?>

<div style="max-width:700px;">
    <!-- Current Plan -->
    <div class="card-k" style="margin-bottom:24px;">
        <div class="card-body-k" style="text-align:center;padding:24px;">
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:4px;">Votre plan actuel</div>
            <div style="font-size:28px;font-weight:800;color:var(--primary);"><?= ucfirst($currentPlan) ?></div>
        </div>
    </div>

    <!-- Plans -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
        <!-- Free -->
        <div class="card-k" style="<?= $currentPlan === 'free' ? 'border:2px solid var(--primary);' : '' ?>">
            <div class="card-body-k" style="text-align:center;padding:24px;">
                <div style="font-size:24px;margin-bottom:8px;">🆓</div>
                <h3 style="font-size:18px;font-weight:800;margin-bottom:4px;">Gratuit</h3>
                <div style="font-size:24px;font-weight:800;margin-bottom:16px;">0 <span style="font-size:13px;font-weight:400;">FCFA/mois</span></div>
                <ul style="list-style:none;text-align:left;font-size:13px;line-height:2;">
                    <li>✅ <?= FREE_MAX_CUSTOMERS ?> clients max</li>
                    <li>✅ <?= FREE_MAX_CREDITS_PER_MONTH ?> crédits/mois</li>
                    <li>✅ Stats basiques</li>
                    <li>❌ Export PDF</li>
                    <li>❌ Rappels</li>
                </ul>
                <?php if ($currentPlan === 'free'): ?>
                    <div style="margin-top:16px;padding:8px;background:rgba(232,168,56,0.1);border-radius:8px;font-size:12px;color:var(--primary-dark);font-weight:600;">✓ Plan actuel</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Starter -->
        <div class="card-k" style="<?= $currentPlan === 'starter' ? 'border:2px solid var(--primary);' : '' ?>">
            <div class="card-body-k" style="text-align:center;padding:24px;">
                <div style="font-size:24px;margin-bottom:8px;">⭐</div>
                <h3 style="font-size:18px;font-weight:800;margin-bottom:4px;">Starter</h3>
                <div style="font-size:24px;font-weight:800;margin-bottom:16px;"><?= number_format(STARTER_PRICE, 0, ',', ' ') ?> <span style="font-size:13px;font-weight:400;">FCFA/mois</span></div>
                <ul style="list-style:none;text-align:left;font-size:13px;line-height:2;">
                    <li>✅ <?= STARTER_MAX_CUSTOMERS ?> clients max</li>
                    <li>✅ <?= STARTER_MAX_CREDITS_PER_MONTH ?> crédits/mois</li>
                    <li>✅ Stats avancées</li>
                    <li>✅ Export PDF</li>
                    <li>❌ Rappels WhatsApp</li>
                </ul>
                <?php if ($currentPlan === 'starter'): ?>
                    <div style="margin-top:16px;padding:8px;background:rgba(232,168,56,0.1);border-radius:8px;font-size:12px;color:var(--primary-dark);font-weight:600;">✓ Plan actuel</div>
                <?php elseif ($currentPlan === 'free'): ?>
                    <form action="<?= url('subscription/pay?plan=starter') ?>" method="get" data-monetbil="form" style="margin-top:16px;">
                        <button class="btn-k btn-primary-k" type="submit" style="width:100%;justify-content:center;">Pay by Mobile Money</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Premium -->
        <div class="card-k" style="<?= $currentPlan === 'premium' ? 'border:2px solid var(--primary);' : '' ?>position:relative;overflow:visible;">
            <div style="position:absolute;top:-10px;right:16px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:700;">POPULAIRE</div>
            <div class="card-body-k" style="text-align:center;padding:24px;">
                <div style="font-size:24px;margin-bottom:8px;">💎</div>
                <h3 style="font-size:18px;font-weight:800;margin-bottom:4px;">Premium</h3>
                <div style="font-size:24px;font-weight:800;margin-bottom:16px;"><?= number_format(PREMIUM_PRICE, 0, ',', ' ') ?> <span style="font-size:13px;font-weight:400;">FCFA/mois</span></div>
                <ul style="list-style:none;text-align:left;font-size:13px;line-height:2;">
                    <li>✅ Clients illimités</li>
                    <li>✅ Crédits illimités</li>
                    <li>✅ Stats complètes</li>
                    <li>✅ Export PDF + Excel</li>
                    <li>✅ Rappels WhatsApp + SMS</li>
                </ul>
                <?php if ($currentPlan === 'premium'): ?>
                    <div style="margin-top:16px;padding:8px;background:rgba(232,168,56,0.1);border-radius:8px;font-size:12px;color:var(--primary-dark);font-weight:600;">✓ Plan actuel</div>
                <?php else: ?>
                    <form action="<?= url('subscription/pay?plan=premium') ?>" method="get" data-monetbil="form" style="margin-top:16px;">
                        <button class="btn-k btn-primary-k" type="submit" style="width:100%;justify-content:center;">Pay by Mobile Money</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
