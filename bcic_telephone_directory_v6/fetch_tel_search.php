
<?php include_once('include/header_show_all_contact.php');?>
<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "bcic_tel_db");
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($connect, $_POST["query"]);
 $query = "
  SELECT * FROM tel_tbl 
  WHERE emp_id LIKE '%".$search."%'
  OR name LIKE '%".$search."%' 
  OR designation LIKE '%".$search."%' 
  OR division_name LIKE '%".$search."%' 
  OR section_name LIKE '%".$search."%'
  OR mobile LIKE '%".$search."%'
  OR email LIKE '%".$search."%'
 ";
}
else
{
 $query = "
  SELECT * FROM tel_tbl ORDER BY id DESC
 ";
}
$result = mysqli_query($connect, $query);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
  <div class="table-responsive">
   <table class="table table-striped table-bordered table-hover border-muted">
    
 ';
 while($row = mysqli_fetch_array($result))
 {
  $output .= '
   <tr class="clickable-row" data-href="personal_details.php?id='.$row["id"].'" >
    <td width="30">'.$row["id"].'</td>
    <td width="90"><img src="upload/'.$row["image"].'" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" width="90" height="50"></td>
    <td>'.$row["name"].' '.'<br>'.'
   '.$row["designation"].'</td>

  
   </tr>
  ';
 }
 echo $output;
}
else
{
 echo 'Data Not Found';
}

?>
<script>


//For Clickable row
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
  </script>
  <?php include_once('include/footer.php');?>