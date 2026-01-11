<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$display_name = trim($_POST["display_name"] ?? "");

if ($display_name === "") {
    header("Location: profile.php");
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET display_name = ? WHERE id = ?");
$stmt->execute([$display_name, $_SESSION["user_id"]]);

header("Location: dashboard.php");
exit;


