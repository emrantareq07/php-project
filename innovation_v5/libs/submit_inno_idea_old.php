<?php
require_once("../db/db.php");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
set_time_limit(300);

$success_message = '';
$error_message = '';

if(isset($_POST['submit_idea'])){

mysqli_begin_transaction($conn);

try{

// =======================
// 1. FORM DATA
// =======================

$emp_id = trim($_POST['emp_id']);
$fullname = trim($_POST['fullname']);
$designation = $_POST['designation'] ?? null;
$email = trim($_POST['email']);
$mobile_no = $_POST['mobile_no'] ?? null;
$place_of_posting = $_POST['place_of_posting'] ?? null;

$fiscal_year = $_POST['fiscal_year'];
$title_of_idea = $_POST['title_of_idea'];
$idea_imp_date = $_POST['idea_imp_date'] ?? null;

$identify_prob_desc = $_POST['identify_prob_desc'];
$prob_sol_plan = $_POST['prob_sol_plan'];
$prob_sol_desc = $_POST['prob_sol_desc'];

$cost = $_POST['cost'] ?? 0;

$cost_less_desc = $_POST['cost_less_desc'] ?? null;
$value_add = $_POST['value_add'] ?? null;
$time_saving = $_POST['time_saving'] ?? null;
$cost_effectiveness = $_POST['cost_effectiveness'] ?? null;
$profitability = $_POST['profitability'] ?? null;

$attestration = $_POST['attestration'] ?? null;
$remarks = $_POST['remarks'] ?? null;


// =======================
// 2. FILE UPLOAD PATH
// =======================

$image_befor_after_inno = null;
$flowchart = null;

$fy_tag = str_replace(['-','/'], '_', $fiscal_year);

// =======================
// CREATE UPLOAD FOLDERS
// =======================

$img_dir = "uploads/idea_images/";
$flow_dir = "uploads/flowcharts/";

if (!is_dir($img_dir)) {
    mkdir($img_dir, 0777, true);
}

if (!is_dir($flow_dir)) {
    mkdir($flow_dir, 0777, true);
}
// =======================
// IMAGE UPLOAD
// =======================

if(!empty($_FILES['image_befor_after_inno']['name'])){

$ext = pathinfo($_FILES['image_befor_after_inno']['name'], PATHINFO_EXTENSION);

$filename = $emp_id."_".$fy_tag."_".time().".".$ext;

$upload_path = $img_dir.$filename;

move_uploaded_file($_FILES['image_befor_after_inno']['tmp_name'],$upload_path);

$image_befor_after_inno = "uploads/idea_images/".$filename;

}


// =======================
// FLOWCHART UPLOAD
// =======================

if(!empty($_FILES['flowchart']['name'])){

$ext = pathinfo($_FILES['flowchart']['name'], PATHINFO_EXTENSION);

$filename = $emp_id."_".$fy_tag."_flowchart_".time().".".$ext;

$upload_path = "uploads/flowcharts/".$filename;

move_uploaded_file($_FILES['flowchart']['tmp_name'],$upload_path);

$flowchart = "uploads/flowcharts/".$filename;

}


// =======================
// 3. CHECK USER
// =======================

$stmt = $conn->prepare("SELECT id FROM tbl_users WHERE emp_id=?");
$stmt->bind_param("s",$emp_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){

$password = md5('1234');

$stmt = $conn->prepare("INSERT INTO tbl_users
(emp_id,fullname,designation,email,mobile_no,place_of_posting,password,role)
VALUES (?,?,?,?,?,?,?,?)");

$role = 'user';

$stmt->bind_param(
"ssssssss",
$emp_id,
$fullname,
$designation,
$email,
$mobile_no,
$place_of_posting,
$password,
$role
);

$stmt->execute();

}


// =======================
// 4. INSERT INNOVATION
// =======================

$stmt = $conn->prepare("INSERT INTO tbl_innovation(

emp_id,
fullname,
designation,
email,
mobile_no,
place_of_posting,

fiscal_year,
title_of_idea,
idea_imp_date,

identify_prob_desc,
prob_sol_plan,
prob_sol_desc,

cost,
cost_less_desc,
value_add,
time_saving,
cost_effectiveness,
profitability,

image_befor_after_inno,
flowchart,

attestration,
remarks,

imple_status,
replicate_eligibility,
prize,
prize_amount,
rank

) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");


$imple_status='চলমান';
$replicate_eligibility=null;
$prize='no';
$prize_amount=null;
$rank=null;

$stmt->bind_param(

"sssssssssssdsssssssssssssss",

$emp_id,
$fullname,
$designation,
$email,
$mobile_no,
$place_of_posting,

$fiscal_year,
$title_of_idea,
$idea_imp_date,

$identify_prob_desc,
$prob_sol_plan,
$prob_sol_desc,

$cost,
$cost_less_desc,
$value_add,
$time_saving,
$cost_effectiveness,
$profitability,

$image_befor_after_inno,
$flowchart,

$attestration,
$remarks,

$imple_status,
$replicate_eligibility,
$prize,
$prize_amount,
$rank

);

$stmt->execute();


// =======================
// COMMIT
// =======================

mysqli_commit($conn);

$success_message="Innovation submitted successfully!";

$_POST=[];

}
catch(Exception $e){

if($conn && mysqli_ping($conn)){
mysqli_rollback($conn);
}

$error_message="Error : ".$e->getMessage();

}

}


// =======================
// FISCAL YEAR LIST
// =======================

// $fiscal_years = mysqli_query($conn,"SELECT * FROM fiscal_year ORDER BY id DESC");


// Get fiscal years
$fiscal_years = mysqli_query($conn, "SELECT * FROM fiscal_year ORDER BY id DESC");
if(!$fiscal_years || mysqli_num_rows($fiscal_years) == 0) {
    // Default fiscal years if table is empty
    $default_fy = ['২০২৪-২০২৫', '২০২৩-২০২৪', '২০২২-২০২৩', '২০২১-২০২২', '২০২০-২০২১'];
}

function convertToBanglaNumber($number) {
    $engDigits  = ['0','1','2','3','4','5','6','7','8','9'];
    $bangDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace($engDigits, $bangDigits, $number);
}
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
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
            flex-wrap: wrap;
            gap: 10px;
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
                <img src="../images/bcic_logo.png" alt="BCIC Logo">
                <h2><i class="fas fa-lightbulb me-2"></i>Submit Innovation Idea</h2>
                <p>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</p>
                <p class="mt-2">Bangladesh Chemical Industries Corporation</p>
            </div>
            
            <div class="card-body">
                <div class="header-actions">
                    <a href="dashboard.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="../index.php" class="btn-back">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </div>
                
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2 text-primary"></i>Important Information</h5>
                    <p>Your employee information will be used to create a user account with default password: <strong>1234</strong>. You can change it after login.</p>
                </div>
                
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
                
                <form method="POST" enctype="multipart/form-data">

    <!-- Employee Information -->
   <div class="section-title">
        <i class="fas fa-user"></i>Employee Information
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label required">Employee ID</label>
            <input type="text" name="emp_id" class="form-control" required value="<?php echo htmlspecialchars($_POST['emp_id'] ?? $_SESSION['emp_id'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Full Name</label>
            <input type="text" name="fullname" class="form-control" required value="<?php echo htmlspecialchars($_POST['fullname'] ?? $_SESSION['fullname'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Designation</label>
            <input type="text" name="designation" class="form-control" value="<?php echo htmlspecialchars($_POST['designation'] ?? $_SESSION['designation'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Email </label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? $_SESSION['email'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Mobile Number</label>
            <input type="text" name="mobile_no" class="form-control" required value="<?php echo htmlspecialchars($_POST['mobile_no'] ?? $_SESSION['mobile_no'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Place of Posting</label>
            <input type="text" name="place_of_posting" class="form-control" required value="<?php echo htmlspecialchars($_POST['place_of_posting'] ?? $_SESSION['place_of_posting'] ?? ''); ?>">
        </div>

    </div>

 <!-- Innovation Details -->
    <div class="section-title">
        <i class="fas fa-lightbulb"></i>Innovation Details
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label required">Fiscal Year</label>
            <select name="fiscal_year" class="form-select" required>
                <option value="">Select Fiscal Year</option>
                <?php  
                /* ===============================
                   GET ACTIVE FISCAL YEAR
                =================================*/
                $recent_query = "
                    SELECT fiscal_year 
                    FROM tbl_innovation_idea 
                    WHERE idea_status='active'
                    ORDER BY id DESC
                    LIMIT 1
                ";

                $recent_result = mysqli_query($conn, $recent_query);

               /* ===============================
               SHOW FISCAL YEAR IN BANGLA
            =================================*/


            if ($recent_result && mysqli_num_rows($recent_result) > 0) {
                $row_fiscal_year = mysqli_fetch_assoc($recent_result);
                $fiscal_year = $row_fiscal_year['fiscal_year'];

                // Convert fiscal year to Bangla numerals
                $fiscal_year_bangla = convertToBanglaNumber($fiscal_year);

                // If you want to mark it as selected
                $selected = "selected";

                echo "<option value='{$fiscal_year}' {$selected}>{$fiscal_year_bangla}</option>";
            }
                            ?>
            </select>
                <?php
                // if(isset($fiscal_years) && mysqli_num_rows($fiscal_years) > 0) {
                //     while($fy = mysqli_fetch_assoc($fiscal_years)) {
                //         $selected = (isset($_POST['fiscal_year']) && $_POST['fiscal_year'] == $fy['fiscal_year']) ? 'selected' : '';
                //         echo "<option value='/{$fy['fiscal_year']}' $selected>{$fy['fiscal_year']}</option>";
                //     }
                // } else {
                //     foreach($default_fy as $fy) {
                //         $selected = (isset($_POST['fiscal_year']) && $_POST['fiscal_year'] == $fy) ? 'selected' : '';
                //         echo "<option value='$fy' $selected>$fy</option>";
                //     }
                // }
                ?>
            <!-- </select> -->
        </div>

        <div class="col-md-8">
            <label class="form-label required">Title of Idea </label>
            <input type="text" name="title_of_idea" class="form-control" required value="<?php echo htmlspecialchars($_POST['title_of_idea'] ?? $_SESSION['title_of_idea'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label ">Implementation Date</label>
            <input type="date" name="idea_imp_date" class="form-control"  value="<?php echo htmlspecialchars($_POST['idea_imp_date'] ?? $_SESSION['idea_imp_date'] ?? ''); ?>">
        </div>

        <div class="col-md-12">
            <label class="form-label required">Problem Description </label>
            <textarea name="identify_prob_desc" class="form-control" required value="<?php echo htmlspecialchars($_POST['identify_prob_desc'] ?? $_SESSION['identify_prob_desc'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label required">Solution Plan </label>
            <textarea name="prob_sol_plan" class="form-control" required value="<?php echo htmlspecialchars($_POST['prob_sol_plan'] ?? $_SESSION['prob_sol_plan'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label required">Solution Description </label>
            <textarea name="prob_sol_desc" class="form-control" required value="<?php echo htmlspecialchars($_POST['prob_sol_desc'] ?? $_SESSION['prob_sol_desc'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Cost (BDT)</label>
            <input type="number" step="0.01" name="cost" class="form-control" value="<?php echo htmlspecialchars($_POST['cost'] ?? $_SESSION['cost'] ?? ''); ?>">
        </div>

        <div class="col-md-8">
            <label class="form-label">Cost Less Description</label>
            <textarea name="cost_less_desc" class="form-control" value="<?php echo htmlspecialchars($_POST['cost_less_desc'] ?? $_SESSION['cost_less_desc'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label">Value Addition</label>
            <textarea name="value_add" class="form-control" value="<?php echo htmlspecialchars($_POST['value_add'] ?? $_SESSION['value_add'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Time Saving</label>
            <textarea name="time_saving" class="form-control" value="<?php echo htmlspecialchars($_POST['time_saving'] ?? $_SESSION['time_saving'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cost Effectiveness</label>
            <textarea name="cost_effectiveness" class="form-control" value="<?php echo htmlspecialchars($_POST['cost_effectiveness'] ?? $_SESSION['cost_effectiveness'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Profitability</label>
            <textarea name="profitability" class="form-control" value="<?php echo htmlspecialchars($_POST['profitability'] ?? $_SESSION['profitability'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Image (Before / After)</label>
            <input type="file" name="image_befor_after_inno" class="form-control" accept="image/*">
        </div>

        <div class="col-md-6">
            <label class="form-label">Flowchart (Before / After)</label>
            <input type="file" name="flowchart" class="form-control" accept="image/*,.pdf">
        </div>

        <div class="col-md-6">
            <label class="form-label">ইতোপূর্বে ধারণাটি জমা দেয়া হয়নি মর্মে প্রত্যয়ন সংযুক্ত করতে হবে</label>
            <textarea name="attestration" class="form-control" value="<?php echo htmlspecialchars($_POST['attestration'] ?? $_SESSION['attestration'] ?? ''); ?>"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" value="<?php echo htmlspecialchars($_POST['remarks'] ?? $_SESSION['remarks'] ?? ''); ?>"></textarea>
        </div>

    </div>

   <!--  <div class="mt-4">
        <button type="submit" name="submit_idea" class="btn btn-primary">
            Submit Innovation Idea
        </button>
    </div> -->

        <div class="mt-4">
        <button type="submit" name="submit_idea" class="btn-submit" id="submitBtn">
            <i class="fas fa-paper-plane me-2"></i>Submit Innovation Idea
        </button>
    </div>

</form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#innovationForm').submit(function() {
                $('.btn-submit').html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...').prop('disabled', true);
                return true;
            });
        });
    </script>
</body>
</html>