<?php
/**
 * Student Information System - View Student Details
 */

require_once 'config.php';
require_once 'students.php';

startSecureSession();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$student = getStudentById($id);

if (!$student) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - <?php echo h($student['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Details</h1>
            <nav>
                <a href="index.php" class="btn btn-secondary">Back to List</a>
                <?php if (isLoggedIn()): ?>
                    <a href="admin.php" class="btn btn-primary">Admin Dashboard</a>
                <?php endif; ?>
            </nav>
        </header>

        <div class="section">
            <div class="student-details">
                <h2><?php echo h($student['name']); ?></h2>
                
                <div class="detail-row">
                    <strong>Matric Number:</strong>
                    <span><?php echo h($student['matric_no']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>IC Number:</strong>
                    <span><?php echo h($student['ic_no']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Email:</strong>
                    <span><?php echo h($student['email']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Phone:</strong>
                    <span><?php echo h($student['phone']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Address:</strong>
                    <span><?php echo h($student['address']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Program:</strong>
                    <span><?php echo h($student['program']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Year of Study:</strong>
                    <span><?php echo h($student['year_of_study']); ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Registered:</strong>
                    <span><?php 
                        $created_timestamp = strtotime($student['created_at']);
                        echo $created_timestamp ? date('F j, Y', $created_timestamp) : 'N/A';
                    ?></span>
                </div>
                
                <div class="detail-row">
                    <strong>Last Updated:</strong>
                    <span><?php 
                        $updated_timestamp = strtotime($student['updated_at']);
                        echo $updated_timestamp ? date('F j, Y', $updated_timestamp) : 'N/A';
                    ?></span>
                </div>
            </div>

            <?php if (isLoggedIn()): ?>
                <div class="actions" style="margin-top: 20px;">
                    <a href="admin.php?action=edit&id=<?php echo $student['id']; ?>" class="btn btn-primary">Edit Student</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
