<?php
include('catsubcat-script.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

 <!-- font awesome  -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


<!-- <link href="assests/style.css" rel="stylesheet"> -->
<title>Signin Admin</title>

<style type="text/css">
  
.right-col{
    width: 70%;
    float: right;
margin-left: 50px;
  }

body{
    overflow-x: hidden;}
.left-col form {
    height: 100vh;
    border: 2px solid #f1f1f1;
    padding: 12px;
    background-color: white; }
.left-col{
    width: 20%;
    float: left;
    margin-left: 0px;
    /*background: #f1f1f1;*/
    height: 100vh;}
.left-col a{
  text-decoration: none;
  font-size: 20px;
  /*color: orangered;*/
  line-height: 25px}
.left-col ul{
  list-style-type:none;
}
.form input, .form select{
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;}
.form select{
    width:109%;}
.form input[type=text]:focus, input[type=password]:focus {
    background-color: #ddd;
    outline: none;}
.form button[type=submit] {
    background-color: #434140;
    color: #ffffff;
    padding: 10px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 111%;
    opacity: 0.9;
    font-size: 20px;}
.form label{
  font-size: 18px;;}
.form button[type=submit]:hover {
  background-color:#3d3c3c;}
.form-title a, .form-title h2{
   display: inline-block;}
.form-title a{
      text-decoration: none;
      font-size: 20px;
      background-color: green;
      color: honeydew;
      padding: 2px 10px;}
 .form{
  width:80%;}


  .table-wrapper {
  max-height: 500px;
  overflow: auto;
  display:inline-block;
}
</style>
</head>
<body>

<div class="container pt-2 mt-2 mb-3 bg-white shadow border rounded">
  <div class="row" style="min-height: 90vh;">
    
    <!--====form section start====-->

<div class="left-col">
<ul>
  <li><a class="btn text-center text-uppercase btn-success my-4" href="catsubcat-form.php?add=add-category">Add Cadre</a></li>
  <li> <a class="btn text-center text-uppercase btn-success" href="catsubcat-form.php?add=add-subcategory">Add Sub-cadre</a></li> 
  <li><a class="btn text-center text-uppercase btn-info my-4" href="utilities.php">Back</a></li>
  <li><a href="logout.php" class="btn btn-danger float-left"> Logout </a></li> 
</ul>       
</div>
<!--====form section start====-->

<div class="right-col">
<div class="form">

<!-- <p style="color:red" > -->
 <h3 class=" text-success" >       
<?php if(!empty($msg)){echo $msg; }?>

  </h3> 
        
<?php

 echo $add=$_GET['add']??'';
 switch ($add) {
 case 'add-category':
         
?>

<!--==== category form=====-->
<div class="form-title">
<h2>Create Cadre</h2>
</div>
<form method="post" action="">
   <!-- <label>Category</label> -->
   <input type="text" class="form-control" id="title" placeholder="Enter Category" name="category_name" required>
   <!-- <input type="text" placeholder="Enter Full Name" name="category_name" required> -->
    <button type="submit" name="addcat" class="btn btn-primary">Add Cadre</button>
</form>
<!--=======subcategory form====-->
             
<?php

     break;
     case 'add-subcategory':
         
?>

 <!--==== subcategory form=====-->
 <div class="form-title">
 <h2 class="text-uppercase text-center">Create Sub-Cadre</h2>
 </div>
<form method="post" action="">
     <label>Choose Cadre</label>
     <select name="parent_id" class="">
             
<?php
foreach ($catData as $cat) {
?> <option value="<?php echo $cat['id']; ?>"> <?php echo $cat['cadre']; ?>
</option>
<?php } ?>

      </select>
    <label>Sub-Cadre</label>
    <input type="text" placeholder="Enter Subcategory" class="form-control" name="subcategory_name" required>
    <button type="submit" name="addsubcat" class="btn btn-primary">Add Cub-Cadre</button>
 </form>
 <!--=======subcategory form====-->
             
<?php
      break;
      
      default:
     
?>

<h3 class="text-center text-primary text-uppercase">Add Cadre and Sub-cadre </h3>
<hr>
             
<?php
      break;
    }
    
?>
</div>

   <div class="row">
    <div class="table-wrapper">
        <h4 class="text-center text-uppercase text-success">Cadre List</h4>
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Cadre</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `cadre`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['cadre']; ?></td>
              <td>
                <!-- <a href="edit_cadre.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a> -->
                <a href="#"><i class="fa fa-edit"></i></a>
                <a href="delete_cadre.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>

    <div class="table-wrapper">
        <h4 class="text-center text-uppercase text-success">Sub-Cadre List</h4>
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Sub-Cadre</th>
        <th>Cadre_id</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `sub_cadre`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['sub_cadre']; ?></td>
              <td><?php echo $row['cadre_id']; ?></td>
              <td>
                <a href="edit_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
  </div> 

  </div>
  </div>
</div>
    </body>
</html>
