<?php
session_name('man_power_db');
session_start();
header('Content-Type: application/json');
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$username = $_SESSION['username'];
$table = $username;

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'insert') {
    $id = $_POST['id'] ?? '';
    $reports_month = $_POST['reports_month'] ?? '';
    $factory_name = $_POST['factory_name'] ?? '';
    $employee_type = $_POST['employee_type'] ?? '';
    $division = $_POST['division'] ?? '';
    $department = $_POST['department'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $grade_class = $_POST['grade_class'] ?? '';
    $male = (int)($_POST['male'] ?? 0);
    $female = (int)($_POST['female'] ?? 0);
    $sanctioned_post = (int)($_POST['sanctioned_post'] ?? 0);
    $filled_post = (int)($_POST['filled_post'] ?? 0);
    $vacant_post = (int)($_POST['vacant_post'] ?? 0);
    $remarks = $_POST['remarks'] ?? '';

    if ($id) {
        $stmt = $conn->prepare("UPDATE $table SET reports_month=?, factory_name=?, employee_type=?, division=?, department=?, designation=?, grade=?, grade_class=?, male=?, female=?, sanctioned_post=?, filled_post=?, vacant_post=?, remarks=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("sssssssiiiiisii", $reports_month, $factory_name, $employee_type, $division, $department, $designation, $grade, $grade_class, $male, $female, $sanctioned_post, $filled_post, $vacant_post, $remarks, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO $table (reports_month,factory_name,employee_type,division,department,designation,grade,grade_class,male,female,sanctioned_post,filled_post,vacant_post,remarks,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())");
        $stmt->bind_param("sssssssiiiiiss", $reports_month, $factory_name, $employee_type, $division, $department, $designation, $grade, $grade_class, $male, $female, $sanctioned_post, $filled_post, $vacant_post, $remarks);
    }

    if ($stmt->execute()) echo json_encode(['status'=>'success']);
    else echo json_encode(['status'=>'error','message'=>$stmt->error]);
}

elseif ($action === 'fetch') {
    $data = [];
    $result = $conn->query("SELECT * FROM $table ORDER BY id DESC");
    while($row=$result->fetch_assoc()) $data[]=$row;
    echo json_encode(['data'=>$data]);
}

elseif ($action === 'delete') {
    $id=(int)$_POST['id'];
    $conn->query("DELETE FROM $table WHERE id=$id");
    echo json_encode(['status'=>'success']);
}

elseif ($action === 'fetch_chart') {
    $res = $conn->query("SELECT employee_type, SUM(male) AS total_male, SUM(female) AS total_female FROM $table GROUP BY employee_type");
    $chart=[];
    while($r=$res->fetch_assoc()) $chart[]=$r;
    echo json_encode(['data'=>$chart]);
}
?>
