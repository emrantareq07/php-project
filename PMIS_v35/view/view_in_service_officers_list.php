
<?php include_once('../includes/header-view_users.php');
//session_start();
require_once('../db/db.php');?>

  <style type="text/css">
    body{
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box {
    width:auto;
    padding:10px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin:10px 10px;
  box-shadow: 5px 10px 18px #888888;
/*box-shadow: 5px 5px 8px blue, 10px 10px 8px red, 15px 15px 8px green;
  width:1270px;
margin:10px;
  */
}
</style>

<?php

$sql = "SELECT count(*) total_in_services from basic_info u inner join job_desc j ON u.emp_id=j.emp_id AND j.job_status = 'In Service' ORDER BY u.seniority_no asc ";

$result = mysqli_query($conn, $sql);


  // if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {
//     $array[] = $row;
  // $row=
// }

?>

  <div class="container-fluid box border border-rounded shadow mt-2">

   <h3 align="center" class="text-center text-muted">   
    BCIC PMIS [--Display Only In Services Employees & Officers--] 
    <a href="view_users.php" class="btn btn-primary float-end">Previous Page</a>
    
   </h3>
   <p class="text-center"><b>
    <?php echo "Total No. of Officers In Service: " .$row['total_in_services']; 
  } ?></b></p>
   <br />
   <div class="row">
    
    <div class="col-md-12">
	   <div class="table-responsive">
		<table id="customersTable" class="display" width="100%" cellspacing="0">
			<thead>
				<tr>
          <th width=""> ID</th>
          <th width="">Emp ID</th>
           <th width="">SNR. No.</th>
           <th width="">Name</th>
           <th width="">Designation</th>
           <th width="">Division</th>
           <th width="">Section</th>
           <!-- <th width="">Status</th> -->
           <th width="">Mobile</th>
           <th width="">Email</th>
    <!--        <th width="">View</th>
           <th width="">Edit</th>
           <th width="">Delete</th>
          <th width="">Bio-Data</th> -->
				</tr>
			</thead>
		</table>
		
	
   </div>

     
    </div>
    
   </div>

  </div>
 <?php include_once('../includes/footer-print.php');?> 
  
<script>
$(document).ready(function() {
    $('#customersTable').dataTable({
        //       search: {
        //     return: true,
        // },
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'All'],
        ],
        "processing": true,
        "ajax": "../controller/fetch_data_ins_services.php",

        "columns": [
            {data: 'id'},
            {data: 'emp_id'},
            {data: 'seniority_no'},
            {data: 'name'},
            {data: 'designation'},
            {data: 'division'},
            {data: 'section'},
           
            {data: 'mobile_no'},
            {data: 'email'},
            
        ],
        order: [[ 2, "desc" ]]
    });
});

</script>

