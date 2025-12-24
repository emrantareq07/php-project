<?php
// api/dashboard_data.php
session_name('ict_main_records_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

$filters = [
    'fiscalYear' => $input['fiscalYear'] ?? 'all',
    'division' => $input['division'] ?? 'all',
    'vendor' => $input['vendor'] ?? 'all'
];

// Build WHERE conditions
$whereConditions = [];
$params = [];

if ($filters['fiscalYear'] !== 'all') {
    list($startYear, $endYear) = explode('-', $filters['fiscalYear']);
    $whereConditions[] = "EXISTS (
        SELECT 1 FROM vendor_list vl 
        WHERE YEAR(vl.fiscal_start) = ? 
        AND r.vendor_name = vl.vendor_name
    )";
    $params[] = $startYear;
}

if ($filters['division'] !== 'all') {
    $whereConditions[] = "r.division_dept = ?";
    $params[] = $filters['division'];
}

if ($filters['vendor'] !== 'all') {
    $whereConditions[] = "r.vendor_name = ?";
    $params[] = $filters['vendor'];
}

$whereClause = empty($whereConditions) ? '' : 'WHERE ' . implode(' AND ', $whereConditions);

// Get summary stats
$summary = [];

// Total divisions
$divisionQuery = "SELECT COUNT(DISTINCT division) as total FROM division";
$divisionResult = mysqli_query($conn, $divisionQuery);
$summary['totalDivisions'] = mysqli_fetch_assoc($divisionResult)['total'];

// Total vendors
$vendorQuery = "SELECT COUNT(DISTINCT vendor_name) as total FROM vendor_list";
$vendorResult = mysqli_query($conn, $vendorQuery);
$summary['totalVendors'] = mysqli_fetch_assoc($vendorResult)['total'];

// Total records with filters
$recordsQuery = "SELECT COUNT(*) as total FROM records_tbl r $whereClause";
$stmt = mysqli_prepare($conn, $recordsQuery);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
}
mysqli_stmt_execute($stmt);
$recordsResult = mysqli_stmt_get_result($stmt);
$summary['totalRecords'] = mysqli_fetch_assoc($recordsResult)['total'];

// Current month records
$currentMonth = date('Y-m');
$monthlyQuery = "SELECT COUNT(*) as month_count FROM records_tbl WHERE DATE_FORMAT(date, '%Y-%m') = '$currentMonth'";
$monthlyResult = mysqli_query($conn, $monthlyQuery);
$summary['currentMonthRecords'] = mysqli_fetch_assoc($monthlyResult)['month_count'];

// Division data
$divisionDataQuery = "
    SELECT d.division, COUNT(r.id) as count 
    FROM division d 
    LEFT JOIN records_tbl r ON d.division = r.division_dept 
    GROUP BY d.division 
    ORDER BY count DESC 
    LIMIT 8
";
$divisionResult = mysqli_query($conn, $divisionDataQuery);
$divisionData = ['labels' => [], 'values' => []];
while ($row = mysqli_fetch_assoc($divisionResult)) {
    $divisionData['labels'][] = $row['division'];
    $divisionData['values'][] = $row['count'];
}

// Vendor data
$vendorDataQuery = "
    SELECT vl.vendor_name, COUNT(r.id) as count 
    FROM vendor_list vl 
    LEFT JOIN records_tbl r ON vl.vendor_name = r.vendor_name 
    GROUP BY vl.vendor_name 
    ORDER BY count DESC 
    LIMIT 10
";
$vendorResult = mysqli_query($conn, $vendorDataQuery);
$vendorData = ['labels' => [], 'values' => []];
while ($row = mysqli_fetch_assoc($vendorResult)) {
    $vendorData['labels'][] = $row['vendor_name'];
    $vendorData['values'][] = $row['count'];
}

// Monthly trend data (last 6 months)
$monthlyQuery = "
    SELECT DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as count 
    FROM records_tbl 
    WHERE date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) 
    GROUP BY DATE_FORMAT(date, '%Y-%m') 
    ORDER BY month
";
$monthlyResult = mysqli_query($conn, $monthlyQuery);
$monthlyData = ['labels' => [], 'values' => []];
$months = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $months[$row['month']] = $row['count'];
}

// Fill in missing months
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthlyData['labels'][] = date('M', strtotime($month));
    $monthlyData['values'][] = $months[$month] ?? 0;
}

// Fiscal year data
$fiscalQuery = "
    SELECT CONCAT(YEAR(vl.fiscal_start), '-', YEAR(vl.fiscal_end)) as fiscal_year, COUNT(r.id) as count 
    FROM vendor_list vl 
    LEFT JOIN records_tbl r ON vl.vendor_name = r.vendor_name 
    WHERE vl.fiscal_start IS NOT NULL 
    GROUP BY YEAR(vl.fiscal_start), YEAR(vl.fiscal_end) 
    ORDER BY vl.fiscal_start DESC 
    LIMIT 5
";
$fiscalResult = mysqli_query($conn, $fiscalQuery);
$fiscalYearData = ['labels' => [], 'values' => []];
while ($row = mysqli_fetch_assoc($fiscalResult)) {
    $fiscalYearData['labels'][] = $row['fiscal_year'];
    $fiscalYearData['values'][] = $row['count'];
}

// Recent activity (last 10 records)
$activityQuery = "
    SELECT date, user_name, 'Added' as action, CONCAT('Record: ', requisition_no) as details 
    FROM records_tbl 
    ORDER BY date DESC 
    LIMIT 10
";
$activityResult = mysqli_query($conn, $activityQuery);
$recentActivity = [];
while ($row = mysqli_fetch_assoc($activityResult)) {
    $recentActivity[] = $row;
}

// Top products
$productsQuery = "
    SELECT p.name, COUNT(r.id) as count 
    FROM product_tbl p 
    LEFT JOIN records_tbl r ON p.name = r.product_name 
    GROUP BY p.name 
    ORDER BY count DESC 
    LIMIT 5
";
$productsResult = mysqli_query($conn, $productsQuery);
$topProducts = [];
$total = 0;
while ($row = mysqli_fetch_assoc($productsResult)) {
    $total += $row['count'];
    $topProducts[] = $row;
}

// Calculate percentages
foreach ($topProducts as &$product) {
    $product['percentage'] = $total > 0 ? round(($product['count'] / $total) * 100) : 0;
}

// Return JSON response
echo json_encode([
    'success' => true,
    'summary' => $summary,
    'divisionData' => $divisionData,
    'vendorData' => $vendorData,
    'monthlyData' => $monthlyData,
    'fiscalYearData' => $fiscalYearData,
    'recentActivity' => $recentActivity,
    'topProducts' => $topProducts
]);