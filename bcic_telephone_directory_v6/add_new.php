<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  
  
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
            .holder {
                height: 180px;
                width: 180px;
				
                <!--border: 2px solid black;-->
            }
            img {
                max-width: 180px;
                max-height: 180px;
                min-width: 180px;
                min-height: 180px;
				
				
            }
            input[type="file"] {
                margin-top: 5px;
            }
            .heading {
                font-family: Montserrat;
                font-size: 45px;
                color: green;
            }
        </style>
        <script>
            $(document).ready(() => {
                $("#photo").change(function () {
                    const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            $("#imgPreview")
                              .attr("src", event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>

</head>
<body>

<div class="container mt-3">
  <p></p>
  <?php
if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully
</div>
<?php }?>
<div class="card">
  <div class="card-header"><h3 class="page-header text-muted text-uppercase text-center">Telephone Directory (--Dashboard--)</h3></div>
  <div class="card-body">
  <form action="insert.php" class="was-validated" method="POST" enctype="multipart/form-data">
 <div class="row">
	
	
		<!--1st col-->
	<div class="col-sm-4 ">
	
	<div class="form-group">
      <label for="name" class="form-label">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
      <label for="designation" class="form-label">Designation:</label>
      <select class="form-control" id="designation" name="designation" required>
				
				

				<option value="" disabled selected>নির্বাচন করুন</option>
				 <?php

                        require_once('db/db.php');

						$sql = "SELECT * FROM designation";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['designation_type']."'>".$row['designation_type']."</option>";
						}

						
						
                    ?>								
				
			  </select>
			
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
				<label for="division" class="form-label">Division/Office:</label>
				<select class="form-control" id="division" name="division" required>
				
				

				<option value="" disabled selected>নির্বাচন করুন</option>
				 <?php

                        require_once('db/db.php');

						$sql = "SELECT * FROM division";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
							$optionid=$row['id'];
							$optionname=$row['name'];
							echo "<option value='$optionid'>$optionname</option>";
						 //echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}

						
						
                    ?>								
				
			  </select>
			
	
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
		<label for="section" class="form-label">Section/Office:</label>
			<select class="form-control" id="section" name="section" required>
				
			<option value="" disabled selected>নির্বাচন করুন</option>
				 
			</select>
			
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>

	</div>
	<!--2nd col-->
	<div class="col-sm-4">
    <div class="form-group">
      <label for="phone_office" class="form-label">Phone (Office):</label>
      <input type="tel" class="form-control" id="phone_office" name="phone_office" placeholder="xx-xxxxxxxxx" pattern="[0-9]{2}-[0-9]{9}" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
      <label for="phone_home" class="form-label">Phone (Residence):</label>
      <input type="tel" class="form-control" id="phone_home" placeholder="xx-xxxxxxx" pattern="^\d{2}-\d{7}$" name="phone_home" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
      <label for="intercom" class="form-label">Intercom/PABX:</label>
      <input type="tel" class="form-control" id="intercom" placeholder="xxxx" pattern="[0-9]{4}" name="intercom" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
	<div class="form-group">
      <label for="mobile" class="form-label">Mobile:</label>
      <input type="tel" class="form-control" id="mobile" placeholder="xxxxx-xxxxxx" pattern="[0-9]{5}-[0-9]{6}" name="mobile" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
    <div class="form-group">
      <label for="email" class="form-label">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
   <!-- <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" id="myCheck"  name="remember" required>
      <label class="form-check-label" for="myCheck">I agree on blabla.</label>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Check this checkbox to continue.</div>
    </div>-->
	
  
  </div>
  <div class="col-sm-4">
  <div class="form-group">
  <div class="card" style="width:180px; vertical-align: middle;">
  <div class="holder">
         <img id="imgPreview" class="card-img-top rounded mx-auto d-block img-fluid" src="images/img_avatar1.png" alt="pic"  />
       </div>
  </div>
 </div>
  <div class="form-group">
      <label for="email" class="form-label">Select Image:</label>
      <input type="file" name="file" id="photo" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
 
    
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
  <span class=" float-center"><a href="dashboard.php" class="btn btn-primary">Previous Page</a></span>
  </div>
  
	
	

	</div>
</form>
  
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by Md. Tareq Emran, Programmer, BCIC.</h6></div>
</div>


  
  
</div>
<script>

$( "select[name='division']" ).change(function () {

    var divisionID = $(this).val();


    if(divisionID) {


        $.ajax({

            url: "ajaxpro.php",

            dataType: 'Json',

            data: {'id':divisionID},

            success: function(data) {

                $('select[name="section"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="section"]').append('<option value="'+ key +'">'+ value +'</option>');

                });

            }

        });


    }else{

        $('select[name="section"]').empty();

    }

});

</script>
</body>
</html>
