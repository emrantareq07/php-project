<?php
session_start();
include('../db/database_2.php');
include('../controller/function.php');
include('../db/db.php');

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

// Check the user role
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .table-responsive {
            max-height: 580px; /* Set the max height of the table container */
            overflow-y: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            z-index: 1; /* Make sure the header is above the table body */
            background-color: #343a40; /* Background color to match .table-dark */
            color: white; /* Text color to match .table-dark */
        }
        body {
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="container my-2">   
        <div class="row">
            <div class="col">
                <a href="admin_dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i>  Previous Page</a>
                <div class="table-responsive table-scroll shadow-lg border border-primary rounded">
                    <table class="table table-bordered table-striped table-light">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Organization Name</th>
                                <th scope="col">Staff/Officers</th>
                                <th scope="col">Male/Female</th>
                                <th scope="col">Total</th>
                                <th scope="col">Pie Chart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sub_total = 0;
                            $chartCount = 1;
                            $sql = "SELECT DISTINCT place_of_posting FROM job_desc";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $place_of_posting = $row['place_of_posting'];
                            ?>
                                <tr>
                                    <td scope="row" rowspan="4"><h5><?php echo $place_of_posting ?></h5></td>
                                    <?php
                                    $sql1 = "SELECT e.employee_type, e.gender 
                                             FROM basic_info e
                                             JOIN job_desc d ON e.emp_id = d.emp_id 
                                             WHERE d.job_status='In Service' AND d.place_of_posting='$place_of_posting'";

                                    $result1 = mysqli_query($conn, $sql1);
                                    $totalStaff = 0;
                                    $totalOfficer = 0;
                                    $staffMaleCount = 0;
                                    $staffFemaleCount = 0;
                                    $officerMaleCount = 0;
                                    $officerFemaleCount = 0;

                                    while ($row1 = mysqli_fetch_assoc($result1)) {
                                        $employee_type = $row1['employee_type'];
                                        $gender = $row1['gender'];

                                        if ($employee_type == 'Staff') {
                                            $totalStaff += 1;
                                        } else {
                                            $totalOfficer += 1;
                                        }

                                        if ($gender == 'Male' && $employee_type == 'Staff') {
                                            $staffMaleCount += 1;
                                        } elseif ($gender == 'Female' && $employee_type == 'Staff') {
                                            $staffFemaleCount += 1;
                                        } elseif ($gender == 'Male' && $employee_type == 'Officer') {
                                            $officerMaleCount += 1;
                                        } elseif ($gender == 'Female' && $employee_type == 'Officer') {
                                            $officerFemaleCount += 1;
                                        }
                                    }

                                    $sum = $totalStaff + $totalOfficer;
                                    $chartId = 'piechart' . $chartCount++;
                                    $sub_total += $sum;
                                    ?>
                                    <td rowspan="2">Officers: <?php echo $totalOfficer ?></td>
                                    <td>Male: <?php echo $officerMaleCount ?></td>
                                    <td scope="row" rowspan="4" class="align-middle text-center"><?php echo $sum ?></td>
                                    <td scope="row" rowspan="4">
                                      <span id="<?php echo $chartId; ?>"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Female: <?php echo $officerFemaleCount ?></td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Staff: <?php echo $totalStaff ?></td>
                                    <td>Male: <?php echo $staffMaleCount ?></td>
                                </tr>
                                <tr>
                                    <td>Female: <?php echo $staffFemaleCount ?></td>
                                </tr>
                                <script type="text/javascript">
                                    google.charts.load('current', {'packages':['corechart']});
                                    google.charts.setOnLoadCallback(function () {
                                        drawChart('<?php echo $chartId; ?>', <?php echo $officerMaleCount; ?>, <?php echo $officerFemaleCount; ?>);
                                    });

                                    function drawChart(chartId, officerMaleCount, officerFemaleCount) {
                                        var data = google.visualization.arrayToDataTable([
                                            ['Gender', 'Number'],
                                            ['Male', officerMaleCount],
                                            ['Female', officerFemaleCount]
                                        ]);

                                        var options = {
                                            title: 'Officer',
                                            is3D: true,
                                            width: 300,
                                            height: 200
                                        };

                                        var chart = new google.visualization.PieChart(document.getElementById(chartId));
                                        chart.draw(data, options);
                                    }
                                </script>
                            <?php
                            }
                            ?>
                            <tr>
                                <td scope="row" colspan="5" class="text-center fw-bold"><b> Sub Total: <?php echo $sub_total ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
