<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Bootstrap DataTable</title>
</head>
<body>

<div class="container mt-5">
    <table id="employeeTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Date of Birth</th>
                <th>Date of Joining</th>
                <th>PRL</th>
                <th>Place of Posting</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table data will be loaded here dynamically -->
        </tbody>
    </table>
</div>

<script>
  $(document).ready(function () {
    $('#employeeTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "getData.php",
            "type": "POST",
            "dataType": "json",
            "error": function (xhr, error, thrown) {
                console.log("Error fetching data:", error);
                console.log("XHR:", xhr);
            }
        },
        "columns": [
            { "data": "emp_id" },
            { "data": "name" },
            { "data": "designation" },
            { "data": "dob" },
            { "data": "doj" },
            { "data": "prl" },
            { "data": "place_of_posting" }
        ],
        "language": {
            "zeroRecords": "No matching records found",
            "emptyTable": "No data available in the table"
        }
    });
});

</script>

</body>
</html>
