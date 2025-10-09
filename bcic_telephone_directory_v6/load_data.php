<?php
// include_once("db/db.php");
$conn = mysqli_connect("localhost", "root", "", "bcic_tel_db");
$perPage = 5;
if (isset($_GET["page"])) { 
	$page  = $_GET["page"]; 
} else { 
	$page=1; 
};  
$startFrom = ($page-1) * $perPage;  
$sqlQuery = "SELECT * FROM tel_tbl ORDER BY id ASC LIMIT $startFrom, $perPage";  
$result = mysqli_query($conn, $sqlQuery); 
$paginationHtml = '';
while ($row = mysqli_fetch_assoc($result)) {  
	$paginationHtml.='<tr class="clickable-row" data-href="personal_details.php?id='.$row["id"].'">';  
	$paginationHtml.='<td>'.$row["id"].'</td>';
	$paginationHtml.='<td>'.$row["image"].'</td>';
	$paginationHtml.='<td>'.$row["name"].'</td>'; 
	$paginationHtml.='<td>'.$row["designation"].'</td>';
 
	$paginationHtml.='</tr>';  

// 	$output .= '  
// <div class="table-responsive">
//     <table class="table table-striped table-bordered table-hover border-muted" >

//      <tbody>
//        <tr class="clickable-row" data-href="personal_details.php?id='.$row["id"].'" >
//   <td width="30">'.$row["id"].'</td>
//     <td width="90"><img src="upload/'.$row["image"].'" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" width="90" height="50"></td>
//    <td>'.$row["name"].' '.'<br>'.'
//    '.$row["designation"].'</td>

//   </tr>

//      </tbody>

//     </table>
    
//    </div>  
//       ';  
} 
$jsonData = array(
	"html"	=> $paginationHtml,	
);
echo json_encode($jsonData); 
?>

<script>


//For Clickable row
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
  </script>