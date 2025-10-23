<?php
session_name('blrr');
session_start();
if (!isset($_SESSION['username'])) { 
    header("Location: ../index.php"); 
    exit(); 
}
require_once("config.php");
include_once '../db/database.php';

// Bangla/English number conversion
function englishToBanglaNumber($number) {
    $eng = ['0','1','2','3','4','5','6','7','8','9'];
    $bn  = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace($eng, $bn, $number);
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>ফাইল প্রাপ্তি রেজিস্টার</title>

<!-- Bootstrap & DataTables CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

body {
    font-family: 'Noto Sans Bengali', sans-serif;
}
    /* Print Styles */
    @media print {
      .no-print, .btn, .modal, .dataTables_length, .dataTables_filter, 
      .dataTables_paginate, #edit_btn, #view_btn, #action_col {
        display: none !important;
      }
      
      .table-bordered {
        border: 1px solid #000 !important;
      }
      
      .table th, .table td {
        border-color: #000 !important;
        background-color: white !important;
        color: black !important;
      }
    }

    /* Font Definitions */
    @font-face {
      font-family: 'Nikosh';
      src: url('fonts/Nikosh.ttf') format('truetype'),
           url('fonts/Nikosh.woff') format('woff'),
           url('fonts/Nikosh.woff2') format('woff2');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    /* Base Typography */
    * {
      font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
    }

    /* Color Variables */
    :root {
      --custom-purple: #7e11bd;
      --custom-purple-light: #9c3dd4;
      --custom-purple-dark: #5c0d8a;
      --text-light: #ffffff;
      --text-dark: #333333;
    }

    /* Custom Color Classes */
    .bg-custom-purple {
      background-color: var(--custom-purple) !important;
    }
    
    .bg-custom-purple-light {
      background-color: var(--custom-purple-light) !important;
    }
    
    .bg-custom-purple-dark {
      background-color: var(--custom-purple-dark) !important;
    }

    .text-custom-purple {
      color: var(--custom-purple) !important;
    }

    .border-custom-purple {
      border-color: var(--custom-purple) !important;
    }

    .btn-custom-purple {
      background-color: var(--custom-purple);
      border-color: var(--custom-purple);
      color: white;
    }

    .btn-custom-purple:hover {
      background-color: var(--custom-purple-dark);
      border-color: var(--custom-purple-dark);
      color: white;
    }

    /* Table Styles */
    .table-purple thead {
      background-color: var(--custom-purple);
      color: var(--text-light);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(126, 17, 189, 0.1);
    }

    /* Logo Container */
    .imgcontainer {
      text-align: center;
/*      margin: 10px 0 15px 0;*/
       margin: 3px 0 5px 0;
      position: relative;
    }
    
    img.avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--custom-purple);
    }

    /* Navigation Enhancements */
    .navbar-custom {
      background: linear-gradient(135deg, var(--custom-purple) 0%, var(--custom-purple-dark) 100%);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);

      min-height: 50px;
      padding-top: 0.15rem;
      padding-bottom: 0.15rem;
    }

    /* Card Enhancements */
    .card-custom {
      border: 1px solid var(--custom-purple);
      border-radius: 10px;
      transition: transform 0.2s ease-in-out;
    }

    .card-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(126, 17, 189, 0.2);
    }

    /* Loading Spinner */
    .loading-spinner {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
      .imgcontainer img.avatar {
        width: 60px;
        height: 60px;
      }
      
      .table-responsive {
        font-size: 0.875rem;
      }
      
      .btn-group-vertical {
        width: 100%;
      }
    }

    /* Animation Classes */
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Badge Customization */
    .badge-custom {
      font-size: 0.75em;
      padding: 0.35em 0.65em;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--custom-purple);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--custom-purple-dark);
    }

    /* Modal Enhancements */
    .modal-header-custom {
      background: linear-gradient(135deg, var(--custom-purple) 0%, var(--custom-purple-dark) 100%);
      color: white;
      border-bottom: none;
    }

    /* Form Control Enhancements */
    .form-control:focus {
      border-color: var(--custom-purple);
      box-shadow: 0 0 0 0.2rem rgba(126, 17, 189, 0.25);
    }

    /* Alert Customization */
    .alert-custom {
      border-left: 4px solid var(--custom-purple);
    }

    /* Footer Styles */
    .footer-custom {
      background-color: var(--custom-purple);
      color: white;
      padding: 1rem 0;
      margin-top: 2rem;
    }

    /* Modal Height Fix */
    .modal-dialog-scrollable .modal-content {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto;
        flex: 1;
    }

    .modal-dialog-scrollable .modal-footer {
        flex-shrink: 0;
    }

    .custom-alert {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  
  <!-- Loading Spinner -->
  <div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border text-custom-purple" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Navigation Header -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
      <div class="imgcontainer">
        <img src="images/bcic_logo.jpg" alt="BCIC Logo" class="avatar" >
      </div>
      
      <a class="navbar-brand fw-bold" href="dashboard.php">
        বিসিআইসি পত্র প্রাপ্তি রেজিস্টার
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="nav-link text-light"><small>Username : 
              <i class="fas fa-user me-1"></i>
              <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></small>
            </span>
          </li>
          <li class="nav-item">
            <span class="nav-link text-light"><small>Office : 
              <i class="fas fa-building me-1"></i>
              <?php echo htmlspecialchars($_SESSION['office'] ?? 'Office'); ?></small>
            </span>
          </li>
            <li class="nav-item">
            <span class="nav-link text-light"><small>Logged As a : [--<?php echo $user_type ?? 'user'; ?>--]</small></span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="logout.php">
              <i class="fas fa-sign-out-alt me-1"></i>লগআউট
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- System Status Bar -->
  <div class="bg-light border-bottom py-2">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-4">
          <small class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i>
            <?php echo date('l, d F Y'); ?>
          </small>
        </div>
       <div class="col-md-4 text-end">
          <small class="text-muted">
            <small><i class="fa fa-copyright"></i> <?php echo date("Y");?> BCIC. [--Design & Developed by ICT Division, BCIC.--]</small>
          </small>
        </div>
        <div class="col-md-4 text-end">
          <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            <span id="liveClock"><?php echo date('h:i:s A'); ?></span>
          </small>
        </div>
      </div>
    </div>
  </div>

<!-- Main Content -->
<main class="container-fluid flex-grow-1 fade-in">
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="fw-bold">📂 ফাইল প্রাপ্তি রেজিস্টার</h4>
        <span class="float-end">
         <a href="dashboard.php" class="btn btn-outline-success " ><i class="fa fa-home" style="font-size:16px;color:red"></i>  Home</a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> নতুন এন্ট্রি</button>
        <a href="file_search_new.php?table_name=<?= $_SESSION['table_name'] ?>&val=987" class="btn btn-outline-primary">
    <i class="fa fa-search" style="font-size:16px"></i> 
     Search</a></span>
    </div>    


    <table id="fileTable" class="table table-striped table-bordered w-100">
        <thead class="table-dark text-center">
            <tr>
                <th>ক্রমিক নং</th>
                <th>এন্ট্রি তারিখ</th>
                <th>উপস্থাপনকারীর বিভাগ</th>
                <th>ডকেট নং</th>
                <th>স্বাক্ষরের তারিখ</th>
                <th>বিষয়</th>
                <th>গন্তব্য</th>
                <th>মন্তব্য</th>
                <th>ক্রিয়া</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable ">
        <form id="fileForm" class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold">ফাইল তথ্য সংযোজন / সম্পাদনা</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <div class="row g-2">
                    <div class="col-md-4"><label>এন্ট্রি তারিখ:</label><input type="date" name="entry_date" id="entry_date" class="form-control" required></div>
                    <div class="col-md-4">
                        <label>উপস্থাপনকারীর বিভাগ:</label>
                        <select class="form-select" id="recipient" name="recipient" required>
                            <option selected disabled value="">--Select--</option>
                            <?php
                            $sql = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<option value='".$row['division_bn']."'>".$row['division_bn']."</option>";
                            }
                            ?>   
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>ডকেট নং:</label>
                        <input type="text" class="form-control bg-light" id="d_number" name="d_number" value="" readonly>
                    </div>
                    <div class="col-md-4"><label>স্বাক্ষরের তারিখ:</label><input type="date" name="send_date" id="send_date" class="form-control" required></div>
                    <div class="col-md-8"><label>বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু:</label><input type="text" name="subject" id="subject" class="form-control" required></div>
                    <div class="col-md-6">
                        <label>গন্তব্য (একাধিক নির্বাচন করুন):</label>
                        <select name="destination_dropfile[]" id="destination_dropfile" class="form-select chosen-select" multiple required>
                             <?php
                            $sql0 = ($office_title == "chairman") 
                                ? "SELECT division_bn FROM division"
                                : "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                            $result0 = mysqli_query($conn, $sql0);
                            while ($row0 = mysqli_fetch_array($result0)) {
                                echo "<option value='" . $row0['division_bn'] . "'>" . $row0['division_bn'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6"><label>মন্তব্য:</label><input type="text" name="comments" id="comments" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="submit" class="btn btn-success">সংরক্ষণ</button>
            </div>
        </form>
    </div>
</div>
</main>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
$(function(){
    $('.chosen-select').chosen({width:"100%"});

    // DataTable
    let table = $('#fileTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: 'file_action.php?action=fetch', type: 'POST' },
        columns: [
            { data: null, render: (data, type, row, meta)=> meta.row + meta.settings._iDisplayStart + 1 },
            { data: 'entry_date', "render": function (data) {
                return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            }
            },
            { data: 'recipient' },
            { data: 'd_number',  },
            { data: 'send_date', "render": function (data) {
                return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            } },
            { data: 'subject' },
            { data: 'destination_dropfile' },
            { data: 'comments' },
            { data: 'id', render: function(id){
                return `<button class="btn btn-sm btn-primary editBtn" data-id="${id}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delBtn" data-id="${id}"><i class="fa fa-trash"></i></button>`;
            }}
        ],
        dom: "<'row mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            { extend: 'excelHtml5', className: 'btn btn-success btn-sm', text: '<i class="fa fa-file-excel"></i> Excel' },
            { extend: 'pdfHtml5', className: 'btn btn-danger btn-sm', text: '<i class="fa fa-file-pdf"></i> PDF' },
            { extend: 'print', className: 'btn btn-primary btn-sm', text: '<i class="fa fa-print"></i> Print' }
        ],
        language: {
            processing: "লোড হচ্ছে...",
            search: "অনুসন্ধান:",
            lengthMenu: "প্রতি পৃষ্ঠায় _MENU_ টি",
            info: "_TOTAL_ টি রেকর্ডের মধ্যে _START_ থেকে _END_ দেখানো হচ্ছে",
            infoEmpty: "কোনো তথ্য নেই",
            infoFiltered: "(মোট _MAX_ রেকর্ড থেকে ফিল্টার করা)",
            paginate: { first: "প্রথম", last: "শেষ", next: "পরবর্তী", previous: "পূর্ববর্তী" }
        }
    });

    // Auto-update d_number on modal open
    // $('#addModal').on('show.bs.modal', function () {
    //     $.getJSON('file_action.php?action=next_d_number', function(res){
    //         if(res.status === 200){
    //             $('#d_number').val(res.next_d_number);
    //         }
    //     });
    // });

    // Auto-update d_number only for new entry
    $('#addModal').on('show.bs.modal', function () {
        let id = $('#id').val(); // check if id exists
        if(!id){ // only if new entry
            $.getJSON('file_action.php?action=next_d_number', function(res){
                if(res.status === 200){
                    $('#d_number').val(res.next_d_number);
                }
            });
        }
    });


    // Save (Add/Update)
    $('#fileForm').on('submit', function(e){
        e.preventDefault();
        let d_number_eng = $('#d_number').val().replace(/[০-৯]/g, function(match){
            return ['0','1','2','3','4','5','6','7','8','9'][['০','১','২','৩','৪','৫','৬','৭','৮','৯'].indexOf(match)];
        });
        $('#d_number').val(d_number_eng);

        $.post('file_action.php?action=save', $(this).serialize(), function(res){
            alert(res.message);
            if(res.status==200){
                $('#addModal').modal('hide');
                $('#addModal').on('hidden.bs.modal', function () {
                    $('#fileForm')[0].reset();
                    $('.chosen-select').val([]).trigger("chosen:updated");
                    table.ajax.reload(null, false);
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
            }
        }, 'json');
    });

    // Edit
    $('#fileTable').on('click','.editBtn', function(){
        let id = $(this).data('id');
        $.getJSON('file_action.php?action=get&id='+id, function(data){
            $('#id').val(data.id);
            $('#entry_date').val(data.entry_date);
            $('#recipient').val(data.recipient);
            $('#d_number').val(data.d_number); // use existing d_number
            $('#send_date').val(data.send_date);
            $('#subject').val(data.subject);
            $('#comments').val(data.comments);

            let selected = data.destination_dropfile.split(',');
            $('#destination_dropfile').val(selected).trigger("chosen:updated");

            $('#addModal').modal('show');
        });
    });


    // Delete
    $('#fileTable').on('click','.delBtn', function(){
        if(!confirm('আপনি কি নিশ্চিত?')) return;
        let id=$(this).data('id');
        $.post('file_action.php?action=delete', {id:id}, function(res){
            alert(res.message);
            if(res.status==200) table.ajax.reload(null,false);
        },'json');
    });

    // Reset modal
    $('#addModal').on('hidden.bs.modal', function () {
        $('#fileForm')[0].reset();
        $('#id').val('');
        $('.chosen-select').val([]).trigger("chosen:updated");
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right','');
    });
});
</script>
</body>
</html>