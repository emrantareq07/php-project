<!-- Optional: Create a refresh endpoint -->
<?php
// Save this as refresh_dashboard_data.php in the same directory

<?php
include('db/db.php');

$response = [
    'success' => true,
    'designation' => [],
    'training' => [],
    'posting' => []
];

// Fetch updated designation data
$sql = "SELECT d.designation, COUNT(e.id) as count 
        FROM employees e 
        LEFT JOIN designation d ON e.designation = d.designation 
        GROUP BY e.designation";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $response['designation']['labels'][] = $row['designation'];
    $response['designation']['data'][] = $row['count'];
}

// Fetch updated training data
$sql = "SELECT t.t_name, COUNT(e.id) as count 
        FROM employees e 
        LEFT JOIN training_list t ON e.training_title = t.t_name 
        GROUP BY e.training_title";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $response['training']['labels'][] = $row['t_name'];
    $response['training']['data'][] = $row['count'];
}

// Fetch updated posting data
$sql = "SELECT p.place_of_posting, COUNT(e.id) as count 
        FROM employees e 
        LEFT JOIN place_of_posting p ON e.place_of_posting = p.place_of_posting 
        GROUP BY e.place_of_posting";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $response['posting']['labels'][] = $row['place_of_posting'];
    $response['posting']['data'][] = $row['count'];
}

header('Content-Type: application/json');
echo json_encode($response);

?>