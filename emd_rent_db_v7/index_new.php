<?php
include_once 'backend/database.php';
include_once 'backend/header.php';

$today_date = date("Y-m-d");
$result = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_meeting_count FROM ajax_todo_tbl WHERE date > '$today_date'");	
$row = mysqli_fetch_array($result);
$upcoming_meeting_count = $row['upcoming_meeting_count'];
?>

<div class="container-fluid ">
    <div class="row">
        <div class="col-md-12">
            <p id="success"></p>
            <div class="table-wrapper border rounded shadow p-2">
                <div class="table-title">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-md-6">
                            <h2 class="text-uppercase text-muted">Daily <b>Meeting</b> Schedule</h2>
                        </div>

                        <div class="col-lg-8 col-md-6 text-end float-end">
                            <a href="#addEmployeeModal" class="btn btn-outline-success" data-bs-toggle="modal">
                                <i class="fa fa-plus"></i> Add New Meeting
                            </a>			
                            <a href="JavaScript:void(0);" class="btn btn-outline-danger" id="delete_multiple">
                                <i class="fa fa-trash"></i> Multiple Delete
                            </a>	
                            <a href="show_all.php" class="btn btn-outline-success">
                                <i class="fa fa-eye"></i> Show All
                            </a>	
                            <a href="upcoming_meeting.php" class="btn btn-outline-success position-relative">
                                <i class="fa fa-clock-o text-danger"></i> Upcoming Meeting
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $upcoming_meeting_count; ?>
                                </span>
                            </a>
                            <hr>
                        </div>

                    </div>
                </div>
               
                <div class="d-flex flex-wrap gap-2 mb-3 text-end float-end">
                    <a href="backend/search_by_date.php" class="btn btn-outline-primary">
                        <i class="fa fa-print"></i> Print Date wise
                    </a>
                    <a href="backend/search_by_two_date.php" class="btn btn-outline-primary">
                        <i class="fa fa-print"></i> Print Between Date
                    </a>			
                    <a href="backend/search_by_month.php" class="btn btn-outline-primary">
                        <i class="fa fa-print"></i> Print Month wise
                    </a>
                    <a href="backend/search_by_two_month.php" class="btn btn-outline-primary">
                        <i class="fa fa-print"></i> Print Between Month
                    </a>
                    <a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary">
                        <i class="fa fa-print"></i> Print All
                    </a>
                </div>
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
                    $result = mysqli_query($conn, "SELECT * FROM ajax_todo_tbl WHERE date = '$today_date'");
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr id="<?php echo $row["id"]; ?>" class=" text-center">
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
                                <label for="checkbox2"></label>
                            </span>
                        </td>
                        <td><?php echo $row["date"]; ?></td>
                        <td><?php echo date("g:i A", strtotime($row["time"])); ?></td>
                        <td><?php echo $row["subject"]; ?></td>
                        <td><?php echo $row["place"]; ?></td>
                        <td><?php echo $row["status"]; ?></td>				
                        <td>
							<a href="#editEmployeeModal" class="edit" data-bs-toggle="modal">
							        <i class="fa fa-edit update" 
							           data-id="<?php echo $row['id']; ?>" 
							           data-date="<?php echo $row['date']; ?>" 
							           data-time="<?php echo $row['time']; ?>" 
							           data-subject="<?php echo $row['subject']; ?>" 
							           data-place="<?php echo $row['place']; ?>" 
							           data-status="<?php echo $row['status']; ?>" 
							           title="Edit" 
							           style="font-size:16px; color:blue;">
							        </i>
							    </a>&nbsp;
							    <a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal">
							        <i class="fa fa-trash" title="Delete" style="font-size:16px; color:red;"></i>
							    </a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-danger'>Today No Meeting Found!!!</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="user_form" role="form">
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
                        <label>Place</label>
                        <select class="form-select" id="place" name="place" required>
                            <option selected disabled value="">--Select--</option>
                            <option value="Conference Room">Conference Room</option>			  
                            <option value="Board Room">Board Room</option>
                            <option value="Seminar Hall">Seminar Hall</option>
                        </select>
                    </div>						
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option selected disabled value="">--Select--</option>
                            <option value="incomplete">Incomplete</option>
                            <option value="complete">Complete</option>
                        </select>						
                    </div>						
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="1" name="type">
                    <input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-success" id="btn-add">
                        <i class="fa fa-save"></i> Save
                    </button>
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
                    <h4 class="modal-title text-uppercase text-center">Edit Meeting</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <div class="form-group">
                        <label>Place</label>
                        <input type="text" id="place_u" name="place" class="form-control" required>
                    </div>						
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" id="status_u" name="status" required>
                            <option selected disabled value="">--Select--</option>
                            <option value="incomplete">Incomplete</option>
                            <option value="complete">Complete</option>
                        </select>						
                    </div>						
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="2" name="type">
                    <input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-success" id="update">
                        <i class="fa fa-save"></i> Save
                    </button>
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
                    <h4 class="modal-title text-uppercase">Delete Task</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">					
                    <p>Are you sure you want to delete these records?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-success" id="delete">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script src="ajax/ajax.js"></script> -->
<?php include_once 'backend/footer.php'; ?>
