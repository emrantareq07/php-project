<?php
session_start();
include('db/db.php');

if(isset($_POST['submit']) && isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Retrieve form data
    $court_type_id = $_POST['court_type'];

	
     $court_division_id = $_POST['court_division'];
    $case_type_id = $_POST['case_type'];

     // Fetch court type name

    $court_type_query = "SELECT type FROM court_type WHERE id = '$court_type_id'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $court_type = $court_type_row['type'];

 
    $court_division_query = "SELECT court_division_name FROM court_division WHERE id = '$court_division_id'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $court_division = $court_division_row['court_division_name'];
 

    $case_type_query = "SELECT type FROM case_type WHERE id = '$case_type_id'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);
    $case_type = $case_type_row['type'];
   
    // Fetch case type name

    $case_no = $_POST['case_no'];
    $case_date = $_POST['case_date'];
     $concate3col=$case_type.' নং-'.$case_no.'/'.$case_date;
    $case_duration = $_POST['case_duration'];
    $reason_of_case = $_POST['reason_of_case'];
    $plaintiff_name = $_POST['plaintiff_name'];
    $defendant_name = $_POST['defendant_name'];
    $lower_name_address = $_POST['lower_name_address'];
    $case_filing_date = $_POST['case_filing_date'];
    $hearing_date = $_POST['hearing_date'];

    $hearing_result = $_POST['hearing_result'];
    $next_hearing_result_date = $_POST['next_hearing_result_date'];

	if($hearing_result!="বিচারাধীন"){
		$next_hearing_result_date='';
	}
    $case_filing_accept_steps = $_POST['case_filing_accept_steps'];
    $panel_lower_info = $_POST['panel_lower_info'];
    $panel_low_adv_info = $_POST['panel_low_adv_info'];
    $remarks = $_POST['remarks'];

    // Update the record in the database
    $update_query = "UPDATE legal_tbl SET 
        court_type = '$court_type', 
        court_division = '$court_division', 
        case_type = '$case_type', 
        case_no = '$case_no', 
        case_date = '$case_date', 
        case_duration = '$case_duration', 
        reason_of_case = '$reason_of_case', 
        plaintiff_name = '$plaintiff_name', 
        defendant_name = '$defendant_name', 
        lower_name_address = '$lower_name_address', 
        case_filing_date = '$case_filing_date', 
        hearing_date = '$hearing_date', 
        hearing_result = '$hearing_result', 
        next_hearing_result_date = '$next_hearing_result_date', 
        case_filing_accept_steps = '$case_filing_accept_steps', 
        panel_lower_info = '$panel_lower_info', 
        panel_low_adv_info = '$panel_low_adv_info', 
        remarks = '$remarks' ,
        concate3col = '$concate3col' 

        WHERE id = '$id'";

    if(mysqli_query($conn, $update_query)) {
        header("Location: edit_legal_data.php?submitted=successfully");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Form submission error.";
}
?>
