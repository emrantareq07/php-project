<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost","root","","project_dashboard");

if($conn->connect_error){
    echo json_encode([
        "records"=>[],
        "stats"=>[]
    ]);
    exit;
}

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$where = "WHERE is_active=1";

if($search!=''){
    $search = $conn->real_escape_string($search);
    $where .= " AND project_name LIKE '%$search%'";
}

if($category!=''){
    $category = $conn->real_escape_string($category);
    $where .= " AND category='$category'";
}

$sql = "SELECT * FROM projects $where ORDER BY id DESC";
$res = $conn->query($sql);

$records = [];

while($row = $res->fetch_assoc()){
    $records[] = $row;
}

$stats = [];

$total = $conn->query("SELECT COUNT(*) c FROM projects")->fetch_assoc();
$active = $conn->query("SELECT COUNT(*) c FROM projects WHERE status='Active'")->fetch_assoc();
$maintenance = $conn->query("SELECT COUNT(*) c FROM projects WHERE status='Maintenance'")->fetch_assoc();
$development = $conn->query("SELECT COUNT(*) c FROM projects WHERE status='Development'")->fetch_assoc();

$stats = [
    "total"=>$total['c'],
    "active"=>$active['c'],
    "maintenance"=>$maintenance['c'],
    "development"=>$development['c']
];

echo json_encode([
    "records"=>$records,
    "stats"=>$stats
]);