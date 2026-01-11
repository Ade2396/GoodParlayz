<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard • GoodParlayz</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-wrap">
  <!-- TEAM LOGOS ROW -->
  <div class="side-teams">
    <img src="assets/heat.png" class="team-icon" alt="Miami Heat">
    <img src="assets/lakers.png" class="team-icon" alt="Los Angeles Lakers">
    <img src="assets/bruins.png" class="team-icon" alt="Boston Bruins">
    <img src="assets/dodgers.png" class="team-icon" alt="LA Dodgers">
    <img src="assets/ravens.png" class="team-icon" alt="Baltimore Ravens">
  </div>

  <!-- TOP TITLE -->
  <h1 class="site-title">GOODPARLAYZ</h1>

  <div class="logo-wrap">
    <img src="assets/logo.png" class="logo">
  </div>

  <div class="card">
    <h2>Hi, <?php echo htmlspecialchars($user['display_name']); ?></h2>

    <div style="margin-top: 10px;">
      <a href="profile.php">Edit Profile</a> |
      <a href="favorites.php">Favorite Teams</a>
    </div>

    <button onclick="window.location='logout.php'" class="btn">Log Out</button>
  </div>

  <div class="card">
    <h2>Your Parlay Slips</h2>
    <p style="font-size:15px;">
      Make your parlay here for free and get the experience of a real sports betting site.
    </p>
    <button onclick="window.location='add_parlay.php'" class="btn">Cook Your Parlay</button>
  </div>

</div>

<div class="footer-note">
  Demo sportsbook style experience. No real wagers or payouts.
</div>

</body>
</html>
