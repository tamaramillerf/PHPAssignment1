<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=phpassignment1;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION["database_error"] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
