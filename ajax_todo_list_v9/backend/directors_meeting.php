<?php
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; 
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];
$dir_tbl = $_GET['dir_tbl'] ?? ''; // Safely retrieve 'dir_tbl' parameter
// echo $dir_tbl ; 
include_once '../db/database.php';
include_once 'header.php';
// Check if the user is already logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
$today_date = date("Y-m-d");
// Query to count the number of upcoming meetings
$result = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_meeting_count FROM $dir_tbl WHERE date > '$today_date'");
$row = mysqli_fetch_array($result);
$upcoming_meeting_count = $row['upcoming_meeting_count'];
include_once 'navbar_directors.php';

?>
<!-- <script src="../ajax/upcoming_meeting.js"></script> -->
<!-- <script src="../ajax/ajax.js"></script> -->
<div class="container-fluid">
    <!-- <p id="success"></p> -->
    <div class="table-wrapper border rounded shadow p-2">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-3">
                    <!-- <h2 class="text-muted">DAILY <small></small><b>MEETING</b> SCHEDULE</h2>
                    <span class="text-primary fw-bold"><small>[--<?php echo htmlspecialchars($office); ?>--]</small></span> -->
                </div>
                <div class="col-sm-9 text-end">                
                   <!--  <a href="show_all.php" class="btn btn-outline-success"><i class="fa fa-eye" style="font-size:16px"></i> <span> Show All</span></a>  -->   
                    <!-- <a href="../dashboard.php" class="btn btn-outline-warning"><i class="fa fa-home" style="font-size:16px"></i> Previous Page </a> -->
                    <!-- <a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print All</a>   -->  
                    <!-- <a href="logout.php" class="btn btn-outline-danger"><i class="fa fa-trash" style="font-size:16px"></i><span> Logout</span></a> -->

                    <a href="upcoming_meeting.php?dir_tbl=<?php echo urlencode($dir_tbl); ?>" class="btn btn-success position-relative"><i class="fa fa-clock-o" style="font-size:20px;color:red"></i>  Upcoming Meeting </a> 
                              <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $upcoming_meeting_count; ?></span>               -->
                </div>
            </div>
        </div> <hr>
        <!-- Printable Area -->
    <div id="printableArea">  
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <!-- <th>
                        <span class="custom-checkbox">
                            <input type="checkbox" id="selectAll">
                            <label for="selectAll"></label>
                        </span>
                    </th> -->
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Place</th>                    
                    <th>Status</th>                    
                </tr>
            </thead>
            <tbody>
            <?php
            $i = isset($_GET['i']) ? (int)$_GET['i'] : 0;
            if ($i === 0) {
                $query = "SELECT * FROM $dir_tbl WHERE date = '$today_date'";
            } else {
                $query = "SELECT * FROM $dir_tbl WHERE date > '$today_date'";
            }
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
            ?>
                <tr id="<?php echo $row['id']; ?>" class="text-center">
                    <!-- <td>
                        <span class="custom-checkbox">
                            <input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row['id']; ?>">
                            <label for="checkbox<?php echo $row['id']; ?>"></label>
                        </span>
                    </td> -->
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['place']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php
                }
            } else {
                // echo "<tr><td colspan='6' class='text-center btn btn-danger btn-md'>Upcoming Meeting Not Yet Found!!!</td></tr>";
                echo "<p class='btn btn-danger btn-md '> Today No Meetting Found!!!</p>";
            }
            ?>
            </tbody>
        </table>
    </div>
    </div>
</div> 
<?php include_once 'footer.php'; ?>
<script>
document.getElementById('print').addEventListener('click', function() {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center text-uppercase">Daily Meeting Schedule</h1>';
    // Create a container for the content to be printed
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = `
        <html>
        <head>
            <title></title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @font-face {
                    font-family: 'Nikosh', Times, serif;
                    src: url(Nikosh.ttf);
                }
                .imgcontainer {
                    text-align: center;
                    margin: 5px 0 12px 0;
                    position: relative;
                }
                img.avatar {
                    width: 25%;
                    border-radius: 50%;
                }
                * {
                    font-family: 'Open Sans', sans-serif;
                    font-family: 'Tiro Bangla', serif;
                    font-family: 'Nikosh', sans-serif;
                }
                #edit_btn{
                  display: none;
                  visibility: none;
                }
                #action_t{
                  display: none;
                  visibility: none;
                }
                #action{
                  display: none;
                  visibility: none;
                }
                #status{
                  display: none;
                  visibility: none;
                }
                #status_t{
                  display: none;
                  visibility: none;
                }
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;

    // Trigger the print dialog
    window.print();
    // Restore the original contents of the page after printing
    document.body.innerHTML = originalContents;    
    // Reload the page to reflect the original content and avoid any loss of functionality
    window.location.reload();
});
</script>