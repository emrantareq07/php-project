<?php

class Model{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "pmis_db";
    private $conn;

    public function __construct()    {
        try {
            $this->conn = new mysqli($this->server, $this->username, $this->password, $this->db);
        } catch (\Throwable $th) {
            //throw $th;
            echo "Connection error " . $th->getMessage();
        }
    }

    public function fetch(){
        $data = [];

        // $query = "SELECT * FROM student";
         $query="SELECT * from basic_info u,  job_desc j where u.emp_id=j.emp_id";
        // $query="SELECT * from users";
        if ($sql = $this->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function date_range($start_date, $end_date){
        $data = [];

        if (isset($start_date) && isset($end_date)) {
            // $query = "SELECT * FROM `student` WHERE `created_at` > '$start_date' AND `created_at` < '$end_date'";
            $query = "SELECT * FROM  basic_info u,  job_desc j where u.emp_id=j.emp_id AND `last_promo_date` >= '$start_date' AND `last_promo_date` <= '$end_date'";
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }
}