<?php
//error_reporting(E_ALL); ini_set('display_errors', 1);
include_once 'config/Database.php';
include_once 'class/Records.php';

$database = new Database();
$db = $database->getConnection();

$record = new Records($db);

if (!empty($_POST['action']) && $_POST['action'] == 'listRecords') {
     $record->listRecords();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addRecord') {
	// $record->user_id = $_SESSION["user_id"];
	$record->emp_id = $_SESSION["emp_id"];	
	$record->name = $_POST["name"];
    $record->dob = $_POST["dob"];
    $record->gender = $_POST["gender"];
    $record->institute = $_POST["institute"];
    $record->class = $_POST["class"];
	$record->addRecord();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getRecord') {
	$record->id = $_POST["id"];
	$record->getRecord();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateRecord') {
	$record->id = $_POST["id"];
	$record->emp_id = $_SESSION["emp_id"];
	$record->name = $_POST["name"];
    $record->dob = $_POST["dob"];
    $record->gender = $_POST["gender"];
    $record->institute = $_POST["institute"];
    $record->class = $_POST["class"];
	$record->updateRecord();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteRecord') {
	$record->id = $_POST["id"];
	$record->deleteRecord();
}
?>