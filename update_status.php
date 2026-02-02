<?php
require("database.php");

$taskID = filter_input(INPUT_POST, 'taskID', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status');

$allowed = ['Not Started', 'In Progress', 'Done'];

if ($taskID && in_array($status, $allowed, true)) {
    $query = "UPDATE tasks
              SET status = :status
              WHERE taskID = :taskID";

    $statement = $db->prepare($query);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':taskID', $taskID, PDO::PARAM_INT);
    $statement->execute();
    $statement->closeCursor();
}

header("Location: index.php");
exit();