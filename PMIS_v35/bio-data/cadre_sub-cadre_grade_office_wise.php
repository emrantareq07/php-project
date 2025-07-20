
<?php
// session_start();
include('../db/db.php');
include('../includes/header.php');

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"  />

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>


<div class="container mt-3 p-1 my-1 border border-primary shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
      <h3 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b> Search by Grade, Cadre, Sub-Cadre and Place of Posting Wise</b></h3>
      <div class="row">
    
        <div class="col-sm-12">
                
    <div class="panel-body">
      
  
    <div class="col-sm-3"> </div>   
          
    <div class="col-sm-6"> 
      <div class="card border border-warning">
        <div class="card-header" style="background-color: #8b32a8">

            <h5 class="text-uppercase text-white " ><b>Bio-Data, Short Bio-Data & Gradation List</b></h5>
        
        </div>
      <div class="card-body">
        
      <form method="POST" action="cadre_sub-cadre_grade_office_wise_info.php" enctype="multipart/form-data">

   <div class="form-group">
        <label for="biodata_gradation">Select Type:</label>
        <select class="form-control" id="biodata_gradation" name="biodata_gradation" tabindex="" required>
              <option value="" disabled selected>--Select--</option>
              <option value="Bio-data" >Bio-Data</option>
              <!-- <option value="Short Bio-data" >Short Bio-Data</option> -->
              <option value="Gradation" >Gradation</option>
      
          </select>     
        
        </div>


        <div class="form-group">
        <label for="grade">Grade:</label>
        <select class="form-control" id="grade" name="grade" tabindex="" required>
            <option value="" disabled selected>--Select--</option>
            <?php
                    
            $sql = "SELECT * FROM grade";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
             echo "<option value='".$row['grade']."'>".$row['grade']."</option>";
            }?> 
           </select>
        
        
        </div>

          <div class="form-group"><label for="cadre">Cadre:</label>
          <select class="form-control" id="cadre" name="cadre" required>
            <option value="" disabled selected>--Select--</option>
            <?php
                        
            $sql = "SELECT * FROM cadre";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
             echo "<option value='".$row['id']."'>".$row['cadre']."</option>";
            }?> 
           </select>
          </div>
          <div class="form-group"><label for="sub_cadre">Sub cadre:</label>
          <select class="form-control" id="sub_cadre" name="sub_cadre" required>
            <option value="" disabled selected>--Select--</option>
            
           </select>
          </div>

          <div class="form-group"><label for="sub_cadre_header">Sub-Cadre Header:</label>
            <select class="form-control" id="sub_cadre_header" name="sub_cadre_header">
              <option value="" disabled selected>--Select--</option>
              <?php
                          
              $sql = "SELECT * FROM sub_cadre_header";
              $result = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_array($result)){
               echo "<option value='".$row['header']."'>".$row['header']."</option>";
              }?> 
             </select>
          </div>


            <div class="form-group"><label for="place_of_posting">Place of Posting:</label>
              <select class="form-control" id="place_of_posting" name="place_of_posting" required>
                
              <option value="" disabled selected>--Select--</option>
              <option value="All">All</option>
                <?php
                    $sql = "SELECT * FROM place_of_posting order by place_of_posting asc";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result))
                    {
                     echo "<option value='".$row['place_of_posting']."'>".$row['place_of_posting']."</option>";
                    }?>
                
              </select>
        </div>

          <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span> Search</button>  
               
              
      </form>
      <!-- <button class="btn" name="btn" id="btn">Reset</button>  -->

      </div>
    </div>
</div>
<div class="col-sm-3"> <center><a href="bio-data_and_gradation_list.php" class="btn btn-primary float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></center></div>

      </div>
    </div>
  </div>
</div>

  <script>

//for cadre and Sub cadre
$( "select[name='cadre']" ).change(function () {

    var cadreID = $(this).val();


    if(cadreID) {


        $.ajax({

            url: "../ajax/ajaxpro_subcadre.php",

            dataType: 'Json',

            data: {'id':cadreID},

            success: function(data) {

                $('select[name="sub_cadre"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="sub_cadre"]').append('<option value="'+ key +'">'+ value +'</option>');

                });

            }

        });


    }else{

        $('select[name="sub_cadre"]').empty();

    }

});


  
  //   jQuery('#designation').chosen();
  // jQuery('#place_of_posting').chosen();



</script>
<?php include('../includes/footer.php');?>