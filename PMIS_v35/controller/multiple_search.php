
<!DOCTYPE html>
<html lang="en">
 <head>
  <title>BCIC PMIS</title>
        
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
  
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Include other required libraries once -->


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
  <!-- for different formatting -->
   <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
   <!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>

  

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
.table-filter-container {
    text-align: right;
}

</style>
     <link rel="icon" type="image/gif/png" href="image/bcic_logo.png">
  
 </head>
 <body>
<?php

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

  <div class="container-fluid box border border-rounded shadow mt-2">

   <h3 align="center" class="text-center text-muted">   
    BCIC PMIS [--Display Only In Services Employees & Officers--] 
    <a href="../dashboard.php" class="btn btn-primary float-end">Previous Page</a>
    
   </h3>
   <p class="text-center"><b><?php echo "Total No. of Officers In Service: " .$row['total_in_services']; } ?></b></p>
   <hr>
   <div class="row">


    <div class="col-md-12">

     <table border="0" cellspacing="5" cellpadding="5">
        <tbody><tr>
            <td>Minimum date:</td>
            <td>
                <!-- <input class="form-control" type="text" id="min" name="min"> -->
                <input class="form-control" type="text" id="min" name="min">
                <span id="min-close" class="datetimepicker-icon datetimepicker-icon-calendar"></span>


            </td>
            <td>Maximum date:</td>
            <td>
                <!-- <input class="form-control" type="text" id="max" name="max"> -->
                <input class="form-control" type="text" id="max" name="max">
                <span id="max-close" class="datetimepicker-icon datetimepicker-icon-calendar"></span>

            </td>
        </tr>
        <tr>
            
        </tr>
    </tbody></table>


	   <div class="table-responsive">

        <table id="customersTable" class="display" width="100%" cellspacing="0">
			<thead>
				<tr>
          <th width=""> SNR No</th>
      <th width="">Emp ID</th>
       
       <th width="">Name</th>
       <th width="">Designation</th>
       <th width="">Place of Posting</th>
       <th width="">DOB</th>
       <!-- <th width="">Status</th> -->
       <th width="">DOJ</th>
       <th width="">Promotion Due</th>
       <th width="">PRL Date</th>
 <!--       <th width="">View</th>
       <th width="">Edit</th>
       <th width="">Delete</th>
      <th width="">Bio-Data</th> 
				</tr> -->
			</thead>
      <tbody></tbody>
      <tfoot>
            <tr>
                <th>ID</th>
                <th>Emp ID</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
		</table>
		
	
   </div>

     
    </div>
    
   </div>

  </div>
 
  
<script>

 // var minDate, maxDate;
 
// Custom filtering function which will search data in column four between two values
// $.fn.dataTable.ext.search.push(
//     function( settings, data, dataIndex ) {
//         // Create date inputs
//         minDate = new DateTime($('#min'), {
//             // format: 'MM-DD-YYYY' 'MMMM Do YYYY'
//             format: 'YYYY-MM-DD'
//         });
//         maxDate = new DateTime($('#max'), {
//             // format: 'MM-DD-YYYY'
//             format: 'YYYY-MM-DD'
//         });

//         var min = minDate.val();
//         var max = maxDate.val();
//         var date = new Date( data[8] );
 
//         if (
//             ( min === null && max === null ) ||
//             ( min === null && date <= max ) ||
//             ( min <= date   && max === null ) ||
//             ( min <= date   && date <= max )
//         ) {
//             return true;
//         }
//         return false;
//     }
// );

$(document).ready(function() {
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
            // 'pageLength'
            'pageLength',
            'colvis',
            {

            text: 'Reset',
               class:'btn btn-warning',
               header: true,
               action: function ( e, dt, node, config ) {
           
           
                   $("input[type='text']").each(function () { 
                       $(this).val(''); 
                   })
           
                   dt.columns().every( function () {
                       var column = this;
                       column
                               .search( '' )
                               .draw();
                   } );
           
                   // dt.search('').draw();
                   dt.search('');
           
               }
             },



        ],
        lengthMenu: [
            [ 5, 10, 25, 50, -1],
            [ 5, 10, 25, 50, 'All'],
        ],
        //  buttons: [
        //     'pageLength'
        // ],
        "processing": true,
        responsive: true,
        colReorder: true,
        "ajax": "fetch_data_for_search.php",
        "columns": [
            // {data: 'id'},
            {data: 'seniority_no'},
            {data: 'emp_id'},
            
            {data: 'name'},
            {data: 'designation'},
            {data: 'place_of_posting'},
            {data: 'dob'},
            {data: 'doj'},
            {data: 'next_promo_date'},
            
            {data: 'prl'},
            // {data: 'email'}
            
        ],
        order: [[ 0, "desc" ]],

         initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
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

    //  // Create date inputs
    // minDate = new DateTime($('#min'), {
    //     // format: 'MMMM Do YYYY'
    //     format: 'YYYY-MM-DD'
    // });
    // maxDate = new DateTime($('#max'), {
    //     // format: 'MMMM Do YYYY'
    //     format: 'YYYY-MM-DD'
    // });
 
    // // DataTables initialisation
    // // var table = $('#example').DataTable();
 
    // // Refilter the table
    // $('#min, #max').on('change', function () {
    //     // table.draw();

    //     $('#customersTable').DataTable().draw();
    // });


// $(document).ready(function() {
    // Initialize date picker
    var minDate = new DateTime($('#min'), {
        format: 'YYYY-MM-DD',
        onSelect: function() {
            $('#min-close').click(); // Click the close button to hide the date picker
        }
    });

    var maxDate = new DateTime($('#max'), {
        format: 'YYYY-MM-DD',
        onSelect: function() {
            $('#max-close').click(); // Click the close button to hide the date picker
        }
    });

    // Initialize DataTable
    // var customersTable = $('#customersTable').DataTable({
    //     // DataTable options
    // });

    // Filter date range on change
    $('#min, #max').on('change', function() {
        customersTable.draw();
    });
// });

        $('#reset').on('click', function () {
            $('#min').val('');
            $('#max').val('');
            table.column($('#dateColumnSelector').val()).search('').draw();
        });



});

    </script>
 </body>
</html>
