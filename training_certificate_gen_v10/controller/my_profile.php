<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php"; // include your db connection (must use MySQLi)

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

$email_id = $_SESSION['user_email'];

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("
    SELECT * FROM users_tbl 
    WHERE email_id = ? 
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->bind_param("s", $email_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// if (!$user) {
//     header('Location: ../index.php');
//     exit();
// }

// Handle profile update
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $mobile_no = $_POST['mobile_no'] ?? '';
    $email_id = $_POST['email_id'] ?? ''; // FIXED
    $designation = $_POST['designation'] ?? '';
    $division = $_POST['division'] ?? '';
    $section = $_POST['section'] ?? '';
    $place_of_posting = $_POST['place_of_posting'] ?? '';
    $office = $_POST['office'] ?? '';

    // Validate email
    if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {

        // ✅ Update ONLY latest row (based on ID)
        $update_stmt = $conn->prepare("
            UPDATE users_tbl 
            SET name = ?, mobile_no = ?, email_id = ?, designation = ?, 
                division = ?, section = ?, place_of_posting = ?, office = ?, updated_at = NOW()
            WHERE id = (
                SELECT id FROM (
                    SELECT id FROM users_tbl 
                    WHERE email_id = ? 
                    ORDER BY id DESC 
                    LIMIT 1
                ) AS temp
            )
        ");

        $update_stmt->bind_param("sssssssss", 
            $name, $mobile_no, $email_id, $designation, 
            $division, $section, $place_of_posting, $office, $email_id
        );

        if ($update_stmt->execute()) {
            $success = 'Profile updated successfully!';

            // ✅ Get updated latest row
            $stmt = $conn->prepare("
                SELECT * FROM users_tbl 
                WHERE email_id = ? 
                ORDER BY id DESC 
                LIMIT 1
            ");
            $stmt->bind_param("s", $email_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - Employee Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --white: #ffffff;
            --green: #28a745;
            --green-dark: #1e7e34;
            --blue: #007bff;
            --blue-dark: #0056b3;
            --yellow: #ffc107;
            --yellow-dark: #e0a800;
            --red: #dc3545;
            --red-dark: #bd2130;
            --pink: #e83e8c;
            --pink-dark: #c82375;
            --dark-bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --glass-bg: rgba(255, 255, 255, 0.05);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: var(--dark-bg);
            color: var(--white);
            min-height: 100vh;
        }
        
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .profile-header {
            padding: 40px 0;
            text-align: center;
            position: relative;
        }
        
        .profile-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 10px;
        }
        
        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: var(--white);
            border: 5px solid rgba(255, 255, 255, 0.2);
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .status-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid var(--card-bg);
        }
        
        .status-active {
            background-color: var(--green);
        }
        
        .status-inactive {
            background-color: var(--red);
        }
        
        .info-card {
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            flex: 0 0 200px;
            color: #94a3b8;
            font-weight: 500;
        }
        
        .info-value {
            flex: 1;
            color: var(--white);
            font-weight: 400;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            background: rgba(0, 123, 255, 0.1);
            color: var(--blue);
        }
        
        .badge-custom {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-role {
            background: var(--yellow);
            color: #000;
        }
        
        .badge-designation {
            background: var(--blue);
            color: var(--white);
        }
        
        .btn-edit {
            background: var(--pink);
            border: none;
            color: var(--white);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            background: var(--pink-dark);
            transform: translateY(-2px);
            color: var(--white);
        }
        
        .modal-content {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--white);
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--pink);
            color: var(--white);
            box-shadow: 0 0 0 0.25rem rgba(232, 62, 140, 0.25);
        }
        
        .form-label {
            color: #94a3b8;
            font-weight: 500;
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        .stats-joined {
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid var(--blue);
        }
        
        .stats-joined .stats-number {
            color: var(--blue);
        }
        
        .stats-updated {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid var(--green);
        }
        
        .stats-updated .stats-number {
            color: var(--green);
        }
        
        .stats-batch {
            background: rgba(232, 62, 140, 0.1);
            border: 1px solid var(--pink);
        }
        
        .stats-batch .stats-number {
            color: var(--pink);
        }
        
        /* Button styles */
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--white);
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--white);
        }
        
        .btn-outline-danger {
            border-color: var(--red);
            color: var(--red);
        }
        
        .btn-outline-danger:hover {
            background: var(--red);
            color: var(--white);
        }
        
        .btn-outline-info {
            border-color: var(--blue);
            color: var(--blue);
        }
        
        .btn-outline-info:hover {
            background: var(--blue);
            color: var(--white);
        }
        
        .btn-outline-success {
            border-color: var(--green);
            color: var(--green);
        }
        
        .btn-outline-success:hover {
            background: var(--green);
            color: var(--white);
        }
        
        .bg-success {
            background-color: var(--green) !important;
        }
        
        .bg-danger {
            background-color: var(--red) !important;
        }
        
        .bg-warning {
            background-color: var(--yellow) !important;
            color: #000 !important;
        }
        
        .bg-info {
            background-color: var(--blue) !important;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid var(--green);
            color: var(--green);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid var(--red);
            color: var(--red);
        }
        
        .btn-primary {
            background: var(--blue);
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--blue-dark);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        @media (max-width: 768px) {
            .info-label {
                flex: 0 0 150px;
            }
            
            .profile-header h1 {
                font-size: 2rem;
            }
            
            .avatar-container {
                width: 120px;
                height: 120px;
            }
            
            .avatar {
                width: 120px;
                height: 120px;
                font-size: 50px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="profile-header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
            <div>
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="avatar-container">
            <div class="avatar">
                <?php 
                // Display initials if no image
                $initials = strtoupper(substr($user['name'], 0, 2));
                echo $initials;
                ?>
            </div>
            <div class="status-badge <?php echo $user['status'] == 1 ? 'status-active' : 'status-inactive'; ?>"></div>
        </div>
        
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <p class="text-muted">Employee ID: <?php echo htmlspecialchars($user['emp_id']); ?></p>
        
        <div class="d-flex justify-content-center gap-2 mb-4">
            <span class="badge badge-custom badge-role"><?php echo htmlspecialchars($user['role']); ?></span>
            <span class="badge badge-custom badge-designation"><?php echo htmlspecialchars($user['designation']); ?></span>
        </div>
        
        <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal" readonly>
            <i class="bi bi-pencil-square me-2"></i> Edit Profile
        </button>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="glass-card info-card">
                <h3 class="mb-4"><i class="bi bi-person-badge me-2" style="color: var(--pink);"></i> Personal Information</h3>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['name']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($user['email_id']); ?>
                        <a href="mailto:<?php echo htmlspecialchars($user['email_id']); ?>" class="btn btn-sm btn-outline-info ms-2">
                            <i class="bi bi-send"></i>
                        </a>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($user['mobile_no']); ?>
                        <a href="tel:<?php echo htmlspecialchars($user['mobile_no']); ?>" class="btn btn-sm btn-outline-success ms-2">
                            <i class="bi bi-telephone"></i>
                        </a>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="info-label">Designation</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['designation']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div class="info-label">Division</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['division']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-grid"></i>
                    </div>
                    <div class="info-label">Section</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['section']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="info-label">Place of Posting</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['place_of_posting']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="info-label">Office</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['office']); ?></div>
                </div>
            </div>
        </div>

        <!-- Statistics & System Info -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="glass-card info-card">
                <h5 class="mb-4"><i class="bi bi-info-circle me-2" style="color: var(--yellow);"></i> Account Status</h5>
                
                <div class="info-row">
                    <div class="info-label">Account Status</div>
                    <div class="info-value">
                        <?php if ($user['status'] == 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">User Role</div>
                    <div class="info-value">
                        <span class="badge bg-warning"><?php echo htmlspecialchars($user['role']); ?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Batch</div>
                    <div class="info-value">
                        <span class="badge bg-info"><?php echo htmlspecialchars($user['batch']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mt-3">
                <div class="col-12">
                    <div class="stats-card stats-joined">
                        <div class="stats-number">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="stats-label">Joined On</div>
                        <div class="stats-value"><?php echo date('d M Y', strtotime($user['created_at'])); ?></div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="stats-card stats-updated">
                        <div class="stats-number">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stats-label">Last Updated</div>
                        <div class="stats-value"><?php echo date('d M Y', strtotime($user['updated_at'])); ?></div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="stats-card stats-batch">
                        <div class="stats-number">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-label">Batch Group</div>
                        <div class="stats-value"><?php echo htmlspecialchars($user['batch']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email_id" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email_id" name="email_id" 
                                   value="<?php echo htmlspecialchars($user['email_id']); ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="mobile_no" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                                   value="<?php echo htmlspecialchars($user['mobile_no']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" 
                                   value="<?php echo htmlspecialchars($user['designation']); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="division" class="form-label">Division</label>
                            <input type="text" class="form-control" id="division" name="division" 
                                   value="<?php echo htmlspecialchars($user['division']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control" id="section" name="section" 
                                   value="<?php echo htmlspecialchars($user['section']); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="place_of_posting" class="form-label">Place of Posting</label>
                            <input type="text" class="form-control" id="place_of_posting" name="place_of_posting" 
                                   value="<?php echo htmlspecialchars($user['place_of_posting']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="office" class="form-label">Office</label>
                            <input type="text" class="form-control" id="office" name="office" 
                                   value="<?php echo htmlspecialchars($user['office']); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Add animation to cards on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.glass-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

</body>
</html>

<script>
// Disable Back & Forward
history.pushState(null, null, location.href);
window.onpopstate = function () {
   window.location.href = "reload_handler.php";
};

// If page reloads, destroy session and redirect
// Prevent reload
if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.location.href = "reload_handler.php";
}

window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        window.location.href = "../index.php";
    }
});
</script>