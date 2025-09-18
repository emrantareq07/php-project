<?php
session_start();
include 'controller/db.php'; // Adjust if needed

/* ===============================
   USER REGISTRATION
================================= */
if (isset($_POST['register'])) {
    $emp_id = $_POST['emp_id']?? '';
    $name = $_POST['name'];
    $batch = $_POST['batch'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $division = $_POST['division'] ?? '';
    $section = $_POST['section'] ?? '';
    $place_of_posting = $_POST['place_of_posting'] ?? '';
    $office = $_POST['office'] ?? '';
    $mobile_no = $_POST['mobile_no'];
    $email_id = $_POST['email_id'];
    $password = '1234';
    $status = 'active';
    $role = 'user';

    // Check duplicate emp_id / email
    // $check = $conn->prepare("SELECT * FROM users_tbl WHERE batch = ? and email_id = ? or mobile_no = ?    ");
    // $check->bind_param("ss", $emp_id, $email_id);
    // $check->execute();
    // $result = $check->get_result();
    
    $check = $conn->prepare("
    SELECT * FROM users_tbl 
    WHERE batch = ? AND (email_id = ? OR mobile_no = ?)
");
$check->bind_param("iss", $batch, $email_id, $mobile_no);

$check->execute();
$result = $check->get_result();


    if ($result->num_rows > 0) {
        $alert = "Employee ID or Email already exists!";
        $alertType = "danger";
    } else {
        $insert = $conn->prepare("INSERT INTO users_tbl 
            (emp_id, name, batch, designation, division, section, place_of_posting, office, mobile_no, email_id, password, status, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssssssssss", $emp_id, $name, $batch, $designation, $division, $section, $place_of_posting, $office, $mobile_no, $email_id, $password, $status, $role);
        if ($insert->execute()) {
            $alert = "Registration successful! Default password is 1234";
            $alertType = "success";
        } else {
            $alert = "Registration failed!";
            $alertType = "danger";
        }
    }
}

/* ===============================
   USER LOGIN
================================= */
if (isset($_POST['login'])) {
    $email_id = $_POST['email_id'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users_tbl WHERE email_id=? AND password=? AND status='active'");
    $stmt->bind_param("ss", $email_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['emp_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email_id'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: controller/dashboard.php");
        exit();
    } else {
        $alert = "Invalid email or password, or your account is not active!";
        $alertType = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Authentication</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body { background: #f8f9fa; }
.auth-container { max-width: 600px; margin: 30px auto; padding: 25px; border-radius: 10px; background: #fff; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
.auth-header { text-align: center; margin-bottom: 10px; }
.auth-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 5px; }
.password-toggle { cursor: pointer; }
</style>
</head>
<body>

<div class="container ">

<!-- Alerts -->
<?php if (!empty($alert)): ?>
<div class="alert alert-<?= $alertType ?> alert-dismissible fade show mt-3">
    <?= $alert ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Login Form -->
<div class="auth-container border border-primary" id="login-form">
    <div class="auth-header">
        <i class="bi bi-person-check auth-icon"></i>
        <h3>User Login</h3>
        <p class="text-muted">Sign in to your account</p>
    </div>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email_id" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="password" class="form-control" id="login_password" name="password" required>
                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('login_password')">
                    <i class="bi bi-eye"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
        <div class="text-center mt-3">
            <p>Available Training
            <a href="#" onclick="event.preventDefault(); loadTrainingList(event)">Click Here</a></p>
        </div>
    </form>
</div>

<!-- Registration Form -->
<div class="auth-container border border-primary" id="registration-form" style="display:none;">
    <div class="auth-header">
        <i class="bi bi-person-plus auth-icon"></i>
        <h3>User Registration</h3>
        <p class="text-muted" id="reg_training_info">Please fill your personal info.</p>
    </div>

    <form method="POST">
        <div class="row mb-3">        
        <!-- Batch Field -->
        <div class="col-md-6">
            <label class="form-label">Batch</label>
            <input type="text" class="form-control" name="batch" id="reg_batch" readonly>
        </div> 
        <div class="col-md-6">
                <label class="form-label">Employee ID <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="emp_id" >
            </div>           
        </div>

        <div class="mb-3">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="row mb-3">
            <div class="col-md-6">
            <label class="form-label">Designation <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="designation" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Office/Organization <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="place_of_posting" >
        </div>
    </div>

        <div class="row mb-3">
            <div class="col-md-6">
            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" name="mobile_no" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email_id" required>
        </div>
    </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="text" class="form-control" value="1234" readonly>
            <div class="form-text">Default password is set to 1234</div>
        </div>
        <button type="submit" class="btn btn-primary w-100" name="register"><i class='fa fa-user-plus'></i> Register</button>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="#" onclick="event.preventDefault(); showLoginForm()">Login here</a></p>
        </div>
    </form>
</div>

<!-- Training List Modal -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-labelledby="trainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase text-muted" id="trainingModalLabel">Available Trainings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="trainingList"></div>
    </div>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Password toggle
function togglePasswordVisibility(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye','bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash','bi-eye');
    }
}

// Show/hide forms
function showRegisterForm() {
    document.getElementById("login-form").style.display = "none";
    document.getElementById("registration-form").style.display = "block";
}
function showLoginForm() {
    document.getElementById("registration-form").style.display = "none";
    document.getElementById("login-form").style.display = "block";
}

// Load training list via AJAX
function loadTrainingList(e) {
    if (e && e.preventDefault) e.preventDefault();
    const target = document.getElementById("trainingList");
    target.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border" role="status"></div>
            <div class="mt-2">Loading trainings…</div>
        </div>`;
    fetch("controller/training_list.php", { cache: "no-store" })
        .then(r => r.text())
        .then(html => {
            target.innerHTML = html;
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        })
        .catch(err => {
            console.error(err);
            target.innerHTML = `<div class="alert alert-danger">Failed to load trainings.</div>`;
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        });
}

// Called from training_list.php "Register here"
function closeModalAndShowRegister(batch = '', title = '', start = '', end = '', organized = '') {
    // Close modal
    const modalEl = document.getElementById('trainingModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    // Show registration form
    showRegisterForm();
    document.getElementById('registration-form').scrollIntoView({ behavior: 'smooth' });

    // Update header text with new lines
    const infoEl = document.getElementById('reg_training_info');
    if (infoEl) {
        infoEl.innerHTML = `
            
            Title: ${title} <br>
           Date: ${start} to ${end} <br>
            Organized by: ${organized}
        `;
    }

    // Fill Batch input
    const batchInput = document.getElementById('reg_batch');
    if (batchInput) batchInput.value = batch;
}
</script>
</body>
</html>
