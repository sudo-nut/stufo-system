<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

requireLogin();
requireAdmin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $matric_no = trim($_POST['matric_no'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $ic_no = trim($_POST['ic_no'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $programme = trim($_POST['programme'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $semester = (int)($_POST['semester'] ?? 1);
        $email = trim($_POST['email'] ?? '');
        $phone_no = trim($_POST['phone_no'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($matric_no === '' || $name === '' || $ic_no === '') {
            $errors[] = 'Matric, Name and IC are required.';
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO `STUDENT` (matric_no, name, ic_no, gender, programme, faculty, semester, email, phone_no, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssisss', $matric_no, $name, $ic_no, $gender, $programme, $faculty, $semester, $email, $phone_no, $address);
            if ($stmt->execute()) {
                header('Location: students.php');
                exit();
            } else {
                $errors[] = 'Insert failed: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<h1>Add Student</h1>
<?php if ($errors): foreach ($errors as $e) echo "<div class=\"error\">".htmlspecialchars($e)."</div>"; endforeach; ?>
<form method="post" action="student_add.php" class="form">
  <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
  <label>Matric No<input type="text" name="matric_no" required></label>
  <label>Name<input type="text" name="name" required></label>
  <label>IC No<input type="text" name="ic_no" required></label>
  <label>Gender<input type="text" name="gender" required></label>
  <label>Programme<input type="text" name="programme" required></label>
  <label>Faculty<input type="text" name="faculty" required></label>
  <label>Semester<input type="number" name="semester" min="1" max="12" value="1" required></label>
  <label>Email<input type="email" name="email"></label>
  <label>Phone<input type="text" name="phone_no"></label>
  <label>Address<textarea name="address"></textarea></label>
  <button type="submit">Add</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>