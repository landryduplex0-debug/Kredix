<?php
$flashMessages = getAllFlash();
foreach ($flashMessages as $type => $message):
    $alertClass = 'alert-info-k';
    $icon = 'bi-info-circle';
    if ($type === 'success') { $alertClass = 'alert-success-k'; $icon = 'bi-check-circle-fill'; }
    elseif ($type === 'error') { $alertClass = 'alert-error-k'; $icon = 'bi-exclamation-circle-fill'; }
    elseif ($type === 'warning') { $alertClass = 'alert-warning-k'; $icon = 'bi-exclamation-triangle-fill'; }
?>
<div class="alert-k <?= $alertClass ?>">
    <i class="bi <?= $icon ?>"></i>
    <span><?= $message ?></span>
</div>
<?php endforeach; ?>
