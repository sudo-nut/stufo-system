<?php
/**
 * Students Listing Page
 * 
 * Displays a searchable and paginated list of students.
 * Includes server-side search by name, matric_no, or ic_no.
 * Shows Add/Edit/Delete buttons for admin users only.
 */

$page_title = 'Students';
require_once 'header.php';
require_once 'db.php';

// Pagination settings
$records_per_page = 20;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $records_per_page;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = '%' . $search . '%';

// Build query with search filter
if (!empty($search)) {
    // Search across name, matric_no, and ic_no
    $count_sql = "SELECT COUNT(*) as total FROM STUDENT 
                  WHERE full_name LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $count_stmt->execute();
    $total_records = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
    
    $students_sql = "SELECT student_id, matric_no, ic_no, full_name, email, phone, program, year_of_study 
                     FROM STUDENT 
                     WHERE full_name LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?
                     ORDER BY full_name ASC
                     LIMIT ? OFFSET ?";
    $students_stmt = $conn->prepare($students_sql);
    $students_stmt->bind_param("sssii", $search_param, $search_param, $search_param, $records_per_page, $offset);
} else {
    // No search filter
    $count_sql = "SELECT COUNT(*) as total FROM STUDENT";
    $count_result = $conn->query($count_sql);
    $total_records = $count_result->fetch_assoc()['total'];
    
    $students_sql = "SELECT student_id, matric_no, ic_no, full_name, email, phone, program, year_of_study 
                     FROM STUDENT 
                     ORDER BY full_name ASC
                     LIMIT ? OFFSET ?";
    $students_stmt = $conn->prepare($students_sql);
    $students_stmt->bind_param("ii", $records_per_page, $offset);
}

$students_stmt->execute();
$students_result = $students_stmt->get_result();
$students = $students_result->fetch_all(MYSQLI_ASSOC);
$students_stmt->close();

// Calculate pagination
$total_pages = ceil($total_records / $records_per_page);
$showing_from = $total_records > 0 ? $offset + 1 : 0;
$showing_to = min($offset + $records_per_page, $total_records);
?>

<div class="page-header">
    <h1>Students</h1>
    <p>Browse and search student records</p>
</div>

<div class="content-section">
    <div class="toolbar">
        <div class="search-box">
            <form method="GET" action="students.php" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    id="search" 
                    placeholder="Search by name, matric no, or IC no..." 
                    class="search-input"
                    value="<?php echo h($search); ?>"
                >
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="students.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if (isAdmin()): ?>
            <div class="toolbar-actions">
                <a href="student_add.php" class="btn btn-success">+ Add Student</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="results-info">
        Showing <?php echo $showing_from; ?> to <?php echo $showing_to; ?> of <?php echo $total_records; ?> students
        <?php if (!empty($search)): ?>
            (filtered by: "<?php echo h($search); ?>")
        <?php endif; ?>
    </div>
    
    <?php if (count($students) > 0): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Matric No</th>
                        <th>IC No</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo h($student['matric_no']); ?></td>
                            <td><?php echo h($student['ic_no']); ?></td>
                            <td><?php echo h($student['full_name']); ?></td>
                            <td><?php echo h($student['email']); ?></td>
                            <td><?php echo h($student['phone']); ?></td>
                            <td><?php echo h($student['program']); ?></td>
                            <td><?php echo h($student['year_of_study']); ?></td>
                            <td class="actions">
                                <a href="student_view.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-info">View</a>
                                <?php if (isAdmin()): ?>
                                    <a href="student_edit.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="student_delete.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                // Build search query string for pagination links
                $query_string = !empty($search) ? '&search=' . urlencode($search) : '';
                
                // Previous button
                if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?><?php echo $query_string; ?>" class="btn btn-sm">« Previous</a>
                <?php endif; ?>
                
                <?php
                // Page numbers
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                if ($start_page > 1): ?>
                    <a href="?page=1<?php echo $query_string; ?>" class="btn btn-sm">1</a>
                    <?php if ($start_page > 2): ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $query_string; ?>" 
                       class="btn btn-sm <?php echo $i === $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $total_pages; ?><?php echo $query_string; ?>" class="btn btn-sm"><?php echo $total_pages; ?></a>
                <?php endif; ?>
                
                <?php // Next button
                if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?><?php echo $query_string; ?>" class="btn btn-sm">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <?php if (!empty($search)): ?>
                No students found matching your search criteria.
            <?php else: ?>
                No students in the database. <?php if (isAdmin()): ?><a href="student_add.php">Add a student</a> to get started.<?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
