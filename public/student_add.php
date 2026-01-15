<?php
/**
 * Add Student Page
 * 
 * Admin-only page for adding new student records.
 * Includes CSRF protection and server-side validation.
 */

$page_title = 'Add Student';
require_once 'header.php';
require_once 'db.php';

// Require admin access
requireAdmin();

$errors = [];
$success = '';

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
        
        // Check for duplicate matric_no
        if (!empty($matric_no)) {
            $check_stmt = $conn->prepare("SELECT student_id FROM STUDENT WHERE matric_no = ?");
            $check_stmt->bind_param("s", $matric_no);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $errors[] = 'Matric number already exists.';
            }
            $check_stmt->close();
        }
        
        // Check for duplicate ic_no
        if (!empty($ic_no)) {
            $check_stmt = $conn->prepare("SELECT student_id FROM STUDENT WHERE ic_no = ?");
            $check_stmt->bind_param("s", $ic_no);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $errors[] = 'IC number already exists.';
            }
            $check_stmt->close();
        }
        
        // If no errors, insert the student
        if (empty($errors)) {
            $insert_stmt = $conn->prepare(
                "INSERT INTO STUDENT (matric_no, ic_no, full_name, email, phone, address, date_of_birth, gender, program, year_of_study) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $insert_stmt->bind_param(
                "sssssssssi",
                $matric_no, $ic_no, $full_name, $email, $phone, 
                $address, $date_of_birth, $gender, $program, $year_of_study
            );
            
            if ($insert_stmt->execute()) {
                $new_student_id = $conn->insert_id;
                $_SESSION['success_message'] = 'Student added successfully!';
                header('Location: student_view.php?id=' . $new_student_id);
                exit;
            } else {
                $errors[] = 'Database error: ' . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h1>Add New Student</h1>
    <p>Enter student information</p>
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
            
            <form method="POST" action="student_add.php" class="form" id="studentForm">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="matric_no">Matric Number: *</label>
                        <input type="text" id="matric_no" name="matric_no" class="form-control" required
                               value="<?php echo isset($_POST['matric_no']) ? h($_POST['matric_no']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="ic_no">IC Number: *</label>
                        <input type="text" id="ic_no" name="ic_no" class="form-control" required
                               value="<?php echo isset($_POST['ic_no']) ? h($_POST['ic_no']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name: *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" required
                           value="<?php echo isset($_POST['full_name']) ? h($_POST['full_name']) : ''; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email: *</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?php echo isset($_POST['email']) ? h($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                               value="<?php echo isset($_POST['phone']) ? h($_POST['phone']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                               value="<?php echo isset($_POST['date_of_birth']) ? h($_POST['date_of_birth']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender: *</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo isset($_POST['address']) ? h($_POST['address']) : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="program">Program: *</label>
                        <input type="text" id="program" name="program" class="form-control" required
                               value="<?php echo isset($_POST['program']) ? h($_POST['program']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="year_of_study">Year of Study: *</label>
                        <select id="year_of_study" name="year_of_study" class="form-control" required>
                            <option value="">Select Year</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" 
                                    <?php echo (isset($_POST['year_of_study']) && $_POST['year_of_study'] == $i) ? 'selected' : ''; ?>>
                                    Year <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Add Student</button>
                    <a href="students.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
