<?php
session_start();
include('db/db.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Simple direct query approach
$response = [];

try {
    // 1. Get total divisions
    $divResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM division");
    if ($divResult) {
        $divRow = mysqli_fetch_assoc($divResult);
        $response['summary']['totalDivisions'] = (int)$divRow['count'];
    } else {
        $response['summary']['totalDivisions'] = 0;
    }

    // 2. Get total vendors
    $vendorResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM vendor_list");
    if ($vendorResult) {
        $vendorRow = mysqli_fetch_assoc($vendorResult);
        $response['summary']['totalVendors'] = (int)$vendorRow['count'];
    } else {
        $response['summary']['totalVendors'] = 0;
    }

    // 3. Get total records
    $recordResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM records_tbl");
    if ($recordResult) {
        $recordRow = mysqli_fetch_assoc($recordResult);
        $response['summary']['totalRecords'] = (int)$recordRow['count'];
    } else {
        $response['summary']['totalRecords'] = 0;
    }

    // 4. Get division data
    $divDataResult = mysqli_query($conn, "
        SELECT division_dept, COUNT(*) as count 
        FROM records_tbl 
        WHERE division_dept IS NOT NULL AND division_dept != ''
        GROUP BY division_dept 
        ORDER BY count DESC
    ");

    $response['divisionData'] = ['labels' => [], 'values' => []];
    if ($divDataResult) {
        while($row = mysqli_fetch_assoc($divDataResult)) {
            $response['divisionData']['labels'][] = $row['division_dept'] ?: 'Unknown';
            $response['divisionData']['values'][] = (int)$row['count'];
        }
    }

    // If no division data, add a placeholder
    if (empty($response['divisionData']['labels'])) {
        $response['divisionData'] = ['labels' => ['No Data'], 'values' => [1]];
    }

    // 5. Get vendor data
    $vendorDataResult = mysqli_query($conn, "
        SELECT vendor_name, COUNT(*) as count 
        FROM records_tbl 
        WHERE vendor_name IS NOT NULL AND vendor_name != ''
        GROUP BY vendor_name 
        ORDER BY count DESC 
        LIMIT 8
    ");

    $response['vendorData'] = ['labels' => [], 'values' => []];
    if ($vendorDataResult) {
        while($row = mysqli_fetch_assoc($vendorDataResult)) {
            $response['vendorData']['labels'][] = $row['vendor_name'] ?: 'Unknown';
            $response['vendorData']['values'][] = (int)$row['count'];
        }
    }

    // If no vendor data, add a placeholder
    if (empty($response['vendorData']['labels'])) {
        $response['vendorData'] = ['labels' => ['No Data'], 'values' => [1]];
    }

    // 6. Get fiscal year data
    $fiscalResult = mysqli_query($conn, "
        SELECT 
            CONCAT(YEAR(date), '-', YEAR(date) + 1) as fiscal_year,
            COUNT(*) as count
        FROM records_tbl 
        WHERE date IS NOT NULL AND date != '0000-00-00'
        AND YEAR(date) > 2000
        GROUP BY YEAR(date)
        ORDER BY YEAR(date) DESC
        LIMIT 5
    ");

    $response['fiscalYearData'] = ['labels' => [], 'values' => []];
    if ($fiscalResult) {
        while($row = mysqli_fetch_assoc($fiscalResult)) {
            if ($row['fiscal_year'] && $row['count'] > 0) {
                $response['fiscalYearData']['labels'][] = $row['fiscal_year'];
                $response['fiscalYearData']['values'][] = (int)$row['count'];
            }
        }
    }

    // If no fiscal year data, add a placeholder
    if (empty($response['fiscalYearData']['labels'])) {
        $response['fiscalYearData'] = ['labels' => [date('Y') . '-' . (date('Y') + 1)], 'values' => [0]];
    }

    // 7. Get current year monthly data
    $currentYear = date('Y');
    $monthlyResult = mysqli_query($conn, "
        SELECT 
            MONTH(date) as month,
            COUNT(*) as count
        FROM records_tbl 
        WHERE YEAR(date) = $currentYear
        AND date IS NOT NULL
        AND date != '0000-00-00'
        GROUP BY MONTH(date)
        ORDER BY month
    ");

    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $response['monthlyData'] = ['labels' => $monthNames, 'values' => array_fill(0, 12, 0)];

    if ($monthlyResult) {
        while($row = mysqli_fetch_assoc($monthlyResult)) {
            $monthIndex = (int)$row['month'] - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                $response['monthlyData']['values'][$monthIndex] = (int)$row['count'];
            }
        }
    }

    $response['success'] = true;
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'summary' => ['totalDivisions' => 0, 'totalVendors' => 0, 'totalRecords' => 0],
        'divisionData' => ['labels' => ['Error'], 'values' => [1]],
        'vendorData' => ['labels' => ['Error'], 'values' => [1]],
        'fiscalYearData' => ['labels' => ['Error'], 'values' => [1]],
        'monthlyData' => ['labels' => $monthNames ?? [], 'values' => array_fill(0, 12, 0)]
    ];
}

echo json_encode($response);
?>