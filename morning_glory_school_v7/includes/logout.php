<?php
session_start();
$_SESSION["uid"] = "";

if(isset($_COOKIE['user_login']))
{
 setcookie ("user_login","",time()- (90 * 365 * 24 * 60 * 60), "/");
 setcookie("user_login", "");
}
session_destroy();
header("Location:../login.php");
?> 