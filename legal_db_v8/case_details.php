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
<div class="bs-example">
  <div class="container-fluid my-2">
    <div class="row">
      <div class="col-sm-12">
          <div class="card">
  
              <div class="card-header">
              <div class="row">
                <div class= "col-sm-8"><h2 class="float-left text-muted text-uppercase">বিসিআইসি মামলার ডাটাবেস </h2></div>
                <div class="col-sm-4">
                <span class="float-end">
                  <a href="user-dashboard_details.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>  
                              
                  <a href="logout.php" class="btn btn-danger text-center "><i class="fa fa-sign-out" style="font-size:16px"></i> Logout</a>  
                </span>           
                </div>
                        
                </div>
              </div>
          <div class="card-body">
            <div class="table-responsive">
                    <div class=" table-hover">
                   <table id="usersListTable" class="display" >
                        <thead class="thead-dark">
                            <tr>
                                <th>আদালতের ধরণ</th>
                                <th>বিভাগ</th>
                                <th>মামলার ধরণ</th>
                                <th>মামলার নং</th>
                                <th>মামলার তারিখ/সাল</th>
                                <!-- <th>মামলা সমাপ্তির তারিখ </th> -->
                                <th>মামলা উদ্ভাবনের কারণ</th>
                                <th>মামলার বাদী</th>
                                <th>বিবাদীর নাম</th>
                                <!-- <th>আইনজীবীর নাম ও ঠিকানা</th>
                                <th>মামলা দায়েরের তারিখ</th>
                                <th>শুনানীর তারিখ</th>
                                <th>শুনানীর ফলাফল</th>
                                <th>পরবর্তী শুনানীর তারিখ</th>
                                <th>মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা</th>
                                <th>প্যানেল আইনজীবী তথ্য</th>
                                <th>আইন উপদেষ্টা আইনজীবীর তথ্য</th>
                                
                                <th>মন্তব্য</th>  -->
                               <th></th>
                            </tr>
                        </thead>
                        <!--<tfoot>
                            <tr>
                                <th>অর্থবছর</th>
                                </tr>
                        </tfoot>-->
                  </table>
                </div>
                </div>
                </div>
                </div>
        </div>
            </div>        
        </div>
    </div>
</body>
<!-- viewmodal -->

<!--Add new-->
<div class="modal fade" id="add-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header text-center">
              <h4 class="col-12 modal-title text-muted text-uppercase" id="userCrudModal"> Add Legal Data</h4>

          </div>
          <div class="modal-body">
              <form id="add-form" name="add-form" class="form-horizontal"  accept-charset="UTF-8">
                 <input type="hidden" class="form-control" id="mode" name="mode" value="add">
         
          
           <!-- <div class="col-sm-12">  -->

              <div class="row">
              <div class="col">
                
                <label for="court_type">আদালতের ধরণ </label>
                  <select class="form-control" id="court_type" name="court_type" >
                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   <?php
                    
                    $sql = "SELECT * FROM court_type  ";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result))
                    {
                     echo "<option value='".$row['id']."'>".$row['type']."</option>";
                    }
                    ?>                  
                   </select>
              </div>
              <div class="col">
                
                <label for="court_division">বিভাগ </label>
                  <select class="form-control" id="court_division" name="court_division" >
                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                 
                   </select>
              </div>
              <div class="col">
                 <label for="case_type">মামলার ধরণ </label>
                  <select class="form-control" id="case_type" name="case_type" >
                  
                  <option value="" disabled selected>নির্বাচন করুন</option>
                   
                   </select>
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group ">
                 <label for="case_no">মামলার নং</label>
                  <input class="form-control" placeholder="মামলার নং" type="text" name="case_no" value="" id="case_no" required>         
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="case_date">মামলার সাল/তারিখ</label>

                  <input class="form-control" placeholder="মামলার সাল/তারিখ" type="text" name="case_date" value="" id="case_date" >
                          
                </div>
              </div>
              <div class="col">
                <div class="form-group"><label for="case_duration">মামলা সমাপ্তির তারিখ</label>
                  <input class="form-control" placeholder="মামলা সমাপ্তির তারিখ" type="date" name="case_duration" value="" id="case_duration" >


                          
                </div>
              </div>
            </div>

             <div class="form-group"><label for="reason_of_case">মামলার বিষয়বস্তু</label>
                  <textarea class="form-control" placeholder="মামলার বিষয়বস্তু" rows="2" id="reason_of_case" value="" name="reason_of_case" ></textarea>
                          
                </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group"><label for="plaintiff_name">মামলার বাদী</label>
                 
                 <input class="form-control" placeholder="মামলার বাদী" type="text" name="plaintiff_name" value="" id="plaintiff_name" required> 
                          
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="defendant_name">বিবাদীর নাম</label>
                
                 <input class="form-control" placeholder="বিবাদীর নাম" type="text" name="defendant_name" value="" id="defendant_name" required>
                          
                </div>
              </div>
            </div>              
                   
                <div class="form-group"><label for="lower_name_address">বিজ্ঞ আইনজীবীর নাম ও ঠিকানা</label>
                  <textarea class="form-control" placeholder="বিজ্ঞ আইনজীবীর নাম ও ঠিকানা" rows="2" id="lower_name_address" value="" name="lower_name_address" ></textarea>
                          
                </div>

            <div class="row ">
              <div class="col ">
                <div class="form-group"><label for="case_filing_date">মামলা দায়েরের তারিখ</label>
                  <input class="form-control" placeholder="মামলার তারিখ" type="date" name="case_filing_date" value="" id="case_filing_date" >
                          
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="hearing_date">শুনানীর তারিখ</label>
                  <input class="form-control" placeholder="মামলার তারিখ" type="date" name="hearing_date" value="" id="hearing_date" >
                          
                </div>
              </div>
              <div class="col">
              <div class="form-group">
              <label for="hearing_result">শুনানীর ফলাফল </label>
                  <select class="form-control" id="hearing_result" name="hearing_result" >
                  
                  <option value=""  selected>নির্বাচন করুন</option>
                   <option value="পক্ষে">পক্ষে</option>
                   <option value="বিপক্ষে">বিপক্ষে</option>
                  <option value="বিচারাধীন">পরবর্তী ধার্য তারিখ</option>
                   </select>

                    <input class="form-control mt-2" placeholder="মামলার তারিখ" type="date" name="next_hearing_result_date" value="" id="next_hearing_result_date" >
                </div>
                <script type="text/javascript">
                         $(document).ready(function() {
                            var next_hearing_result_date = $("#next_hearing_result_date");
                            next_hearing_result_date.hide();

                            $('#hearing_result').change(function() {
                                if ($(this).val() == "বিচারাধীন") {
                                    next_hearing_result_date.prop("disabled", false).show();
                                } else {
                                    next_hearing_result_date.hide();
                                }
                            });
                        });
                    </script>
              </div>
            </div>             
    
                <div class="form-group"><label for="case_filing_accept_steps">মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা</label>
                  <textarea class="form-control" placeholder="মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা" rows="2" id="case_filing_accept_steps" value="" name="case_filing_accept_steps" ></textarea>
                          
                </div>
                 <div class="form-group"><label for="panel_lower_info">প্যানেল আইনজীবী তথ্য</label>
                  <textarea class="form-control" placeholder="প্যানেল আইনজীবী তথ্য" rows="2" id="panel_lower_info" value="" name="panel_lower_info" ></textarea>
                          
                </div>
                 <div class="form-group"><label for="panel_low_adv_info">আইন উপদেষ্টা আইনজীবীর তথ্য</label>
                  <textarea class="form-control" placeholder="আইন উপদেষ্টা আইনজীবীর তথ্য" rows="2" id="panel_low_adv_info" value="" name="panel_low_adv_info" ></textarea>
                          
                </div>
                
                <div class="form-group"><label for="remarks">মন্তব্য</label>
                  <textarea class="form-control" placeholder="মন্তব্য" rows="2" id="remarks" value="" name="remarks" ></textarea>
                  </div>
                           
                  <div class="col-sm-offset-3 col-sm-12">
                   <button type="submit" class="btn btn-primary btn-block" id="btn-save" value="create">Save
                   </button>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
             
          </div>
      </div>
  </div>
</div>
<?php
   
?>
<!-- View section -->
<div class="modal fade" id="lawVIEWModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header ">
              <h4 class="col-11 modal-title text-center text-muted text-uppercase" id="userCrudModal">বিস্তারিত মামলার তথ্য</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="law_viewing_data"> </div>          
          </div>
          <div class="modal-footer">
             
          </div>
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


  <script type="text/javascript">
    
//for Scale and Basic
$( "select[name='court_division']" ).change(function () {
    var court_divisionID = $(this).val();
    if(court_divisionID) {
        $.ajax({
            url: "ajax/ajaxpro_for_payscale.php",
            dataType: 'Json',
            data: {'id':court_divisionID},
            success: function(data) {
                $('select[name="case_type"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="case_type"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });
    }else{
        $('select[name="case_type"]').empty();
    }
});
  </script>
<script>
$(document).ready(function(){
    $('#usersListTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[8, "desc"]], 
        "ajax": "case_details_fetch.php"
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