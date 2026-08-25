<?php
require 'assets/data.php';

echo "<h2>Database Diagnostics</h2>";

if (!$data) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

echo "<h3>1. Providers in `providers` table:</h3>";
$res1 = mysqli_query($data, "SELECT id, provider_code, provider_name, is_active FROM providers");
if ($res1) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Code</th><th>Name</th><th>Active</th></tr>";
    while ($row = mysqli_fetch_assoc($res1)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['provider_code']}</td><td>{$row['provider_name']}</td><td>{$row['is_active']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error querying providers: " . mysqli_error($data);
}

echo "<h3>2. Distinct `demo_provider` values in `demo_games`:</h3>";
$res2 = mysqli_query($data, "SELECT demo_provider, COUNT(*) as count FROM demo_games GROUP BY demo_provider");
if ($res2) {
    echo "<table border='1' cellpadding='5'><tr><th>demo_provider</th><th>Count</th></tr>";
    while ($row = mysqli_fetch_assoc($res2)) {
        echo "<tr><td>{$row['demo_provider']}</td><td>{$row['count']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error querying demo_games: " . mysqli_error($data);
}

echo "<h3>3. Total rows in `demo_games`:</h3>";
$res3 = mysqli_query($data, "SELECT COUNT(*) as total FROM demo_games");
if ($res3) {
    $row = mysqli_fetch_assoc($res3);
    echo "<p>Total games: <strong>" . $row['total'] . "</strong></p>";
}
?>
