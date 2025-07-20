<!DOCTYPE html>
<html lang="en">
 <head>
  <title>BCIC PMIS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css">
  <style type="text/css">
    body {
        margin: 0;
        padding: 0;
        background-color: #f1f1f1;
    }
    .box {
        width: auto;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 10px 10px;
        box-shadow: 5px 10px 18px #888888;
    }
    .table-filter-container {
        text-align: right;
    }
  </style>
  <link rel="icon" type="image/gif/png" href="image/bcic_logo.png">
 </head>
 <body>
    <div class="container-fluid box border border-rounded shadow mt-2">
        <h3 align="center" class="text-center text-muted">
            BCIC PMIS [--Display Only In Services Employees & Officers--]
            <a href="../dashboard.php" class="btn btn-primary float-end">Previous Page</a>
        </h3>
        <?php
        include('../db/db.php');
        $sql = "SELECT count(*) total_in_services from basic_info where job_status = 'In Service'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>
        <p class="text-center"><b>Total No. of Officers In Service: <?php echo $row['total_in_services']; ?></b></p>
        <br />
        <div class="row">
            <div class="col-md-12">
                <table border="0" cellspacing="5" cellpadding="5">
                    <tbody>
                      <tr>
                            <td>Choose Date Column:</td>
                            <td>
                                <select id="dateColumnSelector">
                                  <option value="5">DOB</option>
                                    <option value="6">DOJ</option> <!-- Use the appropriate index for each date column -->
                                    <option value="7">Next Promotion Due</option>
                                    <option value="8">PRL</option>
                                </select>
                            </td>
                            <td><button id="reset" class="btn btn-outline-warning btn-sm">Reset</button></td>
                        </tr>
                        <tr>
                            <td>Minimum date:</td>
                            <td><input type="text" id="min" name="min"></td>
                        </tr>
                        <tr>
                            <td>Maximum date:</td>
                            <td><input type="text" id="max" name="max"></td>
                        </tr>
                        
                    </tbody>
                </table>
                <div class="table-responsive">
                    <table id="example" class="display" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Place of Posting</th>
                                <th>DOB</th>
                                <th>DOJ</th>
                                <th>Promotion Due</th>
                                <th>PRL Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Place of Posting</th>
                                <th>DOB</th>
                                <th>DOJ</th>
                                <th>Promotion Due</th>
                                <th>PRL Date</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>
    <script>
    $(document).ready(function () {
        let table = $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'print',
            ],
            filter: true,
            processing: true,
            // serverSide: true,
            ajax: {
                url: 'fetch_data_for_search.php',
                type: 'POST',
            },

        //     "columnDefs": [{
        //     "targets": [5],
        //     "visible": true,
        //     "searchable": true
        // }],
            columns: [
                { data: 'id' },
                { data: 'emp_id' },
                { data: 'name' },
                { data: 'designation' },
                { data: 'place_of_posting' },
                { data: 'dob' },
                { data: 'doj' },
                { data: 'next_promo_date' },
                { data: 'prl' },
            ],
            order: [[0, 'desc']],
        });

        $('#min, #max, #dateColumnSelector').on('change', function () {
            let minDate = $('#min').val();
            let maxDate = $('#max').val();
            let selectedColumn = $('#dateColumnSelector').val();

            // if (minDate !== '' && maxDate !== '') {
            //     table.column(selectedColumn).search(minDate + '|' + maxDate, true, false).draw();
            // }

        //   if (minDate !== '' && maxDate !== '') {
        //     table.column(selectedColumn).data().filter(function (value, index) {
        //         let date = new Date(value);
        //         return date >= new Date(minDate) && date <= new Date(maxDate);
        //     }).draw();
        // }

        // if (minDate !== '' && maxDate !== '') {
        //     table.column(selectedColumn).between(minDate, maxDate).draw();
        // }

           // if (minDate !== '' && maxDate !== '') {
           //    var dateRange = minDate + ' to ' + maxDate;
           //    table.column(selectedColumn).search(dateRange).draw();

           //  }
           //applyDateRangeFilter(minDate, maxDate, selectedColumn);

             if (minDate !== '' && maxDate !== '') {
                var dateRange = minDate + ' | ' + maxDate;
                table.column(selectedColumn).search(dateRange, true, false).draw();
            }

                  });
         // function applyDateRangeFilter(minDate, maxDate, selectedColumn) {
         //      var dateRange = minDate + ' to ' + maxDate;
         //      table.column(selectedColumn).search(dateRange).draw();
         //  }
          




        $('#reset').on('click', function () {
            $('#min').val('');
            $('#max').val('');
            table.column($('#dateColumnSelector').val()).search('').draw();
        });
    });
    </script>
 </body>
</html>
