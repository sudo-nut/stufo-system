<?php
/**
 * seed_users.php
 * Seeds the USER table with initial admin and student users
 */

require_once __DIR__ . '/../public/db.php';

// Create admin user
$admin_username = 'admin';
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_fullname = 'System Administrator';
$admin_role = 'admin';

$stmt = $conn->prepare("INSERT INTO USER (username, password, full_name, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
$stmt->bind_param("ssss", $admin_username, $admin_password, $admin_fullname, $admin_role);

if ($stmt->execute()) {
    echo "Admin user created successfully.\n";
} else {
    echo "Error creating admin user: " . $stmt->error . "\n";
}

$stmt->close();

// Create sample student user
$student_username = 'student1';
$student_password = password_hash('student123', PASSWORD_DEFAULT);
$student_fullname = 'Sample Student';
$student_role = 'student';

$stmt = $conn->prepare("INSERT INTO USER (username, password, full_name, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
$stmt->bind_param("ssss", $student_username, $student_password, $student_fullname, $student_role);

if ($stmt->execute()) {
    echo "Student user created successfully.\n";
} else {
    echo "Error creating student user: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();

echo "User seeding completed.\n";
?>
