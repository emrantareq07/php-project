<?php 
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code'];  
require_once("backend/config.php");
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include_once 'db/database.php';
include_once 'backend/header.php';
include_once 'backend/navbar.php';

$today_date=date("Y-m-d");
//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
$result = mysqli_query($conn,"SELECT COUNT(*) AS upcoming_meeting_count FROM $table where date>'$today_date'"); 
$row = mysqli_fetch_array($result);
$upcoming_meeting_count = $row['upcoming_meeting_count'];
    
?>
<div class="container-fluid">
  <div class="table-wrapper border rounded shadow p-2">
    <div class="table-title">
      <div class="row">
        <div class="col-sm-3">
         <!--  <h2 class="text-muted">DAILY <b>MEETING</b> SCHEDULE</h2>
          <span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span> -->
        </div>
        <div class="col-sm-9 text-end">
          <?php
          if($user_type=='sadmin'){   
            ?>
            <h4><a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
            </h4>
              <h4>
  
            <a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database </a>
            </h4>
            <?php
             }
            ?>
          <!-- <a href="#addEmployeeModal" class="btn btn-outline-success " data-toggle="modal"><i class="fa fa-plus" style="font-size:16px"></i> <span> Add New Meeting</span></a> -->      
  
          <!-- <a href="backend/show_all.php" class="btn btn-outline-success "><i class="fa fa-eye" style="font-size:16px"></i> <span> Show All</span></a>  -->

          <!-- <a href="JavaScript:void(0);" class="btn btn-outline-danger" id="delete_multiple"><i class="fa fa-trash" style="font-size:16px"></i><span> Multiple Delete</span></a> -->

          <!-- <a href="backend/logout.php" class="btn btn-outline-danger" ><i class="fa fa-trash" style="font-size:16px"></i><span> Logout</span></a> -->

          <!-- <a href="backend/upcoming_meeting.php" class="btn btn-outline-success position-relative"><i class="fa fa-clock-o" style="font-size:20px;color:red"></i>  Upcoming Meeting <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?php echo $upcoming_meeting_count; ?>
            </span></a> -->

          <!-- <hr> -->
          <?php
          if($user_type=='user'){
          ?>
          <!-- <a href="backend/directors_meeting.php?dir_tbl=chairman" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Chairman Sir</span></a> -->
          <?php 
          }
          if($user_type=='admin'){
          ?>

     <!--      <a href="backend/directors_meeting.php?dir_tbl=dir_com" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Director (Commercial)</span></a>
          <a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Director (Finance)</span></a>
          <a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Director (T&E)</span></a>
          <a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Director (P&I)</span></a>
          <a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Director (P&R)</span></a>
          <hr> -->
          
          <?php }; ?>

  <!--         <a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-print" style="font-size:16px"></i> <span>
        <span>Print Date wise</span></a>

        <a href="backend/search_by_two_date.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Between Date </a>      
        
        <a href="backend/search_by_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print Month wise </a> 
        <a href="backend/search_by_two_month.php" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i>  Print Between Month </a>
        
        <a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print All</a> -->
        </div>
      </div>
    </div> <hr> 

<!-- Printable Area -->
<div id="printableArea">  
    <table class="table table-bordered table-striped table-hover ">
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
          <th>Zoom Details</th>
          <th>President</th> 
          <th>Place</th>          
          <!-- <th id="status">Status</th>          -->
          <th id="action">ACTION</th>
        </tr>
      </thead>
      <tbody >     
    <?php
    $today_date=date("Y-m-d");
    date_default_timezone_set('Asia/Dhaka'); // Set timezone to Bangladesh time
    $time = date("g:i A"); // Format time as 'hour:minute AM/PM'
    //echo $time;

      $change_status = [
          'chairman',
          'dir_com',
          'dir_fin',
          'dir_te',
          'dir_pi',
          'dir_pr',
          'ict',
      ];

      foreach ($change_status as $table_change_status) {
          $result_change_status = mysqli_query($conn, "SELECT * FROM `$table_change_status` WHERE date < '$today_date' AND status = 'Incomplete'");
          if (mysqli_num_rows($result_change_status) > 0) {
              while ($row_change_status = mysqli_fetch_array($result_change_status)) {
                  $id = $row_change_status["id"];
                  $sql11 = "UPDATE `$table_change_status` SET `status` = 'Complete' WHERE id = $id";
                  mysqli_query($conn, $sql11);
              }
          }
      }
            //$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
            $result = mysqli_query($conn,"SELECT * FROM $table where date='$today_date' ORDER BY 
                                        FIELD(status, 'Incomplete', 'Complete'), 
                                        id DESC");
        $i=1;
        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result)) {          
          $zoom_concat;
          $link = $row["zoom_link"]; // Replace with your base URL
          if($row["zoom_link"] && $row["zoom_id"] && $row["zoom_passcode"]){
            $link = $row["zoom_link"]; // Replace with your base URL
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
     <!--  <td>
        <span class="custom-checkbox">
          <input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
          <label for="checkbox2"></label>
        </span>
          </td>  -->
        <!--<td><?php echo $i; ?></td>-->
        <td style="font-size: 14px !important;"><?php echo $row["date"].'<br>'.$day; ?></td>
        <!-- <td><?php echo $row["time"]; ?></td> -->
        <td><?php echo date("g:i A", strtotime($row["time"])); ?></td>
        <td style="text-align: justify;"><?php echo $row["subject"] . $focal_point; ?> </td>
        <td ><?php echo  $zoom_concat; ?></td>
        <td><?php echo $row["president"]; ?></td>
        <td style="font-size: 14px !important;"><?php echo $row["place"]; ?></td>
        <!-- <td id="status_t"><?php //echo $row["status"];?>   -->
        <!-- <i class="fa fa-edit update" data-toggle="tooltip" style="font-size:24px" -->  
        <!-- </td>        -->
        <td id="action_t">
          <a href="#editEmployeeModal" class="edit" style="text-decoration: none" data-toggle="modal">
            <i class="fa fa-edit update" id="edit_btn" data-toggle="tooltip"
            data-id="<?php echo $row["id"]; ?>"
            data-date="<?php echo $row["date"]; ?>"
            data-time="<?php echo $row["time"]; ?>"
            data-subject="<?php echo $row["subject"]; ?>"
            data-focal_point="<?php echo $row["focal_point"]; ?>"
            data-zoom_id="<?php echo $row["zoom_id"]; ?>"
            data-zoom_passcode="<?php echo $row["zoom_passcode"]; ?>"
            data-zoom_link="<?php echo $row["zoom_link"]; ?>"
            data-president="<?php echo $row["president"]; ?>"
            data-place="<?php echo $row["place"]; ?>"
            
            title="Edit" style="font-size:20px; color:blue;"></i>
          </a>&nbsp;
          <a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal">
          <!-- <i class="material-icons" data-toggle="tooltip" title="Delete"></i> -->
          <i class="fa fa-trash" id="delete_btn" style="font-size:20px;color:red;"></i>
        </a>
        </td>
      </tr>
      <?php
      $i++;
      }
       }
      else {
        echo "<p class='btn btn-danger btn-md '> Today No Meetting Found!!!</p>";
      }
      ?>
      </tbody>
    </table>
    </div>
  </div>
</div>
  <!-- Add Modal HTML -->
  <div id="addEmployeeModal" class="modal fade" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="user_form" class="needs-validation" validate>
          <div class="modal-header ">
            <h4 class="modal-title text-uppercase text-muted fw-bold text-center w-100">Add New Meeting</h4>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="date" class="text-secondary fw-bold">Date :</label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo $today_date ?>" required>
              </div>
             <script>
              document.addEventListener("DOMContentLoaded", function () {
                var dateInput = document.getElementById("date");
                var today = new Date().toISOString().split('T')[0]; // Get today's date in 'YYYY-MM-DD' format
                dateInput.setAttribute('min', today); // Set min attribute to today to prevent past dates
              });
            </script> 
            </div>
            <div class="col-md-6">
              <div class="form-group mb-2" >
                <label for="time" class="text-secondary fw-bold">Start Time :</label>
                <input type="time" id="time" name="time" class="form-control"  required>
              </div>
                       <script>
            // Convert 12-hour time to 24-hour time
            function convertTo24HourFormat(time12) {
              const [time, period] = time12.split(' ');
              let [hours, minutes] = time.split(':').map(Number);

              if (period === 'PM' && hours < 12) {
                hours += 12;
              } else if (period === 'AM' && hours === 12) {
                hours = 0;
              }

              return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            }

            // Get the PHP time value and set it in the input
            const phpTime = "<?php echo $time; ?>";
            const time24 = convertTo24HourFormat(phpTime);
            document.getElementById('time').value = time24;
          </script>
            </div>
          </div>
            <div class="form-group mb-2">
              <label for="subject" class="text-secondary fw-bold">Subject :</label>
              <!-- <input type="text" id="subject" name="subject" class="form-control" required> -->
              <textarea class="form-control" rows="1" id="subject" name="subject" required></textarea>
              <!-- <div class="invalid-feedback">
                Please provide a subject.
              </div> -->
            </div>
              <div class="form-group mb-2">
              <label for="focal_point" class="text-secondary fw-bold">Focal Point :</label>
              <input type="text" id="focal_point" name="focal_point" class="form-control" >
              <!-- <div class="invalid-feedback">
                Please provide a subject.
              </div> -->
            </div>

            <div class="form-group mb-2">
              <label for="president" class="text-secondary fw-bold">President :</label>
              <input type="text" id="president" name="president" class="form-control" >
              <!-- <div class="invalid-feedback">
                Please provide a subject.
              </div> -->
            </div>

            <div class="form-group mb-2">
              <label for="zoom_id" class="text-secondary fw-bold">Zoom ID & Passcode/Link :</label>
              <fieldset class="border rounded p-2">
                <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Enter Zoom ID" id="zoom_id" name="zoom_id" min="0" step="1">

                  <input type="number" class="form-control" placeholder="Enter Passcode" id="zoom_passcode" name="zoom_passcode">
                </div>
                <input type="text" id="zoom_link" name="zoom_link" placeholder="Enter Zoom Link" class="form-control">
              </fieldset>
            </div>
            
			   <script>
			  document.getElementById('zoom_id').addEventListener('input', function (e) {
			    var value = e.target.value;
			    if (!/^\d*$/.test(value)) {
			      e.target.setCustomValidity('Please enter a valid integer.');
			    } else {
			      e.target.setCustomValidity(''); // Reset the custom validation
			    }
			  });
			</script> 
            <div class="form-group ">
              <label for="place" class="text-secondary fw-bold">Place :</label>
              <select class="form-select" id="place" name="place" required>
                <option selected disabled value="">--Select--</option>
                <option value="Conference Room (4th Floor)">Conference Room (4th Floor)</option>
                <option value="Board Room (4th Floor)">Board Room (4th Floor)</option>
                <option value="Seminar Hall (21th Floor)">Seminar Hall (21th Floor)</option>
                <option value="ICT Lab (3rd Floor)">ICT Lab (3rd Floor)</option>
              </select>
              
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" value="1" name="type">
            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" id="btn-add">
              <i class="fa fa-save" style="font-size:16px"></i> Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="update_form">       
        <div class="modal-header">
              <h4 class="modal-title text-uppercase text-muted text-center">Edit Meeting</h4>
              <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
        <div class="modal-body">
          <input type="hidden" id="id_u" name="id" class="form-control" required>    
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="date" class="text-secondary fw-bold">Date :</label>
                <input type="date" id="date_u" name="date" class="form-control"  required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-2">
                <label for="time" class="text-secondary fw-bold">Start Time :</label>
                <input type="time" id="time_u" name="time" class="form-control"  required>
              </div>
            </div>
          </div>     
         <!--  <div class="form-group">
            <label>Date</label>
            <input type="date" id="date_u" name="date" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Time</label>
            <input type="time" id="time_u" name="time" class="form-control" required>
          </div> -->
          <div class="form-group mb-2">
            <label class="text-secondary fw-bold">Subject :</label>
            <input type="text" id="subject_u" name="subject" class="form-control" required>
          </div>
          <div class="form-group mb-2">
            <label class="text-secondary fw-bold">Focal Point :</label>
            <input type="text" id="focal_point_u" name="focal_point" class="form-control" >
          </div>
          <div class="form-group mb-2">
            <label class="text-secondary fw-bold">President :</label>
            <input type="text" id="president_u" name="president" class="form-control" >
          </div>
          <div class="form-group mb-2">    
            <label class="text-secondary fw-bold">Zoom ID & Passcode/Link :</label>
            <fieldset class="border rounded p-2">
                <div class="input-group mb-3">
                    <!-- <span class="input-group-text"> ID</span> -->
                    <input type="text" class="form-control" placeholder="Enter Zoom ID" id="zoom_id_u" name="zoom_id">
                    <!-- <span class="input-group-text">Passcode</span> -->
                    <input type="text" class="form-control" placeholder="Enter Passcode" id="zoom_passcode_u" name="zoom_passcode">
                </div>
                <!-- Zoom Link -->
                <input type="text" id="zoom_link_u" name="zoom_link" placeholder="Enter Zoom Link" class="form-control">
            </fieldset>
        </div>
          <div class="form-group">
            <label class="text-secondary fw-bold">Place :</label>
            <!-- <input type="text" id="place_u" name="place" class="form-control" required> -->
            <select class="form-select form-control" id="place_u" name="place" required  aria-label="Default select example">
            <option selected disabled value="">--Select--</option>
            <option id="selectA" value="Conference Room (4th Floor)" onchange >Conference Room (4th Floor)    
            </option>       
            <option id="selectB"  value="Board Room (4th Floor)" >Board Room (4th Floor)</option>
            <option id="selectC" value="Seminar Hall (21th Floor)">Seminar Hall (21th Floor)</option>
            <option id="selectD" value="ICT Lab (3rd Floor)">ICT Lab (3rd Floor)</option>
          </select>
          </div>           
            <script>          
            // Function to change webpage background color
            function changeBodyBg(color){
              document.body.style.background = color;
            }           
            // Function to change heading background color
            function changeHeadingBg(color){
              document.getElementById("heading").style.background = color;
            }
          </script>           
        </div>
        <div class="modal-footer">
        <input type="hidden" value="2" name="type">
          <input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
          <button type="button" class="btn btn-primary" id="update"><i class="fa fa-upload" style="font-size:16px"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>        
              <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title text-uppercase">Delete Meeting</h4>
              <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
        <div class="modal-body">
          <input type="hidden" id="id_d" name="id" class="form-control">          
          <p>Are you sure you want to delete these Records?</p>
          <p class="text-warning"><small>This action cannot be undone.</small></p>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-outline-secondary" data-dismiss="modal" value="Cancel">
          <button type="button" class="btn btn-danger" id="delete">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php 
include_once 'backend/footer.php';
?>
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