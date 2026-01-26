<?php
    require("database.php");

    $taskID = filter_input(INPUT_POST, "taskID", FILTER_VALIDATE_INT);

    if ($taskID) {
        $query = "DELETE FROM tasks WHERE taskID = :taskID";
        $statement = $db->prepare($query);
        $statement->bindValue(":taskID", $taskID);
        $statement->execute();
        $statement->closeCursor();
    }

    header("Location: index.php");
    exit();
?>
