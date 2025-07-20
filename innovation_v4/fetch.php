
<?php 


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
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
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
    
    // array( 
        // 'db'        => 'created_at', 
        // 'dt'        => 9, 
        // 'formatter' => function( $d, $row ) { 
            // return date( 'jS M Y', strtotime($row['created_at'])); 
        // } 
    // ), 
    array( 
        'db'        => 'id',
        'dt'        => 12,
        'formatter' => function( $d, $row ) { 
            return '<a href="javascript:void(0)" class="btn btn-warning btn-edit ml-1" data-id="'.$row['id'].'">  <i class="fa fa-edit" style="font-size:15px"></i></a> <hr class="my-1">
			
			<a href="javascript:void(0)" class="btn btn-danger btn-delete ml-1" data-id="'.$row['id'].'"> <i class="far fa-trash-alt"></i> </a>'; 
        } 
    ) 
); 
 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));