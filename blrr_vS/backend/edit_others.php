<?php
session_name('blrr');
include_once '../db/database.php';
session_start();
$table_name=$_SESSION['table_name'];
$office=$_SESSION['office'];
// echo $office;
$office_title = $_SESSION['office_title'];

// Assuming you have an ID to identify the specific record you want to edit
$record_id = $_GET['id'];
$val=$_GET["val"];

$_SESSION['val']=$_GET["val"];
//echo $record_id;
// echo $_SESSION['val'];
$today_date=date("Y-m-d");
//$unique_id = $row1["unique_id"];
// $table_parent = preg_replace('/[^a-zA-Z]/', '', $unique_id);

// $result_for_chair = mysqli_query($conn,"SELECT * FROM $table_parent where unique_id='$unique_id' ");
// $row_chair = mysqli_fetch_array($result_for_chair);
// $ref_number_chair=$row_chair["ref_number"];
// $subject_chair=$row_chair["subject"];

$sql = "SELECT * FROM $table_name WHERE id = $record_id";
$result = mysqli_query($conn, $sql);

// if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $entry_date = $row['entry_date'];    
    $d_number = $row['d_number'];
    
    $destination=$row['destination'];
    $distribution_date=$row['distribution_date'];
    $ref_number = $row['ref_number'];
	   $subject = $row['subject'];
    $status = $row['status'];
    
    $stored_emp_ids = $row['destination_drop'];
    // Convert the stored string back into an array
    $selected_emp_ids = explode(',', $stored_emp_ids);
// } else {
//     echo "Record not found.";
//     exit;
// }

?>
<?php include_once"../includes/header.php";?>
  <div class="container my-2 bg-light">
    <div class="row">
        <form method="POST" action="update_others.php" enctype="multipart/form-data">
            <div class="col col-sm-12">
          <div class="card border-success rounded border-2 shadow-lg">    
              <div class="card-header">
                <h3 class="text-center text-muted fw-bold"> পত্র প্রাপ্তি রেজিস্টার সংশোধন করুন
                  <span class="float-end">
                    <?php 
                    if($val=='111'){
                    ?>
                  <a href="incoming_letter.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>  
                  <?php } else if($val=='000'){ ?> 
                  <a href="../dashboard.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>
                  <?php } ?> 
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
                    <?php 
                    // if($distribution_date==''){
                    //   $distribution_date=date("Y-m-d");
                    // }
                    ?>
                  <input type="date" class="form-control" id="distribution_date_u" placeholder="" name="distribution_date" value="<?php echo $distribution_date;?>"> 
                </div>
                  </div>
                  <div class="col-md">
                  <div class="form-group  mt-2"><label> গন্তব্য (লিখুন): </label>
                   <!-- <input type="text" class="form-control" id="destination_u" placeholder="" name="destination" required> -->
                   <textarea class="form-control" placeholder="" rows="1" id="destination_u" value="" name="destination" ><?php echo $destination;?></textarea>                
                    </div>
                  </div>               
              </div>          

          <div class="row g-2">
                  <div class="col-md">
                  <div class="form-group  mt-2"><label for="entry_date"> পত্র প্রাপ্তি তারিখ :</label>
                       <?php 
                     if($entry_date == '') {
                      // Set the default date in Y-m-d format for the input field
                      $entry_date = date("Y-m-d");
                    
                      // Convert existing date from d-m-Y format to Y-m-d format
                      // $entry_date = date("Y-m-d", strtotime($entry_date));
                    }
                    ?>
                   <input type="date" class="form-control" id="entry_date_u" placeholder="" name="entry_date" value="<?php echo $entry_date;?>" >                
                    </div>
                  </div>
                  <div class="col-md">
                  <div class="form-group"><label> ডকেট নং : </label>
                    <?php 
                  function englishToBanglaNumber($number) {
                  $englishNumbers = range(0, 9);
                  $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                  return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
                    }
                    ?>
                   <input type="text" class="form-control" value="<?php echo englishToBanglaNumber($d_number);?>" id="d_number_u" placeholder="" name="d_number" required oninput="validateInput(this)">                
                    </div>
                  </div>                 
             </div>  

             <div class="row g-2">
               <div class="col-md">
                  <div class="form-group mb-2 mt-1"><label> স্মারক নং : </label>
                   <input type="text" class="form-control" value="<?php echo englishToBanglaNumber($ref_number);?>" id="ref_number_u" placeholder="" name="ref_number" readonly>               
                </div>
                  </div>
				<div class="col-md">
                  <div class="form-group mb-2 mt-1">
		            <label> বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু: </label>
		            <!-- <input type="text"  name="subject" class="form-control" id="subject_u"> -->
		            <textarea class="form-control" placeholder="" readonly rows="1" id="subject_u" value="" name="subject" ><?php echo $subject;?></textarea> 
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
<script>  jQuery('#destination_drop').chosen();  
  
function validateInput(input) {
  // Regular expression to match both English (0-9) and Bengali numerals (০-৯)
  const validChars = /[0-9০-৯]/g;                       
  // Remove any characters that are not valid numerals
  input.value = input.value.replace(/[^0-9০-৯]/g, '');
}
</script>
