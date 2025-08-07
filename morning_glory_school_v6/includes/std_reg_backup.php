<?php
require_once("../db/db.php");

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
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES['picture']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
                $picture_path = $target_file;
            }
        }
    }

    if (isset($_POST['add'])) {
        // Check if registration number already exists
        $sql_regNo = "SELECT reg_no FROM std_tbl WHERE reg_no = ?";
        $stmt_check = $conn->prepare($sql_regNo);
        $stmt_check->bind_param("s", $_POST['reg_no']);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows == 0) {
            // Registration number doesn't exist, proceed with insertion
            
            // Handle file upload if needed (assuming $picture_path is set elsewhere)
            // $picture_path = ...;
            $class = $conn->real_escape_string($_POST['class']);
           $table_name = $class . '_tbl';
            
            // Add new student to std_tbl
            $stmt_std = $conn->prepare("INSERT INTO std_tbl (std_name, reg_no, roll_no, class, section, dob, mobile_no, fathers_name, mothers_name, address, status, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_std->bind_param("ssssssssssss", $_POST['std_name'], $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['dob'], $_POST['mobile_no'], $_POST['fathers_name'], $_POST['mothers_name'], $_POST['address'], $_POST['status'], $picture_path);
            
            // Add entry to play_tbl with current date
            $current_date = date('Y-m-d');
            $stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section,status) VALUES (?, ?, ?, ?, ?,?)");
            $stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);
            
            // Execute both inserts
            if ($stmt_std->execute() && $stmt_play->execute()) {
                echo '<script>alert("Student added successfully!");</script>';
            } else {
                echo '<script>alert("Error adding student!");</script>';
            }
            
            // Close statements
            $stmt_std->close();
            $stmt_play->close();
        } else {
            // Registration number already exists
            echo '<script>alert("Registration number already exists!");</script>';
        }
        
        $stmt_check->close();
    } 

elseif (isset($_POST['update'])) {
    // Use uploaded picture if available, otherwise keep the existing one
    $final_picture = $picture_path ? $picture_path : $_POST['existing_picture'];
    
      $class = $conn->real_escape_string($_POST['class']);
           $table_name = $class . '_tbl';

    // First, check if the student exists
    $sql_regNo = "SELECT id, reg_no, class FROM std_tbl WHERE reg_no = ?";
    $stmt_check = $conn->prepare($sql_regNo);
    $stmt_check->bind_param("s", $_POST['reg_no']);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

 
    if ($row = $result->fetch_assoc()) {

     $class2 = $conn->real_escape_string($row['class']);
    $table_name2 = $class2 . '_tbl';
        // OPTIONAL: Validate class if needed
        if ($row['reg_no'] == $_POST['reg_no'] && $row['class'] == $_POST['class']) {


            // Update std_tbl
            $stmt = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
            $stmt->bind_param("ssssssssssssi",
                $_POST['std_name'],
                $_POST['reg_no'],
                $_POST['roll_no'],
                $_POST['class'],
                $_POST['section'],
                $_POST['dob'],
                $_POST['mobile_no'],
                $_POST['fathers_name'],
                $_POST['mothers_name'],
                $_POST['address'],
                $_POST['status'],
                $final_picture,
                $_POST['id']
            );
            $stmt->execute();
            $stmt->close();       

            // Update play_tbl based on reg_no (adjust this if play_tbl uses id instead)
            $stmt2 = $conn->prepare("UPDATE $table_name SET roll_no=?, section=? WHERE reg_no=?");
            $stmt2->bind_param("sss",
                $_POST['roll_no'],
                $_POST['section'],
                $_POST['reg_no']
            );
            $stmt2->execute();
            
            $stmt2->close();
        }
        else{

 
            // Update std_tbl
$stmt = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
$stmt->bind_param("ssssssssssssi",
    $_POST['std_name'],
    $_POST['reg_no'],
    $_POST['roll_no'],
    $_POST['class'],
    $_POST['section'],
    $_POST['dob'],
    $_POST['mobile_no'],
    $_POST['fathers_name'],
    $_POST['mothers_name'],
    $_POST['address'],
    $_POST['status'],
    $final_picture,
    $_POST['id']
);
$stmt->execute();
$stmt->close();

// Insert into nursery_tbl
$current_date = date('Y-m-d');
$stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section,status) VALUES (?, ?, ?, ?, ?,?)");
$stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);
$stmt_play->execute();
$stmt_play->close();

// Delete from play_tbl
$stmt2 = $conn->prepare("DELETE FROM $table_name2 WHERE reg_no=?");
$stmt2->bind_param("s", $_POST['reg_no']);
$stmt2->execute();
$stmt2->close();

        }
    }

    $stmt_check->close();
}

//for a student admission/shift from one class to another class
elseif (isset($_POST['admission'])) {
    // Use uploaded picture if available, otherwise keep the existing one
    $final_picture = $picture_path ? $picture_path : $_POST['existing_picture'];
    
    $class = $conn->real_escape_string($_POST['class']);
    $table_name = $class . '_tbl';

    // First, check if the student exists
    $sql_regNo = "SELECT id, reg_no, class FROM std_tbl WHERE reg_no = ?";
    $stmt_check = $conn->prepare($sql_regNo);
    $stmt_check->bind_param("s", $_POST['reg_no']);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

 
    if ($row = $result->fetch_assoc()) {

    $class2 = $conn->real_escape_string($row['class']);
    $table_name2 = $class2 . '_tbl';
        // OPTIONAL: Validate class if needed
        if ($row['reg_no'] == $_POST['reg_no'] && $row['class'] == $_POST['class']) {

            // Update std_tbl
            $stmt = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
            $stmt->bind_param("ssssssssssssi",
                $_POST['std_name'],
                $_POST['reg_no'],
                $_POST['roll_no'],
                $_POST['class'],
                $_POST['section'],
                $_POST['dob'],
                $_POST['mobile_no'],
                $_POST['fathers_name'],
                $_POST['mothers_name'],
                $_POST['address'],
                $_POST['status'],
                $final_picture,
                $_POST['id']
            );
            $stmt->execute();
            $stmt->close();       

            // Update play_tbl based on reg_no (adjust this if play_tbl uses id instead)
            $stmt2 = $conn->prepare("UPDATE $table_name SET roll_no=?, section=? WHERE reg_no=?");
            $stmt2->bind_param("sss",
                $_POST['roll_no'],
                $_POST['section'],
                $_POST['reg_no']
            );
            $stmt2->execute();
            $stmt2->close();

        }
        else {
            // Update std_tbl
            $stmt = $conn->prepare("UPDATE std_tbl SET std_name=?, reg_no=?, roll_no=?, class=?, section=?, dob=?, mobile_no=?, fathers_name=?, mothers_name=?, address=?, status=?, picture=? WHERE id=?");
            $stmt->bind_param("ssssssssssssi",
                $_POST['std_name'],
                $_POST['reg_no'],
                $_POST['roll_no'],
                $_POST['class'],
                $_POST['section'],
                $_POST['dob'],
                $_POST['mobile_no'],
                $_POST['fathers_name'],
                $_POST['mothers_name'],
                $_POST['address'],
                $_POST['status'],
                $final_picture,
                $_POST['id']
            );
            $stmt->execute();
            $stmt->close();

            // Update previous table status to 'Inactive'
            $stmt = $conn->prepare("UPDATE $table_name2 SET status='Inactive' WHERE reg_no=?");
            $stmt->bind_param("s", $_POST['reg_no']);
            $stmt->execute();
            $stmt->close();

            // Insert into new class table
            $current_date = date('Y-m-d');
            $stmt_play = $conn->prepare("INSERT INTO $table_name (entry_date, reg_no, roll_no, class, section, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_play->bind_param("ssssss", $current_date, $_POST['reg_no'], $_POST['roll_no'], $_POST['class'], $_POST['section'], $_POST['status']);
            $stmt_play->execute();
            $stmt_play->close();
        }



    }

    $stmt_check->close();
}


}

if (isset($_GET['delete'])) {
    // Delete student
    $stmt = $conn->prepare("DELETE FROM std_tbl WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    $stmt->close();
    header('Location: std_reg.php');
    exit();
}

// Fetch all students
$students = $conn->query("SELECT * FROM std_tbl");
?>