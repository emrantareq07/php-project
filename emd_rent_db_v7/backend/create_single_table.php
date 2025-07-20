<?php
// Database connection
// $host = 'localhost';
// $username = 'your_db_username';
// $password = 'your_db_password';
// $database = 'your_database';

// $conn = new mysqli($host, $username, $password, $database);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include_once '../db/database.php';
// List of tenant tables
$tenants = [   
    'rajuk_tbl' => 'Rajuk' 
     
];

// SQL template for table creation
$sql_template = "
CREATE TABLE `%s` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

foreach ($tenants as $table_name => $tenant_name) {
    // Format the SQL statement
    $sql = sprintf($sql_template, $table_name);

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        echo "Table $table_name created successfully.<br>";
    } else {
        echo "Error creating table $table_name: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
