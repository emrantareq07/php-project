<?php
require_once("includes/config.php");

if (!empty($_POST["field"]) && !empty($_POST["value"])) {
    $field = $_POST["field"];
    $value = $_POST["value"];
    
    if ($field === "emailid") {
        // Email validation and check
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            echo "<span style='color:red'>Invalid email format.</span>";
        } else {
            $sql = "SELECT EmailId FROM tblstudents WHERE EmailId = :value";
            $query = $dbh->prepare($sql);
            $query->bindParam(':value', $value, PDO::PARAM_STR);
            $query->execute();
            
            echo $query->rowCount() > 0 
                ? "<span style='color:red'>Email already exists.</span>"
                : "<span style='color:green'>Email available.</span>";
        }
    } 
    elseif ($field === "StudentId") {
        // Student ID check
        $sql = "SELECT StudentId FROM tblstudents WHERE StudentId = :value";
        $query = $dbh->prepare($sql);
        $query->bindParam(':value', $value, PDO::PARAM_STR);
        $query->execute();
        
        echo $query->rowCount() > 0 
            ? "<span style='color:red'>Student ID already exists.</span>"
            : "<span style='color:green'>Student ID available.</span>";
    }
}
?>