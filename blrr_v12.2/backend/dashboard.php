<?php 
session_name('blrr');
session_start();
$username = $_SESSION['username']; //chairman
$user_type = $_SESSION['user_type'];//admin
$office = $_SESSION['office'];
$table_name = $_SESSION['table_name'];
$office_title = $_SESSION['office_title'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
} 

require_once("config.php");
include_once '../db/db.php';
include_once 'header.php';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));

$result = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_meeting_count FROM $table_name where status='pending'");  
$row11 = mysqli_fetch_array($result);
$upcoming_meeting_count = $row11['upcoming_meeting_count'];

?>

<!-- <div class="container-fluid"> -->
    <div class="table-wrapper border border-muted rounded shadow p-2 my-1">  
        <div class="container-fluid my-1">  
            <span class="float-end d-flex flex-wrap gap-2 align-items-center">
    <!-- Button to open modal -->
    <button class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#entryModal">
        <i class="fa fa-plus"></i> নতুন পত্র/চিঠি এন্ট্রি
    </button>
  
    <a href="addfiledashboard.php" 
       class="btn btn-outline-success border-custom-purple d-inline-block mb-2">
        <i class="fa fa-file" style="font-size:16px;color:red"></i> 
        <span>ফাইল প্রাপ্তি রেজিস্টার</span>
    </a>

    <!-- Your other buttons remain the same -->
    <a href="show_all.php?table_name=<?= $_SESSION['table_name'] ?>" class="btn btn-outline-success mb-2">
        <i class="fa fa-file-archive-o" style="font-size:16px;color:red"></i> <span>সব পত্র দেখুন</span>
    </a>

    <?php if ($user_type == 'user' && ($office_title == 'division' || $office_title == 'director' || $office_title == 'chairman')) { ?>
        <a href="incoming_letter.php" class="btn btn-outline-success position-relative mb-2">
            <i class="fa fa-clock-o" style="font-size:20px;color:red"></i> আগত চিঠি
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $upcoming_meeting_count; ?>
            </span>
        </a>
    <?php } ?>

    <a href="show_all_old.php?table_name=<?= $_SESSION['table_name'] ?>" class="btn btn-outline-success mb-2">
        <i class="fa fa-file-archive-o" style="font-size:16px;color:red"></i> <span>পুরাতন পত্র দেখুন</span>
    </a>
    <a href="search_new.php?table_name=<?= $_SESSION['table_name'] ?>&val=987" class="btn btn-outline-primary mb-2">
        <i class="fa fa-search" style="font-size:16px"></i> <span>খুজুন</span>
    </a>

    <?php //if ($user_type == 'sadmin') { ?>
       <!--  <a href="manage_user.php?username=<?= $_SESSION['username'] ?>" class="btn btn-warning mb-2">
            <i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User
        </a>
        <a href="manage_user.php?username=<?= $_SESSION['username'] ?>" class="btn btn-warning mb-2">
            <i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database
        </a> -->
    <?php //} ?> 
    
    <?php if ($table_name == 'chairman') { ?>
        <form id="downloadForm" action="download_db.php" method="post" class="mb-2">
            <button class="btn btn-warning" type="submit" name="submit">
                <i class="fa fa-download" style="font-size:16px"></i> ডাউনলোড ডিবি
            </button>
        </form>
    <?php } ?>           
</span>

           <!-- Add/Edit Modal - FIXED STRUCTURE -->
<div class="modal fade" id="entryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header bg-custom-purple text-white" style="flex-shrink: 0;">
                <h5 class="modal-title fw-bold">পত্র প্রাপ্তি রেজিস্টার এন্ট্রি ফরম</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; flex: 1;">
                <form id="user_form">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="unique_id" name="unique_id">
                    <input type="hidden" id="table_name" name="table_name" value="<?php echo $table_name; ?>">
                    <input type="hidden" id="year_auto1" name="year_auto1">
                    <div class="row g-2">
                        <?php if($table_name=='chairman'): ?>
                        <div class="col-md-6">
                            <label class="form-label mb-1">পত্র প্রাপ্তি তারিখ</label>
                            <input type="date" class="form-control" id="entry_date" name="entry_date" value="<?php echo $today_date ?>" required>
                            <span id="dateDisplay"></span>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const dateInput = document.getElementById('entry_date');
                                    const docketInput = document.getElementById('d_number');
                                    dateInput.addEventListener('change', function () {
                                        const selectedDate = this.value;
                                        if (!selectedDate) return;
                                        const year = new Date(selectedDate).getFullYear();
                                        $.post('get_d_number.php', { year_auto1: year }, function(response) {
                                            docketInput.value = response;
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">প্রাপক</label>
                            <select class="form-select form-control" id="recipient" name="recipient" required>
                                <option selected disabled value="">--Select--</option>
                                <?php
                                $sql = "SELECT division_bn FROM division where id not in(2,3,4)";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)) {
                                    echo "<option value='".$row['division_bn']."'>".$row['division_bn']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <?php 
                        if($user_type=='user' && $table_name=='chairman'){
                            $sql_d_number1 = "SELECT id FROM $table_name WHERE entry_date LIKE '$year_auto%'";
                            $result_d_number1 = mysqli_query($conn, $sql_d_number1);
                            if (mysqli_num_rows($result_d_number1) == 0) {
                                $row_d_number_max = 1;
                            } else {
                                $sql_d_number = "SELECT MAX(d_number) AS max_d_number FROM $table_name WHERE entry_date LIKE '$year_auto%'";
                                $result_d_number = mysqli_query($conn, $sql_d_number);
                                $row_d_number = mysqli_fetch_array($result_d_number);
                                $row_d_number_max = $row_d_number['max_d_number'] + 1;
                            }
                        ?>
                        <div class="col-md-6">
                            <label class="form-label mb-1">ডকেট নং</label>
                            <input type="text" class="form-control bg-light" id="d_number" name="d_number" value="<?php echo englishToBanglaNumber($row_d_number_max)?>" readonly>
                        </div>
                        <?php } else { ?>
                        <div class="col-md-6">
                            <label class="form-label mb-1">ডকেট নং</label>
                            <input type="text" class="form-control" id="d_number" name="d_number" value="" oninput="validateInput(this)">
                        </div>
                        <?php } ?>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">দৃষ্টি আকর্ষণ</label>
                            <input type="text" class="form-control" name="attention" id="attention">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">স্মারক/রেফারেন্স নং</label>
                            <input type="text" class="form-control" name="ref_number" id="ref_number">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">প্রেরণের তারিখ</label>
                            <input type="date" class="form-control" name="send_date" id="send_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">প্রেরক</label>
                            <input type="text" class="form-control" name="sender" id="sender" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">বিভাগ/ডিপার্টমেন্ট/অফিস</label>
                            <input type="text" class="form-control" name="div_dept_office" id="div_dept_office">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label mb-1">বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু</label>
                            <input type="text" class="form-control" name="subject" id="subject" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">গন্তব্য (এক/একাধিক নির্বাচন করুন):</label>
                            <input type="text" class="form-control" id="destination_input" list="destination_drop" autocomplete="off" placeholder="Select destinations">
                            <datalist id="destination_drop">
                                <?php
                                if ($office_title == "chairman") {
                                    $sql0 = "SELECT division_bn FROM division";
                                } else {
                                    $sql0 = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
                                }
                                $result0 = mysqli_query($conn, $sql0);
                                while ($row0 = mysqli_fetch_array($result0)) {
                                    echo "<option value='" . $row0['division_bn'] . "'>" . $row0['division_bn'] . "</option>";
                                }
                                ?>
                            </datalist>
                            <div id="selected_destinations_display" class="mt-1"></div>
                            <input type="hidden" id="selected_destinations" name="selected_destinations">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">গন্তব্য (লিখুন):</label>
                                <input type="text" class="form-control" id="destination" placeholder="" name="destination">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-1">মাধ্যম</label>
                            <select class="form-select form-control" id="medium" name="medium" required>
                                <option selected disabled value="">--Select--</option>
                                <option value="হার্ডকপি">হার্ডকপি</option>
                                <option value="ই-মেইল">ই-মেইল</option>
                                <option value="ফ্যাক্স">ফ্যাক্স</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">বিতরণের তারিখ</label>
                            <input type="date" class="form-control" name="distribution_date" id="distribution_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">
                                <?php 
                                if ($user_type == 'user' && $office_title == 'chairman') {
                                    echo "চেয়ারম্যান নোট:";
                                } else if ($user_type == 'user' && $office_title == 'director') { 
                                    echo "পরিচালকের নোট:";
                                } else { 
                                    echo "বিভাগীয় প্রধানের নোট:";
                                } 
                                ?>
                            </label>
                            <input type="text" class="form-control" id="chairman_note" name="chairman_note">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label mb-1">মন্তব্য</label>
                            <textarea class="form-control" name="comments" id="comments" rows="1"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="flex-shrink: 0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-cancel"></i> Close</button>
                <button type="button" class="btn btn-success" id="saveFormButton"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
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
<!-- </div> -->

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
// Input validation function
function validateInput(input) {
    const validChars = /[0-9০-৯]/g;                                     
    input.value = input.value.replace(/[^0-9০-৯]/g, '');
}

// Bengali number conversion
function enToBn(num){
    return num.toString().replace(/[0-9]/g, d=>'০১২৩৪৫৬৭৮৯'[d]);
}

// Global selectedDestinations variable
let selectedDestinations = {};

// Alert function for better notifications
function showAlert(type, message) {
    $('.custom-alert').remove();
    
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    var alertHtml = `
        <div class="alert ${alertClass} custom-alert alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    if (type === 'success') {
        setTimeout(function() {
            $('.custom-alert').alert('close');
        }, 3000);
    }
}

// Update selected destinations function
function updateSelectedDestinations() {
    const selectedDestinationsInput = document.getElementById('selected_destinations');
    const selectedDestinationsDisplay = document.getElementById('selected_destinations_display');
    
    if (selectedDestinationsInput && selectedDestinationsDisplay) {
        const selectedValues = Object.keys(selectedDestinations).join(',');
        selectedDestinationsInput.value = selectedValues;
        
        selectedDestinationsDisplay.innerHTML = Object.keys(selectedDestinations)
            .map(destination => `<span class="badge bg-primary me-1 mb-1">${destination} <button type="button" class="btn-close btn-close-white ms-1" onclick="removeDestination('${destination}')"></button></span>`)
            .join('');
    }
}

// Remove destination function
function removeDestination(destination) {
    delete selectedDestinations[destination];
    updateSelectedDestinations();
}

// Destination selection functionality
document.addEventListener('DOMContentLoaded', function () {
    const destinationInput = document.getElementById('destination_input');
    
    if (destinationInput) {
        destinationInput.addEventListener('change', function () {
            const inputValue = this.value.trim();
            if (inputValue) {
                if (!selectedDestinations[inputValue]) {
                    selectedDestinations[inputValue] = true;
                    updateSelectedDestinations();
                }
                this.value = '';
            }
        });

        // Initialize with existing value if editing
        const existingDestination = document.getElementById('selected_destinations').value;
        if (existingDestination) {
            existingDestination.split(',').forEach(dest => {
                if (dest.trim()) {
                    selectedDestinations[dest.trim()] = true;
                }
            });
            updateSelectedDestinations();
        }
    }
});

$(document).ready(function(){
    var tableName = "<?php echo $table_name; ?>";
    var userType = "<?php echo $user_type; ?>";

     // Bangla → English number convert
    function bnToEn(text) {
        return text.toString().replace(/[০১২৩৪৫৬৭৮৯]/g, d => '০১২৩৪৫৬৭৮৯'.indexOf(d));
    }

    /* -------------------------------------------------------
       🔥 MULTI-KEYWORD + PARTIAL MATCH + AND LOGIC SEARCH
       ✔ Bangla + English
       ✔ Partial match
       ✔ Multi keywords
       ✔ Search only in selected columns
    ----------------------------------------------------------*/
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {

        let input = $('#friendsTable_filter input').val().trim();
        if (input === "") return true;

        // convert Bangla numbers & split by space
        let keywords = input.split(/\s+/).map(k => bnToEn(k.trim())).filter(k => k !== "");

        // Columns to search (0-based index)
        // Skip first (SL) and last (action)
        let searchColumns = [1,2,3,4,5,6,7,8,9,10,11];

        // row values converted to lowercase + english numbers
        let rowValues = searchColumns.map(i =>
            bnToEn((data[i] || "").toString().trim().toLowerCase())
        );

        // AND LOGIC → each keyword must match in at least one column
        return keywords.every(keyword =>
            rowValues.some(val => val.includes(keyword))
        );
    });


// Initialize DataTable with export buttons
var table = $('#friendsTable').DataTable({
    // processing: true,
    // serverSide: true,
    pageLength: 50,
    "ajax": {
        "url": "get_data.php?table_name=" + tableName,
        "dataSrc": "data"
    },
    "columns": [
        {
            "data": null,
            "render": function (data, type, row, meta) {
                const rowNum = meta.row + 1;
                return rowNum.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]);
            }
        },
        <?php if($user_type=='user' && $table_name=='chairman'): ?>
        {
            "data": "entry_date",
            "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            }
        },
        <?php endif; ?>
        { "data": "recipient" },
        // {
        //     "data": "d_number",
        //     "render": function (data) {
        //         return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
        //     }
        // },
        { 
        "data": "d_number",
        "render": function (data) {
            return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            },
        "searchable": true,  // enable search
        "type": "numeric-bn" // use custom search
        
        },

        {
            "data": "ref_number",
            "render": function (data) {
                return data ? data.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]) : '';
            }
        },
        {
            "data": "send_date",
            "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            }
        },
        { "data": "sender" },
        { "data": "div_dept_office" },
        { "data": "subject" },
        { "data": "destination_drop" },
        {
            "data": "distribution_date",
            "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            }
        },
        { "data": "medium" },
        {
            "data": null,
            "render": function (data, type, row) {
                return `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary editBtn" data-id="${row.id}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger deleteBtn" data-id="${row.id}" title="Delete"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-info viewBtn" data-id="${row.id}" title="View"><i class="fas fa-eye"></i></button>
                    </div>`;
            }
        }
    ],
    "language": {
        "search": "খুঁজুন:",
        "lengthMenu": "প্রদর্শন _MENU_ এন্ট্রি",
        "info": "দেখানো হচ্ছে _START_ থেকে _END_ পর্যন্ত, মোট _TOTAL_ এন্ট্রি",
        "infoEmpty": "দেখানো হচ্ছে 0 থেকে 0 পর্যন্ত, মোট 0 এন্ট্রি",
        "infoFiltered": "(মোট _MAX_ এন্ট্রি থেকে ফিল্টার করা হয়েছে)",
        "paginate": {
            "first": "প্রথম",
            "last": "শেষ",
            "next": "পরবর্তী",
            "previous": "পূর্ববর্তী"
        }
    },
    "infoCallback": function (settings, start, end, max, total) {
        function enToBn(num) {
            return num.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]);
        }
        return 'দেখানো হচ্ছে ' + enToBn(start) + ' থেকে ' + enToBn(end) + ' পর্যন্ত, মোট ' + enToBn(total) + ' এন্ট্রি';
    },
    "order": [[5, 'DESC']],
    // ✅ Enable Buttons Extension
    // dom: 'Bfrtip',
    dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>rtip',
buttons: {
    dom: {
        button: {
            tag: 'button',
            className: 'btn btn-sm'
        }
    },
 buttons: [
    { extend:'pdfHtml5', text: '<i class="fa fa-file-pdf"></i> Pdf', className:'btn-danger', exportOptions:{columns:':not(:last-child)'} },
    { extend:'excelHtml5', text: '<i class="fa fa-file-excel"></i> Excel', className:'btn-success', exportOptions:{columns:':not(:last-child)'} },
    { extend:'csvHtml5', text:'CSV', className:'btn-primary', exportOptions:{columns:':not(:last-child)'} },
    {
        extend:'print',
        text: '<i class="fa fa-print"></i> Print',
        className:'btn-danger',
        exportOptions:{columns:':not(:last-child)'},
        title: '',
        customize: function (win) {
            var officeTitle = "চেয়ারম্যান সচিবালয়";
            // var officeTitle = "<?php echo $office_title ?? ''; ?>";
            var doc = win.document;

            // Remove default <title>
            var titles = doc.getElementsByTagName('title');
            for (var i = titles.length - 1; i >= 0; i--) {
                titles[i].parentNode.removeChild(titles[i]);
            }

            // Basic style for print body
            var body = doc.body;
            body.style.fontFamily = "'Noto Sans Bengali', sans-serif";
            body.style.direction = 'ltr';
            body.style.textAlign = 'center';
            body.style.fontSize = '12px';

            // Bengali Header
            var headerHTML = `
                <div style="text-align:center; margin-bottom:0px; border-bottom:0px solid #000; padding-bottom:0px;">
                    <h4 style="margin:0;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h4>
                    <h5 style="margin:5px 0;">পত্র প্রাপ্তি রেজিস্টার</h5>
                    <p style="margin:0;" class="mb-0">দপ্তর : ${officeTitle}</p>
                     <p style="margin:0;" class="mb-0">তারিখ : <?php echo englishToBanglaNumber($today_date);?></p>
                   
                </div>
            `;
            body.insertAdjacentHTML('afterbegin', headerHTML);

            // Style the table
            var tables = doc.getElementsByTagName('table');
            if (tables.length > 0) {
                var table = tables[0];
                table.style.margin = '0 auto';
                table.style.width = '100%';
                table.style.fontSize = '14px';
                table.style.border = 'thin solid #aaa'; // ✅ thinner gray border
                table.style.borderCollapse = 'collapse';

                var cells = table.querySelectorAll('th, td');
                for (var j = 0; j < cells.length; j++) {
                    cells[j].style.border = '0.5px solid #aaa'; // ✅ thinner gray border
                    cells[j].style.padding = '4px';
                }
            }

            // Add Footer
            var footerHTML = `
                <div style="text-align:center; margin-top:20px; padding-top:10px; border-top:1px solid #c7c9c8; font-size:11px; color:#666;">
                    <small><i class="fa fa-copyright"></i> <?php echo date("Y"); ?> BCIC. [--Design & Developed by ICT Division, BCIC.--]</small>
                </div>
            `;
            body.insertAdjacentHTML('beforeend', footerHTML);


            // Remove DataTables info line if present
            var infos = doc.querySelectorAll('.dataTables_info');
            for (var k = 0; k < infos.length; k++) {
                infos[k].parentNode.removeChild(infos[k]);
            }
        }
    }
]

}
});
$('#friendsTable_filter input').off().on('keyup', function () {
    table.draw();
});



    // Save form using the button click
    $('#saveFormButton').on('click', function(){
        var url = $('#edit_id').val() ? 'update.php' : 'save.php';
        
        // Show loading state
        var saveBtn = $(this);
        var originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        // Validate required fields before submitting
        var requiredFields = ['recipient', 'sender', 'd_number', 'medium', 'send_date', 'subject'];
        var missingFields = [];
        
        requiredFields.forEach(function(field) {
            var value = $('[name="' + field + '"]').val();
            if (!value || value.trim() === '') {
                missingFields.push(field);
            }
        });
        
        if (missingFields.length > 0) {
            showAlert('error', 'Please fill in all required fields');
            saveBtn.prop('disabled', false).html(originalText);
            return;
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $('#user_form').serialize(),
            dataType: 'json',
            success: function(res) {
                console.log('Save response:', res);
                
                if(res && res.status == 1){
                    showAlert('success', res.message);
                    
                    // Close modal and reset form
                    $('#entryModal').modal('hide');
                    $('#user_form')[0].reset();
                    $('#edit_id').val('');
                    
                    // Reset destinations
                    selectedDestinations = {};
                    updateSelectedDestinations();
                    
                    // Reload table data
                    setTimeout(function() {
                        table.ajax.reload(null, false);
                    }, 500);
                    
                } else {
                    var errorMsg = res && res.message ? res.message : 'Unknown error occurred';
                    showAlert('error', 'Error: ' + errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                var errorMsg = 'Request failed: ' + error;
                if (xhr.responseText) {
                    try {
                        var jsonResponse = JSON.parse(xhr.responseText);
                        errorMsg = jsonResponse.message || errorMsg;
                    } catch (e) {
                        errorMsg = 'Server error: ' + xhr.status;
                    }
                }
                showAlert('error', errorMsg);
            },
            complete: function() {
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Also allow form submission with Enter key
    $('#user_form').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#saveFormButton').click();
        }
    });

    // View Details Functionality
    $('#friendsTable').on('click', '.viewBtn', function(){
        var id = $(this).data('id');
        var table_name = "<?php echo $table_name; ?>";
        
        $.getJSON('fetch.php', {id: id, table_name: table_name}, function(res){
            if(res){
                var modalContent = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>এন্ট্রি তারিখ:</strong> ${res.entry_date ? new Date(res.entry_date).toLocaleDateString('bn-BD') : ''}</p>
                            <p><strong>প্রাপক:</strong> ${res.recipient || ''}</p>
                            <p><strong>ডকেট নং:</strong> ${res.d_number || ''}</p>
                            <p><strong>স্মারক নং:</strong> ${res.ref_number || ''}</p>
                            <p><strong>পাঠানোর তারিখ:</strong> ${res.send_date ? new Date(res.send_date).toLocaleDateString('bn-BD') : ''}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>প্রেরক:</strong> ${res.sender || ''}</p>
                            <p><strong>ডিভিশন/অফিস:</strong> ${res.div_dept_office || ''}</p>
                            <p><strong>বিষয়বস্তু:</strong> ${res.subject || ''}</p>

                            <p><strong>গন্তব্য অফিস:</strong> ${res.destination_drop || ''}, ${res.destination || ''}</p>

                            <p><strong>বিতরণ তারিখ:</strong> ${res.distribution_date ? new Date(res.distribution_date).toLocaleDateString('bn-BD') : ''}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>চেয়ারম্যান নোট:</strong> ${res.chairman_note || 'নাই'}</p>
                            <p><strong>মন্তব্য:</strong> ${res.comments || 'নাই'}</p>
                            <p><strong>মাধ্যম:</strong> ${res.medium || ''}</p>
                        </div>
                    </div>
                `;
                
                $('#viewModal .modal-body').html(modalContent);
                $('#viewModal').modal('show');
            }
        });
    });



let isEditMode = false; // ✅ global flag to detect edit mode

// ✅ Reset form when modal is closed (works for both Add & Edit)
$('#entryModal').on('hidden.bs.modal', function () {
    $('#user_form')[0].reset();
    $('#edit_id').val('');
    selectedDestinations = {};
    updateSelectedDestinations();
    isEditMode = false; // reset flag when modal closes
});

// ✅ Edit Functionality
$('#friendsTable').on('click', '.editBtn', function () {
    isEditMode = true; // ✅ mark that we're editing
    var id = $(this).data('id');
    var table_name = "<?php echo $table_name; ?>";

    $.getJSON('fetch.php', { id: id, table_name: table_name }, function (res) {
        console.log('fetch result for edit:', res);

        if (res && Object.keys(res).length) {
            $('#edit_id').val(res.id);
            $('#unique_id').val(res.unique_id || '');
            $('#entry_date').val(res.entry_date || '');
            $('#recipient').val(res.recipient || '');
            $('#d_number').val(res.d_number || ''); // ✅ keep real d_number
            $('#attention').val(res.attention || '');
            $('#ref_number').val(res.ref_number || '');
            $('#send_date').val(res.send_date || '');
            $('#sender').val(res.sender || '');
            $('#div_dept_office').val(res.div_dept_office || '');
            $('#subject').val(res.subject || '');
            $('#medium').val(res.medium || '');
            $('#distribution_date').val(res.distribution_date || '');
            $('#chairman_note').val(res.chairman_note || '');
            $('#comments').val(res.comments || '');
            $('#destination').val(res.destination || '');

            // ---- handle destinations ----
            var destString = res.destination_drop || '';
            selectedDestinations = {};
            $('#selected_destinations').val('');
            $('#selected_destinations_display').empty();

            if (destString && destString.trim() !== '') {
                destString.split(',').forEach(function (dest) {
                    var clean = dest.trim();
                    if (clean) selectedDestinations[clean] = true;
                });
                updateSelectedDestinations();
            }

            $('#entryModal').modal('show');
        } else {
            alert('No data found for this record.');
        }
    }).fail(function (xhr) {
        console.error('fetch.php error:', xhr.responseText);
        alert('Error loading data');
    });
});

// ✅ Auto-update d_number only when adding new record
$('#entryModal').on('show.bs.modal', function () {
    // skip if we are editing
    if (isEditMode) return;

    var tableName = "<?php echo $table_name; ?>";
    var userType = "<?php echo $user_type; ?>";
    var officeTitle = "<?php echo $office_title; ?>";

    // Only for chairman user
    if (userType === 'user' && tableName === 'chairman') {
        $.ajax({
            url: 'get_next_d_number.php',
            type: 'GET',
            data: { table_name: tableName },
            success: function (res) {
                $('#d_number').val(res); // Bengali number returned
            }
        });
    }
});


    // Delete Functionality
    $('#friendsTable').on('click', '.deleteBtn', function(){
        if(confirm("Are you sure you want to delete this entry?")){
            var id = $(this).data('id');
            var table_name = "<?php echo $table_name; ?>";
            
            $.post('delete.php', {id: id, table_name: table_name}, function(res){
                if(res.status == 1){
                    table.ajax.reload();
                    alert(res.message);
                } else {
                    alert('Error: ' + res.message);
                }
            },'json').fail(function() {
                alert('Delete request failed');
            });
        }
    });


    
    // $('#entryModal').on('hidden.bs.modal', function () {
    //     $('#user_form')[0].reset();
    //     $('#edit_id').val('');
    //     selectedDestinations = {};
    //     updateSelectedDestinations();
    // });



});
</script>