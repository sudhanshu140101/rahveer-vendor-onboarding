<?php
require_once dirname(__DIR__) . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdminLoggedIn(): bool {
    return !empty($_SESSION[ADMIN_SESSION_NAME]);
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getAdminUsername(): string {
    return (string) ($_SESSION[ADMIN_SESSION_NAME] ?? '');
}
