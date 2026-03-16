<?php
require_once("../config/database.php");

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$id]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD']=="POST"){

$name = $_POST['project_name'];
$url = $_POST['project_url'];
$category = $_POST['category'];
$status = $_POST['status'];
$desc = $_POST['description'];

$q = "UPDATE projects SET
project_name=?,
project_url=?,
category=?,
status=?,
description=?
WHERE id=?";

$stmt=$db->prepare($q);
$stmt->execute([$name,$url,$category,$status,$desc,$id]);

header("Location:../index.php");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Project</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<h3>Edit Project</h3>

<form method="POST">

<div class="mb-3">
<label>Project Name</label>
<input type="text" name="project_name" class="form-control"
value="<?= $project['project_name'] ?>" required>
</div>

<div class="mb-3">
<label>Project URL</label>
<input type="text" name="project_url" class="form-control"
value="<?= $project['project_url'] ?>">
</div>

<div class="mb-3">
<label>Category</label>
<select name="category" class="form-control">

<option <?= $project['category']=="CMS"?'selected':'' ?>>CMS</option>
<option <?= $project['category']=="CRM"?'selected':'' ?>>CRM</option>
<option <?= $project['category']=="Dashboard"?'selected':'' ?>>Dashboard</option>
<option <?= $project['category']=="API"?'selected':'' ?>>API</option>

</select>
</div>

<div class="mb-3">
<label>Status</label>

<select name="status" class="form-control">

<option <?= $project['status']=="Active"?'selected':'' ?>>Active</option>
<option <?= $project['status']=="Maintenance"?'selected':'' ?>>Maintenance</option>
<option <?= $project['status']=="Development"?'selected':'' ?>>Development</option>

</select>

</div>

<div class="mb-3">
<label>Description</label>

<textarea name="description" class="form-control"><?= $project['description'] ?></textarea>

</div>

<button class="btn btn-primary">Update Project</button>

<a href="index.php" class="btn btn-secondary">Back</a>

</form>

</div>

</body>
</html>