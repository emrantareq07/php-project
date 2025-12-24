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

include_once 'header_file.php';
?>

<body class="d-flex flex-column min-vh-100">

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
  <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
        Success message here
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>


  <!-- Loading Spinner -->
  <div class="loading-spinner" id="loadingSpinner" style="display:none">
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
            <span class="nav-link text-light"><small>Logged As a : [--<?php echo $_SESSION['user_type'] ?? 'user'; ?>--]</small></span>
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
    <a href="addfiledashboard.php" class="btn btn-primary">
        <i class="fa fa-arrow-left" ></i> <span>Back</span>
    </a>
        <a href="file_search_new.php" class="btn btn-outline-primary">
    <i class="fa fa-search" style="font-size:16px"></i>
     Search</a></span>
    </div>

    <table id="fileTable" class="table table-striped table-bordered ">
        <thead class="table-dark text-center">
            <tr>
                <th>ক্রমিক নং</th>
                <th>এন্ট্রি তারিখ</th>
                <th>ডকেট নং</th>
                <th>আগত ফাইল পরিচালকের দপ্তর</th>
                <th>ফাইল উপস্থাপনকারী বিভাগ/ শাখা</th>                
                <th>বিষয়</th>
                <th>প্রাপক</th>
                <th>চেয়ারম্যান মহোদয়ের স্বাক্ষরের তারিখ</th>
                <th>গন্তব্য</th>
                <th>মন্তব্য</th>
                <th>একশন</th>
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
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" value="">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label>এন্ট্রি তারিখ:</label>
                      <input type="date" name="entry_date" id="entry_date" class="form-control"
                               value="<?php echo date('Y-m-d'); ?>" required>

                        <span id="entry_date_bn">
                            <?php echo englishToBanglaNumber(date('Y-m-d')); ?>
                        </span>

                        <script>
                          document.addEventListener('DOMContentLoaded', function () {
                            const dateInput = document.getElementById('entry_date');
                            const docketInput = document.getElementById('d_number');
                            dateInput.addEventListener('change', function () {
                              const selectedDate = this.value;
                              if (!selectedDate) return;
                              const year = new Date(selectedDate).getFullYear();
                              // Send year to PHP via AJAX to get next d_number in Bangla
                              $.post('get_d_number_file.php', { year_auto1: year }, function(response) {
                                // If PHP returns Bangla number string
                                docketInput.value = response;
                              });
                            });
                          });
                        </script>
                    </div>

                    <div class="col-md-4">
                        <label>ডকেট নং:</label>
                        <input type="text" class="form-control bg-light" id="d_number" name="d_number" value="" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>আগত ফাইল পরিচালকের দপ্তর:</label>
                        <select class="form-select" id="immediate_sender_office" name="immediate_sender_office" required>
                            <option selected disabled value="">--Select--</option>
                            <?php
                            $sql = "SELECT division_bn FROM division WHERE id IN (1,5,6,7,8,9,10,11,19,24)";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<option value='".htmlspecialchars($row['division_bn'])."'>".htmlspecialchars($row['division_bn'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>উপস্থাপনকারী/ প্রেরিত বিভাগ:</label>
                        <select class="form-select" id="div_dept_office" name="div_dept_office" required>
                            <option selected disabled value="">--Select--</option>
                            <?php
                            $sql = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<option value='".htmlspecialchars($row['division_bn'])."'>".htmlspecialchars($row['division_bn'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>শাখা (লিখুন):</label>
                        <input type="text" class="form-control bg-light" id="section_dept" name="section_dept" value="">
                    </div>

                    <div class="col-md-4"><label> তারিখ:</label><input type="date" name="div_sign_date" id="div_sign_date" class="form-control" value="<?php echo date('Y-m-d');?>"></div>

                    <div class="col-md-12"><label>বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু:</label><input type="text" name="subject" id="subject" class="form-control" required></div>

                    <div class="col-md-6">
                        <label>প্রাপক:</label>
                        <select class="form-select" id="recipient" name="recipient" required>
                            <option selected disabled value="">--Select--</option>
                            <?php
                            $sql = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<option value='".htmlspecialchars($row['division_bn'])."'>".htmlspecialchars($row['division_bn'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6"><label>স্বাক্ষরের তারিখ:</label><input type="date" name="sign_date" id="sign_date" class="form-control" required value="<?php echo date('Y-m-d');?>"></div>

                    <div class="col-md-6">
                        <label>গন্তব্য (একাধিক নির্বাচন করুন):</label>
                        <select name="destination_dropfile[]" id="destination_dropfile" class="form-select chosen-select" multiple required>
                             <?php
                            $sql0 = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                            $result0 = mysqli_query($conn, $sql0);
                            while ($row0 = mysqli_fetch_array($result0)) {
                                echo "<option value='" . htmlspecialchars($row0['division_bn']) . "'>" . htmlspecialchars($row0['division_bn']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6"><label>মন্তব্য:</label><input type="text" name="comments" id="comments" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa fa-cancel"></i> বাতিল</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> সংরক্ষণ</button>
            </div>
        </form>
    </div>
</div>


    <!-- Rest of your code remains the same -->
    <!-- View Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-custom-purple text-white">
                    <h5 class="modal-title">এন্ট্রি বিস্তারিত</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                </div>
            </div>
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

//     function showToast(message, type='success') {
//     const toastEl = document.getElementById('liveToast');
//     const toastBody = document.getElementById('toastMessage');

//     toastBody.innerText = message;

//     // Change background color based on type
//     toastEl.className = `toast align-items-center text-bg-${type} border-0`;

//     const toast = new bootstrap.Toast(toastEl);
//     toast.show();
// }



$(function(){
    $('.chosen-select').chosen({width:"100%"});

    // DataTable
    let table = $('#fileTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: 'file_action.php?action=fetch_all', type: 'POST' },
        columns: [
            {
              data: null,
              render: function (data, type, row, meta) {
                const rowNum = meta.row + meta.settings._iDisplayStart + 1;
                return rowNum.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]);
              }
            },
            { data: 'entry_date', render: function (data) {
                return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            }},
            { data: 'd_number' },
            { data: 'immediate_sender_office' },
            // {
            //     data: null,
            //     render: function (data, type, row) {
            //         return row.div_dept_office + ' - ' + row.section_dept;
            //     }
            // },
            {
                data: null,
                render: function (data, type, row) {
                    if(row.section_dept){
                        return row.div_dept_office + ' - ' + row.section_dept;
                    } else {
                        return row.div_dept_office;
                    }
                }
            },
            { data: 'subject' },
            { data: 'recipient' },

            { data: 'sign_date', render: function (data) {
                return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            }},
            { data: 'destination_dropfile' },
            { data: 'comments' },
            { data: 'id', render: function(id){
                return `<button class="btn btn-sm btn-primary editBtn" data-id="${id}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delBtn" data-id="${id}"><i class="fa fa-trash"></i></button>
                         <button class="btn btn-sm btn-info viewBtn" data-id="${id}" title="View"><i class="fas fa-eye"></i></button>`;
            }}
        ],
        dom: "<'row mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            { extend: 'excelHtml5', className: 'btn btn-success btn-sm', text: '<i class="fa fa-file-excel"></i> Excel' },
            { extend: 'pdfHtml5', className: 'btn btn-warning btn-sm', text: '<i class="fa fa-file-pdf"></i> PDF' },
            // { extend: 'print', className: 'btn btn-primary btn-sm', text: '<i class="fa fa-print"></i> Print' }
       {
    extend: 'print',
    text: '<i class="fa fa-print"></i> Print',
    className: 'btn btn-danger btn-sm',
    exportOptions: { columns: ':not(:last-child)' },
    title: '',
    customize: function (win) {
        var officeTitle = "চেয়ারম্যান সচিবালয়";

        // Get today's date in Bangla
        function enToBn(num) {
            return num.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]);
        }
        var today = new Date();
        var formattedDate = enToBn(today.getDate()) + '-' + enToBn(today.getMonth()+1) + '-' + enToBn(today.getFullYear());

        var body = win.document.body;

        // Remove default title
        $(win.document.head).find('title').remove();

        // Body styling
        $(body).css({
            'font-family': "'Noto Sans Bengali', sans-serif",
            'direction': 'ltr',
            'text-align': 'center',
            'font-size': '12px'
        });

        // Header
        $(body).prepend(`
            <div style="text-align:center; margin-bottom:0; padding-bottom:0; border-bottom:0;">
                <h4 style="margin:0;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h4>
                <h5 style="margin:5px 0;">ফাইল প্রাপ্তি রেজিস্টার</h5>
                <p style="margin:0;" class="mb-0">দপ্তর : ${officeTitle}</p>
                <p style="margin:0;" class="mb-0">তারিখ : ${formattedDate}</p>
            </div>
        `);

        // Table styling
        $(body).find('table')
            .css({
                'margin': '0 auto',
                'width': '100%',
                'font-size': '14px',
                'border': '1px solid #c7c9c8',
                'border-collapse': 'collapse'
            });
        $(body).find('th, td').css({
            'border': '1px solid #c7c9c8',
            'padding': '4px'
        });

        // Footer
        $(body).append(`
            <div style="text-align:center; margin-top:20px; padding-top:10px; border-top:1px solid #c7c9c8; font-size:11px; color:#666;">
                <small><i class="fa fa-copyright"></i> ${enToBn(today.getFullYear())} BCIC. [--Design & Developed by ICT Division, BCIC.--]</small>
            </div>
        `);

        // Remove DataTables info line
        $(body).find('.dataTables_info').remove();
    }
}

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

    // Auto-update d_number only for new entry (use file_action next_d_number)
    $('#addModal').on('show.bs.modal', function () {
        let id = $('#id').val();
        if(!id){
            // choose year from entry_date input
            let entryDate = $('#entry_date').val() || new Date().toISOString().slice(0,10);
            let year = new Date(entryDate).getFullYear();
            $.getJSON('file_action.php?action=next_d_number&year='+year, function(res){
                if(res.status === 200){
                    $('#d_number').val(res.next_d_number); // already Bangla
                }
            });
        }
    });

    // Helper: show toast
function showToast(message, type='success') {
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('toastMessage');

    toastBody.innerText = message;

    // Set toast color
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Save (Add/Update)
$('#fileForm').on('submit', function(e){
    e.preventDefault();

    // convert Bangla d_number to English before sending (if d_number is Bangla)
    let dnum = $('#d_number').val();
    let eng = dnum.replace(/[০-৯]/g, function(match){ 
        return ['0','1','2','3','4','5','6','7','8','9'][['০','১','২','৩','৪','৫','৬','৭','৮','৯'].indexOf(match)]; 
    });
    $('#d_number').val(eng);

    $.post('file_action.php?action=save', $(this).serialize(), function(res){
        // Show toast instead of alert
        if(res.status == 200){
            showToast(res.message, 'success');
            $('#addModal').modal('hide');
            setTimeout(function() {
                $('#fileForm')[0].reset();
                $('.chosen-select').val([]).trigger("chosen:updated");
                table.ajax.reload(null, false);
            }, 300);
        } else {
            showToast(res.message, 'danger');
        }
    }, 'json').fail(function(xhr, status, error) {
        showToast('Request failed: ' + error, 'danger');
    });
});


    // Edit
    $('#fileTable').on('click','.editBtn', function(){
        let id = $(this).data('id');
        $.getJSON('file_action.php?action=get&id='+id, function(data){
            $('#id').val(data.id);
            $('#entry_date').val(data.entry_date);
            $('#recipient').val(data.recipient);
            $('#immediate_sender_office').val(data.immediate_sender_office);
            $('#div_dept_office').val(data.div_dept_office);
            $('#section_dept').val(data.section_dept);
            $('#d_number').val(data.d_number); // server returns Bangla version
            $('#div_sign_date').val(data.div_sign_date);
            $('#sign_date').val(data.sign_date);
            $('#subject').val(data.subject);
            $('#comments').val(data.comments);

            let selected = data.destination_dropfile ? data.destination_dropfile.split(',') : [];
            $('#destination_dropfile').val(selected).trigger("chosen:updated");

            $('#addModal').modal('show');
        });
    });

 // Helper: convert English digits to Bangla
function enToBn(num) {
    return num ? num.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
}

// View Details Functionality
$('#fileTable').on('click', '.viewBtn', function() {
    var id = $(this).data('id');

    $.getJSON('file_action.php?action=get&id=' + id, function(data) {
        if (data && Object.keys(data).length > 0) {
            // Handle destination multi-select
            let destinations = data.destination_dropfile ? data.destination_dropfile.split(',').join(', ') : '';

            var modalContent = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>এন্ট্রি তারিখ:</strong> ${data.entry_date ? new Date(data.entry_date).toLocaleDateString('bn-BD') : ''}</p>
                        <p><strong>প্রাপক:</strong> ${data.recipient || ''}</p>
                        <p><strong>ডকেট নং:</strong> ${data.d_number || ''}</p>
                        <p><strong>স্মারক নং:</strong> ${enToBn(data.ref_number || '')}</p>
                         <p><strong>আগত ফাইল পরিচালকের দপ্তর :</strong> ${data.immediate_sender_office || ''}</p>
                        <p><strong>পাঠানোর তারিখ:</strong> ${data.send_date ? new Date(data.send_date).toLocaleDateString('bn-BD') : ''}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>প্রেরক:</strong> ${data.sender || ''}</p>
                        <p><strong>ফাইল উপস্থাপনকারী বিভাগ/শাখ:</strong> ${data.div_dept_office || ''}${data.section_dept ? ' - ' + data.section_dept : ''}</p>
                        <p><strong>বিষয়বস্তু:</strong> ${data.subject || ''}</p>
                        <p><strong>গন্তব্য অফিস:</strong> ${destinations}</p>
                        <p><strong>চেয়ারম্যান স্বাক্ষরের তারিখ:</strong> ${data.sign_date ? new Date(data.sign_date).toLocaleDateString('bn-BD') : ''}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>চেয়ারম্যান নোট:</strong> ${data.chairman_note || 'নাই'}</p>
                        <p><strong>মন্তব্য:</strong> ${data.comments || 'নাই'}</p>
                        <p><strong>মাধ্যম:</strong> ${data.medium || ''}</p>
                    </div>
                </div>
            `;

            $('#viewModal .modal-body').html(modalContent);
            $('#viewModal').modal('show');
        } else {
            alert('ডেটা পাওয়া যায়নি!');
        }
    }).fail(function(xhr, status, error) {
        alert('Request failed: ' + error);
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

    // Reset modal when hidden
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
