<?php
session_start();
require '../../assets/data.php';

if (empty($_SESSION['sukseslogin'])) {
    die("Access Denied: Please log in to admin panel first.");
}

// Increase limits for large folders
set_time_limit(300);
ini_set('memory_limit', '256M');

$target_dir = "../../images/games/";
$added = 0;
$skipped = 0;
$errors = 0;
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

if (!is_dir($target_dir)) {
    die("<p>Error: games directory not found at: $target_dir</p>");
}

// Use DirectoryIterator instead of glob() to avoid the ~100 file limit
// that glob() can hit on some shared hosting environments with open_basedir restrictions.
$iterator = new DirectoryIterator($target_dir);

foreach ($iterator as $fileinfo) {
    if ($fileinfo->isDot() || !$fileinfo->isFile()) continue;

    $filename = $fileinfo->getFilename();
    $ext = strtolower($fileinfo->getExtension());

    // Skip placeholder and non-image files
    if ($filename === 'placeholder.png') continue;
    if (!in_array($ext, $allowed_ext)) continue;

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
        $insert_sql = "INSERT INTO demo_games (demo_provider, game_title, demo_name, demo_gamelink) VALUES ('"
            . mysqli_real_escape_string($data, $provider) . "', '"
            . mysqli_real_escape_string($data, $title) . "', '"
            . mysqli_real_escape_string($data, $filename) . "', '#')";
        if (mysqli_query($data, $insert_sql)) {
            $added++;
        } else {
            $errors++;
        }
    } else {
        $skipped++;
    }
}

echo "<h3>Game Synchronization Complete!</h3>";
echo "<p>Successfully added <strong>$added</strong> missing games to database.</p>";
echo "<p>Skipped <strong>$skipped</strong> already-existing games.</p>";
if ($errors > 0) {
    echo "<p style='color:red;'>Failed to insert <strong>$errors</strong> games (check DB errors).</p>";
}
echo '<p><a href="../dashboard.php?hal=gameimg">Return to Admin Dashboard</a></p>';
?>
