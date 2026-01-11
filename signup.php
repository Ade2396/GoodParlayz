<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signup.html");
    exit;
}

$display_name = trim($_POST["display_name"] ?? "");
$email        = trim($_POST["email"] ?? "");
$password     = $_POST["password"] ?? "";

if ($display_name === "" || $email === "" || $password === "") {
    header("Location: signup.html");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "INSERT INTO users(display_name, email, password_hash) VALUES(?,?,?)"
);

try {
    $stmt->execute([$display_name, $email, $hash]);
    header("Location: login.html?success=Account created. Please log in.");
    exit;
} catch (PDOException $e) {
    header("Location: signup.html?error=Email already exists");
    exit;
}

