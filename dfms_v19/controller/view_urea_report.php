<?php
session_name('dfms');
session_start();
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../include/header.php');
include('../db/db.php');
?>
<style>
  .table-responsive {
    max-height: 650px; /* Adjust height for scrolling */
    overflow-y: auto;
  }
  .table thead th {
    position: sticky;
    top: 0;
    background-color: #343a40; /* Dark header */
    color: #fff;
    z-index: 2;
  }
</style>

<div class="container my-3 border shadow rounded p-3"> 
  <!-- Header row -->
  <div class="row align-items-center mb-3">
    <div class="col-lg-3 col-md-12 text-center text-lg-start mb-2 mb-lg-0"></div>
    <div class="col-lg-6 col-md-12 text-center">
      <h2 class="text-uppercase text-muted mb-0">
        DFMS <b class="text-success">[--<?php echo $table ?>--]</b>
      </h2>
    </div>
    <div class="col-lg-3 col-md-12 text-center text-lg-end mt-2 mt-lg-0">
      <a href="urea_form.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" 
         class="btn btn-primary me-2 mb-2 mb-lg-0">
        <i class="fa fa-arrow-left"></i> Previous Page
      </a>
      <a class="btn btn-danger mb-2 mb-lg-0" href="logout.php">
        <i class="fa fa-sign-out"></i> Logout
      </a>
    </div>
  </div>

  <!-- Table row -->
  <div class="row">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Daily (MT)</th>
              <th>Product</th>
              <th>Plant Load (%)</th>
              <th>Remarks</th>
              <?php if ($user_type == 'admin' || $user_type == 'sadmin') { ?>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_SESSION['username'])) {
              $query = "SELECT * FROM $table ORDER BY id DESC";
            }
            $query_run = mysqli_query($conn, $query);

            if (mysqli_num_rows($query_run) > 0) {
              foreach ($query_run as $row) {
                ?>
                <tr>
                  <td><?= htmlspecialchars($row['date']); ?></td>
                  <td><?= round((float)$row['daily'], 2); ?></td>
                  <td><?= htmlspecialchars($row['product_produce']); ?></td>
                  <td><?= htmlspecialchars($row['plant_load']); ?></td>
                  <td class="p-2 mb-0"><?= htmlspecialchars($row['remarks']); ?></td>
                  <?php if ($user_type == 'admin' || $user_type == 'sadmin') { ?>
                    <td>
                      <a href="edit_urea.php?id=<?= $row['id'] ?>" 
                         class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                      </a>
                    </td>
                  <?php } ?>
                </tr>
                <?php
              }
            } else {
              echo "<tr><td colspan='6' class='text-danger'><b>No Record Found !!!</b></td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>
