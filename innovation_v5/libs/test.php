<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH . 'libs/function.php');

// Check if user is logged in (optional - remove if you want public submission)
// if(!isset($_SESSION['uid']) && !isset($_COOKIE['user_login'])) {
//     header('Location: login.php');
//     exit;
// }

$usercredentials = new DB_con();
$success_message = $error_message = '';

if(isset($_POST['submit_idea'])) {
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Get form data
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
        $place_of_posting = mysqli_real_escape_string($conn, $_POST['place_of_posting']);
        
        // Innovation data
        $fiscal_year = mysqli_real_escape_string($conn, $_POST['fiscal_year']);
        $title_of_idea = mysqli_real_escape_string($conn, $_POST['title_of_idea']);
        $idea_imp_date = mysqli_real_escape_string($conn, $_POST['idea_imp_date']);
        $identify_prob_desc = mysqli_real_escape_string($conn, $_POST['identify_prob_desc']);
        $prob_sol_plan = mysqli_real_escape_string($conn, $_POST['prob_sol_plan']);
        $prob_sol_desc = mysqli_real_escape_string($conn, $_POST['prob_sol_desc']);
        $cost = mysqli_real_escape_string($conn, $_POST['cost']);
        $cost_less_desc = mysqli_real_escape_string($conn, $_POST['cost_less_desc']);
        $value_add = mysqli_real_escape_string($conn, $_POST['value_add']);
        $time_saving = mysqli_real_escape_string($conn, $_POST['time_saving']);
        $cost_effectiveness = mysqli_real_escape_string($conn, $_POST['cost_effectiveness']);
        $profitability = mysqli_real_escape_string($conn, $_POST['profitability']);
        $imple_status = mysqli_real_escape_string($conn, $_POST['imple_status']);
        $replicate_eligibility = mysqli_real_escape_string($conn, $_POST['replicate_eligibility']);
        $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
        $prize = mysqli_real_escape_string($conn, $_POST['prize']);
        $prize_amount = mysqli_real_escape_string($conn, $_POST['prize_amount']);
        $rank = mysqli_real_escape_string($conn, $_POST['rank']);
        
        // File uploads
        $image_befor_after_inno = '';
        $flowchart = '';
        
        // Handle image upload
        if(isset($_FILES['image_befor_after_inno']) && $_FILES['image_befor_after_inno']['error'] == 0) {
            $image_data = file_get_contents($_FILES['image_befor_after_inno']['tmp_name']);
            $image_befor_after_inno = mysqli_real_escape_string($conn, $image_data);
        }
        
        // Handle flowchart upload
        if(isset($_FILES['flowchart']) && $_FILES['flowchart']['error'] == 0) {
            $flowchart_data = file_get_contents($_FILES['flowchart']['tmp_name']);
            $flowchart = mysqli_real_escape_string($conn, $flowchart_data);
        }
        
        // 1. Insert into tbl_users first
        $default_password = md5('1234'); // Default password
        $role = 'user';
        
        // Check if user already exists
        $check_user = mysqli_query($conn, "SELECT id FROM tbl_users WHERE emp_id='$emp_id'");
        
        if(mysqli_num_rows($check_user) == 0) {
            // Insert new user
            $user_query = "INSERT INTO tbl_users (emp_id, fullname, designation, email, mobile_no, place_of_posting, password, role) 
                          VALUES ('$emp_id', '$fullname', '$designation', '$email', '$mobile_no', '$place_of_posting', '$default_password', '$role')";
            
            if(!mysqli_query($conn, $user_query)) {
                throw new Exception("Error inserting user: " . mysqli_error($conn));
            }
        }
        
        // 2. Insert into tbl_innovation
        $innovation_query = "INSERT INTO tbl_innovation (
            emp_id, fullname, designation, email, mobile_no, place_of_posting,
            fiscal_year, title_of_idea, idea_imp_date, identify_prob_desc, prob_sol_plan,
            prob_sol_desc, cost, cost_less_desc, value_add, time_saving, cost_effectiveness,
            profitability, image_befor_after_inno, flowchart, imple_status, replicate_eligibility,
            remarks, prize, prize_amount, rank
        ) VALUES (
            '$emp_id', '$fullname', '$designation', '$email', '$mobile_no', '$place_of_posting',
            '$fiscal_year', '$title_of_idea', '$idea_imp_date', '$identify_prob_desc', '$prob_sol_plan',
            '$prob_sol_desc', '$cost', '$cost_less_desc', '$value_add', '$time_saving', '$cost_effectiveness',
            '$profitability', '$image_befor_after_inno', '$flowchart', '$imple_status', '$replicate_eligibility',
            '$remarks', '$prize', '$prize_amount', '$rank'
        )";
        
        if(!mysqli_query($conn, $innovation_query)) {
            throw new Exception("Error inserting innovation: " . mysqli_error($conn));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        $success_message = "Innovation idea submitted successfully! Default password: 1234";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error_message = "Error: " . $e->getMessage();
    }
}

// Get fiscal years for dropdown
$fiscal_years = mysqli_query($conn, "SELECT * FROM fiscal_year ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Innovation Idea - BCIC</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }
        
        .form-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .form-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            border-bottom: none;
        }
        
        .card-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 15px;
        }
        
        .card-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 15px 0 5px;
        }
        
        .card-header p {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }
        
        .card-body {
            padding: 40px;
        }
        
        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            margin: 30px 0 20px;
            font-size: 20px;
            font-weight: 600;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .required::after {
            content: " *";
            color: #e53e3e;
            font-weight: bold;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .input-group-custom {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .input-group-custom .input-group-text {
            background: #f7fafc;
            border: none;
            padding: 12px 15px;
            color: #4a5568;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .row {
            margin-bottom: 15px;
        }
        
        .alert {
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }
        
        .alert-danger {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 30px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-back {
            background: white;
            color: #667eea;
            padding: 12px 25px;
            border: 2px solid #667eea;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-back:hover {
            background: #667eea;
            color: white;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #f7fafc;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .info-box h5 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .info-box p {
            color: #4a5568;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
            
            .section-title {
                font-size: 18px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-card">
            <div class="card-header">
                <img src="images/bcic_logo.png" alt="BCIC Logo">
                <h2><i class="fas fa-lightbulb me-2"></i>Submit Innovation Idea</h2>
                <p>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</p>
                <p class="mt-2">Bangladesh Chemical Industries Corporation</p>
            </div>
            
            <div class="card-body">
                <!-- Header Actions -->
                <div class="header-actions">
                    <a href="dashboard.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="index.php" class="btn-back">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </div>
                
                <!-- Info Box -->
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2 text-primary"></i>Important Information</h5>
                    <p>Your employee information will be used to create a user account with default password: <strong>1234</strong>. You can change it after login.</p>
                </div>
                
                <!-- Success/Error Messages -->
                <?php if($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($error_message): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" id="innovationForm">
                    <!-- Employee Information Section -->
                    <div class="section-title">
                        <i class="fas fa-user"></i>Employee Information
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label required">Employee ID</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" name="emp_id" required 
                                       placeholder="Enter Employee ID" value="<?php echo isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label required">Full Name</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="fullname" required 
                                       placeholder="Enter Full Name">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                <input type="text" class="form-control" name="designation" 
                                       placeholder="Enter Designation">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label required">Email</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" required 
                                       placeholder="Enter Email">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" name="mobile_no" 
                                       placeholder="Enter Mobile Number">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Place of Posting</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" name="place_of_posting" 
                                       placeholder="Enter Place of Posting">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Innovation Details Section -->
                    <div class="section-title">
                        <i class="fas fa-lightbulb"></i>Innovation Details
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label required">Fiscal Year</label>
                            <select class="form-select" name="fiscal_year" required>
                                <option value="">Select Fiscal Year</option>
                                <?php while($fy = mysqli_fetch_assoc($fiscal_years)): ?>
                                    <option value="<?php echo $fy['fiscal_year']; ?>"><?php echo $fy['fiscal_year']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label required">Title of Idea/Innovation</label>
                            <input type="text" class="form-control" name="title_of_idea" required 
                                   placeholder="Enter Title of Idea/Innovation">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Idea Implementation Date</label>
                            <input type="date" class="form-control" name="idea_imp_date">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label required">Identified Problem Description</label>
                            <textarea class="form-control" name="identify_prob_desc" required 
                                      placeholder="Describe the identified problem"></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label required">Problem Solution Plan</label>
                            <textarea class="form-control" name="prob_sol_plan" required 
                                      placeholder="Describe your solution plan"></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label required">Problem Solution Description</label>
                            <textarea class="form-control" name="prob_sol_desc" required 
                                      placeholder="Describe how you solved the problem"></textarea>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Cost (BDT)</label>
                            <div class="input-group-custom input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control" name="cost" 
                                       placeholder="Enter Cost">
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label">Cost Less Description</label>
                            <textarea class="form-control" name="cost_less_desc" 
                                      placeholder="Describe cost savings"></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Value Addition</label>
                            <textarea class="form-control" name="value_add" 
                                      placeholder="Describe value addition"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Time Saving</label>
                            <textarea class="form-control" name="time_saving" 
                                      placeholder="Describe time savings"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Cost Effectiveness</label>
                            <textarea class="form-control" name="cost_effectiveness" 
                                      placeholder="Describe cost effectiveness"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Profitability</label>
                            <textarea class="form-control" name="profitability" 
                                      placeholder="Describe profitability"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Before/After Innovation Image</label>
                            <input type="file" class="form-control" name="image_befor_after_inno" 
                                   accept="image/*">
                            <small class="text-muted">Upload image showing before/after</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Flowchart</label>
                            <input type="file" class="form-control" name="flowchart" 
                                   accept="image/*,.pdf">
                            <small class="text-muted">Upload process flowchart</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label required">Implementation Status</label>
                            <select class="form-select" name="imple_status" required>
                                <option value="">Select Status</option>
                                <option value="বাস্তবায়িত">বাস্তবায়িত</option>
                                <option value="চলমান">চলমান</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Replicate Eligibility</label>
                            <input type="text" class="form-control" name="replicate_eligibility" 
                                   placeholder="Enter replicate eligibility">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" 
                                      placeholder="Any remarks"></textarea>
                        </div>
                    </div>
                    
                    <!-- Prize Information Section -->
                    <div class="section-title">
                        <i class="fas fa-trophy"></i>Prize Information
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Received Prize?</label>
                            <select class="form-select" name="prize">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Prize Amount</label>
                            <input type="text" class="form-control" name="prize_amount" 
                                   placeholder="Enter prize amount">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Rank</label>
                            <select class="form-select" name="rank">
                                <option value="">Select Rank</option>
                                <option value="1st">1st</option>
                                <option value="2nd">2nd</option>
                                <option value="3rd">3rd</option>
                                <option value="4th">4th</option>
                                <option value="5th">5th</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" name="submit_idea" class="btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Submit Innovation Idea
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.form-select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // Form validation
            $('#innovationForm').submit(function(e) {
                var btn = $('.btn-submit');
                btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...').prop('disabled', true);
                
                // Check required fields
                var required = $(this).find('[required]');
                var valid = true;
                
                required.each(function() {
                    if(!$(this).val()) {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if(!valid) {
                    alert('Please fill all required fields');
                    btn.html('<i class="fas fa-paper-plane me-2"></i>Submit Innovation Idea').prop('disabled', false);
                    e.preventDefault();
                }
            });
            
            // Remove invalid class on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
</body>
</html>