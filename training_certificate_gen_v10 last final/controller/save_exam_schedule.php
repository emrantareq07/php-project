<?php
include 'db.php';
if(isset($_POST['batch'])){
    $batch = (int)$_POST['batch'];
    $exam_date = $_POST['exam_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $active_exam = $_POST['active_exam'];

    $stmt = $conn->prepare("UPDATE authority_tbl SET exam_date=?, start_time=?, end_time=?, active_exam=? WHERE batch=?");
    $stmt->bind_param("ssssi",$exam_date,$start_time,$end_time,$active_exam,$batch);
    $stmt->execute();
    $stmt->close();
    echo "success";
    exit;
}
echo "error";
