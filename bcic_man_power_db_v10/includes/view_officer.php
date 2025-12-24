<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM officers_tbl WHERE id = '$id'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <table class="table table-bordered">
            <tr><th>ID</th><td><?php echo $row['id']; ?></td></tr>
            <tr><th>Factory Name</th><td><?php echo htmlspecialchars($row['factory_name']); ?></td></tr>
            <tr><th>Date</th><td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td></tr>
            <tr><th>Month' Year</th><td><?php echo date("F' Y", strtotime($row['date'])); ?></td></tr>
            <tr><th>Designation</th><td><?php echo htmlspecialchars($row['designation']); ?></td></tr>
            <tr><th>Grade</th><td><?php echo htmlspecialchars($row['grade']); ?></td></tr>
            <tr><th>Sanctioned Post</th><td><?php echo htmlspecialchars($row['sanctioned_post']); ?></td></tr>
            <tr><th>Male</th><td><?php echo htmlspecialchars($row['male']); ?></td></tr>
            <tr><th>Female</th><td><?php echo htmlspecialchars($row['female']); ?></td></tr>
            <tr><th>Total</th><td>
                <?php 
                $male_total = 0;
                $female_total = 0;
                
                if (!empty($row['male'])) {
                    $male_numbers = explode(',', $row['male']);
                    foreach ($male_numbers as $num) {
                        $male_total += intval(trim($num));
                    }
                }
                
                if (!empty($row['female'])) {
                    $female_numbers = explode(',', $row['female']);
                    foreach ($female_numbers as $num) {
                        $female_total += intval(trim($num));
                    }
                }
                
                echo $male_total + $female_total;
                ?>
            </td></tr>
            <tr><th>Status</th><td><?php echo ucfirst($row['status']); ?></td></tr>
            <tr><th>Created At</th><td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td></tr>
            <tr><th>Updated At</th><td><?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></td></tr>
        </table>
        <?php
    } else {
        echo '<div class="alert alert-danger">Officer not found!</div>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request!</div>';
}
?>