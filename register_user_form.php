<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Register</h2>

  <?php if (!empty($_SESSION["add_error"])): ?>
    <p><?php echo htmlspecialchars($_SESSION["add_error"]); ?></p>
    <?php unset($_SESSION["add_error"]); ?>
  <?php endif; ?>

  <form action="register_user.php" method="post" autocomplete="off">
    <p>
      Username:<br>
      <input type="text" name="userName" required>
    </p>

    <p>
      Email Address:<br>
      <input type="text" name="emailAddress" required>
    </p>

    <p>
      Password:<br>
      <input type="password" name="password" required>
    </p>

    <p>
      <input type="submit" value="Register">
      <a href="login_form.php">Login</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>
</body>
</html>
