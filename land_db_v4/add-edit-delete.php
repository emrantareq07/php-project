<?php
// Database connection info 
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = "land_db";
$conn = mysqli_connect($host, $username, $password, $dbname);

 // function englishToBanglaNumber($number) {
 //        $englishNumbers = range(0, 9);
 //        $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

 //        return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
 //    }


if (isset($_POST['mode']) && !empty($_POST['mode'])) {
// Add data
if ($_POST['mode'] === 'add') {

    $org_name_id = $_POST['org_name'];

    $division_id = $_POST['division'];
    $district_id = $_POST['district'];

    // Fetch   org_name
    $court_type_query = "SELECT org_name FROM org_tbl WHERE id = '$org_name_id'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $org_name = $court_type_row['org_name'];

    // Fetch court division name
    $court_division_query = "SELECT bn_name FROM divisions WHERE id = '$division_id'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $division = $court_division_row['bn_name'];

    // Fetch  districts name
    $case_type_query = "SELECT bn_name FROM districts WHERE id = '$district_id'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);
    $district = $case_type_row['bn_name'];
   // Fetch id for bn
    // $case_id_query = "SELECT MAX(id) AS max_id FROM legal_tbl";
    // $case_id_result = mysqli_query($conn, $case_id_query);

    // if ($case_id_result && mysqli_num_rows($case_id_result) > 0) {
    //     $case_id_row = mysqli_fetch_assoc($case_id_result);
    //     $max_id = $case_id_row['max_id'];
    //     $max_id = $max_id + 1;
    // } else {
    //     // If no records found, start from 1
    //     $max_id = 1;
    // }

    // $id_bn = englishToBanglaNumber($max_id);

   
 $land_type=$_POST['land_type'];
 $upazilla_thana=$_POST['upazilla_thana'];
 //$concate3col=$case_type.' নং-'.$case_no.'/'.$case_date;
 
 $jl_no=$_POST['jl_no']; 
 $mouza=$_POST['mouza'];
 $owner_name=$_POST['owner_name'];
 $khatian_cs=$_POST['khatian_cs']; 
 $khatian_rs=$_POST['khatian_rs'];
 $khatian_as=$_POST['khatian_as'];
 $khatian_bs=$_POST['khatian_bs'];

 $dag_cs=$_POST['dag_cs'];
 $dag_rs=$_POST['dag_rs']; 
 $dag_as=$_POST['dag_as']; 
 $dag_bs=$_POST['dag_bs'];

 $land_size=$_POST['land_size'];
 $proprietary_source=$_POST['proprietary_source'];
  $namjaree_caseno_date=$_POST['namjaree_caseno_date']; 
 $land_development_taxpayment=$_POST['land_development_taxpayment']; 
 $boundary_wall=$_POST['boundary_wall']; 
     
  mysqli_query($conn, "INSERT INTO land_tbl (org_name,land_type,division,district,upazilla_thana,jl_no,
 mouza,owner_name,khatian_cs, khatian_rs,khatian_as,khatian_bs,dag_cs,dag_rs,dag_as,dag_bs,land_size,proprietary_source,namjaree_caseno_date,land_development_taxpayment,boundary_wall) 
 VALUES('{$org_name}','{$land_type}','{$division}','{$district}','{$upazilla_thana}','{$jl_no}',
 '{$mouza}','{$owner_name}','{$khatian_cs}','{$khatian_rs}','{$khatian_as}',
 '{$khatian_bs}','{$dag_cs}','{$dag_rs}','{$dag_as}','{$dag_bs}','{$land_size}','{$proprietary_source}','{$namjaree_caseno_date}','{$land_development_taxpayment}','{$boundary_wall}')");

    echo json_encode(true);
}

// Edit data
 if ($_POST['mode'] === 'edit') {
        $id = $_POST['id'];

        // Fetch data by id
        $result = mysqli_query($conn, "SELECT * FROM land_tbl WHERE id = '$id'");
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
        exit; // Make sure to exit after echoing JSON response
    }


// Update data
if ($_POST['mode'] === 'update') {
    $id = $_POST['id'];
       $org_name_id = $_POST['org_name'];

    $division_id = $_POST['division'];
    $district_id = $_POST['district'];

    // Fetch   org_name
    $court_type_query = "SELECT org_name FROM org_tbl WHERE id = '$org_name_id'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $org_name = $court_type_row['org_name'];

    // Fetch court division name
    $court_division_query = "SELECT bn_name FROM divisions WHERE id = '$division_id'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $division = $court_division_row['bn_name'];

    // Fetch  districts name
    $case_type_query = "SELECT bn_name FROM districts WHERE id = '$district_id'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);
    $district = $case_type_row['bn_name'];
   // Fetch id for bn
    // $case_id_query = "SELECT MAX(id) AS max_id FROM legal_tbl";
    // $case_id_result = mysqli_query($conn, $case_id_query);

    // if ($case_id_result && mysqli_num_rows($case_id_result) > 0) {
    //     $case_id_row = mysqli_fetch_assoc($case_id_result);
    //     $max_id = $case_id_row['max_id'];
    //     $max_id = $max_id + 1;
    // } else {
    //     // If no records found, start from 1
    //     $max_id = 1;
    // }

    // $id_bn = englishToBanglaNumber($max_id);

   
 $land_type=$_POST['land_type'];
 $upazilla_thana=$_POST['upazilla_thana'];
 //$concate3col=$case_type.' নং-'.$case_no.'/'.$case_date;
 
 $jl_no=$_POST['jl_no']; 
 $mouza=$_POST['mouza'];
 $owner_name=$_POST['owner_name'];
 $khatian_cs=$_POST['khatian_cs']; 
 $khatian_rs=$_POST['khatian_rs'];
 $khatian_as=$_POST['khatian_as'];
 $khatian_bs=$_POST['khatian_bs'];

 $dag_cs=$_POST['dag_cs'];
 $dag_rs=$_POST['dag_rs']; 
 $dag_as=$_POST['dag_as']; 
 $dag_bs=$_POST['dag_bs'];

 $land_size=$_POST['land_size'];
 $proprietary_source=$_POST['proprietary_source'];
  $namjaree_caseno_date=$_POST['namjaree_caseno_date']; 
 $land_development_taxpayment=$_POST['land_development_taxpayment']; 
 $boundary_wall=$_POST['boundary_wall'];         

  mysqli_query($conn,"UPDATE land_tbl set  org_name='" . $org_name . 
  "', land_type='" . $land_type. 
  "', division='" . $division . 
  "' , district='" . $district .
 "', upazilla_thana='" . $upazilla_thana . 
 "', jl_no='" . $jl_no.
 "', mouza='" . $mouza . 
 "', owner_name='" . $owner_name . 
 "', khatian_cs='" . $khatian_cs.
 "', khatian_rs='" . $khatian_rs.
 "', khatian_as='" . $khatian_as .
 "', khatian_bs='" . $khatian_bs .
 "', dag_cs='" . $dag_cs .
 "', dag_rs='" . $dag_rs .
 "', dag_as='" . $dag_as .
 "', dag_bs='" . $dag_bs .
 "', land_size='" . $land_size.
 "', proprietary_source='" . $proprietary_source .
 "', namjaree_caseno_date='" . $namjaree_caseno_date .
 "', land_development_taxpayment='" . $land_development_taxpayment .
 "', boundary_wall='" . $boundary_wall ."'

 WHERE id='" . $_POST['id'] . "'");
    echo json_encode(true);

}

// Delete data
if ($_POST['mode'] === 'delete') {
    $id = $_POST["id"];
    mysqli_query($conn, "DELETE FROM land_tbl WHERE id = '$id'");
    echo json_encode(true);
    }
}
?>
