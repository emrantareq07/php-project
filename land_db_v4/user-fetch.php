<?php 
// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'legal_db' 
); 

$table ='legal_tbl';
// Your SQL query
//$table = "SELECT * FROM legal_tbl WHERE hearing_result='বিচারাধীন'";

// Table's primary key 
$primaryKey = 'id'; 

// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 'db' => 'id_bn', 'dt' => 0 ), 
    array( 'db' => 'org_name',  'dt' => 1 ), 
    array( 'db' => 'concate3col', 'dt' => 2 ),  
    array( 'db' => 'plaintiff_name',  'dt' => 3 ), 
    array( 'db' => 'defendant_name', 'dt' => 4),
    array( 'db' => 'reason_of_case', 'dt' => 5),
    array( 'db' => 'court_type',  'dt' => 6 ),  
    array( 'db' => 'lower_name_address', 'dt' => 7), 
    array( 'db' => 'case_filing_accept_steps', 'dt' => 8 ), 
    array( 'db' => 'hearing_result', 'dt' => 9 ), 
    array( 
        'db'        => 'id',
        'dt'        => 10,
        'formatter' => function( $d, $row ) { 
            // Add any additional formatting here if needed
            return $d;
        } 
    ) 
); 

// Include SQL query processing class 
require 'ssp.class.php'; 

// Output data as JSON format 
echo json_encode(SSP::complex($_GET, $dbDetails, $table, $primaryKey, $columns));
?>
