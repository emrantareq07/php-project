<?php

include_once('include/header_show_all_contact.php');


// include('database.php');
$conn = mysqli_connect("localhost", "root", "", "bcic_tel_db");
$limit = 5;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  
  
$sql = "SELECT * FROM tel_tbl ORDER BY id ASC LIMIT $start_from, $limit";  
$rs_result = mysqli_query($conn, $sql);  
?>
<table class="table table-bordered table-striped">  
<!-- <thead>  
<tr>  
<th>Name</th>  
<th>Email</th>  
</tr>  
</thead>  --> 
<tbody>  
<?php  
while ($row = mysqli_fetch_array($rs_result)) {  
?>  
            <!-- <tr class="clickable-row" data-href="personal_details.php?id=<?php echo $row['id']; ?>">   -->
            <tr class='clickable-row ' data-href='personal_details.php?id=<?php echo $row["id"]; ?>' >
            <td><?php echo $row["id"]; ?></td>  
            <td><img src='upload/<?php echo $row["image"]; ?>' class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" width="90" height="50"></td> 
             <td><?php echo $row["name"]; ?></td>  
            <td><?php echo $row["designation"]; ?></td> 
            </tr>  

<!-- 
            <tr class="clickable-row" data-href="personal_details.php?id='.$row["id"].'" >
  <td width="30">'.$row["id"].'</td>
    <td width="90"><img src="upload/'.$row["image"].'" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" width="90" height="50"></td>
   <td>'.$row["name"].' '.'<br>'.'
   '.$row["designation"].'</td>

  </tr> -->
<?php  
};  
?>  
</tbody>  
</table>    

<script>


//For Clickable row
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
  </script>