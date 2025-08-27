<?php
session_name('dfms');
session_start();
ob_start(); // Start output buffering

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

if(isset($_GET['val'])){
$table = $_GET['val']; 
$user_type = $_GET['user_type'];
$_SESSION['username']=$table ;
$_SESSION['user_type']=$user_type;
}
else {
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
}

if ($user_type == 'sadmin') {
  include('../include/topbar.php'); 
  include('../include/header_sadmin.php');  
} else {
  include('../include/header.php');
  include('../include/topbar_user.php');
}
?>

<div class="container mt-4">
  <div class="row g-3">
    <!-- Left Column Alerts -->
    <div class="col-lg-3 col-md-4 col-12">
      <?php
      if(isset($_GET['past_dated']) && $_GET['past_dated'] === "Back date error") {
          $_SESSION['back_date_error'] = true;
          header("Location: urea_form.php");
          exit();
      }
      if(isset($_GET['advance_dated']) && $_GET['advance_dated'] === "Advanced date error") {
          $_SESSION['advance_date_error'] = true;
          header("Location: urea_form.php");
          exit();
      }
      if(isset($_GET['already_exists']) && $_GET['already_exists'] === "already_exists") {
          $_SESSION['already_exists'] = true;
          header("Location: urea_form.php");
          exit();
      }
      if(isset($_GET['submitted']) && $_GET['submitted'] === "successfully") {
          $_SESSION['submitted'] = true;
          header("Location: urea_form.php");
          exit();
      }

      if(isset($_SESSION['advance_date_error'])) {
          echo '<div class="alert alert-danger">Date is Advance!!!</div>';
          unset($_SESSION['advance_date_error']);
      } elseif(isset($_SESSION['back_date_error'])) {
          echo '<div class="alert alert-danger">Date is Back2024!!!</div>';
          unset($_SESSION['back_date_error']);
      } elseif(isset($_SESSION['already_exists'])) {
          echo '<div class="alert alert-danger">Date Already Exists!!!</div>';
          unset($_SESSION['already_exists']);
      } elseif(isset($_SESSION['submitted'])) {
          echo '<div class="alert alert-success">Data Inserted Successfully!!!</div>';
          unset($_SESSION['submitted']);
      }
      ?>
    </div>

    <!-- Center Column Form -->
    <div class="col-lg-6 col-md-8 col-12">
      <div class="border shadow rounded p-3 border-success">
        <form action="insert_urea.php" method="POST">       
          <div class="form-group mb-3">
            <label for="date">Choose a Date</label>
            <input type="date" class="form-control" name="date" id="date" 
              onChange="checkAvailability()" required 
              value="<?php echo date('Y-m-d', strtotime('-1 day'));?>">
            <span id="user-availability-status"></span>
            <p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>
          </div>

          <div class="form-group mb-3">
            <label for="product_produce">Product</label>    
            <select class="form-control" name="product_produce" id="product_produce">
              <?php
              if ($table == "sfcl" || $table == "jfcl" || $table == "afccl" || $table == "gpfplc" || $table == "cufl") {
                  echo '<option value="Urea">Urea</option>';
              } elseif ($table == "tspcl") {
                  echo '<option value="TSP">TSP</option>';
              } elseif ($table == "dapfcl") {
                  echo '<option value="DAP">DAP</option>';
              } elseif ($table == "kpml") {
                  echo '<option value="Paper">Paper</option>';
              } elseif ($table == "cccl") {
                  echo '<option value="Cement">Cement</option>';
              } elseif ($table == "ugsf") {
                  echo '<option value="Sheet Glass">Sheet Glass</option>';
              } else {
                  echo '<option value="sanitary">Sanitary</option>';
                  echo '<option value="insulator">Insulator</option>';
                  echo '<option value="refractories">Refractories</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group mb-3">
            <label for="daily">Daily Production (MT)</label>
            <input type="text" class="form-control" name="daily" id="daily">
          </div>

          <div class="form-group mb-3">
            <label for="plant_load">Plant Load (%)</label>
            <input type="text" class="form-control" name="plant_load" id="plant_load">
          </div>

          <div class="form-group mb-3">
            <label for="remarks">Remarks</label>
            <textarea class="form-control" rows="3" name="remarks" id="remarks"></textarea>
          </div>

          <div class="d-grid">
            <button type="submit" name="submit_form" class="btn btn-primary">
              <i class="fa fa-save me-1"></i> Insert
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Column Buttons -->
    <div class="col-lg-3 col-12">
      <div class="d-flex flex-column gap-3 mt-3 mt-lg-0">
        <?php if($user_type=='sadmin'){ ?>
          <a href="view_urea_report.php?username=<?=urlencode($_SESSION['username'])?>&user_type=<?=urlencode($_SESSION['user_type'])?>" class="btn btn-warning w-100">
            <i class="fa fa-edit me-2"></i> Edit Record
          </a>
          <a href="manage_user.php?username=<?=urlencode($_SESSION['username'])?>&user_type=<?=urlencode($_SESSION['user_type'])?>" class="btn btn-warning w-100">
            <i class="fa fa-users-cog me-2"></i> Manage Users
          </a>
          <a href="set_name.php" class="btn btn-info w-100">
            <i class="fa fa-user-tie me-2"></i> Set Head Name
          </a>
        <?php } elseif ($user_type=='admin') { ?>
          <a href="home.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-primary w-100">
            <i class="fa fa-arrow-left"></i> Previous Page
          </a>
          <a href="view_urea_report.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-warning w-100">
            <i class="fa fa-edit"></i> Edit Record
          </a>
        <?php } else { ?>
          <a href="view_urea_report.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-warning w-100">
            <i class="fa fa-eye"></i> View Record
          </a>
        <?php } ?>

        <a href="urea_report_with_date_range.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-danger w-100">
          <i class="fa fa-print"></i> Print Report
        </a>
        <a href="yearly_target_set.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-primary w-100">
          <i class="fa fa-calendar"></i> Yearly Target
        </a>
        <a href="monthly_target.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" class="btn btn-primary w-100">
          <i class="fa fa-calendar"></i> Monthly Target
        </a>
        <a href="logout.php" class="btn btn-danger w-100">
          <i class="fa fa-sign-out"></i> Logout
        </a>
        <button onclick="cleanReload()" class="btn btn-secondary w-100">
          <i class="fas fa-sync-alt me-2"></i> Refresh
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function cleanReload() {
  const url = new URL(window.location.href);
  ['username', 'user_type'].forEach(param => url.searchParams.delete(param));
  window.location.replace(url.toString());
}
</script>

<script type="text/javascript">     
function checkAvailability() {
    var table1 = "<?php echo $table; ?>";
    $("#loaderIcon").show();
    $.ajax({
        url: "check_avail.php",
        data: { 
            date: $("#date").val(),
            table1: table1
        },
        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        }
    });
}
</script>

<script>    
(function($) {
  $.fn.inputFilter = function(callback, errMsg) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
      if (callback(this.value)) {
        if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
          $(this).removeClass("input-error");
          this.setCustomValidity("");
        }
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        $(this).addClass("input-error");
        this.setCustomValidity(errMsg);
        this.reportValidity();
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

$("#daily").inputFilter(function(value) {
  return /^-?\d*$/.test(value); }, "Must be an integer");
</script>   

<?php include('../include/footbar_user.php'); ob_end_flush(); ?>
