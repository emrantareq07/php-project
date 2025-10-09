<?php  
 //pagination.php  
 $connect = mysqli_connect("localhost", "root", "", "bcic_tel_db");  
 $record_per_page = 5;  
 $page = '';  
 $output = '';  
 if(isset($_POST["page"]))
   // if((isset($_POST["page"])) && ($_POST["query"] = ''))
 {  
      $page = $_POST["page"];  
      // $query = "SELECT * FROM tel_tbl ORDER BY id DESC";

 }  
 else  
 {  
      $page = 1;  


      //from fetch tel
     //  $search_array = explode(",", $_POST["query"]);
     // $search_text = "'" . implode("', '", $search_array) . "'";
     // $query = "
     // SELECT * FROM tel_tbl WHERE division_name IN (".$search_text.") ORDER BY division_name DESC
     // ";

 }  
 $start_from = ($page - 1)*$record_per_page;  
 $query = "SELECT * FROM tel_tbl ORDER BY id DESC LIMIT $start_from, $record_per_page";  
 $result = mysqli_query($connect, $query);  
 $output .= "  
    
 ";  
 while($row = mysqli_fetch_array($result))  
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
 $output .= '</table><br /><div align="center">';  
 $page_query = "SELECT * FROM tel_tbl ORDER BY id DESC";  
 $page_result = mysqli_query($connect, $page_query);  
 $total_records = mysqli_num_rows($page_result);  
 $total_pages = ceil($total_records/$record_per_page);  
 for($i=1; $i<=$total_pages; $i++)  
 {  
      $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>";  
 }  
 $output .= '</div><br /><br />';  
 echo $output;  
 ?>  