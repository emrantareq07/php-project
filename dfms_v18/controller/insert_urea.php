<?php
//Start the session
session_name('dfms');
session_start();
if(isset($_GET['val'])){

$table = $_GET['val']; 
$user_type = $_GET['user_type'];
$_SESSION['username']=$table ;
$_SESSION['user_type']=$user_type;

}
else {
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
}

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../db/db.php');
if(isset($_POST['submit_form'])){
$date=$_POST['date'];
$product_produce=$_POST['product_produce'];
$plant_load=$_POST['plant_load'];
$daily=$_POST['daily'];
$remarks = $_POST['remarks'];
$remarks = mysqli_real_escape_string($conn, $remarks);

$month_id = date('Ym', strtotime($date));
$month_id1 = date('Y-m', strtotime($date));

$sql = "SELECT * FROM $table WHERE `date` LIKE '{$date}%' AND `product_produce` LIKE '{$product_produce}%'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
  // echo "Date already taken.";
  header("location:urea_form.php?already_exists=already_exists");
  exit(); 
} 
//main else
else { 

	$month11=date('m',strtotime($date));
	$year11=date('Y',strtotime($date));

if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
  $year22=$year11;
}
else{
  $year22=$year11-1;
}

$yearrange12="$year22-07-01";
$year22=$year22+1;
$yearrange13="$year22-06-30";

$sql_target_table_retrive = "SELECT * FROM target_table 
                             WHERE factory_name = '$table' 
                             AND fiscalstart = '$yearrange12' 
                             AND product_produce = '$product_produce'";

$result_target_table_retrive = mysqli_query($conn, $sql_target_table_retrive);
if(mysqli_num_rows($result_target_table_retrive)  == 0){

$target=0;
$sql_target_table = "INSERT INTO target_table (factory_name, product_produce, fiscalstart, fiscalend, target) 
                     VALUES ('$table', '$product_produce', '$yearrange12', '$yearrange13', '$target')";

$result_target_table = mysqli_query($conn, $sql_target_table);
}
//month target
$sql_target_table_retrive1 = "SELECT * FROM monthly_target 
              WHERE factory_name = '$table' 
              AND fiscalstart = '$yearrange12' 
              AND product_produce = '$product_produce' 
              AND target_date LIKE '$month_id1%' ";

$result_target_table_retrive1 = mysqli_query($conn, $sql_target_table_retrive1);
if(mysqli_num_rows($result_target_table_retrive1)  == 0){
$target=0;
$sql_target_table1 = "INSERT INTO monthly_target (factory_name, product_produce, fiscalstart, fiscalend, target, target_date) 
                      VALUES ('$table', '$product_produce', '$yearrange12', '$yearrange13', '$target', '$date')";
$result_target_table1 = mysqli_query($conn, $sql_target_table1);
}
//collect yearly target 
$sql_year = "SELECT * FROM target_table 
                             WHERE factory_name = '$table' 
                             AND fiscalstart = '$yearrange12' 
                             AND product_produce = '$product_produce'";
$result_year = mysqli_query($conn, $sql_year);
	$row_year = mysqli_fetch_assoc($result_year);
	$year_code=$row_year['id'];
//collect month target

$sql_month ="SELECT * FROM monthly_target 
              WHERE factory_name = '$table' 
              AND fiscalstart = '$yearrange12' 
              AND product_produce = '$product_produce' 
              AND target_date LIKE '$month_id1%' ";

$result_month = mysqli_query($conn, $sql_month);
$row_month = mysqli_fetch_assoc($result_month);
$month_code = $row_month['id'];
//end
$sql22 = "SELECT * FROM $table WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND product_produce = '$product_produce'";

$result22 = mysqli_query($conn, $sql22);
$rowcount_for_fiscal_year=mysqli_num_rows($result22);
while ($r = mysqli_fetch_assoc($result22)) {
    $date_db = $r['date'];

    if ($date_db == null) {
        $date_db = '0000-00-00';
    }
   $date22 = new DateTime($date_db);    
} 

$current_date = date("Y-m-d");
$current_date2 = new DateTime($current_date);
$now = new DateTime($date); //inputed date

	// Assuming $now and $date22 are defined somewhere before this code
	if($now < $date22) {
	    header("location:urea_form.php?past_dated=Back date error");
	    exit(); // Stop further execution
	}
	elseif($now > $current_date2) {
	    header("location:urea_form.php?advance_dated=Advanced date error");
	    exit(); // Stop further execution
	}
else{
	$sql_for_month="INSERT INTO $table(factory_name,product_produce,month_id,date,daily,month_code,year_code,plant_load,remarks) 
	VALUES('{$table}','{$product_produce}','{$month_id}','{$date}','{$daily}','{$month_code}','{$year_code}','{$plant_load}','{$remarks}')";
	 	
	  if (mysqli_query($conn, $sql_for_month)) {
	  // header("Location: success.php");
	  header("location:urea_form.php?submitted=successfully");
	} else {
	  echo "Error: " . $sql_for_month . "<br>" . mysqli_error($conn);
	}
	exit();
	  }
 		}		
	} 

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 
mysqli_close($conn);
 
 ?>