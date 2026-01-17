<?php
// scripts/seed_users.php
// Usage: php scripts/seed_users.php
// Seeds admin01/admin123 and student01/student123 into DBASSIGNMENT->USER table
require_once __DIR__ . '/../public/db.php';

$users = [
    [
        'username' => 'admin01',
        'password' => 'admin123',
        'full_name' => 'System Administrator',
        'role' => 'admin',
        'status' => 'ACTIVE'
    ],
    [
        'username' => 'student01',
        'password' => 'student123',
        'full_name' => 'Student User',
        'role' => 'student',
        'status' => 'ACTIVE'
    ]
];

foreach ($users as $u) {
    $username = $u['username'];
    $plaintext = $u['password'];
    $full_name = $u['full_name'];
    $role = $u['role'];
    $status = $u['status'];
    $hashed = password_hash($plaintext, PASSWORD_DEFAULT);

    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id FROM `USER` WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // update
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        $uStmt = $conn->prepare("UPDATE `USER` SET password=?, full_name=?, role=?, status=? WHERE user_id=?");
        $uStmt->bind_param('ssssi', $hashed, $full_name, $role, $status, $user_id);
        $uStmt->execute();
        $uStmt->close();
        echo "Updated user: $username\n";
    } else {
        $stmt->close();
        // insert
        $iStmt = $conn->prepare("INSERT INTO `USER` (username, password, full_name, role, status) VALUES (?, ?, ?, ?, ?)");
        $iStmt->bind_param('sssss', $username, $hashed, $full_name, $role, $status);
        $iStmt->execute();
        $iStmt->close();
        echo "Inserted user: $username\n";
    }
}

$conn->close();
echo "Seeding complete.\n";