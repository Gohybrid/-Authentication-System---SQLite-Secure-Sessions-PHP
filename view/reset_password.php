<?php
require_once '../core/config.php';

$message = '';
$token = $_GET['token'] ?? '';
$show_form = true;

if (!$token) {
    $message = "Invalid or missing token.";
    $show_form = false;
} else {
    // Check if token exists and hasn't expired
    $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > datetime('now')");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $message = "Invalid or expired token.";
        $show_form = false;
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $new_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $update->execute([$hashed_password, $user['id']]);

        $message = "Password reset successful. You can now <a href='login.php'>log in</a>.";
        $show_form = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <form method="POST">
            <label>New Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</body>
</html>
