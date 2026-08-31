<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $fees = $conn->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{
            --teal-deep:#0B3D3A;
            --teal-mid:#124F4A;
            --paper:#F6F3EC;
            --card:#FFFEFB;
            --ink:#1C2B2A;
            --coral:#E8583A;
            --coral-dim:#c8492e;
            --sage:#8FA6A0;
            --border:#E4DECB;
        }

        body{
            background:var(--paper);
            color:var(--ink);
            font-family:'Inter',sans-serif;
        }

        .page-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:22px;
            flex-wrap:wrap;
            gap:14px;
        }

        .page-head .eyebrow{
            font-size:12.5px;
            letter-spacing:0.12em;
            text-transform:uppercase;
            color:var(--coral);
            font-weight:600;
            margin-bottom:4px;
        }

        .page-head h4{
            font-family:'Fraunces',serif;
            font-weight:600;
            font-size:26px;
            margin:0;
            color:var(--ink);
        }

        /* Buttons re-themed, same classes/behavior as before */
        .btn-primary{
            background:var(--teal-mid);
            border-color:var(--teal-mid);
        }
        .btn-primary:hover{
            background:var(--teal-deep);
            border-color:var(--teal-deep);
        }

        .btn-success{
            background:var(--coral);
            border-color:var(--coral);
        }
        .btn-success:hover{
            background:var(--coral-dim);
            border-color:var(--coral-dim);
        }

        .btn-secondary{
            background:transparent;
            border-color:var(--border);
            color:var(--ink);
        }
        .btn-secondary:hover{
            background:var(--sage);
            border-color:var(--sage);
            color:#fff;
        }

        /* Table card wrapper */
        .table-card{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:16px;
            padding:22px;
            box-shadow:0 1px 3px rgba(11,61,58,0.06);
        }

        table.dataTable thead th{
            background:var(--teal-deep) !important;
            color:var(--paper) !important;
            border-color:var(--teal-deep) !important;
            font-size:12.5px;
            letter-spacing:0.04em;
            text-transform:uppercase;
            font-weight:600;
        }

        table.dataTable tbody tr:nth-child(even){
            background:rgba(18,79,74,0.035);
        }

        table.dataTable tbody td{
            vertical-align:middle;
            font-size:14px;
            border-color:var(--border) !important;
        }

        .action-buttons{
            white-space: nowrap;
        }
        .fee-details{
            white-space: pre-line;
        }

        /* DataTables controls */
        .dataTables_filter input{
            border-radius:8px !important;
            border:1.5px solid var(--border) !important;
        }
        .dataTables_filter input:focus{
            border-color:var(--teal-mid) !important;
            box-shadow:0 0 0 3px rgba(18,79,74,0.10) !important;
            outline:none;
        }
        .dataTables_length select{
            border-radius:8px !important;
            border:1.5px solid var(--border) !important;
        }

        .dt-buttons .btn{
            border-radius:8px !important;
            font-weight:600;
        }

        /* Modal restyle */
        .modal-content{
            border-radius:16px;
            border:none;
            overflow:hidden;
        }
        .modal-header{
            background:var(--teal-deep);
            color:var(--paper);
            border-bottom:none;
        }
        .modal-header .btn-close{
            filter:invert(1) brightness(2);
        }
        .modal-title{
            font-family:'Fraunces',serif;
            font-weight:600;
        }
        .modal-footer{
            border-top:1px solid var(--border);
            background:var(--paper);
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; background:#fff; }
            .table-card{ border:none; box-shadow:none; padding:0; }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="page-head no-print">
        <div>
            <div class="eyebrow">BCIC Patient Management System</div>
            <h4>Patient Management</h4>
        </div>
        <div>
           <!--  <a href="employee_management.php" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Add New Patient
            </a> -->
            <button onclick="window.print();" class="btn btn-primary me-2">
                <i class="fas fa-print"></i> Print Page
            </button>

            <a href="reports.php" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Reports
            </a>
            <a href="prescription_reports.php" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Prescription Reports
            </a>

            <a href="doctor_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="table-card">
        <table id="feeTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Emp Type</th>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Division</th>
                    <th>Section</th>                
                    <th class="no-print">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fees as $fee): ?>
                <tr>
                    <td><?= htmlspecialchars($fee['id']) ?></td>
                    <td><?= htmlspecialchars($fee['employee_type']) ?></td>
                    <td><?= htmlspecialchars($fee['emp_id']) ?></td>
                    <td><?= htmlspecialchars($fee['name']) ?></td>
                    <td><?= htmlspecialchars($fee['designation']) ?></td>
                    <td><?= htmlspecialchars($fee['division']) ?></td>
                    <td><?= htmlspecialchars($fee['section']) ?></td>                
                    <td class="no-print action-buttons">
                        <a href="prescription_entry.php?id=<?= urlencode($fee['emp_id']) ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-notes-medical"></i> Prescription
                        </a>
                        <a href="booked_med_entry.php?id=<?= urlencode($fee['emp_id']) ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-notes-medical"></i> Booked Medicine
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fee Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="receiptContent">
        <!-- Receipt will be injected here -->
      </div>
      <div class="modal-footer no-print">
        <button type="button" class="btn btn-primary" onclick="printReceiptContent()">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable with custom layout
    var table = $('#feeTable').DataTable({
        dom: '<"row"<"col-md-4"l><"col-md-4 text-center"B><"col-md-4"f>>rtip',
        buttons: [
            { 
                extend: 'excel', 
                className: 'btn btn-success btn-sm', 
                text: '<i class="fas fa-file-excel"></i> Excel',
                exportOptions: {
                    columns: ':not(.no-print)'
                }
            },
            { 
                extend: 'csv', 
                className: 'btn btn-secondary btn-sm', 
                text: '<i class="fas fa-file-csv"></i> CSV',
                exportOptions: {
                    columns: ':not(.no-print)'
                }
            },
            { 
                extend: 'pdf', 
                className: 'btn btn-danger btn-sm', 
                text: '<i class="fas fa-file-pdf"></i> PDF',
                exportOptions: {
                    columns: ':not(.no-print)'
                }
            },
            { 
                extend: 'print', 
                className: 'btn btn-primary btn-sm', 
                text: '<i class="fas fa-print"></i> Print Table',
                exportOptions: {
                    columns: ':not(.no-print)'
                }
            }
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        },
        initComplete: function() {
            // Custom styling after table initialization
            $('.dataTables_filter label').addClass('form-label');
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length label').addClass('form-label');
            $('.dataTables_length select').addClass('form-select form-select-sm');
        }
    });

    // Event delegation for print receipt buttons
    $('#feeTable').on('click', '.print-receipt-btn', function() {
        const feeData = $(this).data('fee');
        generateReceipt(feeData);
    });
});

function generateReceipt(fee) {
    try {
        let html = `
            <div class="text-center mb-4">
                <h4>Morning Glory School</h4>
                <p>Fee Receipt</p>
                <hr>
                <p><small>Receipt Date: ${new Date().toLocaleDateString()}</small></p>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Name:</strong> ${fee.name}</p>
                    <p><strong>Class:</strong> ${fee.class}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Reg No:</strong> ${fee.reg_no}</p>
                    <p><strong>Roll No:</strong> ${fee.roll_no}</p>
                </div>
            </div>
            <table class="table table-bordered mt-2">
                <thead class="table-light">
                    <tr>
                        <th>Fee Type</th>
                        <th>Actual Fee</th>
                        <th>Payment</th>
                        <th>Due</th>
                    </tr>
                </thead>
                <tbody>
        `;

        const feeTypes = fee.fee_types.split(',');
        const actualFees = fee.actual_fee.split(',');
        const payments = fee.payment.split(',');
        const dues = fee.due.split(',');

        let totalPayment = 0;
        let totalDue = 0;

        for (let i = 0; i < feeTypes.length; i++) {
            const payment = parseFloat(payments[i]) || 0;
            const due = parseFloat(dues[i]) || 0;
            totalPayment += payment;
            totalDue += due;

            html += `
                <tr>
                    <td>${feeTypes[i] || '-'}</td>
                    <td>${actualFees[i] || '0'}</td>
                    <td>${payments[i] || '0'}</td>
                    <td>${dues[i] || '0'}</td>
                </tr>
            `;
        }

        html += `
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>${totalPayment.toFixed(2)}</th>
                        <th>${totalDue.toFixed(2)}</th>
                    </tr>
                </tfoot>
            </table>
            <div class="mt-4 pt-4 border-top">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <p>_________________________</p>
                        <p>Cashier's Signature</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p></p>
                        
                    </div>
                    <div class="col-md-4 text-center">
                        <p>_________________________</p>
                        <p>Parent's Signature</p>
                    </div>
                </div>
            </div>
        `;

        $('#receiptContent').html(html);
        var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        receiptModal.show();
    } catch (error) {
        console.error("Error generating receipt:", error);
        alert("Error generating receipt. Please try again.");
    }
}

function printReceiptContent() {
    const printContent = document.getElementById('receiptContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reinitialize the modal after printing
    var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    receiptModal.show();
}
</script>
</body>
</html>