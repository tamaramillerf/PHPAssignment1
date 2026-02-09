<?php
session_start();
$error = $_SESSION["database_error"] ?? "Unknown database error.";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Database Error</title>
</head>
<body>
  <h1>Database Error</h1>
  <p><?php echo htmlspecialchars($error); ?></p>
</body>
</html>
