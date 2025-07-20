<?php
// Database connection info 
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = "legal_db";
$conn = mysqli_connect($host, $username, $password, $dbname);

 function englishToBanglaNumber($number) {
        $englishNumbers = range(0, 9);
        $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

        return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
    }


if (isset($_POST['mode']) && !empty($_POST['mode'])) {
// Add data
if ($_POST['mode'] === 'add') {
    $court_type_id = $_POST['court_type'];
    $court_division_id = $_POST['court_division'];
    $case_type_id = $_POST['case_type'];

    // Fetch court type name
    $court_type_query = "SELECT type FROM court_type WHERE id = '$court_type_id'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $court_type = $court_type_row['type'];

    // Fetch court division name
    $court_division_query = "SELECT court_division_name FROM court_division WHERE id = '$court_division_id'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $court_division = $court_division_row['court_division_name'];

    // Fetch case type name
    $case_type_query = "SELECT type FROM case_type WHERE id = '$case_type_id'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);
    
   // Fetch id for bn
    $case_id_query = "SELECT MAX(id) AS max_id FROM legal_tbl";
    $case_id_result = mysqli_query($conn, $case_id_query);

    if ($case_id_result && mysqli_num_rows($case_id_result) > 0) {
        $case_id_row = mysqli_fetch_assoc($case_id_result);
        $max_id = $case_id_row['max_id'];
        $max_id = $max_id + 1;
    } else {
        // If no records found, start from 1
        $max_id = 1;
    }

    $id_bn = englishToBanglaNumber($max_id);

   


$case_type = $case_type_row['type'];
$case_no=$_POST['case_no'];
 $case_date=$_POST['case_date'];
 $concate3col=$case_type.' নং-'.$case_no.'/'.$case_date;
 
 $case_duration=$_POST['case_duration'];
 
 $reason_of_case=$_POST['reason_of_case'];
 $plaintiff_name=$_POST['plaintiff_name'];
 $defendant_name=$_POST['defendant_name'];
 
 $lower_name_address=$_POST['lower_name_address'];
 $case_filing_date=$_POST['case_filing_date'];

 $hearing_date=$_POST['hearing_date'];
 $hearing_result=$_POST['hearing_result'];
 $next_hearing_result_date=$_POST['next_hearing_result_date'];
 
 $case_filing_accept_steps=$_POST['case_filing_accept_steps'];
 $panel_lower_info=$_POST['panel_lower_info'];
 $panel_low_adv_info=$_POST['panel_low_adv_info'];
 $remarks=$_POST['remarks'];
     
  mysqli_query($conn, "INSERT INTO legal_tbl (id_bn,court_type,court_division,case_type,case_no,
 case_date,case_duration,reason_of_case, plaintiff_name,defendant_name,lower_name_address,case_filing_date,hearing_date,hearing_result,next_hearing_result_date,case_filing_accept_steps,panel_lower_info,panel_low_adv_info,remarks,concate3col) 
 VALUES('{$id_bn}','{$court_type}','{$court_division}','{$case_type}','{$case_no}',
 '{$case_date}','{$case_duration}','{$reason_of_case}','{$plaintiff_name}','{$defendant_name}',
 '{$lower_name_address}','{$case_filing_date}','{$hearing_date}','{$hearing_result}','{$next_hearing_result_date}','{$case_filing_accept_steps}','{$panel_lower_info}','{$panel_low_adv_info}','{$remarks}','{$concate3col}')");

    // Insert into database
    // mysqli_query($conn, "INSERT INTO legal_tbl (court_type, court_division, case_type) VALUES ('$court_type', '$court_division', '$case_type')");
    echo json_encode(true);
}

// Edit data
 if ($_POST['mode'] === 'edit') {
        $id = $_POST['id'];

        // Fetch data by id
        $result = mysqli_query($conn, "SELECT * FROM legal_tbl WHERE id = '$id'");
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
        exit; // Make sure to exit after echoing JSON response
    }


// Update data
if ($_POST['mode'] === 'update') {
    $id = $_POST['id'];
    $court_type_id = $_POST['court_type'];
    $court_division_id = $_POST['court_division'];
    $case_type_id = $_POST['case_type'];

    // Fetch court type name
    $court_type_query = "SELECT type FROM court_type WHERE id = '$court_type_id'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $court_type1 = $court_type_row['type'];

    // Fetch court division name
    $court_division_query = "SELECT court_division_name FROM court_division WHERE id = '$court_division_id'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $court_division1 = $court_division_row['court_division_name'];

    // Fetch case type name
    $case_type_query = "SELECT type FROM case_type WHERE id = '$case_type_id'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);

    $case_type1 = $case_type_row['type'];

$case_no1=$_POST['case_no'];
 $case_date1=$_POST['case_date'];
  $concate3col=$case_type1.' নং-'.$case_no1.'/'.$case_date1;
 $case_duration1=$_POST['case_duration'];
 
 $reason_of_case1=$_POST['reason_of_case'];
 $plaintiff_name1=$_POST['plaintiff_name'];
 $defendant_name1=$_POST['defendant_name'];
 
 $lower_name_address1=$_POST['lower_name_address'];
 $case_filing_date1=$_POST['case_filing_date'];

 $hearing_date1=$_POST['hearing_date'];
 $hearing_result1=$_POST['hearing_result'];
 $next_hearing_result_date1=$_POST['next_hearing_result_date'];
 
 $case_filing_accept_steps1=$_POST['case_filing_accept_steps'];
 $panel_lower_info1=$_POST['panel_lower_info'];
 $panel_low_adv_info1=$_POST['panel_low_adv_info'];
 $remarks1=$_POST['remarks'];         

  mysqli_query($conn,"UPDATE legal_tbl set  court_type='" . $court_type1 . 
  "', court_division='" . $court_division1. 
  "', case_type='" . $case_type1 . 
  "' , case_no='" . $case_no1 .
 "', case_date='" . $case_date1 . 
 "', case_duration='" . $case_duration1.
 "', reason_of_case='" . $reason_of_case1 . 
 "', plaintiff_name='" . $plaintiff_name1 . 
 "', defendant_name='" . $defendant_name1.
 "', lower_name_address='" . $lower_name_address1.
 "', case_filing_date='" . $case_filing_date1 .
 "', hearing_date='" . $hearing_date1 .
 "', hearing_result='" . $hearing_result1 .
 "', next_hearing_result_date='" . $next_hearing_result_date1 .
 "', case_filing_accept_steps='" . $case_filing_accept_steps1 .
 "', panel_lower_info='" . $panel_lower_info1 .
 "', panel_low_adv_info='" . $panel_low_adv_info1.
 "', remarks='" . $remarks1 .
 "', concate3col='" . $concate3col ."'

 WHERE id='" . $_POST['id'] . "'");
    echo json_encode(true);

}

// Delete data
if ($_POST['mode'] === 'delete') {
    $id = $_POST["id"];
    mysqli_query($conn, "DELETE FROM legal_tbl WHERE id = '$id'");
    echo json_encode(true);
    }
}
?>
