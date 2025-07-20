<?php
session_start();

if(isset($_GET['id'])) {
    $_SESSION['id'] = $_GET['id'];
} 
// else {
//     header("Location: error_page.php"); // Redirect if 'id' is not provided
//     exit();
// }

include('db/db.php');

$id = $_SESSION['id'];
$sql = "SELECT * FROM legal_tbl WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);

    // Include header outside of the loop
    include_once "includes/header.php";
    $court_type1 = $row['court_type'];
    $court_division1 = $row['court_division'];
    $case_type1 = $row['case_type'];

  $sql5 = "SELECT * FROM court_type where type like '%$court_type1%'";
    $result5 = mysqli_query($conn, $sql5);
    $row5 = mysqli_fetch_array($result5);
    $abc_id5=$row5['id'];

    $sql6 = "SELECT * FROM court_division where court_division_name like '%$court_division1%'";
    $result6 = mysqli_query($conn, $sql6);
    $row6 = mysqli_fetch_array($result6);
    $abc_id6=$row6['id'] ;

    $sql7 = "SELECT * FROM case_type where type like '%$case_type1%'";
    $result7 = mysqli_query($conn, $sql7);
    $row7 = mysqli_fetch_array($result7);
    $abc_id7=$row7['id'] ;
    
 $case_no=$row['case_no'];
 $case_date=$row['case_date'];
 $case_duration=$row['case_duration'];
 
 $reason_of_case=$row['reason_of_case'];
 $plaintiff_name=$row['plaintiff_name'];
 $defendant_name=$row['defendant_name'];
 
 $lower_name_address=$row['lower_name_address'];
 $case_filing_date=$row['case_filing_date'];

 $hearing_date=$row['hearing_date'];
 $hearing_result=$row['hearing_result'];
 $next_hearing_result_date=$row['next_hearing_result_date'];
 
 $case_filing_accept_steps=$row['case_filing_accept_steps'];
 $panel_lower_info=$row['panel_lower_info'];
 $panel_low_adv_info=$row['panel_low_adv_info'];
 $remarks=$row['remarks']; 

?>
<div class="bs-example">
  <div class="container my-2 ">
    <div class="row">
			<div class="col col-sm-12">
          <div class="card border-primary rounded shadow">
	
              <div class="card-header">
                <h3 class="text-center"> মামলার তথ্য সংশোধন করুন
                  <span class="float-end">
                  <a href="add_new.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>                          
                  <a href="logout.php" class="btn btn-danger text-center"><i class="fa fa-sign-out" style="font-size:16px"></i> Logout</a>  
                  </span>

              </h3><hr class="bg-warning">
               <?php
                  if(@$_GET['submitted'])
                  {?>
                  <div class="alert alert-success" role="alert">
                  <b>  Data Updated Successfully!!!</b>
                  </div>
                  <?php }?>
              <div class="row ">
               
            <div class="card-body my-0">
        <form method="POST" action="update_legal.php" enctype="multipart/form-data">
          <!-- <form id="update-form" name="update-form" class="form-horizontal"> -->
                 <input type="hidden" name="id" id="id">
                  <input type="hidden" class="form-control" id="mode" name="mode" value="update">
                   
          <div class="row">
              <div class="col">
                <label for="court_type">আদালতের ধরণ </label>
                  <select class="form-control" id="court_type" name="court_type">
                    <option value="<?php echo $abc_id5?>"  selected  ><?php echo $court_type1?></option>
                    <?php                    
                    $sql1 = "SELECT * FROM court_type  ";
                    $result1 = mysqli_query($conn, $sql1);
                    while($row1 = mysqli_fetch_array($result1)){
                     echo "<option value='".$row1['id']."'>".$row1['type']."</option>";
                    }
                    ?> 
                    </select>
                   
                </div>
              <div class="col">                
                <label for="court_division">বিভাগ </label>
                  <select class="form-control" id="court_division" name="court_division" >                  
                  <option value="<?php echo $abc_id6?>"  selected><?php echo $court_division1?></option>
                   </select>                 
              </div>
              <div class="col">
                 <label for="case_type">মামলার ধরণ </label>
                  <select class="form-control" id="case_type" name="case_type" >                  
                    <option value="<?php echo $abc_id7?>"  selected><?php echo $case_type1?></option> 
                   </select>                   
              </div>
               <script src="js/grade_scale_basic.js" type="text/javascript"></script> 
              <script type="text/javascript">
                //court divison and case type
                $( "select[name='court_division']" ).change(function () {
                    var scaleID = $(this).val();
                    if(scaleID) {
                        $.ajax({
                            url: "ajax/ajaxpro_for_payscale.php",
                            dataType: 'Json',
                            data: {'id':scaleID},
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
            </div>
            <div class="row my-2">
              <div class="col ">
                <div class="form-group ">
                 <label for="case_no">মামলার নং</label>
                  <input class="form-control" placeholder="মামলার নং" type="text" name="case_no" value="<?=$row['case_no'];?>" id="edit-case_no" required>         
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="case_date">মামলার সাল/তারিখ</label>
                  <input class="form-control" placeholder="মামলার সাল/তারিখ" type="text" name="case_date" value="<?=$row['case_date'];?>" id="edit-case_date" >
                          
                </div>
              </div>
              <div class="col">
                <div class="form-group"><label for="case_duration">মামলা সমাপ্তির তারিখ</label>
                  <input class="form-control" placeholder="মামলা সমাপ্তির তারিখ" type="date" name="case_duration" value="<?=$row['case_duration'];?>" id="edit-case_duration" >
                          
                </div>
              </div>
            </div>

             <div class="form-group"><label for="reason_of_case">মামলার বিষয়বস্তু</label>
                  <textarea class="form-control" placeholder="মামলার বিষয়বস্তু" rows="2" id="edit-reason_of_case" value="" name="reason_of_case" ><?=$row['reason_of_case'];?></textarea>
                          
                </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group"><label for="plaintiff_name">মামলার বাদী</label>
                 
                 <input class="form-control" placeholder="মামলার বাদী" type="text" name="plaintiff_name" value="<?=$row['plaintiff_name'];?>" id="edit-plaintiff_name" required> 
                          
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="defendant_name">বিবাদীর নাম</label>
                 
                 <input class="form-control" placeholder="বিবাদীর নাম" type="text" name="defendant_name" value="<?=$row['defendant_name'];?>" id="edit-defendant_name" required>
                          
                </div>
              </div>
            </div>              
                   
                <div class="form-group"><label for="lower_name_address">আইনজীবীর নাম ও ঠিকানা</label>
                  <textarea class="form-control" placeholder="আইনজীবীর নাম ও ঠিকানা" rows="2" id="edit-lower_name_address" value="" name="lower_name_address" ><?=$row['lower_name_address'];?></textarea>
                          
                </div>

            <div class="row ">
              <div class="col ">
                <div class="form-group"><label for="case_filing_date">মামলা দায়েরের তারিখ</label>
                  <input class="form-control" placeholder="মামলার তারিখ" type="date" name="case_filing_date" value="<?=$row['case_filing_date'];?>" id="edit-case_filing_date" >
                          
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="hearing_date">শুনানীর তারিখ</label>
                  <input class="form-control" placeholder="মামলার তারিখ" type="date" name="hearing_date" value="<?=$row['hearing_date'];?>" id="edit-hearing_date" >
                          
                </div>
              </div>
              <div class="col">
              <div class="form-group">
                  <label for="hearing_result">শুনানীর ফলাফল </label>
                  <select class="form-control" id="hearing_result" name="hearing_result">
                      <option value="" disabled selected>নির্বাচন করুন</option>
                      
                      <option value="পক্ষে" <?=$hearing_result == 'পক্ষে' ? ' selected="selected"' : '';?>>পক্ষে</option>
                      <option value="বিপক্ষে" <?=$hearing_result == 'বিপক্ষে' ? ' selected="selected"' : '';?>>বিপক্ষে</option>
                      <option value="বিচারাধীন" <?=$hearing_result == 'বিচারাধীন' ? ' selected="selected"' : '';?>>পরবর্তী ধার্য তারিখ</option>
                  </select>
                  <input class="form-control mt-2" placeholder="মামলার তারিখ" type="date" name="next_hearing_result_date" value="<?=$row['next_hearing_result_date'];?>" id="next_hearing_result_date" >
              </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                      var next_hearing_result_date = $("#next_hearing_result_date");
                      next_hearing_result_date.hide();

                      if( $('#hearing_result').val() == "বিচারাধীন") {
                          next_hearing_result_date.prop("disabled", false).show();
                          next_hearing_result_date.show();
                   

                            }

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
    
                <div class="form-group "><label for="case_filing_accept_steps">মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা</label>
                  <textarea class="form-control" placeholder="মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা" rows="2" id="edit-case_filing_accept_steps" value="" name="case_filing_accept_steps" ><?=$row['case_filing_accept_steps'];?></textarea>
                          
                </div>
                 <div class="form-group"><label for="panel_lower_info">প্যানেল আইনজীবী তথ্য</label>
                  <textarea class="form-control" placeholder="প্যানেল আইনজীবী তথ্য" rows="2" id="edit-panel_lower_info" value="" name="panel_lower_info" ><?=$row['panel_lower_info'];?></textarea>
                          
                </div>
                 <div class="form-group"><label for="panel_low_adv_info">আইন উপদেষ্টা আইনজীবীর তথ্য</label>
                  <textarea class="form-control" placeholder="আইন উপদেষ্টা আইনজীবীর তথ্য" rows="2" id="edit-panel_low_adv_info" value="" name="panel_low_adv_info" ><?=$row['panel_low_adv_info'];?></textarea>
                          
                </div>
                
                <div class="form-group"><label for="remarks">মন্তব্য</label>
                  <textarea class="form-control" placeholder="মন্তব্য" rows="2" id="edit-remarks" value="" name="remarks" ><?=$row['remarks'];?></textarea>
                  </div>
                <div class="col-sm-offset-2 col-sm-12 my-2">
                   <button type="submit" class="btn btn-primary btn-block" id="submit" name="submit" value="">Update 
                   </button>
                  </div>

                </form>


              	</div>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
</div>
    
              
</body>
</html>

<?php
} else {
    echo "No data found for the provided ID.";
}

mysqli_close($conn);
?>