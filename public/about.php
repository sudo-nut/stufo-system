<?php
/**
 * about.php
 * About page describing the system
 */
require_once 'db.php';

$page_title = 'About';
include 'header.php';
?>

<div class="content-section">
    <h1>About Student Information System</h1>
    
    <div class="about-content">
        <section>
            <h2>Overview</h2>
            <p>The Student Information System is a web-based application designed to manage student records efficiently. Built with PHP and MySQL, it provides a secure and user-friendly interface for managing student data.</p>
        </section>
        
        <section>
            <h2>Features</h2>
            <ul>
                <li>Secure user authentication with role-based access control</li>
                <li>Complete student CRUD operations (Create, Read, Update, Delete)</li>
                <li>Pagination support for handling large datasets (20 records per page)</li>
                <li>CSRF protection for all forms</li>
                <li>Prepared statements for SQL injection prevention</li>
                <li>Responsive design for mobile and desktop</li>
            </ul>
        </section>
        
        <section>
            <h2>Technology Stack</h2>
            <ul>
                <li><strong>Backend:</strong> PHP 7.4+ with MySQLi</li>
                <li><strong>Database:</strong> MySQL 5.7+ / MariaDB</li>
                <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript</li>
                <li><strong>Security:</strong> Password hashing, CSRF tokens, prepared statements</li>
            </ul>
        </section>
        
        <section>
            <h2>User Roles</h2>
            <ul>
                <li><strong>Admin:</strong> Full access to add, edit, and delete student records</li>
                <li><strong>Student:</strong> View-only access to student records</li>
            </ul>
        </section>
        
        <section>
            <h2>Contact</h2>
            <p>For more information or support, please contact the system administrator.</p>
        </section>
    </div>
</div>

<?php include 'footer.php'; ?>
