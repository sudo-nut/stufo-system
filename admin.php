<?php
/**
 * Student Information System - Admin Dashboard
 * CRUD operations with CSRF protection
 */

require_once 'config.php';
require_once 'students.php';

startSecureSession();
requireLogin();

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('CSRF token validation failed');
    }
    
    if (isset($_POST['add_student'])) {
        $result = addStudent(
            $_POST['matric_no'],
            $_POST['ic_no'],
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['program'],
            $_POST['year_of_study']
        );
        
        if ($result) {
            $message = 'Student added successfully';
            $action = 'list';
        } else {
            $error = 'Failed to add student';
        }
    } elseif (isset($_POST['update_student'])) {
        $result = updateStudent(
            $_POST['id'],
            $_POST['matric_no'],
            $_POST['ic_no'],
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['program'],
            $_POST['year_of_study']
        );
        
        if ($result) {
            $message = 'Student updated successfully';
            $action = 'list';
        } else {
            $error = 'Failed to update student';
        }
    } elseif (isset($_POST['delete_student'])) {
        $result = deleteStudent($_POST['id']);
        
        if ($result) {
            $message = 'Student deleted successfully';
            $action = 'list';
        } else {
            $error = 'Failed to delete student';
        }
    }
}

// Get student for edit
$student = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $student = getStudentById($_GET['id']);
    if (!$student) {
        $error = 'Student not found';
        $action = 'list';
    }
}

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
    <title>Admin Dashboard - Student Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Information System - Admin Dashboard</h1>
            <nav>
                <a href="index.php" class="btn btn-secondary">View Public List</a>
                <a href="report.php" class="btn btn-secondary">Generate Report</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </nav>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo h($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo h($error); ?></div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <div class="section">
                <h2>Student List</h2>
                <div class="actions">
                    <a href="?action=add" class="btn btn-primary">Add New Student</a>
                </div>
                
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search by name, matric number, or IC number..." 
                           value="<?php echo h($search); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if ($search): ?>
                        <a href="?" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>

                <p>Total students: <?php echo $total_students; ?></p>

                <table>
                    <thead>
                        <tr>
                            <th>Matric No</th>
                            <th>IC No</th>
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
                                <td colspan="7" class="text-center">No students found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><?php echo h($s['matric_no']); ?></td>
                                    <td><?php echo h($s['ic_no']); ?></td>
                                    <td><?php echo h($s['name']); ?></td>
                                    <td><?php echo h($s['email']); ?></td>
                                    <td><?php echo h($s['program']); ?></td>
                                    <td><?php echo h($s['year_of_study']); ?></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=<?php echo $s['id']; ?>" class="btn btn-small">View</a>
                                        <a href="?action=edit&id=<?php echo $s['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                            <button type="submit" name="delete_student" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-secondary">Previous</a>
                        <?php endif; ?>
                        
                        <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-secondary">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="section">
                <h2><?php echo $action === 'add' ? 'Add New Student' : 'Edit Student'; ?></h2>
                <a href="?" class="btn btn-secondary">Back to List</a>
                
                <form method="POST" class="form">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo h($student['id']); ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="matric_no">Matric Number: *</label>
                        <input type="text" id="matric_no" name="matric_no" 
                               value="<?php echo $student ? h($student['matric_no']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ic_no">IC Number: *</label>
                        <input type="text" id="ic_no" name="ic_no" 
                               value="<?php echo $student ? h($student['ic_no']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Full Name: *</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $student ? h($student['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $student ? h($student['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" 
                               value="<?php echo $student ? h($student['phone']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3"><?php echo $student ? h($student['address']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="program">Program: *</label>
                        <input type="text" id="program" name="program" 
                               value="<?php echo $student ? h($student['program']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="year_of_study">Year of Study: *</label>
                        <input type="number" id="year_of_study" name="year_of_study" min="1" max="6"
                               value="<?php echo $student ? h($student['year_of_study']) : ''; ?>" required>
                    </div>
                    
                    <button type="submit" name="<?php echo $action === 'add' ? 'add_student' : 'update_student'; ?>" 
                            class="btn btn-primary">
                        <?php echo $action === 'add' ? 'Add Student' : 'Update Student'; ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
