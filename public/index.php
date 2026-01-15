<?php
/**
 * index.php
 * Home page of Student Information System
 */
require_once 'db.php';

$page_title = 'Home';
include 'header.php';
?>

<div class="hero">
    <h1>Welcome to Student Information System</h1>
    <p class="lead">A comprehensive system for managing student records and information.</p>
    <?php if (!is_logged_in()): ?>
        <a href="login.php" class="btn btn-primary">Login to Get Started</a>
    <?php else: ?>
        <a href="students.php" class="btn btn-primary">View Students</a>
    <?php endif; ?>
</div>

<div class="features">
    <div class="feature-card">
        <h3>Student Management</h3>
        <p>Efficiently manage student records including personal information, academic details, and contact information.</p>
    </div>
    <div class="feature-card">
        <h3>Secure Access</h3>
        <p>Role-based access control ensures that only authorized users can view and modify student data.</p>
    </div>
    <div class="feature-card">
        <h3>Easy Navigation</h3>
        <p>Intuitive interface with pagination support for browsing through large student databases.</p>
    </div>
</div>

<?php include 'footer.php'; ?>
