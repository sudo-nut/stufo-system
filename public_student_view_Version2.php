<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "Invalid student id.";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM `STUDENT` WHERE student_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();
$stmt->close();

if (!$student) {
    echo "Student not found.";
    exit();
}
?>
<h1>Student Details</h1>
<table class="detail-table">
  <?php foreach ($student as $k => $v): ?>
  <tr>
    <th><?= htmlspecialchars($k) ?></th>
    <td><?= nl2br(htmlspecialchars($v)) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/footer.php'; ?>