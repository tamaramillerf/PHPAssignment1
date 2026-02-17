<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration Complete</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Registration Complete</h2>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION["userName"] ?? ""); ?>.</p>
  <p><a href="index.php">Go to Task List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
