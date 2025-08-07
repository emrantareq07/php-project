<?php
require_once("../db/db.php");

$alert = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $picture_path = '';

    // Handle file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/students/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid('student_') . '.' . $file_ext;
        $target_file = $upload_dir . $file_name;

        // Validate image
        $check = getimagesize($_FILES['picture']['tmp_name']);
        if ($check !== false && move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
            $picture_path = $target_file;
        }
    }

    // === ADD Student ===
    if (isset($_POST['add'])) {
        $stmt_check = $conn->prepare("SELECT reg_no FROM std_tbl WHERE reg_no = ?");
        $stmt_check->bind_param("s", $_POST['reg_no']);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            $table_name = $conn->real_escape_string($_POST['class']) . '_tbl';
            $current_date = date('Y-m-d');

            $stmt_std = $conn->prepare("INSERT INTO std_tbl (std_name, reg_no, roll_no, class, section, dob, mobile_no, fathers_name, mothers_name, address, status, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_std->bind_param("ssssssssssss", $_POST['std_name'], $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['dob'], $_POST['mobile_no'], $_POST['fathers_name'], $_POST['mothers_name'], $_POST['address'], $_POST['status'], $picture_path);

            $stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);

            if ($stmt_std->execute() && $stmt_play->execute()) {
                $alert = "Student added successfully!";
            } else {
                $alert = "Error adding student!";
            }

            $stmt_std->close();
            $stmt_play->close();
        } else {
            $alert = "Registration number already exists!";
        }
        $stmt_check->close();
    }

    // === UPDATE Student ===
    elseif (isset($_POST['update'])) {
        if ($picture_path && isset($_POST['existing_picture']) && file_exists($_POST['existing_picture'])) {
            unlink($_POST['existing_picture']);
        }
        $final_picture = $picture_path ?: $_POST['existing_picture'];

        $class = $conn->real_escape_string($_POST['class']);
        $table_name = $class . '_tbl';

        $stmt_check = $conn->prepare("SELECT id, reg_no, class FROM std_tbl WHERE reg_no = ?");
        $stmt_check->bind_param("s", $_POST['reg_no']);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($row = $result->fetch_assoc()) {
            $class2 = $conn->real_escape_string($row['class']);
            $table_name2 = $class2 . '_tbl';

            $update_std = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
            $update_std->bind_param("ssssssssssssi", $_POST['std_name'], $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['dob'], $_POST['mobile_no'], $_POST['fathers_name'], $_POST['mothers_name'], $_POST['address'], $_POST['status'], $final_picture, $_POST['id']);
            $update_std->execute();
            $update_std->close();

            if ($row['reg_no'] == $_POST['reg_no'] && $row['class'] == $_POST['class']) {
                $stmt2 = $conn->prepare("UPDATE $table_name SET roll_no=?, section=? WHERE reg_no=?");
                $stmt2->bind_param("sss", $_POST['roll_no'], $_POST['section'], $_POST['reg_no']);
                $stmt2->execute();
                $stmt2->close();
            } else {
                $current_date = date('Y-m-d');
                $stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);
                $stmt_play->execute();
                $stmt_play->close();

                $stmt2 = $conn->prepare("DELETE FROM $table_name2 WHERE reg_no=?");
                $stmt2->bind_param("s", $_POST['reg_no']);
                $stmt2->execute();
                $stmt2->close();
            }

            $alert = "Student updated successfully!";
        }
        $stmt_check->close();
    }

    // === ADMISSION / SHIFT ===
    elseif (isset($_POST['admission'])) {
        $final_picture = $picture_path ?: $_POST['existing_picture'];
        $class = $conn->real_escape_string($_POST['class']);
        $table_name = $class . '_tbl';

        $stmt_check = $conn->prepare("SELECT id, reg_no, class FROM std_tbl WHERE reg_no = ?");
        $stmt_check->bind_param("s", $_POST['reg_no']);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($row = $result->fetch_assoc()) {
            $class2 = $conn->real_escape_string($row['class']);
            $table_name2 = $class2 . '_tbl';

            $stmt = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
            $stmt->bind_param("ssssssssssssi", $_POST['std_name'], $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['dob'], $_POST['mobile_no'], $_POST['fathers_name'], $_POST['mothers_name'], $_POST['address'], $_POST['status'], $final_picture, $_POST['id']);
            $stmt->execute();
            $stmt->close();

            if ($row['class'] === $_POST['class']) {
                $stmt2 = $conn->prepare("UPDATE $table_name SET roll_no=?, section=? WHERE reg_no=?");
                $stmt2->bind_param("sss", $_POST['roll_no'], $_POST['section'], $_POST['reg_no']);
                $stmt2->execute();
                $stmt2->close();
            } else {
                $stmt = $conn->prepare("UPDATE $table_name2 SET status='Inactive' WHERE reg_no=?");
                $stmt->bind_param("s", $_POST['reg_no']);
                $stmt->execute();
                $stmt->close();

                $current_date = date('Y-m-d');
                $stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);
                $stmt_play->execute();
                $stmt_play->close();
            }

            $alert = "Student admission successfully!";
        }

        $stmt_check->close();
    }
}

// === DELETE ===
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM std_tbl WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Student deleted successfully!'); window.location.href='std_reg_list.php';</script>";
    exit();
}

// === Fetch students ===
$students = $conn->query("SELECT * FROM std_tbl");

// Show alert if set
if (!empty($alert)) {
    echo "<script>alert('$alert');</script>";
}

// Fetch unique classes and sections from std_tbl
$classes = [];
$sectionCategories = [];

$classQuery = "SELECT DISTINCT class FROM std_tbl ORDER BY class";
$classResult = mysqli_query($conn, $classQuery);
while ($row = mysqli_fetch_assoc($classResult)) {
    $classes[] = $row;
}

$sectionQuery = "SELECT DISTINCT section FROM std_tbl ORDER BY section";
$sectionResult = mysqli_query($conn, $sectionQuery);
while ($row = mysqli_fetch_assoc($sectionResult)) {
    $sectionCategories[] = $row;
}

// Get selected filters from GET
$selectedclass = isset($_GET['classfilter']) ? $_GET['classfilter'] : '';
$selectedsection = isset($_GET['sectionfilter']) ? $_GET['sectionfilter'] : '';

// Query students based on filters
$filterQuery = "SELECT * FROM std_tbl WHERE 1";
if (!empty($selectedclass)) {
    $filterQuery .= " AND class = '" . mysqli_real_escape_string($conn, $selectedclass) . "'";
}
if (!empty($selectedsection)) {
    $filterQuery .= " AND section = '" . mysqli_real_escape_string($conn, $selectedsection) . "'";
}

$students = mysqli_query($conn, $filterQuery); // ✅ Use this variable in table loop
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .badge-active { background-color: #28a745; }
        .badge-inactive { background-color: #ffc107; color: #212529; }
        .badge-tc { background-color: #dc3545; }
        .action-btn { margin-right: 5px; }
        .profile-pic-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        #imagePreview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #dee2e6;
            display: none;
            margin-bottom: 10px;
        }
        .red-text {
            color: red;
        }

        .red-bg {
          background-color: #97c2a3;  /* light red background */
          border: 1px solid #f5c6cb; /* red border */
          color: #721c24;             /* dark red text */
        }


    </style>
</head>
<body>
    <div class="container-fluid shadow rounded my-2 p-2">
        <h2 class="text-center mb-0 text-uppercase text-muted">Student Registration & Admission</h2>
        
    <!-- Buttons Row - Right Aligned -->
    <div class="d-flex justify-content-end gap-2 my-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentModal">
            <i class="fas fa-plus"></i> Add New Student
        </button>

        <a href="../dashboard.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <a href="logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

        <!-- Students Table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Student List</h3>
            </div>
            <div class="card-body">
               <!-- Filter with Class and Section -->
                <div class="filter-section mb-0 d-flex justify-content-center">
                    <form method="get" action="std_reg_list.php" class="row row-cols-lg-auto g-3 align-items-center">
                        
                        <div class="col-auto">
                            <label for="class" class="form-label">Class:</label>
                            <select name="classfilter" id="classfilter" class="form-select" onchange="this.form.submit()">
                                <option value="">All Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= htmlspecialchars($class['class']) ?>" 
                                        <?= ($selectedclass == $class['class']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['class']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="section" class="form-label">Section:</label>
                            <select name="sectionfilter" id="sectionfilter" class="form-select" onchange="this.form.submit()">
                                <option value="">All Section</option>
                                <?php foreach ($sectionCategories as $sectionCat): ?>
                                    <option value="<?= htmlspecialchars($sectionCat['section']) ?>" 
                                        <?= ($selectedsection == $sectionCat['section']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sectionCat['section']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-auto">
                            <label class="form-label d-block invisible">Reset</label>
                            <a href="std_reg_list.php" class="btn btn-outline-danger">Reset Filters</a>
                        </div>

                    </form>
                </div>


                <table id="studentsTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Reg No</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>DOB</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="<?= $row['picture'] ? $row['picture'] : 'https://via.placeholder.com/50?text=No+Image' ?>" 
                                     class="profile-pic-thumb" alt="Student Photo">
                            </td>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['reg_no'] ?></td>
                            <td><?= $row['std_name'] ?></td>
                            <td><?= $row['roll_no'] ?></td>
                            <td><?= $row['class'] ?></td>
                            <td><?= $row['section'] ?></td>
                            <td><?= date('d/m/Y', strtotime($row['dob'])) ?></td>
                            <td>
                                <span class="badge rounded-pill <?= 
                                    $row['status'] == 'Active' ? 'badge-active' : 
                                    ($row['status'] == 'Inactive' ? 'badge-inactive' : 'badge-tc') 
                                ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary action-btn edit-btn" 
                                    data-id="<?= $row['id'] ?>"
                                    data-std_name="<?= $row['std_name'] ?>"
                                    data-reg_no="<?= $row['reg_no'] ?>"
                                    data-roll_no="<?= $row['roll_no'] ?>"
                                    data-class="<?= $row['class'] ?>"
                                    data-section="<?= $row['section'] ?>"
                                    data-dob="<?= $row['dob'] ?>"
                                    data-mobile_no="<?= $row['mobile_no'] ?>"
                                    data-fathers_name="<?= $row['fathers_name'] ?>"
                                    data-mothers_name="<?= $row['mothers_name'] ?>"
                                    data-address="<?= $row['address'] ?>"
                                    data-status="<?= $row['status'] ?>"
                                    data-picture="<?= $row['picture'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="std_reg.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this student?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                                <a href="std_details.php?reg_no=<?= $row['reg_no'] ?>" class="btn btn-sm btn-info action-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="fee_mgtm_new.php?reg_no=<?= $row['reg_no'] ?>" class="btn btn-sm btn-info action-btn">
                                    <i class="fas fa-eye"></i> Fee
                                </a>

                                <button class="btn btn-sm btn-primary action-btn admission-btn" 
                                    data-id="<?= $row['id'] ?>"
                                    data-std_name="<?= $row['std_name'] ?>"
                                    data-reg_no="<?= $row['reg_no'] ?>"
                                    data-roll_no="<?= $row['roll_no'] ?>"
                                    data-class="<?= $row['class'] ?>"
                                    data-section="<?= $row['section'] ?>"
                                    data-dob="<?= $row['dob'] ?>"
                                    data-mobile_no="<?= $row['mobile_no'] ?>"
                                    data-fathers_name="<?= $row['fathers_name'] ?>"
                                    data-mothers_name="<?= $row['mothers_name'] ?>"
                                    data-address="<?= $row['address'] ?>"
                                    data-status="<?= $row['status'] ?>"
                                    data-picture="<?= $row['picture'] ?>">
                                    <i class="fas fa-edit"></i> Admission
                                </button> 
                                <a href="fee_mgtm_new.php?reg_no=<?= $row['reg_no'] ?>" class="btn btn-sm btn-info action-btn">
                                    <i class="fas fa-list"></i> Result
                                </a>

                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Student Modal (Add/Edit) -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="studentForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="studentId">
                        <input type="hidden" name="existing_picture" id="existingPicture">
                        
                        <div class="row g-3">
                            <!-- Photo Upload -->
                            <div class="col-12">
                                <label class="form-label">Student Photo</label>
                                <img id="imagePreview" src="#" alt="Preview" class="d-block">
                                <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Registration No*</label>
                                <!-- <input type="text" class="form-control" name="reg_no" id="reg_no" required> -->
                                <input class="form-control"
                                type="text" 
                                name="reg_no" 
                                id="reg_no" 
                                placeholder="Enter Registration Number"
                                onblur="checkRegNo()"  
                              
                                oninput="checkRegNo()"  
                            >
                            <span id="regNoStatus" style="color: red;"></span>
                            <script type="text/javascript">
                                function checkRegNo() {
                                const regNo = document.getElementById("reg_no").value;
                                const statusSpan = document.getElementById("regNoStatus");

                                if (regNo === "") {
                                    statusSpan.textContent = "";
                                    return;
                                }

                                // AJAX request to check if reg_no exists
                                const xhr = new XMLHttpRequest();
                                xhr.open("POST", "check_reg_no.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        const response = JSON.parse(xhr.responseText);
                                        if (response.exists) {
                                            statusSpan.textContent = "⚠️ Registration number already exists!";
                                        } else {
                                            statusSpan.textContent = "✅ Available";
                                            statusSpan.style.color = "green";
                                        }
                                    }
                                };
                                
                                xhr.send("reg_no=" + encodeURIComponent(regNo));
                            }
                            </script>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Student Name*</label>
                                <input type="text" class="form-control" name="std_name" id="std_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Roll No*</label>
                                <input type="text" class="form-control" name="roll_no" id="roll_no" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Class*</label>
                                <select class="form-select" name="class" id="class" required>
                                    <option value="">Select Class</option>
                                    <option value="Play">Play</option>
                                    <option value="Nursery">Nursery</option>
                                    <option value="KG">KG</option>
                                    <option value="One">One</option>
                                    <option value="Two">Two</option>
                                    <option value="Three">Three</option>
                                    <option value="Four">Four</option>
                                    <option value="Five">Five</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Section*</label>
                                <select class="form-select" name="section" id="section" required>
                                    <option value="">Select Section</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth*</label>
                                <input type="date" class="form-control" name="dob" id="dob" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile No</label>
                                <input type="text" class="form-control" name="mobile_no" id="mobile_no">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status*</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="TC">TC</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" class="form-control" name="mothers_name" id="mothers_name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add" id="submitBtn">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#studentsTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 2 }, // Name
                    { responsivePriority: 2, targets: 9 }, // Actions
                    { responsivePriority: 3, targets: 1 }  // Reg No
                ]
            });

            // Image preview
            $('#picture').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Admission button click handler
            $('.admission-btn').click(function() {
                const studentId = $(this).data('id');
                $('#studentId').val(studentId);
                // $('#reg_no').val($(this).data('reg_no'));
                $('#reg_no').val($(this).data('reg_no')).prop('readonly', true);
                $('#std_name').val($(this).data('std_name')).prop('readonly', true);
                // $('#roll_no').val($(this).data('roll_no')).addClass('red-text');
                $('#roll_no').val($(this).data('roll_no')).addClass('red-bg');
                $('#class').val($(this).data('class')).addClass('red-bg');
                $('#section').val($(this).data('section')).addClass('red-bg');
                $('#dob').val($(this).data('dob')).prop('readonly', true);
                $('#mobile_no').val($(this).data('mobile_no')).prop('readonly', true);
                $('#fathers_name').val($(this).data('fathers_name')).prop('readonly', true);
                $('#mothers_name').val($(this).data('mothers_name')).prop('readonly', true);
                $('#address').val($(this).data('address')).prop('readonly', true);
                $('#status').val($(this).data('status')).prop('readonly', true);
                
                // Handle existing picture
                const existingPic = $(this).data('picture');
                $('#existingPicture').val(existingPic);
                if (existingPic) {
                    $('#imagePreview').attr('src', existingPic).show();

                    // Disable file input to prevent changes
                    $('#picture').prop('disabled', true);
                } else {
                    $('#imagePreview').hide();

                    // Enable file input if no existing picture
                    $('#picture').prop('disabled', false);
                }

                
                // Change modal title and submit button
                $('#studentModalLabel').text('Admission Student');
                $('#submitBtn').attr('name', 'admission').text('Admission');
                
                // Show modal
                $('#studentModal').modal('show');
            });

            // Edit button click handler
            $('.edit-btn').click(function() {
                const studentId = $(this).data('id');
                $('#studentId').val(studentId);
                $('#reg_no').val($(this).data('reg_no'));
                $('#std_name').val($(this).data('std_name'));
                $('#roll_no').val($(this).data('roll_no'));
                $('#class').val($(this).data('class'));
                $('#section').val($(this).data('section'));
                $('#dob').val($(this).data('dob'));
                $('#mobile_no').val($(this).data('mobile_no'));
                $('#fathers_name').val($(this).data('fathers_name'));
                $('#mothers_name').val($(this).data('mothers_name'));
                $('#address').val($(this).data('address'));
                $('#status').val($(this).data('status'));
                
                // Handle existing picture
                const existingPic = $(this).data('picture');
                $('#existingPicture').val(existingPic);
                if (existingPic) {
                    $('#imagePreview').attr('src', existingPic).show();
                } else {
                    $('#imagePreview').hide();
                }
                
                // Change modal title and submit button
                $('#studentModalLabel').text('Edit Student');
                $('#submitBtn').attr('name', 'update').text('Update Student');
                
                // Show modal
                $('#studentModal').modal('show');
            });

            // Reset form when modal is closed
            $('#studentModal').on('hidden.bs.modal', function () {
                $('#studentForm')[0].reset();
                $('#studentId').val('');
                $('#existingPicture').val('');
                $('#imagePreview').hide().attr('src', '#');
                $('#studentModalLabel').text('Add New Student');
                $('#submitBtn').attr('name', 'add').text('Add Student');
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>