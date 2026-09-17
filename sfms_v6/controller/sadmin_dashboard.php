<?php
session_start();

$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office_type = $_SESSION['office_type'];

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

include('../db/db.php');
include('../include/header.php');

if ($user_type == 'sadmin') {
    include('../include/topbar_sadmin.php');
} else {
    include('../include/topbar.php');
}
?>

<style>
body{
    background:#ffffff;
}

/* Welcome Section */
.welcome-card{
    background:linear-gradient(135deg,#198754,#28a745);
    color:#fff;
    border-radius:15px;
    padding:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

/* Dashboard Cards */
.dashboard-card{
    background:#fff;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.10);
    border:1px solid #eee;
    height:100%;
}

/* User Info */
.info-box{
    background:#f8f9fa;
    border-left:5px solid #dc3545;
    padding:15px;
    border-radius:10px;
}

/* Buttons */
.custom-btn{
    width:100%;
    margin-bottom:12px;
    padding:12px;
    border-radius:10px;
    font-weight:600;
    transition:all .3s ease;
}

.custom-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(0,0,0,.2);
}

.btn-green{
    background:#198754;
    color:#fff !important;
    border:none;
}

.btn-green:hover{
    background:#157347;
}

.btn-red{
    background:#dc3545;
    color:#fff !important;
    border:none;
}

.btn-red:hover{
    background:#bb2d3b;
}

.icon-circle{
    width:90px;
    height:90px;
    background:#f8f9fa;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto;
    color:#dc3545;
}

.section-title{
    color:#198754;
    font-weight:bold;
    margin-bottom:20px;
}

a{
    text-decoration:none !important;
}
</style>

<div class="container mt-5">

    <!-- Welcome -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="welcome-card text-center">
                <h2>
                    Welcome
                    <strong><?= $_SESSION['username']; ?></strong>
                    Dashboard
                </h2>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- LEFT PANEL -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card">

                <h4 class="section-title">
                    <i class="fa fa-user"></i>
                    User Information
                </h4>

                <div class="info-box mb-3">
                    <strong>User Type:</strong>
                    <?= $user_type; ?>
                    <br>

                    <strong>Office Type:</strong>
                    <?= $office_type; ?>
                </div>

                <?php if($user_type == 'sadmin'){ ?>

<a href="manage_user.php?usernameSSION['username']; ?> "  class="btn btn-warning custom-btn">
    <i class="fa fa-users"></i>
    Manage User
</a>

<a href="office_manage.php"
   class="btn btn-success custom-btn">
<i class="fa fa-building"></i>  Office Manage</a>

<?php } ?>

            </div>
        </div>

        <!-- CENTER PANEL -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card text-center">

                <div class="icon-circle">
                    <i class="fa fa-dashboard fa-3x"></i>
                </div>

                <br>

                <h3 class="text-success">
                    Dashboard Control Panel
                </h3>

                <hr>

                <p class="text-muted">
                    Manage reports, pipeline data, yearly targets
                    and database backup from one place.
                </p>

            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card">

                <h4 class="section-title">
                    <i class="fa fa-cogs"></i>
                    Quick Actions
                </h4>

                <a href="pipeline.php?val=kaliganj_buffer"
                   class="btn btn-green custom-btn">
                    <i class="fa fa-database"></i>
                    Pipeline Data
                </a>

                urea_report_with_date_range.php?username=<?= $_SESSION['username']; ?>                   class="btn btn-red custom-btn">
                    <i class="fa fa-file-pdf-o"></i>
                    Print Report (Date Range)
                </a>

                show_all_urea.php?username=<?= $_SESSION['username']; ?>                   class="btn btn-green custom-btn">
                    <i class="fa fa-list"></i>
                    Show All
                </a>

                yearly_target_set.php?username=<?= $_SESSION['username']; ?>                   class="btn btn-red custom-btn">
                    <i class="fa fa-bullseye"></i>
                    Yearly Target Set
                </a>

                <hr>

                dawnload_database.php
                    <button class="btn btn-outline-danger custom-btn"
                            type="submit"
                            name="submit">
                        <i class="fa fa-download"></i>
                        Download Database
                    </button>
                </form>

                <a href="logout.php"
                   class="btn btn-danger custom-btn">
                    <i class="fa fa-sign-out"></i>
                    Logout
                </a>

            </div>
        </div>

    </div>

</div>

<?php
include('../include/footer.php');
?>