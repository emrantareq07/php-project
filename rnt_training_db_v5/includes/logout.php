<?php 
session_name('rnt_training_db');
session_start(); /* Starts the session */
session_destroy(); /* Destroy started session */
header("location:../index.php");  /* Redirect to login page */
exit;


   // session_start();
   
   // if(session_destroy()) {
      // header("Location: login.php");
   // }

?>
<?php
// Set the same session name
// session_name('rnt_training_db');
// session_start(); // Start the session with the custom name
// session_destroy(); // Destroy the session
// session_unset();  // Clear all session variables

// // Redirect to the login page
// header("Location: ../index.php");
// exit;
?>
