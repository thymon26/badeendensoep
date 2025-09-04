<?php
/**
 * Beveiligingsfuncties voor admin gebied
 */

// Start sessie als deze nog niet gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Controleer of gebruiker ingelogd is
 */
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    
    // Controleer of sessie niet te oud is (optioneel - 2 uur timeout)
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 7200) {
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }
    
    // Update laatste activiteit
    $_SESSION['last_activity'] = time();
}

/**
 * Krijg ingelogde gebruiker info
 */
function getAdminUser() {
    if (isset($_SESSION['admin_username'])) {
        return $_SESSION['admin_username'];
    }
    return null;
}

/**
 * Logout functie
 */
function adminLogout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

/**
 * Krijg login tijd
 */
function getLoginTime() {
    if (isset($_SESSION['login_time'])) {
        return date('d-m-Y H:i:s', $_SESSION['login_time']);
    }
    return 'Onbekend';
}
?>
