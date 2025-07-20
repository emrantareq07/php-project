<!-- promotion_info-create.php -->
<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('db/db.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>BCIC PMIS</title>
</head>
<body class="bg-light">
  
    <div class="container mt-2">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg border-primary">
                    <div class="card-header">
                        <h4 class="text-uppercase text-muted"> Add Promotion Particulars
                            <a href="view_promotion_info.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="promotion_info-code.php" method="POST">

                            <div class="form-group mb-3">
                                <label for="ref_no">Ref. No.:</label>
                                <input class="form-control" placeholder="Enter Ref. No." type="text" name="ref_no" id="ref_no" value="">
                            </div>

                            <div class="form-group mb-3">
                                <label for="designation" class="form-label"> Designation:</label>
                                <select class="form-control" id="designation" name="designation" required tabindex="">
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $sql = "SELECT * FROM designation";
                                    $result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="place_of_posting">Place of Posting:</label>
                                <select class="form-control" id="place_of_posting" name="place_of_posting">
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="BCIC H.O">BCIC H.O</option>
                                    <option value="SFCL">SFCL</option>
                                    <option value="JFCL">JFCL</option>
                                    <option value="CUFL">CUFL</option>
                                    <option value="AFCCL">AFCCL</option>
                                    <option value="GPFPLC">GPFPLC</option>
                                    <option value="CCCL">CCCL</option>
                                    <option value="BISFL">BISFL</option>
                                    <option value="GPUFP">GPUFP</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="date_of_promot">Promotion Date:</label>
                                <input class="form-control" placeholder="" type="date" name="date_of_promot" id="date_of_promot" value="">
                            </div>

                            <div class="form-group mb-3">
                                <label for="granted_promo_date">Granted Date:</label>
                                <input class="form-control" placeholder="" type="date" name="granted_promo_date" id="granted_promo_date" value="">
                            </div>

                            <div class="form-group mb-3">
                                <label for="nature_of_promo">Nature of Promotion:</label>
                                <select class="form-control" id="nature_of_promo" name="nature_of_promo">
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="Selection Grade">Selection Grade</option>
                                    <option value="Senior Scale">Senior Scale</option>
                                    <option value="Regular">Regular</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="scale" class="form-label"> Pay Scale:</label>
                                <select class="form-control" id="scale" name="scale" required tabindex="">
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $sql = "SELECT * FROM pay_scale_2015";
                                    $result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<option value='".$row['scale']."'>".$row['scale']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" name="save_student" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
