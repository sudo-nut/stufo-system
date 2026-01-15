<?php
/**
 * Home Page
 * 
 * Landing page for the Student Information System.
 * Displays welcome message and system overview.
 */

$page_title = 'Home';
require_once 'header.php';
?>

<div class="page-header">
    <h1>Welcome to Student Information System</h1>
    <p class="lead">A comprehensive system for managing student records</p>
</div>

<div class="content-section">
    <div class="row">
        <div class="col">
            <div class="card">
                <h2>About This System</h2>
                <p>
                    The Student Information System is designed to efficiently manage and organize 
                    student records. It provides a user-friendly interface for viewing, searching, 
                    and managing student information.
                </p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <h2>Features</h2>
                <ul>
                    <li>Search and filter student records</li>
                    <li>View detailed student information</li>
                    <li>Pagination for easy navigation</li>
                    <li>Role-based access control</li>
                    <li>Admin capabilities for CRUD operations</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="content-section">
    <div class="card">
        <h2>Getting Started</h2>
        <p>
            <?php if (isLoggedIn()): ?>
                You are logged in as <strong><?php echo h($_SESSION['username']); ?></strong>.
                <?php if (isAdmin()): ?>
                    As an administrator, you have full access to manage student records.
                <?php else: ?>
                    You can view student information and search through records.
                <?php endif; ?>
            <?php else: ?>
                To access the system, please <a href="login.php">login</a> with your credentials.
            <?php endif; ?>
        </p>
        <p>
            <a href="students.php" class="btn btn-primary">Browse Students</a>
            <?php if (!isLoggedIn()): ?>
                <a href="login.php" class="btn btn-secondary">Login</a>
            <?php endif; ?>
        </p>
    </div>
</div>

<?php require_once 'footer.php'; ?>
