<?php
/**
 * Logout Page
 * 
 * Destroys the user session and redirects to login page.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
$_SESSION = [];

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
