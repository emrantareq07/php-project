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
    'abbankho_tbl' => 'AB Bank Head Office',
    'poton_tbl' => 'Poton Traders',
    'mollik_tbl' => 'Mollik Traders',
     'erres_tbl' => 'E. R. Resourses',
    'mrtrading_tbl' => 'M. R. Trading',
    'motalibasso_tbl' => 'Motalib & Associates',
    'romanaent_tbl' => 'Romana Enterprise',
    'rafirachi_tbl' => 'Rafi & Rachi',
    'oyasistec_tbl' => 'Oyasis Technologies',
    'mehedient_tbl' => 'Mehedi Enterprise',
    'multiwaysmkt_tbl' => 'Multi-Ways Marketing',
    'demano_tbl' => 'Demano Ltd.',
    'beximco_tbl' => 'BEXIMCO',
    'bdg_tbl' => 'Bangladesh Development Group',
    'creativep_tbl' => 'Creative Papers',
    'pp_tbl' => 'Petroleum Products',
    'sadg_tbl' => 'South Asia Dev. Group',
    'gp_tbl' => 'Grameen Phone',
    'carbon_tbl' => 'Carbon Holdings',
    'bcicsamiti_tbl' => 'BCIC Samiti',
    'deshb_tbl' => 'Desh Builders',
    'hirajheelh_tbl' => 'Hirajheel Hotel',
    'cccl_tbl' => 'Chatak Cement Project',
    '13buf_tbl' => '13 Buffer Project',
    '34buf_tbl' => '34 Buffer Project',
    'daycare_tbl' => 'Day Care',
    'pdb_tbl' => 'BPDB',
    'fahiment_tbl' => 'Fahim Enterprise',
    'shamin_tbl' => 'Sharmin Akter',
    'chainntrad_tbl' => 'Chaina Traders',
    'nment_tbl' => 'N M Enterprise',
    'rajobali_tbl' => 'Rajob Ali',
    'rajuk_tbl' => 'Rajuk',
    'arjufood_tbl' => 'Arju Food & Restaurant',
    'rajuk148_tbl' => 'Rajuk 148/Ka',
    'abbankpb_tbl' => 'AB Bank Principle Branch',
    'dami1' => 'Dami',
    'royelton_tbl' => 'Royelton Leaker Coating',
    'leaker_tbl' => 'Leaker Coating',
    'pusti_tbl' => 'Super Oil Rifinary (Pusti)',
    'kpml_tbl' => 'KPML',
    'nbpm_tbl' => 'NBPM',
    'dami1' => 'Dami1',
    'dami2' => 'Dami2',
    'dami3' => 'Dami3',
    'dami4' => 'Dami4'
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
  `actual_cb` int(50) NOT NULL,

  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
    `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,

  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
   `cb_in` int(50) NOT NULL,

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
  `cb_outs` int(50) NOT NULL,

`all_value` longtext NOT NULL,
 `address` varchar(255) NOT NULL,

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
