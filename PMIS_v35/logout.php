<?php
session_start();
session_destroy();
header("location: login/index.php");


?>