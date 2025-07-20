<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");
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
      foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
//session  condition  end  but it follows until bottom of the page

  // require_once('db/db.php');

  // $sql = "SELECT * FROM designation";
  // $result = mysqli_query($conn, $sql);
  // while($row = mysqli_fetch_array($result))
  //      {
    // echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Innovation Database</title>

<!-- DataTables CSS library -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<!-- DataTables JS library -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
-->
<style>
 
* {
	font-family: 'Open Sans', sans-serif;
    font-family: 'Tiro Bangla', serif;
	 font-family: 'Noto Sans Bengali', sans-serif;
    font-family: 'Nikosh', sans-serif;
    font-family: 'Nikosh', serif;
}
.bs-example{
            margin: 10px;
        }
  </style>
   <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
 </head>
<body>

 <div class="bs-example">
    <div class="container-fluid">
      <div class="row">
			<div class="col col-sm-12">
        <div class="card shadow border border-secondary rounded">				
          <div class="card-header">
          <div class="row">
					<div class="col col-sm-6"><h2 class="float-left text-success text-uppercase fw-bold">BCIC Innovation Database</h2></div>
					<div class="col col-sm-6">
					<span class="float-right">
            <a href="dashboard.php" class="btn btn-primary text-white text-center"><i class="fa fa-arrow-left"></i> Previous Page</a>  
            <a href="javascript:void(0)" class="btn btn-success text-right add-model"> <i class="fa fa-plus"></i> Add New Innovation </a>
					<a href="logout.php" class="btn btn-danger btn-xs text-center"><i class="fa fa-sign-out"></i>Logout</a>  
						</span>
                </div>
             </div>
           </div>
					<div class="card-body">
            <div class="table-responsive table-hover">
             <table id="usersListTable" class="display" >
                  <thead class="table-success">
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
              </table>
				  </div>
          </div>
          </div>
				</div>
       </div>        
      </div>
    </div>


 <!--Update-->
<div class="modal fade" id="edit-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal">Update Innovation Data</h4>
          </div>
          <div class="modal-body">
              <form id="update-form" name="update-form" class="form-horizontal">
                 <input type="hidden" name="id" id="id">
                  <input type="hidden" class="form-control" id="mode" name="mode" value="update">
                  
				 <div class="col-sm-12"> 
							<!-- <div class="form-group">
  							<label for="fiscal_year">অর্থবছর </label>
      		      <input class="form-control" placeholder="অর্থবছর" type="text" name="fiscal_year" value="" id="fiscal_year" required>
    		      </div> -->	

								<div class="form-group"><label for="fiscal_year">অর্থবছর</label>
    		          <select class="form-control" id="fiscal_year" name="fiscal_year" value="">									
									<option value="" disabled selected>নির্বাচন করুন</option>
                   <option value="২০২৪-২০২৫">২০২৪-২০২৫</option>
                   <option value="২০২৩-২০২৪">২০২৩-২০২৪</option>
									 <option value="২০২২-২০২৩">২০২২-২০২৩</option>
									 <option value="২০২১-২০২২">২০২১-২০২২</option>
									<option value="২০২০-২০২১">২০২০-২০২১</option>
									  <option value="২০১৯-২০২০">২০১৯-২০২০</option>
									  <option value="২০১৮-২০১৯">২০১৮-২০১৯</option>
									  <option value="২০১৭-২০১৮">২০১৭-২০১৮</option>
								  </select>
    		          </div>
								
								<div class="form-title_of_invention"><label for="mothersName">উদ্ভাবনের শিরোনাম</label>    		                    	
									<textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="title_of_invention" value="" name="title_of_invention" required></textarea>
    		        </div>

								<div class="form-group"><label for="inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
    		          <input class="form-control" placeholder="উদ্ভবক/উদ্ভাবকের নাম" name="inventors_name" type="text" value="" id="inventors_name" required>
								</div>
								
							  <div class="form-group"><label for="inventors_designation">পদবী</label>
    		            <!-- <input class="form-control" placeholder="পদবী" type="text" name="inventors_designation" value="" id="inventors_designation" required> -->
                    <select class="form-control" id="inventors_designation" name="inventors_designation" value="">
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
                </select> 
								</div>

								<div class="form-group"><label for="inventors_emp_id">এমপ্লয়ী নং</label>
    		         	<input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" value="" id="inventors_emp_id" >    		                	
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
									   <option value="বিআইএসএফএল">বিআইএসএফএল</option>ইউজিএসএফএল
									   <option value="ইউজিএসএফএল">ইউজিএসএফএল</option>								
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
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal"> Add Innovation Data</h4>
          </div>
          <div class="modal-body">
              <form id="add-form" name="add-form" class="form-horizontal">
                 <input type="hidden" class="form-control" id="mode" name="mode" value="add">
				 				  
				   <div class="col-sm-12"> 

                <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="fiscal_year">অর্থবছর</label>
                      <select class="form-control" id="fiscal_year" name="fiscal_year">
                        <?php
                          require_once('db/db.php');
                          $sql = "SELECT * FROM fiscal_year order by id asc";
                          $result = mysqli_query($conn, $sql);
                          while($row = mysqli_fetch_array($result)){
                           echo "<option value='".$row['fiscal_year']."'>".$row['fiscal_year']."</option>";
                          }
                          ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="title_of_invention">উদ্ভাবনের শিরোনাম</label>
                      <textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="title_of_invention" name="title_of_invention" required></textarea>
                    </div>
                  </div>
                </div>

                 <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
                     <input class="form-control" placeholder="উদ্ভবক/উদ্ভাবকের নাম" name="inventors_name" type="text" value="" id="inventors_name" required> 
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inventors_designation">পদবী</label>                      
                      <select class="form-control" id="inventors_designation" name="inventors_designation" value="">
                        <option value="" disabled selected>--নির্বাচন করুন--</option>
                       <?php
                          require_once('db/db.php');
                          $sql = "SELECT * FROM designation";
                          $result = mysqli_query($conn, $sql);
                          while($row = mysqli_fetch_array($result)){
                           echo "<option value='".$row['designation_bn']."'>".$row['designation_bn']."</option>";
                          }
                          ?>
                        </select>                     
                    </div>
                  </div>
                </div>


                 <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inventors_emp_id">এমপ্লয়ী নং</label>
                      <input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" value="" id="inventors_emp_id" >   
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                       <label for="proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
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
                          <option value="ইউজিএসএফএল">ইউজিএসএফএল</option>
                      </select>                   
                    </div>
                  </div>
                </div>

							<div class="form-group"><label for="des_of_invention">উদ্ভাবনের বর্নণা</label>
    		          <textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="des_of_invention" value="" name="des_of_invention" ></textarea>
    		      </div>

                <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="imple_status">বাস্তাবয়নের অবস্থা</label>
                      <select class="form-control" id="imple_status" name="imple_status" value="" >                 
                        <option value="" disabled selected>নির্বাচন করুন</option>                  
                        <option value="বাস্তবায়িত">বাস্তবায়িত</option>
                        <option value="চলমান">চলমান</option>
                   </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                       <label for="replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
                    <select class="form-control" id="replicate_eligibility" name="replicate_eligibility" value="">
                    <option value="" disabled selected>নির্বাচন করুন</option>                  
                    <option value="বিশেষায়িত">বিশেষায়িত</option>
                    <option value="যোগ্য">যোগ্য</option>
                    <option value="যোগ্য  না">যোগ্য  না</option>                  
                  </select>                   
                    </div>
                  </div>
                </div>


                <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="feedback">ফলাফল</label>
                    <select class="form-control" id="feedback" name="feedback" value="">                  
                        <option value="" disabled selected>নির্বাচন করুন</option>
                        <option value="প্রত্যাশিত">প্রত্যাশিত</option>
                        <option value="অপ্রত্যাশিত">অপ্রত্যাশিত</option>                   
                    </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="service_link">সেবার লিংক</label>
                  <textarea class="form-control" placeholder="সেবার লিংক" rows="1" id="service_link" value="" name="service_link" ></textarea>                    
                    </div>
                  </div>
                </div>
																	
								<div class="form-group"><label for="remarks">মন্তব্য</label>
    		          <textarea class="form-control" placeholder="মন্তব্য" rows="2" id="remarks" value="" name="remarks" ></textarea>
    		      	</div>	
								</div>
								<!--<div class="form-group">
							<label for="parmanent_add">Permanent Address:</label>
							<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="permanent_add" name="permanent_add" ></textarea>
    		                    	 An element to toggle between password visibility 
									<input type="checkbox" onclick="Copydata()"> Same As Present Address
									<script>
										function Copydata(){
										  $("#permanent_add").val($("#present_add").val());
										}
										</script>
    		                	</div>-->
				  
                  <div class=" float-end">
                   <button type="submit" class="btn btn-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save
                   </button>
                  </div>
              </form>
          </div>
          <div class="modal-footer"><small>Design & Developed By Md. Tareq Emran, Programmer, ICT Division, BCIC.</small>  </div>
      </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="datatables.js"></script>
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
$('#add-form').submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: "add-edit-delete.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                var oTable = $('#usersListTable').DataTable();
                oTable.draw(false);
                $('#add-modal').modal('hide');
                $('#add-form').trigger("reset");
                alert(response.message);
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
            alert("An unexpected error occurred. Please try again.");
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
</body>
</html>
<?php
 } else{
  // header('location:login/logout.php');
   header('location:login.php');
  } 
?>