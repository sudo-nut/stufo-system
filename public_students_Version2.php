<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

requireLogin();
$isAdmin = isAdmin();

// Parameters
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Build search query - search in name, matric_no, ic_no (case-insensitive)
$where = '1';
$params = [];
$types = '';
if ($search !== '') {
    $where = "(name LIKE ? OR matric_no LIKE ? OR ic_no LIKE ?)";
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
    $types = 'sss';
}

// Count total
$countSql = "SELECT COUNT(*) AS cnt FROM `STUDENT` WHERE $where";
$stmt = $conn->prepare($countSql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
$total = $res->fetch_assoc()['cnt'];
$stmt->close();

$totalPages = (int)ceil($total / $perPage);

// Fetch page
$listSql = "SELECT student_id, matric_no, name, programme, faculty, semester FROM `STUDENT` WHERE $where ORDER BY name ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($listSql);
if ($types) {
    // bind search + integers
    $stmt->bind_param($types . 'ii', ...$params, $perPage, $offset);
} else {
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
      <th>Name</th>
      <th>Programme</th>
      <th>Faculty</th>
      <th>Sem</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
<?php while ($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['matric_no']) ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['programme']) ?></td>
      <td><?= htmlspecialchars($row['faculty']) ?></td>
      <td><?= htmlspecialchars($row['semester']) ?></td>
      <td>
        <a href="student_view.php?id=<?= $row['student_id'] ?>">View</a>
        <?php if ($isAdmin): ?>
          | <a href="student_edit.php?id=<?= $row['student_id'] ?>">Edit</a>
          | <form method="post" action="student_delete.php" class="inline-form" onsubmit="return confirmDelete();" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['student_id'] ?>">
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