<?php
/**
 * Database Connection Configuration
 * 
 * This file establishes a connection to the DBASSIGNMENT database
 * using mysqli with utf8mb4 character set for proper Unicode support.
 * 
 * Database credentials are set for local development environment.
 * For production, these should be moved to environment variables.
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'DBASSIGNMENT');
define('DB_CHARSET', 'utf8mb4');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error . "\n" .
        "Please ensure MySQL is running and the database has been initialized.\n" .
        "Run: mysql -u root < sql/init_db.sql\n");
}

// Set character set to utf8mb4
if (!$conn->set_charset(DB_CHARSET)) {
    die("Error loading character set " . DB_CHARSET . ": " . $conn->error . "\n");
}

// Connection successful - no output for includes
// $conn is now available for use in included files
