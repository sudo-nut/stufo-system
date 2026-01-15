<?php
/**
 * Student Information System - Public Student List
 * View with pagination and search
 */

require_once 'config.php';
require_once 'students.php';

startSecureSession();

// Pagination and search
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$total_students = getStudentCount($search);
$total_pages = ceil($total_students / $per_page);
$students = getStudents($per_page, $offset, $search);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - Student Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Information System</h1>
            <nav>
                <?php if (isLoggedIn()): ?>
                    <a href="admin.php" class="btn btn-primary">Admin Dashboard</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Admin Login</a>
                <?php endif; ?>
                <a href="report.php" class="btn btn-secondary">Generate Report</a>
            </nav>
        </header>

        <div class="section">
            <h2>Student List</h2>
            
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by name, matric number, or IC number..." 
                       value="<?php echo h($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if ($search): ?>
                    <a href="index.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>

            <p>Total students: <?php echo $total_students; ?></p>

            <table>
                <thead>
                    <tr>
                        <th>Matric No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No students found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td><?php echo h($s['matric_no']); ?></td>
                                <td><?php echo h($s['name']); ?></td>
                                <td><?php echo h($s['email']); ?></td>
                                <td><?php echo h($s['program']); ?></td>
                                <td><?php echo h($s['year_of_study']); ?></td>
                                <td>
                                    <a href="view_student.php?id=<?php echo $s['id']; ?>" class="btn btn-small">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="btn btn-secondary">Previous</a>
                    <?php endif; ?>
                    
                    <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="btn btn-secondary">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
