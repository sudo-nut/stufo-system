<?php
// public/db.php
// Database connection used by the application and scripts.
// Default: host=localhost, user=root, password='', DB=DBASSIGNMENT
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'DBASSIGNMENT';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    // Exit with a helpful message for local dev
    die('Database connection failed: ' . $conn->connect_error . PHP_EOL);
}
$conn->set_charset('utf8mb4');