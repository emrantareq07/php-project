<?php
// controller/ajax_action_other_quali.php
//error_reporting(E_ALL); ini_set('display_errors', 1);
include_once '../config/Database.php';
include_once '../class/ReecordsOthers.php';

$database = new Database();
$db = $database->getConnection();

$record = new ReecordsOthers($db);

if (!empty($_POST['action']) && $_POST['action'] == 'listRecords') {
     $record->listRecords();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addRecord') {
	// $record->user_id = $_SESSION["user_id"];
	$record->emp_id = $_SESSION["emp_id"];	
	$record->degree_name = $_POST["degree_name"];
    $record->subject = $_POST["subject"];
    $record->university = $_POST["university"];
    $record->country = $_POST["country"];
    $record->course_duration = $_POST["course_duration"];
	$record->addRecord();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getRecord') {
	$record->id = $_POST["id"];
	$record->getRecord();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateRecord') {
	$record->id = $_POST["id"];
	$record->degree_name = $_POST["degree_name"];
    $record->subject = $_POST["subject"];
    $record->university = $_POST["university"];
    $record->country = $_POST["country"];
    $record->course_duration = $_POST["course_duration"];
	$record->updateRecord();
}

// if(!empty($_POST['action']) && $_POST['action'] == 'deleteRecord') {
// 	$record->id = $_POST["id"];
// 	$record->deleteRecord();
// }

if(!empty($_POST['action']) && $_POST['action'] == 'deleteRecord') {
    error_log("Delete action received."); // Add this line
    $record->id = $_POST["id"];
    if ($record->deleteRecord()) {
        error_log("Record deleted successfully."); // Add this line
    } else {
        error_log("Failed to delete record."); // Add this line
    }
}
?>