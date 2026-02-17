<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<header>
    <h1>Task Manager System</h1>

    <p>
      <?php if (isset($_SESSION["isLoggedIn"])): ?>
        Logged in as <?php echo htmlspecialchars($_SESSION["userName"]); ?> |
        <a href="logout.php" style="color:white;">Logout</a>
      <?php else: ?>
        <a href="login_form.php" style="color:white;">Login</a> |
        <a href="register_user_form.php" style="color:white;">Register</a>
      <?php endif; ?>
    </p>
</header>
