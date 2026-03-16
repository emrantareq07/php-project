<?php
header('Content-Type: application/json');

// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'innovation_db' 
); 
 
// mysql db table to use 
$table = 'innovation_tbl'; 
 
// Table's primary key 
$primaryKey = 'id'; 
 
// Array of database columns which should be read and sent back to DataTables. 
$columns = array( 
    array( 'db' => 'fiscal_year', 'dt' => 0 ), 
    array( 'db' => 'title_of_invention',  'dt' => 1 ), 
    array( 'db' => 'inventors_name', 'dt' => 2 ), 
    array( 'db' => 'inventors_designation',  'dt' => 3 ), 
    array( 'db' => 'inventors_emp_id', 'dt' => 4 ), 
    array( 'db' => 'proposed_workplace',  'dt' => 5 ), 
    array( 'db' => 'des_of_invention', 'dt' => 6), 
    array( 'db' => 'imple_status',  'dt' => 7 ), 
    array( 'db' => 'replicate_eligibility', 'dt' => 8 ), 
    array( 'db' => 'feedback', 'dt' => 9 ), 
    array( 'db' => 'service_link', 'dt' => 10 ), 
    array( 'db' => 'remarks', 'dt' => 11 ), 
    array( 
        'db'        => 'id',
        'dt'        => 12,
        'formatter' => function( $d, $row ) { 
            return '<div class="btn-group" role="group">
                        <button type="button" class="btn btn-warning btn-sm btn-edit mr-1" data-id="'.$row['id'].'" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="'.$row['id'].'" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>'; 
        } 
    ) 
); 
 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
$result = SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns );
echo json_encode($result);
?>