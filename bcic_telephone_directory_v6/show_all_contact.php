<?php include_once('include/header.php');?>
<style>
 body {
  font-family: Arial, Helvetica, sans-serif;
}

[data-href] {
    cursor: pointer;
	cursor: hand;
}
</style>
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
  <div class="row">
  <div class="col-sm-12">
  <div class="card bg-warning text-white text-center" style="height:50px">
    <div class="card-body"><h4 class="text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4></div>
  </div>
  <!--<h4 class="bg-danger text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4>-->
  </div>

  <div class="col-sm-12 pt-2">
<div class="card">
  <div class="card-body">
    

 
<div class="description-container mt-3">
<!--Start Chairman Section-->
<div class="col-sm-12">
 
 	
	<table class="table table-bordered table-hover border-muted table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
   
    <tbody>
	<?php
	include('db/db.php');
		
		$sql="SELECT id,name,designation,division_name,section_name,phone_office,phone_home,intercom,mobile,email,image
		FROM tel_tbl";
		
    		$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  // output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $image=$row['image']
			?>
	<!--<tr class='clickable-row' data-href='chairman.php?id=<?php //echo $rows["id"]; ?>'>-->
      <tr class='clickable-row ' data-href='personal_details.php?id=<?php echo $row["id"]; ?>' >
	  <td width="30" ><?php echo $row['id']; ?></td>
        <td width="90"><img src="upload/<?php echo $row['image']; ?>" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" 
		width="90" height="50"></td>
		<td><h4 ><?php echo $row['name']; ?></h4>
		<p class="text-muted"><?php echo $row['designation']; ?></p></td>
		 </tr>
	    </tbody>
		<?php
			  }
			}
			else {
				echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
			}
            //}?>
  </table>
      <p class="text-muted"> </p>
    </div>
  </div>
 
</div>
</div>

<script>
  /*$(".section-header").addClass("closed");

$('.section-header').click(function() {
  let $this = $(this);
  let $others = $(".section-header").not($this);
  
  $others.addClass("closed").removeClass('active');;
  $others.siblings().slideUp();

  $this.toggleClass("closed active");
  $this.siblings().slideToggle();

  return false;
});*/

//For Clickable row
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
  </script>
<?php include_once('include/footer.php');?>


