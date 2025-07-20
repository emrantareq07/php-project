$('document').ready(function(){
 var emp_id_state = false;
 //var email_state = false;
 $('#emp_id').on('blur', function(){
  var emp_id = $('#emp_id').val();
  if (emp_id == '') {
  	emp_id_state = false;
  	return;
  }
  $.ajax({
    url: 'registration_form.php',
    type: 'post',
    data: {
    	'emp_id_check' : 1,
    	'emp_id' : emp_id,
    },
    success: function(response){
      if (response == 'taken' ) {
      	emp_id_state = false;
      	$('#emp_id').parent().removeClass();
      	$('#emp_id').parent().addClass("form_error");
      	$('#emp_id').siblings("span").text('Sorry... emp_id already taken');
      }else if (response == 'not_taken') {
      	emp_id_state = true;
      	$('#emp_id').parent().removeClass();
      	$('#emp_id').parent().addClass("form_success");
      	$('#emp_id').siblings("span").text('emp_id available');
      }
    }
  });
 });	
 });