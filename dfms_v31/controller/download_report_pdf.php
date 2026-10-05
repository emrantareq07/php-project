<?php
session_start();
$session_date=$_SESSION['date'];
require('fpdf/fpdf.php'); // Include FPDF library

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = "";
$dbname = 'dfms_db';

$date = date('Y-m-d');
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Function to generate PDF
function generatePDF($html) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Write(5, $html);

    // Output PDF to browser (change destination as needed)
    $pdf->Output('dfms_report.pdf', 'D');
    exit;
}

// Check if the form is submitted
if(isset($_POST['submit'])) {
    $date = $_SESSION['date'];

    $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>DFMS Report</title>
    </head>
    <body>';

    $html .= '<table class="table table-bordered table-striped" id="table_content">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Factory Name</th>            
                        <th>Daily (MT)</th>
                        <th>Monthly (MT)</th>
                        <th>Yearly (MT)</th>
                        <th>Production Target (MT)</th>
                        <th>Due (MT)</th>
                        <th>Plant Load (%)</th>
                        <th>Causes of Shutdown</th>
                    </tr>
                </thead>
                <tbody>';

    // Fetching data from the database
    include('db/db.php');
    $month11=date('m',strtotime($date));
    $year11=date('Y',strtotime($date));

    if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
        $year22=$year11;
    }
    else{
        $year22=$year11-1;
    }

    $yearrange12="$year22-07-01";
    $year22=$year22+1;
    $yearrange13="$year22-06-30";

    if(isset($_POST['hit'])){
        $date=$_POST['date'];
        $_SESSION['date']= $_POST['date'];
        
        $sql="SELECT * FROM sfcl 
                WHERE `date` LIKE '{$date}%'
                UNION
                SELECT * FROM afccl 
                WHERE `date` LIKE '{$date}%'
                UNION
                SELECT * FROM jfcl 
                WHERE `date` LIKE '{$date}%' ORDER BY date desc";
     
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $html .= "<tr>
                            <td class='text-uppercase'>".$row['factory_name']."</td>
                            <td>".$row['date']."</td>
                            <td>".$row['daily']."</td>
                            <td>".$row['monthly']."</td>
                            <td>".$row['yearly']."</td>
                            <td>".$row['production_target']."</td>
                            <td>".$row['due']."</td>
                            <td>".$row['plant_load']."</td>
                            <td>".$row['remarks']."</td>
                        </tr>";
            }

            // Calculate total production
            $sfcl_sql="SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $sfcl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $sfcl_daily=$row['daily'];
                $sfcl_monthly=$row['monthly'];
                $sfcl_yearly=$row['yearly'];
                $sfcl_prod_tar=$row['production_target'];
                $sfcl_due=$row['due'];
            }
              
            $jfcl_sql="SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $jfcl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $jfcl_daily=$row['daily'];
                $jfcl_monthly=$row['monthly'];
                $jfcl_yearly=$row['yearly'];
                $jfcl_prod_tar=$row['production_target'];
                $jfcl_due=$row['due'];
            }              
              
            $afccl_sql="SELECT * FROM afccl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $afccl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $afccl_daily=$row['daily'];
                $afccl_monthly=$row['monthly'];
                $afccl_yearly=$row['yearly'];
                $afccl_prod_tar=$row['production_target'];
                $afccl_due=$row['due'];
            }
              
            $total_prod_daily=(int)$sfcl_daily+(int)$jfcl_daily+(int)$afccl_daily;
            $total_prod_monthly=(int)$sfcl_monthly+(int)$jfcl_monthly+(int)$afccl_monthly;
            $total_prod_yearly=(int)$sfcl_yearly+(int)$jfcl_yearly+(int)$afccl_yearly;
            $total_prod_tar = (int)$sfcl_prod_tar + (int)$jfcl_prod_tar + (int)$afccl_prod_tar;
            $total_due = (int)$sfcl_due + (int)$jfcl_due + (int)$afccl_due;
              
            $html .= "<tr>
                        <td></td>
                        <td>Total Production:</td>
                        <td>$total_prod_daily</td>
                        <td>$total_prod_monthly</td>
                        <td>$total_prod_yearly</td>
                        <td>$total_prod_tar</td>
                        <td>$total_due</td>
                    </tr>";
        } else {
            $html .= "<tr><td colspan='9' class='text-center text-danger'><b>No Record Found!!!</b></td></tr>";
        }
    } else {
        // Show default records
        $sql = "SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM afccl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";

        $result = mysqli_query($link, $sql);

        if ($result === false) {
            // Query failed
            echo "Error: " . mysqli_error($link);
            echo "<tr><td colspan='9' class='text-center text-danger'><b>No Record Found!!!</b></td></tr>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $table = $row['factory_name'];
                $date = $row['date'];
                $daily = $row['daily'];
                $monthly = $row['monthly'];
                $yearly = $row['yearly'];
                $remarks = $row['remarks'];

                $sql_target_table_retrive="SELECT  * FROM target_table where factory_name = '$table' And fiscalstart='$yearrange12'";
                $result1 = mysqli_query($conn, $sql_target_table_retrive);
                $row1 = mysqli_fetch_assoc($result1);    

                $target=$row1['target'];
                $due=$target-$yearly;

                $html .= "<tr>
                            <td>$date</td>
                            <td class='text-uppercase'>$table</td>
                            <td>$daily</td>
                            <td>$monthly</td>
                            <td>$yearly</td>
                            <td>$target</td>
                            <td>$due</td>
                            <td>$remarks</td>
                            </tr>";
            }
            $sfcl_sql="SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $sfcl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $sfcl_daily=$row['daily'];
                $sfcl_monthly=$row['monthly'];
                $sfcl_yearly=$row['yearly'];
            }
              
            $jfcl_sql="SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $jfcl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $jfcl_daily=$row['daily'];
                $jfcl_monthly=$row['monthly'];
                $jfcl_yearly=$row['yearly'];
            }              
              
            $afccl_sql="SELECT * FROM afccl WHERE `date` LIKE '{$date}%'";
            $result = mysqli_query($conn, $afccl_sql);

            while($row = mysqli_fetch_assoc($result)) {
                $afccl_daily=$row['daily'];
                $afccl_monthly=$row['monthly'];
                $afccl_yearly=$row['yearly'];
            }
              
            $total_prod_daily=(int)$sfcl_daily+(int)$jfcl_daily+(int)$afccl_daily;
            $total_prod_monthly=(int)$sfcl_monthly+(int)$jfcl_monthly+(int)$afccl_monthly;
            $total_prod_yearly=(int)$sfcl_yearly+(int)$jfcl_yearly+(int)$afccl_yearly;
              
            $html .= "<tr>
                        <td></td>
                        <td>Total Production:</td>
                        <td>$total_prod_daily</td>
                        <td>$total_prod_monthly</td>
                        <td>$total_prod_yearly</td>
                    </tr>";
        }
    }

    $html .= '</tbody></table></body></html>';

    // Generate PDF
    generatePDF($html);
}

// Your existing PHP code here for database connection and other functionalities
// Make sure to replace <?php ... ?> with your actual PHP code
?>
