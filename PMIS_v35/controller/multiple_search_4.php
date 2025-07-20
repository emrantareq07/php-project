<!DOCTYPE html>
<html>
<head>
    <title>Date Range Filter and Selector DataTable</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

</head>
<body>
    <div>
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date">
        <label for ="end-date">End Date:</label>
        <input type="date" id="end-date">
        
        <label for="date-selector">Date Selector:</label>
        <select id="date-selector">
            <option value="0">Select All</option>
            <option value="5">DOB</option>
            <option value="6">DOJ</option>
            <option value="7">PRL</option>
        </select>
    </div>

    <table id="employee-table" class="display">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#employee-table').DataTable({
                "ajax": {
                    "url": "data.php",
                    "type": "POST",
                    "data": function (d) {
                        d.start_date = $('#start-date').val();
                        d.end_date = $('#end-date').val();
                        d.selected_column = $('#date-selector').val();
                    },
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "id" },
                    { "data": "emp_id" },
                    { "data": "name" },
                    { "data": "designation" },
                    { "data": "place_of_posting" },
                    { "data": "dob" },
                    { "data": "doj" },
                    { "data": "next_promo_date" },
                    { "data": "prl" }

                ],
            });

            $('#start-date, #end-date, #date-selector').on('change', function() {
                // Reload data after the change event
                table.ajax.reload();
            });
        
    </script>
</body>
</html>
