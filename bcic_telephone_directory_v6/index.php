<?php include_once('include/header.php');?>
 <script>

$(document).ready(function(){

$("#splash").fadeOut(8000);
//$( "#main" ).fadeIn(10000);
setTimeout("window.location.href='home.php';",4000);
});



</script> 
  
<div class="container">
  <div class="row justify-content-center">
    <div class="col-sm-4 "></div>
    <div class="col-sm-4 " id="splash" ><h3 class="pt-4">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h3>
	<img class="mx-auto d-block " src="images/bdlogo2.png" width="170" height="170"/>
	<h3 class="mt-2">শিল্প মন্ত্রণালয় </h3>
	<img class="mx-auto d-block" src="images/bcic_logo.png" width="170" height="170"/>
	<h3 class="mt-2">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3>
	<h4>(বিসিআইসি ই-ডিরেক্টরি)</h4>
	</div>
    <div class="col-sm-4 "></div>
  </div>
</div>

<?php include_once('include/footer.php');?>



