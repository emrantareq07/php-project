<?php
 //include('db/db.php');
 include('../db/database_2.php');
function upload_image(){
  //$emp_id=$emp_id;
  function generateRandomPassword($length =3) {
  
    $randomBytes = random_bytes($length);

    $password = bin2hex($randomBytes);
    return $password;
}
$randomPassword = generateRandomPassword(3);



 if(isset($_FILES["user_image"])) {

 $filename = $_FILES['user_image']['name'];

  $image = '../images/'. $randomPassword. $filename;
  

$file = $_FILES['user_image']['tmp_name'];

list($width,$height)=getimagesize($file);
  $nwidth=300;
  $nheight=300;
  $newimage=imagecreatetruecolor($nwidth,$nheight);
  if($_FILES['user_image']['type']=='image/jpeg'){
    $source=imagecreatefromjpeg($file);
    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);

  imagejpeg($newimage,'../images/'.$image);
  }
  elseif($_FILES['user_image']['type']=='image/png'){
    $source=imagecreatefrompng($file);
    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);

  imagepng($newimage,'../images/'.$image);
  }elseif($_FILES['user_image']['type']=='image/gif'){
    $source=imagecreatefromgif($file);
    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
  
   imagegif($newimage,'../images/'.$image);
  }

}


 

  return $image;
 }


function get_image_name($user_id){
 include('../db/database_2.php');
 $statement = $connection->prepare("SELECT image FROM basic_info WHERE id = '$user_id'");
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row) {
  return $row["image"];
 }
}

function get_total_all_records(){
  include('../db/database_2.php');
  //include('../db/db.php');
  // $statement = $connection->prepare("SELECT * FROM basic_info ORDER BY COALESCE(seniority_no, (select max(seniority_no) from basic_info)+1)");
  
  $statement = $connection->prepare("SELECT * FROM basic_info");
//$statement = $connection->prepare("SELECT * FROM job_desc j INNER JOIN basic_info b where j.emp_id=b.emp_id");

 $statement->execute();
 $result = $statement->fetchAll();
 return $statement->rowCount();
}

function get_filter_records(){
  include('../db/database_2.php');
 $statement = $connection->prepare("SELECT * FROM basic_info where email='ferdous@gmail.com'");
 $statement->execute();
 $result = $statement->fetchAll();
 return $statement->rowCount();
}


?>
   