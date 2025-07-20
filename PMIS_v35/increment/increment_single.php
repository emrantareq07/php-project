
<?php
session_start();
include('../db/db.php');
include('../includes/header-bio-increment.php');

if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Unset the session variable
}


if(isset($_POST['submit'])){
  // $edit_id=$_GET['edit'];
    foreach ($_POST['emp_id'] as $selectedOption){
    //echo $selectedOption."\n";
    $emp_id= $selectedOption;
   // $_SESSION['emp_id']=$emp_id;

// $explodeCarsWithNegative = explode(",", $emp_id);

// for($i=0; $i < sizeof($explodeCarsWithNegative); $i++){
//  $emp_id= $explodeCarsWithNegative[$i];
//  $_SESSION['emp_id']=$emp_id;

  $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 
  $result=mysqli_query($conn,$sql);

    $row=mysqli_fetch_assoc($result);

    $basic_after_incr=$row['basic_after_incr'];//25480



    $scale=$row['scale'];//25480

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
            //1864
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
            //1864
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
               //echo '<br>'.$granted_incr;
               $final_basic=$granted_incr + $basic_after_incr;
              
               

              $sql2="UPDATE job_desc SET basic='$basic_after_incr', incr_granted='$granted_incr',basic_after_incr='$final_basic' where emp_id='$emp_id' ";
            $result2=mysqli_query($conn,$sql2);  
            }
      

        
     $_SESSION['message'] = "<b class='text-success'>Increment Process (Single) Successfully!!!</b>";
     header("Location: increment_single.php");
        // header("Location: view_promotion_info.php");
        exit(0);
     }
}

?>

<div class="container mt-2 p-1 my-1 border shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
      <h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b>Basic Increment Process (Single +) With Emp ID</b></h2>
      <div class="row">
    
        <div class="col-sm-12">
        <?php if(@$_GET['submitted'])
      {?>

         <?php include('../view/message.php'); ?>
      <div class="alert alert-success" role="alert">
       <b>    </b>
      </div>
      <?php }?>
          
    <div class="panel-body">
      <?php if(isset($message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="col-sm-4"> </div>   
          
    <div class="col-sm-4"> 
      <div class="card border border-warning">
        <div class="card-header">
        
        </div>
      <div class="card-body">
        
      <form method="POST" action="increment_single.php" enctype="multipart/form-data">

        <!-- <div class="form-group">
          <input class="form-control" placeholder="Enter Emp ID" type="text" name="emp_id" id="emp_id" required="">
        </div> -->

        <div class="form-group">
       
         <select class="form-control" id="emp_id" data-placeholder="Select EMP ID"  name="emp_id[]" multiple class="chosen-select" multiple="multiple" required>
          <!-- <option value="" disabled selected >Select EMP ID</option> -->
          <?php 
          $sql="SELECT emp_id FROM basic_info";
           
          $result=mysqli_query($conn,$sql);

           $arr1=array();
           //while($row=mysql_fetch_object($result)){
          while($row=mysqli_fetch_array($result)){
            $emp_id=$row['emp_id'];
            echo "<option value='$emp_id'>$emp_id</option>";
          }
          ?>
          
          </select>
          
        </div>

          <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><i class="fa fa-line-chart"></i> Increment Process</button>  
               
              
      </form>
      <!-- <button class="btn" name="btn" id="btn">Reset</button>  -->

</div>
</div>
</div>
<div class="col-sm-4"> <center><a href="increment_details.php" class="btn btn-primary float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></center></div>

</div>
</div>
</div>
</div>

 <script>

  jQuery('#emp_id').chosen();
 
  </script>

  <?php include('../includes/footer.php');?>