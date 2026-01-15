<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err = 'Username and password are required.';
    } else {
        $stmt = $conn->prepare('SELECT user_id, username, password, role FROM `USER` WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($user = $res->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: students.php');
                exit();
            } else {
                $err = 'Invalid credentials.';
            }
        } else {
            $err = 'Invalid credentials.';
        }
        $stmt->close();
    }
}
?>
<h1>Login</h1>
<?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<form method="post" action="login.php" class="form">
  <label>Username
    <input type="text" name="username" required>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button type="submit">Login</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>