<?php
/**
 * student_delete.php
 * Delete student (Admin only with CSRF protection)
 */
require_once 'db.php';

// Require admin access
require_admin();

$page_title = 'Delete Student';
$error = '';
$success = '';
$student = null;

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token. Please try again.';
    } else {
        $student_id = (int)$_POST['student_id'];
        
        // Delete student using prepared statement
        $stmt = $conn->prepare("DELETE FROM STUDENT WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = 'Student deleted successfully!';
                // Redirect to students list after short delay
                header("refresh:2;url=students.php");
            } else {
                $error = 'Student not found or already deleted.';
            }
        } else {
            $error = 'Error deleting student: ' . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Fetch student details for confirmation
if ($student_id > 0 && !$success) {
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
} else if ($student_id <= 0) {
    $error = 'Invalid student ID.';
}

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h1>Delete Student</h1>
        <a href="students.php" class="btn btn-secondary">Back to List</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo h($error); ?></div>
        <a href="students.php" class="btn btn-primary">Return to Student List</a>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo h($success); ?> Redirecting...</div>
    <?php endif; ?>
    
    <?php if ($student && !$success): ?>
        <div class="alert alert-warning">
            <strong>Warning:</strong> You are about to delete the following student record. This action cannot be undone!
        </div>
        
        <div class="student-details">
            <div class="detail-group">
                <h3>Student Information</h3>
                <div class="detail-row">
                    <label>Matric Number:</label>
                    <span><?php echo h($student['matric_no']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Full Name:</label>
                    <span><?php echo h($student['name']); ?></span>
                </div>
                <div class="detail-row">
                    <label>IC Number:</label>
                    <span><?php echo h($student['ic_no']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Gender:</label>
                    <span><?php echo h($student['gender']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Programme:</label>
                    <span><?php echo h($student['programme']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Faculty:</label>
                    <span><?php echo h($student['faculty']); ?></span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="student_delete.php?id=<?php echo $student['student_id']; ?>" class="delete-form">
            <input type="hidden" name="csrf_token" value="<?php echo h(generate_csrf_token()); ?>">
            <input type="hidden" name="student_id" value="<?php echo h($student['student_id']); ?>">
            
            <div class="form-actions">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you absolutely sure you want to delete this student? This cannot be undone!');">Confirm Delete</button>
                <a href="student_view.php?id=<?php echo $student['student_id']; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
