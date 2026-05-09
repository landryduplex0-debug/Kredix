<?php
/**
 * Système de messages flash
 */

/**
 * Définir un message flash
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

/**
 * Obtenir et supprimer un message flash
 */
function getFlash(string $type): ?string
{
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Vérifier si un message flash existe
 */
function hasFlash(string $type): bool
{
    return isset($_SESSION['flash'][$type]);
}

/**
 * Obtenir tous les messages flash et les supprimer
 */
function getAllFlash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}
