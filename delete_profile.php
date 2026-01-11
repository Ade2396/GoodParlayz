<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);

session_destroy();

header("Location: signup.html");
exit;

