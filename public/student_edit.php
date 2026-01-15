<?php
/**
 * student_edit.php
 * Edit existing student (Admin only with CSRF protection)
 */
require_once 'db.php';

// Require admin access
require_admin();

$page_title = 'Edit Student';
$error = '';
$success = '';
$student = null;

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token. Please try again.';
    } else {
        $student_id = (int)$_POST['student_id'];
        
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
            // Update student using prepared statement
            $stmt = $conn->prepare("UPDATE STUDENT SET matric_no = ?, name = ?, ic_no = ?, gender = ?, programme = ?, faculty = ?, semester = ?, email = ?, phone_no = ?, address = ? WHERE student_id = ?");
            $stmt->bind_param("ssssssisssi", $matric_no, $name, $ic_no, $gender, $programme, $faculty, $semester, $email, $phone_no, $address, $student_id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $success = 'Student updated successfully!';
                } else {
                    $success = 'No changes were made.';
                }
                
                // Redirect to view page after short delay
                header("refresh:2;url=student_view.php?id=$student_id");
            } else {
                if ($conn->errno === 1062) { // Duplicate entry error
                    $error = 'Matric number or IC number already exists.';
                } else {
                    $error = 'Error updating student: ' . $stmt->error;
                }
            }
            
            $stmt->close();
        }
    }
}

// Fetch student details for the form
if ($student_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM STUDENT WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
    } else {
        $error = 'Student not found.';
    }
    
    $stmt->close();
} else {
    $error = 'Invalid student ID.';
}

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h1>Edit Student</h1>
        <div>
            <a href="students.php" class="btn btn-secondary">Back to List</a>
            <?php if ($student): ?>
                <a href="student_view.php?id=<?php echo $student['student_id']; ?>" class="btn btn-info">View Details</a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo h($error); ?></div>
        <?php if (!$student): ?>
            <a href="students.php" class="btn btn-primary">Return to Student List</a>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo h($success); ?> Redirecting...</div>
    <?php endif; ?>
    
    <?php if ($student): ?>
        <form method="POST" action="student_edit.php?id=<?php echo $student['student_id']; ?>" class="student-form">
            <input type="hidden" name="csrf_token" value="<?php echo h(generate_csrf_token()); ?>">
            <input type="hidden" name="student_id" value="<?php echo h($student['student_id']); ?>">
            
            <div class="form-section">
                <h3>Personal Information</h3>
                
                <div class="form-group">
                    <label for="matric_no">Matric Number: <span class="required">*</span></label>
                    <input type="text" id="matric_no" name="matric_no" required 
                           value="<?php echo h($student['matric_no']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="name">Full Name: <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo h($student['name']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="ic_no">IC Number: <span class="required">*</span></label>
                    <input type="text" id="ic_no" name="ic_no" required 
                           value="<?php echo h($student['ic_no']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender: <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Academic Information</h3>
                
                <div class="form-group">
                    <label for="programme">Programme: <span class="required">*</span></label>
                    <input type="text" id="programme" name="programme" required 
                           value="<?php echo h($student['programme']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="faculty">Faculty: <span class="required">*</span></label>
                    <input type="text" id="faculty" name="faculty" required 
                           value="<?php echo h($student['faculty']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="semester">Semester: <span class="required">*</span></label>
                    <input type="number" id="semester" name="semester" required min="1" max="14" 
                           value="<?php echo h($student['semester']); ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h3>Contact Information</h3>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo h($student['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone_no">Phone Number:</label>
                    <input type="text" id="phone_no" name="phone_no" 
                           value="<?php echo h($student['phone_no']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3"><?php echo h($student['address']); ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="student_view.php?id=<?php echo $student['student_id']; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
