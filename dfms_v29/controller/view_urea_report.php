<?php
//Start the session
session_start();
$table=$_SESSION['username'];
// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
// if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
//    header("location: dashboard.php");
// }

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../include/header.php');
include('../db/db.php');
?>
<style type="text/css">
  .table-responsive {
    max-height: 500px; /* Adjust the height as needed */
    overflow-y: auto;
}

/* Optional: Additional styling for the table to ensure a smooth scrolling experience */
.table thead th {
    position: sticky;
    top: 0;
    background-color: #343a40; /* Same as thead background color */
    color: #fff; /* Ensure text color remains visible */
}

</style>

<div class="container-fluid">
  <h2 class="text-center text-uppercase">DFMS <b class="text-success">[--<?php echo $table?>--]</b></h2>

<div class="row">
<div class="col-sm-3"></div>
<div class="col-sm-6"></div>
	 <div class="col-sm-3 ">
	 <span class="text-center float-right my-2">
	<a href="urea_form.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Previous Page</a>
	 <a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></span>
	 </div>
</div><!--end 2nd row-->	
<div class="row">

<div class="col-sm-12">
<div class="table-responsive">
<!-- Data list table --> 
    <table class="table table-striped table-hover text-center table-bordered">
        <thead class="table-dark text-center">
            
              <tr class="text-center">
              
              <!-- <th class="text-center">ID</th> -->
              <th class="text-center">Date</th>
              <th class="text-center">Daily(MT)</th>
              <th class="text-center">Monthly Cumulative(MT)</th>
              <th class="text-center">Yearly Cumulative(MT)</th>
              <th>Plant Load(%)</th>
             <!--  <th>Production Target(MT)</th>
              <th>Due</th> -->
              
              <th>Causes of Shutdown</th>
              <th class="text-center">Action</th>
            </tr></center>
        </thead>
        <tbody>
            <?php
             if(isset($_SESSION['username'])){
      //$edit_id=$_GET['edit'];
              //$factory_name=$_SESSION['username'];
              $query="SELECT * FROM $table ORDER BY id DESC";
			  // $query="SELECT * FROM sfcl where factory_name='$factory_name' ORDER BY id DESC";
             }
            //$query = "SELECT * FROM training_info";
            $query_run = mysqli_query($conn, $query);

            if(mysqli_num_rows($query_run) > 0){
                foreach($query_run as $row){

            // if(!empty($sfcl)){ $count = 0; 
            //     foreach($sfcl as $row){ $count++;
            ?>
            <tr>
                <!-- <td><?php //echo '#'.$count; ?></td> -->
                 <!-- <td><?php //echo $row['id']; ?></td>  -->
                 <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['daily']; ?></td>
                <td><?php echo $row['monthly']; ?></td>
                <td><?php echo $row['yearly']; ?></td>
				        <td><?php echo $row['plant_load']; ?></td>
                <!-- <td><?php // $row['production_target']; ?></td>
                <td><?php //echo $row['due']; ?></td> -->
                
				      <td><?php echo $row['remarks']; ?></td>
                <td class="text-center">
                <?php 
                // Fetch the last ID from the table
                // $sql_find_last_id = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
                // $result_sql_find_last_id = mysqli_query($conn, $sql_find_last_id);
                // $row_find_last_id = mysqli_fetch_array($result_sql_find_last_id);
                // $last_id = $row_find_last_id['id'];

                // Output the current row ID
                // echo $row['id'];

                // Show the edit button only if the current row ID matches the last ID
                // if ($row['id'] == $last_id) {
                    echo '<a href="edit_urea.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Edit</a>';
                // }
                ?>
                <!-- <a href="edit_urea.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Edit</a> -->
                <form action="membershi_info-code.php" method="POST" class="d-inline">
                <!-- <button type="submit" name="delete_student" value="<?=$student['id'];?>" disable class="btn btn-danger btn-sm">Delete</button> -->
            </form>
                </td>
            </tr>
           
            <?php
                }
            }
            else
            {
                echo "<h5 class='text-danger'> <b>No Record Found !!! </b></h5>";
            }
        ?>
        </tbody>
    </table>
    </div>
  </div>
</div><!--end 1st row-->
	   
</div><!--end container-->	
<?php
include('../include/footer.php');
?>