<?php 
// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'legal_db' 
); 
 
// mysql db table to use 
$table = 'legal_tbl'; 
 
// Table's primary key 
$primaryKey = 'id'; 
 
$columns = array( 
    array( 'db' => 'court_type', 'dt' => 0 ), 
    array( 'db' => 'court_division',  'dt' => 1 ), 
    array( 'db' => 'case_type', 'dt' => 2 ), 
    array( 'db' => 'case_no',  'dt' => 3 ), 
     array( 'db' => 'case_date', 'dt' => 4 ), 
    // array( 'db' => 'case_duration',  'dt' => 5 ), 
    array( 'db' => 'reason_of_case', 'dt' => 5),
     
    array( 'db' => 'plaintiff_name',  'dt' => 6 ), 
    array( 'db' => 'defendant_name', 'dt' => 7 ), 
          
    array( 
        'db'        => 'id',
        'dt'        => 8,
        'formatter' => function( $d, $row ) { 
            return '
    <div class="btn-group">
    <a href="javascript:void(0)" class="btn btn-view ml-1" id="'.$row['id'].'" style="background-color: blue; color: white;">
        <i class="far fa-eye" style="font-size:17px"></i>
    </a>
    
    </div>';
           
        } 
    
    ) 
); 

 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));