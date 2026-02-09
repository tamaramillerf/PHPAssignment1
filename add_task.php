<?php
session_start();
require("database.php");

$taskName   = trim(filter_input(INPUT_POST, 'taskName'));
$categoryID = filter_input(INPUT_POST, 'categoryID', FILTER_VALIDATE_INT);
$dueDate    = filter_input(INPUT_POST, 'dueDate');
$status     = filter_input(INPUT_POST, 'status');

if (!$taskName || !$categoryID || !$dueDate || !$status) {
    $_SESSION["app_error"] = "Please fill out all required fields.";
    header("Location: error.php");
    exit();
}

$imageName = "placeholder.png";

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $targetFolder = __DIR__ . "/images/";
    if (!is_dir($targetFolder)) {
        mkdir($targetFolder, 0755, true);
    }

    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowed = ['png','jpg','jpeg','gif','webp'];
    if (!in_array($ext, $allowed, true)) {
        $_SESSION["app_error"] = "Invalid file type.";
        header("Location: error.php");
        exit();
    }

    $finalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME))
            . "_" . time() . "." . $ext;

    $targetPath = $targetFolder . $finalName;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        $_SESSION["app_error"] = "Could not save uploaded image.";
        header("Location: error.php");
        exit();
    }

    $imageName = $finalName;
}


$query = "INSERT INTO tasks (taskName, categoryID, dueDate, status, imageName)
          VALUES (:taskName, :categoryID, :dueDate, :status, :imageName)";

$statement = $db->prepare($query);
$statement->bindValue(':taskName', $taskName);
$statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
$statement->bindValue(':dueDate', $dueDate);
$statement->bindValue(':status', $status);
$statement->bindValue(':imageName', $imageName);
$statement->execute();
$statement->closeCursor();

header("Location: index.php");
exit();
