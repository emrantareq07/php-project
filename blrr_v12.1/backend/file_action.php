<?php
session_name('blrr');
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once("config.php");
include_once '../db/database.php';

// Helper: convert Bangla to English digits
function banglaToEnglishNumber($banglaNumber) {
    return str_replace(['০','১','২','৩','৪','৫','৬','৭','৮','৯'], ['0','1','2','3','4','5','6','7','8','9'], $banglaNumber);
}
// English to Bangla
function englishToBanglaNumber($number) {
    return str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $number);
}

$action = $_GET['action'] ?? '';

switch($action){

case 'fetch':
    $columns = ['id','entry_date','recipient','immediate_sender_office','div_dept_office','section_dept','d_number','sign_date','div_sign_date','subject','destination_dropfile','comments'];
    $limit = intval($_POST['length'] ?? 10);
    $start = intval($_POST['start'] ?? 0);
    $orderIdx = intval($_POST['order'][0]['column'] ?? 0);
    $order_col = $columns[$orderIdx] ?? 'id';
    $order_dir = ($_POST['order'][0]['dir'] ?? 'DESC') === 'asc' ? 'ASC' : 'DESC';
    $search_value = $conn->real_escape_string($_POST['search']['value'] ?? '');

    $where = "";
    if($search_value){
        $where = "WHERE recipient LIKE '%$search_value%' OR subject LIKE '%$search_value%' OR destination_dropfile LIKE '%$search_value%'";
    }

    $totalData = $conn->query("SELECT COUNT(*) as cnt FROM chairmanfile")->fetch_assoc()['cnt'];
    $totalFiltered = $totalData;
    if($where){
        $totalFiltered = $conn->query("SELECT COUNT(*) as cnt FROM chairmanfile $where")->fetch_assoc()['cnt'];
    }

    $query = "SELECT * FROM chairmanfile $where ORDER BY d_number DESC, $order_col $order_dir LIMIT $start, $limit";
    $res = $conn->query($query);

    $data=[];
    while($row=$res->fetch_assoc()){
        // present d_number in Bangla
        $row['d_number'] = englishToBanglaNumber($row['d_number']);
        $data[] = $row;
    }

    echo json_encode([
        'draw'=> intval($_POST['draw'] ?? 0),
        'recordsTotal'=> intval($totalData),
        'recordsFiltered'=> intval($totalFiltered),
        'data'=> $data
    ]);
    break;

case 'next_d_number':
    // return next d_number for given year (in Bangla)
    $year = intval($_GET['year'] ?? date('Y'));
    // use YEAR(entry_date) to find rows of that year
    $sql = $conn->prepare("SELECT MAX(d_number) AS max_d FROM chairmanfile WHERE YEAR(entry_date)=?");
    $sql->bind_param("i", $year);
    $sql->execute();
    $res = $sql->get_result()->fetch_assoc();
    $max = $res['max_d'] ?? 0;
    $next = (int)$max + 1;
    echo json_encode(['status'=>200, 'next_d_number'=>englishToBanglaNumber($next)]);
    break;

case 'save':
    // sanitize & collect
    $id = intval($_POST['id'] ?? 0);
    $entry_date = $_POST['entry_date'] ?? '';
    $recipient = $_POST['recipient'] ?? '';
    $immediate_sender_office = $_POST['immediate_sender_office'] ?? '';
    $div_dept_office = $_POST['div_dept_office'] ?? '';
    $section_dept = $_POST['section_dept'] ?? '';
    $d_number = banglaToEnglishNumber($_POST['d_number'] ?? '0'); // may already be english
    $div_sign_date = $_POST['div_sign_date'] ?? '';
    $sign_date = $_POST['sign_date'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $destination_dropfile = isset($_POST['destination_dropfile']) ? implode(',', $_POST['destination_dropfile']) : '';
    $comments = $_POST['comments'] ?? '';

    // validate minimal fields
    if(!$entry_date || !$recipient){
        echo json_encode(['status'=>400,'message'=>'Entry date and recipient required']);
        exit;
    }

    if($id > 0){
        // UPDATE
        $stmt = $conn->prepare("UPDATE chairmanfile SET entry_date=?, recipient=?, immediate_sender_office=?, div_dept_office=?, section_dept=?, d_number=?, div_sign_date=?, sign_date=?, subject=?, destination_dropfile=?, comments=? WHERE id=?");
        if(!$stmt){
            echo json_encode(['status'=>500,'message'=>'Prepare failed: '.$conn->error]);
            exit;
        }
        // types: s - string, i - integer
        $dnum_int = intval($d_number);
        $stmt->bind_param("sssssiissssi",
            $entry_date,
            $recipient,
            $immediate_sender_office,
            $div_dept_office,
            $section_dept,
            $dnum_int,
            $div_sign_date,
            $sign_date,
            $subject,
            $destination_dropfile,
            $comments,
            $id
        );
        if($stmt->execute()){
            echo json_encode(['status'=>200,'message'=>'Updated successfully']);
        } else {
            echo json_encode(['status'=>500,'message'=>'Update failed: '.$stmt->error]);
        }
        $stmt->close();
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO chairmanfile (entry_date, recipient, immediate_sender_office, div_dept_office, section_dept, d_number, div_sign_date, sign_date, subject, destination_dropfile, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if(!$stmt){
            echo json_encode(['status'=>500,'message'=>'Prepare failed: '.$conn->error]);
            exit;
        }
        $dnum_int = intval($d_number);
        $stmt->bind_param("sssssiissss",
            $entry_date,
            $recipient,
            $immediate_sender_office,
            $div_dept_office,
            $section_dept,
            $dnum_int,
            $div_sign_date,
            $sign_date,
            $subject,
            $destination_dropfile,
            $comments
        );
        if($stmt->execute()){
            echo json_encode(['status'=>200,'message'=>'Inserted successfully']);
        } else {
            echo json_encode(['status'=>500,'message'=>'Insert failed: '.$stmt->error]);
        }
        $stmt->close();
    }
    break;

case 'get':
    $id=intval($_GET['id'] ?? 0);
    $row = $conn->query("SELECT * FROM chairmanfile WHERE id=$id")->fetch_assoc();
    if($row){
        // convert d_number to Bangla for display in modal
        $row['d_number'] = englishToBanglaNumber($row['d_number']);
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
    break;

case 'delete':
    $id=intval($_POST['id'] ?? 0);
    if($conn->query("DELETE FROM chairmanfile WHERE id=$id")){
        echo json_encode(['status'=>200,'message'=>'Deleted successfully']);
    } else {
        echo json_encode(['status'=>500,'message'=>'Delete failed']);
    }
    break;

default:
    echo json_encode(['status'=>400,'message'=>'Invalid action']);
    break;
}
