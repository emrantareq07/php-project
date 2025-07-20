<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

?>


<html>
	<head>
		<title>In-Place Editing in DataTable with X-Editable using PHP Ajax</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.js"></script>

  		<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script> -->

  		<!--  <script src="//cdn.datatables.net/plug-ins/1.13.4/dataRender/datetime.js"></script>
  		<script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>
  		<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css" /> -->
  		<style type="text/css">
  			div.collapse {
    position:static;}
    /* overflow:visible;or* /
    
}
  		</style>
	</head>
	<body>
		<div class="container">
			<h3 align="center">Update and Delete Children information</h3>
			<br />
			<div class="panel panel-default">
				<div class="panel-heading">Children information</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table id="sample_data" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th> Emp ID</th>
									<th> Name</th>
									<th>Date of Birth</th>
									<th>Gender</th>
									
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		<br />
		<br />
	</body>
</html>

<script type="text/javascript" language="javascript">

$(document).ready(function(){

	var dataTable = $('#sample_data').DataTable({
		
		"processing": true,
		"serverSide": true,
		"order":[],
		"ajax":{
			url:"fetch_child_info_3.php",
			type:"POST",

		},
		createdRow:function(row, data, rowIndex)
		{
			$.each($('td', row), function(colIndex){
				if(colIndex == 1)
				{
					$(this).attr('data-name', 'emp_id');
					$(this).attr('class', 'emp_id');
					$(this).attr('data-type', 'text');
					$(this).attr('data-pk', data[0]);
				}
				if(colIndex == 2)
				{
					$(this).attr('data-name', 'name');
					$(this).attr('class', 'name');
					$(this).attr('data-type', 'text');
					$(this).attr('data-pk', data[0]);
				}
				if(colIndex == 3)
				{
					$(this).attr('data-name', 'dob');
					$(this).attr('class', 'dob');
					$(this).attr('data-type', 'date');
					$(this).attr('data-placement', 'left');
					$(this).attr('data-pk', data[0]);

				}
				if(colIndex == 4)
				{
					$(this).attr('data-name', 'gender');
					$(this).attr('class', 'gender');
					$(this).attr('data-type', 'select');
					$(this).attr('data-pk', data[0]);
				}



			});
		}
		  
	});

	$('#sample_data').editable({
		container:'body',
		selector:'td.name',
		url:'update_child_info_3.php',
		title:'Name',
		type:'POST',
		validate:function(value){
			if($.trim(value) == '')
			{
				return 'This field is required';
			}
		}
	});

	$('#sample_data').editable({
		container:'body',
		selector:'td.dob',
		url:'update_child_info_3.php',
		title:'DOB',
		type:'POST',
		validate:function(value){
			if($.trim(value) == '')
			{
				return 'This field is required';
			}
		}
	});

	$('#sample_data').editable({
		container:'body',
		selector:'td.gender',
		url:'update_child_info_3.php',
		title:'Gender',
		type:'POST',
		datatype:'json',
		source:[{value: "Male", text: "Male"}, {value: "Female", text: "Female"}],
		validate:function(value){
			if($.trim(value) == '')
			{
				return 'This field is required';
			}
		}
	});



	$(document).on('click', '.delete', function(){
   var id = $(this).attr("id");
   if(confirm("Are you sure you want to remove this?"))
   {
    $.ajax({
     url:"delete.php",
     method:"POST",
     data:{id:id},
     success:function(data){
      $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
      $('#sample_data').DataTable().destroy();
      fetch_data();
     }
    });
    setInterval(function(){
     $('#alert_message').html('');
    }, 5000);
   }
  });
});	



</script>
