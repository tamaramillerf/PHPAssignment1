<?php
session_start();
$error = $_SESSION["app_error"] ?? "Something went wrong.";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Error</title>
</head>
<body>
  <h1>Error</h1>
  <p><?php echo htmlspecialchars($error); ?></p>
  <p><a href="index.php">Back to Home</a></p>
</body>
</html>
