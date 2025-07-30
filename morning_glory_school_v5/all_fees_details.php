<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "morning_glory_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $fees = $conn->query("SELECT * FROM fee_mgtm_tbl ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Fee Management Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
            .table-responsive { overflow: visible !important; }
        }
        .action-buttons {
            white-space: nowrap;
        }
        .fee-details {
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .highlight-paid {
            background-color: #d4edda !important;
        }
        .highlight-due {
            background-color: #fff3cd !important;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa !important;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h4>Morning Glory Detailed Fee Management</h4>
        <div>
            <button onclick="window.print();" class="btn btn-primary me-2">
                <i class="fas fa-print"></i> Print Page
            </button>
            <a href="all_fees_mgtm.php" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Add Fee
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="feeTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Entry Date</th>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Roll No</th>
                    <th>Section</th>
                    <th>Admission Fee</th>
                    <th>Tuition Fee</th>
                    <th>Exam Fee</th>
                    <th>Board Reg Fee</th>
                    <th>Scholarship</th>
                    <th>Transfer Fee</th>
                    <th>Late Fee</th>
                    <th>Annual Session</th>
                    <th>Books</th>
                    <th>Khata</th>
                    <th>Diary</th>
                    <th>Stationary</th>
                    <th>Poor Fund</th>
                    <th>Others</th>
                    <th>Total</th>
                    <th class="no-print">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fees as $fee): 
                    $total_due = $fee['total_due'];
                    $row_class = ($total_due == 0) ? 'highlight-paid' : 'highlight-due';
                ?>
                <tr class="<?= $row_class ?>">
                    <td><?= htmlspecialchars($fee['id']) ?></td>
                    <td><?= htmlspecialchars($fee['entry_date']) ?></td>
                    <td><?= htmlspecialchars($fee['reg_no']) ?></td>
                    <td><?= htmlspecialchars($fee['name']) ?></td>
                    <td><?= htmlspecialchars($fee['class']) ?></td>
                    <td><?= htmlspecialchars($fee['roll_no']) ?></td>
                    <td><?= htmlspecialchars($fee['section']) ?></td>
                    
                    <!-- Admission Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['admission_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['admission_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['admission_fee_due']) ?></small>
                    </td>
                    
                    <!-- Tuition Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['tution_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['tution_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['tution_fee_due']) ?></small>
                    </td>
                    
                    <!-- Exam Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['exam_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['exam_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['exam_fee_due']) ?></small>
                    </td>
                    
                    <!-- Board Reg Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['board_reg_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['board_reg_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['board_reg_fee_due']) ?></small>
                    </td>
                    
                    <!-- Scholarship -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['scholarship_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['scholarship_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['scholarship_fee_due']) ?></small>
                    </td>
                    
                    <!-- Transfer Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['transfer_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['transfer_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['transfer_fee_due']) ?></small>
                    </td>
                    
                    <!-- Late Fee -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['late_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['late_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['late_fee_due']) ?></small>
                    </td>
                    
                    <!-- Annual Session -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['annual_session_fee_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['annual_session_fee_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['annual_session_fee_due']) ?></small>
                    </td>
                    
                    <!-- Books -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['books_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['books_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['books_due']) ?></small>
                    </td>
                    
                    <!-- Khata -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['khata_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['khata_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['khata_due']) ?></small>
                    </td>
                    
                    <!-- Diary -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['diary_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['diary_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['diary_due']) ?></small>
                    </td>
                    
                    <!-- Stationary -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['stationary_printing_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['stationary_printing_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['stationary_printing_due']) ?></small>
                    </td>
                    
                    <!-- Poor Fund -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['poor_fund_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['poor_fund_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['poor_fund_due']) ?></small>
                    </td>
                    
                    <!-- Others -->
                    <td class="fee-details">
                        <small>Actual: <?= htmlspecialchars($fee['others_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['others_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['others_due']) ?></small>
                    </td>
                    
                    <!-- Totals -->
                    <td class="total-row">
                        <small>Actual: <?= htmlspecialchars($fee['total_actual']) ?></small><br>
                        <small>Paid: <?= htmlspecialchars($fee['total_pay']) ?></small><br>
                        <small>Due: <?= htmlspecialchars($fee['total_due']) ?></small>
                    </td>
                    
                    <td class="action-buttons no-print">
                        <button class="btn btn-sm btn-info me-1 print-receipt-btn" data-fee='<?= json_encode($fee) ?>'>
                            <i class="fas fa-print"></i>
                        </button>
                        <a href="edit_all_fees_mgtm.php?id=<?= $fee['id'] ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-edit"></i>
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
        <h5 class="modal-title">Detailed Fee Receipt</h5>
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
                },
                customize: function (win) {
                    $(win.document.body).find('table').addClass('display').css('font-size', '8px');
                    $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                        $(this).css('background-color','#ffffff');
                    });
                    $(win.document.body).find('tr:nth-child(even) td').each(function(index){
                        $(this).css('background-color','#f2f2f2');
                    });
                }
            }
        ],
        responsive: true,
        scrollX: true,
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
                <p>Detailed Fee Receipt</p>
                <hr>
                <p><small>Receipt Date: ${new Date().toLocaleDateString()}</small></p>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Name:</strong> ${fee.name}</p>
                    <p><strong>Class:</strong> ${fee.class} (${fee.section})</p>
                    <p><strong>Roll No:</strong> ${fee.roll_no}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Reg No:</strong> ${fee.reg_no}</p>
                    <p><strong>Entry Date:</strong> ${fee.entry_date}</p>
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

        // Add all fee types to the receipt
        const feeTypes = [
            {name: 'Admission Fee', actual: fee.admission_fee_actual, pay: fee.admission_fee_pay, due: fee.admission_fee_due},
            {name: 'Tuition Fee', actual: fee.tution_fee_actual, pay: fee.tution_fee_pay, due: fee.tution_fee_due},
            {name: 'Exam Fee', actual: fee.exam_fee_actual, pay: fee.exam_fee_pay, due: fee.exam_fee_due},
            {name: 'Board Reg Fee', actual: fee.board_reg_fee_actual, pay: fee.board_reg_fee_pay, due: fee.board_reg_fee_due},
            {name: 'Scholarship', actual: fee.scholarship_fee_actual, pay: fee.scholarship_fee_pay, due: fee.scholarship_fee_due},
            {name: 'Transfer Fee', actual: fee.transfer_fee_actual, pay: fee.transfer_fee_pay, due: fee.transfer_fee_due},
            {name: 'Late Fee', actual: fee.late_fee_actual, pay: fee.late_fee_pay, due: fee.late_fee_due},
            {name: 'Annual Session', actual: fee.annual_session_fee_actual, pay: fee.annual_session_fee_pay, due: fee.annual_session_fee_due},
            {name: 'Books', actual: fee.books_actual, pay: fee.books_pay, due: fee.books_due},
            {name: 'Khata', actual: fee.khata_actual, pay: fee.khata_pay, due: fee.khata_due},
            {name: 'Diary', actual: fee.diary_actual, pay: fee.diary_pay, due: fee.diary_due},
            {name: 'Stationary', actual: fee.stationary_printing_actual, pay: fee.stationary_printing_pay, due: fee.stationary_printing_due},
            {name: 'Poor Fund', actual: fee.poor_fund_actual, pay: fee.poor_fund_pay, due: fee.poor_fund_due},
            {name: 'Others', actual: fee.others_actual, pay: fee.others_pay, due: fee.others_due}
        ];

        let totalPayment = 0;
        let totalDue = 0;

        feeTypes.forEach(feeType => {
            const payment = parseFloat(feeType.pay) || 0;
            const due = parseFloat(feeType.due) || 0;
            totalPayment += payment;
            totalDue += due;

            if (payment > 0 || due > 0) {
                html += `
                    <tr>
                        <td>${feeType.name}</td>
                        <td>${feeType.actual || '0'}</td>
                        <td>${feeType.pay || '0'}</td>
                        <td>${feeType.due || '0'}</td>
                    </tr>
                `;
            }
        });

        html += `
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th>${fee.total_actual || '0'}</th>
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
                        <p>Status: ${totalDue == 0 ? '<span class="text-success">Paid</span>' : '<span class="text-warning">Pending</span>'}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p>_________________________</p>
                        <p>Parent's Signature</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center small">
                <p>Generated on ${new Date().toLocaleString()}</p>
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
    
    document.body.innerHTML = `
        <div class="container mt-4">
            ${printContent}
        </div>
    `;
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reinitialize the modal after printing
    var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    receiptModal.show();
}
</script>
</body>
</html>