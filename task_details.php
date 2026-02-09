<?php
require("database.php");

$taskID = filter_input(INPUT_GET, 'taskID', FILTER_VALIDATE_INT);
if (!$taskID) {
    $_SESSION["app_error"] = "Invalid task selected.";
    header("Location: error.php");
    exit();
}

$query = "
SELECT
  t.taskID, t.taskName, t.dueDate, t.status, t.imageName,
  c.categoryName
FROM tasks t
JOIN categories c ON t.categoryID = c.categoryID
WHERE t.taskID = :taskID
";

$statement = $db->prepare($query);
$statement->bindValue(':taskID', $taskID, PDO::PARAM_INT);
$statement->execute();
$task = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$task) {
    $_SESSION["app_error"] = "Task not found.";
    header("Location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Task Details</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Task Details</h2>

  <p><strong>Task:</strong> <?php echo htmlspecialchars($task['taskName']); ?></p>
  <p><strong>Category:</strong> <?php echo htmlspecialchars($task['categoryName']); ?></p>
  <p><strong>Due Date:</strong> <?php echo htmlspecialchars($task['dueDate']); ?></p>
  <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>

  <p>
    <img
      src="images/<?php echo htmlspecialchars($task['imageName']); ?>"
      alt="Task image"
      width="250"
      onerror="this.src='images/placeholder.png';"
    >
  </p>

  <p>
    <a href="update_task_form.php?taskID=<?php echo (int)$task['taskID']; ?>">Edit Task</a>
    | <a href="index.php">Back</a>
  </p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
