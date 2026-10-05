<?php
session_name('dfms_db');
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit();
}

require_once('../db/db.php');

header('Content-Type: application/json; charset=utf-8');

function s($v) { return trim((string)($v ?? '')); }

// ---- As-of date (defaults to today) ----
$asOfDate = $_GET['as_of_date'] ?? date('Y-m-d');
if (!DateTime::createFromFormat('Y-m-d', $asOfDate)) {
    $asOfDate = date('Y-m-d');
}
$asOfYear  = (int)date('Y', strtotime($asOfDate));
$asOfMonth = (int)date('n', strtotime($asOfDate));

// ---- Fiscal year window around as-of date ----
if ($asOfMonth >= 7) {
    $fyStart = sprintf('%04d-07-01', $asOfYear);
    $fyEnd   = sprintf('%04d-06-30', $asOfYear + 1);
} else {
    $fyStart = sprintf('%04d-07-01', $asOfYear - 1);
    $fyEnd   = sprintf('%04d-06-30', $asOfYear);
}

$rows   = [];
$totals = [
    'installed_capacity' => 0.0,
    'daily_amount'       => 0.0,
    'monthly_amount'     => 0.0,
    'yearly_amount'      => 0.0,
    'yearly_target'      => 0.0,
    'due'                => 0.0,
    'monthly_target'     => 0.0,
    'monthly_till_date'  => 0.0,
    'plant_load'         => 0.0,
];

// ---- Fetch all factory offices ----
$factoryOffices = [];
$sqlFO = "SELECT id, office_name, buffer_name, zone, capacity, yearly_target
          FROM office_tbl
          WHERE office_type = 'factory_office'
          ORDER BY office_name";
if ($resFO = mysqli_query($conn, $sqlFO)) {
    while ($r = mysqli_fetch_assoc($resFO)) {
        $factoryOffices[] = $r;
    }
    mysqli_free_result($resFO);
}

foreach ($factoryOffices as $fo) {
    $factoryName     = s($fo['buffer_name']);
    $officeName      = s($fo['office_name']);
    $yearlyTargetOff = (float)($fo['yearly_target'] ?? 0);

    $installedCap    = 0.0;
    $productName     = 'Urea';
    $monthlyTargetDb = 0.0;

    // Latest production row for product/installed capacity/monthly target
    if ($s = mysqli_prepare($conn, "
        SELECT product_produce, installed_capacity, monthly_target
        FROM production_tbl
        WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?))
        ORDER BY date DESC, id DESC
        LIMIT 1
    ")) {
        mysqli_stmt_bind_param($s, 's', $factoryName);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) {
            if (!empty($row['product_produce'])) $productName     = (string)$row['product_produce'];
            $installedCap    = (float)($row['installed_capacity'] ?? 0);
            $monthlyTargetDb = (float)($row['monthly_target'] ?? 0);
        }
        mysqli_stmt_close($s);
    }

    // Daily production on as-of date + plant load + remarks
    $dailyAmount = 0.0;
    $plantLoad   = 0.0;
    $remarks     = '';
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS daily_sum,
               MAX(plant_load)   AS max_load,
               MAX(remarks)      AS any_remarks
        FROM production_tbl
        WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?))
          AND date = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) {
            $dailyAmount = (float)$row['daily_sum'];
            $plantLoad   = (float)($row['max_load'] ?? 0);
            $remarks     = (string)($row['any_remarks'] ?? '');
        }
        mysqli_stmt_close($s);
    }

    // Monthly production
    $monthlyAmount = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS v
        FROM production_tbl
        WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?))
          AND YEAR(date) = ? AND MONTH(date) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $monthlyAmount = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // Yearly production (fiscal year)
    $yearlyAmount = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS v
        FROM production_tbl
        WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?))
          AND date BETWEEN ? AND ?
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $yearlyAmount = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $monthlyTillDate = $monthlyAmount;
    $due             = $yearlyTargetOff - $yearlyAmount;

    if ($monthlyTargetDb <= 0 && $yearlyTargetOff > 0) {
        $monthlyTargetDb = $yearlyTargetOff / 12;
    }

    $rows[] = [
        'office_name'        => $officeName,
        'factory_name'       => $factoryName,
        'product_produce'    => $productName,
        'installed_capacity' => $installedCap,
        'daily_amount'       => $dailyAmount,
        'monthly_amount'     => $monthlyAmount,
        'yearly_amount'      => $yearlyAmount,
        'yearly_target'      => $yearlyTargetOff,
        'due'                => $due,
        'monthly_target'     => $monthlyTargetDb,
        'monthly_till_date'  => $monthlyTillDate,
        'plant_load'         => $plantLoad,
        'remarks'            => $remarks,
    ];

    $totals['installed_capacity']   += $installedCap;
    $totals['daily_amount']         += $dailyAmount;
    $totals['monthly_amount']       += $monthlyAmount;
    $totals['yearly_amount']        += $yearlyAmount;
    $totals['yearly_target']        += $yearlyTargetOff;
    $totals['due']                  += $due;
    $totals['monthly_target']       += $monthlyTargetDb;
    $totals['monthly_till_date']    += $monthlyTillDate;
    $totals['plant_load']           += $plantLoad;
}

echo json_encode([
    'ok'         => true,
    'as_of_date' => $asOfDate,
    'fy_start'   => $fyStart,
    'fy_end'     => $fyEnd,
    'count'      => count($rows),
    'rows'       => $rows,
    'totals'     => $totals,
]);
exit();