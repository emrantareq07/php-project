<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DataTables Date Range Filter</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date">
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date">
    <table id="dataTable">
        <!-- Your table content will be loaded dynamically here -->
    </table>

    <script>
        $(document).ready(function () {
            var dataTable = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "server.php",  // URL to the server-side processing script
                "columns": [
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
                "columnDefs": [
                    {
                        "targets": 3, // Replace with the actual column index
                        "render": function (data, type, row) {
                            return moment(data).format('YYYY-MM-DD'); // Format the date as needed
                        }
                    }
                ]
            });

            $('#start_date, #end_date').on('input', function () {
                // Get the values of the start and end date inputs
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                // Perform the date range filtering
                dataTable.draw();
            });
        });
    </script>
</body>
</html>
