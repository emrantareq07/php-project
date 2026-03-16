<?php
// api/add_project.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../models/Project.php';

$project = new Project();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->project_name) &&
    !empty($data->project_url)
) {
    // Set project properties
    $project->project_name = $data->project_name;
    $project->project_url = $data->project_url;
    $project->category = $data->category ?? 'Other';
    $project->status = $data->status ?? 'Active';
    $project->icon_color = $data->icon_color ?? '#3498db';
    $project->description = $data->description ?? '';

    // Create the project
    if($project->create()) {
        http_response_code(201);
        echo json_encode(array(
            "success" => true,
            "message" => "Project added successfully.",
            "project" => array(
                "id" => $project->conn->lastInsertId(),
                "name" => $project->project_name,
                "url" => $project->project_url
            )
        ));
    } else {
        http_response_code(503);
        echo json_encode(array(
            "success" => false,
            "message" => "Unable to add project."
        ));
    }
} else {
    http_response_code(400);
    echo json_encode(array(
        "success" => false,
        "message" => "Unable to add project. Data is incomplete."
    ));
}
?>