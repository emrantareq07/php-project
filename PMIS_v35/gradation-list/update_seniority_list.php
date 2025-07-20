<?php
session_start();
include('../includes/header.php');
include('../db/db.php');


if(isset($_POST['submit'])){

$emp_id=$_POST['emp_id'];

$sql="SELECT * from basic_info where emp_id ='$emp_id'"; 
$result=mysqli_query($conn,$sql);
?>

<div class="container mt-5 shadow-lg border rounded border-primary">

<div class="row">
	<div class="col-sm-12">
		<h1 class="text-center text-uppercase"> <b> Officer's Information</b> <a class="btn btn-primary float-end" href="update_seniority_list_form.php"> Previous page </a></h1>
		 	
		 	<?php
				if(@$_GET['submitted'])
				{?>
				<div class="alert alert-success" role="alert">
				  Data Updated Successfully!!!
				</div>
			<?php }?>

      <?php
//      if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
//     echo "Update successful!";
//     unset($_SESSION['update_success']); // unset the session variable to avoid displaying the message again on refresh
// }
      if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
    echo '<div class="alert alert-success" role="alert">Update successful!</div>';
    unset($_SESSION['update_success']); // unset the session variable to avoid displaying the message again on refresh
}

      ?>


<table class="table table-striped table-hover text-center table-bordered">
        <thead class="table-dark text-center">
            
              <tr class="text-center">
              
              <th class="text-center">ID</th>
              <th class="text-center">Emp Id</th>
              <th class="text-center">Name</th>
              <th class="text-center">Designation</th>
              <th class="text-center">Old Seniortiy No.</th>
              <th>New Seniority No.</th>
              <!-- <th>Production Target(MT)</th>
              <th>Due</th>
              <th>Stock on date</th>
              <th>Causes of Shutdown</th> -->
              <th class="text-center">Action</th>
            </tr>
        </thead>


<?php

while($row = mysqli_fetch_array($result))
	{
		$id=$row['id'];
		$emp_id=$row['emp_id'];
		$name=$row['name'];
		$designation=$row['designation'];
		$old_seniority_no=$row['seniority_no'];
		$_SESSION['seniority_no']=$old_seniority_no;
		$_SESSION['emp_id']=$emp_id;
		?>


        <tbody>
        	<form method="POST" action="update_seniority_list-code.php" enctype="multipart/form-data">
            
            <tr>
                <!-- <td><?php echo '#'.$count; ?></td> -->
                 <td><?php echo $row['id']; ?></td> 
                 <td><?php echo $row['emp_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['designation']; ?></td>
                <td><?php echo $row['seniority_no']; ?></td>

                <td>
                	 
                <input class="form-control" placeholder="Enter New Seniority No." type="text" name="new_seniority_no" id="new_seniority_no" ></td>

                
				<!--<td><?php //echo $row['plant_load']; ?></td>
                <td><?php //echo $row['production_target']; ?></td>
                <td><?php //echo $row['due']; ?></td>
                <td><?php //echo $row['stock_on_date']; ?></td>
				 <td><?php //echo $row['remarks']; ?></td>-->
                <td class="text-center">
               <!--  <a href="membershi_info-view.php?emp_id=<?= $student['emp_id']; ?>" disable class="btn btn-info btn-sm disabled">View</a> -->
                <!-- <a href="edit_urea.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Edit</a> -->
                
                <button type="submit" name="update"  class="btn btn-danger btn-sm" >Update</button> 
            
                </td>
            </tr>
           </form>
            <?php
                }
            }
            // else
            // {
            //     echo "<h5 class='text-danger'> <b>No Record Found !!! </b></h5>";
            // }
        ?>
        </tbody>
    </table>

	</div>
</div>
</div>

 


<?php include('../includes/footer.php');?>