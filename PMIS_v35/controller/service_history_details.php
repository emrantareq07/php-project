<?php
session_start();
require '../db/dbcon.php';

$_SESSION['emp_id']=$_SESSION['emp_id'];
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Unset the session variable
}
include('../includes/header.php');
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 //echo $val;
  }
?>  
    <div class="container-fluid p-4 mt-2">
        <?php include('../view/message.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted"><b>Service History Details</b>
                            <span class="float-end">

                              <a href="service_history_info-create.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-primary">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
                                  </a>                          

                             <a href="#" id="printButton"  class="btn btn-danger "><span class="glyphicon glyphicon-print" aria-hidden="true"></span>  Print </a>

                           </span>

                           <?php

                        if($val==1){
                          ?>
                          <a class="btn btn-primary float-start" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
                          <?php
                        }
                        else{
                          ?>
                      <a class="btn btn-primary float-start" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>

                      <?php
                    }

                    ?>
                        </h4>

                         <script>
                          document.getElementById("printButton").addEventListener("click", function() {
                              // When the "Print" button is clicked, trigger the browser's print dialog
                              var printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');
                              var contentToPrint = "<center><b><br><h2 class='text-center text-uppercase text-muted'> Service History Details</h2></b></center>";

                              // Fetch the data and add it to the contentToPrint variable
                              var xhr = new XMLHttpRequest();
                              xhr.open("GET", "service_history_details_print.php", true);
                              xhr.onreadystatechange = function () {
                                  if (xhr.readyState === 4 && xhr.status === 200) {
                                      contentToPrint += xhr.responseText;

                                      // Write the content to the new window and initiate printing
                                      printWindow.document.open();
                                      printWindow.document.write(contentToPrint);
                                      printWindow.document.close();
                                      printWindow.print();
                                      printWindow.close();
                                  }
                              };
                              xhr.send();
                          });
                      </script>
                    </div>
                    <div class="card-body">
                      <?php if(isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <!-- <th>ID</th> -->
                                 <!-- <th>Ref. No.</th> -->
                                 <!-- <th>Emp Id</th> -->
                                 <th>Service Type</th>
                                 <th>From Date</th>
                                 <th>To Date</th>
                                 <!-- <th>Currently Working</th> -->
                                 <th>Organization</th>
                                 <!-- <th>Office Location</th> -->
                                 <th>Place of Posting</th>
                                 <th>Designation</th>
                                 <th>Grade</th>
                                  
                                 <th>Scale</th>
                                 <th>Basic</th>
                      <?php

                    if($val==1){
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      <th>Status</th>
                      <th class="text-center">Action</th>

                      <?php
                    }
                    ?>
                                 
                    </tr>
                    </thead>
                          <tbody>
                          <?php 

                           if(isset($_SESSION['emp_id'])){
                                //$edit_id=$_GET['edit'];
                                $emp_id=$_SESSION['emp_id'];
                                $query="SELECT * FROM service_history where emp_id='$emp_id' order by id desc";
                               }
                              //$query = "SELECT * FROM training_info";
                              $query_run = mysqli_query($con, $query);

                              if(mysqli_num_rows($query_run) > 0){
                                  foreach($query_run as $student){
                                      ?>
                                      <tr>
                                          <!-- <td><?= $student['id']; ?></td> -->
                                          <!-- <td><?= $student['user_id']; ?></td> -->
                                          <!-- <td><?= $student['emp_id']; ?></td> -->
                                          <td><?= $student['service_type']; ?></td>
                                          <td><?= $student['from_date']; ?></td>
                                          <td><?= $student['to_date']; ?></td>
                                          <!-- <td><?= $student['till_now']; ?></td> -->
                                          <td><?= $student['org_name']; ?></td>
                                          <!-- <td><?= $student['location']; ?></td> -->
                                          <td><?= $student['place_of_posting']; ?></td>
                                         <td><?= $student['designation']; ?></td>
                                          <td><?= $student['grade']; ?></td>
                                          
                                          <td><?= $student['scale']; ?></td>
                                          <td><?= $student['basic']; ?></td>
                                          <td><?= $student['nature_of_promo']; ?></td>
                                                      
                  <?php

                  if($val==1){
                    ?>
                    <?php
                  }
                  else{
                    ?>
                    <td class="text-center">
                      <!-- <a href="training_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a> -->
                    <a href="service_history_info-edit.php?id=<?= $student['id'];?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <!-- <?php echo $student['id']; ?>&val=<?php echo $val; ?> -->
                    <form action="service_history_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                  </td>
                    <?php
                  }
                  ?>
                                                          
                                                      
                                  </tr>
                                  <?php
                              }
                          }
                          else{
                              echo "<h5 class='text-danger'><b> No Record Found!!! </b></h5>";
                          }
                      ?>
                      
                  </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('../includes/footer.php');?>