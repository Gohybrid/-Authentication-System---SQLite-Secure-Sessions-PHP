<?php
require_once '../core/session.php'; // Adjust path based on your structure
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
</head>
<body>

<h2>Welcome to Your Dashboard</h2>

<p><strong>User ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
<p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

<p><a href="logout.php">Logout</a></p>

</body>
</html>
