<?php
session_name('blrr');
session_start();
include_once '../db/database_old.php';
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

    $sql = "SELECT * FROM rri 
        WHERE (ref_number LIKE '%$query%' 
        OR d_number LIKE '%$query%' 
        OR subject LIKE '%$query%')         
        ORDER BY id DESC";

    $result = mysqli_query($conn_old, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) { // Corrected the variable here
            // Combine destination fields into a single string
            $result_destination = implode(', ', array_filter([ 
                $row['dt'], $row['dc'], $row['df'], $row['dp'], $row['dpl'], $row['sacretry'], 
                $row['cop'], $row['marketing'], $row['audit'], $row['emd'], $row['saksha'], 
                $row['law'], $row['company'], $row['purchase'], $row['ict'], $row['other_des']
            ], fn($value) => $value !== ''));

            // Combine "from" and division/department
            $from = htmlspecialchars($row["from"], ENT_QUOTES, 'UTF-8');
            if (!empty($row["div_dept"])) {
                $from .= ', ' . htmlspecialchars($row["div_dept"], ENT_QUOTES, 'UTF-8');
            }
            ?>
            <tr>
                <td><?php echo englishToBanglaNumber($i); ?></td> <!-- Display descending row number -->
                <td><?php echo englishToBanglaNumber($row["entry_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["to_l"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row["d_number"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row["ref_number"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo englishToBanglaNumber($row["send_date"]); ?></td>
                <td><?php echo $from; ?></td>
                <td><?php echo htmlspecialchars($row["subject"], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($result_destination, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo englishToBanglaNumber($row["dis_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["by"], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php
            $i--; // Decrease the counter for each row
        }
    } else {
        echo '<tr><td colspan="11" class="text-center text-warning">No records found.</td></tr>';
    }
}
?>
