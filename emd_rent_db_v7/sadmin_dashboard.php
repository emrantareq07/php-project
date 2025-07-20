<?php 
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
 // echo $user_type;
$code = $_SESSION['code'];  
require_once("backend/config.php");
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

 
// if($user_type=='sadmin'){
// include('../include/topbar_sadmin.php');        
// }
// else{
// 	include('../include/topbar.php');
// }
?>
<?php
include_once 'db/database.php';
include_once 'backend/header.php';
// $today_date=date("Y-m-d");
// //$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
// $result = mysqli_query($conn,"SELECT COUNT(*) AS upcoming_meeting_count FROM $table where date>'$today_date'");	
// 	$row = mysqli_fetch_array($result);
// 	$upcoming_meeting_count = $row['upcoming_meeting_count'];
	// echo $upcoming_meeting_count ;
?>
<div class="container-fluid ">
<p id="success"></p>
	<div class="table-wrapper border rounded shadow p-2">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-3">
					<h2 class="text-muted">DAILY <small></small><b>MEETING</b> SCHEDULE</h2>
					<span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span>
				</div>
				<div class="col-sm-9 text-end">
					<?php
					if ($user_type == 'sadmin') {   
					?>
					    <div class="d-flex justify-content-end">
					        <a href="backend/manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-info me-2">
					            <i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User
					        </a>
					        <a href="backend/logfile.php?username=<?=$_SESSION['username']?>" class="btn btn-success me-2">
					            <i class="fa fa-building-o" style="font-size:15px;color:black"></i> Log File
					        </a>
					        <form id="downloadForm" action="download_database.php" method="post">
					            <button class="btn btn-warning" type="submit" name="submit">
					                <i class="fa fa-download" style="font-size:16px"></i> Download Database
					            </button>
					        </form>
					    </div>
					<?php
					}
					?>
					<hr>
					<a href="#addEmployeeModal" class="btn btn-outline-success " data-toggle="modal"><i class="fa fa-plus" style="font-size:16px"></i> <span> Add New Meeting</span></a>			
	
					<a href="backend/show_all.php" class="btn btn-outline-success "><i class="fa fa-eye" style="font-size:16px"></i> <span> Show All</span></a>	

					<a href="JavaScript:void(0);" class="btn btn-outline-danger" id="delete_multiple"><i class="fa fa-trash" style="font-size:16px"></i><span> Multiple Delete</span></a>

					<a href="backend/logout.php" class="btn btn-outline-danger" ><i class="fa fa-sign-out" style="font-size:16px"></i><span> Logout</span></a>

					<a href="backend/upcoming_meeting.php" class="btn btn-outline-success position-relative"><i class="fa fa-clock-o" style="font-size:20px;color:red"></i>  Upcoming Meeting <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    				<?php //echo $upcoming_meeting_count; ?>
   					</span></a>

					<hr>
					<?php
					if($user_type=='sadmin'){
					?>

					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (Commercial)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (Finance)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (T&E)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (P&I)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (P&R)</span></a>
					<hr>
					
					<?php }; ?>

					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-print" style="font-size:16px"></i> <span>
				<span>Print Date wise</span></a>

				<a href="backend/search_by_two_date.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Between Date </a>			
				
				<a href="backend/search_by_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Month wise </a>	
				<a href="backend/search_by_two_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i>	 Print Between Month </a>
				
				<a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print All</a>
				</div>
			</div>
		</div> <hr>
		<table class="table table-bordered table-striped table-hover">
			<thead class="table-dark text-center">
				<tr>
					<th>
						<span class="custom-checkbox">
							<input type="checkbox" id="selectAll">
							<label for="selectAll"></label>
						</span>
					</th>
					<th>Date</th>
					<th>Time</th>
					<th>Subject</th>
					<th>Place</th>					
					<th>Status</th>					
					<th>ACTION</th>
				</tr>
			</thead>
			<tbody>			
			<?php
			// $today_date=date("Y-m-d");
			// //$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
			// $result = mysqli_query($conn,"SELECT * FROM $table where date='$today_date'");
			// 	$i=1;
			// 	if (mysqli_num_rows($result) > 0) {
			// 	while($row = mysqli_fetch_array($result)) {
			?>
			<tr id="<?php //echo $row["id"]; ?>" class=" text-center">
			<td>
				<span class="custom-checkbox">
					<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
					<label for="checkbox2"></label>
				</span>
					</td> 
				<!--<td><?php //echo $i; ?></td>-->
				<td><?php //echo $row["date"]; ?></td>
				<!-- <td><?php //echo $row["time"]; ?></td> -->
				<td><?php //echo date("g:i A", strtotime($row["time"])); ?></td>
				<td><?php //echo $row["subject"]; ?></td>
				<td><?php //echo $row["place"]; ?></td>
				<td><?php //echo $row["status"]; 			
				?>	
				<!-- <i class="fa fa-edit update" data-toggle="tooltip" style="font-size:24px" -->			
				</td>				
				<td>
					<a href="#editEmployeeModal" class="edit" data-toggle="modal">
						<i class="fa fa-edit update" data-toggle="tooltip"
						data-id="<?php echo $row["id"]; ?>"
						data-date="<?php echo $row["date"]; ?>"
						data-time="<?php echo $row["time"]; ?>"
						data-subject="<?php echo $row["subject"]; ?>"
						data-place="<?php echo $row["place"]; ?>"
						data-status="<?php echo $row["status"]; ?>"
						title="Edit" style="font-size:20px; color:blue;"></i>
					</a>&nbsp;
					<a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal">
					<!-- <i class="material-icons" data-toggle="tooltip" title="Delete"></i> -->
					<i class="fa fa-trash" style="font-size:20px;color:red;"></i>
				</a>
				</td>
			</tr>
			<?php
			// $i++;
			// }
			//  }
 		// 	else {
			// 	echo "<p class='btn btn-danger btn-md '> Today No Meetting Found!!!</p>";
			// }
			?>
			</tbody>
		</table>
		
	</div>
</div>

 
</body>
</html> 