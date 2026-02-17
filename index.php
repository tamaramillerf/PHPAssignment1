<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}
require("database.php");

$queryTasks = "
SELECT
  t.taskID,
  t.taskName,
  c.categoryName,
  t.dueDate,
  t.status,
  t.imageName
FROM tasks t
JOIN categories c ON t.categoryID = c.categoryID
ORDER BY t.dueDate
";

$statement = $db->prepare($queryTasks);
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Task Manager - Home</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Task List</h2>
  <p><a href="add_task_form.php">Add Task</a></p>

  <table>
    <tr>
      <th>Task</th>
      <th>Category</th>
      <th>Due Date</th>
      <th>Status</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>

    <?php foreach ($tasks as $task): ?>
      <tr>
        <td>
          <a href="task_details.php?taskID=<?php echo (int)$task['taskID']; ?>">
            <?php echo htmlspecialchars($task['taskName']); ?>
          </a>
        </td>
        <td><?php echo htmlspecialchars($task['categoryName']); ?></td>
        <td><?php echo htmlspecialchars($task['dueDate']); ?></td>
        <td><?php echo htmlspecialchars($task['status']); ?></td>

        <td>
          <img
            src="images/<?php echo htmlspecialchars($task['imageName']); ?>"
            alt="Task image"
            width="80"
            onerror="this.src='images/placeholder.png';"
          >
        </td>

        <td>
          <a href="update_task_form.php?taskID=<?php echo (int)$task['taskID']; ?>">Edit</a>

          <form action="delete_task.php" method="post" style="display:inline;">
            <input type="hidden" name="taskID" value="<?php echo (int)$task['taskID']; ?>">
            <input type="submit" value="Delete" onclick="return confirm('Delete this task?');">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</main>

<?php include("footer.php"); ?>
</body>
</html>
