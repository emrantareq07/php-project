<?php
session_start();

class Records {
  private $recordsTable = 'childs_info';
  public $id;
  public $user_id;
  public $emp_id;
  public $name;
  public $dob;
  public $gender;
  public $institute;
  public $class;
  private $conn;

  public function __construct($db){
    $this->conn = $db;
  }    

  public function listRecords(){
  	$sqlQuery = "SELECT * FROM " . $this->recordsTable . " WHERE emp_id = ?"; // Modify the query to fetch 
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("s", $_SESSION['emp_id']);
    $stmt->execute();
    $result = $stmt->get_result();  
    
    $stmtTotal = $this->conn->prepare("SELECT * FROM ".$this->recordsTable);
    $stmtTotal->execute();
    $allResult = $stmtTotal->get_result();

    $allRecords = $result->num_rows;
    
    $displayRecords = $result->num_rows;
    $records = array();    
    while ($record = $result->fetch_assoc()) {           
      $rows = array();      
      $rows[] = $record['id'];
      $rows[] = $record['user_id'];
      $rows[] = $record['emp_id'];
      $rows[] = $record['name'];
      $rows[] = $record['dob'];
      $rows[] = $record['gender'];
      $rows[] = $record['institute'];
      $rows[] = $record['class'];
      
      $rows[] = '<button type="button" name="update" id="'.$record["id"].'" class="btn btn-warning btn-xs update">Update</button>';
      $rows[] = '<button type="button" name="delete" id="'.$record["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
      $records[] = $rows;
    }

    $output = array(
    "draw"  =>  intval($_POST["draw"]),
    "iTotalRecords" =>  $displayRecords,
    "iTotalDisplayRecords" =>  $allRecords,
    "data"  =>  $records,
  );

  echo json_encode($output);
}
  
  // public function getRecord(){
  //   if($this->id) {
  //     $sqlQuery = "
  //       SELECT * FROM ".$this->recordsTable." 
  //       WHERE id = ?";      
  //     $stmt = $this->conn->prepare($sqlQuery);
  //     $stmt->bind_param("i", $this->id);
  //     $stmt->execute();
  //     $result = $stmt->get_result();
  //     $record = $result->fetch_assoc();
  //     echo json_encode($record);
  //   }
  // }
public function getRecord(){
    if($this->id) {
        $sqlQuery = "SELECT * FROM ".$this->recordsTable." WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();
        echo json_encode($record);
    }
}
  public function updateRecord() {
    if ($this->id) {
        // Format current date
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql = "UPDATE job_desc SET last_update_at = ? WHERE emp_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $today, $this->emp_id);
        $stmt->execute();
        $stmt->close();

        // Update recordsTable
        $sql = "UPDATE " . $this->recordsTable . " SET name = ?, dob = ?, gender = ?, institute = ?, class = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->institute = htmlspecialchars(strip_tags($this->institute));
        $this->class = htmlspecialchars(strip_tags($this->class));

        $stmt->bind_param("sssssi", $this->name, $this->dob, $this->gender, $this->institute, $this->class, $this->id);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } 
        // else {
        //     $stmt->close();
        //     return false;
        //}
    }
    //return false;
}

  public function addRecord(){
    
    if($this->name) {

      $stmt = $this->conn->prepare("
        INSERT INTO ".$this->recordsTable."(`emp_id`,`name`, `dob`, `gender`, `institute`, `class`)
        VALUES(?,?,?,?,?,?)");
      
      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->dob = htmlspecialchars(strip_tags($this->dob));
      $this->gender = htmlspecialchars(strip_tags($this->gender));
      $this->institute = htmlspecialchars(strip_tags($this->institute));
      $this->class = htmlspecialchars(strip_tags($this->class));
       // $this->user_id = htmlspecialchars(strip_tags($this->user_id));
       $this->emp_id = htmlspecialchars(strip_tags($this->emp_id));
      
      //$stmt->bind_param("issss", $this->user_id, $this->emp_id, $this->name, $this->dob, $this->gender);
       $stmt->bind_param("ssssss", $this->emp_id, $this->name, $this->dob, $this->gender, $this->institute, $this->class);
      
      if($stmt->execute()){
        return true;
      }    
    }
  }

  public function deleteRecord(){
    if($this->id) {

      $stmt = $this->conn->prepare("
        DELETE FROM ".$this->recordsTable."
        WHERE id = ?");

      $this->id = htmlspecialchars(strip_tags($this->id));

      $stmt->bind_param("i", $this->id);

      if($stmt->execute()){
        return true;
      }
    }
  }
}
?>
