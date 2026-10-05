<?php
// session_start();
// if (!isset($_SESSION['username'])) { header("Location: ../index.php"); exit(); }

require_once('../db/db.php');

function h($val) {
    return htmlspecialchars(trim((string)($val ?? '')), ENT_QUOTES, 'UTF-8');
}
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}
function toNullIfEmpty($val) {
    $val = trim($val ?? '');
    return $val === '' ? null : $val;
}

$message = '';
$messageType = ''; // success | error

// ---------------------------------------------------------------
// OFFICE / BUFFER LIST — every row in office_tbl (id + display name)
// ---------------------------------------------------------------
$OFFICE_ROWS = [];
$officeRes = mysqli_query($conn, "
    SELECT id, office_name, buffer_name
    FROM office_tbl
    WHERE buffer_name IS NOT NULL AND buffer_name != ''
    ORDER BY buffer_name ASC
");
if ($officeRes) {
    while ($o = mysqli_fetch_assoc($officeRes)) {
        $OFFICE_ROWS[] = $o;
    }
}

// ---------------------------------------------------------------
// REFERENCE NO LIST — distinct ref_no already used in monthly_demand,
// for the "edit existing" dropdown. A separate "Create New" option
// lets the user type a brand-new ref_no instead.
// ---------------------------------------------------------------
$REF_NO_LIST = [];
$refRes = mysqli_query($conn, "
    SELECT DISTINCT ref_no FROM monthly_demand
    WHERE ref_no IS NOT NULL AND ref_no != ''
    ORDER BY ref_no DESC
");
if ($refRes) {
    while ($rr = mysqli_fetch_assoc($refRes)) {
        $REF_NO_LIST[] = $rr['ref_no'];
    }
}

// ref_no -> date lookup, so picking an existing ref_no can auto-fill the
// Date field (one date per ref_no batch; MAX() covers stray duplicates).
$REF_NO_DATE_MAP = [];
$refDateRes = mysqli_query($conn, "
    SELECT ref_no, MAX(date) AS date
    FROM monthly_demand
    WHERE ref_no IS NOT NULL AND ref_no != ''
    GROUP BY ref_no
");
if ($refDateRes) {
    while ($rd = mysqli_fetch_assoc($refDateRes)) {
        $REF_NO_DATE_MAP[$rd['ref_no']] = $rd['date'];
    }
}

// ---------------------------------------------------------------
// HANDLE BULK SAVE (one submit -> one row per buffer, upsert)
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_demand_bulk') {

    $bulk_date   = toNullIfEmpty($_POST['date'] ?? '');
    $bulk_ref_no = toNullIfEmpty($_POST['ref_no'] ?? '');

    $officeIds    = $_POST['office_tbl_id']  ?? [];
    $dAmounts     = $_POST['d_amount']       ?? [];
    $additions    = $_POST['addition']       ?? [];
    $substrations = $_POST['substration']    ?? [];

    $validationError = '';

    if ($bulk_date === null || !DateTime::createFromFormat('Y-m-d', $bulk_date)) {
        $validationError = "A valid Date is required.";
    } elseif ($bulk_ref_no === null) {
        $validationError = "Reference No is required.";
    }

    if ($validationError !== '') {
        $message = $validationError;
        $messageType = 'error';
    } else {
        $savedCount = 0;
        $failedCount = 0;

        $findStmt = mysqli_prepare($conn, "SELECT id FROM monthly_demand WHERE office_tbl_id = ? AND ref_no = ? AND date = ?");
        $insStmt  = mysqli_prepare($conn, "
            INSERT INTO monthly_demand (office_tbl_id, ref_no, date, d_amount, addition, substration, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $updStmt  = mysqli_prepare($conn, "
            UPDATE monthly_demand
            SET d_amount = ?, addition = ?, substration = ?, updated_at = NOW()
            WHERE id = ?
        ");

        foreach ($officeIds as $idx => $rawOfficeId) {
            $office_tbl_id = (int)$rawOfficeId;
            if ($office_tbl_id <= 0) continue;

            $d_amount    = isset($dAmounts[$idx]) ? trim($dAmounts[$idx]) : '';
            $addition    = isset($additions[$idx]) && $additions[$idx] !== '' ? $additions[$idx] : 0;
            $substration = isset($substrations[$idx]) && $substrations[$idx] !== '' ? $substrations[$idx] : 0;

            // Skip buffers left blank — only save rows the user actually filled in.
            if ($d_amount === '' || !is_numeric($d_amount)) {
                continue;
            }

            // Look up an existing row for this buffer + date + ref_no
            mysqli_stmt_bind_param($findStmt, 'iss', $office_tbl_id, $bulk_ref_no, $bulk_date);
            mysqli_stmt_execute($findStmt);
            $findRes = mysqli_stmt_get_result($findStmt);
            $existing = mysqli_fetch_assoc($findRes);

            if ($existing) {
                $existingId = (int)$existing['id'];
                mysqli_stmt_bind_param($updStmt, 'dddi', $d_amount, $addition, $substration, $existingId);
                if (mysqli_stmt_execute($updStmt)) {
                    $savedCount++;
                } else {
                    $failedCount++;
                }
            } else {
                mysqli_stmt_bind_param($insStmt, 'issddd', $office_tbl_id, $bulk_ref_no, $bulk_date, $d_amount, $addition, $substration);
                if (mysqli_stmt_execute($insStmt)) {
                    $savedCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        mysqli_stmt_close($findStmt);
        mysqli_stmt_close($insStmt);
        mysqli_stmt_close($updStmt);

        if ($failedCount > 0) {
            $message = "$savedCount entr" . ($savedCount === 1 ? 'y' : 'ies') . " saved, $failedCount failed.";
            $messageType = $savedCount > 0 ? 'success' : 'error';
        } elseif ($savedCount > 0) {
            $message = "$savedCount demand entr" . ($savedCount === 1 ? 'y' : 'ies') . " saved successfully.";
            $messageType = 'success';
        } else {
            $message = "No demand amounts were entered — nothing was saved.";
            $messageType = 'error';
        }
    }

    // keep context after saving
    $_GET['date']          = $bulk_date;
    $_GET['ref_no_select'] = $bulk_ref_no;
}

// ---------------------------------------------------------------
// HANDLE DELETE (single row, from the list below)
// ---------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'delete_demand' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Look up date/ref_no first, only so the page can stay on the same context after deleting.
    $ctxDate = '';
    $ctxRef  = '';
    $lookupStmt = mysqli_prepare($conn, "SELECT date, ref_no FROM monthly_demand WHERE id = ?");
    mysqli_stmt_bind_param($lookupStmt, 'i', $id);
    mysqli_stmt_execute($lookupStmt);
    $lookupRow = mysqli_stmt_get_result($lookupStmt)->fetch_assoc();
    mysqli_stmt_close($lookupStmt);
    if ($lookupRow) {
        $ctxDate = $lookupRow['date'];
        $ctxRef  = $lookupRow['ref_no'];
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM monthly_demand WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Demand entry #$id deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Delete failed: " . mysqli_stmt_error($stmt);
        $messageType = 'error';
    }
    mysqli_stmt_close($stmt);

    if ($ctxDate !== '') $_GET['date']          = $ctxDate;
    if ($ctxRef  !== '') $_GET['ref_no_select'] = $ctxRef;
}

// ---------------------------------------------------------------
// SELECTED CONTEXT (date + ref_no)
// The Reference No comes from the dropdown, unless "__new__" was
// chosen, in which case it comes from the accompanying text field.
// ---------------------------------------------------------------
$selectedDate    = isset($_GET['date']) ? trim($_GET['date']) : '';
$refNoSelectVal  = isset($_GET['ref_no_select']) ? trim($_GET['ref_no_select']) : '';
$newRefNoVal     = isset($_GET['new_ref_no']) ? trim($_GET['new_ref_no']) : '';
$isCreatingNew   = ($refNoSelectVal === '__new__');
$selectedRefNo   = $isCreatingNew ? $newRefNoVal : $refNoSelectVal;
$hasContext      = ($selectedDate !== '' && $selectedRefNo !== '');

// ---------------------------------------------------------------
// FETCH monthly_demand ROWS FOR SELECTED DATE + REF_NO
// keyed by office_tbl_id so the bulk table can pre-fill existing values
// ---------------------------------------------------------------
$demandByOffice = [];   // office_tbl_id => row
$demandRows     = [];   // flat list, for the list/delete table below

$totalDAmount = 0.0;
$totalAddition = 0.0;
$totalSubstration = 0.0;

if ($hasContext) {
    $sql = "
        SELECT md.*, o.buffer_name, o.office_name
        FROM monthly_demand md
        LEFT JOIN office_tbl o ON o.id = md.office_tbl_id
        WHERE md.date = ? AND md.ref_no = ?
        ORDER BY md.id DESC
    ";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $selectedDate, $selectedRefNo);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            $demandRows[] = $row;
            $demandByOffice[(int)$row['office_tbl_id']] = $row;
            $totalDAmount     += (float)$row['d_amount'];
            $totalAddition    += (float)$row['addition'];
            $totalSubstration += (float)$row['substration'];
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BCIC SFMS - Set Monthly Demand</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background:#f4f6fa; }
        .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
        .table thead th {
            background:#1e293b; color:#fff; font-size:12px;
            text-transform:uppercase; letter-spacing:.5px;
            vertical-align:middle; text-align:center;
        }
        .table td { vertical-align:middle; font-size:13px; text-align:center; }
        .text-left { text-align:left !important; }
        .context-bar {
            background:#f7f5ff;
            border:1px solid #e2d9fb;
            border-radius:10px;
            padding:14px 16px;
        }
        .empty-hint { text-align:center; color:#888; padding:30px 10px; font-size:14px; }
        .bulk-input { min-width:100px; }
        .prefilled-row { background:#f0fdf4; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa fa-leaf"></i> Digital Fertilizer Monitoring System (DFMS), BCIC</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fa fa-plus-circle text-primary"></i> Set Monthly Demand</h3>
        <a href="mkt_dashboard.php" class="btn btn-outline-secondary float-end"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout </a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger'; ?>">
            <?= h($message); ?>
        </div>
    <?php endif; ?>

    <!-- ============ CONTEXT SELECTOR: DATE + REF NO ============ -->
    <div class="card p-3 mb-4">
        <form method="GET" action="set_monthly_demand.php" class="row g-3 align-items-end context-bar">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Date</label>
                <input type="date" name="date" id="dateInput" class="form-control" value="<?= h($selectedDate); ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Reference No</label>
                <select name="ref_no_select" id="refNoSelect" class="form-select"
                        onchange="handleRefNoChange(this);"
                        required>
                    <option value="">-- Select Reference No --</option>
                    <option value="__new__" <?= $isCreatingNew ? 'selected' : ''; ?>>+ Create New Reference No</option>
                    <?php foreach ($REF_NO_LIST as $rn):
                        $sel = (!$isCreatingNew && $rn === $selectedRefNo) ? 'selected' : '';
                    ?>
                        <option value="<?= h($rn); ?>" <?= $sel; ?>><?= h($rn); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3" id="newRefNoWrap" style="display: <?= $isCreatingNew ? 'block' : 'none'; ?>;">
                <label class="form-label fw-semibold">New Reference No</label>
                <input type="text" name="new_ref_no" class="form-control" placeholder="Enter new Ref No" value="<?= h($newRefNoVal); ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Load</button>
                <?php if ($hasContext): ?>
                    <a href="set_monthly_demand.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if (!$hasContext): ?>

        <div class="card p-3">
            <div class="empty-hint">Select a Date and a Reference No above, then click "Load" to set demand for all buffers.</div>
        </div>

    <?php else: ?>

        <!-- ============ BULK ENTRY: ALL BUFFERS, ONE SAVE ============ -->
        <div class="card p-3 mb-4">
            <h5 class="fw-bold mb-3">
                Set Demand for All Buffers & Factory
                <small class="text-muted fw-normal">
                    — <?= h($selectedRefNo); ?> on <?= h($selectedDate); ?>
                </small>
            </h5>

            <?php if (empty($OFFICE_ROWS)): ?>
                <div class="empty-hint">No buffers found in office_tbl.</div>
            <?php else: ?>
                <form method="POST" action="set_monthly_demand.php">
                    <input type="hidden" name="action" value="save_demand_bulk">
                    <input type="hidden" name="date" value="<?= h($selectedDate); ?>">
                    <input type="hidden" name="ref_no" value="<?= h($selectedRefNo); ?>">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Buffer Name</th>
                                    <th>Demand Amount</th>
                                    <th>Addition</th>
                                    <th>Substration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($OFFICE_ROWS as $i => $o):
                                    $existing = $demandByOffice[(int)$o['id']] ?? null;
                                    $rowClass = $existing ? 'prefilled-row' : '';
                                ?>
                                    <tr class="<?= $rowClass; ?>">
                                        <td><?= $i + 1; ?></td>
                                        <td class="text-left fw-bold text-primary">
                                            <?= dash($o['buffer_name'] ?: $o['office_name']); ?>
                                            <input type="hidden" name="office_tbl_id[]" value="<?= (int)$o['id']; ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="d_amount[]"
                                                   class="form-control bulk-input"
                                                   value="<?= $existing ? h($existing['d_amount']) : ''; ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="addition[]"
                                                   class="form-control bulk-input"
                                                   value="<?= $existing ? h($existing['addition']) : '0'; ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="substration[]"
                                                   class="form-control bulk-input"
                                                   value="<?= $existing ? h($existing['substration']) : '0'; ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save All
                        </button>
                        <small class="text-muted ms-2">Rows with a green background already have a saved entry — leave Demand Amount blank on any row to skip it.</small>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- ============ SAVED ENTRIES (view / delete) ============ -->
        <div class="card p-3">
            <!-- <h5 class="fw-bold mb-3">Saved Entries</h5> -->
            <h5 class="fw-bold mb-3">
                Saved Demand for All Buffers & Factory
                <small class="text-muted fw-normal">
                    — <?= h($selectedRefNo); ?> on <?= h($selectedDate); ?>
                </small>
            </h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Buffer Name</th>
                            <th>Demand Amount</th>
                            <th>Addition</th>
                            <th>Substration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($demandRows)): ?>
                            <tr>
                                <td colspan="6" class="text-muted">No demand entries saved yet for this Date / Ref No.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($demandRows as $i => $d): ?>
                                <tr>
                                    <td><?= $i + 1; ?></td>
                                    <td class="text-left fw-bold text-primary">
                                        <?= dash($d['buffer_name'] ?: $d['office_name']); ?>
                                    </td>
                                    <td><?= h(number_format((float)$d['d_amount'], 2)); ?></td>
                                    <td><?= h(number_format((float)$d['addition'], 2)); ?></td>
                                    <td><?= h(number_format((float)$d['substration'], 2)); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-danger"
                                           href="set_monthly_demand.php?action=delete_demand&id=<?= (int)$d['id']; ?>&date=<?= urlencode($selectedDate); ?>&ref_no_select=<?= urlencode($selectedRefNo); ?>"
                                           onclick="return confirm('Delete demand entry #<?= (int)$d['id']; ?>? This cannot be undone.');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr class="fw-bold table-secondary">
                                <td colspan="2" class="text-end">Total =</td>
                                <td><?= number_format($totalDAmount, 2); ?></td>
                                <td><?= number_format($totalAddition, 2); ?></td>
                                <td><?= number_format($totalSubstration, 2); ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>

</div>

<script>
    // ref_no -> date lookup, built from monthly_demand, used to auto-fill
    // the Date field when an existing Reference No is picked.
    var refNoDateMap = <?= json_encode($REF_NO_DATE_MAP); ?>;

    function handleRefNoChange(selectEl) {
        var isNew = (selectEl.value === '__new__');
        document.getElementById('newRefNoWrap').style.display = isNew ? 'block' : 'none';

        if (!isNew && refNoDateMap.hasOwnProperty(selectEl.value)) {
            document.getElementById('dateInput').value = refNoDateMap[selectEl.value];
        }
    }
</script>
</body>
</html>