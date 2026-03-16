

<?php
// api/get_projects.php
header('Content-Type: application/json');
include "../../db.php";

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM projects WHERE is_active=1";
$params = [];

if ($search) {
    $sql .= " AND project_name LIKE ?";
    $params[] = "%$search%";
}

if ($category && $category !== 'all') {
    $sql .= " AND category=?";
    $params[] = $category;
}

$stmt = $conn->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Stats
$stats = [
    'total' => count($records),
    'active' => count(array_filter($records, fn($r)=>$r['status']=='Active')),
    'maintenance' => count(array_filter($records, fn($r)=>$r['status']=='Maintenance')),
    'development' => count(array_filter($records, fn($r)=>$r['status']=='Development')),
];

echo json_encode(['records'=>$records, 'stats'=>$stats]);