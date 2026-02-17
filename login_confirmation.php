<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Successful</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Login Successful</h2>
  <p>Welcome back, <?php echo htmlspecialchars($_SESSION["userName"] ?? ""); ?>.</p>
  <p><a href="index.php">Go to Task List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
