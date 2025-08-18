<?php
session_start();

require_once("db/db.php");
include('function.php');

if (isset($_POST["id"])) {
    $id = $_POST["id"];

//$kk=0;
    $sql = "SELECT * FROM land_tbl WHERE id='$id'";
    $query_run = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query_run) > 0) {

        foreach ($query_run as $row) {
           // if($row['hearing_result']=="বিচারাধীন")
           // {
           //  $row['hearing_result']="পরবর্তী ধার্য তারিখ";
           //  $kk=$row['hearing_result'];
           // }

            echo '

            <div class="row">
              <div class="col">
              <div class="form-group">
              <label for="org_name">প্রতিষ্ঠানের নাম: </label>
                  <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['org_name'] . '">
                 </div>
               </div>
              <div class="col">
              <div class="form-group">
              <label for="land_type">ভূমির ধরণ </label>
                 <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['land_type'] . '">
                 </div>
               </div>
              <div class="col">                
                <label for="division">বিভাগ </label>
                  <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['division'] . '">
              </div>

               
                <div class="col">                
                <label for="district">জেলা </label>
                <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['district'] . '">
              </div> 
              
            </div>

             <div class="row my-3">
              <div class="col ">
                <div class="form-group ">
                 <label for="upazilla_thana">উপজেলা/থানা/পৌরসভা/সিটি.কর্প</label>                   
                <textarea readonly class="form-control" placeholder="উপজেলা/থানা/পৌরসভা/সিটি.কর্প" style="font-weight: bold;" rows="2" id="upazilla_thana" value="" name="upazilla_thana" >' . $row['district'] . '
                </textarea>        
                </div>
                
              </div>
              <div class="col">
                <div class="form-group"><label for="jl_no">জেএল নং</label>

                  <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['jl_no'] . '">
                          
                </div>

              </div>
              <div class="col">
                <div class="form-group"><label for="mouza">মৌজা</label>
                <input class="form-control" placeholder="মৌজা" type="text" style="font-weight: bold;" name="mouza" value="' . $row['mouza'] . '" id="mouza" readonly>                          
                </div>
              </div>

               <div class="col">
                  <div class="form-group"><label for="land_size">জমির পরিমান(একর)</label>
                  <input class="form-control" placeholder="জমির পরিমান(একর)" type="text" name="land_size" value="' . $row['land_size'] . '" id="land_size" readonly style="font-weight: bold;">                          
                </div>
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">
                <div class="form-group ">
                 <label for="">খতিয়ান নং</label>               

                 <textarea readonly class="form-control" placeholder="সিএস" rows="1" id="khatian_cs" value="" name="khatian_cs" style="font-weight: bold;">' . $row['khatian_cs'] . '</textarea> 
              
                  <textarea readonly class="form-control" placeholder="আরএস" rows="1" id="khatian_rs" value="" name="khatian_rs" style="font-weight: bold;">' . $row['khatian_rs'] . '</textarea>  
                   
                   <textarea readonly class="form-control" placeholder="এএস" rows="1" id="khatian_as" value="" name="khatian_as" style="font-weight: bold;">' . $row['khatian_as'] . '</textarea> 

                    <textarea readonly class="form-control" placeholder="বিএস" rows="1" id="khatian_bs" value="" name="khatian_bs" style="font-weight: bold;">' . $row['khatian_bs'] . '</textarea>  

                </div>
                
              </div>
              <div class="col ">
                <div class="form-group ">
                 <label for="">দাগ নং</label>
             <textarea readonly class="form-control" placeholder="সিএস" rows="1" id="dag_cs" value="" name="dag_cs" style="font-weight: bold;">' . $row['khatian_bs'] . '</textarea>  
              
                  <textarea readonly class="form-control" placeholder="আরএস" rows="1" id="dag_rs" value="" name="dag_rs" style="font-weight: bold;">' . $row['dag_rs'] . '</textarea>  
                   
                   <textarea readonly class="form-control" placeholder="এএস" rows="1" id="dag_as" value="" name="dag_as" style="font-weight: bold;">' . $row['dag_as'] . '</textarea> 

                    <textarea readonly class="form-control" placeholder="বিএস" rows="1" id="dag_bs" value="" name="dag_bs" style="font-weight: bold;">' . $row['dag_bs'] . '</textarea>  

                </div>                
              </div>
            </div>

             <div class="row my-2">
              <div class="col ">        
             <div class="form-group"><label for="proprietary_source">মালিকানা সূত্র</label>
              <textarea readonly class="form-control" placeholder="মালিকানা সূত্র" rows="2" id="proprietary_source" value="" name="proprietary_source" style="font-weight: bold;">' . $row['proprietary_source'] . '</textarea>                          
              </div>                
              </div>

         <div class="col ">        
             <div class="form-group"><label for="namjaree_caseno_date">নামজারি কেস নং ও তারিখ</label>
              <textarea readonly class="form-control" placeholder="নামজারি কেস নং ও তারিখ" rows="2" id="namjaree_caseno_date" value="" name="namjaree_caseno_date" style="font-weight: bold;">' . $row['namjaree_caseno_date'] . '</textarea>
                          
                </div>                
              </div>
            </div> 

     <div class="row my-2">

         <div class="col ">        
             <div class="form-group"><label for="land_development_taxpayment">ভূমি উন্নয়ন কর পরিশোধের বিবরণ</label>              
                <textarea readonly class="form-control" placeholder="ভূমি উন্নয়ন কর পরিশোধের বিবরণ" rows="2" id="land_development_taxpayment" value="" name="land_development_taxpayment" style="font-weight: bold;">' . $row['land_development_taxpayment'] . '</textarea>
                          
                </div>                
              </div>       

              <div class="col ">        
             <div class="form-group"><label for="boundary_wall">সীমানা প্রাচীর বা বেড়া আছে কিনা?</label>              
                  <textarea readonly class="form-control" placeholder="সীমানা প্রাচীর বা বেড়া আছে কিনা?" rows="2" id="boundary_wall" value="" name="boundary_wall" style="font-weight: bold;">' . $row['boundary_wall'] . '</textarea>
                          
                </div>                
              </div>
            </div>                
            ';               
        }
    } else {
        // Handle case when no rows are found
    }
}
?>
