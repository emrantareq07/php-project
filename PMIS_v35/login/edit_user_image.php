<?php 
include('includes/header.php');
include('db.php');
include('../controller/function.php');

session_start();
 
if (isset($_POST['register'])) {
 
    $emp_id = $_SESSION['emp_id']; 


$statement = $connection->prepare("SELECT id FROM basic_info WHERE emp_id = '$emp_id'");
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row) {
   $id=$row["id"];
 }
    $image = '';
    if ($_FILES["user_image"]["name"] != '') {
        // Remove old image if a new one is uploaded
        $old_image = get_image_name($id);
        if ($old_image != '') {
            $old_image_path = '../images/' . $old_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        
        $image = upload_image();
    //} 

    $statement = $connection->prepare(
        "UPDATE basic_info 
        SET  image = :image 
        WHERE emp_id = :emp_id"
    );
    $result = $statement->execute(
        array(
      
            ':image' => $image,
            ':emp_id' => $emp_id
        )
    );

    if (!empty($result)) {

  $statement = $connection->prepare(
        "UPDATE users 
        SET  image = :image 
        WHERE emp_id = :emp_id"
    );
    $result = $statement->execute(
        array(
      
            ':image' => $image,
            ':emp_id' => $emp_id
        )
    );
        // echo 'Data Updated Successfully!!!';
        // header("Location: service_history_details.php?emp_id=". $emp_id);
    echo '<script type="text/javascript">';
    echo 'alert("Data Updated Successfully!!!");';
    // echo 'window.location.href="add_job_desc.php"';
    echo 'window.location.href="user_dashboard.php"';
    echo '</script>';
    }
}
else{
    // echo 'Please Select a Image!!!';
}
}

?>
    <div class="container">
        <h2 class="page-header text-center mt-2 text-uppercase text-muted"><b class=" bg-muted">Welcome BCIC Personnel Management Information System(PMIS) </b></h2>
        <div class="row">
        <div class="col-sm-3 "></div>
            <div class="col-sm-6 ">
                <div class="login-panel panel panel-primary my-0 mt-0 pt-0 mb-0 shadow-lg">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span>  Uoload Profile Picture
                        </h3>
                    </div>

                    <div class="panel-body">
                        <form method="POST" action="" id="regForm" enctype="multipart/form-data"> 
                            <div class="form-group">
                           
                            <input type="file" name="user_image" id="user_image" accept=".jpg, .jpeg,.png,.gif" required onchange="previewImage(event)"/>

                            <br>
                            <center>
                                <img id="preview" src="#" alt="Preview" style="max-width: 150px; max-height: 150px; display: none; " class="img-thumbnail shadow">
                            </center>                          
                        
                            </div>

                            <button type="submit" name="register" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Submit</button>
                        </form>
                        
                    </div>
                    
                </div>
                
            </div>

    <div class="col-sm-3 ">
        <a class="btn btn-primary float-end" href="user_dashboard.php"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
    </div>
        </div>
    </div>

<?php include('includes/footer.php');?>

<script>
    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById("preview");
            imgElement.src = reader.result;
            imgElement.style.display = "block";
        }
        reader.readAsDataURL(input.files[0]);
    }
</script>








    
   