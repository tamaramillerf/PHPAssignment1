<?php
    session_start();

    $taskName = filter_input(INPUT_POST, 'taskName');
    $category = filter_input(INPUT_POST, 'category');
    $dueDate  = filter_input(INPUT_POST, 'dueDate');
    $status   = filter_input(INPUT_POST, 'status');

    require_once('database.php');

    // Add Task
    $query = "INSERT INTO tasks (taskName, category, dueDate, status)
              VALUES (:taskName, :category, :dueDate, :status)";

    $statement = $db->prepare($query);
    $statement->bindValue(':taskName', $taskName);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':dueDate', $dueDate);
    $statement->bindValue(':status', $status);
    $statement->execute();
    $statement->closeCursor();

    header("Location: index.php");
    exit();
?>
