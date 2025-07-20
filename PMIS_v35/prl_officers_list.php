
<!DOCTYPE html>
<html lang="en">
 <head>
 <title>BCIC PMIS</title>
    
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  	 
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
 
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>  

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  	  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
	  
 <!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
   -->

   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

  <style type="text/css">
    body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    
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
<link rel="icon" type="image/gif/png" href="image/bcic_logo.png">


</head>
<body>
<?php

// db settings
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pmis_db';

// db connection
$con = mysqli_connect($hostname, $username, $password, $database) or die("Error " . mysqli_error($con));

// fetch records
$sql = "SELECT count(*) prl_in_services from users where status = 'PRL'";
// $sql = "select * from users GROUP BY status = 'In Service' ORDER BY `seniority_no` DESC";
$result = mysqli_query($con, $sql);


  // if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {
    // $array[] = $row;
  // $row=
// }

?>

  <div class="container-fluid box border border-rounded shadow mt-3">

   <h3 align="center" class="text-center text-muted">   
    BCIC PMIS [--Display Only in PRL Employees & Officers--] 
    <a href="view_users.php" class="btn btn-primary float-end">Previous Page</a>
    
   </h3>
   <p class="text-center"><b><?php echo "Total No. of Officers In PRL: " .$row['prl_in_services']; } ?></b></p>
   <br />
   <div class="row">
    
    <div class="col-md-12">
	   <div class="table-responsive">
		<table id="user_data" class="display" style="width:100%">
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
 
<!-- <script type="text/javascript">
$(document).ready(function(){
 
 var dataTable = $('#user_data').DataTable({
  lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'All'],
        ],

  "processing":true,
  "serverSide":true,
  "order":[],
  // "scrollY": '50vh',
  //       "scrollCollapse": true,
  //       "paging": false,
  "ajax":{
   url:"fetch_data_prl_3.php",
   type:"POST"
  },
  "columnDefs":[
   {
    "targets":[0, 3, 4],
    "orderable":false,
   },
  ],

 });

    } );
  </script> --> 
  

 </body>
</html>
  

  
<script type="text/javascript">
      $(document).ready(function() {
        $('#user_data').dataTable({
            "processing": true,
            "ajax": "fetch_data_prl_3.php",
            "columns": [
            {data: 'id'},
            {data: 'emp_id'},
            {data: 'seniority_no'},
            {data: 'name'},
            {data: 'designation'},
            {data: 'division'},
            {data: 'section'},
            // {data: 'status'},
            
            {data: 'mobile_no'},
            {data: 'email'}
            ],
             order: [[ 2, "desc" ]],
               lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'All'],
        ],
        });
    });

// $(document).ready(function() {
//         $('#user_data').DataTable({
//             'processing': true,
//             'serverSide': true,
//             'serverMethod': 'post',
//             'ajax': {
//                 'url':'fetch_data_prl_3.php',
//                 'type':'POST'
//             },
//             'columns': [
//             {data: 'id'},
//             {data: 'emp_id'},
//             {data: 'seniority_no'},
//             {data: 'name'},
//             // {data: 'designation'},
//             // {data: 'division'},
//             // {data: 'section'},
//             // {data: 'status'},
            
//             {data: 'mobile_no'},
//             {data: 'email'}
//             ]
//        });

//     } );

// $(document).ready(function() {
//     $('#example').dataTable({
        //       search: {
        //     return: true,
        // },
        // lengthMenu: [
        //     [5, 10, 25, 50, -1],
        //     [5, 10, 25, 50, 'All'],
        // ],
        // "processing": true,
        // "ajax": "fetch_data_prl_2.php",
        // "columns": [
        //     {data: 'id'},
        //     {data: 'emp_id'},
        //     {data: 'seniority_no'},
        //     {data: 'name'},
            // {data: 'designation'},
            // {data: 'division'},
            // {data: 'section'},
            // {data: 'status'},
            
//             {data: 'mobile_no'},
//             {data: 'email'}
            
//         ]
//     });
// });

</script>