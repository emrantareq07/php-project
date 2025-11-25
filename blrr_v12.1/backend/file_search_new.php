<?php
session_name('blrr');
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

$username     = $_SESSION['username'];
$user_type    = $_SESSION['user_type'];
$office       = $_SESSION['office'];
$office_title = $_SESSION['office_title'];
$officeTitle = "চেয়ারম্যান সচিবালয়";
include('header.php');
include_once '../db/database.php';
date_default_timezone_set("Asia/Dhaka");

$table_name = 'chairmanfile'; // Fixed table (you can make it dynamic if needed)
?>

<div class="container-fluid mt-1 p-2 border rounded shadow-lg">
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2 class="text-muted"><b>ফাইল প্রাপ্তি রেজিস্টার</b></h2>
      <hr class="bg-muted col-sm-6 mx-auto">
    </div>

    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <form action="" method="post" class="needs-validation">
        <div class="form-group row">
          <label for="date1" class="col-form-label col-sm-5">শুরু তারিখ :</label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="date1" name="date1" required>
          </div>
        </div>

        <div class="form-group row mt-2">
          <label for="date2" class="col-form-label col-sm-5">শেষ তারিখ :</label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="date2" name="date2" required>
          </div>
        </div>

        <div class="form-group row mt-2">
          <label for="division_bn" class="col-form-label col-sm-5">বিভাগ অনুযায়ী :</label>
          <div class="col-sm-7">
            <select class="form-select" id="division_bn" name="division_bn">
              <option value="">--Select--</option>
              <?php
              $sql = "SELECT division_bn FROM division WHERE id NOT IN (2,3,4)";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['division_bn']}'>{$row['division_bn']}</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <center>
          <button type="submit" name="submit" class="btn btn-primary mt-3">
            <i class="fa fa-search"></i> Search
          </button>
        </center>
      </form>
    </div>

    <div class="col-sm-3 text-center">
      <a href="addfiledashboard.php" class="btn btn-primary mt-3">
        <i class="fa fa-arrow-left"></i> Back
      </a>
      <hr>
      <button type="button" class="btn btn-danger" id="print">
        <i class="fa fa-print"></i> Print
      </button>
    </div>
  </div>

  <div id="printableArea" class="mt-3">
    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>ক্রমিক নং</th>
          <th>এন্ট্রি তারিখ</th>
          <th>উপস্থাপনকারীর বিভাগ</th>
          <th>ডকেট নং</th>
          <th>স্বাক্ষরের তারিখ</th>
          <th>বিষয়</th>
          <th>গন্তব্য</th>
          <th>মন্তব্য</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($_POST['submit'])) {
          $from_date   = $_POST['date1'];
          $to_date     = $_POST['date2'];
          $division_bn = $_POST['division_bn'] ?? '';

          // Build query
          $query = "SELECT * FROM $table_name WHERE entry_date BETWEEN '$from_date' AND '$to_date'";
          if (!empty($division_bn)) {
            $query .= " AND FIND_IN_SET('$division_bn', destination_dropfile)";
          }       

          $query .= " ORDER BY d_number DESC";
          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?= englishToBanglaNumber($i++) ?></td>
                <td><?= englishToBanglaNumber(date('d-m-Y', strtotime($row['entry_date']))) ?></td>
                <td><?= $row['immediate_sender_office'] ?></td>
                <td><?= englishToBanglaNumber($row['d_number']) ?></td>
                <td><?= englishToBanglaNumber(date('d-m-Y', strtotime($row['sign_date']))) ?></td>
                <td><?= $row['subject'] ?></td>
                <td><?= $row['destination_dropfile'] ?></td>
                <td><?= $row['comments'] ?></td>
              </tr>
              <?php
            }
          } else {
            echo '<tr><td colspan="8" class="text-danger text-center">No records found for the selected date range.</td></tr>';
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$date_range_text = "";
if (isset($_POST['submit'])) {
    $date_range_text =
        "তারিখ: " .
        englishToBanglaNumber(date('d-m-Y', strtotime($_POST['date1']))) .
        " থেকে " .
        englishToBanglaNumber(date('d-m-Y', strtotime($_POST['date2'])));
}
?>

<script>
document.getElementById('print').addEventListener('click', function () {

    var printContents = document.getElementById('printableArea').innerHTML;
    
     function enToBn(num) {
            return num.toString().replace(/[0-9]/g, d => '০১২৩৪৫৬৭৮৯'[d]);
        }

    // Header (PHP variables inserted safely)
    var title = `
        <div style="text-align:center;  margin-top:0px;">
            <h4>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h4>
            <h5>ফাইল প্রাপ্তি রেজিস্টার</h5>
            <p>দপ্তর: <?= $officeTitle ?></p>
            <p><?= $date_range_text ?></p>
        </div>
    `;

    // Footer
    var today = new Date();
    var footer = `
        <div style="text-align:center; margin-top:5px; padding-top:5px; border-top:1px solid #c7c9c8; font-size:11px; color:#666;">
                <small><i class="fa fa-copyright"></i> ${enToBn(today.getFullYear())} BCIC. [--Design & Developed by ICT Division, BCIC.--]</small>
            </div>
    `;

    var originalBody = document.body.innerHTML;

    document.body.innerHTML = title + printContents + footer;

    window.print();

    document.body.innerHTML = originalBody;
    location.reload();
});
</script>


<?php include('footer.php'); ?>
