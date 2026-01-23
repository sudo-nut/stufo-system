<?php
/**
 * login.php
 * User login page with authentication
 */
require_once 'db.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Query user from database using prepared statement
        $stmt = $conn->prepare("SELECT user_id, username, password, full_name, role FROM USER WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        
        $stmt->close();
    }
}

$page_title = 'Login';
include 'header.php';
?>

<div class="login-container">
    <div class="login-box">
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo h($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo h($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        
        <div class="login-info">
            <p><strong>Default Credentials:</strong></p>
            <p>Admin - Username: <code>admin</code>, Password: <code>admin123</code></p>
            <p>Student - Username: <code>student1</code>, Password: <code>student123</code></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
