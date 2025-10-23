<?php
session_name('blrr');
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once("config.php");
include_once '../db/database.php';

function banglaToEnglishNumber($banglaNumber) {
    return str_replace(['০','১','২','৩','৪','৫','৬','৭','৮','৯'], ['0','1','2','3','4','5','6','7','8','9'], $banglaNumber);
}

function englishToBanglaNumber($number) {
    return str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $number);
}

$action = $_GET['action'] ?? '';

switch($action){

case 'fetch':
    $columns = ['id','entry_date','recipient','d_number','send_date','subject','destination_dropfile','comments'];
    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order_col = $columns[$_POST['order'][0]['column']] ?? 'id';
    $order_dir = $_POST['order'][0]['dir'] ?? 'DESC';
    $search_value = $_POST['search']['value'] ?? '';

    $where = "";
    if($search_value){
        $search_value = $conn->real_escape_string($search_value);
        $where = "WHERE recipient LIKE '%$search_value%' 
                  OR subject LIKE '%$search_value%' 
                  OR destination_dropfile LIKE '%$search_value%'";
    }

    $totalData = $conn->query("SELECT COUNT(*) as cnt FROM chairmanfile")->fetch_assoc()['cnt'];
    $totalFiltered = $totalData;
    if($where){
        $totalFiltered = $conn->query("SELECT COUNT(*) as cnt FROM chairmanfile $where")->fetch_assoc()['cnt'];
    }

    $query = "SELECT * FROM chairmanfile $where ORDER BY d_number DESC, $order_col $order_dir LIMIT $start,$limit";

    $res = $conn->query($query);

    $data=[];
    while($row=$res->fetch_assoc()){
        $row['d_number'] = englishToBanglaNumber($row['d_number']);
        $data[] = $row;
    }

    echo json_encode([
        'draw'=> intval($_POST['draw']),
        'recordsTotal'=> intval($totalData),
        'recordsFiltered'=> intval($totalFiltered),
        'data'=> $data
    ]);
    break;

case 'next_d_number':
    $year_auto = date("Y");

    mysqli_begin_transaction($conn);
    try {
        $conn->query("LOCK TABLES chairmanfile WRITE");
        $row = $conn->query("SELECT MAX(d_number) AS max_d_number 
                             FROM chairmanfile 
                             WHERE entry_date LIKE '$year_auto%' FOR UPDATE")->fetch_assoc();
        $next_number = empty($row['max_d_number']) ? 1 : $row['max_d_number'] + 1;
        $conn->query("UNLOCK TABLES");
        mysqli_commit($conn);
        echo json_encode(['status'=>200, 'next_d_number'=>englishToBanglaNumber($next_number)]);
    } catch(Exception $e) {
        mysqli_rollback($conn);
        $conn->query("UNLOCK TABLES");
        echo json_encode(['status'=>500,'error'=>'Unable to generate next d_number']);
    }
    break;

case 'save':
    $id = intval($_POST['id'] ?? 0);
    $entry_date = $_POST['entry_date'] ?? '';
    $recipient = $_POST['recipient'] ?? '';
    $d_number = banglaToEnglishNumber($_POST['d_number'] ?? '0');
    $send_date = $_POST['send_date'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $destination_dropfile = implode(',', $_POST['destination_dropfile'] ?? []);
    $comments = $_POST['comments'] ?? '';

    if($id>0){
        // Update
        $stmt = $conn->prepare("UPDATE chairmanfile SET entry_date=?, recipient=?, d_number=?, send_date=?, subject=?, destination_dropfile=?, comments=? WHERE id=?");
        $stmt->bind_param("ssissssi", $entry_date, $recipient, $d_number, $send_date, $subject, $destination_dropfile, $comments, $id);
        $stmt->execute();
        echo json_encode(['status'=>200,'message'=>'Updated successfully']);
    }else{
        // Insert
        $stmt = $conn->prepare("INSERT INTO chairmanfile(entry_date,recipient,d_number,send_date,subject,destination_dropfile,comments) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("ssissss", $entry_date, $recipient, $d_number, $send_date, $subject, $destination_dropfile, $comments);
        $stmt->execute();
        echo json_encode(['status'=>200,'message'=>'Inserted successfully']);
    }
    break;

case 'get':
    $id=intval($_GET['id'] ?? 0);
    $row = $conn->query("SELECT * FROM chairmanfile WHERE id=$id")->fetch_assoc();
    echo json_encode($row);
    break;

case 'delete':
    $id=intval($_POST['id'] ?? 0);
    $conn->query("DELETE FROM chairmanfile WHERE id=$id");
    echo json_encode(['status'=>200,'message'=>'Deleted successfully']);
    break;

default:
    echo json_encode(['status'=>400,'message'=>'Invalid action']);
    break;
}
