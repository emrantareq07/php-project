<?php  
 if(isset($_POST["employee_id"]))  
 {  
      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "innovation_db");  
      $query = "SELECT * FROM innovation_tbl WHERE id = '".$_POST["employee_id"]."'";  
      $result = mysqli_query($connect, $query);  
      $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td width="30%"><label>সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</label></td>  
                     <td width="70%">'.$row["title_of_invention"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা</label></td>  
                     <td width="70%">'.$row["des_of_invention"].'</td>  
                </tr>  
                
                ';  
      }  
      $output .= "</table></div>";  
      echo $output;  
 }  
 ?>
