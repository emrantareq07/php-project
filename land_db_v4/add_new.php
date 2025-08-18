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
      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
//session  condition  end  but it follows until bottom of the page
include_once"includes/header.php";
?> 
<!-- <div class="bs-example"> -->
  <div class="container-fluid my-2">
    <div class="row">
      <div class="col col-sm-12">
          <div class="card shadow">  
              <div class="card-header">
              <div class="row">
                <div class="col col-sm-8"><h2 class="float-left text-success text-uppercase fw-bold">বিসিআইসি ভূমি সংক্রান্ত তথ্যর ডাটাবেস </h2></div>
          <div class="col col-sm-4">
            <span class="float-end">
               <a href="admin-dashboard.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>              
              <a href="javascript:void(0)" class="btn btn-success text-right add-model"> <i class="fa fa-plus"></i> Add New  </a>
              <a href="logout.php" class="btn btn-danger text-center"> <i class="fa fa-sign-out" style="font-size:16px"></i>Logout</a>  
              </span>
                    </div>                        
                  </div>
              </div>
          <div class="card-body">
            <div class="table-responsive">
                <div class=" table-hover">
                   <table id="usersListTable" class="display"  >
                        <thead class="text-white" style="background-color:#8e13f2;">
                          <style>
                            /* Custom CSS to increase font size */
                            .custom-font-size {
                                font-size: 1.1rem; /* Adjust this value as needed */
                            }
                        </style>
                        <tr class=" text-white">
                            <th class="custom-font-size">প্রতিষ্ঠানের নাম</th>
                            <th class="custom-font-size">ভূমির ধরণ</th>
                            <th class="custom-font-size">বিভাগ</th>
                            <th class="custom-font-size">জেলা</th>
                            <th class="custom-font-size">উপজেলা/থানা/পৌরসভা/সিটি.কর্প</th>
                            <th class="custom-font-size">জেএল নং</th>
                            <th class="custom-font-size">মৌজা</th>
                            <th class="custom-font-size">জমির পরিমান (একর)</th>
                            <th >এ্যাকশন</th>
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
<!-- end viewmodal -->

<!--Add new-->
<div class="modal fade" id="add-modal" aria-hidden="true">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <!-- <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal">  ভূমির তথ্য এন্ট্রি করুন</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> -->
              <div class="modal-header ">
              <h4 class="col-11 modal-title text-center text-success text-uppercase fw-bold" id="userCrudModal">ভূমির তথ্য এন্ট্রি করুন</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="add-form" name="add-form" class="form-horizontal"  accept-charset="UTF-8">
                 <input type="hidden" class="form-control" id="mode" name="mode" value="add">        
          
           <!-- <div class="col-sm-12">  -->
              <div class="row">
              <div class="col">
              <div class="form-group">
              <label for="org_name">প্রতিষ্ঠানের নাম</label>
                  <select class="form-control" id="org_name" name="org_name" >                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   <?php                    
                    $sql = "SELECT * FROM org_tbl  ";
                    $result = mysqli_query($conn, $sql);
                    while($row_org_name = mysqli_fetch_array($result)){
                     echo "<option value='".$row_org_name['id']."'>".$row_org_name['org_name']."</option>";
                    }
                    ?>        
                   </select>
                 </div>
               </div>
              <div class="col">
              <div class="form-group">
              <label for="land_type">ভূমির ধরণ </label>
                  <select class="form-control" id="land_type" name="land_type" >                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   <option value="ব্যক্তি মালিকানা">ব্যক্তি মালিকানা</option>
                   <option value="খাস জমি">খাস জমি</option>
                  <option value="লীজকৃত জমি">লীজকৃত জমি</option>
                  <option value="মালিকাধীন">মালিকাধীন</option>
                  <option value="অধিগ্রহনকৃত জমি">অধিগ্রহনকৃত জমি</option>
                  <option value="ক্রয়কৃত জমি">ক্রয়কৃত জমি</option>
                   </select>
                 </div>
               </div>

              <div class="col">                
                <label for="division">বিভাগ </label>
                  <select class="form-control" id="division" name="division" >                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   <?php                    
                    $sql = "SELECT * FROM divisions  ";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result)){
                       echo "<option value='".$row['id']."'>".$row['bn_name']."</option>";
                      }
                    ?>                  
                   </select>
              </div>
               
                <div class="col">                
                <label for="district">জেলা </label>
                  <select class="form-control" id="district" name="district" >                  
                  <option value="" disabled selected>নির্বাচন করুন</option>                 
                   </select>
              </div> 
                <script type="text/javascript">                
                $( "select[name='division']" ).change(function () {
                    var divisionID = $(this).val();
                    if(divisionID) {
                        $.ajax({
                            url: "ajax/ajaxpro_for_payscale.php",
                            dataType: 'Json',
                            data: {'id':divisionID},
                            success: function(data) {
                                $('select[name="district"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });
                            }
                        });
                    }else{
                        $('select[name="district"]').empty();
                    }
                });
              </script> 
            </div>

             <div class="row my-3">
              <div class="col ">
                <div class="form-group ">
                 <label for="upazilla_thana">উপজেলা/থানা/পৌরসভা/সিটি.কর্প</label>
                  <input class="form-control" placeholder="উপজেলা/থানা/পৌরসভা.." type="text" name="upazilla_thana" value="" id="upazilla_thana" required> 
                <!--   <textarea class="form-control" placeholder="উপজেলা/থানা/পৌরসভা/সিটি.কর্প" rows="2" id="upazilla_thana" value="" name="upazilla_thana" ></textarea> -->        
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="jl_no">জেএল নং</label>
                  <input class="form-control" placeholder="জেএল নং" type="text" name="jl_no" value="" id="jl_no" >
                </div>

              </div>
              <div class="col">
                <div class="form-group"><label for="mouza">মৌজা</label>
                <input class="form-control" placeholder="মৌজা" type="text" name="mouza" value="" id="mouza" >
                 </div>
              </div>

               <div class="col">
                  <div class="form-group"><label for="mouza">জমির পরিমান (একর)</label>
                  <input class="form-control" placeholder="জমির পরিমান (একর)" type="text" name="land_size" value="" id="land_size" oninput="this.value = this.value.replace(/[^\u09E6-\u09EF.]/g, '');" >                          
                </div>
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group ">
                 <label for="">খতিয়ান নং</label>
                <!--  <textarea 
                    class="form-control" 
                    placeholder="সিএস" 
                    rows="1" 
                    id="khatian_cs" 
                    name="khatian_cs" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                ></textarea> 
                  <textarea class="form-control" placeholder="আরএস" rows="1" id="khatian_rs" value="" name="khatian_rs" oninput="this.value = this.value.replace(/[^0-9\u0980-\u09FF]/g, '');"></textarea> 
                  <textarea class="form-control" placeholder="এএস" rows="1" id="khatian_as" value="" name="khatian_as" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></textarea> 
                  <textarea class="form-control" placeholder="বিএস" rows="1" id="khatian_bs" value="" name="khatian_bs" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></textarea> -->

                  <textarea 
                    class="form-control" 
                    placeholder="সিএস" 
                    rows="1" 
                    id="khatian_cs" 
                    name="khatian_cs"                     
                ></textarea> 
                  <textarea class="form-control" placeholder="আরএস" rows="1" id="khatian_rs" value="" name="khatian_rs" ></textarea> 
                  <textarea class="form-control" placeholder="এএস" rows="1" id="khatian_as" value="" name="khatian_as" o></textarea> 
                  <textarea class="form-control" placeholder="বিএস" rows="1" id="khatian_bs" value="" name="khatian_bs" ></textarea>  
                </div>
                
              </div>
              <div class="col ">
                <div class="form-group ">
                 <label for="">দাগ নং</label>
             <textarea class="form-control" placeholder="সিএস" rows="1" id="dag_cs" value="" name="dag_cs" ></textarea>
                  <textarea class="form-control" placeholder="আরএস" rows="1" id="dag_rs" value="" name="dag_rs" ></textarea>
                   <textarea class="form-control" placeholder="এএস" rows="1" id="dag_as" value="" name="dag_as" ></textarea> 
                    <textarea class="form-control" placeholder="বিএস" rows="1" id="dag_bs" value="" name="dag_bs" ></textarea>
                </div>                
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">        
             <div class="form-group"><label for="proprietary_source">মালিকানা সূত্র</label>
              <textarea class="form-control" placeholder="মালিকানা সূত্র" rows="2" id="proprietary_source" value="" name="proprietary_source" ></textarea>                          
              </div>                
              </div>

         <div class="col ">        
             <div class="form-group"><label for="namjaree_caseno_date">নামজারি কেস নং ও তারিখ</label>
              <textarea class="form-control" placeholder="নামজারি কেস নং ও তারিখ" rows="2" id="namjaree_caseno_date" value="" name="namjaree_caseno_date" ></textarea>                          
                </div>                
              </div>
            </div> 

     <div class="row my-2">
         <div class="col ">        
             <div class="form-group"><label for="land_development_taxpayment">ভূমি উন্নয়ন কর পরিশোধের বিবরণ</label>             
                <textarea class="form-control" placeholder="ভূমি উন্নয়ন কর পরিশোধের বিবরণ" rows="2" id="land_development_taxpayment" value="" name="land_development_taxpayment" ></textarea>                        
                </div>                
              </div>       

              <div class="col ">        
             <div class="form-group"><label for="boundary_wall">সীমানা প্রাচীর বা বেড়া আছে কিনা?</label>
            <!--   <select class="form-control" id="boundary_wall" name="boundary_wall" >                  
                  <option value=""  selected>নির্বাচন করুন</option>
                   <option value="হ্যাঁ">হ্যাঁ</option>
                   <option value="না">না</option>
                  <option value="খুটি">খুটি</option>                  
                   </select>  -->             
                  <textarea class="form-control" placeholder="সীমানা প্রাচীর বা বেড়া আছে কিনা?" rows="2" id="boundary_wall" value="" name="boundary_wall" ></textarea>                          
                </div>                
              </div>
            </div>  
                           
            <div class="col-sm-offset-3 col-sm-12 my-2">
              <button type="submit" class="btn btn-primary btn-block" id="btn-save" value="create"><i class="fa fa-save" style="font-size:16px"></i> Save
              </button>
            </div> 
              </form>
          </div>
          <!-- <div class="modal-footer">
             
          </div> -->
          <?php  include('includes/footer_modal.php');  ?>
      </div>
  </div>
</div>

<!-- View section -->
<div class="modal fade" id="lawVIEWModal" aria-hidden="true">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header ">
              <h4 class="col-11 modal-title text-center text-success text-uppercase fw-bold" id="userCrudModal">বিস্তারিত ভূমির তথ্য</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="law_viewing_data"> </div>          
          </div>
  <?php  include('includes/footer_modal.php');  ?>
      </div>
  </div>
</div>

<!-- Bootstrap CSS (you might already have this) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="datatables.js"></script>
  <script src="js/grade_scale_basic.js" type="text/javascript"></script> 
<script>
// $(document).ready(function(){
//     $('#usersListTable').DataTable({
//         "processing": true,
//         "serverSide": true,
//         "order": [],
//         "ajax": "fetch.php"
//     });
// });
$(document).ready(function() {
    $('#usersListTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "fetch.php",
            "type": "GET"
        },
        "order": [[8, "desc"]] // Order by the 9th column (id) in descending order
    });
});

  /*  add user model */
$('.add-model').click(function () {
    $('#add-modal').modal('show');
});

// add form submit
$('#add-form').submit(function(e){
    e.preventDefault();
    // ajax
    $.ajax({
        url: "add-edit-delete.php",
        type: "POST",
        data: $(this).serialize(), // get all form field value in serialize form
        success: function(){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
            $('#add-modal').modal('hide');
            $('#add-form').trigger("reset");
            // Show success alert message
            alert("Data added successfully!");
        }
    });
}); 

// add form submit
$('#update-form').submit(function(e){
    e.preventDefault();       
    // ajax
    $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: $(this).serialize(), // get all form field value in serialize form
        success: function(){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
            $('#edit-modal').modal('hide');
            $('#update-form').trigger("reset");
        }
    });
});  

$(document).on('click', '.view', function(){
    var id = $(this).attr("id");
    $.ajax({
        url: "view.php",
        method: "POST",
        data: { id: id },
        success: function(data){
            // Ensure that the id is properly encoded for URL
            var encodedId = encodeURIComponent(id);
            // Redirect to edit_legal_data.php with the id parameter
            window.location.href = 'edit_land_data.php?id=' + encodedId;
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
});

$(document).on('click', '.btn-view', function(){
    var id = $(this).attr("id");
    $.ajax({
        url: "view3.php",
        method: "POST",
        data: { id: id },
        success: function (response) {
$('.law_viewing_data').html(response);
$('#lawVIEWModal').modal('show');
        }      
    });
});

/* DELETE FUNCTION */
$('body').on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    if (confirm("Are You sure want to delete !")) {
     $.ajax({
        url:"add-edit-delete.php",
        type: "POST",
        data: {
            id: id,
            mode: 'delete' 
        },
        dataType : 'json',
        success: function(result){
            var oTable = $('#usersListTable').dataTable(); 
            oTable.fnDraw(false);
        }
     });
    } 
    return false;
});
$(document).ready( function () {
    $('.table').DataTable({
       "order": [[1, "desc"]], //"2" is my date array position
       lengthMenu: [
            [5,10, 25, 50, -1],
            [5,10, 25, 50, 'All'],
        ],
    });
  });
</script>
</html>
<?php } else{
  // header('location:login/logout.php');
   header('location:index.php');
  } 
?>