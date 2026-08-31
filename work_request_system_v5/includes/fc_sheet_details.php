<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$full_name    = $_SESSION['full_name'];
$emp_type     = $_SESSION['emp_type'];
$section      = $_SESSION['section'];
$division     = $_SESSION['division'];
$routine_role = $_SESSION['routine_role'];
$addl_role    = $_SESSION['addl_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Work Request System</title>     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 260px;
            --bg-light: #f8f9fa;
        }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
            z-index: 1000;
        }
        .main-content { margin-left: var(--sidebar-width); padding: 30px; }
        
        .nav-link {
            padding: 12px 25px;
            color: #444;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s;
            border-radius: 8px;
            margin: 4px 15px;
        }
        .nav-link:hover { background: #eef2ff; color: var(--primary-color); }
        .nav-link.active { background: var(--primary-color); color: white; }
        
        /* Card Styling */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }

        /* Modal Table Styling */
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .sticky-thead th { position: sticky; top: 0; background: #212529; z-index: 10; }
        
        @media print {
            .no-print, .sidebar, .btn-close, .modal-header { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
            .modal { position: relative; }
        }
    </style>
</head>
<body>

<div class="sidebar no-print">
    <div class="px-4 mb-4">
        <h5 class="fw-bold text-primary"><i class="bi bi-factory me-2"></i>BCIC ERP</h5>
        <hr>
        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Menu</small>
    </div>
    
    <nav>
        <a href="dashboard.php" class="nav-link active">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#fcModal">
            <i class="fas fa-calendar-check text-success"></i> <span>Generate FC</span>
        </a>
         <a href="my_fc_monthwise_report.php" class="nav-link">
                <i class="fas fa-file-invoice text-primary"></i> <span>My FC Report Monthwise</span>
            </a>

        <?php if ($emp_type === 'officer' && ($routine_role === 'section_head' || $routine_role === 'division_head' ) && $addl_role === 'fc_officer'): ?>
            <div class="px-4 mt-4 mb-2">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Reports</small>
            </div>
            <a href="fc_monthly_report.php" class="nav-link">
                <i class="fas fa-file-invoice text-primary"></i> <span>Monthly Report</span>
            </a>
            <a href="submitted_fc.php" class="nav-link">
                <i class="fas fa-check-double text-info"></i> <span>Submitted FC</span>
            </a>
        <?php endif; ?>

        <?php if ($emp_type === 'officer' && $routine_role === 'md_charge'  && $addl_role === 'md_charge'): ?>
          
            <a href="submitted_fc_all.php" class="nav-link">
                <i class="fas fa-check-double text-info"></i> <span>Submitted FC All</span>
            </a>
        <?php endif; ?>
        
        <div class="mt-5 px-3">
            <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-pill btn-sm">Logout</a>
        </div>
    </nav>
</div>

<div class="main-content">
    <div class="container-fluid">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Welcome, <?= htmlspecialchars($full_name) ?></h3>
                <p class="text-muted"><?= $section ?> | <?= $division ?></p>
            </div>
            <div class="text-end no-print">
                <span class="badge bg-primary px-3 py-2"><?= date("d M Y") ?></span>
            </div>
        </header>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card bg-white p-3 border-start border-primary border-4" data-bs-toggle="modal" data-bs-target="#fcModal" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Action</h6>
                            <h5 class="fw-bold mb-0">Create New FC</h5>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>

<div class="modal fade" id="fcModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Factory Work Request (FC) Sheet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">SELECT MONTH</label>
                            <select id="month" class="form-select border-primary shadow-sm">
                                <option value="">Choose...</option>
                                <?php 
                                    $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                                    foreach($months as $idx => $m) echo "<option value='".($idx+1)."'>$m</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">YEAR</label>
                            <input type="number" id="year" class="form-select border-primary shadow-sm" value="<?= date('Y') ?>">
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-primary px-4 fw-bold shadow-sm" onclick="generateTable()">
                                <i class="bi bi-gear-fill me-2"></i>Generate Sheet
                            </button>
                        </div>
                    </div>
                </div>

                <form id="fcForm">
                    <input type="hidden" name="month" id="formMonth">
                    <input type="hidden" name="year" id="formYear">

                    <div class="table-responsive rounded shadow-sm border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-thead text-white">
                                <tr>
                                    <th class="px-3">SL</th>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Hrs</th>
                                    <th>Remarks</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="fcTableBody" class="bg-white">
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar2-range display-6 d-block mb-2"></i>
                                        Select month and year to generate rows
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 no-print">
                        <div class="h5 mb-0 fw-bold">
                            Total Days: <span id="totalDays" class="badge bg-success shadow-sm">0</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="window.print()" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-printer me-2"></i>Print
                            </button>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i>Save Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function generateTable(){
    let month = document.getElementById("month").value;
    let year = document.getElementById("year").value;

    if(!month || !year){
        alert("Please select both Month and Year");
        return;
    }

    document.getElementById("formMonth").value = month;
    document.getElementById("formYear").value = year;

    let days = new Date(year, month, 0).getDate();
    let tbody = document.getElementById("fcTableBody");
    tbody.innerHTML = "";

    for(let i=1; i<=days; i++){
        let date = year + "-" + ("0" + month).slice(-2) + "-" + ("0" + i).slice(-2);
        let row = `
            <tr>
                <td class="fw-bold text-muted px-3">${i}</td>
                <td><input type="date" name="date[]" value="${date}" class="form-control form-control-sm border-0 bg-light" readonly></td>
                <td><input type="time" name="time_from[]" value="14:00" class="form-control form-control-sm border-primary" onchange="calcHours(this)"></td>
                <td><input type="time" name="time_to[]" value="17:00" class="form-control form-control-sm border-primary" onchange="calcHours(this)"></td>
                <td><input type="text" name="total_hours[]" value="3.00" class="form-control form-control-sm total text-center fw-bold border-0 bg-light shadow-none" readonly></td>
                <td><input type="text" name="remarks[]" placeholder="Task details..." class="form-control form-control-sm border-0 border-bottom rounded-0"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="deleteRow(this)">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </td>
            </tr>`;
        tbody.innerHTML += row;
    }
    document.getElementById("totalDays").innerText = days;
}

function calcHours(el){
    let row = el.closest("tr");
    let from = row.querySelector('[name="time_from[]"]').value;
    let to = row.querySelector('[name="time_to[]"]').value;

    if(from && to){
        let start = new Date("2000-01-01T"+from);
        let end = new Date("2000-01-01T"+to);
        
        // Handle overnight shifts if necessary, otherwise standard:
        let diff = (end - start) / (1000 * 60 * 60);
        if(diff < 0) diff += 24; // Simple wrap-around logic

        row.querySelector(".total").value = diff.toFixed(2);
    }
}

function deleteRow(btn){
    btn.closest("tr").remove();
    renumberRows();
}

function renumberRows(){
    let rows = document.querySelectorAll("#fcTableBody tr");
    rows.forEach((row, index) => {
        row.cells[0].innerText = index + 1;
    });
    document.getElementById("totalDays").innerText = rows.length;
}

document.getElementById("fcForm").addEventListener("submit", function(e){
    e.preventDefault();
    let btn = e.target.querySelector('button[type="submit"]');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
    btn.disabled = true;

    let formData = new FormData(this);
    fetch("save_fc.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(err => {
        alert("Error saving data");
        btn.innerHTML = 'Save Data';
        btn.disabled = false;
    });
});
</script>
</body>
</html>