<?php
include('database.php');
include('function.php');

if(isset($_POST["user_id"])) {
    $output = array();
    $user_id = $_POST["user_id"];

    // Fetch the current row
    $statement = $connection->prepare("SELECT * FROM basic_info WHERE id = :user_id LIMIT 1");
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $row = $statement->fen b    tch(PDO::FETCH_ASSOC);

    // Get the current seniority_no and name
    $current_seniority_no = $row["seniority_no"];
    $current_name = $row["name"];

    // Update the current row with incremented seniority_no
    $new_seniority_no = $current_seniority_no + 1;
    $statement = $connection->prepare("UPDATE basic_info SET seniority_no = :new_seniority_no WHERE id = :user_id");
    $statement->bindParam(':new_seniority_no', $new_seniority_no);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();

    // Update subsequent rows with incremented seniority_no
    $statement = $connection->prepare("UPDATE basic_info SET seniority_no = seniority_no + 1 WHERE seniority_no > :current_seniority_no");
    $statement->bindParam(':current_seniority_no', $current_seniority_no);
    $statement->execute();

    // Fetch the updated row
    $statement = $connection->prepare("SELECT * FROM basic_info WHERE id = :user_id LIMIT 1");
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    $output["emp_id"] = $row["emp_id"];
    $output["name"] = $row["name"];
    $output["seniority_no"] = $row["seniority_no"];
    $output["designation"] = $row["designation"];
    $output["division"] = $row["division"];
    $output["section"] = $row["section"];
    $output["job_status"] = $row["job_status"];
    $output["mobile_no"] = $row["mobile_no"];
    $output["email"] = $row["email"];
    
    if($row["image"] != '') {
        $output['user_image'] = '<img src="images/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
    } else {
        $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
    }

    echo json_encode($output);
}
?>