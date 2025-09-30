<?php 
session_name('blrr');
session_start();
$username = $_SESSION['username']; //chairman
$user_type = $_SESSION['user_type'];//admin
$office = $_SESSION['office'];
$table_name = $_SESSION['table_name'];
$office_title = $_SESSION['office_title'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
} 

require_once("backend/config.php");
include_once 'db.php';
include_once 'backend/header.php';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));

$result = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_meeting_count FROM $table_name where status='pending'");  
$row11 = mysqli_fetch_array($result);
$upcoming_meeting_count = $row11['upcoming_meeting_count'];

function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
}
?>

<div class="container-fluid ">
    <div class="table-wrapper border border-muted rounded shadow p-2 my-1 ">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-3">
                    <h2 class="text-muted text-left"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b> </h2>
                    <span class="text-primary fw-bold"><small>Username : [--<?php echo $_SESSION['username']; ?>--]</small></span><br>
                    <span class="text-success fw-bold"><small>Office : [--<?php echo $office; ?>--]</small></span><br>
                </div>

                <div class="col-sm-9 text-end d-flex flex-wrap align-items-center justify-content-end gap-1">
                    <a href="#addEmployeeModal" class="btn btn-outline-success border-custom-purple d-inline-block" data-toggle="modal">
                        <i class="fa fa-plus" style="font-size:16px;color:red"></i> <span>Add New Docs</span>
                    </a>
                    <a href="backend/show_all.php?table_name=<?= $_SESSION['table_name'] ?>" class="btn btn-outline-success d-inline-block">
                        <i class="fa fa-file-archive-o" style="font-size:16px;color:red"></i> <span>Show All</span>
                    </a>

                    <?php if ($user_type == 'user' && ($office_title == 'division' || $office_title == 'director' || $office_title == 'chairman')) { ?>
                        <a href="backend/incoming_letter.php" class="btn btn-outline-success position-relative d-inline-block">
                            <i class="fa fa-clock-o" style="font-size:20px;color:red"></i> Incoming Letter
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $upcoming_meeting_count; ?>
                            </span>
                        </a>
                    <?php } ?>

                    <a href="backend/show_all_old.php?table_name=<?= $_SESSION['table_name'] ?>" class="btn btn-outline-success d-inline-block">
                        <i class="fa fa-file-archive-o" style="font-size:16px;color:red"></i> <span>Show Old Docs</span>
                    </a>
                    <a href="backend/search_new.php?table_name=<?= $_SESSION['table_name'] ?>&val=987" class="btn btn-outline-primary d-inline-block">
                        <i class="fa fa-search" style="font-size:16px"></i> <span>Search</span>
                    </a>

                    <?php if ($user_type == 'sadmin') { ?>
                        <a href="backend/manage_user.php?username=<?= $_SESSION['username'] ?>" class="btn btn-warning d-inline-block">
                            <i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User
                        </a>
                        <a href="backend/manage_user.php?username=<?= $_SESSION['username'] ?>" class="btn btn-warning d-inline-block">
                            <i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database
                        </a>
                    <?php } ?>            

                    <button type="button" class="btn btn-danger d-inline-block" id="print_current_date">
                        <i class="fa fa-print" style="font-size:16px"></i> Print
                    </button>
                    <a href="backend/logout.php" class="btn btn-danger d-inline-block">
                        <i class="fa fa-sign-out" style="font-size:16px"></i> <span>Logout</span>
                    </a>
                    <hr class="w-100">
                </div>
            </div>
        </div> 

        <span class="text-secondary fw-bold"><small>Logged As a : [--<?php echo $user_type; ?>--]</small></span>
        <span class="text-custom-purple text-center fw-bold"><small>&nbsp;&nbsp; Date : <?php echo date("d-m-Y");?></small></span>

        <?php
require 'db.php';
?>
<?php include 'backend/header.php'; ?>

<div class="container my-4">  
  <!-- Button to open modal -->
  <button class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#entryModal">
    <i class="fa fa-plus"></i> নতুন এন্ট্রি
  </button>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="entryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="user_form">
          <div class="modal-header">
            <h5 class="modal-title">পত্র প্রাপ্তি রেজিস্টার এন্ট্রি ফরম</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <input type="hidden" id="edit_id" name="id">
            <input type="hidden" id="unique_id" name="unique_id">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">এন্ট্রি তারিখ</label>
                <input type="date" class="form-control" name="entry_date" id="entry_date">
              </div>
              <div class="col-md-6">
                <label class="form-label">প্রাপক</label>
                <input type="text" class="form-control" name="recipient" id="recipient">
              </div>
              <div class="col-md-6">
                <label class="form-label">তাৎক্ষণিক প্রেরক অফিস</label>
                <input type="text" class="form-control" name="immediate_sender_office" id="immediate_sender_office">
              </div>
              <div class="col-md-6">
                <label class="form-label">ডাক নং</label>
                <input type="text" class="form-control" name="d_number" id="d_number">
              </div>
              <div class="col-md-6">
                <label class="form-label">দৃষ্টি আকর্ষণ</label>
                <input type="text" class="form-control" name="attention" id="attention">
              </div>
              <div class="col-md-6">
                <label class="form-label">রেফারেন্স নং</label>
                <input type="text" class="form-control" name="ref_number" id="ref_number">
              </div>
              <div class="col-md-6">
                <label class="form-label">প্রেরণের তারিখ</label>
                <input type="date" class="form-control" name="send_date" id="send_date">
              </div>
              <div class="col-md-6">
                <label class="form-label">প্রেরক</label>
                <input type="text" class="form-control" name="sender" id="sender">
              </div>
              <div class="col-md-6">
                <label class="form-label">বিভাগ/অফিস</label>
                <input type="text" class="form-control" name="div_dept_office" id="div_dept_office">
              </div>
              <div class="col-md-6">
                <label class="form-label">বিষয়</label>
                <input type="text" class="form-control" name="subject" id="subject">
              </div>
              <div class="col-md-6">
                <label class="form-label">গন্তব্য</label>
                <input type="text" class="form-control" name="destination" id="destination">
              </div>
              <div class="col-md-6">
                <label class="form-label">গন্তব্য ড্রপ</label>
                <input type="text" class="form-control" name="destination_drop" id="destination_drop">
              </div>
              <div class="col-md-6">
                <label class="form-label">বিতরণ তারিখ</label>
                <input type="date" class="form-control" name="distribution_date" id="distribution_date">
              </div>
              <div class="col-md-12">
                <label class="form-label">চেয়ারম্যান মন্তব্য</label>
                <textarea class="form-control" name="chairman_note" id="chairman_note"></textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label">অন্যান্য মন্তব্য</label>
                <textarea class="form-control" name="comments" id="comments"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">মাধ্যম</label>
                <input type="text" class="form-control" name="medium" id="medium">
              </div>
              <div class="col-md-6">
                <label class="form-label">স্ট্যাটাস</label>
                <select class="form-select" name="status" id="status">
                  <option value="Pending">Pending</option>
                  <option value="Processed">Processed</option>
                  <option value="Completed">Completed</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">সময়</label>
                <input type="time" class="form-control" name="time" id="time">
              </div>
              <div class="col-md-6">
                <label class="form-label">Modified</label>
                <input type="datetime-local" class="form-control" name="modified" id="modified">
              </div>
              <div class="col-md-6">
                <label class="form-label">Created At</label>
                <input type="datetime-local" class="form-control" name="created_at" id="created_at">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Table -->
  <table id="friendsTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>প্রাপক</th>
        <th>প্রেরক</th>
        <th>বিষয়</th>
        <th>তারিখ</th>
        <th>স্ট্যাটাস</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<?php include 'backend/footer.php'; ?>

<script>
$(document).ready(function(){
  var table = $('#friendsTable').DataTable({
    "ajax": "get.php",
    "columns": [
      { "data": "id" },
      { "data": "recipient" },
      { "data": "sender" },
      { "data": "subject" },
      { "data": "entry_date" },
      { "data": "status" },
      { "data": null, "render": function(data,type,row){
          return `
            <button class="btn btn-sm btn-primary editBtn" data-id="${row.id}">Edit</button>
            <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">Delete</button>
          `;
        }
      }
    ]
  });

  // Save form
  $('#user_form').on('submit', function(e){
    e.preventDefault();
    var url = $('#edit_id').val() ? 'update.php' : 'save.php';
    $.post(url, $(this).serialize(), function(res){
      if(res.status == 1){
        $('#entryModal').modal('hide');
        $('#user_form')[0].reset();
        $('#edit_id').val('');
        table.ajax.reload();
        alert(res.message);
      } else {
        alert(res.message);
      }
    },'json');
  });

  // Edit
  $('#friendsTable').on('click','.editBtn',function(){
    var id = $(this).data('id');
    $.getJSON('get.php',{id:id},function(res){
      if(res){
        $('#edit_id').val(res.id);
        $('#unique_id').val(res.unique_id);
        $('#entry_date').val(res.entry_date);
        $('#recipient').val(res.recipient);
        $('#immediate_sender_office').val(res.immediate_sender_office);
        $('#d_number').val(res.d_number);
        $('#attention').val(res.attention);
        $('#ref_number').val(res.ref_number);
        $('#send_date').val(res.send_date);
        $('#sender').val(res.sender);
        $('#div_dept_office').val(res.div_dept_office);
        $('#subject').val(res.subject);
        $('#destination').val(res.destination);
        $('#destination_drop').val(res.destination_drop);
        $('#distribution_date').val(res.distribution_date);
        $('#chairman_note').val(res.chairman_note);
        $('#comments').val(res.comments);
        $('#medium').val(res.medium);
        $('#status').val(res.status);
        $('#time').val(res.time);
        $('#modified').val(res.modified);
        $('#created_at').val(res.created_at);

        $('#entryModal').modal('show');
      }
    });
  });

  // Delete
  $('#friendsTable').on('click','.deleteBtn',function(){
    if(confirm("Are you sure?")){
      var id = $(this).data('id');
      $.post('delete.php',{id:id},function(res){
        if(res.status == 1){
          table.ajax.reload();
          alert(res.message);
        } else {
          alert(res.message);
        }
      },'json');
    }
  });
});
</script>
