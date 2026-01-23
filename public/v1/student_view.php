<?php
/**
 * student_view.php
 * View detailed information of a single student
 */
require_once 'db.php';

// Require user to be logged in
require_login();

$page_title = 'View Student';
$error = '';
$student = null;

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id > 0) {
    // Fetch student details using prepared statement
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
        <h1>Student Details</h1>
        <div>
            <a href="students.php" class="btn btn-secondary">Back to List</a>
            <?php if (is_admin() && $student): ?>
                <a href="student_edit.php?id=<?php echo $student['student_id']; ?>" class="btn btn-warning">Edit</a>
                <a href="student_delete.php?id=<?php echo $student['student_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo h($error); ?></div>
        <a href="students.php" class="btn btn-primary">Return to Student List</a>
    <?php elseif ($student): ?>
        <div class="student-details">
            <div class="detail-group">
                <h3>Personal Information</h3>
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
            </div>
            
            <div class="detail-group">
                <h3>Academic Information</h3>
                <div class="detail-row">
                    <label>Programme:</label>
                    <span><?php echo h($student['programme']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Faculty:</label>
                    <span><?php echo h($student['faculty']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Semester:</label>
                    <span><?php echo h($student['semester']); ?></span>
                </div>
            </div>
            
            <div class="detail-group">
                <h3>Contact Information</h3>
                <div class="detail-row">
                    <label>Email:</label>
                    <span><?php echo h($student['email'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <label>Phone Number:</label>
                    <span><?php echo h($student['phone_no'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <label>Address:</label>
                    <span><?php echo h($student['address'] ?? 'N/A'); ?></span>
                </div>
            </div>
            
            <div class="detail-group">
                <h3>System Information</h3>
                <div class="detail-row">
                    <label>Student ID:</label>
                    <span><?php echo h($student['student_id']); ?></span>
                </div>
                <div class="detail-row">
                    <label>Created At:</label>
                    <span><?php echo h($student['created_at']); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
