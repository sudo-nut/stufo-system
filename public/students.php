<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

requireLogin();
$isAdmin = isAdmin();

// Helper to bind params using call_user_func_array (mysqli requires references)
function bind_params_safe(mysqli_stmt $stmt, string $types, array $params) {
    // Build an array where the first element is the types string,
    // and the following elements are references to the parameters.
    $bindNames = [];
    $bindNames[] = $types;
    // params must be referenced
    foreach ($params as $key => $value) {
        $bindNames[] = &$params[$key];
    }
    return call_user_func_array([$stmt, 'bind_param'], $bindNames);
}

// Parameters
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Build search query - search in FULLNAME, matric_no, ic_no (case-insensitive)
$where = '1';
$params = [];
$types = '';
if ($search !== '') {
    $where = "(FULLNAME LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?)";
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
    $types = 'sss';
}

// Count total
$countSql = "SELECT COUNT(*) AS cnt FROM `STUDENT` WHERE $where";
$stmt = $conn->prepare($countSql);
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}
if ($types) {
    // bind params safely
    if (!bind_params_safe($stmt, $types, $params)) {
        die('Bind failed: ' . $stmt->error);
    }
}
$stmt->execute();
$res = $stmt->get_result();
$total = (int)$res->fetch_assoc()['cnt'];
$stmt->close();

$totalPages = (int)ceil($total / $perPage);
if ($totalPages < 1) $totalPages = 1;

// Fetch page
$listSql = "SELECT STUDENTID, matric_no, FULLNAME, programme, faculty, semester, CONTACTNO, EMAIL FROM `STUDENT` WHERE $where ORDER BY FULLNAME ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($listSql);
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

if ($types) {
    // We need to bind ($params..., $perPage, $offset) with types $types . 'ii'
    $typesWithInts = $types . 'ii';
    // append perPage and offset as additional params
    $allParams = $params;
    $allParams[] = $perPage;
    $allParams[] = $offset;
    if (!bind_params_safe($stmt, $typesWithInts, $allParams)) {
        die('Bind failed: ' . $stmt->error);
    }
} else {
    // bind only the two ints
    $stmt->bind_param('ii', $perPage, $offset);
}

$stmt->execute();
$res = $stmt->get_result();

?>
<h1>Students</h1>

<form method="get" action="students.php" class="search-form">
  <input type="text" name="q" id="q" placeholder="Search by name, matric_no or ic_no" value="<?= htmlspecialchars($search) ?>">
  <button type="submit">Search</button>
  <?php if ($isAdmin): ?>
    <a class="btn" href="student_add.php">Add Student</a>
  <?php endif; ?>
</form>

<table class="table">
  <thead>
    <tr>
      <th>Matric</th>
      <th>Fullname</th>
      <th>Programme</th>
      <th>Faculty</th>
      <th>Sem</th>
      <th>Contact</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
<?php while ($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['matric_no']) ?></td>
      <td><?= htmlspecialchars($row['FULLNAME']) ?></td>
      <td><?= htmlspecialchars($row['programme']) ?></td>
      <td><?= htmlspecialchars($row['faculty']) ?></td>
      <td><?= htmlspecialchars($row['semester']) ?></td>
      <td><?= htmlspecialchars($row['CONTACTNO']) ?></td>
      <td>
        <a href="student_view.php?id=<?= $row['STUDENTID'] ?>">View</a>
        <?php if ($isAdmin): ?>
          | <a href="student_edit.php?id=<?= $row['STUDENTID'] ?>">Edit</a>
          | <form method="post" action="student_delete.php" class="inline-form" onsubmit="return confirmDelete();" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['STUDENTID'] ?>">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              <button type="submit">Delete</button>
            </form>
        <?php endif; ?>
      </td>
    </tr>
<?php endwhile; ?>
  </tbody>
</table>

<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?q=<?= urlencode($search) ?>&page=<?= $page-1 ?>">Prev</a>
  <?php endif; ?>
  <span>Page <?= $page ?> / <?= $totalPages ?></span>
  <?php if ($page < $totalPages): ?>
    <a href="?q=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Next</a>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>