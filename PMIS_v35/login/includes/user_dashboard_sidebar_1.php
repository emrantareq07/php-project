<?php include 'db.php' ;
//session_start();
if (isset($_SESSION['emp_id'])) {
    $emp_id = $_SESSION['emp_id']; // Store the value in a variable for easier access
    $role = $_SESSION['role'];
} else {
    // Redirect to the login page or handle the case when the 'email' session variable is not set
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
#mySidenav a {
  position: absolute;
  left: -200px;
  transition: 0.3s;
  padding: 10px;
  width: 250px;
  text-decoration: none;
  font-size: 16px;
  color: white;
  border-radius: 0 5px 5px 0;
  margin-top: 30px;
  /*margin-bottom: 60px;*/
}

#mySidenav a:hover {
  left: 0;
}

#personal_info {
  top: 70px;
  background-color: #04AA6D;
}
#child_info {
  top: 120px;
  background-color: #5f12db;
}

#edu_info {
  top: 170px;
  background-color: #2196F3;
}

#job_desc {
  top: 220px;
  background-color: #f44336;
}

#awar_penalty {
  top: 270px;
  background-color: #f09b0a
}
#service_history {
  top: 320px;
  background-color: #0badb5
}
#training_info {
  top: 370px;
  background-color: #1d119e
}
#prof_or_mem_info {
  top: 420px;
  background-color: #555
}
/*#promotion_details {
  top: 420px;
  background-color: #8f0b86
}*/
#emp_bank_info {
  top: 470px;
  background-color: #c21750
}
#leave_mgtm {
  top: 520px;
  background-color: #b5a621
}
/*#disciplinary_action {
  top: 520px;
  background-color: #a8a032
}*/
</style>
</head>
<body>

<div id="mySidenav" class="sidenav">
  <!-- <a href="edit_personal_info.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" id="personal_info">Personal Information</a> -->
  <a href="../controller/edit_personal_info.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="personal_info">Personal Information</a>

  <a href="../edit_child_info.php?emp_id=<?php echo $_SESSION['emp_id'] ?>&role=<?php echo $_SESSION['role']?>" id="child_info">Add Children Info.</a>
  
  <a href="../controller/add_edu_info.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="edu_info">Add Educational Information</a>
  <a href="../controller/add_job_desc.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="job_desc">Add Service Information</a>
  <a href="../controller/service_history_details.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="service_history">Add Service History</a>

   <a href="../controller/training_info_details.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="training_info">Add Training Information</a>
  <a href="../controller/membership_info_details.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="prof_or_mem_info">Prof./ Mermbership Information</a>
  <!-- <a href="#" id="promotion_details">Promotion Details</a> -->
  <a href="../controller/add_bank_info.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="emp_bank_info">Emp Bank Information</a>
  <!-- <a href="#" id="disciplinary_action">Disciplinary Action</a> -->
  <a href="../controller/award_and_penalty_info.php?emp_id=<?php echo $_SESSION['emp_id'];?>&val=1" id="awar_penalty">Add Award/Penalty</a>
   <a href="#" id="leave_mgtm">Leave Management</a>
</div>

<!-- <div style="margin-left:80px;">
  <h2>Hoverable Sidenav Buttons</h2>
  <p>Hover over the buttons in the left side navigation to open them.</p>
</div> -->
   
</body>
</html> 
