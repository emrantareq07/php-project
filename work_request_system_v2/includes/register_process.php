<?php
// register_process.php
session_name('factory_work_request_db');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if this is an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // AJAX request
    $isAjax = true;
} else {
    $isAjax = false;
}

// Set header for JSON response
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Database configuration - UPDATE THESE WITH YOUR ACTUAL CREDENTIALS
$host = 'localhost';
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$database = 'factory_work_request_db'; // Your database name

// Create connection
try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get form data
$emp_id = trim($_POST['emp_id'] ?? '');
$full_name = trim($_POST['full_name'] ?? '');
$designation = trim($_POST['designation'] ?? '');
$division = trim($_POST['division'] ?? '');
$section = trim($_POST['section'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Debug log (remove in production)
error_log("Registration attempt - Emp ID: $emp_id, Name: $full_name");

// Validation
$errors = [];

// Validate employee ID
if (empty($emp_id)) {
    $errors[] = 'Employee ID is required';
} elseif (strlen($emp_id) < 3) {
    $errors[] = 'Employee ID must be at least 3 characters';
} else {
    // Check if employee ID already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE emp_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $emp_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = 'Employee ID already exists';
        }
        $stmt->close();
    } else {
        error_log("Prepare statement failed: " . $conn->error);
        $errors[] = 'Database error. Please try again.';
    }
}

// Validate full name
if (empty($full_name)) {
    $errors[] = 'Full name is required';
} elseif (strlen($full_name) < 2) {
    $errors[] = 'Full name must be at least 2 characters';
}

// Validate designation
if (empty($designation)) {
    $errors[] = 'Designation is required';
}

// Validate division
if (empty($division)) {
    $errors[] = 'Division is required';
}

// Validate section
if (empty($section)) {
    $errors[] = 'Section is required';
}

// Validate password
if (empty($password)) {
    $errors[] = 'Password is required';
} elseif (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters';
} elseif (!preg_match('/[A-Z]/', $password) || 
          !preg_match('/[a-z]/', $password) || 
          !preg_match('/[0-9]/', $password)) {
    $errors[] = 'Password must contain uppercase, lowercase, and numbers';
}

// Validate confirm password
if (empty($confirm_password)) {
    $errors[] = 'Please confirm your password';
} elseif ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
}

// If there are errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
    $conn->close();
    exit;
}

// Hash password using bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Check if hashing was successful
if ($hashed_password === false) {
    echo json_encode(['success' => false, 'message' => 'Password encryption failed']);
    $conn->close();
    exit;
}

// Set default values
$status = 'active';
$role = 'user';
$routine_role = NULL;

// First, let's check if the users table exists
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check->num_rows == 0) {
    // Table doesn't exist, create it
    $create_table_sql = "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        emp_id VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        designation VARCHAR(100),
        division VARCHAR(100),
        section VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        role ENUM('user', 'admin', 'sadmin') DEFAULT 'user',
        routine_role ENUM('section_head', 'division_head') DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table_sql)) {
        error_log("Create table failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database table creation failed: ' . $conn->error]);
        $conn->close();
        exit;
    }
}

// Prepare SQL statement
$sql = "INSERT INTO users (emp_id, password, full_name, designation, division, section, status, role, routine_role, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    $conn->close();
    exit;
}

// Bind parameters
$bind_result = $stmt->bind_param("sssssssss", 
    $emp_id, 
    $hashed_password, 
    $full_name, 
    $designation, 
    $division, 
    $section, 
    $status, 
    $role, 
    $routine_role
);

if ($bind_result === false) {
    error_log("Bind param failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Execute the statement
if ($stmt->execute()) {
    // Get the last inserted ID (optional)
    $user_id = $stmt->insert_id;
    
    error_log("Registration successful for user ID: $user_id, Emp ID: $emp_id");
    
    echo json_encode(['success' => true, 'message' => 'Registration successful!']);
} else {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
}

// Close statements and connection
if (isset($stmt) && $stmt) {
    $stmt->close();
}
$conn->close();
?>