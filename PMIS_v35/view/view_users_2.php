<?php include_once('../includes/header-view_users.php');
//session_start();
require_once('../db/db.php');
?>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <div class="container-fluid box shadow">
   <h2 align="center" class="text-center text-muted text-uppercase shadow-md"><b>BCIC PMIS Admin Dashboard</b></h2>
  <h4 class="text-center text-success"><b>[--All Officer & Employees--]</b></h4>
   <div class="table-responsive">
    <span class="">
	<a href="../dashboard.php" class="btn btn-primary "><i class="fa fa-dashboard" style="color:white"></i> Dashboard Page</a>
  <button id="reset" class="btn btn-danger text-white">Reset</button></span>

    <span style="float:right;">
      <a href="view_in_service_officers_list.php" class="btn btn-primary"><i class="fa fa-users" style="color:white"></i> Officer's List (In-Service)</a>
      <a href="prl_officers_list.php" class="btn btn-primary"><i class="fa fa-list" style="color:white"></i> Officer's List (PRL)</a>
      <a href="prl_officers_list.php" class="btn btn-primary"> <i class="fa fa-navicon" style="color:white"></i> Officer's List (Retired)</a>
     <!--  <a href="prl_officers_list.php" class="btn btn-danger"><i class="fa fa-navicon" style="color:white"></i> Officer's List (Resignation)</a> -->
     <a href="../admin/create_new_employee_id.php" class="btn btn-primary"><i class="fa fa-users" style="color:white"></i> Create New Employee ID</a>

      <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-primary"><i class="fa fa-plus" style="color:white"></i>
       Add User/ Emplyoee</button></span>
      
    <hr class="border border-warning">
    <table id="user_data" class="table table-bordered table-responsive table-hover table-striped" class="display nowrap" style="width:100%">
     <thead class="table-success">
      <tr>
       <th >Photo</th>
	     <th >Emp ID</th>
       <th >SNR. No.</th>
       <th >Name</th>
       <th >Designation</th>
       <!-- <th width="35%">Sub Cadre Header</th> -->
       <th >Division/Office</th>
       <th >Section</th>
       <!-- <th >Job Status</th> -->
       <th >Mobile</th>
       <th >Email</th>
	     <th >View</th>
       <th >Edit</th>
       <th>Delete</th>
      <!-- <th width="10%">Bio-Data</th> -->
      </tr>
     </thead>
    </table>
    
   </div>
  </div>
 </body>
</html>

<div id="userModal" class="modal fade">
  <!-- <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModal" aria-hidden="true"> -->
 <div class="modal-dialog" >
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header"><h5 class="modal-title text-uppercase">Add User/Employee </h5>
     <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
      <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
        <button type="button" class="btn-close" data-dismiss="modal"></button>
     
    </div>
        <div class="modal-body">
   
       <div class="form-floating mb-2 mt-2">
        <input type="text" class="form-control" id="emp_id" placeholder="Enter Employee ID" name="emp_id">
        <label for="emp_id" > Employee ID</label>
      </div>

         <div class="form-floating mb-2 mt-2">
        <input type="number" class="form-control" id="seniority_no" placeholder="Enter Seniority No." name="seniority_no">
        <label for="seniority_no"> Seniority No.</label>
      </div>
   
      <div class="form-floating mb-2 mt-2">
        <input type="text" class="form-control" id="name" placeholder="Enter Full Name" name="name">
        <label for="name"> Full Name</label>
      </div>
	
        <div class="form-floating mb-2 mt-2">
        <select class="form-select" id="designation" name="designation" aria-label="Floating label select example">
          <option value="" disabled selected>--Select--</option>
          <?php
            $sql = "SELECT * FROM designation order by designation ASC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)) {
             echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
            }

            ?>   
        </select>
        <label for="designation"> Designation</label>
      </div>

        <div class="form-floating mb-2 mt-2">
        <select class="form-select" id="sub_cadre_header" name="sub_cadre_header" aria-label="Floating label select example">
          <option value="Select"  selected>--Select--</option>
          <?php
            $sql = "SELECT header FROM sub_cadre_header order by header ASC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)) {
             echo "<option value='".$row['header']."'>".$row['header']."</option>";
            }

            ?>   
        </select>
        <label for="sub_cadre_header"> Designation Ext.</label>
      </div>

        <div class="form-floating mb-2 mt-2">
        <select class="form-select" id="division" name="division" aria-label="Floating label select example">
          <option value="" disabled selected>--Select--</option>
          <?php
            $sql = "SELECT * FROM division";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)) {
              echo "<option value='".$row['division']."'>".$row['division']."</option>";
            }

            ?>   
        </select>
        <label for="division"> Division</label>
      </div>             
  
       <div class="form-floating mb-2 mt-2">
        <select class="form-select" id="section" name="section" aria-label="Floating label select example">
          <option value="" disabled selected>--Select--</option>
          <?php
            $sql = "SELECT name FROM section";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)) {
              echo "<option value='".$row['name']."'>".$row['name']."</option>";
            }

            ?>   
        </select>
        <label for="section"> Section</label>
      </div>
     
       <div class="form-floating mb-2 mt-2">
        <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile Number" name="mobile_no" maxlength="11" pattern="\d{10}">
        <label for="mobile_no"> Mobile Number</label>
      </div>
      
       <div class="form-floating mb-2 mt-2">
        <input type="email" class="form-control" id="email" placeholder="Enter Mobile Number" name="email">
        <label for="email"> Email Address</label>
      </div>
       <!-- <br /> -->
     <label>Select User Image</label>
       <input type="file" name="user_image" id="user_image" accept=".jpg, .jpeg,.png,.gif"/>
       <span id="user_uploaded_image"></span>
    </div>
    <div class="modal-footer my-0">
     <input type="hidden" name="user_id" id="user_id" />
     <input type="hidden" name="operation" id="operation" />
     <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
     <button type="button" class="btn btn-default btn-outline-danger" data-dismiss="modal">Close</button>
    </div>
   </div>
  </form>
 </div>
</div>


<?php include_once('../includes/footer-print.php');?>
<script type="text/javascript" language="javascript" >

$(document).ready(function(){

   $('#user_form').on('shown.bs.modal', function () {
      $('#emp_id').focus();
  })

 $('#add_button').click(function(){
  $('#user_form')[0].reset();
  $('.modal-title').text("Add User/Employee Information");
  $('#action').val("Add");
  $('#operation').val("Add");
  $('#user_uploaded_image').html('');
 });
 
 var dataTable = $('#user_data').DataTable({
       
  lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'All'],
        ],
  responsive: true,
  "processing":true,
  "serverSide":true,
  "order":[],
 
  "ajax":{
   url:"../controller/fetch.php",
   type:"POST"
  },
  "columnDefs":[
   {
    "targets":[0, 3, 4],
    "orderable":false,
   },
  ],

 });
   

 $(document).on('submit', '#user_form', function(event){
  event.preventDefault();
  var emp_id = $('#emp_id').val();
  var seniority_no = $('#seniority_no').val();
  var name = $('#name').val();
  var designation = $('#designation').val();
  var sub_cadre_header = $('#sub_cadre_header').val();
  var division = $('#division').val();
  var section = $('#section').val();
  // var job_status = $('#job_status').val();
  var mobile_no = $('#mobile_no').val();
  var email = $('#email').val();

  var extension = $('#user_image').val().split('.').pop().toLowerCase();
  if(extension != ''){
   if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1){
    alert("Invalid Image File");
    $('#user_image').val('');
    return false;
   }
  } 
  // if(emp_id != '' && seniority_no != '' && name != '' && designation != '' && division != '' && section != '' && mobile_no != '' && email != '')
      if(emp_id != '' && name != '' && designation != '' && division != '' && section != '' &&  mobile_no != '' && email != ''){
   $.ajax({
    url:"../controller/insert_new.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    success:function(data){
     alert(data);
     $('#user_form')[0].reset();
     $('#userModal').modal('hide');
     dataTable.ajax.reload();
    }
   });
  }
  else{
   alert("Both Fields are Required");
  }
 });
 
 $(document).on('click', '.update', function(){
  var user_id = $(this).attr("id");
  $.ajax({
   url:"../controller/fetch_single.php",
   method:"POST",
   data:{user_id:user_id},
   dataType:"json",
   success:function(data){
    $('#userModal').modal('show');
    $('#emp_id').val(data.emp_id);
    $('#seniority_no').val(data.seniority_no);
    $('#name').val(data.name);
    $('#designation').val(data.designation);
    $('#sub_cadre_header').val(data.sub_cadre_header);
	  $('#division').val(data.division);
    $('#section').val(data.section);
    // $('#job_status').val(data.job_status);
    $('#mobile_no').val(data.mobile_no);
    $('#email').val(data.email);
    $('.modal-title').text("Edit User");
    $('#user_id').val(user_id);
    $('#user_uploaded_image').html(data.user_image);
    $('#action').val("Update");
    $('#operation').val("Edit");
   }
  })
 });
 
 $(document).on('click', '.delete', function(){
  var user_id = $(this).attr("id");
  if(confirm("Are you sure you want to delete this?")){
   $.ajax({
    url:"../controller/delete.php",
    method:"POST",
    data:{user_id:user_id},
    success:function(data){
     alert(data);
     dataTable.ajax.reload();
    }
   });
  }
  else{
   return false; 
  }
 });
 
 $(document).on('click', '.view', function(){
  var emp_id = $(this).attr("id");

   $.ajax({
    url:"../view/view.php",
    method:"POST",
    data:{emp_id:emp_id},
    success:function(data){
		
       window.location.href = 'view_profile_details.php?emp_id='+emp_id;
	   }    
   });
 });
 
$(document).on('click', '.print', function(){
//var myValue = dt.rows( { selected: true } ).data()[0].user_id;
  var emp_id = $(this).attr("id");
//var data = table.row(this).data();
// alert('You clicked on ' + data[0] + "'s row");
   $.ajax({
    url:"../view/view.php",
    method:"POST",
    data:{emp_id:emp_id},
    success:function(data)
    {
     window.location.href = 'view_bio_data.php?emp_id='+emp_id;
  
   }
    
   });

 
 });

$(document).on("click", "#reset", function(e) {
        e.preventDefault();
      
     var table = $('#user_data').DataTable(); 
    $('#user_data').val('');
    
   table.search('').columns().search('').draw();
 });

});
</script>