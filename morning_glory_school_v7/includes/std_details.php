<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "morning_glory_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student details based on registration number
$reg_no = $_GET['reg_no'] ?? '';
$student = null;

if ($reg_no) {
    $stmt = $conn->prepare("SELECT * FROM std_tbl WHERE reg_no = ?");
    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

if (!$student) {
    die("Student not found!");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - <?= $student['std_name'] ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .detail-card .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .badge-active { background-color: #28a745; }
        .badge-inactive { background-color: #ffc107; color: #212529; }
        .badge-tc { background-color: #dc3545; }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Back button -->
        <a href="std_reg_list.php" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left"></i> Back to Student List
        </a>
        
        <!-- Student Profile Header -->
        <div class="profile-header text-center">
            <div class="d-flex justify-content-center mb-3">
                <img src="<?= $student['picture'] ? $student['picture'] : 'https://via.placeholder.com/150?text=No+Image' ?>" 
                     alt="Student Picture" class="profile-picture">
            </div>
            <h2><?= $student['std_name'] ?></h2>
            <p class="lead"><?= $student['class'] ?> - Section <?= $student['section'] ?></p>
            <span class="badge rounded-pill <?= 
                $student['status'] == 'Active' ? 'badge-active' : 
                ($student['status'] == 'Inactive' ? 'badge-inactive' : 'badge-tc') 
            ?>">
                <?= $student['status'] ?>
            </span>
        </div>

        <!-- Student Details -->
        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Registration No</div>
                            <div class="col-md-8"><?= $student['reg_no'] ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Roll No</div>
                            <div class="col-md-8"><?= $student['roll_no'] ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Class</div>
                            <div class="col-md-8"><?= $student['class'] ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Section</div>
                            <div class="col-md-8"><?= $student['section'] ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Date of Birth</div>
                            <div class="col-md-8"><?= date('d/m/Y', strtotime($student['dob'])) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 detail-label">Mobile No</div>
                            <div class="col-md-8"><?= $student['mobile_no'] ? $student['mobile_no'] : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="col-md-6">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Family Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Father's Name</div>
                            <div class="col-md-8"><?= $student['fathers_name'] ? $student['fathers_name'] : 'N/A' ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Mother's Name</div>
                            <div class="col-md-8"><?= $student['mothers_name'] ? $student['mothers_name'] : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="col-12">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-home"></i> Address</h5>
                    </div>
                    <div class="card-body">
                        <p><?= $student['address'] ? nl2br($student['address']) : 'N/A' ?></p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="col-md-6">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Registration Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 detail-label">Registration Date</div>
                            <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($student['created_at'])) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 detail-label">Last Updated</div>
                            <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($student['updated_at'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end mt-4">
            <a href="std_reg.php?edit=<?= $student['id'] ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
            <a href="std_reg.php?delete=<?= $student['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">
                <i class="fas fa-trash-alt"></i> Delete
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>