<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $id = $_POST['id'] ?? '';
    $entry_date = $_POST['entry_date'];
    $roll_no = $_POST['roll_no'];
    $std_name = $_POST['std_name'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $exam_fee = $_POST['exam_fee'];
    $total = $_POST['total'];
    $remarks = $_POST['remarks'] ?? '';

    try {
        if (empty($id)) {
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO student_exam_fees 
                                  (entry_date, roll_no, std_name, class, section, month, year, exam_fee, total, remarks) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$entry_date, $roll_no, $std_name, $class, $section, $month, $year, $exam_fee, $total, $remarks]);
            
            $_SESSION['message'] = "Record added successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            // Update existing record
            $stmt = $pdo->prepare("UPDATE student_exam_fees SET 
                                  entry_date = ?, roll_no = ?, std_name = ?, class = ?, section = ?, 
                                  month = ?, year = ?, exam_fee = ?, total = ?, remarks = ? 
                                  WHERE id = ?");
            $stmt->execute([$entry_date, $roll_no, $std_name, $class, $section, $month, $year, $exam_fee, $total, $remarks, $id]);
            
            $_SESSION['message'] = "Record updated successfully!";
            $_SESSION['msg_type'] = "success";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }

    header("location: index.php");
    exit();
}

// Delete operation
if (isset($_GET['delete'])) {
    try {
        $id = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM student_exam_fees WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = "Record deleted successfully!";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    
    header("location: index.php");
    exit();
}
?>