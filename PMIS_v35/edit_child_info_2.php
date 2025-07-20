<?php
// Start the session
//https://editor.datatables.net/examples/inline-editing/join.html
//https://www.youtube.com/watch?v=j1O2uM1nsZA
//https://www.youtube.com/watch?v=rng1bQUFaCA&lc=UggO3ZDVNYbaVHgCoAEC
//https://www.youtube.com/watch?v=nd9nOQemVAc
//https://www.webslesson.info/2021/08/live-vanilla-datatables-crud-with-javascript-php-mysql.html
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
?>
<?php include('db/db.php'); 
//include('includes/header.php');?>


<html>
 <head>
  <title>Live Add Edit Delete Datatables Records using PHP Ajax</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
  <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
  <style>
  body
  {
   margin:0;
   padding:0;
   background-color:#f1f1f1;
  }
  .box
  {
   width:1270px;
   padding:20px;
   background-color:#fff;
   border:1px solid #ccc;
   border-radius:5px;
   margin-top:25px;
   box-sizing:border-box;
  }
  </style>
 </head>
 <body>
  <div class="container box">
   <h1 align="center">Live Add Edit Delete Datatables Records using PHP Ajax</h1>
   <br />
   <div class="table-responsive">
   <br />
    <div align="right">
     <button type="button" name="add" id="add" class="btn btn-info">Add</button>
     <a class="btn btn-primary" href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>
    </div>
    <br />
    <div id="alert_message"></div>
    <table id="user_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Name</th>
       <th>Date of Birth</th>
       <th>Gender</th>
       <th></th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </body>
</html>

<script type="text/javascript" language="javascript" >
 $(document).ready(function(){
  
  fetch_data();

  function fetch_data()
  {
   var dataTable = $('#user_data').DataTable({
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : {
     url:"fetch_child_info_2.php",
     type:"POST"
    }
   });
  }
  
  function update_data(id, column_name, value)
  {
   $.ajax({
    url:"update_child_info_2.php",
    method:"POST",
    data:{id:id, column_name:column_name, value:value},
     //source:[{value: "Male", text: "Male"}, {value: "Female", text: "Female"}],
     fields: [ {
     
                label: "Start date:",
                name: "dob",
                type: "datetime"
            
     },
      {
            "label": "Edit Field 2:",
            "name": "wbs_name_1",
            type: 'select',
            options: ['Choice 1', 'Choice 2', 'Choice 3']
        }
     ],
    success:function(data)
    {
     $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
     $('#user_data').DataTable().destroy();
     fetch_data();
    }
   });
   setInterval(function(){
    $('#alert_message').html('');
   }, 5000);
  }

  $(document).on('blur', '.update', function(){
   var id = $(this).data("id");
   var column_name = $(this).data("column");
   var value = $(this).text();
   update_data(id, column_name, value);
  });
  
  $('#add').click(function(){
   var html = '<tr>';
   html += '<td contenteditable id="data1"></td>';
   html += '<td contenteditable id="data2"><input class="form-control" placeholder="Enter Home District" type="date" name="dob" id="dob" value=""></td>';
   html += '<td contenteditable id="data3"><select class="form-control" id="gender" name="gender" ><option value="" disabled selected>--Select--</option><option value="Male" >Male</option><option value="Female">Female</option></select></td>';
   html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Insert</button></td>';
   html += '</tr>';
   $('#user_data tbody').prepend(html);
  });
  
  $(document).on('click', '#insert', function(){
   var name = $('#data1').text();
   var dob = $('#data2').text();
   var gender = $('#data3').text();
   if(name != '' && dob != ''&& gender != '')
   {
    $.ajax({
     url:"insert_child_info_2.php",
     method:"POST",
     data:{name:name, dob:dob,gender:gender},
     success:function(data)
     {
      $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
      $('#user_data').DataTable().destroy();
      fetch_data();
     }
    });
    setInterval(function(){
     $('#alert_message').html('');
    }, 5000);
   }
   else
   {
    alert("Both Fields is required");
   }
  });
  
  $(document).on('click', '.delete', function(){
   var id = $(this).attr("id");
   if(confirm("Are you sure you want to remove this?"))
   {
    $.ajax({
     url:"delete_child_info_2.php",
     method:"POST",
     data:{id:id},
     success:function(data){
      $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
      $('#user_data').DataTable().destroy();
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
