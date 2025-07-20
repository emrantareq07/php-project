<?php
// include('includes/header.php');
error_reporting(0);
include('../db/db.php');
session_start();

if (isset($_POST['submit'])) {

    $emp_id = $_POST['emp_id'];
    $_SESSION['emp_id'] = $emp_id;
    $emp_id=$_SESSION['emp_id'];
$sql="SELECT * from basic_info where emp_id ='$emp_id'"; 
$result=mysqli_query($conn,$sql);

    if (mysqli_num_rows($result)>0) {
        $row = mysqli_fetch_array($result);
      ?>

<?php include('../includes/header-dashboard.php');?>


<div class="container mt-1 ">
 
<div class="card shadow-lg">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">Bio-Data Search by Emp ID for Specific Info. Details</h3></div>
  <div class="card-body bg-white">
  
 <div class="row">
	
	
		<!--1st col-->
	<div class="col-sm-1 ">	</div>
	<!--2nd col-->
	<div class="col-sm-10">
    


    <?php 
        
    print "<center>&nbsp;<input class='rounded-circle shadow border border-primary' type=\"image\" name=\"imageField\" 
 width=\"100\" height=\"100\" src=\"".'../images/'.$row['image']."\">&nbsp; </center>";
    
      echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>&nbsp;(".$row['designation'].")</b>&nbsp;</center>";
      echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division : ".$row['division']."</b>&nbsp; </center>";
       
      ?>
  <h3 class="mt-2 text-center"></h3>
  <h4 class="text-center"></h4>
    <h4 class="float-center text-center">


	<a href="#" id="printButton" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Personal Information</a>

 

  <a href="#" id="printButtonSerivicInfo" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Service Information</a>

  <a href="#" id="printButtonSerivicHistory" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Service History</a>

	<hr class="">  
  <a href="#" id="printButtonTrainingInfo" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Training Information</a>

  <a href="#" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Bank Information</a>
  
  <a href="#" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Nominees Info.</a>
  <hr>

<a href="bio_data_specific_info_by_emp_id_1.php" class="btn btn-primary text-white"><i class="fa fa-arrow-left" style="color:white"></i> Previous Page</a>

	</h4>
    
  </div>
 
  <div class="col-sm-1 "> </div>
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by, BCIC.</h6></div>
</div>
 
</div>


<?php include('../includes/footer-dashboard.php');?>


<script>
let printWindow = null;

function printContent(url, title) {
    if (printWindow && !printWindow.closed) {
        printWindow.focus();
        return;
    }

    printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');

    var contentToPrint = `
        <html>
        <head>
            <title>PMIS</title>
        </head>
        <body>
            <center><b><h2 class='text-center text-muted text-uppercase'>${title}</h2></b></center>
        </body>
        </html>
    `;

    var xhr = new XMLHttpRequest();
    var emp_id = <?php echo $_POST['emp_id']; ?>;
    var urlToFetch = url + "?emp_id=" + emp_id;
    xhr.open("GET", urlToFetch, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            contentToPrint += xhr.responseText;

            printWindow.document.open();
            printWindow.document.write(contentToPrint);
            printWindow.document.close();

           
            printWindow.onload = function() {
                
                printWindow.onafterprint = function() {
                    printWindow.close();
                    printWindow = null;
                };
                printWindow.print();
            };
        }
    };
    xhr.send();
}

document.getElementById("printButtonSerivicInfo").addEventListener("click", function() {
    printContent("bio_data_service_info_print.php", "Service Record/ Information");
});

document.getElementById("printButton").addEventListener("click", function() {
    printContent("bio_data_personal_info_print.php", "Personal Information");
});


document.getElementById("printButtonSerivicHistory").addEventListener("click", function() {
    printContent("bio_data_service_history_print.php", "Service History");
});
document.getElementById("printButtonTrainingInfo").addEventListener("click", function() {
    printContent("bio_data_training_info_print.php", "Training Information");
});


</script>


<?php
        }
        else
        {
             echo '<script type="text/javascript">';
    echo 'alert("No User Found!");';
    // echo 'window.location.href="add_job_desc.php"';
    echo 'window.location.href="bio_data_specific_info_by_emp_id_1.php"';

    echo '</script>';
        }

     }

?>



