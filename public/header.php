<?php
/**
 * header.php
 * Common header for all pages
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">Student Information System</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="students.php">Students</a></li>
                    <?php if (is_admin()): ?>
                        <li><a href="student_add.php">Add Student</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo h($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">
