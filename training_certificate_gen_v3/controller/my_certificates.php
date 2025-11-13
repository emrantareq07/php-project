<?php
// manage_users.php
session_start();
require_once "db.php"; // include your db connection (must use MySQLi)

// Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php?msg=deleted");
    exit;
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Get email from URL (if passed)
$email = $_GET['email'] ?? '';

// For extra security, compare with session email
if ($email !== $_SESSION['user_email']) {
    die("Invalid request.");
}

// Fetch Users
$result = $conn->query("
    SELECT u.id,u.name,u.designation,u.place_of_posting,a.training_title,a.start_date,a.end_date
    FROM users_tbl u
    INNER JOIN authority_tbl a ON u.batch = a.batch
    WHERE u.email_id = '$email'
    ORDER BY u.id DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet
  ">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-2 shadow rounded p-3">
<h3 class="mb-3 text-uppercase text-muted">
    My All Certificates 
    <a href="dashboard.php" class="btn btn-secondary float-end ms-2">
        <i class="fa fa-arrow-left"></i> Back
    </a>
    <a href="logout.php" class="btn btn-outline-danger float-end ms-2">
        Logout
    </a>
</h3>

  
  <?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted"): ?>
    <div class="alert alert-success">User deleted successfully.</div>
  <?php endif; ?>
  
  <table id="userTable" class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <!-- <th>ID</th>
        <th>Emp ID</th> -->
        <th>Name</th>
        <th>Designation</th>
        <!-- <th>Division</th>
        <th>Section</th> -->
        <th>Place of Posting</th>
        <!-- <th>Office</th> -->
        <!-- <th>Mobile</th>
        <th>Email</th> -->
         <th>Training Title</th>
        <th>Duration</th>
        
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <!-- <td><?= $row['id']; ?></td> -->
        <!-- <td><?= htmlspecialchars($row['emp_id']); ?></td> -->
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['designation']); ?></td>
      <!--   <td><?= htmlspecialchars($row['division']); ?></td>
        <td><?= htmlspecialchars($row['section']); ?></td> -->
        <td><?= htmlspecialchars($row['place_of_posting']); ?></td>
    <!--     <td><?= htmlspecialchars($row['office']); ?></td>
        <td><?= htmlspecialchars($row['mobile_no']); ?></td>
        <td><?= htmlspecialchars($row['email_id']); ?></td> -->
         <td><?= htmlspecialchars($row['training_title']); ?></td>
       <td><?= htmlspecialchars($row['start_date'] . " to " . $row['end_date']); ?></td>

        
    </td>

        <td>
             <a href="user_certificate_pdf.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View</a>
          
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<div class="card-footer mb-4 my-2"><h6 class="float-end text-muted">Design & Developed By ICT Division, ICT.</h6></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
  $('#userTable').DataTable();
});
</script>

</body>
</html>
