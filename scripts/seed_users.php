<?php
/**
 * User Seeding Script
 * 
 * This CLI script seeds the USER table with default admin and student accounts.
 * The script is idempotent - it will update existing users if they already exist.
 * 
 * Usage: php scripts/seed_users.php
 * 
 * Default Users:
 * - admin01 / admin123 (role: admin)
 * - student01 / student123 (role: student)
 */

// Ensure this script is run from CLI only
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Include database configuration
require_once __DIR__ . '/../public/db.php';

echo "=== User Seeding Script ===\n";
echo "Database: " . DB_NAME . "\n";
echo "Host: " . DB_HOST . "\n\n";

// Define users to seed
$users = [
    [
        'username' => 'admin01',
        'password' => 'admin123',
        'full_name' => 'Administrator',
        'role' => 'admin'
    ],
    [
        'username' => 'student01',
        'password' => 'student123',
        'full_name' => 'Student User',
        'role' => 'student'
    ]
];

// Process each user
foreach ($users as $user) {
    echo "Processing user: {$user['username']}... ";
    
    // Hash the password
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    
    // Check if user exists
    $check_stmt = $conn->prepare("SELECT user_id FROM USER WHERE username = ?");
    $check_stmt->bind_param("s", $user['username']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists - update password, role, and full_name
        $update_stmt = $conn->prepare(
            "UPDATE USER SET password = ?, role = ?, full_name = ? WHERE username = ?"
        );
        $update_stmt->bind_param(
            "ssss",
            $hashed_password,
            $user['role'],
            $user['full_name'],
            $user['username']
        );
        
        if ($update_stmt->execute()) {
            echo "UPDATED\n";
        } else {
            echo "ERROR: " . $update_stmt->error . "\n";
        }
        $update_stmt->close();
    } else {
        // User doesn't exist - insert new user
        $insert_stmt = $conn->prepare(
            "INSERT INTO USER (username, password, full_name, role) VALUES (?, ?, ?, ?)"
        );
        $insert_stmt->bind_param(
            "ssss",
            $user['username'],
            $hashed_password,
            $user['full_name'],
            $user['role']
        );
        
        if ($insert_stmt->execute()) {
            echo "CREATED (ID: " . $conn->insert_id . ")\n";
        } else {
            echo "ERROR: " . $insert_stmt->error . "\n";
        }
        $insert_stmt->close();
    }
    
    $check_stmt->close();
}

echo "\n=== Seeding Complete ===\n";
echo "Default credentials:\n";
echo "  Admin:   admin01 / admin123\n";
echo "  Student: student01 / student123\n";

$conn->close();
