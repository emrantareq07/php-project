<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Innovation</title>

<!-- DataTables CSS library -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<!-- DataTables JS library -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <style type="text/css">
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
					<div class="col col-sm-9"><h2 class="float-left text-muted text-uppercase">Innovation Database</h2></div>
					<div class="col col-sm-3">
                            
                        <a href="javascript:void(0)" class="btn btn-info float-right add-model"> Add New Innovation </a>
                        </div>
                        
                    </div>
                    </div>
					<div class="card-body">
                    <div class="table-responsive table-hover">
                   <table id="usersListTable" class="display" >
                        <thead class="thead-dark">
                            <tr>
                                <th>অর্থবছর</th>
                                <th>সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
                                <th>উদ্ভাবক/উদ্ভাবকের নাম</th>
								 <th>পদবী</th>
                                <th>এমপ্লয়ী নং</th>
                                <th>প্রস্তাবকালীন কর্মস্থল</th>
								<th>সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা</th>
                                <th>বাস্তাবায়নের অবস্থা</th>
								<th>রেপ্লিকেট যোগ্যতা</th>
								<th>ফলাফল</th>
                                <th>সেবার লিংক</th>
								<th>মন্তব্য<th>
                               
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
          <div class="modal-header">
              <h4 class="modal-title" id="userCrudModal"></h4>
          </div>
          <div class="modal-body">
              <form id="update-form" name="update-form" class="form-horizontal">
                 <input type="hidden" name="id" id="id">
                  <input type="hidden" class="form-control" id="mode" name="mode" value="update">
                  
				  
				  
				  <!--<div class="form-group">
                      <label for="name" class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-12">
                          <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required="">
                      </div>
                  </div>-->
				 <div class="col-sm-12"> 
							<div class="form-group">
							<label for="fiscal_year">অর্থবছর </label>
    		                    	<input class="form-control" placeholder="অর্থবছর" type="text" name="fiscal_year" value="" id="fiscal_year" required>
    		                	</div>
								<!--
								<div class="form-group"><label for="fiscal_year">অর্থবছর</label>
    		                    	<select class="form-control" id="fiscal_year" name="fiscal_year" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 <option value="২০২২-২০২৩">২০২২-২০২৩</option>
									 <option value="২০২১-২০২২">২০২১-২০২২</option>
									<option value="২০২০-২০২১">২০২০-২০২১</option>
									  <option value="২০১৯-২০২০">২০১৯-২০২০</option>
									  <option value="২০১৮-২০১৯">২০১৮-২০১৯</option>
									  <option value="২০১৭-২০১৮">২০১৭-২০১৮</option>
																		
									
								  </select>
    		                	</div>-->
								
								<div class="form-title_of_invention"><label for="mothersName">উদ্ভাবনের শিরোনাম</label>
    		                    	
									<textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="title_of_invention" value="" name="title_of_invention" required></textarea>
    		                	</div>
								<div class="form-group"><label for="inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
    		                    	<input class="form-control" placeholder="উদ্ভবক/উদ্ভাবকের নাম" name="inventors_name" type="text" value="" id="inventors_name" required>
    		                	
								</div>
								
							  <div class="form-group"><label for="inventors_designation">পদবী</label>
    		                    	<input class="form-control" placeholder="পদবী" type="text" name="inventors_designation" value="" id="inventors_designation" required>
    		                	
								</div>
								<div class="form-group"><label for="inventors_emp_id">এমপ্লয়ী নং</label>
    		                    	<input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" value="" id="inventors_emp_id" required>
    		                	
								</div>
							
							<!-- 3rd Column-->
							
														
									<div class="form-group"><label for="proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
    		                    	<select class="form-control" id="proposed_workplace" name="proposed_workplace" value="">
									
									

									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিসিআইসি প্র: কা:">বিসিআইসি প্র: কা:</option>
									  <option value="জেএফসিএল" >জেএফসিএল</option>
									  <option value="জিপিইউএফসিএল">জিপিইউএফসিএল</option>
									  <option value="এসএফসিএল">এসএফসিএল</option>
									  <option value="এএফসিসিএল">এএফসিসিএল</option>
									  <option value="ডিএপিএফসিএল">ডিএপিএফসিএল</option>
									<option value="সিইউএফএল">সিইউএফএল</option>
									  <option value="সিসিসিএল">সিসিসিএল</option>
									  <option value="কেপিএমএল">কেপিএমএল</option>
									  <option value="টিএসপিসিএল">টিএসপিসিএল</option>
									    <option value="বিআইএসএফএল">বিআইএসএফএল</option>
																		
									
								  </select>
    		                	</div>
							
								
							 
								 <div class="form-group"><label for="des_of_invention">উদ্ভাবনের বর্নণা</label>
    		                    	<textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="des_of_invention" value="" name="des_of_invention" ></textarea>
    		                	</div>
								
								 <!--<div class="form-group"><label for="imple_status">বাস্তাবয়নের অবস্থা</label>
    		                    	<input class="form-control" placeholder="বাস্তাবয়নের অবস্থা" type="text" name="imple_status" id="imple_status" >
    		                	</div>-->
								
										<div class="form-group"><label for="imple_status">বাস্তাবায়নের অবস্থা</label>
    		                    	<select class="form-control" id="imple_status" name="imple_status" value="" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বাস্তবায়িত">বাস্তবায়িত</option>
									  <option value="চলমান">চলমান</option>
									 </select>
    		                	</div>
								
									<div class="form-group"><label for="replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
    		                    	<select class="form-control" id="replicate_eligibility" name="replicate_eligibility" value="">
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিশেষায়িত"  >বিশেষায়িত</option>
									  <option value="যোগ্য"   >যোগ্য</option>
									  <option value="যোগ্য  না"  >যোগ্য  না</option>
									
								  </select>
    		                	</div>
								
									<div class="form-group"><label for="feedback">ফলাফল</label>
    		                    	<select class="form-control" id="feedback" name="feedback" value="">
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									<option value="প্রত্যাশিত">প্রত্যাশিত</option>
									  <option value="অপ্রত্যাশিত">অপ্রত্যাশিত</option>
									 
								  </select>
    		                	</div>
								
								<div class="form-group"><label for="service_link">সেবার লিংক</label>
    		                    	<textarea class="form-control" placeholder="সেবার লিংক" rows="2" id="service_link" value="" name="service_link" ></textarea>
    		                	</div>
								
								<div class="form-group"><label for="remarks">মন্তব্য</label>
    		                    	<textarea class="form-control" placeholder="মন্তব্য" rows="2" id="remarks" value="" name="remarks" ></textarea>
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
<!--Add Innovation-->
<div class="modal fade" id="add-modal" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="userCrudModal"></h4>
          </div>
          <div class="modal-body">
              <form id="add-form" name="add-form" class="form-horizontal">
                 <input type="hidden" class="form-control" id="mode" name="mode" value="add">
				 
				 
                 <!-- <div class="form-group">
                      <label for="name" class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-12">
                          <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required="">
                      </div>
                  </div>-->
				  
				   <div class="col-sm-12"> 
							<div class="form-group">
							<label for="fiscal_year">অর্থবছর </label>
    		                    	<input class="form-control" placeholder="অর্থবছর" type="text" name="fiscal_year" value="" id="fiscal_year" required>
    		                	</div>
								<!--
								<div class="form-group"><label for="fiscal_year">অর্থবছর</label>
    		                    	<select class="form-control" id="fiscal_year" name="fiscal_year" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 <option value="২০২২-২০২৩">২০২২-২০২৩</option>
									 <option value="২০২১-২০২২">২০২১-২০২২</option>
									<option value="২০২০-২০২১">২০২০-২০২১</option>
									  <option value="২০১৯-২০২০">২০১৯-২০২০</option>
									  <option value="২০১৮-২০১৯">২০১৮-২০১৯</option>
									  <option value="২০১৭-২০১৮">২০১৭-২০১৮</option>
																		
									
								  </select>
    		                	</div>-->
								
								<div class="form-title_of_invention"><label for="mothersName">উদ্ভাবনের শিরোনাম</label>
    		                    	
									<textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="title_of_invention" value="" name="title_of_invention" required></textarea>
    		                	</div>
								<div class="form-group"><label for="inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
    		                    	<input class="form-control" placeholder="উদ্ভবক/উদ্ভাবকের নাম" name="inventors_name" type="text" value="" id="inventors_name" required>
    		                	
								</div>
								
							  <div class="form-group"><label for="inventors_designation">পদবী</label>
    		                    	<input class="form-control" placeholder="পদবী" type="text" name="inventors_designation" value="" id="inventors_designation" required>
    		                	
								</div>
								<div class="form-group"><label for="inventors_emp_id">এমপ্লয়ী নং</label>
    		                    	<input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" value="" id="inventors_emp_id" required>
    		                	
								</div>
							
							<!-- 3rd Column-->
												
									<div class="form-group"><label for="proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
    		                    	<select class="form-control" id="proposed_workplace" name="proposed_workplace" value="">
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিসিআইসি প্র: কা:">বিসিআইসি প্র: কা:</option>
									  <option value="জেএফসিএল" >জেএফসিএল</option>
									  <option value="জিপিইউএফসিএল">জিপিইউএফসিএল</option>
									  <option value="এসএফসিএল">এসএফসিএল</option>
									  <option value="এএফসিসিএল">এএফসিসিএল</option>
									  <option value="ডিএপিএফসিএল">ডিএপিএফসিএল</option>
									<option value="সিইউএফএল">সিইউএফএল</option>
									  <option value="সিসিসিএল">সিসিসিএল</option>
									  <option value="কেপিএমএল">কেপিএমএল</option>
									  <option value="টিএসপিসিএল">টিএসপিসিএল</option>
									    <option value="বিআইএসএফএল">বিআইএসএফএল</option>
										<option value="বিআইএসএফএল">ইউজিএসএফএল</option>
								</select>
    		                	</div>
							<div class="form-group"><label for="des_of_invention">উদ্ভাবনের বর্নণা</label>
    		                    	<textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="des_of_invention" value="" name="des_of_invention" ></textarea>
    		                	</div>
								
								 <!--<div class="form-group"><label for="imple_status">বাস্তাবয়নের অবস্থা</label>
    		                    	<input class="form-control" placeholder="বাস্তাবয়নের অবস্থা" type="text" name="imple_status" id="imple_status" >
    		                	</div>-->
								
										<div class="form-group"><label for="imple_status">বাস্তাবয়নের অবস্থা</label>
    		                    	<select class="form-control" id="imple_status" name="imple_status" value="" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বাস্তবায়িত">বাস্তবায়িত</option>
									  <option value="চলমান">চলমান</option>
									 </select>
    		                	</div>
								
									<div class="form-group"><label for="replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
    		                    	<select class="form-control" id="replicate_eligibility" name="replicate_eligibility" value="">
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিশেষায়িত"  >বিশেষায়িত</option>
									  <option value="যোগ্য"   >যোগ্য</option>
									  <option value="যোগ্য  না"  >যোগ্য  না</option>
									
								  </select>
    		                	</div>
									<div class="form-group"><label for="feedback">ফলাফল</label>
    		                    	<select class="form-control" id="feedback" name="feedback" value="">
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									<option value="প্রত্যাশিত">প্রত্যাশিত</option>
									  <option value="অপ্রত্যাশিত">অপ্রত্যাশিত</option>
									 
								  </select>
    		                	</div>
								<div class="form-group"><label for="service_link">সেবার লিংক</label>
    		                    	<textarea class="form-control" placeholder="সেবার লিংক" rows="2" id="service_link" value="" name="service_link" ></textarea>
    		                	</div>
								
								<div class="form-group"><label for="remarks">মন্তব্য</label>
    		                    	<textarea class="form-control" placeholder="মন্তব্য" rows="2" id="remarks" value="" name="remarks" ></textarea>
    		                	</div>
								
								
								</div>
								<!--<div class="form-group">
							<label for="parmanent_add">Permanent Address:</label>
							<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="permanent_add" name="permanent_add" ></textarea>
    		                    	<!-- An element to toggle between password visibility 
									<input type="checkbox" onclick="Copydata()"> Same As Present Address
									<script>
										function Copydata(){
										  $("#permanent_add").val($("#present_add").val());
										}
										</script>
    		                	</div>-->
								
								
							
				  
				  
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