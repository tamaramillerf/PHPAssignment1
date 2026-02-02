<?php
require("database.php");

$queryTasks = '
    SELECT taskID, taskName, category, dueDate, status
    FROM tasks
    ORDER BY dueDate';

$statement = $db->prepare($queryTasks);
$statement->execute();
$tasks = $statement->fetchAll();
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
            <th>Delete</th>
        </tr>

        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['taskName']); ?></td>
                <td><?php echo htmlspecialchars($task['category']); ?></td>
                <td><?php echo htmlspecialchars($task['dueDate']); ?></td>

                <td>
                    <form action="update_status.php" method="post">
                        <input type="hidden" name="taskID" value="<?php echo $task['taskID']; ?>">

                        <select name="status" onchange="this.form.submit()">
                            <option value="Not Started" <?php if ($task['status'] === 'Not Started') echo 'selected'; ?>>Not Started</option>
                            <option value="In Progress" <?php if ($task['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Done" <?php if ($task['status'] === 'Done') echo 'selected'; ?>>Done</option>
                        </select>
                    </form>
                </td>

                <td>
                    <form action="delete_task.php" method="post">
                        <input type="hidden" name="taskID" value="<?php echo $task['taskID']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include("footer.php"); ?>

</body>
</html>
