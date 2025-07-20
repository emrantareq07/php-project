<?php
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; 
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];

include_once '../db/database.php';
include_once 'header.php';
$today_date=date("Y-m-d");
//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
$result = mysqli_query($conn,"SELECT COUNT(*) AS upcoming_meeting_count FROM $table where date>'$today_date'");	
	$row = mysqli_fetch_array($result);
	$upcoming_meeting_count = $row['upcoming_meeting_count'];
	// echo $upcoming_meeting_count ;
?>
<script src="../ajax/upcoming_meeting.js"></script>
<div class="container-fluid">
<p id="success"></p>
	<div class="table-wrapper border rounded shadow p-2">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-3">
					<h2 class="text-muted">DAILY <small>e</small>-<b>MEETING</b> SCHEDULE</h2>
					<span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span>
				</div>
				<div class="col-sm-9 text-end">
					<a href="#addEmployeeModal" class="btn btn-outline-success " data-toggle="modal"><i class="fa fa-plus" style="font-size:16px"></i> <span> Add New Meeting</span></a>	
				<!-- 	<a href="#" 
					   class="btn btn-outline-success" 
					   data-bs-toggle="modal" 
					   data-bs-target="#addEmployeeModal">
					   <i class="fa fa-plus" style="font-size:16px"></i> 
					   <span>Add New Meeting</span>
					</a>	
					
						<script type="text/javascript">
						    document.addEventListener('DOMContentLoaded', function() {
						        document.querySelector('[data-bs-target="#addEmployeeModal"]').addEventListener('click', function(e) {
						            e.preventDefault();
						            
						            // Fetch data from backend
						            fetch('backend/add_meeting.php')
						                .then(response => response.json())
						                .then(data => {
						                    // Handle response data here
						                    
						                    // Show the modal
						                    var myModal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
						                    myModal.show();
						                })
						                .catch(error => console.error('Error:', error));
						        });
						    });					

					</script> -->	

					<a href="JavaScript:void(0);" class="btn btn-outline-danger" id="delete_multiple"><i class="fa fa-trash" style="font-size:16px"></i><span> Multiple Delete</span></a>	
					<a href="../dashboard.php" class="btn btn-outline-warning "><i class="fa fa-home" style="font-size:16px"></i> Home Page </a>

					<a href="logout.php" class="btn btn-outline-danger" ><i class="fa fa-trash" style="font-size:16px"></i><span> Logout</span></a>

					<a href="upcoming_meeting.php" class="btn btn-outline-success position-relative"><i class="fa fa-clock-o" style="font-size:20px;color:red"></i>  Upcoming Meeting <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    				<?php echo $upcoming_meeting_count; ?>
   					</span></a>
					<hr>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-print" style="font-size:16px"></i> <span>
				<span>Print Date wise</span></a>

				<a href="backend/search_by_two_date.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Between Date </a>			
				
				<a href="backend/search_by_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Month wise </a>	
				<a href="backend/search_by_two_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i>	 Print Between Month </a>
				
				<a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print All</a>
				</div>
			</div>
		</div> 
		<hr>
		<table id="myTable" class="table table-bordered table-striped table-hover display">
			<thead  class="thead-dark table-dark text-center ">
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
					<th>Zoom ID & Passcode/Link</th>
					<th>Place</th>					
					<th>Status</th>					
					<th>ACTION</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			
			//$today_date=date("Y-m-d");
			$result = mysqli_query($conn,"SELECT * FROM $table order by date DESC");
			//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl where date='$today_date'");
				$i=1;
				while($row = mysqli_fetch_array($result)) {
			?>
			<tr id="<?php echo $row["id"]; ?>" class=" text-center">
			<td>
				<span class="custom-checkbox">
					<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
					<label for="checkbox2"></label>
				</span>
					</td>
				<!--<td><?php echo $i; ?></td>-->
				<td><?php echo $row["date"]; ?></td>
				<td><?php echo $row["time"]; ?></td>
				<td><?php echo $row["subject"]; ?></td>
				<td><?php echo $row["zoom_link"]; ?></td>
				<td><?php echo $row["place"]; ?></td>
				<td><?php echo $row["status"]; 				
				?>				
				</td>				
				<td>
					<a href="#editEmployeeModal" class="edit" style="text-decoration: none" data-toggle="modal">
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
			$i++;
			}
			?>
			</tbody>
		</table>
		
	</div>
</div>
<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="user_form" role="form">				
				      <!-- Modal Header -->
			      <div class="modal-header">
			        <h4 class="modal-title text-uppercase">Add Task</h4>
			        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			      </div>
				<div class="modal-body">					
					<div class="form-group">
						<label>Date</label>
						<input type="date" id="date" name="date" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Start Time</label>
						<input type="time" id="time" name="time" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" id="subject" name="subject" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Zoom Link</label>
						<input type="text" id="zoom_link" name="zoom_link" class="form-control" >
					</div>
					<div class="form-group">
						<label>Place</label>
						<!-- <input type="text" id="place" name="place" class="form-control" required> -->
						<select class="form-select form-control" id="place" name="place" required  aria-label="Default select example">
					  <option selected disabled value="">--Select--</option>
					  <option id="selectA" value="Conference Room	" onchange >Conference Room		
					  </option>			  
					  <option id="selectB"  value="Board Room" >Board Room</option>
					  <option id="selectC" value="Seminar Hall">Seminar Hall</option>
					</select>
					</div>						
					<!--progress bar-->					
					<div class="form-group"><label>Status</label>
					<select class="form-select form-control" id="status" name="status" required  aria-label="Default select example">
					  <option selected disabled value="">--Select--</option>
					  <option id="selectA" value="incomplete" onchange >Incomplete		
					  </option>			  
					  <!-- <option id="selectB"  value="inprogress" >In Progress</option> -->
					  <option id="selectC" value="complete">Complete</option>
					</select>						
					</div>						
				</div>
				<div class="modal-footer">
					<input type="hidden" value="1" name="type">
					<input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-success" id="btn-add"><i class="fa fa-save" style="font-size:16px"></i> Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="update_form">
				<div class="modal-header">
			        <h4 class="modal-title text-uppercase">Edit Task</h4>
			        <button type="button" class="btn-close" data-dismiss="modal"></button>
			      </div>
				<div class="modal-body">
					<input type="hidden" id="id_u" name="id" class="form-control" required>					
					<div class="form-group">
						<label>Date</label>
						<input type="date" id="date_u" name="date" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Time</label>
						<input type="text" id="time_u" name="time" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" id="subject_u" name="subject" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Zoom Link</label>
						<input type="text" id="zoom_link_u" name="zoom_link" class="form-control" >
					</div>
					<div class="form-group">
						<label>Place</label>
						<input type="text" id="place_u" name="place" class="form-control" required>
					</div>	
					<div class="form-group">
					<select class="form-select form-control" id="status_u" name="status" aria-label="Default select example" required>
					  <option selected disabled>Status</option>
					  <option value="incomplete">Incomplete</option>
					  <option value="inprogress">In Progress</option>
					  <option value="complete">Complete</option>
					  <option value="complete">Upcoming</option>
					</select>	
					</div>	
						<script>					
						// Function to change webpage background color
						function changeBodyBg(color){
							document.body.style.background = color;
						}						
						// Function to change heading background color
						function changeHeadingBg(color){
							document.getElementById("heading").style.background = color;
						}
					</script>

						
				</div>
				<div class="modal-footer">
				<input type="hidden" value="2" name="type">
					<input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-info" id="update"><i class="fa fa-upload" style="font-size:16px"></i>Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Delete User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id_d" name="id" class="form-control">					
					<p>Are you sure you want to delete these Records?</p>
					<p class="text-warning"><small>This action cannot be undone.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-danger" id="delete">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>
 <script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
</body>
</html> 