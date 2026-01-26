<?php
    session_start();
    $error = $_SESSION["database_error"] ?? "Unknown database error";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Error</title>
</head>
<body>
    <h2>Database Error</h2>
    <p><?php echo htmlspecialchars($error); ?></p>
</body>
</html>
