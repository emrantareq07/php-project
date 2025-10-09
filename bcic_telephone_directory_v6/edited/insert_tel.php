<?php
include('db/db.php');
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
   INSERT INTO tel_tbl (name, designation,division_name,section_name,mobile,email,image) 
   VALUES (:name, :designation, :division_name, :section_name, :mobile, :email, :image)
  ");
  $result = $statement->execute(
   array(
    ':name' => $_POST["name"],
    ':designation' => $_POST["designation"],
	':division_name' => $_POST["division_name"],
    ':section_name' => $_POST["section_name"],
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
   "UPDATE users 
   SET name = :name, designation = :designation, division_name = :division_name, section_name = :section_name, mobile = :mobile, image = :image  
   WHERE id = :id
   "
  );
  $result = $statement->execute(
   array(
    ':first_name' => $_POST["first_name"],
    ':last_name' => $_POST["last_name"],
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