<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}
require("database.php");

$taskID = filter_input(INPUT_POST, 'taskID', FILTER_VALIDATE_INT);
if (!$taskID) {
    $_SESSION["app_error"] = "Invalid task selected.";
    header("Location: error.php");
    exit();
}

$q = "SELECT imageName FROM tasks WHERE taskID = :taskID";
$s = $db->prepare($q);
$s->bindValue(':taskID', $taskID, PDO::PARAM_INT);
$s->execute();
$row = $s->fetch(PDO::FETCH_ASSOC);
$s->closeCursor();

if ($row) {
    $imageName = $row['imageName'];

    $del = "DELETE FROM tasks WHERE taskID = :taskID";
    $sd = $db->prepare($del);
    $sd->bindValue(':taskID', $taskID, PDO::PARAM_INT);
    $sd->execute();
    $sd->closeCursor();

    if ($imageName !== "placeholder.png") {
        $path = __DIR__ . "/images/" . $imageName;
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}

header("Location: index.php");
exit();
