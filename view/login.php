<?php
require __DIR__ . '/../core/config.php';

$error = '';

// Start session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // 🔐 Regenerate session ID to prevent fixation
        session_regenerate_id(true);

        // Store user data in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        // Set session creation time for 48-hour expiration tracking
        $_SESSION['last_activity'] = time();

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login - Daily Sermon</title>
</head>
<body>

<h2>Login</h2>

<?php if (isset($_GET['expired'])): ?>
  <p style="color: orange;">Your session has expired. Please log in again.</p>
<?php endif; ?>

<?php if (isset($_GET['logged_out'])): ?>
  <p style="color: green;">You have been logged out successfully.</p>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
  <p>
    <label>Email:<br>
      <input type="email" name="email" required>
    </label>
  </p>
  <p>
    <label>Password:<br>
      <input type="password" name="password" required>
    </label>
  </p>
  <p><button type="submit">Login</button></p>
  <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
  <p>Forgot Password? <a href="forgot_password.php">Reset</a>.</p>
</form>

</body>
</html>
