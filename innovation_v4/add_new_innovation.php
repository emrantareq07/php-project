<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();
  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 
  $query="SELECT*FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);
      foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Innovation Database</title>

<!-- DataTables CSS library -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
<!-- DataTables JS library -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">

<style>
* {
    font-family: 'Open Sans', sans-serif;
    font-family: 'Tiro Bangla', serif;
    font-family: 'Noto Sans Bengali', sans-serif;
    font-family: 'Nikosh', sans-serif;
    font-family: 'Nikosh', serif;
}
.bs-example{
    margin: 10px;
}
</style>
<link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</head>
<body>

<div class="bs-example">
    <div class="container-fluid">
        <div class="row">
            <div class="col col-sm-12">
                <div class="card shadow border border-secondary rounded">       
                    <div class="card-header">
                        <div class="row">
                            <div class="col col-sm-6"><h2 class="float-left text-success text-uppercase fw-bold">BCIC Innovation Database</h2></div>
                            <div class="col col-sm-6">
                                <span class="float-right">
                                    <a href="dashboard.php" class="btn btn-primary text-white text-center"><i class="fa fa-arrow-left"></i> Previous Page</a>  
                                    <a href="javascript:void(0)" class="btn btn-success text-right add-model"> <i class="fa fa-plus"></i> Add New Innovation </a>
                                    <a href="logout.php" class="btn btn-danger btn-xs text-center"><i class="fa fa-sign-out"></i>Logout</a>  
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-hover">
                            <table id="usersListTable" class="display">
                                <thead class="table-success">
                                    <tr>
                                        <th>অর্থবছর</th>
                                        <th>সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
                                        <th>উদ্ভাবক/উদ্ভাবকের নাম</th>
                                        <th>পদবী</th>
                                        <th>এমপ্লয়ী নং</th>
                                        <th>প্রস্তাবকালীন কর্মস্থল</th>
                                        <th>সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা</th>
                                        <th>বাস্তাবায়নের অবস্থা</th>
                                        <th>রেপ্লিকেট যোগ্যতা</th>
                                        <th>ফলাফল</th>
                                        <th>সেবার লিংক</th>
                                        <th>মন্তব্য</th>
                                        <th>Action</th>                               
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>

<!--Update Modal-->
<div class="modal fade" id="edit-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="col-12 modal-title text-muted text-uppercase">Update Innovation Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="update-form" name="update-form" class="form-horizontal">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="mode" value="update">
                    
                    <div class="row">
                        <!-- Fiscal Year -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_fiscal_year">অর্থবছর</label>
                                <select class="form-control" id="edit_fiscal_year" name="fiscal_year" required>
                                    <option value="">নির্বাচন করুন</option>
                                    <?php
                                        require_once('db/db.php');
                                        $sql = "SELECT * FROM fiscal_year ORDER BY id ASC";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='".$row['fiscal_year']."'>".$row['fiscal_year']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Title of Invention -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_title_of_invention">উদ্ভাবনের শিরোনাম</label>
                                <textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="edit_title_of_invention" name="title_of_invention" required></textarea>
                            </div>
                        </div>

                        <!-- Inventor Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
                                <input class="form-control" placeholder="উদ্ভাবক/উদ্ভাবকের নাম" name="inventors_name" type="text" id="edit_inventors_name" required>
                            </div>
                        </div>

                        <!-- Designation -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_inventors_designation">পদবী</label>
                                <select class="form-control" id="edit_inventors_designation" name="inventors_designation" required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                    <?php
                                        require_once('db/db.php');
                                        $sql = "SELECT * FROM designation ORDER BY id ASC";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='".$row['designation_bn']."'>".$row['designation_bn']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Employee ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_inventors_emp_id">এমপ্লয়ী নং</label>
                                <input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" id="edit_inventors_emp_id">
                            </div>
                        </div>

                        <!-- Workplace -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
                                <select class="form-control" id="edit_proposed_workplace" name="proposed_workplace">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বিসিআইসি প্র: কা:">বিসিআইসি প্র: কা:</option>
                                    <option value="জেএফসিএল">জেএফসিএল</option>
                                    <option value="জিপিইউএফসিএল">জিপিইউএফসিএল</option>
                                    <option value="এসএফসিএল">এসএফসিএল</option>
                                    <option value="এএফসিসিএল">এএফসিসিএল</option>
                                    <option value="ডিএপিএফসিএল">ডিএপিএফসিএল</option>
                                    <option value="সিইউএফএল">সিইউএফএল</option>
                                    <option value="সিসিসিএল">সিসিসিএল</option>
                                    <option value="কেপিএমএল">কেপিএমএল</option>
                                    <option value="টিএসপিসিএল">টিএসপিসিএল</option>
                                    <option value="বিআইএসএফএল">বিআইএসএফএল</option>
                                    <option value="ইউজিএসএফএল">ইউজিএসএফএল</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_des_of_invention">উদ্ভাবনের সংক্ষিপ্ত বর্নণা</label>
                                <textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="edit_des_of_invention" name="des_of_invention"></textarea>
                            </div>
                        </div>

                        <!-- Implementation Status -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_imple_status">বাস্তাবায়নের অবস্থা</label>
                                <select class="form-control" id="edit_imple_status" name="imple_status">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বাস্তবায়িত">বাস্তবায়িত</option>
                                    <option value="চলমান">চলমান</option>
                                </select>
                            </div>
                        </div>

                        <!-- Replicate Eligibility -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
                                <select class="form-control" id="edit_replicate_eligibility" name="replicate_eligibility">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বিশেষায়িত">বিশেষায়িত</option>
                                    <option value="যোগ্য">যোগ্য</option>
                                    <option value="যোগ্য না">যোগ্য না</option>
                                </select>
                            </div>
                        </div>

                        <!-- Feedback -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_feedback">ফলাফল</label>
                                <select class="form-control" id="edit_feedback" name="feedback">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="প্রত্যাশিত">প্রত্যাশিত</option>
                                    <option value="অপ্রত্যাশিত">অপ্রত্যাশিত</option>
                                </select>
                            </div>
                        </div>

                        <!-- Service Link -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_service_link">সেবার লিংক</label>
                                <textarea class="form-control" placeholder="সেবার লিংক" rows="2" id="edit_service_link" name="service_link"></textarea>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_remarks">মন্তব্য</label>
                                <textarea class="form-control" placeholder="মন্তব্য" rows="2" id="edit_remarks" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Add Innovation Modal-->
<div class="modal fade" id="add-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="col-12 modal-title text-muted text-uppercase">Add Innovation Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-form" name="add-form" class="form-horizontal">
                    <input type="hidden" name="mode" value="add">
                    
                    <div class="row">
                        <!-- Fiscal Year -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_fiscal_year">অর্থবছর</label>
                                <select class="form-control" id="add_fiscal_year" name="fiscal_year" required>
                                    <option value="">নির্বাচন করুন</option>
                                    <?php
                                        require_once('db/db.php');
                                        $sql = "SELECT * FROM fiscal_year ORDER BY id ASC";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='".$row['fiscal_year']."'>".$row['fiscal_year']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Title of Invention -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_title_of_invention">উদ্ভাবনের শিরোনাম</label>
                                <textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="add_title_of_invention" name="title_of_invention" required></textarea>
                            </div>
                        </div>

                        <!-- Inventor Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
                                <input class="form-control" placeholder="উদ্ভাবক/উদ্ভাবকের নাম" name="inventors_name" type="text" id="add_inventors_name" required>
                            </div>
                        </div>

                        <!-- Designation -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_inventors_designation">পদবী</label>
                                <select class="form-control" id="add_inventors_designation" name="inventors_designation" required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                    <?php
                                        require_once('db/db.php');
                                        $sql = "SELECT * FROM designation ORDER BY id ASC";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='".$row['designation_bn']."'>".$row['designation_bn']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Employee ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_inventors_emp_id">এমপ্লয়ী নং</label>
                                <input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" id="add_inventors_emp_id">
                            </div>
                        </div>

                        <!-- Workplace -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
                                <select class="form-control" id="add_proposed_workplace" name="proposed_workplace">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বিসিআইসি প্র: কা:">বিসিআইসি প্র: কা:</option>
                                    <option value="জেএফসিএল">জেএফসিএল</option>
                                    <option value="জিপিইউএফসিএল">জিপিইউএফসিএল</option>
                                    <option value="এসএফসিএল">এসএফসিএল</option>
                                    <option value="এএফসিসিএল">এএফসিসিএল</option>
                                    <option value="ডিএপিএফসিএল">ডিএপিএফসিএল</option>
                                    <option value="সিইউএফএল">সিইউএফএল</option>
                                    <option value="সিসিসিএল">সিসিসিএল</option>
                                    <option value="কেপিএমএল">কেপিএমএল</option>
                                    <option value="টিএসপিসিএল">টিএসপিসিএল</option>
                                    <option value="বিআইএসএফএল">বিআইএসএফএল</option>
                                    <option value="ইউজিএসএফএল">ইউজিএসএফএল</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add_des_of_invention">উদ্ভাবনের সংক্ষিপ্ত বর্নণা</label>
                                <textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="add_des_of_invention" name="des_of_invention"></textarea>
                            </div>
                        </div>

                        <!-- Implementation Status -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_imple_status">বাস্তাবায়নের অবস্থা</label>
                                <select class="form-control" id="add_imple_status" name="imple_status">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বাস্তবায়িত">বাস্তবায়িত</option>
                                    <option value="চলমান">চলমান</option>
                                </select>
                            </div>
                        </div>

                        <!-- Replicate Eligibility -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
                                <select class="form-control" id="add_replicate_eligibility" name="replicate_eligibility">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="বিশেষায়িত">বিশেষায়িত</option>
                                    <option value="যোগ্য">যোগ্য</option>
                                    <option value="যোগ্য না">যোগ্য না</option>
                                </select>
                            </div>
                        </div>

                        <!-- Feedback -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_feedback">ফলাফল</label>
                                <select class="form-control" id="add_feedback" name="feedback">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="প্রত্যাশিত">প্রত্যাশিত</option>
                                    <option value="অপ্রত্যাশিত">অপ্রত্যাশিত</option>
                                </select>
                            </div>
                        </div>

                        <!-- Service Link -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_service_link">সেবার লিংক</label>
                                <textarea class="form-control" placeholder="সেবার লিংক" rows="2" id="add_service_link" name="service_link"></textarea>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_remarks">মন্তব্য</label>
                                <textarea class="form-control" placeholder="মন্তব্য" rows="2" id="add_remarks" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <small>Design & Developed By Md. Tareq Emran, Programmer, ICT Division, BCIC.</small>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Initialize DataTable
    $('#usersListTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "ajax": "fetch.php",
        "columnDefs": [
            { "targets": [6, 10, 11], "orderable": false }
        ]
    });

    // Add modal
    $('.add-model').click(function () {
        $('#add-modal').modal('show');
    });

    // Add form submit
    $('#add-form').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "add-edit-delete.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $('#usersListTable').DataTable().ajax.reload();
                    $('#add-modal').modal('hide');
                    $('#add-form').trigger("reset");
                    alert(response.message);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
                alert("An unexpected error occurred. Please try again.");
            }
        });
    });

    // Edit function
    $('body').on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: "add-edit-delete.php",
            type: "POST",
            data: {
                id: id,
                mode: 'edit'
            },
            dataType: 'json',
            success: function(result){
                $('#edit_id').val(result.id);
                $('#edit_fiscal_year').val(result.fiscal_year);
                $('#edit_title_of_invention').val(result.title_of_invention);
                $('#edit_inventors_name').val(result.inventors_name);
                $('#edit_inventors_designation').val(result.inventors_designation);
                $('#edit_inventors_emp_id').val(result.inventors_emp_id);
                $('#edit_proposed_workplace').val(result.proposed_workplace);
                $('#edit_des_of_invention').val(result.des_of_invention);
                $('#edit_imple_status').val(result.imple_status);
                $('#edit_replicate_eligibility').val(result.replicate_eligibility);
                $('#edit_feedback').val(result.feedback);
                $('#edit_service_link').val(result.service_link);
                $('#edit_remarks').val(result.remarks);
                
                $('#edit-modal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data: " + error);
                alert("Could not fetch record details.");
            }
        });
    });

    // Update form submit
    $('#update-form').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            url: "add-edit-delete.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if (response.status === "success") {
                    $('#usersListTable').DataTable().ajax.reload();
                    $('#edit-modal').modal('hide');
                    $('#update-form').trigger("reset");
                    alert(response.message);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating: " + error);
                alert("Error updating record.");
            }
        });
    });

    // Delete function
    $('body').on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        
        if (confirm("আপনি কি নিশ্চিতভাবে মুছে ফেলতে চান?")) {
            $.ajax({
                url: "add-edit-delete.php",
                type: "POST",
                data: {
                    id: id,
                    mode: 'delete'
                },
                dataType: 'json',
                success: function(response){
                    if (response.status === "success") {
                        $('#usersListTable').DataTable().ajax.reload();
                        alert(response.message);
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting: " + error);
                    alert("Error deleting record.");
                }
            });
        }
    });

    // Reset form when modal is hidden
    $('#add-modal, #edit-modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger("reset");
    });

});
</script>
</body>
</html>
<?php
} else {
    header('location:login.php');
}
?>