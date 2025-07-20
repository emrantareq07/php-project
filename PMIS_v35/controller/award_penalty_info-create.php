<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../db/db.php');
include_once('../includes/header.php');
$val= $_GET['val'];
// Fetch award and penalty data from the database
$sql = "SELECT * FROM award";
$result = mysqli_query($conn, $sql);

$awardData = [
    'Award' => [],
    'Penalty' => []
];

while ($row = mysqli_fetch_assoc($result)) {
    $status = ($row['auto_id'] == 1) ? 'Award' : 'Penalty';
    $awardData[$status][] = $row['award_or_penalty'];
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>BCIC PMIS</title>
</head>
<body class="bg-light">

    <div class="container mt-2">
        <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg border-primary">
                    <div class="card-header">
                        <h4 class="text-uppercase text-muted "> <b>Add Award / Penalty Information</b>
                            <a href="award_and_penalty_info.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="award_penalty_info-code.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" method="POST">
                            <div class="form-group mb-3">
                                <label for="given_date">Given Date:</label>
                                <input class="form-control" placeholder="" required type="date" name="given_date" id="given_date" value="">
                            </div>

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="Award">Award</option>
                                    <option value="Penalty">Penalty</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nature">Nature:</label>
                                <select class="form-control" id="nature" name="nature" required>
                                    <option value="" disabled selected>--Select--</option>
                                </select>
                                <!-- <input class="form-control" placeholder="" type="text" name="special_increment" id="special_increment"> -->
                            </div>

                             <div class="form-group ">
                                <label for="special_increment">No. of Special Increment:</label>
                                <input class="form-control" placeholder="" type="text" name="special_increment" id="special_increment">
                            </div>

                              <div class="form-group ">
                                <label for="from_date">From Date:</label>
                                <input class="form-control" placeholder="" type="date" name="from_date" id="from_date" >
                            </div>

                              <div class="form-group ">
                                <label for="to_date">To Date:</label>
                                <input class="form-control" placeholder="" type="date" name="to_date" id="to_date" >
                            </div>

                            <div class="form-group mb-3">
                                <label for="ground_or_subject">Ground/Subject:</label>
                                <textarea class="form-control" placeholder="Enter Ground/Subject...." rows="2" id="ground_or_subject"  name="ground_or_subject" ></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="save_student" class="btn btn-primary form-control"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script>
        var awardData = <?php echo json_encode($awardData); ?>;
        var statusDropdown = document.getElementById('status');
        var natureDropdown = document.getElementById('nature');

        statusDropdown.addEventListener('change', function () {
            var status = statusDropdown.value;
            var options = awardData[status] || [];

            // Clear existing options
            natureDropdown.innerHTML = '<option value="" disabled selected>--Select--</option>';

            // Add new options
            options.forEach(function (option) {
                var optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                natureDropdown.appendChild(optionElement);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
