<?php
session_start();
include('db/db.php');

if(isset($_POST['submit']) && isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

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

    // Update the record in the database
    $update_query = "UPDATE land_tbl SET 
        org_name = '$org_name', 
        land_type = '$land_type', 
        division = '$division', 
        district = '$district', 
        upazilla_thana = '$upazilla_thana', 
        jl_no = '$jl_no', 
        mouza = '$mouza', 
        owner_name = '$owner_name', 
        khatian_cs = '$khatian_cs', 
        khatian_rs = '$khatian_rs', 
        khatian_as = '$khatian_as', 
        khatian_bs = '$khatian_bs', 
        dag_cs = '$dag_cs', 
        dag_rs = '$dag_rs', 
        dag_as = '$dag_as', 
        dag_bs = '$dag_bs', 
        land_size = '$land_size', 
        proprietary_source = '$proprietary_source' ,
        namjaree_caseno_date = '$namjaree_caseno_date', 
        land_development_taxpayment = '$land_development_taxpayment', 
        
        boundary_wall = '$boundary_wall' 

        WHERE id = '$id'";

    if(mysqli_query($conn, $update_query)) {
        header("Location: edit_land_data.php?submitted=successfully");
        // header("Location: edit_legal_data.php?submitted=" . $_SESSION['id']);
        exit(); // It's good practice to call exit() after header redirection to stop further execution

    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Form submission error.";
}
?>
