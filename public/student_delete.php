<?php
/**
 * Delete Student Page
 * 
 * Admin-only page for deleting student records.
 * Includes CSRF protection and POST-only deletion.
 */

$page_title = 'Delete Student';
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

// Fetch student data
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

// Handle delete confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_token)) {
        $errors[] = 'Invalid CSRF token. Please try again.';
    } else {
        // Delete the student
        $delete_stmt = $conn->prepare("DELETE FROM STUDENT WHERE student_id = ?");
        $delete_stmt->bind_param("i", $student_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = 'Student deleted successfully!';
            $delete_stmt->close();
            header('Location: students.php');
            exit;
        } else {
            $errors[] = 'Database error: ' . $delete_stmt->error;
            $delete_stmt->close();
        }
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h1>Delete Student</h1>
    <p>Confirm deletion of student record</p>
</div>

<div class="content-section">
    <div class="form-container">
        <div class="card">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="alert alert-warning">
                <strong>Warning:</strong> You are about to delete this student record. This action cannot be undone.
            </div>
            
            <div class="student-details">
                <h3>Student Information:</h3>
                
                <div class="detail-row">
                    <div class="detail-label">Matric Number:</div>
                    <div class="detail-value"><?php echo h($student['matric_no']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">IC Number:</div>
                    <div class="detail-value"><?php echo h($student['ic_no']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Full Name:</div>
                    <div class="detail-value"><?php echo h($student['full_name']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value"><?php echo h($student['email']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Program:</div>
                    <div class="detail-value"><?php echo h($student['program']); ?></div>
                </div>
            </div>
            
            <form method="POST" action="student_delete.php?id=<?php echo $student_id; ?>" class="form">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you absolutely sure you want to delete this student? This action cannot be undone.');">
                        Yes, Delete Student
                    </button>
                    <a href="student_view.php?id=<?php echo $student_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
