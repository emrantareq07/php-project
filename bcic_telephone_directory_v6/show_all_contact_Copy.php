<?php include_once('include/header_show_all_contact.php');
 
?>
<!-- <script src="plugin/simple-bootstrap-paginator.js"></script>
<script src="js/pagination.js"></script> -->
 <script>
//$('#main').hide();
$(document).ready(function(){

//$("#splash").fadeOut(4000);
$( "#main" ).fadeIn();
});


</script> 


<!-- <div class="container"> -->
  <!-- <div class="row justify-content-center"> -->
    <!-- <div class="col-sm-4 "></div> -->
    <!-- <div class="col-sm-4 " id="splash" ><h3 class="pt-4">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h3> -->
	<!-- <img class="mx-auto d-block " src="images/bdlogo2.png" width="170" height="170"/> -->
	<!-- <h3>শিল্প মন্ত্রণালয় </h3> -->
	<!-- <img class="mx-auto d-block" src="images/bcic_logo.png" width="170" height="170"/> -->
	<!-- <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3> -->
	<!-- <h4>(বিসিআইসি ই-ডিরেক্টরি)</h4> -->
	<!-- </div> -->
    <!-- <div class="col-sm-4 "></div> -->
  <!-- </div> -->
<!-- </div> margin-left-5 margin-right-5-->
 
 <div class="container mt-3 p-2 shadow-lg bg-white border" id ="main">
  
  <div class="col-sm-12">
  <div class="card bg-warning text-white text-center" style="height:50px">
    <div class="card-body"><h4 class="text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4></div>
  </div>
  <!--<h4 class="bg-danger text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4>-->
  </div>


  <div class="col-sm-12 pt-2">
    <div class="card">
  <div class="card-body">
     
    <div class="input-group">
     <span class="input-group-addon">Search</span>
     <input type="text" name="search_box" id="search_box" placeholder="Search by Name, Emp ID, Division etc..." class="form-control" />
    </div>
   
   
<!-- <div id="result"></div> -->
  </div>
</div></div>
  
  <div class="col-sm-12 pt-2">
<div class="card">
  <div class="card-body">
    <?php
    //index.php

    $connect = new PDO("mysql:host=localhost;dbname=bcic_tel_db", "root", "");
    //include('db/db.php');
    $query = "SELECT DISTINCT division_name FROM tel_tbl ORDER BY division_name ASC";

    $statement = $connect->prepare($query);

    $statement->execute();

    $result = $statement->fetchAll();

    ?>

   <select name="multi_search_filter" id="multi_search_filter" multiple class="form-control selectpicker">
   <?php
   foreach($result as $row)
   {
    echo '<option value="'.$row["division_name"].'">'.$row["division_name"].'</option>'; 
   }
   ?>
   </select>
   <input type="hidden" name="hidden_division_name" id="hidden_division_name" />
   <div style="clear:both"></div>
  <br />
   <div class="table-responsive" id="dynamic_content">
    <table class="table table-striped table-bordered table-hover border-muted" id="result">

     <tbody >

   

     </tbody>

    </table>
     <!--for pagination-->
<!-- <div id="pagination"></div>    
    <input type="hidden" id="totalPages" value="<?php echo $totalPages; ?>"> -->
    <!--for pagination-->
     
   </div>
  
  </div>
</div>
</div>

 
        
  </div>

<script>



  //filtering / Multiserach
$(document).ready(function(){

 load_data();
 
 function load_data(query='')
 {
  $.ajax({
   url:"fetch_tel.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('tbody').html(data);
   }
  })
 }

 $('#multi_search_filter').change(function(){
  $('#hidden_division_name').val($('#multi_search_filter').val());
  var query = $('#hidden_division_name').val();
  load_data(query);
 });
 
});

// searching data and Pagination
$(document).ready(function(){

    load_data(1);

    function load_data(page, query = '')
    {
      $.ajax({
        url:"fetch_tel_serach_pagination.php",
        method:"POST",
        data:{page:page, query:query},
        success:function(data)
        {
          $('#dynamic_content').html(data);
        }
      });
    }

    $(document).on('click', '.page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#search_box').val();
      load_data(page, query);
    });

    $('#search_box').keyup(function(){
      var query = $('#search_box').val();
      load_data(1, query);
    });

  });

//For Clickable row
// jQuery(document).ready(function($) {
//     $(".clickable-row").click(function() {
//         window.location = $(this).data("href");
//     });
// });

// $(document).ready(function(){
//     $("#limit-records").change(function(){
//       $('form').submit();
//     })
//   })
  </script>
<?php //include_once('include/footer.php');?>


