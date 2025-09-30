<?php
session_name('dfms');
session_start();
include('../include/header_index.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$table = $_SESSION['username'];

// Factory title mapping
$factories = [
    'sfcl'   => 'Shahjalal Fertilizer Company Ltd. (SFCL)',
    'jfcl'   => 'Jamuna Fertilizer Company Ltd. (JFCL)',
    'afccl'  => 'Ashuganj Fertilizer Company Ltd. (AFCCL)',
    'gpfplc' => 'Ghorashal Polash Fertilizer PLC (GPFPLC)',
    'cufl'   => 'Chittagong Urea Fertilizer Ltd. (CUFL)',
    'tspcl'  => 'TSP Complex Limited (TSPCL)',
    'dapfcl' => 'DAP Fertilizer Company Limited (DAPFCL)',
    'bisf'   => 'Bangladesh Insulator & Sanitaryware Factory Ltd.(BISFL)',
    'cccl'   => 'Chatak Cement Company Limited (CCCL)',
    'ugsf'   => 'Osmania Glass Sheet Factory Limited (UGSFL)',
    'kpml'   => 'Karnaphuli Paper Mills Limited (KPML)',
];

$table1 = $factories[$table] ?? '';

// Save posted dates into session
if (isset($_POST['hit'])) {
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date']   = $_POST['end_date'];
}
?>

<style>
/* Default hidden elements */
.print-only {
    display: none;
    text-align: center;
    margin-bottom: .5rem;
}

/* Print styles */
@media print {
    body { margin: 0; padding: 0; }

    .print-only {
        display: block !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    #search-btn, #login-btn, #print-btn, #logout { display: none !important; }

    #print-content, #print-content,#cardborder, .card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;

    }

    #print-content { margin-top: 5px; }
    #table_content { font-size: 12px; }
}

/* Keep date column in single line */
td.date-nowrap, th.date-nowrap { white-space: nowrap !important; }
</style>

<div class="container-fluid">
    <div class="row my-2 align-items-center">  
        <!-- Search Form -->
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <form class="row g-2 align-items-center" action="" method="post">
                <div class="col-12 col-sm-6">
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-12 col-sm-6">
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 w-sm-auto" id="search-btn" name="hit">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>     
        </div>

        <!-- Action Buttons -->
        <div class="col-12 col-md-6 text-md-end text-center mt-2 mt-md-0">     
            <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                <a class="btn btn-primary" id="login-btn" href="urea_form.php">
                    <i class="fa fa-arrow-left"></i> Previous Page
                </a> 
                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn">
                    <i class="fa fa-print"></i> Print
                </button>
                <a class="btn btn-danger" href="logout.php" id="logout">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div> 

    <?php error_reporting(E_ALL ^ E_NOTICE); ?>

    <?php if(isset($_POST['hit'])): ?>
    <div class="print-only">
        <!-- <img src="../images/logo.png" alt="Factory Logo" style="height:80px;"><br> -->
        <h3><?php echo $table1; ?></h3>
        <p>
            Date Range: <?php echo htmlspecialchars($_POST['start_date']); ?> 
            to <?php echo htmlspecialchars($_POST['end_date']); ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow border border-success border-2" id="cardborder">
                <div class="card-header">
                    <h4 class="text-center text-uppercase text-muted">
                        <b>Daily Production & Plant Status Report</b>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center small" id="table_content">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Factory</th>
                                    <th class="date-nowrap">Date</th>                                         
                                    <th>Product</th>    
                                    <th>Unit</th>       
                                    <th>Daily (MT)</th>
                                    <th>Monthly (MT)</th>
                                    <th>Yearly (MT)</th>
                                    <th>Production Target (MT)</th>
                                    <th>Due (MT)</th>
                                    <th>Monthly Target (MT)</th>
                                    <th>Due (MT)</th>
                                    <th>Plant Load (%)</th>                             
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php include('report_query.php'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end text-muted">
                    <i>Design & Developed By ICT Division, BCIC.</i>
                </div>
            </div>  
        </div> 
    </div>
</div>

<?php include('../include/footer.php'); ?>
