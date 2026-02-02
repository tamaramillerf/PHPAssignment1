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

    <form action="add_task.php" method="post" enctype="multipart/form-data">

        <p>
            Task Name:<br>
            <input type="text" name="taskName" required>
        </p>

        <p>
            Category:<br>
            <input type="text" name="category" required>
        </p>

        <p>
            Due Date:<br>
            <input type="date" name="dueDate" required>
        </p>

        <p>
            Status:<br>
            <select name="status">
                <option value="Not Started">Not Started</option>
                <option value="In Progress">In Progress</option>
                <option value="Done">Done</option>
            </select>
        </p>

        <p>
            Image:<br>
            <input type="file" name="image">
        </p>

        <p>
            <input type="submit" value="Add Task">
            <a href="index.php">Cancel</a>
        </p>

    </form>
</main>

<?php include("footer.php"); ?>
