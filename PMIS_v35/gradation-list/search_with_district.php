
<!DOCTYPE html>
<html lang="en">
 <head>
  <title>BCIC PMIS</title>
        
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
   
  <!-- for different formatting -->
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.13.4/api/fnFilterClear.js"></script>



  
  <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
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
tfoot {
    display: table-header-group;
}

</style>
     <link rel="icon" type="image/gif/png" href="image/bcic_logo.png">
  
 </head>
 <body>
<?php

// db settings
// $hostname = 'localhost';
// $username = 'root';
// $password = '';
// $database = 'pmis_db';

// // db connection
// $con = mysqli_connect($hostname, $username, $password, $database) or die("Error " . mysqli_error($con));
// include('database.php');
include('../db/db.php');
// fetch records
$sql = "SELECT count(*) total_in_services from basic_info where job_status = 'In Service' ORDER BY `seniority_no` DESC ";
// $sql = "SELECT count(*) total_in_services from users where status = 'In Service' ORDER BY id DESC ";
// $sql = "select * from users GROUP BY status = 'In Service' ORDER BY `seniority_no` DESC";
$result = mysqli_query($conn, $sql);


  // if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {
    // $array[] = $row;
  // $row=
// }

?>

  <div class="container-fluid box border border-rounded shadow mt-3">

   <h3 class="text-center text-muted">   
    BCIC PMIS [--District Wise--] 
    <a href="view_users.php" class="btn btn-primary float-end">Previous Page</a>

    
   </h3><span><button id="reset" class="btn btn-outline-warning btn-sm bg-danger text-white float-start">Reset</button></span>
   <p class="text-center"><b><?php echo "Total No. of Officers In Service: " .$row['total_in_services']; } ?></b></p>
   <br />
   <div class="row">
    <!-- <div class="table-filter-container">
      <p id="table-filter" style="display:none">
        Search: 
        <select>
        <option value="">All</option>
        <option>London</option>
        <option>San Francisco</option>
        <option>Engineer</option>
        <option>Developer</option>
        </select>
        </p>
      </div> -->
      

    <div class="col-md-12">


	   <div class="table-responsive">
		<table id="customersTable" class="display" width="100%" cellspacing="0">
			<thead>
				<tr>
          <th width=""> ID</th>
      <th width="">Emp ID</th>
       <!-- <th width="">SNR. No.</th> -->
       <th width="">Name</th>
       <th width="">Designation</th>
       <th width="">Division</th>
       <th width="">Home District</th>
       <!-- <th width="">Status</th> -->
       <th width="">Job Status</th>
       <!--<th width="">Email</th>
      <th width="">View</th>
       <th width="">Edit</th>
       <th width="">Delete</th>
      <th width="">Bio-Data</th> -->
				</tr>
			</thead>
      <tbody></tbody>
      <tfoot>
            <tr>
            <th width=""> ID</th>
          <th width="">Emp ID</th>
           <!-- <th width="">SNR. No.</th> -->
           <th width="">Name</th>
           <th width="">Designation</th>
           <th width="">Division</th>
           <th width="">Home District</th>
           <!-- <th width="">Status</th> -->
           <th width="">Job Status</th>
<!--                 <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              <th></th>
                <th></th> --> 
            </tr>
        </tfoot>
		</table>
		
	
   </div>

     
    </div>
    
   </div>

  </div>
  
  
<script>

//   $(document).ready(function() {
//     $('#customersTable').DataTable( {
//         dom: 'Bfrtip',
//         buttons: [
//             'copyHtml5',
//             'excelHtml5',
//             'csvHtml5',
//             'pdfHtml5'
//         ]
//     } );
// } );
  //
$(document).ready(function() {
    //reset
      $(document).on("click", "#reset", function(e) {
        e.preventDefault();
      // var table = $('#customersTable').DataTable();
       // var val = $.fn.dataTable.util.escapeRegex($(this).val());
 
        // column.search(val ? '^' + val + '$' : '', true, false).draw();
        //  $('#customersTable').DataTable().destroy();
        // // //fetch();

        // $('#reset').on('click', function() {
    // table.search('').columns().search('').draw();

    var table = $('#customersTable').DataTable(); 
    $('#customersTable').val('');
    // table.search('').columns(4).search('').draw();//define which column reset
   table.search('').columns().search('').draw();
 });

     $('#customersTable').dataTable({
        //       search: {
        //     return: true,
        // },
         dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'print',
            'pageLength'

        ],
        lengthMenu: [
            [ 5, 10, 25, 50, -1],
            [ 5, 10, 25, 50, 'All'],
        ],
        //  buttons: [
        //     'pageLength'
        // ],
          responsive: true,
          searching: true,
        "processing": true,
        "ajax": "fetch_data_for_district.php",
        "columns": [
            {data: 'id'},
            {data: 'emp_id'},
            //{data: 'seniority_no'},
            {data: 'name'},
            {data: 'designation'},
            {data: 'division'},
            {data: 'home_dist'},
            {data: 'job_status'},
            
            // {data: 'mobile_no'},
            // {data: 'email'}
            
        ],
        order: [[ 2, "desc" ]],

         initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    var column = this;
                    var select = $('<select class="form-select"><option value="">All</option></select>')
                         //.appendTo($(column.tbody()).empty())
                         .appendTo($(column.footer()).empty())
                        //.appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
 
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
 
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                });
        },


    });
// $('#reset').on('click', function(){
//   var table = $('#customersTable').DataTable();
// // table.search('');
// // table.columns().search('').draw();

//     table.search('').columns().search('').draw();
// });

// });
    // Reset


});



//filter

// $(document).ready(function (){
//     var table = $('#customersTable').DataTable({
//        dom: 'lr<"table-filter-container">tip',
//        initComplete: function(settings){
//           var api = new $.fn.dataTable.Api( settings );
//           $('.table-filter-container', api.table().container()).append(
//              $('#table-filter').detach().show()
//           );
          
//           $('#table-filter select').on('change', function(){
//              table.search(this.value).draw();   
//           });       
//        }
//     });
    
// });

// $(document).ready(function () {
//     $('#customersTable').DataTable({
//         initComplete: function () {
//             this.api()
//                 .columns()
//                 .every(function () {
//                     var column = this;
//                     var select = $('<select><option value=""></option></select>')
//                         .appendTo($(column.footer()).empty())
//                         .on('change', function () {
//                             var val = $.fn.dataTable.util.escapeRegex($(this).val());
 
//                             column.search(val ? '^' + val + '$' : '', true, false).draw();
//                         });
 
//                     column
//                         .data()
//                         .unique()
//                         .sort()
//                         .each(function (d, j) {
//                             select.append('<option value="' + d + '">' + d + '</option>');
//                         });
//                 });
//         },
//     });
// });

</script>
 </body>
</html>
