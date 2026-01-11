<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

// Add favorite team
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $team = trim($_POST["team_name"] ?? "");
    if ($team !== "") {
        $stmt = $pdo->prepare("INSERT INTO favorite_teams(user_id, team_name) VALUES(?, ?)");
        $stmt->execute([$_SESSION["user_id"], $team]);
    }
    header("Location: favorites.php");
    exit;
}

// Delete favorite team
if (isset($_GET["delete_id"])) {
    $id = (int) $_GET["delete_id"];
    $stmt = $pdo->prepare("DELETE FROM favorite_teams WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION["user_id"]]);
    header("Location: favorites.php");
    exit;
}

// Fetch favorites
$stmt = $pdo->prepare("SELECT * FROM favorite_teams WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]);
$favs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Favorites • GoodParlayz</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="logo-wrap">
  <img src="assets/logo.png" class="logo" alt="GoodParlayz">
</div>

<h2 style="text-align:center;">Favorite Teams</h2>

<div class="center-form">
  <form method="POST">
    <label>Team Name</label><br>
    <input type="text" name="team_name" required><br><br>
    <button type="submit">Add Favorite</button>
  </form>
</div>

<h3 style="text-align:center;">Your Favorites</h3>

<div class="center-list">
  <?php if (count($favs) === 0): ?>
    <p>No favorite teams yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($favs as $f): ?>
        <li>
          <?php echo htmlspecialchars($f["team_name"]); ?>
          <a href="favorites.php?delete_id=<?php echo $f['id']; ?>">[remove]</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<p class="center-links"><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>

