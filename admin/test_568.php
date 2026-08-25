<?php
// Find and include the DB connection
$files = ['data.php', '../assets/data.php', 'config/data.php', '../config/data.php'];
foreach ($files as $f) {
    if (file_exists($f)) {
        include $f;
        echo "DB Loaded from: $f<br>";
        break;
    }
}

if (!isset($data)) {
    echo "ERROR: \$data connection not found!<br>";
    exit;
}

echo "DB Connection: " . (mysqli_ping($data) ? "OK" : "FAILED") . "<br>";

$where = "WHERE LOWER(demo_provider) = LOWER('568win')";
$q = mysqli_query($data, "SELECT COUNT(*) as cnt FROM demo_games $where");
echo "Query Error: " . mysqli_error($data) . "<br>";
$row = mysqli_fetch_assoc($q);
echo "568win Count: " . $row['cnt'] . "<br><br>";

// Also show first 3 rows
$q2 = mysqli_query($data, "SELECT id, demo_name, demo_provider FROM demo_games $where LIMIT 3");
while ($r = mysqli_fetch_assoc($q2)) {
    echo $r['id'] . " | " . $r['demo_name'] . " | " . $r['demo_provider'] . "<br>";
}
