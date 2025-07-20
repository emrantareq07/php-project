<?php 
session_name('ict_main_records_db');
session_start();
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
$code = $_SESSION['code']; 

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <div class="col-md-3">
            <a href="dashboard.php" class="btn btn-primary float-end"><i class="fa fa-arrow-left"></i> Back</a>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script>
let selectedDivisionName = 'All Divisions';
let allDivisions = [
    "Chairman Secretariat", "Director (Commercial)", "Director (Finance)", "Director (P&I)", "Director (T&E)", "Director (P&R)",
    "Senior General Manager (Admin)", "Personnel Division", "Accounts Division", "Purchase Division", "MIS Division",
    "MTS Division", "PRD", "PID", "ICT Division", "RPD", "Marketing Division", "Audit Division", "Finance Division", "EMD",
    "Planning Division", "Production Division", "CSD", "Legal Affairs Department", "Board & Co-ordination Department",
    "Company Affairs", "PDD", "Construction Division"
];

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
                { data: 'requisition_no' }, { data: 'emp_id' }, { data: 'user_name' }, { data: 'division_dept' },
                { data: 'designation' }, { data: 'date' }, { data: 'product_name' }, { data: 'srm' },
                { data: 'srm_ref_no' }, { data: 'bill_or_challan_no' }, { data: 'remarks' }, { data: 'vendor_name' }
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
                    action: function(e, dt, node, config) {
                        if (selectedDivisionName === 'All Divisions') {
                            generateAllDivisionsPDF();
                        } else {
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn-info btn-sm',
                    customize: function (win) {
                        $(win.document.body).prepend(
                            `<div style="text-align:center;font-size:14pt;font-weight:bold;margin-bottom:10px;">
                                Division: ${selectedDivisionName}</div>
                             <div style="text-align:center;font-size:12pt;">ICT MAINTENANCE RECORDS</div>
                             <div style="text-align:center;font-size:10pt;margin-bottom:10px;">(ICT Division, BCIC)</div>`
                        );
                        $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                        $(win.document.body).append(
                            '<div style="text-align:center;font-size:10pt;margin-top:20px;">Design and Developed by ICT Division, BCIC</div>'
                        );
                    }
                }
            ],
            drawCallback: function () {
                if ($('#division_filter').length === 0) {
                    let divisionFilterHtml = `
                        <label style="margin-left: 10px;">
                            <select id="division_filter" class="form-select form-select-sm" style="width:200px;">
                                <option value="">All Divisions</option>
                                ${allDivisions.map(d => `<option value="${d}">${d}</option>`).join('')}
                            </select>
                        </label>`;
                    $(".dataTables_filter").append(divisionFilterHtml);
                    $('#division_filter').val($('#division_filter option').filter(function () {
                        return $(this).text() === selectedDivisionName;
                    }).val());
                }

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

    loadTable();

    $('#filter').on('click', function () {
        $('#recordsTable').DataTable().destroy();
        loadTable($('#start_date').val(), $('#end_date').val(), $('#division_filter').val());
    });

    $('#reset').on('click', function () {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#division_filter').val('');
        selectedDivisionName = 'All Divisions';
        $('#recordsTable').DataTable().destroy();
        loadTable();
    });

    async function generateAllDivisionsPDF() {
        const docContent = [];
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();

        for (const division of allDivisions) {
            const result = await $.post('fetch_records.php', {
                start_date: startDate,
                end_date: endDate,
                division: division,
                all_data: true
            }, null, 'json');

            const rows = result.data;
            if (!rows.length) continue;

            docContent.push({
                text: `${division}\nICT Maintenance Records\n\n`,
                alignment: 'center',
                fontSize: 12,
                bold: true,
                margin: [0, 0, 0, 10],
                pageBreak: docContent.length ? 'before' : ''
            });

            const tableBody = [
                ["Req No", "Emp ID", "User", "Department", "Designation", "Date", "Product", "SRM", "SRM Ref", "Bill/Challan", "Remarks", "Vendor"]
            ];

            rows.forEach(row => {
                tableBody.push([
                    row.requisition_no, row.emp_id, row.user_name, row.division_dept, row.designation, row.date,
                    row.product_name, row.srm, row.srm_ref_no, row.bill_or_challan_no, row.remarks, row.vendor_name
                ]);
            });

            docContent.push({
                table: {
                    headerRows: 1,
                    widths: Array(12).fill('*'),
                    body: tableBody
                },
                layout: 'lightHorizontalLines',
                fontSize: 9
            });
        }

        const docDefinition = {
            content: docContent,
            pageOrientation: 'landscape',
            pageSize: 'A4',
            defaultStyle: {
                fontSize: 9
            },
            footer: function (currentPage, pageCount) {
                return {
                    text: `Page ${currentPage} of ${pageCount}`,
                    alignment: 'right',
                    margin: [0, 0, 20, 0]
                };
            }
        };

        pdfMake.createPdf(docDefinition).download('ICT_Maintenance_Records_All_Divisions.pdf');
    }
});
</script>
</body>
</html>
