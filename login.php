<?php
// login.php - Login page
session_start();
require_once 'config.php';
require_once 'auth.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($pdo, $username, $password)) {
            header('Location: listen_log.php');
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Music Log & Rating System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="auth-container">
        <h1 class="auth-title">Login</h1>
        
        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="auth-button">Login</button>
        </form>
        <p class="auth-text">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>
