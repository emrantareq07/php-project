<?php 
session_name('blrr');
session_start();
$username = $_SESSION['username']; 
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
$table_name = $_SESSION['table_name'];
$office_title = $_SESSION['office_title'];

if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
} 

require_once("backend/config.php");
include_once 'db.php';
include_once 'backend/header.php';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));

$result = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_meeting_count FROM $table_name where status='pending'");  
$row11 = mysqli_fetch_array($result);
$upcoming_meeting_count = $row11['upcoming_meeting_count'];
?>

<div class="container-fluid">
    <div class="table-wrapper border border-muted rounded shadow p-2 my-1">  
        <div class="container my-2">  
            <button class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#entryModal">
                <i class="fa fa-plus"></i> Add New Docs
            </button>
            <a href="backend/show_all.php?table_name=<?= $table_name ?>" class="btn btn-outline-success mb-2">
                <i class="fa fa-file-archive-o text-danger"></i> Show All
            </a>
            <?php if ($user_type == 'user' && in_array($office_title,['division','director','chairman'])): ?>
            <a href="backend/incoming_letter.php" class="btn btn-outline-success position-relative mb-2">
                <i class="fa fa-clock-o text-danger"></i> Incoming Letter
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $upcoming_meeting_count; ?>
                </span>
            </a>
            <?php endif; ?>
            <a href="backend/show_all_old.php?table_name=<?= $table_name ?>" class="btn btn-outline-success mb-2">
                <i class="fa fa-file-archive-o text-danger"></i> Show Old Docs
            </a>
            <a href="backend/search_new.php?table_name=<?= $table_name ?>&val=987" class="btn btn-outline-primary mb-2">
                <i class="fa fa-search"></i> Search
            </a>
            <?php if ($user_type == 'sadmin'): ?>
                <a href="backend/manage_user.php?username=<?= $username ?>" class="btn btn-warning mb-2">
                    <i class="fa fa-edit text-dark"></i> Manage User
                </a>
                <a href="backend/manage_user.php?username=<?= $username ?>" class="btn btn-warning mb-2">
                    <i class="fa fa-download text-dark"></i> Download Database
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-danger mb-2" id="print_current_date">
                <i class="fa fa-print"></i> Print
            </button>
            <a href="backend/logout.php" class="btn btn-danger mb-2">
                <i class="fa fa-sign-out"></i> Logout
            </a>

            <table id="friendsTable" class="table table-bordered table-striped">
                <thead class="bg-custom-purple text-light">
                    <tr>
                        <th>ক্রম</th>
                        <?php if($user_type=='user' && $table_name=='chairman'): ?>
                        <th>পত্র প্রাপ্তি তারিখ</th>
                        <?php endif; ?>
                        <th>প্রাপক</th>
                        <th>ডকেট নং</th>
                        <th>স্মারক নং</th>
                        <th>পাঠানোর তারিখ</th>
                        <th>মূল প্রেরক/উৎস</th>
                        <th>ডিভিশন/অফিস</th>
                        <th>বিষয়বস্তু</th>
                        <th>গন্তব্য অফিস</th>
                        <th>বিতরণ তারিখ</th>
                        <th>মাধ্যম</th>
                        <th id="action_col">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- DATA TABLES + EXPORT JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
function enToBn(num){
    return num.toString().replace(/[0-9]/g,d=>'০১২৩৪৫৬৭৮৯'[d]);
}

$(document).ready(function(){
    var tableName = "<?= $table_name ?>";

    var table = $('#friendsTable').DataTable({
        "ajax": "get_data.php?table_name="+tableName,
        "columns": [
            { "data": null, render: (data,type,row,meta)=> enToBn(meta.row+1) },
            <?php if($user_type=='user' && $table_name=='chairman'): ?> 
            { "data": "entry_date", render: d=> d? new Date(d).toLocaleDateString('bn-BD') : '' }, 
            <?php endif; ?>
            { "data": "recipient" },
            { "data": "d_number", render: d=> d? enToBn(d) : '' },
            { "data": "ref_number", render: d=> d? enToBn(d) : '' },
            { "data": "send_date", render: d=> d? new Date(d).toLocaleDateString('bn-BD') : '' },
            { "data": "sender" },
            { "data": "div_dept_office" },
            { "data": "subject" },
            { "data": "destination_drop" },
            { "data": "distribution_date", render: d=> d? new Date(d).toLocaleDateString('bn-BD') : '' },
            { "data": "medium" },
            { "data": null, render: (data,type,row)=> `
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary editBtn" data-id="${row.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger deleteBtn" data-id="${row.id}"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-info viewBtn" data-id="${row.id}"><i class="fas fa-eye"></i></button>
                </div>`
            }
        ],
        "order": [[0,'asc']],
        "dom": 'Bfrtip',
        "buttons":[
            { extend:'pdfHtml5', text:'PDF', className:'btn btn-danger btn-sm', exportOptions:{columns:':not(:last-child)'} },
            { extend:'excelHtml5', text:'Excel', className:'btn btn-success btn-sm', exportOptions:{columns:':not(:last-child)'} },
            { extend:'csvHtml5', text:'CSV', className:'btn btn-primary btn-sm', exportOptions:{columns:':not(:last-child)'} },
            { extend:'print', text:'Print', className:'btn btn-secondary btn-sm', exportOptions:{columns:':not(:last-child)'} }
        ]
    });
});
</script>
