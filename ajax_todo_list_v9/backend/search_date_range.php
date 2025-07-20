<?php
session_name('PROJECT1SESSION');
include_once 'header.php';
include_once '../db/database.php';
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

// Retrieve table_name and val from the URL query parameters
$table_name = isset($_GET['table_name']) ? urlencode($_GET['table_name']) : '';
//$val = isset($_GET['val']) ? urlencode($_GET['val']) : ''; 

?>  

<div class="container mt-1 p-2 border rounded shadow-lg">  
  <div class="row"> 
    <div class="col-sm-12">
      <h2 class="text-muted text-center text-uppercase righteous-regular"><b>Daily Meeting Schedule</b> </h2>
      <center><hr class="bg-muted col-sm-6 text-center"></center>
    </div> 
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <!-- Form with corrected query parameters in action URL -->
      <form action="search_date_range.php?table_name=<?= urlencode($_GET['table_name']) ?>&val=<?= urlencode($_GET['val']) ?>" method="post" class="needs-validation">
    
        <input type="hidden" class="form-control" value="<?= $_GET['table_name']; ?>" name="table_name">
        <input type="hidden" class="form-control" value="<?= $_GET['val']; ?>" name="val">
        <div class="form-group row">
          <label for="date1" class="col-form-label col-sm-5">Start Date : </label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="date1" placeholder="From Date" name="date1" required>
          </div>
        </div> 
        <div class="form-group row mt-2">
          <label for="date2" class="col-form-label col-sm-5">End Date : </label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="date2" placeholder="To Date" name="date2" required>
          </div>
        </div>

              <?php 
   if($table_name == 'chairman') 
    { 
        ?>

       <div class="form-group row mt-2">
          <label for="division_bn" class="col-form-label col-sm-5">Directors List : </label>
          <div class="col-sm-7">
            <select class="form-select form-control" id="division_bn" name="division_bn" aria-label="Default select example">
              <option selected disabled value="">--Select--</option>
    <option value="chairman">Chairman</option>
    <option value="dir_com">Director of Communications</option>
    <option value="dir_fin">Director of Finance</option>
    <option value="dir_te">Director of Technology</option>
    <option value="dir_pi">Director of Public Information</option>
    <option value="dir_pr">Director of Public Relations</option>
       
            </select>
          </div>
        </div> 

          <?php 
}
        ?>

        <center><button type="submit" name="submit" class="btn btn-primary btn-block mb-2 mt-2 col-sm-6 offset-5"><i class="fa fa-search" style="font-size:16px"></i> Search</button></center>
      </form>    
    </div>
    <div class="col-sm-3 text-center">
      <?php 
      // if($val == '456') 
     // { 
        ?>
        <!-- <a href="incoming_letter.php" class="btn btn-primary text-center"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>   -->
      <?php 
  //} 
      // else if($val == '987') 
      //{ 
        ?>
        <a href="../dashboard.php" class="btn btn-primary text-center"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>
      <?php 
   // } 
    ?> 

      <hr>
      <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
    </div>
  </div>
</div>

<!-- Printable Area -->
<div id="printableArea">
    <!-- Your table content goes here -->
    <table class="table table-bordered table-striped text-center">
        <thead class="thead-dark">
            <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Subject</th>
          <th>Zoom Details</th>
          <th>President</th> 
          <th>Place</th>        
            </tr>
        </thead>
        <tbody>
        <?php
        function englishToBanglaNumber($number) {
            $englishNumbers = range(0, 9);
            $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
            return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
        } 

if (isset($_POST['submit'])) {
    $from_date = $_POST['date1'];
    $to_date = $_POST['date2'];

   $division_bn = isset($_POST['division_bn']) ? $_POST['division_bn'] : ''; // Check if 'division_bn' exists
    $table_name = $_POST['table_name'];    

    // Fetch val from GET (since it's passed through URL)
    //$val = isset($_GET['val']) ? $_GET['val'] : '';
    $val = $_POST['val'];
    if ($division_bn == '') {
      
        $base_query = "SELECT * FROM $table_name WHERE date BETWEEN '$from_date' AND '$to_date'";
    } else {

       $table_name=$division_bn;
        $base_query = "SELECT * FROM $table_name WHERE date BETWEEN '$from_date' AND '$to_date'";
    }    
   // Add division_bn filter if it is provided
    // if ($division_bn != '') {
    //     $base_query .= " AND FIND_IN_SET('$division_bn', destination_drop) > 0";
    // }

    $result1 = mysqli_query($conn, $base_query);    
    if (mysqli_num_rows($result1) > 0) {
        //$i = 1;
        while ($row = mysqli_fetch_assoc($result1)) {

          $zoom_concat;
          $link = $row["zoom_link"]; 

          if($row["zoom_link"] && $row["zoom_id"] && $row["zoom_passcode"]){
            $link = $row["zoom_link"]; 
            $zoom_concat = $row["zoom_id"] . ', ' . $row["zoom_passcode"] . ', <br>' . 
               '<a href="' . htmlspecialchars($link) . '" target="_blank"><i><small>Zoom Link</small></i></a>';
          }

          else if($row["zoom_id"] && $row["zoom_passcode"]){
            $zoom_concat=$row["zoom_id"].', '.$row["zoom_passcode"];
          }

           else{
            if( $link !=''){
              // $zoom_concat=$row["zoom_link"];
            $zoom_concat = '<a href="' . htmlspecialchars($link) . '" target="_blank"><i><small>Zoom Link</small></i></a>';
            }
            else{
              $zoom_concat ='';
            }           
            
          }     
         if ($row["focal_point"]) {
          $focal_point = '<br><b>Focal Point: </b>' . $row["focal_point"];
        } else {
          $focal_point = '';
        }

        $date=$row["date"];
        $day = date("l", strtotime(str_replace('/', '-', $date)));
      ?>
      <tr id="<?php echo $row["id"]; ?>" class=" text-center">

        <td style="font-size: 14px !important;"><?php echo $row["date"].'<br>'.$day; ?></td>
      
        <td><?php echo date("g:i A", strtotime($row["time"])); ?></td>
        <td style="text-align: justify;"><?php echo $row["subject"] . $focal_point; ?> </td>
        <td ><?php echo  $zoom_concat; ?></td>
        <td><?php echo $row["president"]; ?></td>
        <td style="font-size: 14px !important;"><?php echo $row["place"]; ?></td>

      </tr>
                <?php
               // $i++;
            }
        }
   // } 
    else {
        // No records found message
        echo '<tr><td colspan="11" class="text-center text-danger">No records found for the selected date range.</td></tr>';
    }
}
mysqli_close($conn);
?>
        </tbody>
    </table>
</div>

<script>
// Print functionality
document.getElementById('print').addEventListener('click', function() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center">Meeting Schedule Records</h1>';
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>Meeting Schedule Records</title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
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
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;

    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
});
</script>

<?php include('footer.php'); ?>
