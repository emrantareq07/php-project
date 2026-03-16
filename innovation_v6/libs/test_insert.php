<?php
require_once("../config/config.php");
require_once("../db/db.php");

echo "<h2>Testing Direct Insert</h2>";

// Test tbl_users insert
$test_emp_id = "TEST" . rand(100, 999);
$test_password = md5('1234');

$user_test = "INSERT INTO tbl_users (emp_id, fullname, email, password, role) 
              VALUES ('$test_emp_id', 'Test User', 'test@test.com', '$test_password', 'user')";

echo "<h3>Testing tbl_users:</h3>";
echo "<p>SQL: $user_test</p>";

if(mysqli_query($conn, $user_test)) {
    echo "<p style='color:green'>✓ Test user inserted successfully with ID: " . mysqli_insert_id($conn) . "</p>";
    
    // Test tbl_innovation insert
    $innovation_test = "INSERT INTO tbl_innovation (emp_id, fullname, fiscal_year, title_of_idea, identify_prob_desc, prob_sol_plan, prob_sol_desc) 
                        VALUES ('$test_emp_id', 'Test User', '২০২৪-২০২৫', 'Test Title', 'Test Problem', 'Test Plan', 'Test Solution')";
    
    echo "<h3>Testing tbl_innovation:</h3>";
    echo "<p>SQL: $innovation_test</p>";
    
    if(mysqli_query($conn, $innovation_test)) {
        echo "<p style='color:green'>✓ Test innovation inserted successfully with ID: " . mysqli_insert_id($conn) . "</p>";
    } else {
        echo "<p style='color:red'>✗ Innovation insert failed: " . mysqli_error($conn) . "</p>";
    }
    
} else {
    echo "<p style='color:red'>✗ User insert failed: " . mysqli_error($conn) . "</p>";
}

// Show table structure
echo "<h3>Table Structure:</h3>";
$tables = ['tbl_users', 'tbl_innovation'];

foreach($tables as $table) {
    $result = mysqli_query($conn, "DESCRIBE $table");
    if($result) {
        echo "<h4>$table</h4>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>Table $table does not exist</p>";
    }
}
?>