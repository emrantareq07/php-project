
<?php 


// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'morning_glory_db' 
); 
 
// mysql db table to use 
$table = 'notice_board'; 
 
// Table's primary key 
$primaryKey = 'id'; 
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
	array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'notice', 'dt' => 1 ), 
    array( 'db' => 'dated',  'dt' => 2 ), 
	array( 'db' => 'created_at',  'dt' => 3 ), 
 
    
    // array( 
        // 'db'        => 'created_at', 
        // 'dt'        => 9, 
        // 'formatter' => function( $d, $row ) { 
            // return date( 'jS M Y', strtotime($row['created_at'])); 
        // } 
    // ), 
    array( 
        'db'        => 'id',
        'dt'        => 4,
        'formatter' => function( $d, $row ) { 
            return '<a href="javascript:void(0)" class="btn btn-warning btn-edit ml-1" data-id="'.$row['id'].'">  <i class="fas fa-edit" style="font-size:17px"></i></a> <hr class="my-1">
			
			<a href="javascript:void(0)" class="btn btn-danger btn-delete ml-1" data-id="'.$row['id'].'"> <i class="far fa-trash-alt"></i> </a>'; 
        } 
    ) 
); 
 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));