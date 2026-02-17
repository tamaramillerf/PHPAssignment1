<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}
require("database.php");

$query = "SELECT categoryID, categoryName FROM categories ORDER BY categoryName";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Task</title>
  <link rel="stylesheet" href="css/taskmanager.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2>Add Task</h2>

  <form action="add_task.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <p>
      Task Name:<br>
      <input type="text" name="taskName" required autocomplete="off">
    </p>

    <p>
      Category:<br>
      <select name="categoryID" required>
        <option value="">-- Select --</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo (int)$cat['categoryID']; ?>">
            <?php echo htmlspecialchars($cat['categoryName']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </p>

    <p>
      Due Date:<br>
      <input type="date" name="dueDate" required>
    </p>

    <p>
      Status:<br>
      <select name="status" required>
        <option value="Not Started">Not Started</option>
        <option value="In Progress">In Progress</option>
        <option value="Done">Done</option>
      </select>
    </p>

    <p>
      Image (optional):<br>
      <input type="file" name="image" accept=".png,.jpg,.jpeg,.gif,.webp">
    </p>

    <p>
      <input type="submit" value="Add Task">
      <a href="index.php">Cancel</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>
</body>
</html>
