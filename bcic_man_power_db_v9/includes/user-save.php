<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$full_name = $_POST['full_name'];
$factory_name = $_POST['factory_name'];
$designation = $_POST['designation'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'];

if ($id == "") {

    // ADD NEW USER
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, full_name, factory_name, designation, email, phone, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $username, $password, $full_name, $factory_name, $designation, $email, $phone, $role);
    $stmt->execute();

} else {

    // UPDATE USER
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, password=?, full_name=?, factory_name=?, designation=?, email=?, phone=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $username, $password, $full_name, $factory_name, $designation, $email, $phone, $role, $id);
    } else {
        $sql = "UPDATE users SET username=?, full_name=?, factory_name=?, designation=?, email=?, phone=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $username, $full_name, $factory_name, $designation, $email, $phone, $role, $id);
    }

    $stmt->execute();
}

header("Location: manage_users.php");
exit;
?>
