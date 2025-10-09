<?php
//include('db/db.php');
include('database.php');
include('function.php');
if(isset($_POST["operation"]))
{
 if($_POST["operation"] == "Add")
 {
  $image = '';
  if($_FILES["user_image"]["name"] != '')
  {
   $image = upload_image();
  }
  $statement = $connection->prepare("
   INSERT INTO tel_tbl (name, designation,division_name,section_name,phone_office,phone_home,intercom,mobile,email,image) 
   VALUES (:name, :designation, :division_name, :section_name, :phone_office, :phone_home, :intercom, :mobile, :email, :image)
  ");
  $result = $statement->execute(
   array(
    ':name' => $_POST["name"],
    ':designation' => $_POST["designation"],
    ':division_name' => $_POST["division_name"],
    ':section_name' => $_POST["section_name"],
    ':phone_office' => $_POST["phone_office"],
    ':phone_home' => $_POST["phone_home"],
    ':intercom' => $_POST["intercom"],
    ':mobile' => $_POST["mobile"],
    ':email' => $_POST["email"],
    ':image'  => $image
   )
  );
  if(!empty($result))
  {
   echo 'Data Inserted';
  }
 }
 if($_POST["operation"] == "Edit")
 {
  $image = '';
  if($_FILES["user_image"]["name"] != '')
  {
   $image = upload_image();
  }
  else
  {
   $image = $_POST["hidden_user_image"];
  }
  $statement = $connection->prepare(
   "UPDATE tel_tbl 
   SET name = :name, designation = :designation, division_name = :division_name, section_name = :section_name, phone_office = :phone_office, phone_home = :phone_home, intercom = :intercom, mobile = :mobile, email = :email, image = :image  
   WHERE id = :id"
  );
  $result = $statement->execute(
   array(
    ':name' => $_POST["name"],
    ':designation' => $_POST["designation"],
	  ':division_name' => $_POST["division_name"],
    ':section_name' => $_POST["section_name"],
	  ':phone_office' => $_POST["phone_office"],
    ':phone_home' => $_POST["phone_home"],
	  ':intercom' => $_POST["intercom"],
	  ':mobile' => $_POST["mobile"],
    ':email' => $_POST["email"],
    ':image'  => $image,
    ':id'   => $_POST["user_id"]
   )
  );
  if(!empty($result))
  {
   echo 'Data Updated';
  }
 }
}

?>