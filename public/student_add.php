<?php
/**
 * student_add.php
 * Add new student (Admin only with CSRF protection)
 */
require_once 'db.php';

// Require admin access
require_admin();

$page_title = 'Add Student';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token. Please try again.';
    } else {
        // Get form data
        $matric_no = trim($_POST['matric_no'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $ic_no = trim($_POST['ic_no'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $programme = trim($_POST['programme'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $semester = (int)($_POST['semester'] ?? 0);
        $email = trim($_POST['email'] ?? '');
        $phone_no = trim($_POST['phone_no'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        // Validate required fields
        if (empty($matric_no) || empty($name) || empty($ic_no) || empty($gender) || 
            empty($programme) || empty($faculty) || $semester < 1) {
            $error = 'Please fill in all required fields.';
        } else {
            // Insert student using prepared statement
            $stmt = $conn->prepare("INSERT INTO STUDENT (matric_no, name, ic_no, gender, programme, faculty, semester, email, phone_no, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $matric_no, $name, $ic_no, $gender, $programme, $faculty, $semester, $email, $phone_no, $address);
            
            if ($stmt->execute()) {
                $success = 'Student added successfully!';
                $new_student_id = $stmt->insert_id;
                
                // Redirect to view page after short delay
                header("refresh:2;url=student_view.php?id=$new_student_id");
            } else {
                if ($conn->errno === 1062) { // Duplicate entry error
                    $error = 'Matric number or IC number already exists.';
                } else {
                    $error = 'Error adding student: ' . $stmt->error;
                }
            }
            
            $stmt->close();
        }
    }
}

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h1>Add New Student</h1>
        <a href="students.php" class="btn btn-secondary">Back to List</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo h($success); ?> Redirecting...</div>
    <?php endif; ?>
    
    <form method="POST" action="student_add.php" class="student-form">
        <input type="hidden" name="csrf_token" value="<?php echo h(generate_csrf_token()); ?>">
        
        <div class="form-section">
            <h3>Personal Information</h3>
            
            <div class="form-group">
                <label for="matric_no">Matric Number: <span class="required">*</span></label>
                <input type="text" id="matric_no" name="matric_no" required 
                       value="<?php echo h($_POST['matric_no'] ?? ''); ?>" 
                       placeholder="e.g., A000001">
            </div>
            
            <div class="form-group">
                <label for="name">Full Name: <span class="required">*</span></label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo h($_POST['name'] ?? ''); ?>" 
                       placeholder="e.g., Muhammad Ali Abdullah">
            </div>
            
            <div class="form-group">
                <label for="ic_no">IC Number: <span class="required">*</span></label>
                <input type="text" id="ic_no" name="ic_no" required 
                       value="<?php echo h($_POST['ic_no'] ?? ''); ?>" 
                       placeholder="e.g., 990101-12-1234">
            </div>
            
            <div class="form-group">
                <label for="gender">Gender: <span class="required">*</span></label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <h3>Academic Information</h3>
            
            <div class="form-group">
                <label for="programme">Programme: <span class="required">*</span></label>
                <input type="text" id="programme" name="programme" required 
                       value="<?php echo h($_POST['programme'] ?? ''); ?>" 
                       placeholder="e.g., Computer Science">
            </div>
            
            <div class="form-group">
                <label for="faculty">Faculty: <span class="required">*</span></label>
                <input type="text" id="faculty" name="faculty" required 
                       value="<?php echo h($_POST['faculty'] ?? ''); ?>" 
                       placeholder="e.g., Faculty of Computing">
            </div>
            
            <div class="form-group">
                <label for="semester">Semester: <span class="required">*</span></label>
                <input type="number" id="semester" name="semester" required min="1" max="14" 
                       value="<?php echo h($_POST['semester'] ?? ''); ?>" 
                       placeholder="e.g., 1">
            </div>
        </div>
        
        <div class="form-section">
            <h3>Contact Information</h3>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo h($_POST['email'] ?? ''); ?>" 
                       placeholder="e.g., student@example.com">
            </div>
            
            <div class="form-group">
                <label for="phone_no">Phone Number:</label>
                <input type="text" id="phone_no" name="phone_no" 
                       value="<?php echo h($_POST['phone_no'] ?? ''); ?>" 
                       placeholder="e.g., 012-3456789">
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" 
                          placeholder="e.g., 123 Jalan Example, 50000 Kuala Lumpur"><?php echo h($_POST['address'] ?? ''); ?></textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="students.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
