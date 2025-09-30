<?php
include('../db/db.php');

// Start and end dates
$startDate = new DateTime("2025-08-01");
$endDate   = new DateTime("2025-08-13");

// Loop through each day
for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
    $formattedDate = $date->format("Y-m-d"); // YYYY-MM-DD format for DB
    
    $sql_for_month = "INSERT INTO bisf 
        (factory_name, product_produce, month_id, date, daily, month_code, year_code, plant_load, remarks) 
        VALUES (
            'bisf',
            'insulator',
            '202508',
            '{$formattedDate}',
            '0',
            '103',
            '18',
            '0',
            'Production stopped on 19/04/2023 due to lack of sales order.'
        )";

    mysqli_query($conn, $sql_for_month) or die("Error: " . mysqli_error($conn));
}
?>
