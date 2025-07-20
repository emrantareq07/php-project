<?php
session_name('blrr');
session_name('blrr');
include_once '../db/database.php';
session_start();
$table_name=$_SESSION['table_name'];
$immediate_sender_office=$_SESSION['office'];
$office_title = $_SESSION['office_title'];
// if(isset($_GET['id'])) {
//     $_SESSION['id'] = $_GET['id'];
// } 
// Assuming you have an ID to identify the specific record you want to edit
$record_id = $_GET['id'];
$today_date=date("Y-m-d");
// $id = $_SESSION['id'];
// Fetch the record from the database
$sql = "SELECT * FROM $table_name WHERE id = $record_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $entry_date = $row['entry_date'];
    $recipient = $row['recipient'];
    $d_number = $row['d_number'];
    $attention = $row['attention'];
    $ref_number = $row['ref_number'];
    $send_date = $row['send_date'];
    $sender = $row['sender'];
    $div_dept_office = $row['div_dept_office'];
    $subject = $row['subject'];
    $chairman_note = $row['chairman_note'];
    $comments = $row['comments'];
    $medium = $row['medium'];
    $destination=$row['destination'];
    $distribution_date=$row['distribution_date'];

    $stored_emp_ids = $row['destination_drop'];
    // Convert the stored string back into an array
    $selected_emp_ids = explode(',', $stored_emp_ids);
} else {
    echo "Record not found.";
    exit;
}
?>
<?php include_once"../includes/header.php";?>

  <div class="container my-2 bg-light">
    <div class="row">
        <form method="POST" action="update_show_all.php" enctype="multipart/form-data">
            <div class="col col-sm-12">
          <div class="card border-success rounded border-2 shadow-lg">    
              <div class="card-header">
                <h3 class="text-center text-muted fw-bold"> পত্র প্রাপ্তি রেজিস্টার সংশোধন করুন
                  <span class="float-end">
                  <a href="show_all.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>                   
                    <button type="submit" id="submit" name="submit" class="btn  btn-primary btn-block"><i class="fa fa-save" ></i> Update</button>
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
            <div class="form-group" >
                <label for="emp_id">গন্তব্য (এক/একাধিক নির্বাচন করুন):</label>
                <select class="form-control chosen-select custom-chosen-container" id="destination_drop" data-placeholder="Choose EMP ID" name="destination_drop[]" multiple="multiple" multiple class="chosen-select"> 
                    <?php 
                    if($office_title=="chairman"){
                      $sql = "SELECT division_bn FROM division ";
                    }               
                    else{
                      $sql = "SELECT division_bn FROM division where id not in(2,3,4)";
                    }                
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $division = $row['division_bn'];
                        // Check if the current EMP ID was previously selected
                        $selected = in_array($division, $selected_emp_ids) ? 'selected' : '';
                        echo "<option value='$division' $selected>$division</option>";
                    }
                    ?>
                </select>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#destination_drop').chosen({
                            width: '100%', // Ensure it takes the full width
                        });
                        // Adjust height of the Chosen dropdown after initialization
                        $('#destination_drop_chosen .chosen-choices').css('min-height', '35px');
                    });
                </script>
            </div>
            <div class="row g-2">
                <div class="col-md">
                  <div class="form-group  mt-2">
                    <label> বিতরণ তারিখ:  </label>
                  <input type="date" class="form-control" id="distribution_date_u" placeholder="" name="distribution_date" value="<?php echo $distribution_date;?>"> </div>
                  </div>
                  <div class="col-md">
                  <div class="form-group  mt-2"><label> গন্তব্য (লিখুন): </label>
                   <!-- <input type="text" class="form-control" id="destination_u" placeholder="" name="destination" required> -->
                   <textarea class="form-control" placeholder="" rows="1" id="destination_u" value="" name="destination" ><?php echo $destination;?></textarea>                
                    </div>
                  </div>               
              </div>          

          <div class="row g-2">
                     <?php 
                      if($table_name=='chairman'){
                      ?>
                  <div class="col-md">
                  <div class="form-group  mt-2"><label for="entry_date"> পত্র প্রাপ্তি তারিখ :</label>
                   <input type="date" class="form-control" id="entry_date_u" placeholder="" name="entry_date" value="<?php echo $entry_date;?>" >                
                    </div>
                  </div>
                  <?php }?>
                  <div class="col-md">
                  <div class="form-group  mt-2"><label for="recipient">প্রাপক  :</label>
                  <!-- <input type="text" class="form-control" id="recipient_u" value="<?php echo $recipient;?>" placeholder="" name="recipient"> -->
                  <select class="form-select form-control" id="recipient" name="recipient"  aria-label="Default select example">
                          <option selected disabled value="">--Select--</option>
                          <?php 
                          $query ="SELECT division_bn FROM division where id not in(2,3,4)";
                          $result = $conn->query($query);
                          if($result->num_rows> 0){
                              while($optionData=$result->fetch_assoc()){
                              $option =$optionData['division_bn'];
                          ?>
                          <?php
                          if(!empty($recipient) && $recipient== $option){
                          ?>
                          <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
                          <?php 
                          continue;
                         }?>
                          <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
                         <?php
                          }}
                          ?>  
                      </select>
                    </div>
                  </div>
              </div>  

           <div class="row g-2">
                  <div class="col-md">
                  <div class="form-group"><label> ডকেট নং : </label>
                    <?php 
                  function englishToBanglaNumber($number) {
                  $englishNumbers = range(0, 9);
                  $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                  return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
                    }
                    ?>
                   <input type="text" class="form-control  " value="<?php echo englishToBanglaNumber($d_number);?>" id="d_number_u" placeholder="" name="d_number" readonly>                
                    </div>
                  </div>

              <!-- <div class="col-md">
                <div class="form-group row mt-2">
                <label for="date2" class="col-form-label col-sm-4">ডকেট নং :</label> -->
                <?php 
                  // function englishToBanglaNumber($number) {
                  // $englishNumbers = range(0, 9);
                  // $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                  // return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
                  //   }
                  ?>
              <!--   <div class="col-sm-8">
                   <input type="text" class="form-control bg-secondary text-white" value="<?php echo englishToBanglaNumber($d_number);?>" id="d_number_u" placeholder="" name="d_number" readonly>
                    </div>
                </div>
              </div> -->


            <!-- <div class="col-md">
                <div class="form-group row mt-2">
                <label for="date2" class="col-form-label col-sm-4">দৃষ্টি আকর্ষণ :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" value="<?php echo $attention;?>" id="attention_u" placeholder="" name="attention"> 
                    </div>
                </div>
              </div> -->

                  <div class="col-md">
                  <div class="form-group">
                    <label> দৃষ্টি আকর্ষণ : </label>
                  <input type="text" class="form-control" value="<?php echo $attention;?>" id="attention_u" placeholder="" name="attention">                  
                    </div>
                  </div>
              </div>  

              <div class="row g-2">
               <div class="col-md">
                  <div class="form-group mb-1 mt-1"><label> স্মারক নং : </label>
                   <input type="text" class="form-control" value="<?php echo $ref_number;?>" id="ref_number_u" placeholder="" name="ref_number" >               
                </div>
                  </div>

                <div class="col-md">
                  <div class="form-group mb-1 mt-1">
                    <label> পাঠানোর তারিখ : </label>
                  <input type="date" class="form-control" value="<?php echo $send_date;?>" id="send_date_u" placeholder="" name="send_date">                 
                </div>
                  </div>
              </div>  

          <div class="row g-2">
               <div class="col-md">
                  <div class="form-group mb-1 mt-1"><label> পত্র প্রেরক: </label>
                   <input type="text" class="form-control" value="<?php echo $sender;?>" id="sender_u" placeholder="" name="sender" >               
                </div>
                  </div>

                <div class="col-md">
                  <div class="form-group mt-1">
                  <label> ডিভিশন/ডিপার্টমেন্ট/অফিস : </label>
                  <input type="text" class="form-control" value="<?php echo $div_dept_office;?>" id="div_dept_office_u" placeholder="" name="div_dept_office" value="">                  
                </div>
                  </div>
              </div>

          <div class="form-group  mt-1">
            <label> বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু: </label>
            <!-- <input type="text"  name="subject" class="form-control" id="subject_u"> -->
            <textarea class="form-control" placeholder="" rows="1" id="subject_u" value="" name="subject" ><?php echo $subject;?></textarea> 
          </div>  

          <div class="row g-2">
               <div class="col-md">
                  <div class="form-group  mt-1">
                    <label> চেয়ারম্যান নোট: </label>
                   <input type="text" class="form-control" value="<?php echo $chairman_note;?>" id="chairman_note_u" placeholder="" name="chairman_note" >               
                </div>
                  </div>

                <div class="col-md">
                  <div class="form-group  mt-1">
                  <label> মন্তব্য: </label>
                  <input type="text" class="form-control" value="<?php echo $comments;?>" id="comments_u" placeholder="" value="" name="comments">                  
                </div>
                  </div>
              </div>

              <div class="row g-2">
               <div class="col-md">
                  <div class="form-group mb-1 mt-1">
                    <label> মাধ্যম (হার্ডকপি/ফ্যাক্স/ই-মেইল) </label>
                   <!-- <input type="text" class="form-control" value="<?php echo $medium;?>" id="medium_u" placeholder="" name="medium" >                -->
                   <select class="form-control" id="medium" name="medium" > 
                    <option value=""  selected>--Select--</option>
                    <option value="হার্ডকপি"  <?=$medium == 'হার্ডকপি' ? ' selected="selected"' : '';?> >হার্ডকপি</option>
                    <option value="ই-মেইল"  <?=$medium == 'ই-মেইল' ? ' selected="selected"' : '';?> >ই-মেইল</option>
                    <option value="ফ্যাক্স"  <?=$medium == 'ফ্যাক্স' ? ' selected="selected"' : '';?> >ফ্যাক্স</option>
                  </select>
                </div>
                  </div>
              </div>    

    <input type="hidden" name="record_id" value="<?php echo $record_id; ?>">
    <button type="submit" id="submit" name="submit" class="btn  btn-primary btn-block"><i class="fa fa-save" ></i> Update</button>
</form>

    </div>
  </div>
</div>
 </div>
</div>

</div>
</div>

</body>
</html> 
  <script>  jQuery('#destination_drop').chosen();  </script>
