<?php
error_reporting(0);
include('../includes/header-bio-increment.php');
include('../db/db.php');

if (isset($_POST['submit'])) {

    $sql = "SELECT * FROM job_desc ";

    $result = mysqli_query($conn, $sql);

    echo '<div class="container mt-4 p-4 my-4">';
    echo '<div class="row">';
    echo '<a href="#" id="printButton" class="btn btn-danger btn-xs text-white"><i class="fa fa-print" style="color:white"></i> Print </a> ';

    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">Employee ID</th>';
    echo '<th scope="col">Name</th>';
    echo '<th scope="col">Designation</th>';
    echo '<th scope="col">Place of Posting</th>';
    echo '<th scope="col">Penalty/Confirmation/Increment Status</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';


    while ($row = mysqli_fetch_assoc($result)) {
        $emp_id = $row['emp_id'];
        $basic_after_incr = $row['basic_after_incr'];
        $grade = $row['grade'];
        $doj = $row['doj'];
        $nature_of_promo=$row['nature_of_promo'];
        $scale=$row['scale'];
      

        $tomorrow = date('Y-m-d');
        $year = date('Y', strtotime($tomorrow));

        $date4 = '01/07/' . $year;
        $date5 = $year . '/07/01';

        $datetime1 = date_create($doj);
        $datetime2 = date_create($date4);
        $interval = date_diff($datetime1, $datetime2);
        $interval->format('%a');

        $sql41 = "SELECT name,designation FROM basic_info where emp_id= '$emp_id'";
         $result41 = mysqli_query($conn, $sql41);
         $row5 = mysqli_fetch_assoc($result41);
          $name=$row5['name'];
          $designation=$row5['designation'];

        $sql4 = "SELECT * FROM award_and_penalty where emp_id= '$emp_id' AND nature ='Increment Held-up'";
                $result4 = mysqli_query($conn, $sql4);
                $rowcount = mysqli_num_rows($result4);
              
              $row9 = mysqli_fetch_assoc($result4 );
              $to_date=$row9['to_date'];
             
                      



       if (($basic_after_incr <= 73720 && $scale == "66000-76490") || ($basic_after_incr <= 71530 && $scale == "56500-74400") || ($basic_after_incr <= 68460 && $scale == "50000-71200") || ($basic_after_incr <= 66840 && $scale == "43000-69850") || ($basic_after_incr <= 63810 && $scale == "35500-67010") || ($basic_after_incr <= 60390 && $scale == "29000-63410") || ($basic_after_incr <= 52800 && $scale == "23000-55460") || ($basic_after_incr <= 50530 && $scale == "22000-53060") || ($basic_after_incr <= 36800 && $scale == "16000-38640") || ($basic_after_incr <= 28790 && $scale == "12500-32240") || ($basic_after_incr <= 26000 && $scale == "11300-27300") || ($basic_after_incr <= 25320 && $scale == "11000-26590") || ($basic_after_incr <= 23500 && $scale == "10200-24680") || ($basic_after_incr <= 22370 && $scale == "9700-23490") || ($basic_after_incr <= 21410 && $scale == "9300-22490") || ($basic_after_incr <= 20760 && $scale == "9000-21800") || ($basic_after_incr <= 20290 && $scale == "8800-21310") || ($basic_after_incr <= 19590 && $scale == "8500-20570") || ($basic_after_incr <= 19050 && $scale == "8250-20010")) {


            if ($interval->format('%a') > 181) {


                if($rowcount == 0 || (strtotime($date5) > strtotime($to_date)) ){

     if($scale=='66000-76490') {
          $granted_incr=(int) (($basic_after_incr*3.75)/100);

                     $granted_incr1=(($basic_after_incr*3.75)/100);

           if($granted_incr1==$granted_incr)
           {
             $granted_incr=$granted_incr;
           }
           else
           {
            $dm=$granted_incr%10;
            if($dm==0)
            {
              $granted_incr=$granted_incr+10;
            }
            else
            {
              $granted_incr=$granted_incr;
            }

           }
           }

          elseif($scale=='56500-74400' || $scale=='50000-71200' ) {
          $granted_incr=(int) (($basic_after_incr*4)/100);
           $granted_incr1=(($basic_after_incr*4)/100);

           if($granted_incr1==$granted_incr)
           {
             $granted_incr=$granted_incr;
           }
           else
           {
            $dm=$granted_incr%10;
            if($dm==0)
            {
              $granted_incr=$granted_incr+10;
            }
            else
            {
              $granted_incr=$granted_incr;
            }

           }



           }
          elseif($scale=='43000-69850' ) {
          $granted_incr=(int) (($basic_after_incr*4.5)/100);

                     $granted_incr1=(($basic_after_incr*4.5)/100);

           if($granted_incr1==$granted_incr)
           {
             $granted_incr=$granted_incr;
           }
           else
           {
            $dm=$granted_incr%10;
            if($dm==0)
            {
              $granted_incr=$granted_incr+10;
            }
            else
            {
              $granted_incr=$granted_incr;
            }

           }
            
           }
           else {
          $granted_incr=(int) (($basic_after_incr*5)/100);
                     $granted_incr1=(($basic_after_incr*5)/100);

           if($granted_incr1==$granted_incr)
           {
             $granted_incr=$granted_incr;
           }
           else
           {
            $dm=$granted_incr%10;
            if($dm==0)
            {
              $granted_incr=$granted_incr+10;
            }
            else
            {
              $granted_incr=$granted_incr;
            }

           }
           
           }

					
					$d=$granted_incr%10;
					
					if($d==0){
							$final_basic= $granted_incr + $basic_after_incr;
							$sql1="UPDATE job_desc SET basic='$basic_after_incr', incr_granted='$granted_incr',basic_after_incr='$final_basic' where emp_id='$emp_id' ";
							$result1=mysqli_query($conn,$sql1);  
						}
					elseif($d>0){   
						   $d=(10-$d);
						   $granted_incr=$granted_incr+$d;
						 
						   $final_basic=$granted_incr + $basic_after_incr;
						  
						   

						  $sql2="UPDATE job_desc SET basic='$basic_after_incr', incr_granted='$granted_incr',basic_after_incr='$final_basic' where emp_id='$emp_id' ";
						$result2=mysqli_query($conn,$sql2);  
						}

					 

        
                } else {
                    // Output employee ID and status in the table
                    echo '<tr>';
                    echo '<td>' . $emp_id . '</td>';
                    echo '<td>' . $row5['name'] . '</td>';
                    echo '<td>' . $row5['designation'] . '</td>';
                    echo '<td>' . $row['place_of_posting']. '</td>';
                    echo '<td>Penalty Received</td>';
                    echo '</tr>';
                }
            } else {
                // Output employee ID and status in the table
                echo '<tr>';
                echo '<td>' . $emp_id . '</td>';
                echo '<td>' . $row5['name'] . '</td>';
                echo '<td>' . $row5['designation'] . '</td>';
                echo '<td>' . $row['place_of_posting']. '</td>';
                echo '<td>Job Confirmation Not 6 months</td>';
                echo '</tr>';
            }
        } else {
            // Output employee ID and status in the table
            echo '<tr>';
            echo '<td>' . $emp_id . '</td>';
            echo '<td>' . $row5['name'] . '</td>';
	        echo '<td>' . $row5['designation'] . '</td>';
	        echo '<td>' . $row['place_of_posting']. '</td>';
            echo '<td>Increment Exceeds Limit</td>';
            echo '</tr>';
        }
    
   

	}

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
}

?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to handle the printing
        function printTable() {
            // Hide unnecessary parts before printing
            document.getElementById("printButton").style.display = "none";
            document.getElementById("nonPrintableContent").style.display = "none";
            window.print();
            // Show the hidden parts after printing
            document.getElementById("printButton").style.display = "block";
            document.getElementById("nonPrintableContent").style.display = "block";
        }

        // Attach the printTable function to the click event of the printButton
        document.getElementById("printButton").addEventListener("click", printTable);
    });
</script>

<div id="nonPrintableContent"  class="container mt-4 p-4 my-4  "><?php
			//if(@$_GET['submitted'])
			//{?>
			<!-- <div class="alert alert-success" role="alert">
			  Data Update Successfully!!!
			</div> -->
			<?php// }?>
	<!--  <a href="increment_update.php" class="btn btn-success">Increment Process</a> -->
<div class="row">
	<div class="col-md-12 col-sm-12 border shadow rounded bg-light">
		<h1 class="text-center text-info text-uppercase"><b>BCIC PMIS Increment System</b></h1>
	<h3 class="text-center text-muted text-uppercase"> Click button for Increment</h3>
	<div class="col-md-4 col-sm-4 "></div>
	<div class="col-md-4 col-sm-4  mt-4 p-4 my-4">
		
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-success text-center"><i class="fa fa-line-chart"></i>  Increment Process </button>
		 </center>
          
		</form>

	</div>
	<div class="col-md-4 col-sm-4  mt-4 p-4 my-4 ">
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-danger text-center"><i class="fa fa-print"></i>  Print All (In-service) </button>
		 </center>
          
		</form>
		<hr>
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-danger text-center"><i class="fa fa-print"></i>  Print All (PRL) </button>
		 </center>
          
		</form>
		<hr>
		<center><a class="btn btn-primary" href="increment_details.php"><i class="fa fa-arrow-left"></i> Previous page </a></center></div>

	</div>

</div>


</div>
<?php include('../includes/footer.php'); ?>
