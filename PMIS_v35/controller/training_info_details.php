<?php
session_start();
require '../db/dbcon.php';

    // session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Unset the session variable
}
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <title>BCIC PMIS</title>
</head>
<body>
  
    <div class="container-fluid mt-4">

        <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border border-primary rounded">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted"><b>Training Information Details</b>
                            <span class="float-end">
                            <a href="training_info-create.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Training</a>

                            <!-- <a href="service_history_info-create.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-primary">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
                                  </a>  --> 

                             <a href="#" id="printButton"  class="btn btn-danger "><span class="glyphicon glyphicon-print" aria-hidden="true"></span>  Print </a></span>
                             <?php

                          if($val==1){
                            ?>
                            <a class="btn btn-primary float-start" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
                            <?php
                          }
                          else{
                            ?>
                            <a class="btn btn-primary float-start" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>Previous page </a>

                            <?php
                          }

                          ?>
                        </h4>
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
                                <th>ID</th>
                                 <!-- <th>User Id</th> -->
                                 <th>Emp Id</th>
                                 <th>Training Type</th>
                                 <th>Title</th>
                                 <th>Institute</th>
                                 <th>Country</th>
                                 <th>Period</th>
                                 <th>Month/ Year</th>
                    <?php

                    if($val==1){
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      
                      <th class="text-center">Action</th>

                      <?php }
                    ?>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      $query="SELECT * FROM training_info where emp_id='$emp_id'";
                                     }
                                    //$query = "SELECT * FROM training_info";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0){
                                        foreach($query_run as $student){
                                            ?>
                                            <tr>
                                                <td><?= $student['id']; ?></td>
                                                <!-- <td><?= $student['user_id']; ?></td> -->
                                                <td><?= $student['emp_id']; ?></td>
                                                <td><?= $student['type']; ?></td>
                                                <td><?= $student['title']; ?></td>
                                                <td><?= $student['institute']; ?></td>
                                                <td><?= $student['country']; ?></td>
                                                <td><?= $student['period']; ?></td>
                                                <td><?= $student['month_year']; ?></td>

                  <?php

                  if($val==1){
                    ?>
                    <?php
                  }
                  else{
                    ?>
                    <td class="text-center">
                      <!-- <a href="training_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a> -->

                    <a href="training_info-edit.php?id=<?= $student['id'];?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <!-- <?php echo $student['id']; ?>&val=<?php echo $val; ?> -->
                    <form action="training_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                    <!-- <a href="training_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <form action="training_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form> -->
                  </td>
                    <?php
                  }
                  ?>

                                               <!--  <td>
                                                    <a href="training_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                                                    <a href="training_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                    <form action="training_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                                    </form>
                                                </td> -->

                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

 <script>
  document.getElementById("printButton").addEventListener("click", function() {
      // When the "Print" button is clicked, trigger the browser's print dialog
      var printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');
      
      var contentToPrint = "<center><b><br><h2 class='text-center text-uppercase text-muted'> Training Information Details</h2></b></center>";

      // Fetch the data and add it to the contentToPrint variable
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "training_info_details_print.php", true);
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