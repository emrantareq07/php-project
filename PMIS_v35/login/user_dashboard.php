<!-- user_dashboard.php -->
<?php
session_start();

if (isset($_SESSION['emp_id'])) {
    $emp_id = $_SESSION['emp_id']; 
} else {
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BCIC PMIS | User Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- BOX ICONS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css" />  
    <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">     
      <!-- Custom styles for this template -->
  <link href="css/sidebars.css" rel="stylesheet">
</head>
<body>
<body data-spy="scroll" data-target="#efec-scroll" data-offset="10">
<?php include 'includes/user_dashboard_topbar.php' ?>
<?php include 'includes/user_dashboard_sidebar_1.php' ?>

  <!-- Main Wrapper -->
  <div class="container">
    <h2 class="text-uppercase text-center text-muted sha">User Dashboard     <?php
    echo "Hello EMP ID: <b class='text-center text-success text-big'>$emp_id</b>. Welcome!";
    ?>      
    </h2>
    <hr class="border border-2 border-primary bg-primary">
<?php
include('db.php');
$emp_id = $_SESSION['emp_id'];
$sql = "SELECT * FROM users j 
    left JOIN basic_info b ON j.emp_id = b.emp_id 
    -- INNER JOIN job_desc k ON j.emp_id = k.emp_id 
    -- WHERE j.emp_id = '$emp_id'
 WHERE b.emp_id = '$emp_id'";
    $result = mysqli_query($conn,$sql);
    if (mysqli_num_rows($result) > 0) {          
  echo "<table class='table table-bordered table-striped text-center rounded border-rounded'>";
    echo  '<thead class="table-dark text-center">';
      echo  '<tr>';
        echo  '<th class="">Emp ID</th>';
        echo  '<th ">Name</th>';    
        echo  '<th >Designation</th>';
        echo  '<th >Division</th>';
        echo  '<th >Place of Posting</th>';
        echo  '<th>Email ID</th>'; 
      echo  '</tr>';
    echo  '</thead>';
 
 while($row = mysqli_fetch_array($result)) {
 //  print "<input class='mx-auto d-block rounded-circle shadow border border-success img-fluid my-2' alt='User Image' type=\"image\" name=\"imageField\" 
 // width=\"140\" height=\"140\" src=\"".$row['image']."\">";
  if ($row['image'] != '' && file_exists('' . $row['image'])) {
      print "<input class='mx-auto d-block rounded-circle shadow border border-2 border-success img-fluid my-2' alt='User Image' type=\"image\" name=\"imageField\" 
 width=\"140\" height=\"140\" src=\"".$row['image']."\">";
  // echo "<img class='rounded rectangle border border-muted' src='" . $row['image'] . "' width='50' height='50'";
  } 
  else {
 //    print "<input class='mx-auto d-block rounded-circle shadow border border-success img-fluid my-2' alt='User Image' type=\"image\" name=\"imageField\" 
 // width=\"140\" height=\"140\" src=\"".image/avatar.png."\">";
  echo "<img class='mx-auto d-block rounded-circle shadow border border-2 border-success img-fluid my-2' src='image/avatar.png' width='140' height='140'>";
 }
    $emp_id=$row['emp_id'];
    $name=$row['name'];   
    $designation=$row['designation'];   
    $division=$row['division'];
    $email=$row['email'];
    $place_of_posting=$row['place_of_posting'];
    $imagePath = " " . $row['image'];
    
 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $emp_id.'</td>';
        echo  '<td>'.  $name.'</td>';        
        echo  '<td>'.  $designation.'</td>';
        echo  '<td>'.  $division.'</td>';
        echo  '<td>'.  $place_of_posting.'</td>';
        echo  '<td>'.  $email.'</td>';   
      echo  '</tr>';
        
  }
 }
 else {
        echo "<p class=' btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
      }
echo  '</tbody>';
echo  '</table>'; 
mysqli_close($conn);
?>
<!-- 
    <span class="px-3">
      <a href="#" class="btn btn-primary text-white">Update Peronal Information</a>
      <a href="#" class="btn btn-primary text-white">Add Children Information</a>
      <a href="#" class="btn btn-primary text-white">View</a>
    </span>
    
      <p class="px-3">
      

    </p> -->
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

  <script src="js/sidebars.js"></script>
  <script>
    var menu_btn = document.querySelector("#menu-btn");
    var sidebar = document.querySelector("#sidebar");
    var container = document.querySelector(".my-container");

    menu_btn.addEventListener("click", () => {
      sidebar.classList.toggle("active-nav");
      container.classList.toggle("active-cont");
    });
  </script>
</body>

</html>