<?php
session_start();

// Check if the user is already logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username'];

// -----------------------------------------------------------------
// HELPERS
// -----------------------------------------------------------------
function h($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}

// -----------------------------------------------------------------
// HANDLE "SEND" -> INSERT INTO buffer_transaction
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_transaction'])) {
    $postAllotmentId = isset($_POST['import_allotment_id']) ? (int)$_POST['import_allotment_id'] : 0;
    $postAmount      = isset($_POST['amount']) ? trim($_POST['amount']) : '';
    $postMedium      = isset($_POST['medium']) ? trim($_POST['medium']) : '';
    $redirectPort    = isset($_POST['port']) ? $_POST['port'] : '';
    $redirectRef     = isset($_POST['ref_no']) ? $_POST['ref_no'] : '';

    if ($postAllotmentId <= 0 || $postAmount === '' || $postMedium === '') {
        $_SESSION['flash_message'] = 'Please provide both Amount and Medium before sending.';
        $_SESSION['flash_type']    = 'danger';
    } elseif (!is_numeric($postAmount)) {
        $_SESSION['flash_message'] = 'Amount must be numeric.';
        $_SESSION['flash_type']    = 'danger';
    } else {
        $amountVal = (float)$postAmount;
        $status    = 'pending'; // adjust default status to whatever your workflow expects

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO buffer_transaction
                (import_allotment_id, date, amount, medium, status, created_by, created_at, updated_at)
             VALUES (?, NOW(), ?, ?, ?, ?, NOW(), NOW())"
        );
        mysqli_stmt_bind_param($stmt, 'idsss', $postAllotmentId, $amountVal, $postMedium, $status, $logged_in_user);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_message'] = 'Transaction recorded successfully.';
            $_SESSION['flash_type']    = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to record transaction: ' . mysqli_error($conn);
            $_SESSION['flash_type']    = 'danger';
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: ?port=" . urlencode($redirectPort) . "&ref_no=" . urlencode($redirectRef));
    exit();
}

// Pick up flash message set on the previous (POST) request
$flashMessage = '';
$flashType = 'success';
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    $flashType    = $_SESSION['flash_type'];
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}

// -----------------------------------------------------------------
// STEP 1: SELECTED PORT
// -----------------------------------------------------------------
$selectedPort = isset($_GET['port']) ? trim($_GET['port']) : '';

// -----------------------------------------------------------------
// STEP 2: SELECTED REF NO (only meaningful once a port is chosen)
// -----------------------------------------------------------------
$selectedRefNo = isset($_GET['ref_no']) ? trim($_GET['ref_no']) : '';

// -----------------------------------------------------------------
// FETCH DISTINCT PORTS (from import_urea)
// -----------------------------------------------------------------
$portList = [];
$portQuery = mysqli_query($conn, "SELECT DISTINCT port_name FROM import_urea WHERE port_name IS NOT NULL AND port_name <> '' ORDER BY port_name ASC");
if ($portQuery) {
    while ($p = mysqli_fetch_assoc($portQuery)) {
        $portList[] = $p['port_name'];
    }
}

// -----------------------------------------------------------------
// FETCH REF NOs FOR THE SELECTED PORT
// -----------------------------------------------------------------
$refNoList = [];
if ($selectedPort !== '') {
    $stmt = mysqli_prepare($conn, "SELECT ref_no FROM import_urea WHERE port_name = ? ORDER BY ref_no DESC");
    mysqli_stmt_bind_param($stmt, 's', $selectedPort);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) {
        $refNoList[] = $r['ref_no'];
    }
    mysqli_stmt_close($stmt);

    // If the ref_no in the URL doesn't actually belong to this port, drop it
    if ($selectedRefNo !== '' && !in_array($selectedRefNo, $refNoList, true)) {
        $selectedRefNo = '';
    }
}

// -----------------------------------------------------------------
// FETCH import_urea RECORD FOR THE SELECTED PORT + REF NO
// -----------------------------------------------------------------
$ureaRecord = null;
if ($selectedPort !== '' && $selectedRefNo !== '') {
    $stmt = mysqli_prepare($conn, "SELECT * FROM import_urea WHERE port_name = ? AND ref_no = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'ss', $selectedPort, $selectedRefNo);
    mysqli_stmt_execute($stmt);
    $ureaRecord = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------
// FETCH import_allotment RECORDS FOR THE SELECTED REF NO
// -----------------------------------------------------------------
$allotRows = [];
$allotTotal = 0;
$allotCount = 0;
if ($ureaRecord) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM import_allotment WHERE ref_no = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 's', $selectedRefNo);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($a = mysqli_fetch_assoc($res)) {
        $allotRows[] = $a;
        $allotTotal += (float)$a['amount'];
        $allotCount++;
    }
    mysqli_stmt_close($stmt);
}
$ureaQuantity = $ureaRecord ? (float)$ureaRecord['quantity'] : null;
$allotBalance = $ureaQuantity !== null ? $ureaQuantity - $allotTotal : null;

// -----------------------------------------------------------------
// FETCH buffer_transaction TOTALS PER import_allotment_id (for Due calc)
// -----------------------------------------------------------------
$bufferTotals = []; // [allotment_id => sum of amounts already sent]
if (!empty($allotRows)) {
    $allotIds = array_map(fn($a) => (int)$a['id'], $allotRows);
    $placeholders = implode(',', array_fill(0, count($allotIds), '?'));
    $types = str_repeat('i', count($allotIds));

    $sql = "SELECT import_allotment_id, COALESCE(SUM(amount), 0) AS total_sent
            FROM buffer_transaction
            WHERE import_allotment_id IN ($placeholders)
            GROUP BY import_allotment_id";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$allotIds);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $bufferTotals[(int)$row['import_allotment_id']] = (float)$row['total_sent'];
    }
    mysqli_stmt_close($stmt);
}

// -----------------------------------------------------------------
// OVERVIEW: per-port summary (shown when no port is selected yet)
// -----------------------------------------------------------------
$portOverview = [];
if ($selectedPort === '') {
    $ov = mysqli_query($conn, "SELECT port_name, COUNT(*) AS shipment_count, COALESCE(SUM(quantity),0) AS total_quantity
                                FROM import_urea
                                WHERE port_name IS NOT NULL AND port_name <> ''
                                GROUP BY port_name
                                ORDER BY port_name ASC");
    if ($ov) {
        while ($row = mysqli_fetch_assoc($ov)) {
            $portOverview[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .summary-pill { padding: 5px 12px; border-radius: 14px; font-size: 13px; font-weight: 600; display: inline-block; margin-right: 6px; }
    .pill-neutral  { background: #e2e8f0; color: #2d3748; }
    .pill-balanced { background: #c6f6d5; color: #22543d; }
    .pill-pending  { background: #fefcbf; color: #744210; }
    .pill-over     { background: #fed7d7; color: #822727; }
    .overview-card { cursor: pointer; transition: box-shadow .15s ease; }
    .overview-card:hover { box-shadow: 0 4px 10px rgba(0,0,0,0.12); }
    #ref_no_select:disabled { background: #f0f0f0; }
    .due-badge { font-weight: 600; }
    .due-badge.due-zero { color: #22543d; }
    .due-badge.due-positive { color: #744210; }
    .due-badge.due-negative { color: #822727; }
    .live-due-preview { font-size: 12px; margin-top: 2px; display: block; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid text-center">
    <a class="navbar-brand" href="#">Smart Fertilizer Monitoring System (SFMS), BCIC.</a>
  </div>
</nav>

<div class="container-fluid p-1 my-1 border rounded">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-dark text-center">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b> Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6"><h3 class="text-center text-uppercase">Port Dashboard</h3></div>
        <div class="col-sm-6">
            <span class="float-end">
                <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Previous Page</a>
                <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
            </span>
        </div>
    </div>

    <div class="row"><div class="col-12"><hr></div></div>

    <?php if ($flashMessage !== ''): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= h($flashType); ?>"><?= h($flashMessage); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ============ STEP 1 & 2: PORT -> REF NO SELECTOR ============ -->
    <div class="row mb-3">
        <div class="col-12">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Port <span class="text-danger">*</span></label>
                    <select name="port" id="port_select" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select Port --</option>
                        <?php foreach ($portList as $p): ?>
                            <option value="<?= h($p); ?>" <?= ($p === $selectedPort) ? 'selected' : ''; ?>><?= h($p); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ref No <span class="text-danger">*</span></label>
                    <select name="ref_no" id="ref_no_select" class="form-select" onchange="this.form.submit()" <?= $selectedPort === '' ? 'disabled' : ''; ?>>
                        <option value="">-- Select Ref No --</option>
                        <?php foreach ($refNoList as $rn): ?>
                            <option value="<?= h($rn); ?>" <?= ($rn === $selectedRefNo) ? 'selected' : ''; ?>><?= h($rn); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <?php if ($selectedPort !== ''): ?>
                        <a href="" class="btn btn-secondary"><i class="fa fa-times"></i> Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if ($selectedPort === ''): ?>

        <!-- ============ OVERVIEW: ALL PORTS ============ -->
        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">Ports Overview</h5>
            </div>
            <?php if (empty($portOverview)): ?>
                <div class="col-12"><p class="text-muted">No import urea records found.</p></div>
            <?php else: ?>
                <?php foreach ($portOverview as $ov): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="?port=<?= urlencode($ov['port_name']); ?>" class="text-decoration-none text-dark">
                            <div class="card overview-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fa fa-anchor"></i> <?= h($ov['port_name']); ?></h6>
                                    <p class="card-text mb-1">Shipments: <b><?= (int)$ov['shipment_count']; ?></b></p>
                                    <p class="card-text mb-0">Total Quantity: <b><?= h($ov['total_quantity']); ?></b></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    <?php elseif ($selectedRefNo === ''): ?>

        <div class="row">
            <div class="col-12">
                <p class="text-muted">Select a Ref No above to view its shipment and allotment details for <b><?= h($selectedPort); ?></b>.</p>
                <?php if (empty($refNoList)): ?>
                    <p class="text-danger">No reference numbers found for this port.</p>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>

        <?php if (!$ureaRecord): ?>
            <div class="row"><div class="col-12">
                <div class="alert alert-danger">No import urea record found for port "<?= h($selectedPort); ?>" and ref no "<?= h($selectedRefNo); ?>".</div>
            </div></div>
        <?php else: ?>

            <!-- ============ IMPORT UREA DETAILS ============ -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white"><h5 class="mb-0"><i class="fa fa-ship"></i> Shipment Details — Ref No <?= h($ureaRecord['ref_no']); ?></h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2"><small class="text-muted">Port Name</small><br><b><?= h($ureaRecord['port_name']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Country</small><br><b><?= h($ureaRecord['country']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Ship Name</small><br><b><?= h($ureaRecord['ship_name']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Contractor</small><br><b><?= h($ureaRecord['contractor_name']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Purchase Order No</small><br><b><?= h($ureaRecord['pur_order_no']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Shipping Date</small><br><b><?= h($ureaRecord['shiping_date']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Port Arrival Date</small><br><b><?= h($ureaRecord['port_arraival_date']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Despatch Date</small><br><b><?= h($ureaRecord['despatch_date']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Quantity</small><br><b><?= h($ureaRecord['quantity']); ?></b></div>
                                <div class="col-md-3 mb-2"><small class="text-muted">Status</small><br><b><?= h($ureaRecord['status']); ?></b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ ALLOTMENT SUMMARY + LIST ============ -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0"><i class="fa fa-list"></i> Allotments for Ref No <?= h($selectedRefNo); ?></h5>
                            <div>
                                <span class="summary-pill pill-neutral">Allotments: <?= (int)$allotCount; ?></span>
                                <span class="summary-pill pill-neutral">Urea Quantity: <?= h($ureaQuantity); ?></span>
                                <span class="summary-pill pill-neutral">Total Allotted: <?= h($allotTotal); ?></span>
                                <?php if ($allotBalance == 0): ?>
                                    <span class="summary-pill pill-balanced">Fully Allotted ✓</span>
                                <?php elseif ($allotBalance < 0): ?>
                                    <span class="summary-pill pill-over">Over-allotted by <?= h(abs($allotBalance)); ?></span>
                                <?php else: ?>
                                    <span class="summary-pill pill-pending">Remaining: <?= h($allotBalance); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body p-0">

                            <?php // One hidden <form> per row; row inputs reference it via form="..." ?>
                            <?php foreach ($allotRows as $a): ?>
                                <form method="POST" action="" id="txn_form_<?= (int)$a['id']; ?>"></form>
                            <?php endforeach; ?>

                            <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th>Buffer Name</th>
                                        <th class="text-center">Amount</th>
                                        <th>Medium</th>
                                        <th>Created By</th>
                                        <th>Partial Delivery Amount (MT)</th>
                                        <th class="text-center">Due</th>
                                        <th>Medium</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($allotRows)): ?>
                                    <tr><td colspan="9" class="text-center text-muted py-3">No allotments found for this reference no.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($allotRows as $a):
                                        $formId       = 'txn_form_' . (int)$a['id'];
                                        $allotAmount  = (float)$a['amount'];
                                        $sentAmount   = $bufferTotals[(int)$a['id']] ?? 0;
                                        $dueAmount    = $allotAmount - $sentAmount;

                                        if ($dueAmount == 0) {
                                            $dueClass = 'due-zero';
                                        } elseif ($dueAmount > 0) {
                                            $dueClass = 'due-positive';
                                        } else {
                                            $dueClass = 'due-negative';
                                        }
                                    ?>
                                        <tr>
                                            <td class="text-center">#<?= (int)$a['id']; ?></td>
                                            <td><?= h($a['buffer_name']); ?></td>
                                            <td class="text-center"><?= h($a['amount']); ?></td>
                                            <td><?= h($a['mdium']); ?></td>
                                            <td><?= h($a['created_by']); ?></td>
                                            <td>
                                                <input
                                                    type="text"
                                                    name="amount"
                                                    form="<?= $formId; ?>"
                                                    class="form-control live-amount-input"
                                                    placeholder="Enter Amount"
                                                    data-allot-amount="<?= h($allotAmount); ?>"
                                                    data-sent-amount="<?= h($sentAmount); ?>"
                                                    data-due-target="due_<?= (int)$a['id']; ?>"
                                                    oninput="updateLiveDue(this)">
                                                <small class="live-due-preview text-muted" id="due_<?= (int)$a['id']; ?>_preview"></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="due-badge <?= $dueClass; ?>" id="due_<?= (int)$a['id']; ?>">
                                                    <?= h(number_format($dueAmount, 2)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <input type="text" name="medium" form="<?= $formId; ?>" class="form-control" placeholder="Enter Medium">
                                            </td>
                                            <td>
                                                <input type="hidden" name="import_allotment_id" value="<?= (int)$a['id']; ?>" form="<?= $formId; ?>">
                                                <input type="hidden" name="port" value="<?= h($selectedPort); ?>" form="<?= $formId; ?>">
                                                <input type="hidden" name="ref_no" value="<?= h($selectedRefNo); ?>" form="<?= $formId; ?>">
                                                <button type="submit" name="send_transaction" value="1" class="form-control btn-primary" form="<?= $formId; ?>"> Send</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>

<script>
// Live-preview: as the user types an amount, show what Due would become
// after this transaction is sent (allotment.amount - sent_so_far - typed_amount)
function updateLiveDue(input) {
    const allotAmount = parseFloat(input.dataset.allotAmount) || 0;
    const sentAmount  = parseFloat(input.dataset.sentAmount) || 0;
    const typedAmount = parseFloat(input.value) || 0;

    const previewEl = document.getElementById(input.dataset.dueTarget + '_preview');
    if (!previewEl) return;

    if (input.value.trim() === '') {
        previewEl.textContent = '';
        return;
    }

    const projectedDue = allotAmount - sentAmount - typedAmount;
    let label = 'Due after this: ' + projectedDue.toFixed(2);

    previewEl.textContent = label;
    previewEl.classList.remove('text-danger', 'text-warning', 'text-success');
    if (projectedDue < 0) {
        previewEl.classList.add('text-danger');   // would overpay
    } else if (projectedDue === 0) {
        previewEl.classList.add('text-success');  // fully settles
    } else {
        previewEl.classList.add('text-warning');  // still remaining
    }
}
</script>
</body>
</html>