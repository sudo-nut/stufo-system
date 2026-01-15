<?php
/**
 * About Page
 * 
 * Information about the Student Information System.
 */

$page_title = 'About';
require_once 'header.php';
?>

<div class="page-header">
    <h1>About Student Information System</h1>
</div>

<div class="content-section">
    <div class="card">
        <h2>System Overview</h2>
        <p>
            The Student Information System is a web-based application developed using PHP and MySQL 
            to provide an efficient solution for managing student records in educational institutions.
        </p>
        
        <h3>Key Features</h3>
        <ul>
            <li><strong>User Authentication:</strong> Secure login system with role-based access control</li>
            <li><strong>Student Management:</strong> Comprehensive CRUD operations for student records</li>
            <li><strong>Search & Filter:</strong> Advanced search capabilities to find students quickly</li>
            <li><strong>Pagination:</strong> Easy navigation through large datasets</li>
            <li><strong>Security:</strong> CSRF protection, prepared statements, and input validation</li>
            <li><strong>Responsive Design:</strong> Mobile-friendly interface</li>
        </ul>
        
        <h3>Technology Stack</h3>
        <ul>
            <li><strong>Backend:</strong> PHP (standard PHP, no frameworks)</li>
            <li><strong>Database:</strong> MySQL with mysqli extension</li>
            <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript</li>
            <li><strong>Security:</strong> Prepared statements, password hashing, CSRF tokens</li>
        </ul>
        
        <h3>User Roles</h3>
        <ul>
            <li><strong>Administrator:</strong> Full access to add, edit, and delete student records</li>
            <li><strong>Student:</strong> View-only access to browse student information</li>
        </ul>
    </div>
</div>

<div class="content-section">
    <div class="card">
        <h2>System Information</h2>
        <table class="info-table">
            <tr>
                <th>Version:</th>
                <td>1.0</td>
            </tr>
            <tr>
                <th>Database:</th>
                <td>DBASSIGNMENT</td>
            </tr>
            <tr>
                <th>Character Set:</th>
                <td>UTF-8 (utf8mb4)</td>
            </tr>
            <tr>
                <th>PHP Version Required:</th>
                <td>7.4 or higher</td>
            </tr>
            <tr>
                <th>MySQL Version Required:</th>
                <td>5.7 or higher</td>
            </tr>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>
