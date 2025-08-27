<?php 

date_default_timezone_set("Asia/Dhaka");   //India time (GMT+6)
$file_name = basename($_SERVER['PHP_SELF'], '.php');	
    
    define ('ROOT_PATH', realpath(dirname(__FILE__).'/..'));
    //define('BASE_URL()', 'http://localhost/my_book');

    function base_url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
	    else{
	        $protocol = 'http';
	    }
      return $protocol . "://" . $_SERVER['HTTP_HOST'].'/login';
	}
   
?>