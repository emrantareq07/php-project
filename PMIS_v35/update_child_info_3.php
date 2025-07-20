<?php

//update.php

include('database_connection.php');
//include('db/db.php');
$query = "
UPDATE childs_info 
SET ".$_POST["name"]." = '".$_POST["value"]."' 
WHERE id = '".$_POST["pk"]."'
";

$connect->query($query);

?>
