<?php
date_default_timezone_set("Asia/Kolkata");

// Enable detailed MySQL error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database config
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'pms_db');

class DB_con {

    function __construct() {
        $con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $this->dbh = $con;

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    // Username availability
    public function usernameavailblty($uname) {
        $result = mysqli_query($this->dbh, "SELECT `username` FROM `user` WHERE `username`='$uname'");
        return $result;
    }

    // Email availability
    public function uemailavailblty($email) {
        $result = mysqli_query($this->dbh, "SELECT `UserEmail` FROM `user` WHERE `UserEmail`='$email'");
        return $result;
    }

    // Registration function
    public function registration($fname, $uname, $uemail, $pasword) {
        $ret = mysqli_query($this->dbh, 
            "INSERT INTO `user` (`FullName`, `username`, `UserEmail`, `password`) 
            VALUES ('$fname', '$uname', '$uemail', '$pasword')"
        );
        return $ret;
    }

    // Sign-in function
    public function signin($uname, $pasword) {
        $result = mysqli_query($this->dbh, 
            "SELECT `id`, `FullName` FROM `user` 
            WHERE `username`='$uname' AND `password`='$pasword'"
        );
        return $result;
    }

    // Run base query and return result set
    public function runBaseQuery($query) {
        $result = mysqli_query($this->dbh, $query);
        $resultset = [];
        while($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        return !empty($resultset) ? $resultset : null;
    }

    // Return number of rows for a query
    public function numRows($query) {
        $result  = mysqli_query($this->dbh, $query);
        return mysqli_num_rows($result);
    }

    // Execute any query
    public function executeQuery($query) {
        // Uncomment below for debugging SQL errors
        // echo "Executing SQL: $query<br>";
        $result = mysqli_query($this->dbh, $query);
        return $result;
    }
}
?>
