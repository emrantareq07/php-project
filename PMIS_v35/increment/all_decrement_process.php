<?php

include('../includes/header-bio-increment.php');
include('../db/db.php');


// <?php

// include('../includes/header.php');
// include('../db/db.php');

if (isset($_POST['submit'])) {

    $sql = "SELECT * FROM job_desc ";

    $result = mysqli_query($conn, $sql); 

    while ($rowk = mysqli_fetch_assoc($result)) {
        $emp_id = $rowk['emp_id'];


            $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 
    $result=mysqli_query($conn,$sql);

     $row=mysqli_fetch_array($result);


$scale=$row['scale'];
$basic_after_incr=$row['basic_after_incr'];

 if (($basic_after_incr == 66000) || ($basic_after_incr == 56500) || ($basic_after_incr == 50000) || ($basic_after_incr ==43000) || ($basic_after_incr == 35500) || ($basic_after_incr ==29000) || ($basic_after_incr ==23000) || ($basic_after_incr ==22000) || ($basic_after_incr == 16000) || ($basic_after_incr ==12500) || ($basic_after_incr ==11300) || ($basic_after_incr ==11000) || ($basic_after_incr ==10200) || ($basic_after_incr == 9700) || ($basic_after_incr == 9300 ) || ($basic_after_incr ==9000 ) || ($basic_after_incr == 8800) || ($basic_after_incr ==8500 ) || ($basic_after_incr == 8250 )) {

}
else{

     if($scale=='66000-76490') {
          $X=(int) (($basic_after_incr*100)/103.75);

           }

          elseif($scale=='56500-74400' || $scale=='50000-71200' ) {
          $X=(int) (($basic_after_incr*100)/104);
         


           }
          elseif($scale=='43000-69850' ) {
        $X=(int) (($basic_after_incr*100)/104.5);

                     
            
           }
           else {
          $X=(int) (($basic_after_incr*100)/105);

           
           }


  
        $k=$X%10;

       $d=(10-$k);

       $basic_after_incr_new=$X+$d-10;




 if (($basic_after_incr_new == 66000) || ($basic_after_incr_new == 56500) || ($basic_after_incr_new == 50000) || ($basic_after_incr_new ==43000) || ($basic_after_incr_new == 35500) || ($basic_after_incr_new ==29000) || ($basic_after_incr_new ==23000) || ($basic_after_incr_new ==22000) || ($basic_after_incr_new == 16000) || ($basic_after_incr_new ==12500) || ($basic_after_incr_new ==11300) || ($basic_after_incr_new ==11000) || ($basic_after_incr_new ==10200) || ($basic_after_incr_new == 9700) || ($basic_after_incr_new == 9300 ) || ($basic_after_incr_new ==9000 ) || ($basic_after_incr_new == 8800) || ($basic_after_incr_new ==8500 ) || ($basic_after_incr_new == 8250 )) {


    $incr_granted=0;
   
    $sql1="UPDATE job_desc SET basic='$basic_after_incr_new', incr_granted='$incr_granted', basic_after_incr='$basic_after_incr_new' where emp_id='$emp_id' ";
        $result1=mysqli_query($conn,$sql1);

     

           
       }

       else
       {

if($scale=='66000-76490') {
          $a=(int) (($basic_after_incr_new*100)/103.75);

           }

          elseif($scale=='56500-74400' || $scale=='50000-71200' ) {
          $a=(int) (($basic_after_incr_new*100)/104);
         


           }
          elseif($scale=='43000-69850' ) {
        $a=(int) (($basic_after_incr_new*100)/104.5);

                     
            
           }
           else {
          $a=(int) (($basic_after_incr_new*100)/105);

           
           }





      $k1=$a%10;

       $d1=(10-$k1);

       $basic=$a+$d1-10;

   

    $incr_granted=$basic_after_incr_new-$basic;

    $sql1="UPDATE job_desc SET basic='$basic', incr_granted='$incr_granted', basic_after_incr='$basic_after_incr_new' where emp_id='$emp_id' ";
        $result1=mysqli_query($conn,$sql1);  
    
 

       }




}
      
      

        

}

 echo '<script>window.location.href = "increment_details.php?submitted=successfully";</script>';
        exit();
}
?>


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
        <h1 class="text-center text-info text-uppercase"><b>BCIC PMIS Decrement System</b></h1>
    <h3 class="text-center text-muted text-uppercase"> Click button for Decrement</h3>
    <div class="col-md-4 col-sm-4 "></div>
    <div class="col-md-4 col-sm-4  mt-4 p-4 my-4">
        
        <form method="POST" action="">  
         <center>
        <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="submit" class="btn btn-success text-center"><i class="fa fa-line-chart"></i>  Decrement Process </button>
         </center>
          
        </form>

    </div>
    <div class="col-md-4 col-sm-4  mt-4 p-4 my-4 ">
    
        <center><a class="btn btn-primary" href="increment_details.php"><i class="fa fa-arrow-left"></i> Previous page </a></center></div>

    </div>

</div>


</div>
<?php include('../includes/footer.php'); ?>
