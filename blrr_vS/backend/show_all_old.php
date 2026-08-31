<?php
session_name('blrr');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include_once '../db/database_old.php';
include_once 'header.php';

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$user_type = htmlspecialchars($_SESSION['user_type'], ENT_QUOTES, 'UTF-8');
$office = htmlspecialchars($_SESSION['office'], ENT_QUOTES, 'UTF-8');
$office_title = htmlspecialchars($_SESSION['office_title'], ENT_QUOTES, 'UTF-8');
$today_date = date("Y-m-d");

// Validate table name
$allowed_tables = ['rri'];
$table_name = 'rri'; // Hardcoded for this example
if (!in_array($table_name, $allowed_tables)) {
    die("Invalid table name.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLRR</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <link rel="icon" type="image/gif/png" href="../images/bcic_logo.jpg">
</head>
<body>
<div class="container-fluid">
    <div class="table-wrapper border border-muted rounded shadow p-2 my-1">
        <div class="row mb-0">
            <div class="col">
                <h2 class="text-muted"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b></h2>
            <p>
            <span class="text-primary fw-bold mb-0 m-0">Username: [<?php echo $username; ?>]</span><br>
            <span class="text-success fw-bold">Office: [<?php echo $office; ?>]</span><br>
            <span class="text-warning fw-bold">Logged in as: [<?php echo $user_type; ?>]</span>
            </p>
            </div>
            <div class="col-sm-4 text-end">
                <a href="../dashboard.php" class="btn btn-outline-success mb-2">
                    <i class="fa fa-home"></i> Home
                </a>
                <a href="show_all_old_search.php?table_name=<?= $table_name ?>&val=987" class="btn btn-outline-primary mb-2">
                    <i class="fa fa-search"></i> Search
                </a>
                <button type="button" class="btn btn-danger mb-2" id="print_current_date">
                    <i class="fa fa-print"></i> Print
                </button>
                <a href="logout.php" class="btn btn-danger mb-2">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>

        <!-- DataTable -->
        <table class="table table-hover table-striped table-bordered text-center" id="form-tbl">
            <thead class="table-dark">
                <tr>
                    <th>ক্রম</th>
                    <th>পত্র প্রাপ্তি তারিখ</th>
                    <th>প্রাপক</th>
                    <th>ডকেট নং</th>
                    <th>স্মারক নং</th>
                    <th>পাঠানোর তারিখ</th>
                    <th>পত্র প্রেরক</th>
                    <th>বিষয়বস্তু</th>
                    <th>গন্তব্য</th>
                    <th>বিতরণ তারিখ</th>
                    <th>মাধ্যম</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- jQuery and DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- pdfmake for PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    $(document).ready(function () {
        $('#form-tbl').DataTable({
            serverSide: true,
            processing: true,
            ajax: "server_processing.php",
            columns: [
                { data: "id" },
                { data: "entry_date" },
                { data: "to_l" },
                { data: "d_number" },
                { data: "ref_number" },
                { data: "send_date" },
                { data: "from" },
                { data: "subject" },
                { data: "destination" },
                { data: "dis_date" },
                { data: "by" }
            ],
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            language: {
                search: "অনুসন্ধান:",
                lengthMenu: "প্রতি পৃষ্ঠায় _MENU_ টি রেকর্ড দেখান",
                info: "মোট _TOTAL_ রেকর্ডের মধ্যে _START_ থেকে _END_ প্রদর্শিত",
                paginate: {
                    previous: "পূর্ববর্তী",
                    next: "পরবর্তী"
                }
            }
        });
    });
</script>
</body>
</html>
