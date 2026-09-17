<?php
/**
 * pur_dashboard.php
 * Single-page CRUD interface for `import_urea` table in `sfms_db`
 */

// ---------------------------------------------------------------
// DB CONNECTION
// ---------------------------------------------------------------
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'sfms_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// ---------------------------------------------------------------
// HELPERS
// ---------------------------------------------------------------
function clean($conn, $val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}

function toNullIfEmpty($val) {
    $val = trim($val ?? '');
    return $val === '' ? null : $val;
}

// Port name options: label => value
$PORT_OPTIONS = [
    'Chittagong Port' => 'chittagong_port',
    'Mongla Port'      => 'mongla_port',
];

$message = '';
$messageType = ''; // success | error

// ---------------------------------------------------------------
// HANDLE CREATE / UPDATE
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {

    $id                 = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $ref_no             = toNullIfEmpty($_POST['ref_no']);
    $shiping_date       = toNullIfEmpty($_POST['shiping_date']);
    $country            = toNullIfEmpty($_POST['country']);
    $ship_name          = toNullIfEmpty($_POST['ship_name']);
    $contractor_name    = toNullIfEmpty($_POST['contractor_name']);
    $port_arraival_date = toNullIfEmpty($_POST['port_arraival_date']);
    $quantity           = toNullIfEmpty($_POST['quantity']);
    $port_name          = toNullIfEmpty($_POST['port_name']);
    $status             = toNullIfEmpty($_POST['status']);

    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE import_urea SET
                    ref_no = ?,
                    shiping_date = ?,
                    country = ?,
                    ship_name = ?,
                    contractor_name = ?,
                    port_arraival_date = ?,
                    quantity = ?,
                    port_name = ?,
                    status = ?,
                    updated_at = NOW()
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                'sssssssssi',
                $ref_no, $shiping_date, $country, $ship_name, $contractor_name,
                $port_arraival_date, $quantity, $port_name, $status, $id
            );
            if ($stmt->execute()) {
                $message = "Record #$id updated successfully.";
                $messageType = 'success';
            } else {
                $message = "Update failed: " . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $messageType = 'error';
        }
    } else {
        // INSERT
        $sql = "INSERT INTO import_urea
                    (ref_no, shiping_date, country, ship_name, contractor_name,
                     port_arraival_date, quantity, port_name, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                'sssssssss',
                $ref_no, $shiping_date, $country, $ship_name, $contractor_name,
                $port_arraival_date, $quantity, $port_name, $status
            );
            if ($stmt->execute()) {
                $message = "New record added successfully (ID: {$conn->insert_id}).";
                $messageType = 'success';
            } else {
                $message = "Insert failed: " . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $messageType = 'error';
        }
    }
}

// ---------------------------------------------------------------
// HANDLE DELETE
// ---------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM import_urea WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = "Record #$id deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Delete failed: " . $stmt->error;
        $messageType = 'error';
    }
    $stmt->close();
}

// ---------------------------------------------------------------
// LOAD RECORD FOR EDIT
// ---------------------------------------------------------------
$editRow = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM import_urea WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editRow = $result->fetch_assoc();
    $stmt->close();
}

// ---------------------------------------------------------------
// SEARCH (optional simple filter)
// ---------------------------------------------------------------
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$statusFilter = isset($_GET['status_filter']) ? trim($_GET['status_filter']) : '';

// ---------------------------------------------------------------
// FETCH LIST  (base query: SELECT * FROM import_urea WHERE 1)
// ---------------------------------------------------------------
$sql = "SELECT * FROM import_urea WHERE 1";
$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND (ref_no LIKE ? OR ship_name LIKE ? OR contractor_name LIKE ?
                   OR port_name LIKE ? OR country LIKE ?)";
    $like = "%$search%";
    for ($i = 0; $i < 5; $i++) {
        $params[] = $like;
    }
    $types .= str_repeat('s', 5);
}

if ($statusFilter !== '') {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
    $types .= 's';
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$rows = $stmt->get_result();

// =================================================================
// IMPORT UREA ALLOTMENT  (import_allotment) — linked by ref_no
// =================================================================

// -----------------------------------------------------------------
// HANDLE ALLOTMENT CREATE / UPDATE
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_allotment') {

    $al_id         = isset($_POST['al_id']) ? (int)$_POST['al_id'] : 0;
    $al_ref_no     = toNullIfEmpty($_POST['al_ref_no']);
    $al_buffer     = toNullIfEmpty($_POST['buffer_name']);
    $al_amount     = toNullIfEmpty($_POST['amount']);
    $al_medium     = toNullIfEmpty($_POST['mdium']);
    $al_created_by = toNullIfEmpty($_POST['created_by']);

    $validationError = '';

    // ---- VALIDATION 1: no duplicate buffer for the same ref_no ----
    if ($validationError === '' && $al_ref_no !== null && $al_buffer !== null) {
        $dupSql = "SELECT id FROM import_allotment WHERE ref_no = ? AND buffer_name = ?";
        if ($al_id > 0) {
            $dupSql .= " AND id <> ?";
            $dupStmt = $conn->prepare($dupSql);
            $dupStmt->bind_param('ssi', $al_ref_no, $al_buffer, $al_id);
        } else {
            $dupStmt = $conn->prepare($dupSql);
            $dupStmt->bind_param('ss', $al_ref_no, $al_buffer);
        }
        $dupStmt->execute();
        $dupResult = $dupStmt->get_result();
        if ($dupResult->num_rows > 0) {
            $validationError = "Buffer \"$al_buffer\" has already been allotted for reference no \"$al_ref_no\". Duplicate buffer entries are not allowed.";
        }
        $dupStmt->close();
    }

    // ---- VALIDATION 2: total allotted amount must not exceed import_urea quantity ----
    if ($validationError === '' && $al_ref_no !== null) {
        $ureaQtyStmt = $conn->prepare("SELECT quantity FROM import_urea WHERE ref_no = ? LIMIT 1");
        $ureaQtyStmt->bind_param('s', $al_ref_no);
        $ureaQtyStmt->execute();
        $ureaQtyRow = $ureaQtyStmt->get_result()->fetch_assoc();
        $ureaQtyStmt->close();

        if (!$ureaQtyRow) {
            $validationError = "No import urea record found for reference no \"$al_ref_no\".";
        } else {
            $ureaQuantity = (float)$ureaQtyRow['quantity'];

            $sumSql = "SELECT COALESCE(SUM(amount),0) AS total FROM import_allotment WHERE ref_no = ?";
            if ($al_id > 0) {
                $sumSql .= " AND id <> ?";
                $sumStmt2 = $conn->prepare($sumSql);
                $sumStmt2->bind_param('si', $al_ref_no, $al_id);
            } else {
                $sumStmt2 = $conn->prepare($sumSql);
                $sumStmt2->bind_param('s', $al_ref_no);
            }
            $sumStmt2->execute();
            $existingTotal = (float)$sumStmt2->get_result()->fetch_assoc()['total'];
            $sumStmt2->close();

            $newTotal = $existingTotal + (float)$al_amount;

            if ($newTotal > $ureaQuantity) {
                $over = $newTotal - $ureaQuantity;
                $validationError = "Total allotted amount ($newTotal) exceeds the import urea quantity ($ureaQuantity) for ref no \"$al_ref_no\" by $over. Adjust the amount so the total matches the quantity.";
            }
        }
    }

    // ---- SAVE (only if validation passed) ----
    if ($validationError !== '') {
        $message = $validationError;
        $messageType = 'error';
    } elseif ($al_id > 0) {
        // UPDATE
        $sql = "UPDATE import_allotment SET
                    ref_no = ?, buffer_name = ?, amount = ?, mdium = ?, created_by = ?, updated_at = NOW()
                WHERE id = ?";
        $stmt2 = $conn->prepare($sql);
        if ($stmt2) {
            $stmt2->bind_param('sssssi', $al_ref_no, $al_buffer, $al_amount, $al_medium, $al_created_by, $al_id);
            if ($stmt2->execute()) {
                $message = "Allotment #$al_id updated successfully.";
                $messageType = 'success';
            } else {
                $message = "Allotment update failed: " . $stmt2->error;
                $messageType = 'error';
            }
            $stmt2->close();
        }
    } else {
        // INSERT
        $sql = "INSERT INTO import_allotment
                    (ref_no, buffer_name, amount, mdium, created_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt2 = $conn->prepare($sql);
        if ($stmt2) {
            $stmt2->bind_param('sssss', $al_ref_no, $al_buffer, $al_amount, $al_medium, $al_created_by);
            if ($stmt2->execute()) {
                $message = "New allotment added successfully (ID: {$conn->insert_id}).";
                $messageType = 'success';
            } else {
                $message = "Allotment insert failed: " . $stmt2->error;
                $messageType = 'error';
            }
            $stmt2->close();
        }
    }
    // keep the ref_no context after saving (or after a failed validation)
    $_GET['ref_no'] = $al_ref_no;
}


// -----------------------------------------------------------------
// HANDLE ALLOTMENT DELETE
// -----------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'delete_allotment' && isset($_GET['id'])) {
    $al_id = (int)$_GET['id'];

    // Look up ref_no ONLY so the page can stay scrolled to the same context
    // after deleting — it plays no part in the delete condition itself.
    $lookupRefNo = '';
    $lookupStmt = $conn->prepare("SELECT ref_no FROM import_allotment WHERE id = ?");
    $lookupStmt->bind_param('i', $al_id);
    $lookupStmt->execute();
    $lookupRow = $lookupStmt->get_result()->fetch_assoc();
    $lookupStmt->close();
    if ($lookupRow) {
        $lookupRefNo = $lookupRow['ref_no'];
    }

    // Delete keyed strictly on the import_allotment primary key `id`.
    $stmt2 = $conn->prepare("DELETE FROM import_allotment WHERE id = ?");
    $stmt2->bind_param('i', $al_id);
    if ($stmt2->execute()) {
        $message = "Allotment #$al_id deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Allotment delete failed: " . $stmt2->error;
        $messageType = 'error';
    }
    $stmt2->close();

    if ($lookupRefNo !== '') {
        $_GET['ref_no'] = $lookupRefNo;
    }
}

// -----------------------------------------------------------------
// SELECTED REF_NO CONTEXT (which import_urea record's allotments to show)
// -----------------------------------------------------------------
$selectedRefNo = isset($_GET['ref_no']) ? trim($_GET['ref_no']) : '';

// -----------------------------------------------------------------
// LOAD ALLOTMENT RECORD FOR EDIT
// -----------------------------------------------------------------
$editAllotment = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit_allotment' && isset($_GET['id'])) {
    $al_id = (int)$_GET['id'];
    $stmt2 = $conn->prepare("SELECT * FROM import_allotment WHERE id = ?");
    $stmt2->bind_param('i', $al_id);
    $stmt2->execute();
    $editAllotment = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
    if ($editAllotment) {
        $selectedRefNo = $editAllotment['ref_no'];
    }
}

// -----------------------------------------------------------------
// FETCH DISTINCT REF NOs FROM import_urea (for the selector dropdown)
// -----------------------------------------------------------------
$refNoList = $conn->query("SELECT DISTINCT ref_no FROM import_urea WHERE ref_no IS NOT NULL AND ref_no <> '' ORDER BY ref_no DESC");

// -----------------------------------------------------------------
// FETCH ALLOTMENTS  (SELECT * FROM import_allotment WHERE 1 [AND ref_no = ?])
// -----------------------------------------------------------------
$allotSql = "SELECT * FROM import_allotment WHERE 1";
if ($selectedRefNo !== '') {
    $allotSql .= " AND ref_no = ?";
}
$allotSql .= " ORDER BY id DESC";
$allotStmt = $conn->prepare($allotSql);
if ($selectedRefNo !== '') {
    $allotStmt->bind_param('s', $selectedRefNo);
}
$allotStmt->execute();
$allotRows = $allotStmt->get_result();

// Running total allotted amount for the selected ref_no
$allotTotal = 0;
$allotCount = 0;
if ($selectedRefNo !== '') {
    $sumStmt = $conn->prepare("SELECT COALESCE(SUM(amount),0) AS total, COUNT(*) AS cnt FROM import_allotment WHERE ref_no = ?");
    $sumStmt->bind_param('s', $selectedRefNo);
    $sumStmt->execute();
    $sumRow = $sumStmt->get_result()->fetch_assoc();
    $allotTotal = $sumRow['total'];
    $allotCount = $sumRow['cnt'];
    $sumStmt->close();
}

// Import urea quantity for the selected ref_no + remaining balance to allot
$ureaQuantityForRef = null;
$allotBalance = null;
if ($selectedRefNo !== '') {
    $uq = $conn->prepare("SELECT quantity FROM import_urea WHERE ref_no = ? LIMIT 1");
    $uq->bind_param('s', $selectedRefNo);
    $uq->execute();
    $uqRow = $uq->get_result()->fetch_assoc();
    $uq->close();
    if ($uqRow) {
        $ureaQuantityForRef = (float)$uqRow['quantity'];
        $allotBalance = $ureaQuantityForRef - (float)$allotTotal;
    }
}

// Helper to render a nicely-labeled port name (falls back to raw value)
function portLabel($PORT_OPTIONS, $value) {
    $flipped = array_flip($PORT_OPTIONS);
    return $flipped[$value] ?? $value;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Import Urea Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    :root {
        --primary: #3a7bd5;
        --primary-dark: #2f65b0;
        --accent: #805ad5;
        --accent-dark: #6b46c1;
        --success: #38a169;
        --success-dark: #2f855a;
        --danger: #e53e3e;
        --danger-dark: #c53030;
        --bg-grad-1: #6a5cf5;
        --bg-grad-2: #3a7bd5;
    }
    * { box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(160deg, #eef2ff 0%, #f4f6f8 35%, #f0fbf6 100%);
        margin: 0;
        padding: 20px;
        color: #2b2f38;
    }
    .container {
        max-width: 1300px;
        margin: 0 auto;
    }
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: linear-gradient(135deg, var(--bg-grad-1), var(--bg-grad-2));
        border-radius: 12px;
        padding: 18px 24px;
        margin-bottom: 24px;
        box-shadow: 0 6px 18px rgba(58, 123, 213, 0.28);
    }
    .top-bar h1 {
        font-size: 22px;
        margin: 0;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .top-bar .btn-danger {
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.5);
        color: #fff;
        backdrop-filter: blur(2px);
    }
    .top-bar .btn-danger:hover { background: rgba(255,255,255,0.32); }

    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(30, 41, 59, 0.08);
        padding: 20px;
        margin-bottom: 24px;
        border-top: 4px solid var(--primary);
        transition: box-shadow .2s ease;
    }
    .card:hover { box-shadow: 0 6px 18px rgba(30, 41, 59, 0.12); }
    .card.card-allotment { border-top-color: var(--accent); }
    .card h2 {
        margin-top: 0;
        font-size: 18px;
        border-bottom: 2px solid #eef1f4;
        padding-bottom: 10px;
        color: #1a3b5d;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #4a5568;
    }
    input[type=text], input[type=number], input[type=date], select {
        padding: 8px 10px;
        border: 1px solid #d3dae3;
        border-radius: 6px;
        font-size: 14px;
        background: #fbfcfe;
    }
    input:focus, select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.15);
    }
    .form-actions {
        margin-top: 16px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    button, .btn {
        padding: 9px 18px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: transform .08s ease, background .15s ease;
    }
    button:hover, .btn:hover { transform: translateY(-1px); }
    .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-secondary { background: #e2e8f0; color: #333; }
    .btn-secondary:hover { background: #cbd5e0; }
    .btn-danger { background: linear-gradient(135deg, var(--danger), var(--danger-dark)); color: #fff; }
    .btn-danger:hover { background: var(--danger-dark); }
    .btn-edit { background: linear-gradient(135deg, var(--success), var(--success-dark)); color: #fff; }
    .btn-edit:hover { background: var(--success-dark); }
    .btn-allot { background: linear-gradient(135deg, var(--accent), var(--accent-dark)); color: #fff; }
    .btn-allot:hover { background: var(--accent-dark); }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    th, td {
        padding: 10px 10px;
        border-bottom: 1px solid #eef1f4;
        text-align: left;
        white-space: nowrap;
    }
    th {
        background: linear-gradient(135deg, #eef2ff, #e6f0ff);
        color: #1a3b5d;
        position: sticky;
        top: 0;
    }
    tbody tr:nth-child(even) { background: #fbfcfe; }
    tr:hover { background: #eef6ff; }
    .table-wrap {
        overflow-x: auto;
        max-height: 560px;
        overflow-y: auto;
        border-radius: 8px;
        border: 1px solid #eef1f4;
    }
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .alert-success { background: #e6fffa; color: #22543d; border: 1px solid #38a169; }
    .alert-error { background: #fff5f5; color: #742a2a; border: 1px solid #e53e3e; }
    .search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .search-bar input, .search-bar select {
        padding: 9px 12px;
        border: 1px solid #d3dae3;
        border-radius: 6px;
        font-size: 14px;
    }
    .search-bar input { flex: 1; min-width: 200px; }
    .status-badge {
        padding: 4px 11px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
        display: inline-block;
        letter-spacing: .2px;
    }
    .status-pending { background: #fefcbf; color: #744210; }
    .status-completed, .status-delivered, .status-arrived { background: #c6f6d5; color: #22543d; }
    .status-in_transit, .status-shipped { background: #bee3f8; color: #2a4365; }
    .status-cancelled { background: #fed7d7; color: #822727; }
    .actions-col { display: flex; gap: 6px; flex-wrap: wrap; }
    .ref-context {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
        padding: 12px 14px;
        background: #f7f5ff;
        border: 1px solid #e2d9fb;
        border-radius: 8px;
    }
    .ref-context select {
        padding: 7px 10px;
        border: 1px solid #d3dae3;
        border-radius: 6px;
        font-size: 14px;
    }
    .summary-pill {
        background: #edf2f7;
        color: #2d3748;
        padding: 5px 12px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
    }
    .pill-balanced { background: #c6f6d5; color: #22543d; }
    .pill-pending { background: #fefcbf; color: #744210; }
    .pill-over { background: #fed7d7; color: #822727; }
    .empty-hint {
        text-align: center;
        color: #888;
        padding: 30px 10px;
        font-size: 14px;
    }
    @media (max-width: 900px) {
        .form-grid { grid-template-columns: repeat(2, 1fr); }
        .top-bar { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 600px) {
        body { padding: 10px; }
        .form-grid { grid-template-columns: 1fr; }
        .card { padding: 14px; }
        th, td { padding: 8px 6px; font-size: 12.5px; }
    }
</style>
</head>
<body>
<div class="container">

    <div class="top-bar">
        <h1>📦 Import Urea — Purchase Dashboard</h1>
        <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'error'; ?>">
            <?php echo clean($conn, $message); ?>
        </div>
    <?php endif; ?>

    <!-- ============ FORM: CREATE / EDIT ============ -->
    <div class="card">
        <h2><?php echo $editRow ? 'Edit Record #' . (int)$editRow['id'] : 'Add New Record'; ?></h2>
        <form method="POST" action="pur_dashboard.php">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?php echo $editRow ? (int)$editRow['id'] : 0; ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label>Reference No</label>
                    <input type="text" name="ref_no" value="<?php echo clean($conn, $editRow['ref_no'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" value="<?php echo clean($conn, $editRow['country'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Ship Name</label>
                    <input type="text" name="ship_name" value="<?php echo clean($conn, $editRow['ship_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Contractor Name</label>
                    <input type="text" name="contractor_name" value="<?php echo clean($conn, $editRow['contractor_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Port Name</label>
                    <select name="port_name">
                        <option value="">-- Select Port --</option>
                        <?php
                        $currentPort = $editRow['port_name'] ?? '';
                        foreach ($PORT_OPTIONS as $label => $value) {
                            $sel = ($currentPort === $value) ? 'selected' : '';
                            echo "<option value=\"" . clean($conn, $value) . "\" $sel>" . clean($conn, $label) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Shipping Date</label>
                    <input type="date" name="shiping_date" value="<?php echo clean($conn, $editRow['shiping_date'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Port Arrival Date</label>
                    <input type="date" name="port_arraival_date" value="<?php echo clean($conn, $editRow['port_arraival_date'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Quantity (MT)</label>
                    <input type="number" step="any" name="quantity" value="<?php echo clean($conn, $editRow['quantity'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <?php
                        $statuses = ['pending', 'shipped', 'in_transit', 'arrived', 'completed', 'cancelled'];
                        $currentStatus = $editRow['status'] ?? '';
                        foreach ($statuses as $s) {
                            $sel = ($currentStatus === $s) ? 'selected' : '';
                            echo "<option value=\"$s\" $sel>" . ucfirst(str_replace('_',' ',$s)) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><?php echo $editRow ? 'Update Record' : 'Add Record'; ?></button>
                <?php if ($editRow): ?>
                    <a href="pur_dashboard.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ============ LIST / SEARCH ============ -->
    <div class="card">
        <h2>Records</h2>

        <form method="GET" action="pur_dashboard.php" class="search-bar">
            <input type="text" name="q" placeholder="Search by ref no, ship, contractor, port, country..." value="<?php echo clean($conn, $search); ?>">
            <select name="status_filter">
                <option value="">All Statuses</option>
                <?php
                $statuses = ['pending', 'shipped', 'in_transit', 'arrived', 'completed', 'cancelled'];
                foreach ($statuses as $s) {
                    $sel = ($statusFilter === $s) ? 'selected' : '';
                    echo "<option value=\"$s\" $sel>" . ucfirst(str_replace('_',' ',$s)) . "</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn-primary">Search</button>
            <?php if ($search !== '' || $statusFilter !== ''): ?>
                <a href="pur_dashboard.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ref No</th>
                    <th>Country</th>
                    <th>Ship Name</th>
                    <th>Contractor</th>
                    <th>Port Name</th>
                    <th>Shipping Date</th>
                    <th>Port Arrival</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows && $rows->num_rows > 0): ?>
                    <?php while ($r = $rows->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo (int)$r['id']; ?></td>
                            <td><?php echo clean($conn, $r['ref_no']); ?></td>
                            <td><?php echo clean($conn, $r['country']); ?></td>
                            <td><?php echo clean($conn, $r['ship_name']); ?></td>
                            <td><?php echo clean($conn, $r['contractor_name']); ?></td>
                            <td><?php echo clean($conn, portLabel($PORT_OPTIONS, $r['port_name'])); ?></td>
                            <td><?php echo clean($conn, $r['shiping_date']); ?></td>
                            <td><?php echo clean($conn, $r['port_arraival_date']); ?></td>
                            <td><?php echo clean($conn, $r['quantity']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo clean($conn, $r['status']); ?>">
                                    <?php echo clean($conn, str_replace('_',' ', $r['status'])); ?>
                                </span>
                            </td>
                            <td class="actions-col">
                                <a class="btn btn-edit" href="pur_dashboard.php?action=edit&id=<?php echo (int)$r['id']; ?>">Edit</a>
                                <a class="btn btn-allot" href="pur_dashboard.php?ref_no=<?php echo urlencode($r['ref_no']); ?>#allotment">Allotment</a>
                                <a class="btn btn-danger"
                                   href="pur_dashboard.php?action=delete&id=<?php echo (int)$r['id']; ?>"
                                   onclick="return confirm('Delete record #<?php echo (int)$r['id']; ?>? This cannot be undone.');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11" style="text-align:center; padding:20px; color:#888;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- ============ IMPORT UREA ALLOTMENT (linked by ref_no) ============ -->
    <div class="card card-allotment" id="allotment">
        <h2>🧾 Import Urea Allotment</h2>

        <form method="GET" action="pur_dashboard.php#allotment" class="ref-context">
            <label for="ref_no_select" style="margin:0;">Ref No:</label>
            <select id="ref_no_select" name="ref_no" onchange="this.form.submit()">
                <option value="">-- Select Reference No --</option>
                <?php
                if ($refNoList) {
                    while ($rn = $refNoList->fetch_assoc()) {
                        $sel = ($rn['ref_no'] === $selectedRefNo) ? 'selected' : '';
                        echo "<option value=\"" . clean($conn, $rn['ref_no']) . "\" $sel>" . clean($conn, $rn['ref_no']) . "</option>";
                    }
                }
                ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary">Go</button></noscript>

            <?php if ($selectedRefNo !== ''): ?>
                <span class="summary-pill">Allotments: <?php echo (int)$allotCount; ?></span>
                <?php if ($ureaQuantityForRef !== null): ?>
                    <span class="summary-pill">Urea Quantity: <?php echo clean($conn, $ureaQuantityForRef); ?></span>
                <?php endif; ?>
                <span class="summary-pill">Total Allotted: <?php echo clean($conn, $allotTotal); ?></span>
                <?php if ($allotBalance !== null): ?>
                    <span class="summary-pill <?php echo $allotBalance == 0 ? 'pill-balanced' : ($allotBalance < 0 ? 'pill-over' : 'pill-pending'); ?>">
                        <?php
                        if ($allotBalance == 0) {
                            echo "Fully Allotted ✓";
                        } elseif ($allotBalance < 0) {
                            echo "Over-allotted by " . clean($conn, abs($allotBalance));
                        } else {
                            echo "Remaining: " . clean($conn, $allotBalance);
                        }
                        ?>
                    </span>
                <?php endif; ?>
                <a href="pur_dashboard.php#allotment" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <?php if ($selectedRefNo === ''): ?>
            <div class="empty-hint">Select a Reference No above, or click "Allotment" next to a record in the table to manage its allotments.</div>
        <?php else: ?>

            <!-- Allotment Form -->
            <form method="POST" action="pur_dashboard.php#allotment">
                <input type="hidden" name="action" value="save_allotment">
                <input type="hidden" name="al_id" value="<?php echo $editAllotment ? (int)$editAllotment['id'] : 0; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Ref No</label>
                        <input type="text" name="al_ref_no" value="<?php echo clean($conn, $selectedRefNo); ?>" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Buffer Name</label>
                        <input type="text" name="buffer_name" value="<?php echo clean($conn, $editAllotment['buffer_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="any" name="amount" value="<?php echo clean($conn, $editAllotment['amount'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Medium</label>
                        <input type="text" name="mdium" value="<?php echo clean($conn, $editAllotment['mdium'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Created By</label>
                        <input type="text" name="created_by" value="<?php echo clean($conn, $editAllotment['created_by'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><?php echo $editAllotment ? 'Update Allotment' : 'Add Allotment'; ?></button>
                    <?php if ($editAllotment): ?>
                        <a href="pur_dashboard.php?ref_no=<?php echo urlencode($selectedRefNo); ?>#allotment" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Allotment List -->
            <div class="table-wrap" style="margin-top:20px;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ref No</th>
                        <th>Buffer Name</th>
                        <th>Amount</th>
                        <th>Medium</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($allotRows && $allotRows->num_rows > 0): ?>
                        <?php while ($a = $allotRows->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo (int)$a['id']; ?></td>
                                <td><?php echo clean($conn, $a['ref_no']); ?></td>
                                <td><?php echo clean($conn, $a['buffer_name']); ?></td>
                                <td><?php echo clean($conn, $a['amount']); ?></td>
                                <td><?php echo clean($conn, $a['mdium']); ?></td>
                                <td><?php echo clean($conn, $a['created_by']); ?></td>
                                <td><?php echo clean($conn, $a['created_at']); ?></td>
                                <td><?php echo clean($conn, $a['updated_at']); ?></td>
                                <td class="actions-col">
                                    <a class="btn btn-edit" href="pur_dashboard.php?action=edit_allotment&id=<?php echo (int)$a['id']; ?>#allotment">Edit</a>
                                    <a class="btn btn-danger"
                                       href="pur_dashboard.php?action=delete_allotment&id=<?php echo (int)$a['id']; ?>#allotment"
                                       onclick="return confirm('Delete allotment #<?php echo (int)$a['id']; ?>? This cannot be undone.');">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" style="text-align:center; padding:20px; color:#888;">No allotments found for this reference no.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

        <?php endif; ?>
    </div>

</div>
</body>
</html>
<?php
$stmt->close();
$allotStmt->close();
$conn->close();
?>