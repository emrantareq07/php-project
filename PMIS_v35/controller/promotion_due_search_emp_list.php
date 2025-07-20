<?php
session_start(); // Start session at the beginning

include('../db/db.php');
include('../includes/header-dashboard.php');

if (isset($_POST['submit'])) {
   
    $target_date1 = mysqli_real_escape_string($conn, $_POST['target_date']);

    $target_date2 = array();
    $target_date2[] = $target_date1;
    $_SESSION['target_date2'] = $target_date2;

    $target_date = new DateTime($target_date1);
       
    $designation =  $_POST['designation']; 
    
    
    if (isset($_POST['sub_cadre_header'])) {
    // If it exists, assign its value to the $sub_cadre_header variable
    $sub_cadre_header = $_POST['sub_cadre_header'];
    
}else{
    $sub_cadre_header = '';
}
    $emp_ids = array();

    $sql1="SELECT * FROM job_desc where job_status='In Service'";
    $result1 = mysqli_query($conn, $sql1);


    ?>


 <div class="container mt-1 ">
            <div class="card shadow-lg">
                <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">
                   Promotion Due Employees List
                        
                    </h3></div>
                <div class="card-body bg-white">
                    <div class="row">
                        <!--1st col-->
                        <div class="col-sm-4 "> </div>
                        <!--2nd col-->
                        <div class="col-sm-4">
                            <?php

                            while ($row1 = mysqli_fetch_array($result1)) {
                               $emp_id=$row1['emp_id'];
                               $doj=$row1['doj'];

                                $last_promo_date_db =$row1['last_promo_date'];
                                if($last_promo_date_db=='0000-00-00'){
                                    $last_promo_date1 = new DateTime($row1['doj']);

                                    $last_promo_date =$last_promo_date1->diff($target_date);
                                    $last_promotion_year=$last_promo_date->y;
                                }else{
                                    $last_promo_date1 = new DateTime($row1['last_promo_date']);

                                    $last_promo_date =$last_promo_date1->diff($target_date);
                                    $last_promotion_year=$last_promo_date->y;
                                }

                                    // echo $last_promotion_year.'<br>';
                                    
                                    if($last_promotion_year>=5){

                                     if($sub_cadre_header==''){
                                        $sql = "SELECT * FROM  basic_info WHERE emp_id='$emp_id' and   designation = '$designation'";

                                      $result = mysqli_query($conn, $sql);
                                      $row = mysqli_fetch_array($result);
                                            if ($row) {

                                        echo "SNR NO: " . $row['seniority_no'] . "<br>";
                                        echo "EMP ID: " . $row['emp_id'] . "<br>";
                                        echo "<br>";
                                         $emp_ids[] = $row['emp_id'];
                                        $_SESSION['emp_ids'] = $emp_ids;

                                           }
                                                                    
                                     }
                                     else {
                                      $sql = "SELECT * FROM  basic_info WHERE emp_id='$emp_id' and   designation = '$designation' and sub_cadre_header='$sub_cadre_header'" ;
                                      $result = mysqli_query($conn, $sql);
                                      $row = mysqli_fetch_assoc($result);
                                            if ($row) {
                                        echo "SNR NO: " . $row['seniority_no'] . "<br>";
                                        echo "EMP ID: " . $row['emp_id'] . "<br>";
                                        echo "<br>";
                                         $emp_ids[] = $row['emp_id'];
                                       $_SESSION['emp_ids'] = $emp_ids;
                                       
                                           }                                                                         
                                        }
                                
                                    }                                                              
                                }

                            ?>
                             <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">
                    <?php
                    $numberOfEmployees = count($emp_ids);
                 echo  "Total Found : " . $numberOfEmployees . "<br>";
                    ?>
                        
                    </h3></div>
                        </div>
                        <!-- Add a print button -->
                        <div class="col-sm-4">
                            <center>
                                <a href="#" id="printButton" class="btn btn-danger text-white">
                                    <i class="fa fa-print" style="color:white"></i> Print
                                </a>
                                <a href="promotion_due_search.php" class="btn btn-primary text-white">
                                    <i class="fa fa-left-arrow" style="color:white"></i> Previous Page
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
 //}

?>

<?php include('../includes/footer.php'); ?>

<script>

    document.getElementById("printButton").addEventListener("click", function() {
        
    printContent("promotion_due_search_emp_list_print.php", " ");
    
    });

    function printContent(url, title) {
        let printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');

        let emp_ids = <?php echo isset($_SESSION['emp_ids']) ? json_encode($_SESSION['emp_ids']) : 'null'; ?>;
        let target_date2 = <?php echo isset($_SESSION['target_date2']) ? json_encode($_SESSION['target_date2'][0]) : 'null'; ?>;

        var xhr = new XMLHttpRequest();
        var urlToFetch = url + "?emp_ids=" + emp_ids.join(',') + "&target_date2=" + target_date2;
        xhr.open("GET", urlToFetch, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var contentToPrint = `
                    <html>
                    <head>
                        <title>PMIS</title>
                    </head>
                    <body>
                        <center><b><h2 class='text-center text-muted text-uppercase'>${title}</h2></b></center>
                        ${xhr.responseText}
                    </body>
                    </html>
                `;

                printWindow.document.open();
                printWindow.document.write(contentToPrint);
                printWindow.document.close();

                printWindow.onload = function() {
                    printWindow.onafterprint = function() {
                        printWindow.close();
                    };
                    printWindow.print();
                };
            }
        };
        xhr.send();
    }


</script>