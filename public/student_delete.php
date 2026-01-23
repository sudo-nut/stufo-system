<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';
requireLogin();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit();
}
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo "Invalid CSRF token.";
    exit();
}
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo "Invalid id.";
    exit();
}

$stmt = $conn->prepare("DELETE FROM `STUDENT` WHERE STUDENTID = ?");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    header('Location: students.php');
    exit();
} else {
    echo "Delete failed: " . $stmt->error;
}
$stmt->close();