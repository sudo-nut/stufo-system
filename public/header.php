<?php
/**
 * Common Header Template
 * 
 * This file provides the common HTML header, navigation, and authentication helpers
 * for all pages in the Student Information System.
 * 
 * Authentication helpers:
 * - isLoggedIn(): Check if user is authenticated
 * - isAdmin(): Check if user has admin role
 * - requireLogin(): Redirect to login if not authenticated
 * - requireAdmin(): Redirect to login if not admin
 * - generateCSRFToken(): Generate CSRF token for forms
 * - validateCSRFToken(): Validate CSRF token from POST
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication helper functions

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Check if user is an admin
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require user to be logged in, redirect to login if not
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

/**
 * Require user to be admin, redirect to login if not
 */
function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

/**
 * Generate CSRF token and store in session
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from POST request
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize output for HTML display
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Get current page for active nav highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? h($page_title) . ' - ' : ''; ?>Student Information System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <a href="index.php">Student Info System</a>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="about.php" class="<?php echo $current_page === 'about.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="students.php" class="<?php echo $current_page === 'students.php' ? 'active' : ''; ?>">Students</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li class="user-info">
                            <span>Welcome, <?php echo h($_SESSION['username']); ?></span>
                            <?php if (isAdmin()): ?>
                                <span class="badge">Admin</span>
                            <?php endif; ?>
                        </li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="<?php echo $current_page === 'login.php' ? 'active' : ''; ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">
