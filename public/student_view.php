<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { echo "Invalid student id."; exit(); }

$stmt = $conn->prepare("SELECT * FROM `STUDENT` WHERE STUDENTID = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();
$stmt->close();

if (!$student) { echo "Student not found."; exit(); }
?>
<h1>Student Details</h1>
<table class="detail-table">
  <tr><th>Student ID</th><td><?= htmlspecialchars($student['STUDENTID']) ?></td></tr>
  <tr><th>Matric No</th><td><?= htmlspecialchars($student['matric_no']) ?></td></tr>
  <tr><th>IC No</th><td><?= htmlspecialchars($student['ic_no']) ?></td></tr>
  <tr><th>Full Name</th><td><?= htmlspecialchars($student['FULLNAME']) ?></td></tr>
  <tr><th>Programme</th><td><?= htmlspecialchars($student['programme']) ?></td></tr>
  <tr><th>Faculty</th><td><?= htmlspecialchars($student['faculty']) ?></td></tr>
  <tr><th>Semester</th><td><?= htmlspecialchars($student['semester']) ?></td></tr>
  <tr><th>Contact</th><td><?= htmlspecialchars($student['CONTACTNO']) ?></td></tr>
  <tr><th>Email</th><td><?= htmlspecialchars($student['EMAIL']) ?></td></tr>
  <tr><th>Address 1</th><td><?= htmlspecialchars($student['ADDRESS1']) ?></td></tr>
  <tr><th>Address 2</th><td><?= htmlspecialchars($student['ADDRESS2']) ?></td></tr>
  <tr><th>Postcode</th><td><?= htmlspecialchars($student['POSTCODE']) ?></td></tr>
  <tr><th>City</th><td><?= htmlspecialchars($student['CITY']) ?></td></tr>
  <tr><th>State</th><td><?= htmlspecialchars($student['STATE']) ?></td></tr>
  <tr><th>Gender</th><td><?= htmlspecialchars($student['GENDER']) ?></td></tr>
  <tr><th>Race</th><td><?= htmlspecialchars($student['RACE']) ?></td></tr>
  <tr><th>Religion</th><td><?= htmlspecialchars($student['RELIGION']) ?></td></tr>
  <tr><th>Created At</th><td><?= htmlspecialchars($student['created_at']) ?></td></tr>
</table>
<?php require_once __DIR__ . '/footer.php'; ?>