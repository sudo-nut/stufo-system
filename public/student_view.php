<?php
/**
 * Student View Page
 * 
 * Displays detailed information for a single student.
 * Requires login to access.
 */

$page_title = 'View Student';
require_once 'header.php';
require_once 'db.php';

// Require login to view student details
requireLogin();

// Get student ID from query string
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    header('Location: students.php');
    exit;
}

// Fetch student details
$stmt = $conn->prepare(
    "SELECT * FROM STUDENT WHERE student_id = ?"
);
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
?>

<div class="page-header">
    <h1>Student Details</h1>
    <p>View complete student information</p>
</div>

<div class="content-section">
    <div class="card">
        <div class="card-header">
            <h2><?php echo h($student['full_name']); ?></h2>
            <div class="card-actions">
                <a href="students.php" class="btn btn-secondary">« Back to List</a>
                <?php if (isAdmin()): ?>
                    <a href="student_edit.php?id=<?php echo $student['student_id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="student_delete.php?id=<?php echo $student['student_id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="student-details">
            <div class="detail-row">
                <div class="detail-label">Student ID:</div>
                <div class="detail-value"><?php echo h($student['student_id']); ?></div>
            </div>
            
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
                <div class="detail-value">
                    <a href="mailto:<?php echo h($student['email']); ?>"><?php echo h($student['email']); ?></a>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Phone:</div>
                <div class="detail-value"><?php echo h($student['phone']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Date of Birth:</div>
                <div class="detail-value">
                    <?php 
                    if ($student['date_of_birth']) {
                        echo date('F d, Y', strtotime($student['date_of_birth']));
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Gender:</div>
                <div class="detail-value"><?php echo h($student['gender']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Program:</div>
                <div class="detail-value"><?php echo h($student['program']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Year of Study:</div>
                <div class="detail-value">Year <?php echo h($student['year_of_study']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Address:</div>
                <div class="detail-value"><?php echo nl2br(h($student['address'])); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Created At:</div>
                <div class="detail-value">
                    <?php echo date('F d, Y g:i A', strtotime($student['created_at'])); ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Updated At:</div>
                <div class="detail-value">
                    <?php echo date('F d, Y g:i A', strtotime($student['updated_at'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
