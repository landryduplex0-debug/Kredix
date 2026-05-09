<?php
/**
 * Fonctions de formatage
 */

/**
 * Formater un montant en FCFA
 */
function formatMoney($amount): string
{
    return number_format((float)$amount, 0, ',', ' ') . ' FCFA';
}

/**
 * Formater un montant court (K)
 */
function formatMoneyShort($amount): string
{
    $amount = (float)$amount;
    if ($amount >= 1000000) {
        return number_format($amount / 1000000, 1, ',', '') . 'M';
    }
    if ($amount >= 1000) {
        return number_format($amount / 1000, 0) . 'K';
    }
    return number_format($amount, 0);
}

/**
 * Formater une date en français
 */
function formatDate(?string $date): string
{
    if (!$date) return '-';
    $timestamp = strtotime($date);
    $months = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    return "{$day} {$month} {$year}";
}

/**
 * Formater une date complète avec heure
 */
function formatDateTime(?string $date): string
{
    if (!$date) return '-';
    $timestamp = strtotime($date);
    return formatDate($date) . ' à ' . date('H:i', $timestamp);
}

/**
 * Formater un numéro de téléphone camerounais
 */
function formatPhone(?string $phone): string
{
    if (!$phone) return '-';
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone) === 9) {
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    }
    return $phone;
}

/**
 * Badge de statut pour les crédits
 */
function statusBadge(string $status): string
{
    $badges = [
        'pending'   => '<span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> En attente</span>',
        'partial'   => '<span class="badge bg-info"><i class="bi bi-pie-chart"></i> Partiel</span>',
        'paid'      => '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Payé</span>',
        'overdue'   => '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> En retard</span>',
        'cancelled' => '<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Annulé</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">' . e($status) . '</span>';
}

/**
 * Badge de méthode de paiement
 */
function paymentMethodBadge(string $method): string
{
    $badges = [
        'cash'          => '<span class="badge bg-success-subtle text-success"><i class="bi bi-cash-coin"></i> Espèces</span>',
        'mobile_money'  => '<span class="badge bg-primary-subtle text-primary"><i class="bi bi-phone"></i> Mobile Money</span>',
        'bank_transfer' => '<span class="badge bg-info-subtle text-info"><i class="bi bi-bank"></i> Virement</span>',
        'other'         => '<span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-three-dots"></i> Autre</span>',
    ];
    return $badges[$method] ?? $badges['other'];
}

/**
 * Pourcentage de remboursement
 */
function paymentProgress(float $amount, float $paid): array
{
    if ($amount <= 0) return ['percent' => 0, 'class' => 'bg-secondary'];
    
    $percent = min(100, round(($paid / $amount) * 100));
    
    if ($percent >= 100) $class = 'bg-success';
    elseif ($percent >= 50) $class = 'bg-info';
    elseif ($percent >= 25) $class = 'bg-warning';
    else $class = 'bg-danger';
    
    return ['percent' => $percent, 'class' => $class];
}

/**
 * Score de confiance en étoiles
 */
function trustStars(int $score): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $score / 2) {
            $html .= '<i class="bi bi-star-fill text-warning"></i>';
        } else {
            $html .= '<i class="bi bi-star text-muted"></i>';
        }
    }
    return $html;
}
