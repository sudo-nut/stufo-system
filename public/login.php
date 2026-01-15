<?php
/**
 * Login Page
 * 
 * Handles user authentication for both admin and student roles.
 * Uses password_verify for secure password checking.
 */

$page_title = 'Login';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: students.php');
    exit;
}

require_once 'db.php';

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Query user from database
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
                
                // Redirect to originally requested page or students page
                $redirect = $_SESSION['redirect_after_login'] ?? 'students.php';
                unset($_SESSION['redirect_after_login']);
                
                header('Location: ' . $redirect);
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

require_once 'header.php';
?>

<div class="page-header">
    <h1>Login</h1>
    <p>Please enter your credentials to access the system</p>
</div>

<div class="content-section">
    <div class="login-container">
        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php" class="form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control" 
                        required 
                        autofocus
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required
                    >
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            <div class="login-help">
                <h3>Default Credentials</h3>
                <p><strong>Admin:</strong> admin01 / admin123</p>
                <p><strong>Student:</strong> student01 / student123</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
