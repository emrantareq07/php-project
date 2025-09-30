<?php
session_name('blrr');
session_start();
include_once '../db/database.php';
$table_name = $_SESSION['table_name'];

function banglaToEnglishNumber($banglaNumber) {
    // Define arrays for Bengali to English numeral conversion
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    // Replace Bengali numerals with English numerals
    $englishNumber = str_replace($banglaDigits, $englishDigits, $banglaNumber);
    return $englishNumber;
}

function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
    }

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    // Search query based on ref_number
    // $sql = "SELECT * FROM $table_name WHERE ref_number LIKE '%$query%' AND immediate_sender_office='' ORDER BY id DESC";
    $todate = date('Y-m-d');
    $sql = "SELECT * FROM $table_name 
        WHERE (ref_number LIKE '%$query%' 
        OR d_number LIKE '%$query%' 
        OR subject LIKE '%$query%') 
        AND immediate_sender_office='' 
        AND entry_date ='$todate'
        ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $unique_id = $row["unique_id"];
            $table_parent = preg_replace('/[^a-zA-Z]/', '', $unique_id);
            $entry_date = $row["entry_date"];
            $distribution_date = $row["distribution_date"];
            $destination = $row["destination"];
            $destination_drop = rtrim($row["destination_drop"], ',');
            if ($destination != '') {
                $destination_drop .= ', ' . $destination;
            }
            $d_number = $row["d_number"];
            $send_date = $row["send_date"];
            
            echo "<tr id='{$row["id"]}' class='text-center'>
            <td>" . englishToBanglaNumber($i) . "</td>";

            // Conditionally add the entry date if the table name is 'chairman'
            if ($table_name == 'chairman') {
                echo "<td>" . englishToBanglaNumber($entry_date) . "</td>";
            }

            echo "<td>{$row["recipient"]}</td>
                  <td>" . englishToBanglaNumber($row["d_number"]) . "</td>
                  <td>" . englishToBanglaNumber($row["ref_number"]) . "</td>
                  <td>" . englishToBanglaNumber($send_date) . "</td>
                  <td>{$row["sender"]}</td>
                  <td>{$row["div_dept_office"]}</td>
                  <td>{$row["subject"]}</td>
                  <td>{$destination_drop}</td>
                  <td>" . englishToBanglaNumber($distribution_date) . "</td>
                  <td>{$row["medium"]}</td>
                  <td>
                      <a href='#viewEmployeeModal' class='edit' data-toggle='modal' style='text-decoration: none'>
                          <i class='fa fa-eye' style='font-size:20px; color:blue;'></i>
                      </a>
                      <a href='edit.php?id={$row['id']}' class=''>
                          <i class='fa fa-edit' style='font-size:20px; color:black;'></i>
                      </a>
                  </td>
                </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='13' class='text-center text-danger'>No Record Found!</td></tr>";
    }
}
?>
