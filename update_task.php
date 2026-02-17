<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}
require("database.php");

$taskID     = filter_input(INPUT_POST, 'taskID', FILTER_VALIDATE_INT);
$taskName   = trim(filter_input(INPUT_POST, 'taskName'));
$categoryID = filter_input(INPUT_POST, 'categoryID', FILTER_VALIDATE_INT);
$dueDate    = filter_input(INPUT_POST, 'dueDate');
$status     = filter_input(INPUT_POST, 'status');

if (!$taskID || !$taskName || !$categoryID || !$dueDate || !$status) {
    $_SESSION["app_error"] = "Invalid form submission.";
    header("Location: error.php");
    exit();
}

$q = "SELECT imageName FROM tasks WHERE taskID = :taskID";
$s = $db->prepare($q);
$s->bindValue(':taskID', $taskID, PDO::PARAM_INT);
$s->execute();
$current = $s->fetch(PDO::FETCH_ASSOC);
$s->closeCursor();

if (!$current) {
    $_SESSION["app_error"] = "Task not found.";
    header("Location: error.php");
    exit();
}

$imageName = $current['imageName'];

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION["app_error"] = "Upload failed. Error code: " . $_FILES['image']['error'];
        header("Location: error.php");
        exit();
    }

    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        $_SESSION["app_error"] = "Image too large (max 5MB).";
        header("Location: error.php");
        exit();
    }

    $allowed = ['jpg','jpeg','png','gif','webp'];
    $originalName = $_FILES['image']['name'];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
        $_SESSION["app_error"] = "Only JPG, PNG, GIF, or WEBP images are allowed.";
        header("Location: error.php");
        exit();
    }

    $safeBase = preg_replace("/[^A-Za-z0-9_-]/", "_", pathinfo($originalName, PATHINFO_FILENAME));
    $newName = $safeBase . "_" . uniqid() . "." . $ext;

    $targetFolder = __DIR__ . "/images/";
    if (!is_dir($targetFolder)) {
        mkdir($targetFolder, 0755, true);
    }

    $targetPath = $targetFolder . $newName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $_SESSION["app_error"] = "Could not save uploaded image.";
        header("Location: error.php");
        exit();
    }

    if ($imageName !== "placeholder.png") {
        $oldPath = __DIR__ . "/images/" . $imageName;
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }

    $imageName = $newName;
}

$update = "
UPDATE tasks
SET taskName = :taskName,
    categoryID = :categoryID,
    dueDate = :dueDate,
    status = :status,
    imageName = :imageName
WHERE taskID = :taskID
";

$st2 = $db->prepare($update);
$st2->bindValue(':taskName', $taskName);
$st2->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
$st2->bindValue(':dueDate', $dueDate);
$st2->bindValue(':status', $status);
$st2->bindValue(':imageName', $imageName);
$st2->bindValue(':taskID', $taskID, PDO::PARAM_INT);
$st2->execute();
$st2->closeCursor();

header("Location: task_details.php?taskID=" . $taskID);
exit();
