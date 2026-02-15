<?php
session_name('factory_work_request_db');

require_once '../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Get dropdown data BEFORE form submission handling
$designations = [];
$divisions = [];
$sections = [];

// Get designations
$sql_desg = "SELECT designation FROM designation";
$result_desg = $conn->query($sql_desg);
if ($result_desg && $result_desg->num_rows > 0) {
    while ($row_desg = $result_desg->fetch_assoc()) {
        $designations[] = $row_desg['designation'];
    }
}

// Get divisions
$sql_div = "SELECT division FROM division";
$result_div = $conn->query($sql_div);
if ($result_div && $result_div->num_rows > 0) {
    while ($row_div = $result_div->fetch_assoc()) {
        $divisions[] = $row_div['division'];
    }
}

// Get sections
$sql_section = "SELECT name FROM section";
$result_section = $conn->query($sql_section);
if ($result_section && $result_section->num_rows > 0) {
    while ($row_section = $result_section->fetch_assoc()) {
        $sections[] = $row_section['name'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $division = trim($_POST['division'] ?? '');
    $section = trim($_POST['section'] ?? '');
    
    // Validation
    if (empty($full_name)) {
        $error = "Full name is required";
    } elseif (empty($designation)) {
        $error = "Designation is required";
    } elseif (empty($division)) {
        $error = "Division is required";
    } elseif (empty($section)) {
        $error = "Section is required";
    } else {
        // Update user data
        $update_sql = "UPDATE users SET 
                      full_name = ?, 
                      designation = ?, 
                      division = ?, 
                      section = ?,
                      updated_at = NOW()
                      WHERE id = ?";
        
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $full_name, $designation, $division, $section, $user_id);
        
        if ($update_stmt->execute()) {
            // Update session data
            $_SESSION['full_name'] = $full_name;
            $_SESSION['designation'] = $designation;
            $_SESSION['division'] = $division;
            $_SESSION['section'] = $section;
            
            $message = "Profile updated successfully!";
            
            // Refresh user data
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } else {
            $error = "Failed to update profile: " . $conn->error;
        }
        $update_stmt->close();
    }
}

// Close connection after getting all data
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
     <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Add similar styles as registration page */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 500px; 
        }
        h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555;
            font-weight: 600;
        }
        input[type="text"], 
        select { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e1e5eb; 
            border-radius: 6px; 
            font-size: 16px; 
            transition: all 0.3s;
        }
        input:focus, 
        select:focus { 
            outline: none; 
            border-color: #667eea; 
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); 
        }
        .btn { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .message { 
            padding: 15px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            text-align: center;
        }
        .success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-muted text-uppercase">Update Profile</h2>
        <h5  class="text-muted text-center" >Emp ID: <?php echo $user['emp_id']?></h5>
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Designation</label>
                <select name="designation" required>
                    <option value="">Select Designation</option>
                    <?php 
                    if (!empty($designations)) {
                        foreach ($designations as $desg) {
                            $selected = ($user['designation'] == $desg) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($desg) . "\" $selected>" . htmlspecialchars($desg) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No Designation found</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Division</label>
                <select name="division" required>
                    <option value="">Select Division</option>
                    <?php 
                    if (!empty($divisions)) {
                        foreach ($divisions as $div) {
                            $selected = ($user['division'] == $div) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($div) . "\" $selected>" . htmlspecialchars($div) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No division found</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Section</label>
                <select name="section" required>
                    <option value="">Select Section</option>
                    <?php 
                    if (!empty($sections)) {
                        foreach ($sections as $sec) {
                            $selected = ($user['section'] == $sec) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($sec) . "\" $selected>" . htmlspecialchars($sec) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No section found</option>";
                    }
                    ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Update Profile</button>
        </form>
        
        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>