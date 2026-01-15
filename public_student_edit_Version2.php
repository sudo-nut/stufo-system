<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';
requireLogin();
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid student id.";
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $id = (int)($_POST['id'] ?? 0);
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
            $stmt = $conn->prepare("UPDATE `STUDENT` SET matric_no=?, name=?, ic_no=?, gender=?, programme=?, faculty=?, semester=?, email=?, phone_no=?, address=? WHERE student_id=?");
            $stmt->bind_param('ssssssisssi', $matric_no, $name, $ic_no, $gender, $programme, $faculty, $semester, $email, $phone_no, $address, $id);
            if ($stmt->execute()) {
                header('Location: student_view.php?id=' . $id);
                exit();
            } else {
                $errors[] = 'Update failed: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
} else {
    // Load existing
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
}
?>
<h1>Edit Student</h1>
<?php if (!empty($errors)): foreach ($errors as $e) echo "<div class=\"error\">".htmlspecialchars($e)."</div>"; endforeach; ?>
<form method="post" action="student_edit.php" class="form">
  <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($student['student_id'] ?? $id) ?>">
  <label>Matric No<input type="text" name="matric_no" required value="<?= htmlspecialchars($student['matric_no'] ?? '') ?>"></label>
  <label>Name<input type="text" name="name" required value="<?= htmlspecialchars($student['name'] ?? '') ?>"></label>
  <label>IC No<input type="text" name="ic_no" required value="<?= htmlspecialchars($student['ic_no'] ?? '') ?>"></label>
  <label>Gender<input type="text" name="gender" required value="<?= htmlspecialchars($student['gender'] ?? '') ?>"></label>
  <label>Programme<input type="text" name="programme" required value="<?= htmlspecialchars($student['programme'] ?? '') ?>"></label>
  <label>Faculty<input type="text" name="faculty" required value="<?= htmlspecialchars($student['faculty'] ?? '') ?>"></label>
  <label>Semester<input type="number" name="semester" min="1" max="12" value="<?= htmlspecialchars($student['semester'] ?? '1') ?>" required></label>
  <label>Email<input type="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>"></label>
  <label>Phone<input type="text" name="phone_no" value="<?= htmlspecialchars($student['phone_no'] ?? '') ?>"></label>
  <label>Address<textarea name="address"><?= htmlspecialchars($student['address'] ?? '') ?></textarea></label>
  <button type="submit">Save</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>