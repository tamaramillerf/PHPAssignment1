<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}

require("database.php");

$qCats = "SELECT categoryID, categoryName FROM categories ORDER BY categoryName";
$stCats = $db->prepare($qCats);
$stCats->execute();
$categories = $stCats->fetchAll(PDO::FETCH_ASSOC);
$stCats->closeCursor();

$filterCategoryID = filter_input(INPUT_GET, 'categoryID', FILTER_VALIDATE_INT);
$filterStatus = filter_input(INPUT_GET, 'status');

$allowedStatus = ['', 'Not Started', 'In Progress', 'Done'];
if (!in_array((string)$filterStatus, $allowedStatus, true)) {
    $filterStatus = '';
}

$sql = "
SELECT
  t.taskID,
  t.taskName,
  c.categoryName,
  t.categoryID,
  t.dueDate,
  t.status,
  t.imageName
FROM tasks t
JOIN categories c ON t.categoryID = c.categoryID
WHERE 1=1
";

$params = [];

if (!empty($filterCategoryID)) {
    $sql .= " AND t.categoryID = :categoryID ";
    $params[':categoryID'] = $filterCategoryID;
}

if (!empty($filterStatus)) {
    $sql .= " AND t.status = :status ";
    $params[':status'] = $filterStatus;
}

$sql .= " ORDER BY t.dueDate";

$statement = $db->prepare($sql);

foreach ($params as $key => $val) {
    if ($key === ':categoryID') {
        $statement->bindValue($key, $val, PDO::PARAM_INT);
    } else {
        $statement->bindValue($key, $val);
    }
}

$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

$today = new DateTime('today');
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
  <div class="page-title">
    <h2>Task List</h2>
    <a class="btn" href="add_task_form.php">Add Task</a>
  </div>

  <form class="filters" method="get" action="index.php" autocomplete="off">
    <div class="filter-group">
      <label for="categoryID">Category</label>
      <select name="categoryID" id="categoryID">
        <option value="">All</option>
        <?php foreach ($categories as $cat): ?>
          <?php $cid = (int)$cat['categoryID']; ?>
          <option value="<?php echo $cid; ?>" <?php if ((int)$filterCategoryID === $cid) echo 'selected'; ?>>
            <?php echo htmlspecialchars($cat['categoryName']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="filter-group">
      <label for="status">Status</label>
      <select name="status" id="status">
        <option value="" <?php if ($filterStatus === '') echo 'selected'; ?>>All</option>
        <option value="Not Started" <?php if ($filterStatus === 'Not Started') echo 'selected'; ?>>Not Started</option>
        <option value="In Progress" <?php if ($filterStatus === 'In Progress') echo 'selected'; ?>>In Progress</option>
        <option value="Done" <?php if ($filterStatus === 'Done') echo 'selected'; ?>>Done</option>
      </select>
    </div>

    <div class="filter-actions">
      <input type="submit" value="Apply">
      <a class="link" href="index.php">Clear</a>
    </div>
  </form>

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
      <?php
        $due = new DateTime($task['dueDate']);
        $badge = "";
        $badgeClass = "";

        if ($task['status'] !== 'Done') {
            if ($due < $today) {
                $badge = "Overdue";
                $badgeClass = "badge badge-overdue";
            } else {
                $daysAway = (int)$today->diff($due)->format('%r%a');
                if ($daysAway >= 0 && $daysAway <= 3) {
                    $badge = "Due Soon";
                    $badgeClass = "badge badge-soon";
                }
            }
        }
      ?>

      <tr class="<?php echo ($badgeClass === 'badge badge-overdue') ? 'row-overdue' : ''; ?>">
        <td>
          <a href="task_details.php?taskID=<?php echo (int)$task['taskID']; ?>">
            <?php echo htmlspecialchars($task['taskName']); ?>
          </a>
        </td>

        <td><?php echo htmlspecialchars($task['categoryName']); ?></td>

        <td>
          <?php echo htmlspecialchars($task['dueDate']); ?>
          <?php if ($badge): ?>
            <span class="<?php echo $badgeClass; ?>"><?php echo $badge; ?></span>
          <?php endif; ?>
        </td>

        <td><?php echo htmlspecialchars($task['status']); ?></td>

        <td>
          <img
            class="task-image"
            src="images/<?php echo htmlspecialchars($task['imageName']); ?>"
            alt="Task image"
            onerror="this.src='images/placeholder.png';"
          >
        </td>

        <td class="actions">
          <a class="link" href="update_task_form.php?taskID=<?php echo (int)$task['taskID']; ?>">Edit</a>

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