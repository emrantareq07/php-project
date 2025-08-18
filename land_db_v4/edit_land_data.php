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
$sql = "SELECT * FROM land_tbl WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    // Include header outside of the loop
    include_once "includes/header.php";    
    $org_name = $row['org_name'];

    $division = $row['division'];
    $district = $row['district'];

    // Fetch   org_name
    $court_type_query = "SELECT id FROM org_tbl WHERE org_name like '%$org_name%'";
    $court_type_result = mysqli_query($conn, $court_type_query);
    $court_type_row = mysqli_fetch_assoc($court_type_result);
    $org_name_id = $court_type_row['id'];

    // Fetch court division name
    $court_division_query = "SELECT id FROM divisions WHERE bn_name like '%$division%'";
    $court_division_result = mysqli_query($conn, $court_division_query);
    $court_division_row = mysqli_fetch_assoc($court_division_result);
    $division_id = $court_division_row['id'];

    // Fetch  districts name
    $case_type_query = "SELECT id FROM districts WHERE bn_name like '%$district%'";
    $case_type_result = mysqli_query($conn, $case_type_query);
    $case_type_row = mysqli_fetch_assoc($case_type_result);
    $district_id = $case_type_row['id'];
  
   
 $land_type=$row['land_type'];
 $upazilla_thana=$row['upazilla_thana'];
 //$concate3col=$case_type.' নং-'.$case_no.'/'.$case_date;
 
 $jl_no=$row['jl_no']; 
 $mouza=$row['mouza'];
 $owner_name=$row['owner_name'];
 $khatian_cs=$row['khatian_cs']; 
 $khatian_rs=$row['khatian_rs'];
 $khatian_as=$row['khatian_as'];
 $khatian_bs=$row['khatian_bs'];

 $dag_cs=$row['dag_cs'];
 $dag_rs=$row['dag_rs']; 
 $dag_as=$row['dag_as']; 
 $dag_bs=$row['dag_bs'];

 $land_size=$row['land_size'];
 $proprietary_source=$row['proprietary_source'];
  $namjaree_caseno_date=$row['namjaree_caseno_date']; 
 $land_development_taxpayment=$row['land_development_taxpayment']; 
 $boundary_wall=$row['boundary_wall'];         

?>
<div class="bs-example">
  <div class="container my-2 ">
    <div class="row">
			<div class="col col-sm-12">
          <div class="card border-success rounded border-2 shadow-lg">
	
              <div class="card-header">
                <h3 class="text-center text-success fw-bold"> ভূমির তথ্য সংশোধন করুন
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
              <div class="form-group">
              <label for="org_name">প্রতিষ্ঠানের নাম: </label>
                  <select class="form-control" id="org_name" name="org_name" >                  
                  <option value="<?php echo $org_name_id?>" selected><?php echo $org_name?></option>
                   <?php                    
                    $sql = "SELECT * FROM org_tbl  ";
                    $result = mysqli_query($conn, $sql);
                    while($row2 = mysqli_fetch_array($result))
                        {
                         echo "<option value='".$row2['id']."'>".$row2['org_name']."</option>";
                        }
                    ?>        
                   </select>
                 </div>
               </div>
              <div class="col">
              <div class="form-group">
              <label for="land_type">ভূমির ধরণ </label>
                  <select class="form-control" id="land_type" name="land_type" >                  
                  <option value="<?php echo $land_type?>"  selected><?php echo $land_type?></option>
                   <option value="ব্যক্তি মালিকানা">ব্যক্তি মালিকানা</option>
                   <option value="খাস জমি">খাস জমি</option>
                  <option value="লীজকৃত জমি">লীজকৃত জমি</option>
                  <option value="মালিকাধীন">মালিকাধীন</option>
                   </select>
                 </div>
               </div>
              <div class="col">                
                <label for="division">বিভাগ </label>
                  <select class="form-control" id="division" name="division" >                  
                  <option value="<?php echo $division_id?>" selected><?php echo $division?></option>
                   <?php
                    
                    $sql = "SELECT * FROM divisions  ";
                    $result = mysqli_query($conn, $sql);
                    while($row1 = mysqli_fetch_array($result))
                        {
                         echo "<option value='".$row1['id']."'>".$row1['bn_name']."</option>";
                        }
                    ?>                  
                   </select>
              </div>               
                <div class="col">                
                <label for="district">জেলা </label>
                  <select class="form-control" id="district" name="district" >                  
                  <option value="<?php echo $district_id?>"  selected><?php echo $district?></option>                 
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
                  <input class="form-control" placeholder="উপজেলা/থানা/পৌরসভা.." type="text" name="upazilla_thana" value="<?php echo $upazilla_thana;?>" id="upazilla_thana" required> 
                <!--   <textarea class="form-control" placeholder="উপজেলা/থানা/পৌরসভা/সিটি.কর্প" rows="2" id="upazilla_thana" value="" name="upazilla_thana" ></textarea> -->        
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="jl_no">জেএল নং</label>
                  <input class="form-control" placeholder="জেএল নং" type="text" name="jl_no" value="<?=$row['jl_no'];?>" id="jl_no" >                          
                </div>

              </div>
              <div class="col">
                <div class="form-group"><label for="mouza">মৌজা</label>
                <input class="form-control" placeholder="মৌজা" type="text" name="mouza" value="<?=$row['mouza'];?>" id="mouza" > </div>
              </div>

               <div class="col">
                  <div class="form-group"><label for="mouza">জমির পরিমান(একর)</label>
                  <input class="form-control" placeholder="জমির পরিমান(একর)" type="text" name="land_size" value="<?=$row['land_size'];?>" id="land_size" >                          
                </div>
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group ">
                 <label for="">খতিয়ান নং</label>
                 <textarea class="form-control" placeholder="সিএস" rows="1" id="khatian_cs" value="" name="khatian_cs" ><?=$row['khatian_cs'];?></textarea>               
                  <textarea class="form-control" placeholder="আরএস" rows="1" id="khatian_rs" value="" name="khatian_rs" ><?=$row['khatian_rs'];?></textarea>                   
                   <textarea class="form-control" placeholder="এএস" rows="1" id="khatian_as" value="" name="khatian_as" ><?=$row['khatian_as'];?></textarea> 
                    <textarea class="form-control" placeholder="বিএস" rows="1" id="khatian_bs" value="" name="khatian_bs" ><?=$row['khatian_bs'];?></textarea>
                </div>
                
              </div>
              <div class="col ">
                <div class="form-group ">
                 <label for="">দাগ নং</label>
                 <textarea class="form-control" placeholder="সিএস" rows="1" id="dag_cs" value="" name="dag_cs" ><?=$row['dag_cs'];?></textarea>                
                  <textarea class="form-control" placeholder="আরএস" rows="1" id="dag_rs" value="" name="dag_rs" ><?=$row['dag_rs'];?></textarea>                    
                   <textarea class="form-control" placeholder="এএস" rows="1" id="dag_as" value="" name="dag_as" ><?=$row['dag_as'];?></textarea>
                    <textarea class="form-control" placeholder="বিএস" rows="1" id="dag_bs" value="" name="dag_bs" ><?=$row['dag_bs'];?></textarea>
                </div>                
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">        
                 <div class="form-group"><label for="proprietary_source">মালিকানা সূত্র</label>
                  <textarea class="form-control" placeholder="মালিকানা সূত্র" rows="2" id="proprietary_source" value="" name="proprietary_source" ><?=$row['proprietary_source'];?></textarea>                          
              </div>                
              </div>

            <div class="col ">        
             <div class="form-group"><label for="namjaree_caseno_date">নামজারি কেস নং ও তারিখ</label>
              <textarea class="form-control" placeholder="নামজারি কেস নং ও তারিখ" rows="2" id="namjaree_caseno_date" value="" name="namjaree_caseno_date" ><?=$row['namjaree_caseno_date'];?></textarea>                          
                </div>                
              </div>
            </div> 

     <div class="row my-2">
         <div class="col ">        
             <div class="form-group"><label for="land_development_taxpayment">ভূমি উন্নয়ন কর পরিশোধের বিবরণ</label>             
                <textarea class="form-control" placeholder="ভূমি উন্নয়ন কর পরিশোধের বিবরণ" rows="2" id="land_development_taxpayment" value="" name="land_development_taxpayment" ><?=$row['land_development_taxpayment'];?></textarea>                          
                </div>                
              </div>       

              <div class="col ">        
             <div class="form-group"><label for="boundary_wall">সীমানা প্রাচীর বা বেড়া আছে কিনা?</label>              
                  <textarea class="form-control" placeholder="সীমানা প্রাচীর বা বেড়া আছে কিনা?" rows="2" id="boundary_wall" value="" name="boundary_wall" ><?=$row['boundary_wall'];?></textarea>                  
      <!--         <select class="form-control" id="boundary_wall" name="boundary_wall" >                  
                  <option value="<?php echo $boundary_wall?>"  disabled selected><?php echo $boundary_wall?></option>
                   <option value="হ্যাঁ">হ্যাঁ</option>
                   <option value="না">না</option>
                  <option value="খুটি">খুটি</option>                  
                   </select> -->                          
                </div>                
              </div>
            </div>                     
          
                <div class="col-sm-offset-2 col-sm-12 my-2">
                   <button type="submit" class="btn btn-primary btn-block" id="submit" name="submit" value=""><i class="fa fa-save" style="font-size:16px"></i> Update 
                   </button>
                  </div>
                </form>
              	</div>
              </div>
          </div>
      </div>
  </div>
</div><?php  include('includes/footer_modal.php');  ?>
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