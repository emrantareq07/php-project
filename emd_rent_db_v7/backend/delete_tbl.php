<?php
// Database connection
include_once '../db/database.php';

// List of tenant tables
$tenants = [    
    'abbank_tbl' => 'AB Bank',
    'poton_tbl' => 'Poton Traders',
    'mollik_tbl' => 'Mollik Traders',
    'erres_tbl' => 'Traders',
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
    'cccl_tbl' => 'Chatak Cement Pro',
    '13buf_tbl' => '13 Buffer Project',
    '34buf_tbl' => '34 Buffer Project',
    'daycare_tbl' => 'Day Care',
    'pdb_tbl' => 'BPDB',
    'fahiment_tbl' => 'Fahim Enterprise',
    'shamin_tbl' => 'Sharmin Akter',
    'chainntrad_tbl' => 'Chaina Traders',
    'nment_tbl' => 'N M Enterprise',
    'rajobali_tbl' => 'Rajob Ali',
    'arjufood_tbl' => 'Arju Food & Restaurant'
     'rajuk_tbl' => 'Rajuk'

    
];

// Loop through each tenant table and drop it
foreach ($tenants as $table_name => $tenant_name) {
    // SQL statement to drop the table
    $sql = "DROP TABLE IF EXISTS `$table_name`";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        echo "Table $table_name deleted successfully.<br>";
    } else {
        echo "Error deleting table $table_name: " . $conn->error . "<br>";
    }
}

// Close the connection
$conn->close();
?>
