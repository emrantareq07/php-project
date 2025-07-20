<?php
//Start the session
session_start();
// $table=$_SESSION['username'];

if(isset($_GET['id'])){
$table=$_GET['to_office'];
	}
	else{
		$table=$_SESSION['username'];
	}

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
// if(($_SESSION['username']!=="$table") || ($_SESSION['role']!=="user")){
//    header("location: dashboard.php");
// }

include('../db/db.php');

//add pipeline 

if(isset($_GET['id'])){
	$id=$_GET['id'];
	$date = date('Y-m-d');
	// $pipeline=$_POST['pipeline'];	

$day=date('d',strtotime($date));

$month=date('m',strtotime($date));

//echo $month;
$year=date('Y',strtotime($date));


if($month==7){
	$month_id=$year.'1';
}
elseif ($month==8){
	$month_id=$year.'2';
}
elseif ($month==9){
	$month_id=$year.'3';
}
elseif ($month==10){
	$month_id=$year.'4';
}
elseif ($month==11){
	$month_id=$year.'5';
}
elseif ($month==12){
	$month_id=$year.'6';
}
elseif ($month==1){
	$month_id=$year.'7';
}
elseif ($month==2){
	$month_id=$year.'8';
}
elseif ($month==3){
	$month_id=$year.'9';
}
elseif ($month==4){
	$month_id=$year.'10';
}
elseif ($month==5){
	$month_id=$year.'11';
}
elseif ($month==6){
	$month_id=$year.'12';
}

//echo $month_id;

$sql = "SELECT * FROM $table where `date` LIKE'{$date}%'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){

// for monthly demand
$sql_monthly_demand = "SELECT * FROM $table where month_id LIKE'{$month_id}%'";
$result_monthly_demand = mysqli_query($conn, $sql_monthly_demand);

if(mysqli_num_rows($result_monthly_demand) == 0){
$sql_monthly_demand1="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand1  = mysqli_query($conn, $sql_monthly_demand1);
}
else{
$sql_monthly_demand2="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand2  = mysqli_query($conn, $sql_monthly_demand2);
}
// end monthly demand set


$sql1 = "SELECT * FROM $table where id=(select max(id) from $table)";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$pipeline=$row1['pipeline'];
$total_stock=$row1['total_stock'];

$sql_pipline = "SELECT amount FROM pipeline where id=$id";
$result_pipline = mysqli_query($conn, $sql_pipline);
$row_pipline = mysqli_fetch_assoc($result_pipline);
$pipeline_amount=$row_pipline['amount'];
$pipeline=$pipeline + $pipeline_amount;
 
$sql_target_table="INSERT INTO $table (date,total_stock,pipeline,month_id) values('{$date}','{$total_stock}','{$pipeline}','{$month_id}')";
$result_target_table = mysqli_query($conn, $sql_target_table);

$sql_target_table="UPDATE pipeline SET status='accept' WHERE id='$id' ";
$result_target_table = mysqli_query($conn, $sql_target_table);

  header("location:urea_form.php");
  exit(); 
} 
//main else
else {	
	$row = mysqli_fetch_assoc($result);
	$id1=$row['id'];
	$pipeline=$row['pipeline'];
	    
	$sql_pipline = "SELECT amount FROM pipeline where id=$id";
	$result_pipline = mysqli_query($conn, $sql_pipline);
	$row_pipline = mysqli_fetch_assoc($result_pipline);
	$pipeline_amount=$row_pipline['amount'];
	$pipeline=$pipeline + $pipeline_amount;

	$query = "UPDATE $table SET  pipeline='$pipeline' WHERE id='$id1' ";
    $query_run = mysqli_query($conn, $query);

    $sql_target_table="UPDATE pipeline SET status='accept' WHERE id='$id' ";
	$result_target_table = mysqli_query($conn, $sql_target_table);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: urea_form.php");
        exit(0);
    	}	
	}
}

// for uere pipeline
// if(isset($_POST['submit_pipeline'])){
// 	$date=$_POST['date'];
// 	$buffer_name=$_POST['buffer_name'];
// 	$pipeline=$_POST['pipeline'];

// 	if($_POST['buffer_name']=='kaliganj_buffer'){
// 		$table='kaliganj_buffer';
// 	}
// 	elseif($_POST['buffer_name']=='shiromoni_buffer'){
// 		$table='shiromoni_buffer';
// 	}
// 	elseif($_POST['buffer_name']=='jessore_buffer'){
// 		$table='jessore_buffer';
// 	}	

// $day=date('d',strtotime($date));

// $month=date('m',strtotime($date));

// //echo $month;
// $year=date('Y',strtotime($date));

// if($month==7){
// 	$month_id=$year.'1';
// }
// elseif ($month==8){
// 	$month_id=$year.'2';
// }
// elseif ($month==9){
// 	$month_id=$year.'3';
// }
// elseif ($month==10){
// 	$month_id=$year.'4';
// }
// elseif ($month==11){
// 	$month_id=$year.'5';
// }
// elseif ($month==12){
// 	$month_id=$year.'6';
// }
// elseif ($month==1){
// 	$month_id=$year.'7';
// }
// elseif ($month==2){
// 	$month_id=$year.'8';
// }
// elseif ($month==3){
// 	$month_id=$year.'9';
// }
// elseif ($month==4){
// 	$month_id=$year.'10';
// }
// elseif ($month==5){
// 	$month_id=$year.'11';
// }
// elseif ($month==6){
// 	$month_id=$year.'12';
// }

// //echo $month_id;

// $sql = "SELECT * FROM $table where `date` LIKE'{$date}%'";
// $result = mysqli_query($conn, $sql);

// if(mysqli_num_rows($result) == 0){
// $total_stock1=$total_stock1+$pipeline;
 
// $sql_target_table="INSERT INTO $table (total_stock,pipeline) values('{$total_stock1}','{$pipeline}')";
// $result_target_table = mysqli_query($conn, $sql_target_table);

//   header("location:pipeline.php");
//   exit(); 
// } 
// //main else
// else {	

// 	$row = mysqli_fetch_assoc($result);
// 	$total_stock=$row['total_stock'];
// 	$pipeline=$row['pipeline'];
	    
// 	$id=  $row['id'];

// 	$total_stock=$total_stock+$pipeline;

// 	$query = "UPDATE $table SET  total_stock='$total_stock', pipeline='$pipeline' WHERE id='$id' ";
//     $query_run = mysqli_query($conn, $query);

//     if($query_run) {
//         $_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
//         header("Location: pipeline.php");
//         exit(0);
//     	}

	
// 	} 

// }
// for delivery
if(isset($_POST['submit_delivery'])){

$date=$_POST['date'];
$delivery1=$_POST['delivery'];
$total_stock=$_POST['total_stock'];

$day=date('d',strtotime($date));

$month=date('m',strtotime($date));

//echo $month;
$year=date('Y',strtotime($date));

if($month==7){
	$month_id=$year.'1';
}
elseif ($month==8){
	$month_id=$year.'2';
}
elseif ($month==9){
	$month_id=$year.'3';
}
elseif ($month==10){
	$month_id=$year.'4';
}
elseif ($month==11){
	$month_id=$year.'5';
}
elseif ($month==12){
	$month_id=$year.'6';
}
elseif ($month==1){
	$month_id=$year.'7';
}
elseif ($month==2){
	$month_id=$year.'8';
}
elseif ($month==3){
	$month_id=$year.'9';
}
elseif ($month==4){
	$month_id=$year.'10';
}
elseif ($month==5){
	$month_id=$year.'11';
}
elseif ($month==6){
	$month_id=$year.'12';
}

//echo $month_id;

$sql = "SELECT * FROM $table where `date` LIKE'{$date}%'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){

// for monthly demand
$sql_monthly_demand = "SELECT * FROM $table where month_id LIKE'{$month_id}%'";
$result_monthly_demand = mysqli_query($conn, $sql_monthly_demand);

if(mysqli_num_rows($result_monthly_demand) == 0){
$sql_monthly_demand1="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand1  = mysqli_query($conn, $sql_monthly_demand1);
}
else{
$sql_monthly_demand2="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand2  = mysqli_query($conn, $sql_monthly_demand2);
}	// end monthly demand


$total_stock=$total_stock-$delivery1;
$concat_delivery = $concat_delivery . '+';
 
$sql_target_table="INSERT INTO $table (date,delivery,total_stock,month_id,concat_delivery) values('{$date}','{$delivery1}','{$total_stock}','{$month_id}','{$concat_delivery}')";
$result_target_table = mysqli_query($conn, $sql_target_table);

  header("location:urea_form.php");
  exit(); 
} 
//main else
else {	
	$row = mysqli_fetch_assoc($result);
	$total_stock_db=$row['total_stock'];
	// $receive_import1=$row['receive_import'];
	// $receive_factory1=  $row['receive_factory'];
	$concat_delivery_db =$row['concat_delivery'];
	$delivery_db=  $row['delivery'];    
	$id_db=  $row['id'];

	$delivery=$delivery_db + $delivery1;
	$concat_delivery = $concat_delivery_db . $delivery1.'+';
	$total_stock=$total_stock_db-$delivery1;

	$query = "UPDATE $table SET  delivery='$delivery', total_stock='$total_stock', concat_delivery='$concat_delivery' WHERE id='$id_db' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: urea_form.php");
        exit(0);
    	}	
	} 
}
// for insert
if(isset($_POST['submit_form'])){
$date=$_POST['date'];
$receive_import=$_POST['receive_import'];
$receive_factory=$_POST['receive_factory'];

if($receive_import==null){
	$receive_import=0;
}
if($receive_factory==null){
	$receive_factory=0;
}
$total_stock1=$_POST['total_stock'];
$day=date('d',strtotime($date));
$month=date('m',strtotime($date));
//echo $month;
$year=date('Y',strtotime($date));
if($month==7){
	$month_id=$year.'1';
}
elseif ($month==8){
	$month_id=$year.'2';
}
elseif ($month==9){
	$month_id=$year.'3';
}
elseif ($month==10){
	$month_id=$year.'4';
}
elseif ($month==11){
	$month_id=$year.'5';
}
elseif ($month==12){
	$month_id=$year.'6';
}
elseif ($month==1){
	$month_id=$year.'7';
}
elseif ($month==2){
	$month_id=$year.'8';
}
elseif ($month==3){
	$month_id=$year.'9';
}
elseif ($month==4){
	$month_id=$year.'10';
}
elseif ($month==5){
	$month_id=$year.'11';
}
elseif ($month==6){
	$month_id=$year.'12';
}

//echo $month_id;

$sql = "SELECT * FROM $table where `date` LIKE'{$date}%'";
$result = mysqli_query($conn, $sql);

$sql1 = "SELECT * FROM $table where id=(select max(id) from $table)";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$pipeline_db=$row1['pipeline'];

if(mysqli_num_rows($result) == 0){
// for monthly demand
$sql_monthly_demand = "SELECT * FROM $table where month_id='$month_id'";
$result_monthly_demand = mysqli_query($conn, $sql_monthly_demand);

if(mysqli_num_rows($result_monthly_demand) == 0){
$sql_monthly_demand1="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand1  = mysqli_query($conn, $sql_monthly_demand1);
}else{
$sql_monthly_demand2="INSERT INTO monthly_demand (date,office_name,month_id) values('{$date}','{$table}','{$month_id}')";
$result_monthly_demand2  = mysqli_query($conn, $sql_monthly_demand2);
}	// end monthly demand

$total_stock1=$total_stock1+$receive_import+$receive_factory;
$pipeline=$pipeline_db - ($receive_import+$receive_factory);
$concat_receive = $receive_import . '+' . $receive_factory . '+';
 
$sql_target_table="INSERT INTO $table (date,receive_import,receive_factory,total_stock,pipeline,month_id,concat_receive) values('{$date}','{$receive_import}','{$receive_factory}','{$total_stock1}','{$pipeline}','{$month_id}','{$concat_receive}')";
$result_target_table = mysqli_query($conn, $sql_target_table);

  header("location:urea_form.php");
  exit(); 
} 
//main else
else {	

	// $row = mysqli_fetch_assoc($result);
	// $total_stock=$row['total_stock'];//50
	// $receive_import1=$row['receive_import'];
	// $receive_factory1=  $row['receive_factory'];
	// $delivery=  $row['delivery'];    
	// $id=  $row['id'];

	// $temp_stock= $total_stock - ($receive_import1+$receive_factory1-$delivery);
	// $total_stock = $temp_stock+ ($receive_import+$receive_factory-$delivery);
	// $pipeline1=$pipeline + ($receive_import1+$receive_factory1);
	// $pipeline=$pipeline1 - ($receive_import+$receive_factory);


	// $query = "UPDATE $table SET  receive_import='$receive_import',receive_factory='$receive_factory', total_stock='$total_stock', pipeline='$pipeline' WHERE id='$id' ";
 //    $query_run = mysqli_query($conn, $query);

 //    if($query_run) {
 //        $_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
 //        header("Location: urea_form.php");
 //        exit(0);
 //    	}


	$row = mysqli_fetch_assoc($result);
	$total_stock_db=$row['total_stock'];//50 
	$id_db=  $row['id'];
	$receive_import_db=$row['receive_import'];
	$receive_factory_db=  $row['receive_factory'];
	$delivery_db=  $row['delivery'];
	$concat_receive_db= $row['concat_receive'];

	$receive_import_dbin=$receive_import_db+$receive_import;
	$receive_factory_dbin=$receive_factory_db+$receive_factory;
	$total_stock=$total_stock_db+($receive_import+$receive_factory);
	$pipeline=$pipeline_db - ($receive_import+$receive_factory);
	$concat_receive = $concat_receive_db . $receive_import . '+'.$receive_factory.'+';
	
	// $total_stock = $total_stock+ ($receive_import+$receive_factory);
	// $pipeline1=$pipeline_db + ($receive_import1+$receive_factory1);
	// $pipeline=$pipeline1 - ($receive_import+$receive_factory);
	// $concat_receive_db = $receive_import . '+' . $receive_factory . '+';
	
	$query = "UPDATE $table SET  receive_import='$receive_import_dbin',receive_factory='$receive_factory_dbin', total_stock='$total_stock', pipeline='$pipeline', concat_receive='$concat_receive' WHERE id='$id_db' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: urea_form.php");
        exit(0);
    	}	
	} 
}//end main if

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 
mysqli_close($conn);
 
 ?>