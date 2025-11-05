<?php
// include('../db/db.php');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $id = $_POST['id'];
//     $viva_marks = $_POST['viva_marks'];
//     $remarks = $_POST['candidate_remarks'];

//     $stmt = $conn->prepare("UPDATE candidates_tbl SET viva_marks=?, remarks=? WHERE id=?");
//     $stmt->bind_param("dsi", $viva_marks, $remarks, $id);
//     if ($stmt->execute()) {
//         echo "<script>alert('Viva Marks updated successfully');window.location='dashboard.php';</script>";
//         exit;
//     } else {
//         echo "<script>alert('Failed to update marks');</script>";
//     }
// }
?>

<?php
// include('../db/db.php');

// $id = $_POST['id'];
// $viva_marks = $_POST['viva_marks'];
// $remarks = $_POST['candidate_remarks'];

// $query = "UPDATE candidates_tbl SET viva_marks='$viva_marks',remarks='$remarks', updated_at=NOW() WHERE id='$id'";
// if (mysqli_query($conn, $query)) {
//     echo "Viva marks updated successfully!";
// } else {
//     echo "Error updating marks: " . mysqli_error($conn);
// }
?>
<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    echo "Session expired. Please log in again.";
    exit;
}

$username = $_SESSION['username'];  // examiner's username
$committee = $_SESSION['committee_name'] ?? ''; // optional
$id = intval($_POST['id']);
$viva_marks = is_numeric($_POST['viva_marks']) ? $_POST['viva_marks'] : 0;
$remarks = $_POST['candidate_remarks'] ?? '';

$query = "INSERT INTO viva_marks_tbl 
            (candidate_id, examiner_username, viva_marks, remarks, committe_name)
          VALUES (?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE 
              viva_marks = VALUES(viva_marks), 
              remarks = VALUES(remarks), 
              updated_at = NOW()";

$stmt = $conn->prepare($query);
$stmt->bind_param("isdss", $id, $username, $viva_marks, $remarks, $committee);

if ($stmt->execute()) {
    echo "Viva marks saved successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>


