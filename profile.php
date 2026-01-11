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

if (!$user) {
    session_destroy();
    header("Location: signup.html");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Profile • GoodParlayz</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="logo-wrap">
  <img src="assets/logo.png" class="logo" alt="GoodParlayz">
</div>

<h2 style="text-align:center;">Edit Profile</h2>

<div class="center-form">
  <form method="POST" action="update_profile.php">
    <label>Display Name</label><br>
    <input type="text" name="display_name"
           value="<?php echo htmlspecialchars($user['display_name']); ?>"
           required><br><br>

    <button type="submit">Save Changes</button>
  </form>

  <br>
  <a href="dashboard.php">Back to Dashboard</a><br><br>
  <a href="delete_profile.php" style="color:red;">Delete My Profile</a>
</div>

</body>
</html>

