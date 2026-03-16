<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? 0;
$id = (int)$id;

$stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$project = $res->fetch_assoc();

if(!$project){
    echo "<h2>Project not found</h2>";
    exit;
}

// Custom message based on status
$message = '';
if($project['status'] === 'Maintenance'){
    $message = "This project is under maintenance. Expected time to resume: soon!";
} elseif($project['status'] === 'Development'){
    $message = "This project is currently in development. Please check back later!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($project['project_name']); ?> Status</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1><?php echo htmlspecialchars($project['project_name']); ?></h1>
    <p><strong>Status:</strong> <?php echo $project['status']; ?></p>
    <div class="alert alert-info"><?php echo $message; ?></div>
    <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
</div>
</body>
</html>