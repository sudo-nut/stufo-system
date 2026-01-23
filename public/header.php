<?php
// public/header.php
// Minimal, safe header that starts session, provides CSRF token,
// defines helper wrappers only if they don't already exist, and
// computes a correct base path for asset URLs.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* CSRF token */
if (!isset($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$csrf_token = $_SESSION['csrf_token'];

/* helper functions (guarded) */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool { return !empty($_SESSION['user_id']); }
}
if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool { return isLoggedIn(); }
}
if (!function_exists('isAdmin')) {
    function isAdmin(): bool { return !empty($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
}
if (!function_exists('is_admin')) {
    function is_admin(): bool { return isAdmin(); }
}
if (!function_exists('requireLogin')) {
    function requireLogin(): void { if (!isLoggedIn()) { header('Location: login.php'); exit(); } }
}
if (!function_exists('require_login')) {
    function require_login(): void { requireLogin(); }
}
if (!function_exists('requireAdmin')) {
    function requireAdmin(): void { if (!isAdmin()) { http_response_code(403); echo "Forbidden: admin only."; exit(); } }
}
if (!function_exists('require_admin')) {
    function require_admin(): void { requireAdmin(); }
}

/* Compute base path for assets relative to the current script.
   Example: if pages are served from /stufo-system/public/students.php,
   $base becomes "/stufo-system/public" so "$base/assets/..." resolves correctly. */
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = rtrim($scriptDir, '/\\'); // may be empty or like "/stufo-system/public"
$base = ($scriptDir === '/' ? '' : $scriptDir);

/* asset_url helper (uses global $base) */
if (!function_exists('asset_url')) {
    function asset_url(string $path): string {
        global $base;
        $prefix = ($base === '' ? '' : $base);
        return $prefix . '/' . ltrim($path, '/');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Student Information System</title>
  <!-- Use asset_url() to reference the correct path for CSS/JS -->
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('assets/css/styles.css')) ?>">
  <script src="<?= htmlspecialchars(asset_url('assets/js/scripts.js')) ?>" defer></script>
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<header class="site-header">
  <nav>
    <a href="<?= htmlspecialchars(asset_url('index.php')) ?>">Home</a>
    <a href="<?= htmlspecialchars(asset_url('students.php')) ?>">Students</a>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <span class="nav-user">Hello, <?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
      <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="<?= htmlspecialchars(asset_url('admin.php')) ?>">Admin</a>
      <?php endif; ?>
      <a href="<?= htmlspecialchars(asset_url('logout.php')) ?>">Logout</a>
    <?php else: ?>
      <a href="<?= htmlspecialchars(asset_url('login.php')) ?>">Login</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">