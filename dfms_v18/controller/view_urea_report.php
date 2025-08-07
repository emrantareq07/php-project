<?php
session_name('dfms');
session_start();
$table=$_SESSION['username'];
$user_type = $_SESSION['user_type'];

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../include/header.php');
include('../db/db.php');
?>
    <style type="text/css">
      .table-responsive {
        max-height: 650px; /* Adjust the height as needed */
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
<div class="container my-2 border shadow rounded"> 
<div class="row">
<div class="col-sm-3"></div>
<div class="col-sm-6">  
<h2 class="text-center text-uppercase text-muted">DFMS <b class="text-success">[--<?php echo $table?>--]</b></h2> 
</div>
	 <div class="col-sm-3 ">
	 <span class="text-center float-end my-2">
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
              <th class="text-center">Product</th>
              <th>Plant Load(%)</th>                      
              <th>Remarks</th>
             <?php
                if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'sadmin'){ ?>
                <th class="text-center">Action</th>
                 <?php
                }
            ?>
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
            ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo round((float)$row['daily'], 2); ?></td>
                <td><?php echo $row['product_produce']; ?></td>
                <td><?php echo $row['plant_load']; ?></td>                
				<td style="font-size: 10px;" class="p-2 mb-0"><?php echo $row['remarks']; ?></td>
             <?php
                if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'sadmin') { ?>
                <td class="text-center">
                <?php       
                echo '<a href="edit_urea.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm"> <i class="fa fa-edit"></i> Edit</a>';        
                ?>     
                </td>
                 <?php
                    }
                ?>
            </tr>           
            <?php
                }
            }
            else{
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