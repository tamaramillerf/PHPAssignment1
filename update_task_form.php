<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}
require("database.php");

$taskID = filter_input(INPUT_GET, 'taskID', FILTER_VALIDATE_INT);
if (!$taskID) {
    $_SESSION["app_error"] = "Invalid task selected.";
    header("Location: error.php");
    exit();
}

$queryTask = "SELECT * FROM tasks WHERE taskID = :taskID";
$st = $db->prepare($queryTask);
$st->bindValue(':taskID', $taskID, PDO::PARAM_INT);
$st->execute();
$task = $st->fetch(PDO::FETCH_ASSOC);
$st->closeCursor();

if (!$task) {
    $_SESSION["app_error"] = "Task not found.";
    header("Location: error.php");
    exit();
}

$queryCats = "SELECT categoryID, categoryName FROM categories ORDER BY categoryName";
$sc = $db->prepare($queryCats);
$sc->execute();
$categories = $sc->fetchAll(PDO::FETCH_ASSOC);
$sc->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Task</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Edit Task</h2>

  <form action="update_task.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="taskID" value="<?php echo (int)$task['taskID']; ?>">

    <p>
      Task Name:<br>
      <input type="text" name="taskName" value="<?php echo htmlspecialchars($task['taskName']); ?>" required>
    </p>

    <p>
      Category:<br>
      <select name="categoryID" required>
        <?php foreach ($categories as $cat): ?>
          <option
            value="<?php echo (int)$cat['categoryID']; ?>"
            <?php if ((int)$cat['categoryID'] === (int)$task['categoryID']) echo "selected"; ?>
          >
            <?php echo htmlspecialchars($cat['categoryName']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </p>

    <p>
      Due Date:<br>
      <input type="date" name="dueDate" value="<?php echo htmlspecialchars($task['dueDate']); ?>" required>
    </p>

    <p>
      Status:<br>
      <select name="status" required>
        <option value="Not Started" <?php if ($task['status'] === 'Not Started') echo 'selected'; ?>>Not Started</option>
        <option value="In Progress" <?php if ($task['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
        <option value="Done" <?php if ($task['status'] === 'Done') echo 'selected'; ?>>Done</option>
      </select>
    </p>

    <p>
      Current Image:<br>
      <img src="images/<?php echo htmlspecialchars($task['imageName']); ?>" width="120"
           onerror="this.src='images/placeholder.png';">
    </p>

    <p>
      Replace Image (optional):<br>
      <input type="file" name="image" accept=".png,.jpg,.jpeg,.gif,.webp">
    </p>

    <p>
      <input type="submit" value="Update Task">
      <a href="task_details.php?taskID=<?php echo (int)$task['taskID']; ?>">Cancel</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>
</body>
</html>
