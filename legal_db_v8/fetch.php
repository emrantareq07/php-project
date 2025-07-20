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
    array( 'db' => 'case_duration',  'dt' => 5 ), 
     
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

    <button type="button" name="view" id="'.$row["id"].'" class="btn btn-warning btn-xs view"><i class="fa fa-edit" style="font-size:15px"></i> </button>
    <hr class="my-1">
    
    </div>';
           
        } 
    //     <a href="javascript:void(0)" class="btn btn-danger btn-delete ml-1" data-id="'.$row['id'].'">
    //     <i class="far fa-trash-alt" style="font-size:17px"></i>
    // </a>
    ) 
); 

 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));