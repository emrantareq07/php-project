<?php
session_start();
  
require_once("../config/config.php");
require_once("../db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";

      $result= $usercredentials->runBaseQuery($query);

      foreach ($result as $k => $v) 
      {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
//session  condition  end  but it follows until bottom of the page


?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Admin Dashboard</title>

<!-- DataTables CSS library -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<!-- DataTables JS library -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
 
* {
	font-family: 'Open Sans', sans-serif;

    font-family: 'Tiro Bangla', serif;
	<!--font-family: 'Noto Sans Bengali', sans-serif;

    font-family: 'Nikosh', sans-serif;

    font-family: 'Nikosh', serif;-->
}
.bs-example{
            margin: 20px;
        }
  </style>
 </head>
<body>
    <div class="bs-example">
        <div class="container-fluid">
            <div class="row">
			<div class="col col-sm-12">
                <div class="card">
				
                    <div class="card-header">
                    <div class="row">
					<div class="col col-sm-8"><h2 class="float-left text-muted text-uppercase">Notice Board</h2></div>
					<div class="col col-sm-4">
					<span class="float-right">
                         <a href="dashboard.php" class="btn btn-info text-center"> Previous Page</a>  
                        <a href="javascript:void(0)" class="btn btn-info text-right add-model"> Add New Notice</a></span>
                        </div>
                        
                    </div>
                    </div>
					<div class="card-body">
                    <div class="table-responsive table-hover">
                   <table id="usersListTable" class="display" >
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Notice</th>
								                <th>Date</th>
                                <th>Created At (Date & Time)</th>
								                <th>Action</th>
								 
                               
                            </tr>
                        </thead>
                        <!--<tfoot>
                            <tr>
                                <th>অর্থবছর</th>
                                <th>উদ্ভাবনের শিরোনাম</th>
                                <th>উদ্ভাবক/উদ্ভাবকের নাম</th>
								 <th>পদবী</th>
                                <th>এমপ্লয়ী নং</th>
                                <th>প্রস্তাবকালীন কর্মস্থল</th>
								<th>উদ্ভাবনের বর্নণা</th>
                                <th>বাস্তাবয়নের অবস্থা</th>
								<th>রেপ্লিকেট যোগ্যতা</th>
                                <th>Action</th>

                            </tr>
                        </tfoot>-->
                  </table>
				  </div>
                </div>
                </div>
				</div>
            </div>        
        </div>
    </div>
</body>
 <!--Update-->
<div class="modal fade" id="edit-modal" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal">Update Innovation Data</h4>
          </div>
          <div class="modal-body">
              <form id="update-form" name="update-form" class="form-horizontal">
                 <input type="hidden" name="id" id="id">
                  <input type="hidden" class="form-control" id="mode" name="mode" value="update">
                  
				  
				 <div class="col-sm-12"> 
					<div class="form-group">
					<label for="notice">Notice </label>
							<input class="form-control" placeholder="notice" type="text" name="notice" value="" id="notice" required>
						</div>
					
						<div class="form-group"><label for="date">Date</label>
							<input class="form-control" placeholder="" type="date" name="date" id="date" value="" required>
						</div>
						
					</div>
								
				  
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-primary" id="btn-save" value="create">Update 
                   </button>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
             
          </div>
      </div>
  </div>
</div>
<!--Add Notice-->
<div class="modal fade" id="add-modal" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal"> Add Notice Data</h4>
          </div>
          <div class="modal-body">
              <form id="add-form" name="add-form" class="form-horizontal" enctype="multipart/form-data" >
                 <input type="hidden" class="form-control" id="mode" name="mode" value="add">
				 
							  
				   <div class="col-sm-12"> 
					<div class="form-group">
					<label for="notice">Notice Title </label>
					<textarea class="form-control" placeholder="Enter Notice" rows="2" id="notice" value="" name="notice" required></textarea>
							<!--<input class="form-control" placeholder="notice" type="text" name="notice" value="" id="notice" required>
						-->
						</div>
					
						<div class="form-group"><label for="dated">Date</label>
							<input class="form-control" placeholder="" type="date" name="dated" id="dated" value="" required>
						</div>

             <div class="form-group">
                <input type="file" name="pdf_file" class="form-control" accept=".pdf" title="Upload PDF"/>
             </div>
			
					</div>
					
				  
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save
                   </button>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
             
          </div>
      </div>
  </div>
</div>

<script>
$(document).ready(function(){
    $('#usersListTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": "fetch.php"
    });
});


  /*  add user model */
$('.add-model').click(function () {
    $('#add-modal').modal('show');
});

// add form submit
$('#add-form').submit(function(e){

    e.preventDefault();

    // ajax
    $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: $(this).serialize(), // get all form field value in serialize form
        success: function(){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
            $('#add-modal').modal('hide');
            $('#add-form').trigger("reset");
        }
    });
});  

/* edit user function */
$('body').on('click', '.btn-edit', function () {
    var id = $(this).data('id');
     $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: {
            id: id,
            mode: 'edit' 
        },
        dataType : 'json',
        success: function(result){
          $('#id').val(result.id);
          $('#fiscal_year').val(result.fiscal_year);
          $('#title_of_invention').val(result.title_of_invention);
		  $('#inventors_name').val(result.inventors_name);
          $('#inventors_designation').val(result.inventors_designation);
		  $('#inventors_emp_id').val(result.inventors_emp_id);
          $('#proposed_workplace').val(result.proposed_workplace);
		  $('#des_of_invention').val(result.des_of_invention);
          $('#imple_status').val(result.imple_status);
		  $('#replicate_eligibility').val(result.replicate_eligibility);
         
          $('#feedback').val(result.feedback);
		  $('#service_link').val(result.service_link);
		  $('#remarks').val(result.remarks);
		  
          $('#edit-modal').modal('show');
        }
    });
});

// add form submit
$('#update-form').submit(function(e){

    e.preventDefault();
       
    // ajax
    $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: $(this).serialize(), // get all form field value in serialize form
        success: function(){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
            $('#edit-modal').modal('hide');
            $('#update-form').trigger("reset");
        }
    });
});  

/* DELETE FUNCTION */
$('body').on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    if (confirm("Are You sure want to delete !")) {
     $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: {
            id: id,
            mode: 'delete' 
        },
        dataType : 'json',
        success: function(result){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
        }
     });
    } 
    return false;
});
$(document).ready( function () {
		$('.table').DataTable({
			 "order": [[1, "desc"]], //"2" is my date array position
			 lengthMenu: [
            [5,10, 25, 50, -1],
            [5,10, 25, 50, 'All'],
        ],
		});
  });
</script>
</html>
<?php } else{
  // header('location:login/logout.php');
   header('location:login.php');
  } 
?>