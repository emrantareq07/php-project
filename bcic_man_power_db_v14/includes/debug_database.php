<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

echo "<h2>Database Debug Information</h2>";

// Check officers_tbl data
echo "<h3>officers_tbl - Sample Data:</h3>";
$sql = "SELECT id, factory_name, date, g2, g3, g4 FROM officers_tbl LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Factory Name</th><th>Date</th><th>G2</th><th>G3</th><th>G4</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>'" . $row['factory_name'] . "'</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['g2'] . "</td>";
        echo "<td>" . $row['g3'] . "</td>";
        echo "<td>" . $row['g4'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found in officers_tbl<br>";
    echo "MySQL Error: " . $conn->error . "<br>";
}

// Check for May 2026 data specifically
echo "<h3>Data for May 2026 (2026-05):</h3>";
$sql2 = "SELECT COUNT(*) as total, factory_name, DATE_FORMAT(date, '%Y-%m') as month FROM officers_tbl WHERE DATE_FORMAT(date, '%Y-%m') = '2026-05' GROUP BY factory_name";
$result2 = $conn->query($sql2);

if ($result2 && $result2->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Total Records</th><th>Factory Name</th><th>Month</th></tr>";
    while($row = $result2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['total'] . "</td>";
        echo "<td>'" . $row['factory_name'] . "'</td>";
        echo "<td>" . $row['month'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found for May 2026 in officers_tbl<br>";
}

// Show session factory name
echo "<h3>Session Factory Name:</h3>";
echo "Factory Name: '" . ($_SESSION['factory_name'] ?? 'Not set') . "'<br>";
echo "Username: '" . ($_SESSION['username'] ?? 'Not set') . "'<br>";

// Check exact match
echo "<h3>Testing Exact Match:</h3>";
$test_factory = trim($_SESSION['factory_name'] ?? '');
$sql3 = "SELECT * FROM officers_tbl WHERE TRIM(factory_name) = TRIM('$test_factory') LIMIT 5";
$result3 = $conn->query($sql3);
if ($result3 && $result3->num_rows > 0) {
    echo "Found " . $result3->num_rows . " records matching exactly!<br>";
} else {
    echo "No exact matches found for: '$test_factory'<br>";
}

// Show all distinct factory names
echo "<h3>All Distinct Factory Names in officers_tbl:</h3>";
$sql4 = "SELECT DISTINCT TRIM(factory_name) as factory_name FROM officers_tbl LIMIT 20";
$result4 = $conn->query($sql4);
if ($result4 && $result4->num_rows > 0) {
    echo "<ul>";
    while($row = $result4->fetch_assoc()) {
        echo "<li>'" . $row['factory_name'] . "'</li>";
    }
    echo "</ul>";
} else {
    echo "No factory names found<br>";
}
?>