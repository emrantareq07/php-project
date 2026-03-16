<?php

date_default_timezone_set("Asia/Dhaka");

// Include config to get database constants
require_once(dirname(__DIR__) . '/config/config.php');

class DB_con{
    public $dbh;
    
    function __construct(){
        // Use constants from config
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->dbh = $con;
        
        // Check connection
        if (mysqli_connect_errno()){
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }
        
        // Set charset to UTF-8
        mysqli_set_charset($this->dbh, "utf8");
    }

    // Check if username (emp_id) exists
    public function usernameavailblty($emp_id) {
        $emp_id = mysqli_real_escape_string($this->dbh, $emp_id);
        $result = mysqli_query($this->dbh, "SELECT emp_id FROM tbl_users WHERE emp_id='$emp_id'");
        return $result;
    }

    // Check if email exists
    public function uemailavailblty($email) {
        $email = mysqli_real_escape_string($this->dbh, $email);
        $result = mysqli_query($this->dbh, "SELECT email FROM tbl_users WHERE email='$email'");
        return $result;
    }

    // User registration
    public function registration($fullname, $emp_id, $email, $password){
        $fullname = mysqli_real_escape_string($this->dbh, $fullname);
        $emp_id = mysqli_real_escape_string($this->dbh, $emp_id);
        $email = mysqli_real_escape_string($this->dbh, $email);
        $password = mysqli_real_escape_string($this->dbh, md5($password));
        
        $ret = mysqli_query($this->dbh, "INSERT INTO tbl_users(fullname, emp_id, email, password) VALUES('$fullname', '$emp_id', '$email', '$password')");
        return $ret;
    }

    // User signin
    public function signin($emp_id, $password) {
        $emp_id = mysqli_real_escape_string($this->dbh, $emp_id);
        $password = mysqli_real_escape_string($this->dbh, $password);
        
        $query = "SELECT id, fullname, emp_id, email FROM tbl_users WHERE emp_id='$emp_id' AND password='$password'";
        $result = mysqli_query($this->dbh, $query);
        return $result;
    }

    // Run base query and return results as array
    function runBaseQuery($query) {
        $result = mysqli_query($this->dbh, $query);
        $resultset = array();
        
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $resultset[] = $row;
            }
        }
        return $resultset;
    }
    
    // Get number of rows
    function numRows($query) {
        $result = mysqli_query($this->dbh, $query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;   
    }
    
    // Execute query
    function executeQuery($query) {
        $result = mysqli_query($this->dbh, $query);
        return $result; 
    }
}
?>