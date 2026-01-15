<?php
/**
 * Edit Student Page
 * 
 * Admin-only page for editing existing student records.
 * Includes CSRF protection and server-side validation.
 */

$page_title = 'Edit Student';
require_once 'header.php';
require_once 'db.php';

// Require admin access
requireAdmin();

// Get student ID
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    header('Location: students.php');
    exit;
}

// Fetch existing student data
$stmt = $conn->prepare("SELECT * FROM STUDENT WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header('Location: students.php');
    exit;
}

$student = $result->fetch_assoc();
$stmt->close();

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_token)) {
        $errors[] = 'Invalid CSRF token. Please try again.';
    } else {
        // Get and sanitize form data
        $matric_no = trim($_POST['matric_no'] ?? '');
        $ic_no = trim($_POST['ic_no'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $date_of_birth = trim($_POST['date_of_birth'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $program = trim($_POST['program'] ?? '');
        $year_of_study = intval($_POST['year_of_study'] ?? 0);
        
        // Validate required fields
        if (empty($matric_no)) $errors[] = 'Matric number is required.';
        if (empty($ic_no)) $errors[] = 'IC number is required.';
        if (empty($full_name)) $errors[] = 'Full name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        if (empty($gender)) $errors[] = 'Gender is required.';
        if (empty($program)) $errors[] = 'Program is required.';
        if ($year_of_study < 1 || $year_of_study > 10) $errors[] = 'Year of study must be between 1 and 10.';
        
        // Validate email format
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }
        
        // Check for duplicate matric_no (excluding current student)
        if (!empty($matric_no)) {
            $check_stmt = $conn->prepare("SELECT student_id FROM STUDENT WHERE matric_no = ? AND student_id != ?");
            $check_stmt->bind_param("si", $matric_no, $student_id);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $errors[] = 'Matric number already exists.';
            }
            $check_stmt->close();
        }
        
        // Check for duplicate ic_no (excluding current student)
        if (!empty($ic_no)) {
            $check_stmt = $conn->prepare("SELECT student_id FROM STUDENT WHERE ic_no = ? AND student_id != ?");
            $check_stmt->bind_param("si", $ic_no, $student_id);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $errors[] = 'IC number already exists.';
            }
            $check_stmt->close();
        }
        
        // If no errors, update the student
        if (empty($errors)) {
            $update_stmt = $conn->prepare(
                "UPDATE STUDENT SET 
                 matric_no = ?, ic_no = ?, full_name = ?, email = ?, phone = ?, 
                 address = ?, date_of_birth = ?, gender = ?, program = ?, year_of_study = ?
                 WHERE student_id = ?"
            );
            $update_stmt->bind_param(
                "sssssssssii",
                $matric_no, $ic_no, $full_name, $email, $phone, 
                $address, $date_of_birth, $gender, $program, $year_of_study, $student_id
            );
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = 'Student updated successfully!';
                header('Location: student_view.php?id=' . $student_id);
                exit;
            } else {
                $errors[] = 'Database error: ' . $update_stmt->error;
            }
            $update_stmt->close();
        }
    }
    
    // If there were errors, use POST data for form
    if (!empty($errors)) {
        $student = array_merge($student, $_POST);
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h1>Edit Student</h1>
    <p>Update student information</p>
</div>

<div class="content-section">
    <div class="form-container">
        <div class="card">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="student_edit.php?id=<?php echo $student_id; ?>" class="form" id="studentForm">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="matric_no">Matric Number: *</label>
                        <input type="text" id="matric_no" name="matric_no" class="form-control" required
                               value="<?php echo h($student['matric_no']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="ic_no">IC Number: *</label>
                        <input type="text" id="ic_no" name="ic_no" class="form-control" required
                               value="<?php echo h($student['ic_no']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name: *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" required
                           value="<?php echo h($student['full_name']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email: *</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?php echo h($student['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                               value="<?php echo h($student['phone']); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                               value="<?php echo h($student['date_of_birth']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender: *</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo h($student['address']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="program">Program: *</label>
                        <input type="text" id="program" name="program" class="form-control" required
                               value="<?php echo h($student['program']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="year_of_study">Year of Study: *</label>
                        <select id="year_of_study" name="year_of_study" class="form-control" required>
                            <option value="">Select Year</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" 
                                    <?php echo ($student['year_of_study'] == $i) ? 'selected' : ''; ?>>
                                    Year <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">Update Student</button>
                    <a href="student_view.php?id=<?php echo $student_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
