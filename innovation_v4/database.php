<?php 
class Database {
	private $host = "localhost";
	private $user = "root";
	private $password = "";
	private $database = "innovation_db";
	
	function runQuery($sql) {
		$conn = new mysqli($this->host,$this->user,$this->password,$this->database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $result = $conn->query($sql);
    if ($result->mysqli_num_rows > 0) {
      while($row = $result->mysqli_fetch_assoc()) {
          $resultset[] = $row;
      }
    }
    $conn->close();

		if(!empty($resultset))
			return $resultset;
	}
}

?>