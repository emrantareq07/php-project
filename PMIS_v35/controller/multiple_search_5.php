<!DOCTYPE html>
<html>
<head>
    <title>Data Table with Date Range Filter</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css">
</head>
<body>
    <label for="dateSelector">Select Date Field:</label>
    <select id="dateSelector">
        <option value="dob">Date of Birth</option>
        <option value="doj">Date of Joining</option>
        <option value="prl">Next Promotion Date</option>
    </select>
    <br><br>
    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate">
    <label for="endDate">End Date:</label>
    <input type="date" id="endDate">
    <br><br>
    <table id="employeeTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Emp ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Place of Posting</th>
                <th>Date of Birth</th>
                <th>Date of Joining</th>
                <th>Next Promotion Date</th>
                <th>PRL Date</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
<script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>
    <script>
       <script>
$(document).ready(function () {
    let dataTable = $('#employeeTable').DataTable({
        language: {
            loadingRecords: "Loading data... Please wait...",
            zeroRecords: "No matching records found"
        },
        ajax: {
            url: 'fetch_data.php',
            type: 'POST',
            data: function (d) {
                d.dateField = $('#dateSelector').val();
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
            }
        },
        columns: [
            { data: 'emp_id' },
            { data: 'name' },
            { data: 'designation' },
            { data: 'place_of_posting' },
            { data: 'dob' },
            { data: 'doj' },
            { data: 'next_promotion_date' },
            { data: 'prl' }
        ]
    });

    $('#dateSelector, #startDate, #endDate').on('change', function () {
        dataTable.ajax.reload();
    });
});
</script>

    </script>
</body>
</html>
