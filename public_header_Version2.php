<?php
// public/header.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
function requireAdmin() {
    if (!isAdmin()) {
        http_response_code(403);
        echo "Forbidden: admin only.";
        exit();
    }
}

// CSRF helper
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Student Information System</title>
  <link rel="stylesheet" href="/public/assets/css/styles.css">
  <script src="/public/assets/js/scripts.js" defer></script>
</head>
<body>
<header class="site-header">
  <nav>
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="students.php">Students</a>
    <?php if (isLoggedIn()): ?>
      <span class="nav-user">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">