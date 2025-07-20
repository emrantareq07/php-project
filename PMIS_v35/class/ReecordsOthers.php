<?php
session_start();

class ReecordsOthers {
  
  private $recordsTable = 'other_qualification_info';
  public $id;
  public $degree_name;
  public $subject;
  public $university;
  public $country;
  public $course_duration;
  // public $user_id;
  public $emp_id;
  private $conn;

  public function __construct($db){
    $this->conn = $db;
  }    

  public function listRecords(){
  	$sqlQuery = "SELECT * FROM " . $this->recordsTable . " WHERE emp_id = ?"; // Modify the query to fetch records based on emp_id
      
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
      // $rows[] = $record['user_id'];
      $rows[] = $record['emp_id'];
      $rows[] = $record['degree_name'];
      $rows[] = $record['subject'];
      $rows[] = $record['university'];
      $rows[] = $record['country'];
      $rows[] = $record['course_duration'];
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
  
  public function getRecord(){
    if($this->id) {
      $sqlQuery = "
        SELECT * FROM ".$this->recordsTable." 
        WHERE id = ?";      
      $stmt = $this->conn->prepare($sqlQuery);
      $stmt->bind_param("i", $this->id);
      $stmt->execute();
      $result = $stmt->get_result();
      $record = $result->fetch_assoc();
      echo json_encode($record);
    }
  }

  public function updateRecord(){
    
    if($this->id) {
      
      $stmt = $this->conn->prepare("
        UPDATE ".$this->recordsTable."
        SET degree_name = ?, subject = ?, university = ?, country = ?, course_duration = ?
        WHERE id = ?");
 
      $this->id = htmlspecialchars(strip_tags($this->id));
      $this->degree_name = htmlspecialchars(strip_tags($this->degree_name));
      $this->subject = htmlspecialchars(strip_tags($this->subject));
      $this->university = htmlspecialchars(strip_tags($this->university));
      $this->country = htmlspecialchars(strip_tags($this->country));
      
      $this->course_duration = htmlspecialchars(strip_tags($this->course_duration));
      $stmt->bind_param("sssssi", $this->degree_name, $this->subject, $this->university, $this->country, $this->course_duration, $this->id);
      
      if($stmt->execute()){
        return true;
      }
      
    }  
  }

 public function addRecord(){
    if($this->degree_name) {
        $stmt = $this->conn->prepare("
            INSERT INTO ".$this->recordsTable."(`emp_id`,`degree_name`, `subject`, `university`, `country`, `course_duration`)
            VALUES(?,?,?,?,?,?)");

        $this->degree_name = htmlspecialchars(strip_tags($this->degree_name));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->university = htmlspecialchars(strip_tags($this->university));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->course_duration = htmlspecialchars(strip_tags($this->course_duration));
        $this->emp_id = htmlspecialchars(strip_tags($this->emp_id));

        $stmt->bind_param("ssssss", $this->emp_id, $this->degree_name, $this->subject, $this->university, $this->country, $this->course_duration);

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
