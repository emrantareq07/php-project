<?php
session_name('PROJECT1SESSION');
session_start();
$table=$_SESSION['username']; 
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];
$dir_tbl = $_GET['dir_tbl'] ?? ''; // Safely retrieve 'dir_tbl' parameter
include_once '../db/database.php';
include_once 'header.php';
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
// Check if the user is already logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
include_once 'navbar_directors.php';
?>

<div class="container-fluid ">
	<div class="table-wrapper border rounded shadow p-2">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-3">					
				</div>
			</div>
		</div> <hr>
<!-- Printable Area -->
<div id="printableArea">  
    <table class="table table-bordered table-striped table-hover ">
      <thead class="table-primary text-center">
        <tr>         
          <th>Date</th>
          <th>Time</th>
          <th>Subject</th>
          <th>Zoom Details</th>
          <th>President</th> 
          <th>Place</th>          
          <!-- <th id="status">Status</th>          -->
           <?php  
                if(empty($dir_tbl)){
                  ?>
          <th id="action">ACTION</th>
        <?php }
        ?>
        </tr>
      </thead>
      <tbody>  		
			<?php			
			$today_date = date("Y-m-d");

if ($dir_tbl) {
    $result = mysqli_query($conn, "SELECT * FROM `$dir_tbl` WHERE date > '$today_date'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM `$table` WHERE date > '$today_date'");
}

$i = 1;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $zoom_concat = '';
        $link = $row["zoom_link"];

        if ($row["zoom_link"] && $row["zoom_id"] && $row["zoom_passcode"]) {
            $zoom_concat = $row["zoom_id"] . ', ' . $row["zoom_passcode"] . ', <br>' .
                '<a href="' . htmlspecialchars($link) . '" target="_blank"><i><small>Zoom Link</small></i></a>';
        } elseif ($row["zoom_id"] && $row["zoom_passcode"]) {
            $zoom_concat = $row["zoom_id"] . ', ' . $row["zoom_passcode"];
        } elseif ($link != '') {
            $zoom_concat = '<a href="' . htmlspecialchars($link) . '" target="_blank"><i><small>Zoom Link</small></i></a>';
        }

        $focal_point = $row["focal_point"] ? '<br><b>Focal Point: </b>' . $row["focal_point"] : '';
        $date = $row["date"];
        $day = date("l", strtotime(str_replace('/', '-', $date)));
        ?>

        <tr id="<?php echo $row["id"]; ?>" class="text-center">
            <td style="font-size: 14px !important;"><?php echo $row["date"] . '<br>' . $day; ?></td>
            <td><?php echo date("g:i A", strtotime($row["time"])); ?></td>
            <td style="text-align: justify;"><?php echo $row["subject"] . $focal_point; ?></td>
            <td><?php echo $zoom_concat; ?></td>
            <td><?php echo $row["president"]; ?></td>
            <td style="font-size: 14px !important;"><?php echo $row["place"]; ?></td>
            <?php  
                if(empty($dir_tbl)){
                  ?>
              <td id="action_t">
                <a href="#editEmployeeModal" class="edit" id="edit_btn" style="text-decoration: none" data-toggle="modal">
                    <i class="fa fa-edit update" data-toggle="tooltip"
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
                <a href="#deleteEmployeeModal" class="delete" id="del_btn" data-id="<?php echo $row["id"]; ?>" data-toggle="modal">
                    <i class="fa fa-trash" style="font-size:20px;color:red;"></i>
                </a>
            </td> 
            <?php  
                }
              ?>

        </tr>
        <?php
        $i++;
    }
} else {
    echo "<p class='btn btn-danger btn-md text-left'>Upcoming Meeting Not Yet Found!!!</p>";
}
?>
			</tbody>
		</table>
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
//include_once 'footer.php';
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
                #del_btn{
                  display: none;
                  visibility: none;
                }
                #action{
                  display: none;
                  visibility: none;
                }
                 #action_t{
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

<!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   
<script src="ajax/upcoming_meeting.js"></script>
<!-- <script src="../ajax/ajax.js"></script> -->
 <script type="text/javascript">
    $(document).on('click','.update',function(e) {
    var id=$(this).attr("data-id");
    var date=$(this).attr("data-date");
    var time=$(this).attr("data-time");
    var subject=$(this).attr("data-subject");
    var focal_point=$(this).attr("data-focal_point");
    var president=$(this).attr("data-president");    
    var zoom_id=$(this).attr("data-zoom_id");
    var zoom_passcode=$(this).attr("data-zoom_passcode");
    var zoom_link=$(this).attr("data-zoom_link");
    var place=$(this).attr("data-place");
    var status_s=$(this).attr("data-status");
    
    $('#id_u').val(id);
    $('#date_u').val(date);
    $('#time_u').val(time);   
    $('#subject_u').val(subject);
    $('#focal_point_u').val(focal_point);
    $('#president_u').val(president);
    $('#zoom_id_u').val(zoom_id);
    $('#zoom_passcode_u').val(zoom_passcode);
    $('#zoom_link_u').val(zoom_link);
    $('#place_u').val(place);
    $('#status_u').val(status_s);
  });

    $(document).on('click','#update',function(e) {
    var data = $("#update_form").serialize();
    $.ajax({
      data: data,
      type: "post",
      url: "save.php",
      success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200){
            $('#editEmployeeModal').modal('hide');
            alert('Data updated successfully !'); 
                        location.reload();            
          }
          else if(dataResult.statusCode==201){
             alert(dataResult);
          }
      }
    });
  });

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
      'use strict'      
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')      
      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function (user_form) {
          user_form.addEventListener('submit', function (event) {
            if (!user_form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            user_form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>

   <script>
function applyFontSizes() {
  // Get all elements that need to be styled
  var elements = document.querySelectorAll('.text');

  elements.forEach(function(element) {
    // Check if the text contains Bangla characters
    if (/[\u0980-\u09FF]/.test(element.textContent)) {
      element.classList.add('bangla-text');
    } else {
      element.classList.add('english-text');
    }
  });
}

// Call the function after the page has loaded
document.addEventListener('DOMContentLoaded', applyFontSizes);
</script>

</body>
</html>