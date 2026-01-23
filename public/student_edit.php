<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';
requireLogin();
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') { echo "Invalid student id."; exit(); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $id = (int)($_POST['id'] ?? 0);
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
            $stmt = $conn->prepare("UPDATE `STUDENT` SET FULLNAME=?, ADDRESS1=?, ADDRESS2=?, POSTCODE=?, CITY=?, STATE=?, GENDER=?, RACE=?, RELIGION=?, CONTACTNO=?, EMAIL=?, matric_no=?, ic_no=?, programme=?, faculty=?, semester=? WHERE STUDENTID=?");
            $stmt->bind_param('ssssssssssssssssii',
                $FULLNAME, $ADDRESS1, $ADDRESS2, $POSTCODE, $CITY, $STATE,
                $GENDER, $RACE, $RELIGION, $CONTACTNO, $EMAIL,
                $matric_no, $ic_no, $programme, $faculty, $semester,
                $id
            );
            // Note: bind types string is 'ssssssssssssssssii' — but semester and id are ints.
            // For clarity, rebuild with correct types:
            $stmt->close();
            $stmt = $conn->prepare("UPDATE `STUDENT` SET FULLNAME=?, ADDRESS1=?, ADDRESS2=?, POSTCODE=?, CITY=?, STATE=?, GENDER=?, RACE=?, RELIGION=?, CONTACTNO=?, EMAIL=?, matric_no=?, ic_no=?, programme=?, faculty=?, semester=? WHERE STUDENTID=?");
            $types = 'sssssssssssssssi' . 'i'; // 15 strings + semester int + STUDENTID int
            $stmt->bind_param(
                'sssssssssssssssis',
                $FULLNAME, $ADDRESS1, $ADDRESS2, $POSTCODE, $CITY, $STATE,
                $GENDER, $RACE, $RELIGION, $CONTACTNO, $EMAIL,
                $matric_no, $ic_no, $programme, $faculty, $semester,
                $id
            );
            // Above bind uses single type string 'sssssssssssssssis' (15 s + i + s?) 
            // To avoid confusion, we'll use a safe approach: cast semester and id, and use 'ssssssssssssssssii' with two ints at end.
            $stmt->close();
            $stmt = $conn->prepare("UPDATE `STUDENT` SET FULLNAME=?, ADDRESS1=?, ADDRESS2=?, POSTCODE=?, CITY=?, STATE=?, GENDER=?, RACE=?, RELIGION=?, CONTACTNO=?, EMAIL=?, matric_no=?, ic_no=?, programme=?, faculty=?, semester=? WHERE STUDENTID=?");
            $stmt->bind_param(
                'ssssssssssssssssii',
                $FULLNAME, $ADDRESS1, $ADDRESS2, $POSTCODE, $CITY, $STATE,
                $GENDER, $RACE, $RELIGION, $CONTACTNO, $EMAIL,
                $matric_no, $ic_no, $programme, $faculty, $semester,
                $id
            );

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
    $stmt = $conn->prepare("SELECT * FROM `STUDENT` WHERE STUDENTID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $student = $res->fetch_assoc();
    $stmt->close();
    if (!$student) { echo "Student not found."; exit(); }
}
?>
<h1>Edit Student</h1>
<?php foreach ($errors as $e) echo "<div class='error'>".htmlspecialchars($e)."</div>"; ?>
<form method="post" action="student_edit.php" class="form">
  <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($student['STUDENTID'] ?? $id) ?>">
  <label>Fullname<input type="text" name="FULLNAME" required value="<?= htmlspecialchars($student['FULLNAME'] ?? '') ?>"></label>
  <label>Matric No<input type="text" name="matric_no" required value="<?= htmlspecialchars($student['matric_no'] ?? '') ?>"></label>
  <label>IC No<input type="text" name="ic_no" required value="<?= htmlspecialchars($student['ic_no'] ?? '') ?>"></label>
  <label>Programme<input type="text" name="programme" value="<?= htmlspecialchars($student['programme'] ?? '') ?>"></label>
  <label>Faculty<input type="text" name="faculty" value="<?= htmlspecialchars($student['faculty'] ?? '') ?>"></label>
  <label>Semester<input type="number" name="semester" min="1" max="12" value="<?= htmlspecialchars($student['semester'] ?? '1') ?>"></label>
  <label>Address 1<input type="text" name="ADDRESS1" value="<?= htmlspecialchars($student['ADDRESS1'] ?? '') ?>"></label>
  <label>Address 2<input type="text" name="ADDRESS2" value="<?= htmlspecialchars($student['ADDRESS2'] ?? '') ?>"></label>
  <label>Postcode<input type="text" name="POSTCODE" value="<?= htmlspecialchars($student['POSTCODE'] ?? '') ?>"></label>
  <label>City<input type="text" name="CITY" value="<?= htmlspecialchars($student['CITY'] ?? '') ?>"></label>
  <label>State<input type="text" name="STATE" value="<?= htmlspecialchars($student['STATE'] ?? '') ?>"></label>
  <label>Gender<input type="text" name="GENDER" value="<?= htmlspecialchars($student['GENDER'] ?? '') ?>"></label>
  <label>Race<input type="text" name="RACE" value="<?= htmlspecialchars($student['RACE'] ?? '') ?>"></label>
  <label>Religion<input type="text" name="RELIGION" value="<?= htmlspecialchars($student['RELIGION'] ?? '') ?>"></label>
  <label>Contact No<input type="text" name="CONTACTNO" value="<?= htmlspecialchars($student['CONTACTNO'] ?? '') ?>"></label>
  <label>Email<input type="email" name="EMAIL" value="<?= htmlspecialchars($student['EMAIL'] ?? '') ?>"></label>
  <button type="submit">Save</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>