<?php 
session_start();
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
 
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once 'db/database.php';
include_once 'backend/header.php';
$today_date=date("Y-m-d");
//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
$result = mysqli_query($conn,"SELECT COUNT(*) AS upcoming_meeting_count FROM $table where date>'$today_date'");	
$row = mysqli_fetch_array($result);
$upcoming_meeting_count = $row['upcoming_meeting_count'];
	// echo $upcoming_meeting_count ;
?>

<div class="container-fluid ">
	<div class="table-wrapper border rounded shadow p-2">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-3">
					<h2 class="text-muted">DAILY <b>MEETING</b> SCHEDULE</h2>
					<span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span>
				</div>
				<div class="col-sm-9 text-end">
					<?php
					if($user_type=='sadmin'){   
						?>
						<h4><a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
						</h4>
							<h4>
						<a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database </a>
						</h4>
						<?php
						 }
						?>
					<a href="#addEmployeeModal" class="btn btn-outline-success " data-toggle="modal"><i class="fa fa-plus" style="font-size:16px"></i> <span> Add New Meeting</span></a>			
	
					<a href="backend/show_all.php" class="btn btn-outline-success "><i class="fa fa-eye" style="font-size:16px"></i> <span> Show All</span></a>	

					<a href="JavaScript:void(0);" class="btn btn-outline-danger" id="delete_multiple"><i class="fa fa-trash" style="font-size:16px"></i><span> Multiple Delete</span></a>

					<a href="backend/logout.php" class="btn btn-outline-danger" ><i class="fa fa-trash" style="font-size:16px"></i><span> Logout</span></a>

					<a href="backend/upcoming_meeting.php" class="btn btn-outline-success position-relative"><i class="fa fa-clock-o" style="font-size:20px;color:red"></i>  Upcoming Meeting <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    				<?php echo $upcoming_meeting_count; ?>
   					</span></a>

					<hr>
					<?php
					if($user_type=='user'){
					?>
					<a href="backend/directors_meeting.php?dir_tbl=chairman" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Chairman Sir</span></a>
					<?php 
					}
					if($user_type=='admin'){
					?>

					<a href="backend/directors_meeting.php?dir_tbl=dir_com" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
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
					<th>Zoom ID & Passcode/Link</th>
					<th>Place</th>					
					<th>Status</th>					
					<th>ACTION</th>
				</tr>
			</thead>
			<tbody>			
			<?php
			$today_date=date("Y-m-d");
			//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
			$result = mysqli_query($conn,"SELECT * FROM $table where date='$today_date' order by id desc");
				$i=1;
				if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {

					$zoom_concat;
					if($row["zoom_link"] && $row["zoom_id"] && $row["zoom_passcode"]){
						$zoom_concat = $row["zoom_id"] . ', ' . $row["zoom_passcode"] . ', <br>' . $row["zoom_link"];
					}
					else if($row["zoom_id"] && $row["zoom_passcode"]){
						$zoom_concat=$row["zoom_id"].', '.$row["zoom_passcode"];
					}
					 else{
						$zoom_concat=$row["zoom_link"];
					}
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
				<!-- <td><?php echo $row["time"]; ?></td> -->
				<td><?php echo date("g:i A", strtotime($row["time"])); ?></td>
				<td><?php echo $row["subject"]; ?></td>
				<td><?php echo  $zoom_concat; ?></td>
				<td><?php echo $row["place"]; ?></td>
				<td><?php echo $row["status"]; 			
				?>	
				<!-- <i class="fa fa-edit update" data-toggle="tooltip" style="font-size:24px" -->	
				</td>				
				<td>
					<a href="#editEmployeeModal" class="edit" style="text-decoration: none" data-toggle="modal">
						<i class="fa fa-edit update" data-toggle="tooltip"
						data-id="<?php echo $row["id"]; ?>"
						data-date="<?php echo $row["date"]; ?>"
						data-time="<?php echo $row["time"]; ?>"
						data-subject="<?php echo $row["subject"]; ?>"
						data-zoom_id="<?php echo $row["zoom_id"]; ?>"
						data-zoom_passcode="<?php echo $row["zoom_passcode"]; ?>"
						data-zoom_link="<?php echo $row["zoom_link"]; ?>"
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
			 }
 			else {
				echo "<p class='btn btn-danger btn-md '> Today No Meetting Found!!!</p>";
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
			      <div class="modal-header">
			        <h4 class="modal-title text-uppercase text-muted">Add Meeting</h4>
			        <button type="button" class="btn-close" data-dismiss="modal"></button>
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
				    <label>Zoom ID & Passcode/Link</label>
				    <fieldset class="border rounded p-2">
				        <div class="input-group mb-3">				       
				            <input type="text" class="form-control" placeholder="Enter Zoom ID" id="zoom_id" name="zoom_id">				           
				            <input type="text" class="form-control" placeholder="Enter Passcode" id="zoom_passcode" name="zoom_passcode">
				        </div>				    
				        <input type="text" id="zoom_link" name="zoom_link" placeholder="Enter Zoom Link" class="form-control">
				    </fieldset>
				</div>

					<div class="form-group">
						<label>Place</label>						
						<select class="form-select form-control" id="place" name="place" required  aria-label="Default select example">
					  <option selected disabled value="">--Select--</option>
					  <option id="selectA" value="Conference Room" onchange >Conference Room </option> 
					  <option id="selectB"  value="Board Room" >Board Room</option>
					  <option id="selectC" value="Seminar Hall">Seminar Hall</option>
					</select>
					</div>						
							
					<div class="form-group"><label>Status</label>
					<select class="form-select form-control" id="status" name="status" required  aria-label="Default select example">
					  <option selected disabled value="">--Select--</option>
					  <option id="selectA" value="incomplete" onchange >Incomplete		
					  </option>			  
					 
					  <option id="selectC" value="complete">Complete</option>
					</select>						
					</div>						
				</div>
				<div class="modal-footer">
					<input type="hidden" value="1" name="type">
					<input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-success" id="btn-add" ><i class="fa fa-save" style="font-size:16px"></i> Save</button>
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
			        <h4 class="modal-title text-uppercase text-muted text-center">Edit Meeting</h4>
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
						<input type="time" id="time_u" name="time" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" id="subject_u" name="subject" class="form-control" required>
					</div>
					<!-- <div class="form-group">
						<label>Zoom ID & Passcode/Link</label>
						<input type="text" id="zoom_link_u" name="zoom_link" class="form-control" >
					</div> -->
					<div class="form-group">    
				    <label>Zoom ID & Passcode/Link</label>
				    <fieldset class="border rounded p-2">
				        <div class="input-group mb-3">
				            <!-- <span class="input-group-text"> ID</span> -->
				            <input type="text" class="form-control" placeholder="Enter Zoom ID" id="zoom_id_u" name="zoom_id">
				            <!-- <span class="input-group-text">Passcode</span> -->
				            <input type="text" class="form-control" placeholder="Enter Passcode" id="zoom_passcode_u" name="zoom_passcode">
				        </div>
				        <!-- Zoom Link -->
				        <input type="text" id="zoom_link_u" name="zoom_link" placeholder="Enter Zoom Link" class="form-control">
				    </fieldset>
				</div>
					<div class="form-group">
						<label>Place</label>
						<!-- <input type="text" id="place_u" name="place" class="form-control" required> -->
						<select class="form-select form-control" id="place_u" name="place" required  aria-label="Default select example">
					  <option selected disabled value="">--Select--</option>
					  <option id="selectA" value="Conference Room" onchange >Conference Room		
					  </option>			  
					  <option id="selectB"  value="Board Room" >Board Room</option>
					  <option id="selectC" value="Seminar Hall">Seminar Hall</option>
					</select>
					</div>	
					<div class="form-group"><label>Status</label>
					<select class="form-select form-control" id="status_u" name="status" aria-label="Default select example" required>
					  <option selected disabled>--Select--</option>
					  <option value="incomplete">Incomplete</option>
					  <!-- <option value="inprogress">In Progress</option> -->
					  <option value="complete">Complete</option>
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
					<button type="button" class="btn btn-info" id="update"><i class="fa fa-upload" style="font-size:16px"></i> Update</button>
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
				      <!-- Modal Header -->
			      <div class="modal-header">
			        <h4 class="modal-title text-uppercase">Delete Meeting</h4>
			        <button type="button" class="btn-close" data-dismiss="modal"></button>
			      </div>
				<div class="modal-body">
					<input type="hidden" id="id_d" name="id" class="form-control">					
					<p>Are you sure you want to delete these Records?</p>
					<p class="text-warning"><small>This action cannot be undone.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-outline-secondary" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-danger" id="delete">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
 <script type="text/javascript">
 		$(document).on('click','.update',function(e) {
		var id=$(this).attr("data-id");
		var date=$(this).attr("data-date");
		var time=$(this).attr("data-time");
		var subject=$(this).attr("data-subject");
		var zoom_id=$(this).attr("data-zoom_id");
		var zoom_passcode=$(this).attr("data-zoom_passcode");
		var zoom_link=$(this).attr("data-zoom_link");
		var place=$(this).attr("data-place");
		var status_s=$(this).attr("data-status");
		
		$('#id_u').val(id);
		$('#date_u').val(date);
		$('#time_u').val(time);		
		$('#subject_u').val(subject);
		$('#zoom_id_u').val(zoom_id);
		$('#zoom_passcode_u').val(zoom_passcode);
		$('#zoom_link_u').val(zoom_link);
		$('#place_u').val(place);
		$('#status_u').val(status_s);
	});
 </script>


</body>
</html> 