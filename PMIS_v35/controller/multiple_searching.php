
<!DOCTYPE html>
<html lang="en">
 <head>
  <title>BCIC PMIS</title>
        
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
   
  <!-- for different formatting -->
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>

 <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
  <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>


  <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.dataTables.min.css" rel="stylesheet">

    <style type="text/css">
    body{
    margin:0;
    padding:0;
    /*background-color:#f1f1f1;*/
   }
   .box{
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
     <link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
  
 </head>
 <body>
<?php

include('../db/db.php');

// $sql = "SELECT count(*) total_in_services from basic_info where job_status = 'In Service' ORDER BY `seniority_no` DESC ";
$sql = "SELECT count(*) total_in_services from basic_info";
$result = mysqli_query($conn, $sql);
// if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {
    // $array[] = $row;
  // $row=
// }

?>

  <div class="container-fluid box border border-rounded shadow mt-2">

   <h3 align="center" class="text-center text-muted">   
    BCIC PMIS [--MULTI SEARCHING--] 
    <a href="../dashboard.php" class="btn btn-primary float-end">Previous Page</a>
    <a href="../dashboard.php"  class="btn btn-outline-primary btn-sm  float-start" onclick="">Dashboard 
    </a>
    <a href="#" value="ClearFilters" class="btn btn-outline-danger btn-sm  float-start" onclick="ClearFilters()">Clear Filters 
    </a>
   </h3>
   <p class="text-center"><b><?php echo "Total No. of Officer: " .$row['total_in_services']; } ?></b></p>
   <br />
   <div class="row">
    <div class="col-md-12">

	   <div class="table-responsive">

		<table id="customersTable" class="display " width="100%" cellspacing="0">
			<thead>
				<tr>
        <th width="" class="never"> ID</th>
      
       <th width="" class="all">SNR NO</th>
       <th width="" class="all">Emp ID</th>
       <th width="" class="all">Name</th>
       <th width="" class="all">Designation</th>
       <th width="" class="all">Designation Ext.</th>
       <th width="" class="all">Cadre</th>
       <th width="" class="min-phone-l">Sub-Cadre</th>
       <th width="" class="min-phone-l">Place of Posting</th>
       <th width="" class="desktop tablet">Division</th>
       <th width="" class="min-tablet">DOB</th>
       <th width="" class="min-phone-l">DOJ</th>
       <th width="" class="min-phone-l">Promotion Due</th>
       <th width="" class="min-phone-l">PRL Date</th>
       <th width="" class="all">Mobile NO.</th>
       <th width="" class="desktop tablet">Home District</th>
       <th width="" class="desktop tablet">Job Status</th>
       <th width="" class="desktop tablet">Blood Group</th>
       <th width="" class="min-phone-l">Quota</th>

 <!--       <th width="" class="never">View</th>
       <th width="" class="none">Edit</th>
       <th width="">Delete</th>
      <th width="">Bio-Data</th> 
				</tr> -->
			</thead>
      <tbody></tbody>
      <tfoot>
            <tr>
                <th>ID</th>
                <th>SNR NO</th>
                <th>Emp ID</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
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

$(document).ready(function () {
            // Setup - add a text input to each footer cell
            $('#customersTable tfoot th').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text"  class="form-control" placeholder="Search ' + title + '" />');
            });

            // DataTable
            var table = $('#customersTable').DataTable({

         dom: 'Bfrtip',
        // buttons: [
        //     'copyHtml5',
        //     'excelHtml5',
        //     'csvHtml5',
        //     'pdfHtml5',
        //     'print',
        //     'pageLength',
        //     'colvis'

        // ],

            buttons: [
            'copyHtml5',
            'excelHtml5',

            'csvHtml5',
             {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                messageTop: 'ICT Division',
                exportOptions: {
                columns: ':visible'
                },
                title: 'Custom Page Title'
            },
            //'pdfHtml5',
            // 'print',
            {
                extend: 'print',
                exportOptions: {
                columns: ':visible'
                }
            },
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

             {
                extend: 'colvisGroup',
                text: 'District',
                show: [ 1, 2, 3, 4, 7, 8, 14, 15 ],
                hide: [ 0, 5, 6, 9, 10, 11, 12, 13, 16, 17, 18 ]
            },
            {
                extend: 'colvisGroup',
                text: 'Blood Group',
                show: [ 1, 2, 3, 4, 7, 8, 14, 15, 16 ],
                hide: [ 0, 5, 6, 9, 10, 11, 12, 13, 17, 18 ]
            },
            {
                extend: 'colvisGroup',
                text: 'Show all',
                show: ':hidden'
            }
        ],
        columnDefs: [ {
            targets: -1,
            visible: false
        } ],

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
        // columnDefs: [       //column priority set
        //     { responsivePriority: 1, targets: 0 },
        //     { responsivePriority: 10001, targets: 4 },
        //     { responsivePriority: 3, targets: 3 },
        //     { responsivePriority: 2, targets: -2 }
        // ],
        "ajax": "fetch_data_for_search.php",
        "columns": [
            {data: 'id',className: "never"},
            {data: 'seniority_no',className: "all"},
            {data: 'emp_id',className: "all"},
             // {data: 'cadrewise_snr_no',className: "all"},
            {data: 'name',className: "all"},
            {data: 'designation',className: "all"},
            {data: 'sub_cadre_header',className: "all"},
            {data: 'cadre',className: "all"},
            {data: 'sub_cadre'},
            {data: 'place_of_posting',className: "min-phone-l"},
            {data: 'division', className: "all"},
            {data: 'dob',className: "min-tablet"},
            {data: 'doj',className: "min-phone-l"},
            {data: 'next_promo_date', className: "min-phone-l"},
            {data: 'prl'},
            {data: 'mobile_no', className: "desktop"},
            {data: 'home_dist',className: "all"},
            {data: 'job_status',className: "desktop"},
            {data: 'blood_group',className: "desktop"},
            {data: 'quota',className: "all"}
            
        ],
        order: [[ 1, "asc" ]],

//         buttons: [
//    {
//      text: 'Reset',
//      className:'btn btn-warning',
//      header: true,
//      action: function ( e, dt, node, config ) {
 
 
//          $("input[type='text']").each(function () { 
//              $(this).val(''); 
//          })
 
//          dt.columns().every( function () {
//              var column = this;
//              column
//                      .search( '' )
//                      .draw();
//          } );
 
//          // dt.search('').draw();
//          dt.search('');
 
//      }
//    }
// ],

            // 'pageLength': 25,    // where you choose the default number of row displayed
                "initComplete": function () {
                    // Apply the search
                    this.api().columns().every( function () {     // If you want to only show certain columns you can use a array, see below : 
                    // this.api().columns([0]).every(function () {
                        var that = this;

                        $('#customersTable tfoot tr').appendTo('#customersTable thead');   // To displays the search boxs at the top instead to the bottom of the table
                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });


                    // -------------- here we add dropdown selectors filters to specified columns  --------------
                    this.api().columns().every( function () {
                    // this.api().columns([1]).every(function () {
                        var column = this;
                        var select = $('<select class="form-select" id="mySelect"><option  value="">All</option></select>')
                        // var select = $('<select class="form-control"><option  value="">All</option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    });


                    // -------------- here we add dropdown selectors specific for preview files  --------------
                    // this.api().columns([2]).every(function () {
                    //     var column = this;
                    //     var select = $('<select class="form-control"><option  value=""></option></select>')
                    //         .appendTo($(column.footer()).empty())
                    //         .on('change', function () {
                    //             var val = $.fn.dataTable.util.escapeRegex(
                    //                 $(this).val()
                    //             );

                    //             column
                    //                 .search(this.value)
                    //                 .draw();
                    //         });

                        // {
                        //     select.append('<option value="jpg">jpg</option>');
                        //     select.append('<option value="png">png</option>');
                        //     select.append('<option value="gif">gif</option>');
                        //     select.append('<option value="mp4">mp4</option>');
                        // }
                    // });

                    // -------------- here we add checkbox state   --------------

// this.api().columns([3]).every(function () {
//                         var column = this;
//                         var select = $('<input type="checkbox" id="chk">')
//                             .appendTo($(column.footer()).empty())
//                             .on('change', function () {
//                                 var ischecked = $(this).is(':checked');
//                                 if (ischecked) {
//                                     // If selected records should be displayed
//                                     $.fn.dataTable.ext.search.pop();
//                                     $.fn.dataTable.ext.search.push(
//                                         function (settings, data, dataIndex) {
//                                             var row = table.row(dataIndex).node();
//                                             var checked = $('#chk_' + dataIndex).prop('checked');
//                                             var currentCheckChecked = $(row).find('input').prop('checked');
//                                             if (currentCheckChecked ) { 
//                                                 return true;
//                                             }

//                                             return false;
//                                         }
//                                     );

//                                     table.draw();

//                                 } else {
//                                     $.fn.dataTable.ext.search.pop();
//                                     $.fn.dataTable.ext.search.push(
//                                         function (settings, data, dataIndex) {
//                                             var row = table.row(dataIndex).node();
//                                             var checked = $('#chk_' + dataIndex).prop('checked');
//                                             var currentCheckChecked = $(row).find('input').prop('checked');
//                                             if (currentCheckChecked ) {  
//                                                 return true;
//                                             }

//                                             return true;
//                                         }
//                                     );

//                                     table.draw();
//                                 }
//                             });


//                     });





                 },
                // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]   //Page length options , cf. https://datatables.net/examples/advanced_init/length_menu
            });

        });


function ClearFilters() {
  var table = $('#customersTable').DataTable();
  table
  .search( '' )
  .columns().search( '' )
  .draw();
  //  table
  // search( '' )
  // .draw();
// $("#mySelect").val( '' ).change();
$('#customersTable tfoot tr').val('').change();
//$("#mySelect").val([]).change();
// $("#mySelect").val([]);
 // table
 //  .draw();
/*
    
  $("#myInputFilter").val([]);
  $('tfoot input').val( '' );

  $('#PluginsTable tfoot input').val('');

  $('tfoot input').val([]);
  $('#PluginsTable tfoot input').val([]);
  
*/


// To Do : clear each search label
 }
 
</script>
<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script><link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script> -->

 </body>
</html>
