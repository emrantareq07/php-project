<?php
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
 // echo $user_type;
include_once '../db/database.php';
if(count($_POST)>0){
	if($_POST['type']==1){
		$date=$_POST['date'];
		$time = $_POST['time']; // Assuming the time is in 'HH:MM' format (24-hour)
		// $time = date("g:i A", strtotime($_POST['time']));
		$subject=$_POST['subject'];
		$place=$_POST['place'];

		$status='Incomplete';

		$zoom_id=$_POST['zoom_id'];
		$zoom_passcode=$_POST['zoom_passcode'];
		$focal_point=$_POST['focal_point'];
		$zoom_link=$_POST['zoom_link'];
		$president=$_POST['president'];
		$date_for_month=$date;
		$month=date('F', strtotime($date_for_month));

		if(($zoom_id=='' || $zoom_passcode)==''){
			$zoom_id='';
			$zoom_passcode='';
		}

if ( $date && $time  && $subject  && $place ) {		
		$sql = "INSERT INTO `$table`( `date`, `time`,`subject`,`focal_point`,`zoom_id`,`zoom_passcode`,`zoom_link`,`president`,`place`,`status`,`month`) 
		VALUES ('$date','$time','$subject','$focal_point','$zoom_id','$zoom_passcode','$zoom_link','$president','$place','$status','$month')";
		  if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode" => 200));
        } else {
            echo json_encode(array("statusCode" => 201, "error" => mysqli_error($conn)));
        }
    } else {
        echo json_encode(array("statusCode" => 201, "error" => "Required fields missing"));
    }
    mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$id=$_POST['id'];
		$date=$_POST['date'];
		$time=$_POST['time'];
		// $time = date("g:i A", strtotime($_POST['time']));
		$subject=$_POST['subject'];
		$zoom_id=$_POST['zoom_id'];
		$zoom_passcode=$_POST['zoom_passcode'];
		$zoom_link=$_POST['zoom_link'];
		$place=$_POST['place'];
		//$status=$_POST['status'];
		$president=$_POST['president'];
		$focal_point=$_POST['focal_point'];

		if(($zoom_id=='' || $zoom_passcode)==''){
			$zoom_id='';
			$zoom_passcode='';
		}
		if ( $date && $time  && $subject && $place) {
		$sql = "UPDATE `$table` SET `date`='$date',`time`='$time',`subject`='$subject',`focal_point`='$focal_point',`zoom_id`='$zoom_id',`zoom_passcode`='$zoom_passcode',`zoom_link`='$zoom_link',`president`='$president',`place`='$place' WHERE id=$id";
		  if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode" => 200));
        } else {
            echo json_encode(array("statusCode" => 201, "error" => mysqli_error($conn)));
        }
    } else {
        echo json_encode(array("statusCode" => 201, "error" => "Required fields missing"));
    }
    mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM `$table` WHERE id=$id ";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==4){
		$id=$_POST['id'];
		$sql = "DELETE FROM $table WHERE id in ($id)";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}

?>