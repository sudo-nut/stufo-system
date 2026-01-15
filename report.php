<?php
/**
 * Student Information System - Report Generation
 * Generate PDF/HTML reports of all students
 */

require_once 'config.php';
require_once 'students.php';

startSecureSession();

$format = $_GET['format'] ?? 'html';
$students = getAllStudents();

if ($format === 'csv') {
    // Generate CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="student_report_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, ['Matric No', 'IC No', 'Name', 'Email', 'Phone', 'Address', 'Program', 'Year of Study', 'Registered Date']);
    
    // CSV data
    foreach ($students as $student) {
        fputcsv($output, [
            $student['matric_no'],
            $student['ic_no'],
            $student['name'],
            $student['email'],
            $student['phone'],
            $student['address'],
            $student['program'],
            $student['year_of_study'],
            date('Y-m-d', strtotime($student['created_at']))
        ]);
    }
    
    fclose($output);
    exit();
}

// HTML format
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report - Student Information System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .container { max-width: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="no-print">
            <h1>Student Report</h1>
            <nav>
                <a href="index.php" class="btn btn-secondary">Back to List</a>
                <?php if (isLoggedIn()): ?>
                    <a href="admin.php" class="btn btn-primary">Admin Dashboard</a>
                <?php endif; ?>
                <button onclick="window.print()" class="btn btn-primary">Print Report</button>
                <a href="?format=csv" class="btn btn-success">Download CSV</a>
            </nav>
        </header>

        <div class="section report">
            <div class="report-header">
                <h1>Student Information System</h1>
                <h2>Complete Student List Report</h2>
                <p>Generated on: <?php echo date('F j, Y g:i A'); ?></p>
                <p>Total Students: <?php echo count($students); ?></p>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Matric No</th>
                        <th>IC No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Program</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No students found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo h($student['matric_no']); ?></td>
                                <td><?php echo h($student['ic_no']); ?></td>
                                <td><?php echo h($student['name']); ?></td>
                                <td><?php echo h($student['email']); ?></td>
                                <td><?php echo h($student['phone']); ?></td>
                                <td><?php echo h($student['program']); ?></td>
                                <td><?php echo h($student['year_of_study']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="report-footer">
                <p>End of Report</p>
            </div>
        </div>
    </div>
</body>
</html>
