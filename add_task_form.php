<!DOCTYPE html>
<html>
<head>
    <title>Task Manager - Add Task</title>
</head>
<body>

<?php include("header.php"); ?>

<main>
    <h2>Add Task</h2>

    <<form action="add_task.php" method="post">

    <label>Task Name:</label>
    <input type="text" name="taskName" required>
    <br><br>

    <label>Category:</label>
    <input type="text" name="category" required>
    <br><br>

    <label>Due Date:</label>
    <input type="date" name="dueDate">
    <br><br>

    <label>Status:</label>
    <select name="status" required>
        <option value="Not Started">Not Started</option>
        <option value="In Progress">In Progress</option>
        <option value="Done">Done</option>
    </select>
    <br><br>

    <input type="submit" value="Add Task">

</form>
