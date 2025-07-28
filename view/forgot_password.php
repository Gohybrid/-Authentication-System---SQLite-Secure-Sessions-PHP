<?php
require_once '../core/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Use $db from config.php
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $db->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update->execute([$token, $expires, $email]);


        // Simulated reset link
        $reset_link = "http://localhost:8888/auth-main/view/reset_password.php?token=$token"; // Update domain as needed
        $message = "A password reset link has been generated:<br><a href=\"$reset_link\">$reset_link</a>";
    } else {
        $message = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Email Address:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
