<?php
session_start();
require("database.php");

$user_name = trim(filter_input(INPUT_POST, "userName"));
$user_password = filter_input(INPUT_POST, "password");

if ($user_name === "" || $user_password === "" || $user_password === null) {
    $_SESSION["login_error"] = "Please enter your username and password.";
    header("Location: login_form.php");
    exit();
}

$query = "SELECT userID, userName, password FROM registrations WHERE userName = :userName";
$statement = $db->prepare($query);
$statement->bindValue(":userName", $user_name);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if ($user) {
    if (password_verify($user_password, $user["password"])) {
        $_SESSION["isLoggedIn"] = TRUE;
        $_SESSION["userName"] = $user["userName"];
        $_SESSION["user_id"] = $user["userID"];

        header("Location: login_confirmation.php");
        exit();
    } else {
        $_SESSION["login_error"] = "Incorrect password.";
        header("Location: login_form.php");
        exit();
    }
} else {
    $_SESSION["login_error"] = "User not found.";
    header("Location: login_form.php");
    exit();
}
