<?php
session_name('ict_main_records_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('db/db.php');
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICT Maintenance Records</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid rounded shadow-lg p-2">
        <h4 class="text-center mb-2 text-uppercase">ICT Maintenance Records</h4>

        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" id="start_date" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" id="end_date" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <button id="filter" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <button id="reset" class="btn btn-danger"><i class="fa fa-sync-alt"></i> Reset</button>
                
            </div>
            <div class="col-md-3 ">
                <a href="dashboard.php" class="btn btn-primary float-end" ><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>

        <table id="recordsTable" class="table table-striped table-bordered table-hover table-sm" style="width:100%">
            <thead class="table-success">
                <tr>
                    <th>Requisition No</th>
                    <th>Emp ID</th>
                    <th>User</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Req. Date</th>
                    <th>Product Information</th>
                    <th>SRM</th>
                    <th>SRM Ref</th>
                    <th>Bill/Challan</th>
                    <th>Remarks</th>
                    <th>Vendor</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script>
let selectedDivisionName = 'All Divisions'; // Global variable

$(document).ready(function () {
    function loadTable(startDate = '', endDate = '', division = '') {
        $('#recordsTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: 'fetch_records.php',
                type: 'POST',
                data: { start_date: startDate, end_date: endDate, division: division }
            },
            dom: '<"row mb-2"<"col-md-6 d-flex align-items-center"l><"col-md-6 text-end"Bf>>' +
                 '<"row"<"col-md-12"tr>>' +
                 '<"row"<"col-md-5"i><"col-md-7"p>>',
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            columns: [
                { data: 'requisition_no' },
                { data: 'emp_id' },
                { data: 'user_name' },
                { data: 'division_dept' },
                { data: 'designation' },
                { data: 'date' },
                { data: 'product_name' },
                { data: 'srm' },
                { data: 'srm_ref_no' },
                { data: 'bill_or_challan_no' },
                { data: 'remarks' },
                { data: 'vendor_name' }
            ],
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn-danger btn-sm',
                    title: 'ICT Maintenance Records',
                    filename: 'ICT_Records_' + new Date().toISOString().slice(0, 10),
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            text: 'Design and Developed by ICT Division, BCIC',
                            alignment: 'center',
                            fontSize: 12,
                            bold: true,
                            margin: [0, 0, 0, 10]
                        });
                        doc.content.splice(1, 0, {
                            text: 'Division: ' + selectedDivisionName,
                            alignment: 'center',
                            fontSize: 11,
                            margin: [0, 0, 0, 10]
                        });
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 11,
                            color: 'black',
                            fillColor: '#f8f9fa'
                        };
                        doc.defaultStyle.fontSize = 10;
                    },
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data) {
                                return data.replace(/<[^>]*>/g, '').replace(/<br\s*\/?>/gi, '\n');
                            }
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    titleAttr: 'Print table',
                    className: 'btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data) {
                                return data.replace(/<br\s*\/?>/gi, "\n").replace(/<[^>]*>/g, "");
                            }
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).prepend(`
                            <div style="text-align: center; font-size: 12pt; font-weight: bold; margin-bottom: 5px;">
                                Division: ${selectedDivisionName}
                            </div>
                        `);
                        $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                        $(win.document.body).find('h1').css('text-align', 'center').css('font-size', '14pt');
                        $(win.document.body).find('th').css('background-color', '#f8f9fa').css('font-weight', 'bold');
                        $(win.document.body).find('table').before(`
                            <div style="text-align: center; font-size: 10pt; font-style: italic; margin-bottom: 10px;">
                                (ICT Division, BCIC)
                            </div>
                        `);
                                // Add footer at the bottom of the page
                        $(win.document.body).append(`
                            <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10pt; font-style: italic; padding: 10px;">
                                Design and Developed by ICT Division, BCIC
                            </div>
                        `);

                        // Adjust margins to prevent footer overlap
                        $(win.document.body).css('margin-bottom', '10px');
                                    }
                                }
            ],
            drawCallback: function () {
                // Insert division filter if not already present
                if ($('#division_filter').length === 0) {
                    let divisionFilterHtml = `
                        <label style="margin-left: 10px;">
                            <select id="division_filter" class="form-select form-select-sm" style="width:200px; display:inline-block; margin-left: 10px;">

                                <option value="">All Divisions</option>
                                <option value="Chairman Secretariat">Chairman Secretariat</option>
                                <option value="Director (Commercial)">Director (Commercial)</option>
                                <option value="Director (Finance)">Director (Finance)</option>
                                <option value="Director (P&I)">Director (P&I)</option>
                                <option value="Director (T&E)">Director (T&E)</option>
                                <option value="Director (P&R)">Director (P&R)</option>
                                <option value="Senior General Manager (Admin">Senior General Manager (Admin)</option>
                                <option value="Personnel Division">Personnel Division</option>
                                <option value="Accounts Division">Accounts Division</option>
                                <option value="Purchase Division">Purchase Division</option>
                                <option value="MIS Division">MIS Division</option>
                                <option value="MTS Division">MTS Division</option>
                                <option value="PRD">PRD</option>
                                <option value="PID">PID</option>
                                <option value="ICT Division">ICT Division</option>
                                <option value="RPD">RPD</option>
                                <option value="Marketing Division">Marketing Division</option>
                                <option value="Audit Division">Audit Division</option>
                                <option value="Finance Division">Finance Division</option>
                                <option value="EMD">EMD</option>
                                <option value="Planning Division">Planning Division</option>
                                <option value="Production Division">Production Division</option>
                                <option value="CSD">CSD</option>
                                <option value="Legal Affairs Department">Legal Affairs Department</option>
                                <option value="Board & Co-ordination Department">Board & Co-ordination Department</option>
                                <option value="Company Affairs">Company Affairs</option>
                                <option value="PDD">PDD</option>
                                <option value="Construction Division">Construction Division</option>
                            </select>
                        </label>`;
                    $(".dataTables_filter").append(divisionFilterHtml);

                    // Re-apply previously selected value
                    $('#division_filter').val($('#division_filter option').filter(function () {
                        return $(this).text() === selectedDivisionName;
                    }).val());
                }

                // Attach event handler for division filter
                $('#division_filter').off('change').on('change', function () {
                    let selectedDivision = $(this).val();
                    selectedDivisionName = $(this).find('option:selected').text();
                    $('#recordsTable').DataTable().destroy();
                    loadTable($('#start_date').val(), $('#end_date').val(), selectedDivision);
                });
            },
            initComplete: function () {
                $('.dt-buttons').addClass('btn-group');
                $('.dt-button').removeClass('dt-button').addClass('btn');
            }
        });
    }

    // Initial load
    loadTable();

    // Filter button
    $('#filter').click(function () {
        selectedDivisionName = $('#division_filter option:selected').text();
        $('#recordsTable').DataTable().destroy();
        loadTable($('#start_date').val(), $('#end_date').val(), $('#division_filter').val());
    });

    // Reset button
    $('#reset').click(function () {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#division_filter').val('');
        selectedDivisionName = 'All Divisions';
        $('#recordsTable').DataTable().destroy();
        loadTable();
    });
});

</script>

</body>
</html>
