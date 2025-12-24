<?php 
session_name('blrr');
session_start();
$username = $_SESSION['username']; 
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
$table_name = $_SESSION['table_name'];
$office_title = $_SESSION['office_title'];

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
} 

require_once("config.php");
include_once '../db/db.php';
include_once 'header.php';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));
?>

<div class="container-fluid">
    <div class="table-wrapper border border-muted rounded shadow p-2 my-1">  
        <div class="container my-1">  
            <span class="float-end mb-2">         
                <?php if($user_type=='sadmin'){ ?>
                    <h4>
                        <a href="manage_user.php?username=<?=$username?>" class="btn btn-warning">
                            <i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User
                        </a>
                    </h4>
                    <h4>
                        <a href="manage_user.php?username=<?=$username?>" class="btn btn-warning">
                            <i class="fa fa-download" style="font-size:15px;color:black"></i> Download DB
                        </a>
                    </h4>
                <?php } ?>

                <a href="dashboard.php" class="btn btn-outline-success"><i class="fa fa-home" style="color:red"></i> Home</a>                    
                <a href="show_all_old.php?table_name=<?=$table_name?>" class="btn btn-outline-success">
                    <i class="fa fa-file-archive-o" style="color:red"></i> Show Old Docs
                </a>
                <a href="search_new.php?table_name=<?=$table_name?>&val=987" class="btn btn-outline-primary">
                    <i class="fa fa-search"></i> Search
                </a>
            </span>     

            <!-- Add/Edit Modal - FIXED STRUCTURE -->
            <div class="modal fade" id="entryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="max-height: 95vh; display: flex; flex-direction: column;">
                        <div class="modal-header bg-custom-purple text-white" style="flex-shrink: 0;">
                            <h5 class="modal-title">পত্র প্রাপ্তি রেজিস্টার এন্ট্রি / সংশোধন করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; flex: 1;">
                            <form id="user_form">
                                <input type="hidden" id="edit_id" name="id">
                                <input type="hidden" id="unique_id" name="unique_id">
                                <input type="hidden" id="table_name" name="table_name" value="<?php echo $table_name; ?>">

                                <div class="row g-3">
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">পত্র প্রাপ্তি তারিখ</label>
                                        <input type="date" class="form-control" id="entry_date" name="entry_date" value="<?php echo date("Y-m-d") ?>" >
                                    </div>                                    

                                    <?php 
                                    if($user_type=='user' && $table_name=='chairman'){
                                        $sql_d_number1 = "SELECT id FROM $table_name WHERE entry_date LIKE '$year_auto%'";
                                        $result_d_number1 = mysqli_query($conn, $sql_d_number1);

                                        if (mysqli_num_rows($result_d_number1) == 0) {
                                            $row_d_number_max = 1;
                                        } else {
                                            $sql_d_number = "SELECT MAX(d_number) AS max_d_number FROM $table_name";
                                            $result_d_number = mysqli_query($conn, $sql_d_number);
                                            $row_d_number = mysqli_fetch_array($result_d_number); 
                                            $row_d_number_max= $row_d_number['max_d_number']+1;
                                        } 
                                    ?> 
                                    <div class="col-md-6">
                                        <label class="form-label">ডকেট নং</label>
                                        <input type="text" class="form-control bg-light" id="d_number" name="d_number" value="<?php echo englishToBanglaNumber($row_d_number_max)?>" readonly>
                                    </div>
                                    <?php } else { ?>
                                    <div class="col-md-6">
                                        <label class="form-label">ডকেট নং</label>
                                        <input type="text" class="form-control" id="d_number" name="d_number" value="" oninput="validateInput(this)">
                                    </div>
                                    <?php } ?>  
                                    <div class="col-md-6">
                                        <label class="form-label">স্মারক/রেফারেন্স নং</label>
                                        <input type="text" class="form-control" name="ref_number" id="ref_number">
                                    </div>
                                    
                                    <!-- <div class="col-md-6">
                                        <label class="form-label">প্রেরণের তারিখ</label>
                                        <input type="date" class="form-control" name="send_date" id="send_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div> -->
                                    
                                    <div class="col-md-12">
                                        <label class="form-label">বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু</label>
                                        <input type="text" class="form-control" name="subject" id="subject" readonly required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">গন্তব্য (এক/একাধিক নির্বাচন করুন):</label>
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
                                        
                                        <!-- Container to display selected destinations -->
                                        <div id="selected_destinations_display" class="mt-2"></div>

                                        <!-- Hidden input to store selected destinations -->
                                        <input type="hidden" id="selected_destinations" name="selected_destinations">
                                    </div>  
                                   <div class="col-md-6">
                                      <div class="form-group  mt-2"><label> গন্তব্য (লিখুন): </label>
                                       <input type="text" class="form-control" id="destination" placeholder="" name="destination" >                                                 
                                        </div>
                                      </div>                                     
                                    <div class="col-md-6">
                                        <label class="form-label">বিতরণের তারিখ</label>
                                        <input type="date" class="form-control" name="distribution_date" id="distribution_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer" style="flex-shrink: 0;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="saveFormButton">Save</button>
                        </div>
                    </div>
                </div>
            </div>
       

            <!-- View Details Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-custom-purple text-white">
                            <h5 class="modal-title">এন্ট্রি বিস্তারিত</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="viewModalBody"></div>
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
                        <th>প্রেরিত দপ্তর</th>
                        <th>পত্র প্রাপ্তি তারিখ</th>
                        <th>প্রাপক</th>
                        <th>ডকেট নং</th>
                        <th>স্মারক নং</th>                  
                        <th>পাঠানোর তারিখ</th> 
                        <th>মূল প্রেরক/উৎস</th>
                        <th>ডিভিশন/অফিস</th>
                        <th>বিষয়বস্তু</th>
                        <th>গন্তব্য</th>                    
                        <th>বিতরণ তারিখ</th>    
                        <th>মাধ্যম</th>     
                        <th>স্টাটাস</th>            
                        <th>অ্যাকশন</th>
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
    var table = $('#friendsTable').DataTable({
        "ajax": "get_data_incoming_letter.php?table_name=<?=$table_name?>",
        "columns": [
            { "data": null },
            { "data": "immediate_sender_office" },
            { "data": "entry_date",
                "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            }
             },
            { "data": "recipient" },
            { "data": "d_number" },
            { "data": "ref_number" },
            { "data": "send_date", "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            } },
            { "data": "sender" },
            { "data": "div_dept_office" },
            { "data": "subject" },
            // { "data": "destination" },
            { "data": "destination_drop" },
            { "data": "distribution_date", "render": function (data) {
                return data ? new Date(data).toLocaleDateString('bn-BD') : '';
            } },
            { "data": "medium" },
            { "data": "status" },
            { "data": null }
        ],
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row, meta){
                    return (meta.row + 1).toString().replace(/[0-9]/g, d=>'০১২৩৪৫৬৭৮৯'[d]);
                }
            },
            {
                "targets": [3,4,5],
                "render": function(data){ return data ? data.toString().replace(/[0-9]/g, d=>'০১২৩৪৫৬৭৮৯'[d]) : ''; }
            },
            {
                "targets": -1,
                "render": function(data, type, row){
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info viewBtn" data-id="${row.id}"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-primary editBtn" data-id="${row.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger deleteBtn" data-id="${row.id}"><i class="fas fa-trash"></i></button>
                        </div>`;
                }
            }
        ],
        "order": [[0,"asc"]],
        dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>rtip',
        buttons: {
            dom: {
                button: {
                    tag: 'button',
                    className: 'btn btn-sm'
                }
            },
            buttons: [
                { extend:'pdfHtml5', text:'PDF', className:'btn-danger', exportOptions:{columns:':not(:last-child)'} },
                { extend:'excelHtml5', text:'Excel', className:'btn-success', exportOptions:{columns:':not(:last-child)'} },
                { extend:'csvHtml5', text:'CSV', className:'btn-primary', exportOptions:{columns:':not(:last-child)'} },
                { extend:'print', text:'Print', className:'btn-secondary', exportOptions:{columns:':not(:last-child)'} }
            ]
        }
    });

    // Save form using the button click
    $('#saveFormButton').on('click', function(){
        var url = $('#edit_id').val() ? 'update_others.php' : 'save.php';
        
        // Show loading state
        var saveBtn = $(this);
        var originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        // Validate required fields before submitting
        var requiredFields = ['entry_date','d_number', 'subject'];
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

    // View, Edit, Delete handlers can be the same as your existing code
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
                            <p><strong>গন্তব্য অফিস:</strong> ${res.destination || ''}</p>
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

   // Edit Functionality
$('#friendsTable').on('click', '.editBtn', function(){
    var id = $(this).data('id');
    var table_name = "<?php echo $table_name; ?>";

    // fetch record (fetch.php now guarantees row.destination exists if destination_drop exists)
    $.getJSON('fetch.php', { id: id, table_name: table_name }, function(res){
        console.log('fetch result for edit:', res);

        if (res && Object.keys(res).length) {
            // Populate form fields (IDs match your modal inputs)
            $('#edit_id').val(res.id);
            $('#unique_id').val(res.unique_id || '');
            $('#entry_date').val(res.entry_date || '');
            $('#recipient').val(res.recipient || '');
            $('#d_number').val(res.d_number || '');
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

            // ----- DESTINATIONS: reset & repopulate -----
            // use normalized field 'destination' (fetch.php sets it), but fallback if necessary
            var destString =  res.destination_drop || '';

            // reset current selection UI/state
            selectedDestinations = {};
            $('#selected_destinations').val('');
            $('#selected_destinations_display').empty();

            if (destString && destString.trim() !== '') {
                destString.split(',').forEach(function(dest){
                    var clean = dest.trim();
                    if (clean) selectedDestinations[clean] = true;
                });
                // refresh UI and hidden input
                updateSelectedDestinations();
            }

            // show the modal
            $('#entryModal').modal('show');
        } else {
            alert('No data found for this record.');
        }
    }).fail(function(xhr, status, err){
        console.error('fetch.php error:', xhr.responseText);
        alert('Error loading data');
    });
});

});
</script>
