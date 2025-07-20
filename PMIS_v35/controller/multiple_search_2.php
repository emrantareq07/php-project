<!doctype html>
<html>
<head>
    <title>Date range search in DataTable with jQuery AJAX and PHP</title>

    <!-- Datatable CSS -->
    <link href='../DataTables/datatables.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="../js/jquery-ui.min.css">

    <!-- jQuery Library -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>

    <!-- Datatable JS -->
    <script src="../DataTables/datatables.min.js"></script>
</head>
<body>
    <div>
        <!-- Date Filter -->
        <table>
            <tr>
                <td>
                    <input type='text' readonly id='search_fromdate' class="datepicker" placeholder='From date'>
                </td>
                <td>
                    <input type='text' readonly id='search_todate' class="datepicker" placeholder='To date'>
                </td>
                <td>
                    <input type='button' id="btn_search" value="Search">
                </td>
            </tr>
        </table>

        <!-- Table -->
        <table id='empTable' class='display dataTable'>
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
        </table>
    </div>

    <script>
    $(document).ready(function(){

        // Datepicker
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true
        });

        // DataTable
        var dataTable = $('#empTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true, // Set false to remove default Search Control
            ajax: {
                url: 'ajaxfile.php',
                type: 'POST',
                data: function(data) {
                    // Read values
                    data.searchByFromdate = $('#search_fromdate').val();
                    data.searchByTodate = $('#search_todate').val();
                }
            },
            columns: [
                { data: 'id' },
                { data: 'emp_id' },
                { data: 'name' },
                { data: 'designation' },
                { data: 'place_of_posting' },
                { data: 'dob' },
                { data: 'doj' },
                { data: 'next_promo_date' },
                { data: 'prl' }
            ],
            order: [[0, 'desc']],
        });

        // Search button
        $('#btn_search').click(function(){
            dataTable.draw();
        });
    });
    </script>
</body>
</html>
