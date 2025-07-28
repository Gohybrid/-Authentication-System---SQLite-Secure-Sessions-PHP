<?php
// Define session lifetime (48 hours = 172800 seconds)
define('SESSION_LIFETIME', 48 * 60 * 60); // 48 hours in seconds

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (empty($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}

// Check if session is expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_LIFETIME) {
    // Expired: clear session and redirect to login
    session_unset();
    session_destroy();
    header('Location: /login.php?expired=1');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Optional: make $user variable available on any page
$user = $_SESSION['user'];
