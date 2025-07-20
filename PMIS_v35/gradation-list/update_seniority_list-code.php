<?php
session_start();
include('../db/db.php');

$emp_id = $_SESSION['emp_id'];
$seniority_no = $_SESSION['seniority_no'];
$seniority_no_old = $_SESSION['seniority_no'];

if (isset($_POST['update'])) {

$new_seniority_no1 = $_POST['new_seniority_no'];
$new_seniority_no = ($new_seniority_no1 !== '') ? $new_seniority_no1 : null;

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("UPDATE basic_info SET seniority_no = ? WHERE emp_id = ?");
$stmt->bind_param("ss", $new_seniority_no, $emp_id);
$stmt->execute();
$stmt->close();

    if ($new_seniority_no < $seniority_no && $new_seniority_no!='') {
        $sql1 = "SELECT * FROM basic_info WHERE seniority_no BETWEEN $new_seniority_no AND $seniority_no AND emp_id != '$emp_id'";
        $result1 = mysqli_query($conn, $sql1);

        if (!$result1) {
            die('SQL Error: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_array($result1)) {
            $emp_id_db = $row['emp_id'];
            $seniority_no_db = $row['seniority_no'] + 1;

            $sql_update = "UPDATE basic_info SET seniority_no = $seniority_no_db WHERE emp_id = '$emp_id_db'";
            $result_update = mysqli_query($conn, $sql_update);

            if (!$result_update) {
                die('SQL Error: ' . mysqli_error($conn));
            }
        }
        header("Location: update_seniority_list.php?submitted=successfully");
        exit();

    // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();
    }

    elseif ($seniority_no=='') {

       $sql2 = "SELECT Max(seniority_no) AS seniority_no FROM basic_info";
        $result2 = mysqli_query($conn, $sql2);
        $row2= mysqli_fetch_array($result2);
        $seniority_no_max=$row2['seniority_no'];

        if($seniority_no_max<$new_seniority_no){
            header("Location: update_seniority_list.php?submitted=successfully");
        exit();
        //             echo '<script>window.location.href = "update_seniority_list.php?submitted=successfully";</script>';
        // exit();
       // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();

        }else{
        $sql1 = "SELECT * FROM basic_info WHERE seniority_no BETWEEN $new_seniority_no AND $seniority_no_max AND emp_id != '$emp_id'";
        $result1 = mysqli_query($conn, $sql1);

        if (!$result1) {
            die('SQL Error: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_array($result1)) {
            $emp_id_db = $row['emp_id'];
            $seniority_no_db = $row['seniority_no'] + 1;

            $sql_update = "UPDATE basic_info SET seniority_no = $seniority_no_db WHERE emp_id = '$emp_id_db'";
            $result_update = mysqli_query($conn, $sql_update);

            if (!$result_update) {
                die('SQL Error: ' . mysqli_error($conn));
            }
        }
        header("Location: update_seniority_list.php?submitted=successfully");
        exit();

        // echo '<script>window.location.href = "update_seniority_list.php?submitted=successfully";</script>';
        // exit(); 
    // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();
     }
    
       
    }

    elseif($new_seniority_no==''){
         $sql2 = "SELECT Max(seniority_no) AS seniority_no FROM basic_info";
        $result2 = mysqli_query($conn, $sql2);
        $row2= mysqli_fetch_array($result2);
        $seniority_no_max=$row2['seniority_no'];
        if($seniority_no_max==$seniority_no_old) {
            header("Location: update_seniority_list.php?submitted=successfully");
        exit();
            // echo '<script>window.location.href = "update_seniority_list.php?submitted=successfully";</script>';
            // exit(); 
    // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();
            }
        else {
             $seniority_no_old=$seniority_no_old+1;
            $sql4= "SELECT * FROM basic_info WHERE seniority_no BETWEEN $seniority_no_old AND $seniority_no_max AND emp_id != '$emp_id'";
            $result4 = mysqli_query($conn, $sql4);

        if (!$result4) {
            die('SQL Error: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_array($result4)) {
            $emp_id_db = $row['emp_id'];
            $seniority_no_db = $row['seniority_no'] - 1;

            $sql_update = "UPDATE basic_info SET seniority_no = $seniority_no_db WHERE emp_id = '$emp_id_db'";
            $result_update = mysqli_query($conn, $sql_update);

            if (!$result_update) {
                die('SQL Error: ' . mysqli_error($conn));
            }
        }
        header("Location: update_seniority_list.php?submitted=successfully");
        exit();
        // echo '<script>window.location.href = "update_seniority_list.php?submitted=successfully";</script>';
        // exit(); 
    // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();
        }
    }

    else {
        $sql2 = "SELECT * FROM basic_info WHERE seniority_no BETWEEN $seniority_no AND $new_seniority_no AND emp_id != '$emp_id'";
        $result2 = mysqli_query($conn, $sql2);

        if (!$result2) {
            die('SQL Error: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_array($result2)) {
            $emp_id_db = $row['emp_id'];
            $seniority_no_db = $row['seniority_no'] - 1;

            $sql_update = "UPDATE basic_info SET seniority_no = $seniority_no_db WHERE emp_id = '$emp_id_db'";
            $result_update = mysqli_query($conn, $sql_update);

            if (!$result_update) {
                die('SQL Error: ' . mysqli_error($conn));
            }
        }
        header("Location: update_seniority_list.php?submitted=successfully");
        exit();
        // echo '<script>window.location.href = "update_seniority_list.php?submitted=successfully";</script>';
        // exit();
    // Set a session variable for success message
    // $_SESSION['update_success'] = true;

    // // Redirect to the update_seniority_list.php page
    // header("Location: update_seniority_list.php");
    // exit();
    }
}
?>
