<?php 

session_start(); /* Starts the session */
session_destroy(); /* Destroy started session */
header("location:../index.php");  /* Redirect to login page */
exit;


   // session_start();
   
   // if(session_destroy()) {
      // header("Location: login.php");
   // }

?>