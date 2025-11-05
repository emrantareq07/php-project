<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

// ✅ Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS candidates_tbl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50),
    name VARCHAR(255),
    fathers_name VARCHAR(255),
    mothers_name VARCHAR(255),
    district VARCHAR(100),
    dob DATE,
    ssc VARCHAR(255),
    hsc VARCHAR(255),
    honors VARCHAR(255),
    masters VARCHAR(255),
    designation VARCHAR(150),
    written_marks FLOAT,
    viva_marks FLOAT,
    status VARCHAR(50),
    remarks TEXT,
    image VARCHAR(255),
    committe_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// ✅ Save or update candidates
if (isset($_POST['save_all'])) {
    foreach ($_POST['roll_no'] as $i => $roll_no) {
        $candidate_id = $_POST['candidate_id'][$i];
        $name = $_POST['name'][$i];
        $fathers_name = $_POST['fathers_name'][$i];
        $mothers_name = $_POST['mothers_name'][$i];
        $district = $_POST['district'][$i];
        $dob = $_POST['dob'][$i];
        $ssc = $_POST['ssc'][$i];
        $hsc = $_POST['hsc'][$i];
        $honors = $_POST['honors'][$i];
        $masters = $_POST['masters'][$i];
        $designation = $_POST['designation'][$i];
        $written_marks = $_POST['written_marks'][$i];
        $viva_marks = $_POST['viva_marks'][$i];
        $status = $_POST['status'][$i];
        $remarks = $_POST['remarks'][$i];
        $committe_name = $_POST['committe_name'][$i];

        // ✅ Handle image upload
        $image = '';
        if (!empty($_FILES['image']['name'][$i])) {
            $targetDir = "uploads/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $image = $targetDir . basename($_FILES["image"]["name"][$i]);
            move_uploaded_file($_FILES["image"]["tmp_name"][$i], $image);
        }

        // ✅ Update or Insert logic
        if (!empty($candidate_id)) {
            // Update existing
            $sql = "UPDATE candidates_tbl SET 
                roll_no=?, name=?, fathers_name=?, mothers_name=?, district=?, dob=?, 
                ssc=?, hsc=?, honors=?, masters=?, designation=?, written_marks=?, viva_marks=?, 
                status=?, remarks=?, committe_name=?".($image ? ", image=? " : " ")."WHERE id=?";
            $stmt = $conn->prepare($sql);

            if ($image) {
                $stmt->bind_param("sssssssssssssssssii", $roll_no, $name, $fathers_name, $mothers_name, $district, $dob,
                    $ssc, $hsc, $honors, $masters, $designation, $written_marks, $viva_marks, 
                    $status, $remarks, $committe_name, $image, $candidate_id);
            } else {
                $stmt->bind_param("ssssssssssssssssi", $roll_no, $name, $fathers_name, $mothers_name, $district, $dob,
                    $ssc, $hsc, $honors, $masters, $designation, $written_marks, $viva_marks, 
                    $status, $remarks, $committe_name, $candidate_id);
            }
            $stmt->execute();
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO candidates_tbl 
                (roll_no, name, fathers_name, mothers_name, district, dob, ssc, hsc, honors, masters, designation, written_marks, viva_marks, status, remarks, committe_name, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssssssss", $roll_no, $name, $fathers_name, $mothers_name, $district, $dob, 
                $ssc, $hsc, $honors, $masters, $designation, $written_marks, $viva_marks, 
                $status, $remarks, $committe_name, $image);
            $stmt->execute();
        }
    }

    echo "<script>alert('Candidates saved successfully!');window.location='candidates_details.php';</script>";
    exit;
}

// ✅ Delete candidate
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM candidates_tbl WHERE id=$id");
    echo "<script>alert('Candidate deleted successfully!');window.location='candidates_details.php';</script>";
    exit;
}

// ✅ Fetch all candidates
$result = $conn->query("SELECT * FROM candidates_tbl ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Candidate Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
<h3 class="text-center mb-4 bg-primary text-white p-2 rounded">Candidate Entry Form</h3>

<form method="POST" enctype="multipart/form-data" class="card p-3 mb-4" id="candidateForm">
    <div id="candidateRows">
        <div class="candidate-row border p-3 mb-3 rounded bg-white">
            <input type="hidden" name="candidate_id[]" value="">
            <div class="row g-3">
                <div class="col-md-2"><input type="text" name="roll_no[]" class="form-control" placeholder="Roll No" required></div>
                <div class="col-md-2"><input type="text" name="name[]" class="form-control" placeholder="Name" required></div>
                <div class="col-md-2"><input type="text" name="fathers_name[]" class="form-control" placeholder="Father's Name"></div>
                <div class="col-md-2"><input type="text" name="mothers_name[]" class="form-control" placeholder="Mother's Name"></div>
                <div class="col-md-2"><input type="text" name="district[]" class="form-control" placeholder="District"></div>
                <div class="col-md-2"><input type="date" name="dob[]" class="form-control"></div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3"><input type="text" name="ssc[]" class="form-control" placeholder="SSC"></div>
                <div class="col-md-3"><input type="text" name="hsc[]" class="form-control" placeholder="HSC"></div>
                <div class="col-md-3"><input type="text" name="honors[]" class="form-control" placeholder="Honors"></div>
                <div class="col-md-3"><input type="text" name="masters[]" class="form-control" placeholder="Masters"></div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3"><input type="text" name="designation[]" class="form-control" placeholder="Designation"></div>
                <div class="col-md-2"><input type="number" name="written_marks[]" class="form-control" placeholder="Written"></div>
                <div class="col-md-2"><input type="number" name="viva_marks[]" class="form-control" placeholder="Viva"></div>
                <div class="col-md-2">
                    <select name="status[]" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Passed">Passed</option>
                        <option value="Failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-2"><input type="text" name="remarks[]" class="form-control" placeholder="Remarks"></div>
                <div class="col-md-3">
                    <select name="committe_name[]" class="form-select">
                        <option value="">Select Committee</option>
                        <?php 
                        $sql_com = "SELECT DISTINCT committe_name FROM committee_tbl";
                        $result_committe = mysqli_query($conn, $sql_com);
                        if ($result_committe && mysqli_num_rows($result_committe) > 0) {
                            while ($row_committe = mysqli_fetch_assoc($result_committe)) {
                                $committe_name = htmlspecialchars($row_committe['committe_name']);
                                echo "<option value=\"$committe_name\">$committe_name</option>";
                            }
                        } else {
                            echo "<option disabled>No committees found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2"><input type="file" name="image[]" class="form-control"></div>
            </div>

            <div class="text-end mt-2">
                <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <button type="button" id="addCandidate" class="btn btn-outline-primary">+ Add Candidate</button>
        <button type="submit" name="save_all" class="btn btn-success">Save All</button>
        <a href="admin_dashboard.php" class="btn btn-primary">Back</a>
    </div>
</form>

<!-- ✅ Candidate List Table -->
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Roll No</th>
<th>Name</th>
<th>District</th>
<th>Designation</th>
<th>Viva Committee</th>
<th>Status</th>
<th>Image</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['roll_no']) ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['district']) ?></td>
<td><?= htmlspecialchars($row['designation']) ?></td>
<td><?= htmlspecialchars($row['committe_name']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td><?php if($row['image']): ?><img src="<?= $row['image'] ?>" width="40"><?php endif; ?></td>
<td>
    <button class="btn btn-sm btn-info editCandidate"
        data-id="<?= $row['id'] ?>"
        data-roll="<?= htmlspecialchars($row['roll_no']) ?>"
        data-name="<?= htmlspecialchars($row['name']) ?>"
        data-father="<?= htmlspecialchars($row['fathers_name']) ?>"
        data-mother="<?= htmlspecialchars($row['mothers_name']) ?>"
        data-district="<?= htmlspecialchars($row['district']) ?>"
        data-dob="<?= $row['dob'] ?>"
        data-ssc="<?= htmlspecialchars($row['ssc']) ?>"
        data-hsc="<?= htmlspecialchars($row['hsc']) ?>"
        data-honors="<?= htmlspecialchars($row['honors']) ?>"
        data-masters="<?= htmlspecialchars($row['masters']) ?>"
        data-designation="<?= htmlspecialchars($row['designation']) ?>"
        data-committe_name="<?= htmlspecialchars($row['committe_name']) ?>"
        data-written="<?= $row['written_marks'] ?>"
        data-viva="<?= $row['viva_marks'] ?>"
        data-status="<?= $row['status'] ?>"
        data-remarks="<?= htmlspecialchars($row['remarks']) ?>"
    >Edit</button>
    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this candidate?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<script>
$('#addCandidate').click(function(){
    let row = $('.candidate-row:first').clone();
    row.find('input').val('');
    row.find('select').val('Pending');
    row.find('input[name="candidate_id[]"]').val('');
    $('#candidateRows').append(row);
});

$(document).on('click', '.removeRow', function(){
    if ($('.candidate-row').length > 1) {
        $(this).closest('.candidate-row').remove();
    } else {
        alert("At least one candidate must remain!");
    }
});

// ✅ Edit candidate
$(document).on('click', '.editCandidate', function(){
    let row = $('.candidate-row:first').clone();
    row.find('input, select').val(''); // reset
    row.find('input[name="candidate_id[]"]').val($(this).data('id'));
    row.find('input[name="roll_no[]"]').val($(this).data('roll'));
    row.find('input[name="name[]"]').val($(this).data('name'));
    row.find('input[name="fathers_name[]"]').val($(this).data('father'));
    row.find('input[name="mothers_name[]"]').val($(this).data('mother'));
    row.find('input[name="district[]"]').val($(this).data('district'));
    row.find('input[name="dob[]"]').val($(this).data('dob'));
    row.find('input[name="ssc[]"]').val($(this).data('ssc'));
    row.find('input[name="hsc[]"]').val($(this).data('hsc'));
    row.find('input[name="honors[]"]').val($(this).data('honors'));
    row.find('input[name="masters[]"]').val($(this).data('masters'));
    row.find('input[name="designation[]"]').val($(this).data('designation'));
    row.find('select[name="committe_name[]"]').val($(this).data('committe_name'));
    row.find('input[name="written_marks[]"]').val($(this).data('written'));
    row.find('input[name="viva_marks[]"]').val($(this).data('viva'));
    row.find('select[name="status[]"]').val($(this).data('status'));
    row.find('input[name="remarks[]"]').val($(this).data('remarks'));
    
    $('#candidateRows').empty().append(row);
    window.scrollTo({top:0, behavior:'smooth'});
});
</script>
</body>
</html>
