<?php 
// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'land_db' 
); 

// MySQL db table to use 
$table = 'land_tbl'; 

// Table's primary key 
$primaryKey = 'id'; 

// Table columns
$columns = array( 
    array( 'db' => 'org_name',  'dt' => 0 ), 
    array( 'db' => 'land_type', 'dt' => 1 ), 
    array( 'db' => 'division',  'dt' => 2 ), 
    array( 'db' => 'district', 'dt' => 3 ), 
    array( 'db' => 'upazilla_thana',  'dt' => 4 ), 
    array( 'db' => 'jl_no',  'dt' => 5 ), 
    array( 'db' => 'mouza', 'dt' => 6), 
    array( 'db' => 'land_size', 'dt' => 7 ),
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
    ) 
); 

// Include SQL query processing class 
require 'ssp.class.php'; 

// No need to modify the SSP::simple function for sorting
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns )
);
?>
