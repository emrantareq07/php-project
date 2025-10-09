<html>
 <head>
  <title>Webslesson Demo - PHP PDO Ajax CRUD with Data Tables and Bootstrap Modals</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style>
   body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:25px;
   }
  </style>
 </head>
 <body>
  <div class="container box">
   <h1 align="center">PHP PDO Ajax CRUD with Data Tables and Bootstrap Modals</h1>
   <br />
   <div class="table-responsive">
    <br />
    <div align="right">
     <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Add</button>
    </div>
    <br /><br />
    <table id="user_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th width="10%">Image</th>
       <th width="35%">Name</th>
       <th width="35%">Designation</th>
       <th width="10%">Division</th>
       <th width="35%">Section</th>
       <th width="35%">Mobile</th>
       <th width="35%">Email</th>
       <th width="10%">Edit</th>
       <th width="10%">Delete</th>
      </tr>
     </thead>
    </table>
    
   </div>
  </div>
 </body>
</html>

<div id="userModal" class="modal fade">
 <div class="modal-dialog">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">Add User/Employee Data </h4>
    </div>
    <div class="modal-body">
		<label>Enter Name</label>
     <input type="text" name="name" id="name" class="form-control" />
     <br />
		<label>Enter Designation</label>
     <!--<input type="text" name="designation" id="designation" class="form-control" />-->
     <select class="form-control" id="designation" name="designation" value="">
          <option value="" disabled selected>--Select--</option>
		    
                  <option value="Assistant Engineer">Assistant Engineer</option>
                    <option value="Executive Engineer" >Executive Engineer</option>
                    <option value="Deputy Chief Engineer">Deputy Chief Engineer</option>
                    <option value="Additional Chief Engineer">Additional Chief Engineer</option>
                    <option value="General Manager ">General Manager </option>
                    <option value="Sr. System Analyst">Sr. System Analyst</option>
                  <option value="Deputy Manager">Deputy Manager</option>
                    <option value="Manager">Manager</option>
                    <option value="Deputy General Manager">Deputy General Manager</option>
					
					<option value="Addl. Chief Accountant">Addl. Chief Accountant</option>
                    <option value="Assistant Programmer">Assistant Programmer</option>
                    <option value="Programmer">Programmer</option>
                    <option value="Chairman (Grade-1)">Chairman (Grade-1)</option>
                    <option value="Director(Commerce)">Director(Commerce)</option>
                    <option value="Director(Finance)">Director(Finance)</option>
                  <option value="Director(T&E)">Director(T&E)</option>
                    <option value="Director(P&I)">Director(P&I)</option>
                    <option value="Director(Production)">Director(Production)</option>
					
					<option value="Sr.GM(Admin)">Sr.GM(Admin)</option>
                    <option value="GM(MTS)/Chief Engineer(MTS)">GM(MTS)/Chief Engineer(MTS)</option>
                    <option value="Accounts Officer">Accounts Officer</option>
                    <option value="Assistant Accounts Officer">Assistant Accounts Officer</option>
                    <option value="Assistant Admin Officer">Assistant Admin Officer</option>
                    <option value="Assistant Com.Officer">Assistant Com.Officer</option>
                  <option value="Assistant Manager (Admin)">Assistant Manager (Admin)</option>
                    <option value="Assistant Manager (Com.)">Assistant Manager (Com.)</option>
                    <option value="Assistant Technical Officer">Assistant Technical Officer</option>
					
					<option value="Assistant Operation Officer">Assistant Operation Officer</option>
                  <option value="Operation Officer">Operation Officer</option>
                    <option value="Technical Officer">Technical Officer</option>
                    <option value="System Analyst">System Analyst</option>
         <?php
             //include('database.php');

            //$sql = "SELECT * FROM designation";
            //$result = mysqli_query($connection, $sql);
            //while($row = mysqli_fetch_array($result))
           // {
            // echo "<option value='".$row['designation_type']."'>".$row['designation_type']."</option>";
            //}
			    
			?>               
        
        </select>
     <br />
	 
	      <label>Enter Division</label>
     <!--<input type="text" name="division_name" id="division_name" class="form-control" />-->
            <select class="form-control" id="division_name" name="division_name" value="">
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   
                  <option value="Administration">Administration</option>
                    <option value="Accounts" >Accounts</option>
                    <option value="MTS">MTS</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Technical">Technical</option>
                    <option value="Operation">Operation</option>
                  <option value="PRD">PRD</option>
                    <option value="Personal Division">Personal Division</option>
                    <option value="PRD">PRD</option>
           <?php
             //include('database.php');

            //$sql = "SELECT * FROM designation";
            //$result = mysqli_query($connection, $sql);
            //while($row = mysqli_fetch_array($result))
            //{
            //echo "<option value='".$row['designation_type']."'>".$row['designation_type']."</option>";
           // }
			    
			?>
                </select>
     <br />
     <label>Enter Section</label>
     <input type="text" name="section_name" id="section_name" class="form-control" />
	 
	 
     <br />
	      <label>Enter Phone No.(Office)</label>
     <input type="text" name="phone_office" id="phone_office" class="form-control" />
     <br />
     <label>Enter Phone No.(Res.)</label>
     <input type="text" name="phone_home" id="phone_home" class="form-control" />
     <br />
	      <label>Enter Intercom/PABX</label>
     <input type="text" name="intercom" id="intercom" class="form-control" />
     <br />
     <label>Enter Mobile Number</label>
     <input type="text" name="mobile" id="mobile" class="form-control" />
     <br />
	   <label>Enter Email</label>
     <input type="email" name="email" id="email" class="form-control" />
     <br />
     <label>Select User Image</label>
     <input type="file" name="user_image" id="user_image" />
     <span id="user_uploaded_image"></span>
    </div>
    <div class="modal-footer">
     <input type="hidden" name="user_id" id="user_id" />
     <input type="hidden" name="operation" id="operation" />
     <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
   </div>
  </form>
 </div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 $('#add_button').click(function(){
  $('#user_form')[0].reset();
  $('.modal-title').text("Add User/Employee Information");
  $('#action').val("Add");
  $('#operation').val("Add");
  $('#user_uploaded_image').html('');
 });
 
 var dataTable = $('#user_data').DataTable({
  "processing":true,
  "serverSide":true,
  "order":[],
  "ajax":{
   url:"fetch.php",
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
  var name = $('#name').val();
  var designation = $('#designation').val();
  var division_name = $('#division_name').val();
  var section_name = $('#section_name').val();
  var phone_office = $('#phone_office').val();
  var phone_home = $('#phone_home').val();
  var intercom = $('#intercom').val();
  var mobile = $('#mobile').val();
  var email = $('#email').val();

  var extension = $('#user_image').val().split('.').pop().toLowerCase();
  if(extension != '')
  {
   if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
   {
    alert("Invalid Image File");
    $('#user_image').val('');
    return false;
   }
  } 
  if(name != '' && designation != '' && division_name != '' && section_name != '' && phone_office != '' && phone_home != '' && intercom != '' && mobile != '' && email != '')
  {
   $.ajax({
    url:"insert_new.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    success:function(data)
    {
     alert(data);
     $('#user_form')[0].reset();
     $('#userModal').modal('hide');
     dataTable.ajax.reload();
    }
   });
  }
  else
  {
   alert("Both Fields are Required");
  }
 });
 
 $(document).on('click', '.update', function(){
  var user_id = $(this).attr("id");
  $.ajax({
   url:"fetch_single.php",
   method:"POST",
   data:{user_id:user_id},
   dataType:"json",
   success:function(data)
   {
    $('#userModal').modal('show');
    $('#name').val(data.name);
    $('#designation').val(data.designation);
	  $('#division_name').val(data.division_name);
    $('#section_name').val(data.section_name);
    $('#phone_office').val(data.phone_office);
    $('#phone_home').val(data.phone_home);
    $('#intercom').val(data.intercom);
	  $('#mobile').val(data.mobile);
    $('#email').val(data.email);
    $('.modal-title').text("Edit User");
    $('#user_id').val(user_id);
    $('#user_uploaded_image').html(data.user_image);
    $('#action').val("Edit");
    $('#operation').val("Edit");
   }
  })
 });
 
 $(document).on('click', '.delete', function(){
  var user_id = $(this).attr("id");
  if(confirm("Are you sure you want to delete this?"))
  {
   $.ajax({
    url:"delete.php",
    method:"POST",
    data:{user_id:user_id},
    success:function(data)
    {
     alert(data);
     dataTable.ajax.reload();
    }
   });
  }
  else
  {
   return false; 
  }
 });
 
 
});
</script>