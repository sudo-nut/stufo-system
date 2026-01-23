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
        $FULLNAME = trim($_POST['FULLNAME'] ?? '');
        $ADDRESS1 = trim($_POST['ADDRESS1'] ?? '');
        $ADDRESS2 = trim($_POST['ADDRESS2'] ?? '');
        $POSTCODE = trim($_POST['POSTCODE'] ?? '');
        $CITY = trim($_POST['CITY'] ?? '');
        $STATE = trim($_POST['STATE'] ?? '');
        $GENDER = trim($_POST['GENDER'] ?? '');
        $RACE = trim($_POST['RACE'] ?? '');
        $RELIGION = trim($_POST['RELIGION'] ?? '');
        $CONTACTNO = trim($_POST['CONTACTNO'] ?? '');
        $EMAIL = trim($_POST['EMAIL'] ?? '');
        $matric_no = trim($_POST['matric_no'] ?? '');
        $ic_no = trim($_POST['ic_no'] ?? '');
        $programme = trim($_POST['programme'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $semester = (int)($_POST['semester'] ?? 1);

        if ($FULLNAME === '' || $matric_no === '' || $ic_no === '') {
            $errors[] = 'Fullname, Matric and IC are required.';
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO `STUDENT` (FULLNAME, ADDRESS1, ADDRESS2, POSTCODE, CITY, STATE, GENDER, RACE, RELIGION, CONTACTNO, EMAIL, matric_no, ic_no, programme, faculty, semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssssssssssi',
                $FULLNAME, $ADDRESS1, $ADDRESS2, $POSTCODE, $CITY, $STATE,
                $GENDER, $RACE, $RELIGION, $CONTACTNO, $EMAIL,
                $matric_no, $ic_no, $programme, $faculty, $semester
            );
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
<?php foreach ($errors as $e) echo "<div class=\"error\">".htmlspecialchars($e)."</div>"; ?>
<form method="post" action="student_add.php" class="form">
  <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
  <label>Fullname<input type="text" name="FULLNAME" required></label>
  <label>Matric No<input type="text" name="matric_no" required></label>
  <label>IC No<input type="text" name="ic_no" required></label>
  <label>Programme<input type="text" name="programme"></label>
  <label>Faculty<input type="text" name="faculty"></label>
  <label>Semester<input type="number" name="semester" min="1" max="12" value="1"></label>
  <label>Address 1<input type="text" name="ADDRESS1"></label>
  <label>Address 2<input type="text" name="ADDRESS2"></label>
  <label>Postcode<input type="text" name="POSTCODE"></label>
  <label>City<input type="text" name="CITY"></label>
  <label>State<input type="text" name="STATE"></label>
  <label>Gender<input type="text" name="GENDER"></label>
  <label>Race<input type="text" name="RACE"></label>
  <label>Religion<input type="text" name="RELIGION"></label>
  <label>Contact No<input type="text" name="CONTACTNO"></label>
  <label>Email<input type="email" name="EMAIL"></label>
  <button type="submit">Add</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>