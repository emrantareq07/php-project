<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if(isset($_POST['final_ids'])){

$final_ids = $_POST['final_ids'];

/* GET ACTIVE FISCAL YEAR */

$q = "SELECT fiscal_year 
      FROM tbl_innovation_idea 
      WHERE idea_status='active' 
      ORDER BY id DESC LIMIT 1";

$r = mysqli_query($conn,$q);
$row = mysqli_fetch_assoc($r);
$fiscal_year = $row['fiscal_year'];


/* =============================
   UPDATE FINAL SELECTED
=============================*/

foreach($final_ids as $id){

mysqli_query($conn,"
UPDATE tbl_innovation 
SET status='final selected'
WHERE id='$id'
");

}


/* =============================
   REJECT REST IDEAS
=============================*/

$id_list = implode(",", $final_ids);

mysqli_query($conn,"
UPDATE tbl_innovation 
SET status='rejected'
WHERE fiscal_year='$fiscal_year'
AND id NOT IN ($id_list)
AND status!='final selected'
");


header("Location: submitted_innovation_ideas.php?success=1");
exit();

}
?>