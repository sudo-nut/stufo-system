<?php
/**
 * students.php
 * List all students with pagination (20 per page)
 */
require_once 'db.php';

// Require user to be logged in
require_login();

$page_title = 'Students';

// Pagination configuration
$records_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page); // Ensure page is at least 1

// Calculate offset
$offset = ($current_page - 1) * $records_per_page;

// Get total number of students
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM STUDENT");
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$count_stmt->close();

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);

// Get students for current page using prepared statement
$stmt = $conn->prepare("SELECT student_id, matric_no, name, ic_no, gender, programme, faculty, semester FROM STUDENT ORDER BY matric_no ASC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h1>Student Records</h1>
        <?php if (is_admin()): ?>
            <a href="student_add.php" class="btn btn-primary">Add New Student</a>
        <?php endif; ?>
    </div>
    
    <div class="stats-info">
        <p>Total Students: <strong><?php echo $total_records; ?></strong> | 
           Page <strong><?php echo $current_page; ?></strong> of <strong><?php echo $total_pages; ?></strong></p>
    </div>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="student-table">
                <thead>
                    <tr>
                        <th>Matric No</th>
                        <th>Name</th>
                        <th>IC No</th>
                        <th>Gender</th>
                        <th>Programme</th>
                        <th>Faculty</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo h($student['matric_no']); ?></td>
                            <td><?php echo h($student['name']); ?></td>
                            <td><?php echo h($student['ic_no']); ?></td>
                            <td><?php echo h($student['gender']); ?></td>
                            <td><?php echo h($student['programme']); ?></td>
                            <td><?php echo h($student['faculty']); ?></td>
                            <td><?php echo h($student['semester']); ?></td>
                            <td class="actions">
                                <a href="student_view.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-info">View</a>
                                <?php if (is_admin()): ?>
                                    <a href="student_edit.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="student_delete.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=1" class="btn btn-sm">First</a>
                    <a href="?page=<?php echo $current_page - 1; ?>" class="btn btn-sm">Previous</a>
                <?php endif; ?>
                
                <?php
                // Show page numbers
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                    if ($i == $current_page):
                ?>
                    <span class="btn btn-sm btn-primary"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>" class="btn btn-sm"><?php echo $i; ?></a>
                <?php
                    endif;
                endfor;
                ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>" class="btn btn-sm">Next</a>
                    <a href="?page=<?php echo $total_pages; ?>" class="btn btn-sm">Last</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No students found in the database.</div>
    <?php endif; ?>
</div>

<?php
$stmt->close();
include 'footer.php';
?>
