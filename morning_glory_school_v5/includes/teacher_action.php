<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

// Check if user is logged in
if(!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
    die("Unauthorized access");
}

include_once(ROOT_PATH.'/libs/function.php');
$usercredentials = new DB_con();

// Fetch username from either session or cookies
$uname = "";
if (isset($_SESSION["uname"])) {
    $uname = $_SESSION['uname'];
} elseif (isset($_COOKIE['user_login'])) {
    $uname = $_COOKIE['user_login'];
}

// Fetch user data
if(!empty($uname)) {
    $query = "SELECT * FROM tblusers WHERE Username='$uname'";
    $result = $usercredentials->runBaseQuery($query);
    if(!empty($result)) {
        $uun = $result[0]['Username'];
        $uup = $result[0]['Password'];
    }
}

// Handle CRUD operations
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Teacher (only if edit_id is not set)
    if(isset($_POST['name']) && !isset($_POST['edit_id'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $designation = $conn->real_escape_string($_POST['designation']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $joining_date = $conn->real_escape_string($_POST['joining_date']);
        $last_edu_info = $conn->real_escape_string($_POST['last_edu_info'] ?? '');
        $university = $conn->real_escape_string($_POST['university'] ?? '');
        $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $maritial_status = $conn->real_escape_string($_POST['maritial_status'] ?? '');
        $religon = $conn->real_escape_string($_POST['religon'] ?? '');
        $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
        $address = $conn->real_escape_string($_POST['address'] ?? '');
        $nid = $conn->real_escape_string($_POST['nid'] ?? '');

        $image = "";
        if(!empty($_FILES['image']['name'])) {
            $image = time().'_'.basename($_FILES['image']['name']);
            $target = 'uploads/'.$image;
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        $sql = "INSERT INTO teacher_info (name, designation, dob, joining_date, last_edu_info, university, mobile, email, maritial_status, religon, blood_group, address, nid, image)
                VALUES ('$name','$designation','$dob','$joining_date','$last_edu_info','$university','$mobile','$email','$maritial_status','$religon','$blood_group','$address','$nid','$image')";

        if($conn->query($sql)) {
            echo "Teacher added successfully.";
        } else {
            echo "Error: ".$conn->error;
        }
        exit();
    }

 // ✅ Delete Teacher
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // ✅ Fetch and unlink the image before deleting the record
    $res = $conn->query("SELECT image FROM teacher_info WHERE id = $id");
    if ($res && $row = $res->fetch_assoc()) {
        $imageFile = $row['image'];
        $filepath = "uploads/" . $imageFile;
        if ($imageFile && file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // ✅ Delete the record
    if ($conn->query("DELETE FROM teacher_info WHERE id = $id")) {
        echo "Teacher deleted successfully.";
    } else {
        echo "Error deleting teacher: " . $conn->error;
    }

    exit();
}


    // Fetch single teacher
    if(isset($_POST['fetch_id'])) {
        $id = intval($_POST['fetch_id']);
        $result = $conn->query("SELECT * FROM teacher_info WHERE id=$id");
        echo json_encode($result->fetch_assoc());
        exit();
    }
// ✅ Update teacher (only if edit_id is set)
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $joining_date = $conn->real_escape_string($_POST['joining_date']);
    $last_edu_info = $conn->real_escape_string($_POST['last_edu_info'] ?? '');
    $university = $conn->real_escape_string($_POST['university'] ?? '');
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $maritial_status = $conn->real_escape_string($_POST['maritial_status'] ?? '');
    $religon = $conn->real_escape_string($_POST['religon'] ?? '');
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $nid = $conn->real_escape_string($_POST['nid'] ?? '');

    // ✅ Handle image replacement safely
    if (!empty($_FILES['image']['name'])) {
        // Fetch existing image
        $res = $conn->query("SELECT image FROM teacher_info WHERE id = $id");
        if ($res && $row = $res->fetch_assoc()) {
            $oldImage = $row['image'];
            $oldPath = 'uploads/' . $oldImage;
            if ($oldImage && file_exists($oldPath)) {
                unlink($oldPath); // ✅ Delete previous image
            }
        }

        // Upload new image
        $update_image = time() . '_' . basename($_FILES['image']['name']);
        $target = 'uploads/' . $update_image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Update image field in DB
        $conn->query("UPDATE teacher_info SET image = '$update_image' WHERE id = $id");
    }

    // ✅ Update other fields
    $sql = "UPDATE teacher_info SET 
            name='$name',
            designation='$designation',
            dob='$dob',
            joining_date='$joining_date',
            last_edu_info='$last_edu_info',
            university='$university',
            mobile='$mobile',
            email='$email',
            maritial_status='$maritial_status',
            religon='$religon',
            blood_group='$blood_group',
            address='$address',
            nid='$nid'
            WHERE id = $id";

    if ($conn->query($sql)) {
        echo "Teacher updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    exit();
}

}