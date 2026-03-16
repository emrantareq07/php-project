<?php
// api/get_stats.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../models/Project.php';

$project = new Project();
$stats = $project->getStats();

echo json_encode($stats);
?>