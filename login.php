<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$email    = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    header("Location: login.html?error=Missing fields");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password_hash"])) {
    $_SESSION["user_id"] = $user["id"];
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.html?error=Invalid email or password");
    exit;
}

