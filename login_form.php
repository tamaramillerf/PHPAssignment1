<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Login</h2>

  <?php if (!empty($_SESSION["login_error"])): ?>
    <p><?php echo htmlspecialchars($_SESSION["login_error"]); ?></p>
    <?php unset($_SESSION["login_error"]); ?>
  <?php endif; ?>

  <form action="login.php" method="post" autocomplete="off">
    <p>
      Username:<br>
      <input type="text" name="userName" required>
    </p>

    <p>
      Password:<br>
      <input type="password" name="password" required>
    </p>

    <p>
      <input type="submit" value="Login">
      <a href="register_user_form.php">Register</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>
</body>
</html>
