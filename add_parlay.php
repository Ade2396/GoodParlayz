<?php
session_start();
require "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

function tableExists(PDO $pdo, string $table): bool {
    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return (bool) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

function getColumnNames(PDO $pdo, string $table): array {
    try {
        $stmt = $pdo->query("DESCRIBE `$table`");
        $cols = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];
        return $cols ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

function fetchPresetMatchups(PDO $pdo): array {
    $fallback = [
        "Bulls vs Pistons",
        "Eagles vs 49ers",
        "Giants vs Cowboys",
        "Heat vs Celtics",
        "Lakers vs Warriors",
        "Ravens vs Steelers",
    ];

    if (!tableExists($pdo, "matchups")) {
        return $fallback;
    }

    $matchups = [];
    $columns = getColumnNames($pdo, "matchups");

    try {
        if (in_array("home_team", $columns, true) && in_array("away_team", $columns, true)) {
            $stmt = $pdo->query("SELECT home_team, away_team FROM matchups ORDER BY id DESC LIMIT 50");
            foreach ($stmt as $row) {
                $home = trim((string) ($row["home_team"] ?? ""));
                $away = trim((string) ($row["away_team"] ?? ""));
                if ($home !== "" && $away !== "") {
                    $matchups[] = "$home vs $away";
                }
            }
        } elseif (
            in_array("home_team_id", $columns, true) &&
            in_array("away_team_id", $columns, true) &&
            tableExists($pdo, "teams")
        ) {
            $teamColumns = getColumnNames($pdo, "teams");
            $nameCol = null;
            if (in_array("team_name", $teamColumns, true)) {
                $nameCol = "team_name";
            } elseif (in_array("name", $teamColumns, true)) {
                $nameCol = "name";
            }

            if ($nameCol) {
                $query = "
                    SELECT m.id, t1.`$nameCol` AS home_name, t2.`$nameCol` AS away_name
                    FROM matchups m
                    LEFT JOIN teams t1 ON m.home_team_id = t1.id
                    LEFT JOIN teams t2 ON m.away_team_id = t2.id
                    ORDER BY m.id DESC
                    LIMIT 50
                ";
                $stmt = $pdo->query($query);
                foreach ($stmt as $row) {
                    $home = trim((string) ($row["home_name"] ?? ""));
                    $away = trim((string) ($row["away_name"] ?? ""));
                    if ($home !== "" && $away !== "") {
                        $matchups[] = "$home vs $away";
                    }
                }
            }
        }
    } catch (PDOException $e) {
        return $fallback;
    }

    $matchups = array_values(array_unique(array_filter($matchups)));
    return count($matchups) > 0 ? $matchups : $fallback;
}

$presetMatchups = fetchPresetMatchups($pdo);

// Add a parlay
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matchup = trim($_POST["matchup"] ?? "");
    $stake   = trim($_POST["stake"] ?? "");

    if ($matchup !== "" && $stake !== "") {
        $stmt = $pdo->prepare(
            "INSERT INTO parlay_slips(user_id, matchup, stake, odds) VALUES(?, ?, ?, ?)"
        );
        // Odds no longer used; keep placeholder to satisfy the column
        $stmt->execute([$_SESSION["user_id"], $matchup, $stake, "N/A"]);
    }

    header("Location: add_parlay.php");
    exit;
}

// Delete a parlay
if (isset($_GET["delete_id"])) {
    $id = (int) $_GET["delete_id"];
    $stmt = $pdo->prepare("DELETE FROM parlay_slips WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION["user_id"]]);
    header("Location: add_parlay.php");
    exit;
}

// Fetch parlays
$stmt = $pdo->prepare("SELECT * FROM parlay_slips WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]);
$slips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Parlays • GoodParlayz</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="logo-wrap">
  <img src="assets/logo.png" class="logo" alt="GoodParlayz">
</div>

<h2 style="text-align:center;">Place a Parlay</h2>

<div class="center-form">
  <form method="POST">
    <label>Matchup</label><br>
    <?php if (count($presetMatchups) > 0): ?>
      <select name="matchup" required>
        <option value="" disabled selected>Select a preset matchup</option>
        <?php foreach ($presetMatchups as $preset): ?>
          <option value="<?php echo htmlspecialchars($preset); ?>">
            <?php echo htmlspecialchars($preset); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <br>
    <?php else: ?>
      <input type="text" name="matchup" required placeholder="Enter matchup"><br><br>
    <?php endif; ?>

    <label>Stake</label><br>
    <input type="number" step="0.01" name="stake" required><br><br>

    <button type="submit">Save Parlay</button>
  </form>
</div>

<h3 style="text-align:center;">Your Parlays</h3>

<div class="center-list">
  <?php if (count($slips) === 0): ?>
    <p>No parlays yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($slips as $s): ?>
        <li>
          <?php echo htmlspecialchars($s["matchup"]); ?> —
          $<?php echo htmlspecialchars($s["stake"]); ?>
          <a href="add_parlay.php?delete_id=<?php echo $s['id']; ?>">[delete]</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<p class="center-links"><a href="dashboard.php">Back to Dashboard</a></p>

<div class="footer-note">
  Demo sportsbook style experience. No real wagers or payouts.
</div>

</body>
</html>
