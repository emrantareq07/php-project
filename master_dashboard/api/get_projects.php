<?php
// api/get_projects.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../models/Project.php';

$project = new Project();

// Get search parameter if any
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

if(!empty($search)) {
    $stmt = $project->search($search);
} elseif(!empty($category) && $category != 'All') {
    $stmt = $project->filterByCategory($category);
} else {
    $stmt = $project->readAll();
}

$projects_arr = array();
$projects_arr["records"] = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    
    $project_item = array(
        "id" => $id,
        "project_name" => $project_name,
        "project_url" => $project_url,
        "category" => $category,
        "status" => $status,
        "icon_color" => $icon_color,
        "description" => $description,
        "created_at" => $created_at
    );
    
    array_push($projects_arr["records"], $project_item);
}

// Get statistics
$stats = $project->getStats();
$projects_arr["stats"] = $stats;

echo json_encode($projects_arr);
?>