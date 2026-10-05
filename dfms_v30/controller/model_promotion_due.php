<?php

class Model{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "dfms_db";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new mysqli($this->server, $this->username, $this->password, $this->db);
        } catch (\Throwable $th) {
            echo "Connection error " . $th->getMessage();
        }
    }

    public function fetch($table) {
        $data = [];
        $query = "SELECT * FROM $table order by id desc";
        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function date_range($start_date, $end_date, $table) {
        $data = [];
        if (isset($start_date) && isset($end_date) && isset($table)) {
            $query = "SELECT * FROM $table WHERE `date` BETWEEN '$start_date' AND '$end_date'";
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }
}
?>
