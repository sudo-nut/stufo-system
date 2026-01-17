<?php
// public/db.php
// Database connection and small compatibility helpers.
// Replace DB credentials below to match your environment. Back up existing file before replacing.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database credentials - change if your XAMPP/MySQL is different
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';      // usually empty for local XAMPP
$DB_NAME = getenv('DB_NAME') ?: 'DBASSIGNMENT';
$DB_PORT = getenv('DB_PORT') ?: 3306;

// Create mysqli connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, (int)$DB_PORT);
if ($conn->connect_errno) {
    // Don't reveal credentials in production; this is local dev.
    die("Database connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset('utf8mb4');

/*
 * Define compatibility helper functions only if they are not already defined.
 * Some files (header.php and others) may also define these; using function_exists
 * prevents "Cannot redeclare" fatal errors.
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }
}
if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        return isLoggedIn();
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool {
        return !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
if (!function_exists('is_admin')) {
    function is_admin(): bool {
        return isAdmin();
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        if (!isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }
}
if (!function_exists('require_login')) {
    function require_login(): void {
        requireLogin();
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin(): void {
        if (!isAdmin()) {
            http_response_code(403);
            echo "Forbidden: admin only.";
            exit();
        }
    }
}
if (!function_exists('require_admin')) {
    function require_admin(): void {
        requireAdmin();
    }
}

// Optionally expose a small helper to get current username/id
if (!function_exists('current_user_id')) {
    function current_user_id() {
        return $_SESSION['user_id'] ?? null;
    }
}
if (!function_exists('current_username')) {
    function current_username() {
        return $_SESSION['username'] ?? null;
    }
}