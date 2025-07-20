<?php
session_name('PROJECT1SESSION');
include_once 'backend/database.php';
// include_once 'backend/header.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="user_form" role="form">
				<div class="modal-header">						
					<h4 class="modal-title">Add Task</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<label>Date</label>
						<input type="date" id="date" name="date" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Time</label>
						<input type="text" id="time" name="time" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" id="subject" name="subject" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Place</label>
						<input type="text" id="place" name="place" class="form-control" required>
					</div>	
					
					<!--progress bar-->
					
					<div class="form-group">
					<select class="form-select form-control" id="status" name="status" required  aria-label="Default select example">
					  <option selected disabled value="">Status</option>
					  <option id="selectA" value="incomplete" onchange >Incomplete</option>			  
					  <option id="selectB"  value="inprogress" >In Progress</option>
					  <option id="selectC" value="complete">Complete</option>
					  <option id="selectC" value="complete">Upcoming</option>
					</select>
					</div>						
				</div>
				<div class="modal-footer">
					<input type="hidden" value="1" name="type">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-success" id="btn-add">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>