<?php
session_start();
require '../../assets/data.php';

if (empty($_SESSION['sukseslogin'])) {
    die("Access Denied: Please log in to admin panel first.");
}

$target_dir = "../../images/games/";
$files = glob($target_dir . "*.*");
$added = 0;
$skipped = 0;

foreach ($files as $filepath) {
    $filename = basename($filepath);
    if ($filename === 'placeholder.png') continue;
    
    $base_name = pathinfo($filename, PATHINFO_FILENAME);
    $parts = explode('-', $base_name);
    $provider = (count($parts) > 0) ? $parts[0] : 'general';
    
    if (count($parts) >= 2) {
        $title = strtoupper($parts[0]) . ' Game ' . $parts[1];
    } else {
        $title = ucwords(str_replace('-', ' ', $base_name));
    }
    
    $check_sql = "SELECT id FROM demo_games WHERE demo_name = '" . mysqli_real_escape_string($data, $filename) . "'";
    $res = mysqli_query($data, $check_sql);
    
    if ($res && mysqli_num_rows($res) == 0) {
        $insert_sql = "INSERT INTO demo_games (demo_provider, game_title, demo_name, demo_gamelink) VALUES ('" . mysqli_real_escape_string($data, $provider) . "', '" . mysqli_real_escape_string($data, $title) . "', '" . mysqli_real_escape_string($data, $filename) . "', '#')";
        if (mysqli_query($data, $insert_sql)) {
            $added++;
        }
    } else {
        $skipped++;
    }
}

echo "<h3>Game Synchronization Complete!</h3>";
echo "<p>Successfully added <strong>$added</strong> missing games to database.</p>";
echo "<p>Skipped <strong>$skipped</strong> existing games.</p>";
echo '<p><a href="../dashboard.php?hal=gameimg">Return to Admin Dashboard</a></p>';
?>
