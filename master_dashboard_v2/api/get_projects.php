<?php
include "../db.php";

$records=[];

$res=$conn->query("SELECT * FROM projects WHERE is_active=1 ORDER BY id DESC");

while($row=$res->fetch_assoc()){
$records[]=$row;
}

$total=$conn->query("SELECT COUNT(*) c FROM projects")->fetch_assoc();
$active=$conn->query("SELECT COUNT(*) c FROM projects WHERE status='Active'")->fetch_assoc();
$maintenance=$conn->query("SELECT COUNT(*) c FROM projects WHERE status='Maintenance'")->fetch_assoc();
$development=$conn->query("SELECT COUNT(*) c FROM projects WHERE status='Development'")->fetch_assoc();

$stats=[
"total"=>$total['c'],
"active"=>$active['c'],
"maintenance"=>$maintenance['c'],
"development"=>$development['c']
];

echo json_encode([
"records"=>$records,
"stats"=>$stats
]);