<?php
session_start();
require("database.php");

$user_name = trim(filter_input(INPUT_POST, "userName"));
$email_address = trim(filter_input(INPUT_POST, "emailAddress"));
$user_password = filter_input(INPUT_POST, "password");

if ($user_name === "" || $email_address === "" || $user_password === "" || $user_password === null) {
    $_SESSION["add_error"] = "Invalid registration data. Check all fields and try again.";
    header("Location: register_user_form.php");
    exit();
}

if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["add_error"] = "Please enter a valid email address.";
    header("Location: register_user_form.php");
    exit();
}

$hash = password_hash($user_password, PASSWORD_DEFAULT);

// check username/email uniqueness (simple like class projects)
$check = "SELECT userID FROM registrations WHERE userName = :userName OR emailAddress = :emailAddress";
$st = $db->prepare($check);
$st->bindValue(":userName", $user_name);
$st->bindValue(":emailAddress", $email_address);
$st->execute();
$existing = $st->fetch(PDO::FETCH_ASSOC);
$st->closeCursor();

if ($existing) {
    $_SESSION["add_error"] = "That username or email already exists. Try another.";
    header("Location: register_user_form.php");
    exit();
}

// insert
$query = "INSERT INTO registrations (userName, password, emailAddress)
          VALUES (:userName, :password, :emailAddress)";

$statement = $db->prepare($query);
$statement->bindValue(":userName", $user_name);
$statement->bindValue(":password", $hash);
$statement->bindValue(":emailAddress", $email_address);
$statement->execute();
$statement->closeCursor();

// auto log in (same idea as teacher)
$_SESSION["isLoggedIn"] = 1;
$_SESSION["userName"] = $user_name;

header("Location: register_confirmation.php");
exit();
