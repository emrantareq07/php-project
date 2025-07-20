<?php
include('../db/database.php');
include('../db/db.php');
include('function.php');

if(isset($_POST["operation"])){
  $emp_id=$_POST["emp_id"];
 
 if($_POST["operation"] == "Add") {
  
  $sub_cadre_header=$_POST["sub_cadre_header"];

  if($sub_cadre_header=='Select'){
    $sub_cadre_header='';
  }
 $seniority_no1 = $_POST["seniority_no"];

 $seniority_no =($seniority_no1 !== '') ? $seniority_no1 : null;

// $sql="SELECT count(emp_id) as emp from basic_info where emp_id='$emp_id'";
// $result=mysqli_query($conn,$sql);
// $row = mysqli_fetch_array($result);
// $count=$row['emp'];
 
//  if($count==null){  

$sql="SELECT emp_id from basic_info";
$result=mysqli_query($conn,$sql);

 $i=0;
 while($row = mysqli_fetch_array($result)){           
        $emp_id_db=$row['emp_id'];

        if($emp_id==$emp_id_db){

          $i=1;

        }
        
      }

if($i==0){         
       
  $image = '';
  if($_FILES["user_image"]["name"] != ''){
  $image = upload_image();
  }    
 
  $statement = $connection->prepare("
  INSERT INTO basic_info(employee_type,emp_id,seniority_no,name,designation,sub_cadre_header,division,section,mobile_no,email,image) 
  VALUES (:employee_type,:emp_id, :seniority_no, :name,:designation,:sub_cadre_header, :division, :section, :mobile_no, :email, :image)
  ");

  $result = $statement->execute(
   array(
    ':employee_type' => $_POST["employee_type"],
    ':emp_id' => $_POST["emp_id"],
    ':seniority_no' => $seniority_no,
    ':name' => $_POST["name"],
    ':designation' => $_POST["designation"],
    ':sub_cadre_header' => $sub_cadre_header,
    // ':section' => $section,
    ':division' => $_POST["division"],
    ':section' => $_POST["section"],
    // ':job_status' => $_POST["job_status"],
    ':mobile_no' => $_POST["mobile_no"],
    ':email' => $_POST["email"],
    ':image'  => $image
   )
  );

  if(!empty($result))  {
 
// $digits    = array_flip(range('0', '9'));
// $lowercase = array_flip(range('a', 'z'));
// $uppercase = array_flip(range('A', 'Z')); 
// $special   = array_flip(str_split('!@#$%^&*()_+=-}{[}]\|;:<>?/'));
// $combined  = array_merge($digits, $lowercase, $uppercase, $special);

// $password  = str_shuffle(array_rand($digits) .
//                          array_rand($lowercase) .
//                          array_rand($uppercase) . 
//                          array_rand($special) . 
//                          implode(array_rand($combined, rand(4, 4))));
 
$password = '123456';
$password = md5($password);
$job_confirm_status='approved';
$status='approved';
// $employee_type='';

  $statement = $connection->prepare("
  INSERT INTO users(employee_type,emp_id,name,designation,division,password,status,image,mobile_no,email,job_confirm_status) 
  VALUES (:employee_type,:emp_id, :name,:designation,:division,:password,:status, :image, :mobile_no, :email,:job_confirm_status)
  ");

  $result = $statement->execute(
   array(
    ':employee_type' => $_POST["employee_type"],
    ':emp_id' => $_POST["emp_id"],
   
    ':name' => $_POST["name"],
    ':designation' => $_POST["designation"],
 
    ':division' =>$_POST["division"],
    ':password' =>$password,
    ':status' => $status,
   ':image'  => $image,
    
    ':mobile_no' => $_POST["mobile_no"],
    ':email' => $_POST["email"],
    ':job_confirm_status' => $job_confirm_status
    
   )
  );

  echo 'Data Inserted Successfully!!!';

  }
 }
 else{
  echo 'Data Already Exists';
 }
}
//Edit portion

if ($_POST["operation"] == "Edit") {


  // Check if the sub_cadre_header key exists in the $_POST array
    if (isset($_POST["sub_cadre_header"])) {
        $sub_cadre_header1 = $_POST["sub_cadre_header"];

        // Check if the value is 'Select' or empty, set $sub_cadre_header to null
        if ($sub_cadre_header1 == 'Select' || $sub_cadre_header1 == '') {
            $sub_cadre_header = null;
        } else {
            $sub_cadre_header = $sub_cadre_header1;
        }
    } else {
        // If the key doesn't exist, set $sub_cadre_header to null
        $sub_cadre_header = null;
    }

  $seniority_no1 = $_POST["seniority_no"];
  $seniority_no =($seniority_no1 !== '') ? $seniority_no1 : null;

    $image = '';
    if ($_FILES["user_image"]["name"] != '') {
        // Remove old image if a new one is uploaded
        $old_image = get_image_name($_POST["user_id"]);
        if ($old_image != '') {
            $old_image_path = '../images/' . $old_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        
        $image = upload_image();
    } else {

        $image = $_POST["hidden_user_image"];
    }


$user_id=$_POST["user_id"];
$statement = $connection->prepare("SELECT emp_id FROM basic_info WHERE id = '$user_id'");
$statement->execute();
$result = $statement->fetchAll();

 foreach($result as $row) {
  $emp_id1= $row["emp_id"];
 }
    $statement = $connection->prepare(
        "UPDATE basic_info 
        SET employee_type = :employee_type, emp_id = :emp_id, seniority_no = :seniority_no, name = :name, designation = :designation,sub_cadre_header = :sub_cadre_header, division = :division, section = :section,  mobile_no = :mobile_no, email = :email, image = :image 
        WHERE id = :id"
    );
    $result = $statement->execute(
        array(
          ':employee_type' => $_POST["employee_type"],
            ':emp_id' => $_POST["emp_id"],
            ':seniority_no' => $seniority_no,
            ':name' => $_POST["name"],
            ':designation' => $_POST["designation"],
            ':sub_cadre_header' => $sub_cadre_header,
            ':division' => $_POST["division"],
            ':section' => $_POST["section"],
            // ':job_status' => $_POST["job_status"],
            ':mobile_no' => $_POST["mobile_no"],
            ':email' => $_POST["email"],
            ':image' => $image,
            ':id' => $_POST["user_id"]
        )
    );
    if (!empty($result)) {

    $statement = $connection->prepare(
        "UPDATE users 
        SET employee_type = :employee_type, emp_id = :emp_id, name = :name, designation = :designation, division = :division,image = :image , mobile_no = :mobile_no, email = :email 
        WHERE emp_id = :emp_id1"
    );
    $result = $statement->execute(
        array(
            ':employee_type' => $_POST["employee_type"],
            ':emp_id' => $_POST["emp_id"],
            ':name' => $_POST["name"],
            ':designation' => $_POST["designation"],
       
            ':division' => $_POST["division"],
          
            // ':job_status' => $_POST["job_status"],
             ':image' => $image,
            ':mobile_no' => $_POST["mobile_no"],
            ':email' => $_POST["email"],
            ':emp_id1' => $emp_id1                    
            
        )
    );

        echo 'Data Updated Successfully!!!';
    }
}

}

?>
   