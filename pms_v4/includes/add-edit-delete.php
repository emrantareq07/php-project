<?php

// Database connection info 
  $host='localhost';
  $username='root';
  $password='';
  $dbname = "morning_glory_db";
  $conn=mysqli_connect($host,$username,$password,"$dbname");


if ($_POST['mode'] === 'add') {
     
     //$name = $_POST['name'];
     //$email = $_POST['email'];
     
	$notice=$_POST['notice'];
	$dated=$_POST['dated'];

  //if (isset($_FILES['pdf_file']['name']))
        //{
          $file_name = $_FILES['pdf_file']['name'];
          $file_tmp = $_FILES['pdf_file']['tmp_name'];
 
          move_uploaded_file($file_tmp,"./pdf/".$file_name);
 
          //$insertquery =
          //"INSERT INTO pdf_data(username,filename) VALUES('$name','$file_name')";
          //$iquery = mysqli_query($con, $insertquery);
        mysqli_query($conn, "INSERT INTO notice_board (notice,filename,dated) VALUES('{$notice}','{$file_name}','{$dated}')");
        echo json_encode(true);
        //}
        //else
        //{
           ?>
            <!--<div class="alert alert-danger alert-dismissible fade show text-center">
              <a class="close" data-dismiss="alert" aria-label="close">×</a>
              <strong>Failed!</strong>File must be uploaded in PDF format!
            </div>-->
          <?php
        //}
	 
    // mysqli_query($conn, "INSERT INTO notice_board (notice,dated) VALUES('{$notice}','{$dated}')");

     //echo json_encode(true);
}  

if ($_POST['mode'] === 'edit') {
    
    $result = mysqli_query($conn,"SELECT * FROM innovation_tbl WHERE id='" . $_POST['id'] . "'");
    $row= mysqli_fetch_array($result);

     echo json_encode($row);
}   

if ($_POST['mode'] === 'update') {

    mysqli_query($conn,"UPDATE innovation_tbl set  fiscal_year='" . $_POST['fiscal_year'] . "', title_of_invention='" . $_POST['title_of_invention'] . 
 "', inventors_name='" . $_POST['inventors_name'] . "', inventors_designation='" . $_POST['inventors_designation'] .
 "', inventors_emp_id='" . $_POST['inventors_emp_id'] . "', proposed_workplace='" . $_POST['proposed_workplace'] .
 "', des_of_invention='" . $_POST['des_of_invention'] . "', imple_status='" . $_POST['imple_status'] . 
 "', replicate_eligibility='" . $_POST['replicate_eligibility'] .
 "', feedback='" . $_POST['feedback'] .
 "', service_link='" . $_POST['service_link'] .
 "', remarks='" . $_POST['remarks'] ."' WHERE id='" . $_POST['id'] . "'");
    echo json_encode(true);
}  

if ($_POST['mode'] === 'delete') {

     mysqli_query($conn, "DELETE FROM innovation_tbl WHERE id='" . $_POST["id"] . "'");
     echo json_encode(true);
}  

?>