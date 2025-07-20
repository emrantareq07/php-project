<?php
include('db/db.php');

if(isset($_REQUEST['del'])){
$delete_id=$_REQUEST['del'];
$sql=mysqli_query($conn,"select img_path from users where id='$delete_id'");
$row=mysqli_fetch_array($sql);
mysqli_query($conn,"delete from users where id='$delete_id'");
//$del_img=$row['img_path'];

unlink("./".$row['img_path']);

//header("location:view_users.php");


echo" <script> window.open('view_users.php?deleted=User has  been deleted!!!','_self')</script>";
}
  //mysql_close($con);
?>