<?php
session_start();

$table = $_POST['table1'];

include 'model_promotion_due.php';

$model = new Model();

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $rows = $model->date_range($start_date, $end_date, $table);
} else {
    $rows = $model->fetch($table);
}

echo json_encode($rows);
?>
