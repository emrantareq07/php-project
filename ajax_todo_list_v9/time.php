<?php 

  date_default_timezone_set('Asia/Dhaka'); // Set timezone to Bangladesh time
    $time = date("g:i A"); // Format time as 'hour:minute AM/PM'
    echo $time;
?>