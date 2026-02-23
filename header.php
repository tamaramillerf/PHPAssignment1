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

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <h1>Task Manager System</h1>
      <?php if (isset($_SESSION["isLoggedIn"])): ?>
        <p class="subtext">Logged in as <?php echo htmlspecialchars($_SESSION["userName"]); ?></p>
      <?php endif; ?>
    </div>

    <nav class="nav">
      <?php if (isset($_SESSION["isLoggedIn"])): ?>
        <a href="index.php">Home</a>
        <a href="add_task_form.php">Add Task</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login_form.php">Login</a>
        <a href="register_user_form.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>