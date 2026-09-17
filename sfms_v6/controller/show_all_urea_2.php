<?php
include('include/header.php');
session_start();
$table=$_SESSION['username'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

$i=0;
?>
 
 <div class="container my-2 pt-2 shadow-lg rounded border border-primary">
  
 <div class="row">
  <div class="col-sm-12">
    <h1 class="text-center text-success"><b> BCIC DFMS</b></h1>
    <form class="form" action="" method="post">
      <div class="col-sm-3">
        <label>Please Select Date Range:</label>
      <div class="form-group">
      <input type="date" class="form-control" placeholder="Enter Date" name="start_date" id="start_date">
        
      </div>
      <div class="form-group">
        
      <input type="date" class="form-control" placeholder="Enter Date" name="end_date" id="end_date">
        
      </div>
           
    </div>
    <div class="col-sm-4">
      <div class="form-group">
       <input type="submit" value="Search" class="btn btn-primary" name="hit">
      </div>
    </div>
     <div class="col-sm-5">
      <center>
      <a class="btn btn-primary text-center float-end" href="login.php">Login</a>     
      <button type="submit" class="btn btn-primary text-center float-end print" name="print" id="print" disabled >Print</button>
      <!--  <a class="btn btn-primary text-center float-end" href="print_report_date_range_pdf.php" target="_blank"  onclick="myFunction()">Print</a>  -->
       <script>  
      $(document).ready(function() {
          $('#start_date').on('input change', function() {
              if($(this).val() != '') {
                  $('#print').prop('disabled', false);
              } else {
                  $('#print').prop('disabled', true);
              }
          });
      });
              

        // function myFunction(){
        //     // window.print();

        //    var start_date =document.getElementById("start_date").value;
        //    var end_date =document.getElementById("end_date").value;

        //     //if(empty(start_date) && empty(end_date)){
        //       if(start_date==null && end_date==null){
        //       // window.location.href = 'print_urea_report_date_range.php';
        //       window.location.href = 'print_urea_report_date_range_pdf.php'; 
        //     }
        //      else{
        //       window.location.href = 'print_urea_report_date_range_pdf.php';
        //      }

            
        // }

        
         </script>           

      <h4 class="text-center"><a class="btn btn-danger" href="urea_form.php?username=<?=$_SESSION['username']?>">Previous Page</a></h4>
      
    </center>
       
     </div>
  </form>
 </div>
  
  <div class="col-sm-12">

  <table class="table table-bordered table-striped rounded text-center table-hover" id="table_content">
    <thead class="table-info">
        <tr > 
        <th class="text-center">Date</th>  
        <th class="text-center">Daily</th>  
        <th class="text-center">Monthly</th>  
        <th class="text-center">Yearly</th>
        <!-- <th class="text-center">Target</th>  
        <th class="text-center">Due</th> -->
        <th class="text-center">Remarks</th>
        </tr> 
        </thead> 
      
    </div>

  </div>
</div>     

<?php
 $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = "";
            $dbname = 'dfms_db';

        $link= mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
        $conn =mysqli_select_db($link,$dbname);

  if(isset($_POST['hit'])){

     $start_date =$_POST['start_date'];
     $end_date =$_POST['end_date'];

  
  $sql=mysqli_query($link,"SELECT * FROM $table  where date between '$start_date' and '$end_date'");


    while($row=mysqli_fetch_assoc($sql)){
          $date=$row['date'];
            $daily=$row['daily'];
            $monthly=$row['monthly'];
            $yearly=$row['yearly'];
            // $production_target=$row['production_target'];
            // $due=$row['due'];
            $remarks=$row['remarks'];

     if($remarks==null){
        $remarks='On Production';
      }

    echo "<tr>";  
    echo "<td> $date </td>";  
    echo "<td> $daily</td>"; 
    echo "<td>$monthly </td>";  
    echo "<td>$yearly</td>"; 
    // echo "<td>$production_target </td>";  
    // echo "<td>$due </td>"; 
     echo "<td>$remarks </td>";
    echo "</tr>";  
       }      

   }


// $month=date('m',strtotime($start_date));
// $year=date('Y',strtotime($start_date));

// if($month==7 || $month==8 || $month==9 || $month==10 || $month==11 || $month==12 ){
//   $year1=$year;
// }
// else{
//   $year1=$year-1;
// }
// $xyz=$year1;
// $yearrange12="$year1-07-01";
// $year1=$year1+1;
// $yearrange13="$year1-06-30";

// $month11=date('m',strtotime($end_date));
// $year11=date('Y',strtotime($end_date));

// if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
//   $year22=$year11;
// }
// else{
//   $year22=$year11-1;
// }

// if($xyz!=$year22){
//   echo'Plz enter Running Fiscal Year';
//   //header("Location: print_urea_report_date_range.php");
// }

// else{

//   $_SESSION['start_date']=$start_date;
//   $_SESSION['end_date']=$end_date;

//   $sql=mysqli_query($link,"SELECT * FROM $table  where date between '$start_date' and '$end_date'");


//     while($row=mysqli_fetch_assoc($sql)){
//           $date=$row['date'];
//             $daily=$row['daily'];
//             $monthly=$row['monthly'];
//             $yearly=$row['yearly'];
//             // $production_target=$row['production_target'];
//             // $due=$row['due'];
//             $remarks=$row['remarks'];

//      if($remarks==null){
//         $remarks='On Production';
//       }

//     echo "<tr>";  
//     echo "<td> $date </td>";  
//     echo "<td> $daily</td>"; 
//     echo "<td>$monthly </td>";  
//     echo "<td>$yearly</td>"; 
//     // echo "<td>$production_target </td>";  
//     // echo "<td>$due </td>"; 
//      echo "<td>$remarks </td>";
//     echo "</tr>";  
//        }      

//    }
        
 // }
?>    
</table>



