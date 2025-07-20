<?php
session_start();
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: index.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if ($role === 'admin') {
include 'db.php';
// Get the combined pending request counts
$sql="SELECT count(job_status) as job_status_in_service from job_desc where job_status='In Service'";
// $sql = "SELECT
//            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
//            SUM(CASE WHEN job_confirm_status = 'pending' THEN 1 ELSE 0 END) AS pending_count_job_confirm
//         FROM users";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $job_status_in_service = $row['job_status_in_service'];
    // $pendingCountJobConfirm = $row['pending_count_job_confirm'];
} else {
    // Handle the query error
    echo "Error: " . mysqli_error($conn);
}
?>
<?php include('includes/header.php');?>
<div class="container p-0 my-2 border shadow rounded">

    	<h1 class="page-header text-center">Welcome </h1>
    	<div class="row">
		<div class="col-sm-3 "></div>
		<div class="col-sm-6">
		
<h4 class="page-header text-success text-center"><?php echo "Successfully ".$_SESSION['emp_id']. '  Are Logged in!! Now Update Your Information'; ?></h4>


<?php
$sql = "SELECT * FROM users2";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {


    while ($row = mysqli_fetch_assoc($result)) {
        // Access individual columns using $row['column_name']
        $emp_id = $row['emp_id'];
        $name = $row['name'];
        $designation = $row['designation'];
        $department = $row['division'];
        $section = $row['section'];
        $place_of_posting = $row['place_of_posting'];
        $image = $row['image'];

        // Output the information
        echo "<div>";
        echo "<h2>Employee ID: $emp_id</h2>";
        echo "<p>Name: $name</p>";
        echo "<p>Designation: $designation</p>";
        echo "<p>Department: $department</p>";
        echo "<p>Section: $section</p>";
        echo "<p>Place of Posting: $place_of_posting</p>";
        echo "<img src='$image' alt='Employee Image'>";
        echo "</div>";
    }
} else {
    echo "No records found.";
}

mysqli_close($conn);
?>



		</div>
		<div class="col-sm-3 ">			
			<center> <h4><a href="logout.php" class="btn btn-danger"> Logout </a></h4></center>
			<center> <h4><a href="editprofile_1.php"> Edit Profile </a></h4></center>
			<center> <h4><a href="users/view_profile_details.php"> View Profile </a></h4></center>
		</div>
		</div>
</div>
		


<?php include('includes/footer.php');?>