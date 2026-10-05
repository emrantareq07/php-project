<?php 
session_name('dfms_db');
session_start();

if(session_destroy()){
   header("location: ../index.php");
}
?>