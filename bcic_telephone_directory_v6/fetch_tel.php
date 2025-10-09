<?php include_once('include/header_show_all_contact.php');?>
<?php

//fetch.php

$connect = new PDO("mysql:host=localhost;dbname=bcic_tel_db", "root", "");

if($_POST["query"] != '')
{
 $search_array = explode(",", $_POST["query"]);
 $search_text = "'" . implode("', '", $search_array) . "'";
 $query = "
 SELECT * FROM tel_tbl WHERE division_name IN (".$search_text.") ORDER BY division_name DESC
 ";
}
else
{
 $query = "SELECT * FROM tel_tbl ORDER BY id DESC";
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$total_row = $statement->rowCount();

$output = '';

if($total_row > 0)
{
 foreach($result as $row)
 {
  $output .= '
   <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover border-muted" >

     <tbody>
       <tr class="clickable-row" data-href="personal_details.php?id='.$row["id"].'" >
	<td width="30">'.$row["id"].'</td>
  	<td width="90"><img src="upload/'.$row["image"].'" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" width="90" height="50"></td>
   <td>'.$row["name"].' '.'<br>'.'
   '.$row["designation"].'</td>

  </tr>

     </tbody>

    </table>
    
   </div>


  ';
 }
}
else
{
 $output .= '
 <tr>
  <td colspan="5" align="center">No Data Found</td>
 </tr>
 ';
}

echo $output;

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