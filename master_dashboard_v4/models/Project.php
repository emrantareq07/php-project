<?php
// models/Project.php

require_once 'config/database.php';

class Project {
    private $conn;
    private $table_name = "projects";

    // Object properties
    public $id;
    public $project_name;
    public $project_url;
    public $category;
    public $status;
    public $icon_color;
    public $description;
    public $created_at;
    public $updated_at;
    public $is_active;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new project
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    project_name = :project_name,
                    project_url = :project_url,
                    category = :category,
                    status = :status,
                    icon_color = :icon_color,
                    description = :description";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->project_name = htmlspecialchars(strip_tags($this->project_name));
        $this->project_url = htmlspecialchars(strip_tags($this->project_url));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->icon_color = htmlspecialchars(strip_tags($this->icon_color));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bindParam(":project_name", $this->project_name);
        $stmt->bindParam(":project_url", $this->project_url);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":icon_color", $this->icon_color);
        $stmt->bindParam(":description", $this->description);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all projects
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single project
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->project_name = $row['project_name'];
            $this->project_url = $row['project_url'];
            $this->category = $row['category'];
            $this->status = $row['status'];
            $this->icon_color = $row['icon_color'];
            $this->description = $row['description'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Update project
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    project_name = :project_name,
                    project_url = :project_url,
                    category = :category,
                    status = :status,
                    icon_color = :icon_color,
                    description = :description
                  WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->project_name = htmlspecialchars(strip_tags($this->project_name));
        $this->project_url = htmlspecialchars(strip_tags($this->project_url));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->icon_color = htmlspecialchars(strip_tags($this->icon_color));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":project_name", $this->project_name);
        $stmt->bindParam(":project_url", $this->project_url);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":icon_color", $this->icon_color);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete project (soft delete)
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET is_active = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get project statistics
    public function getStats() {
        $stats = [];
        
        // Total projects
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Active projects
        $query = "SELECT COUNT(*) as active FROM " . $this->table_name . " WHERE status = 'Active' AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['active'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];

        // Maintenance projects
        $query = "SELECT COUNT(*) as maintenance FROM " . $this->table_name . " WHERE status = 'Maintenance' AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['maintenance'] = $stmt->fetch(PDO::FETCH_ASSOC)['maintenance'];

        // By category
        $query = "SELECT category, COUNT(*) as count FROM " . $this->table_name . " WHERE is_active = 1 GROUP BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    // Search projects
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE 
                    (project_name LIKE ? OR project_url LIKE ? OR description LIKE ?)
                    AND is_active = 1
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->execute();

        return $stmt;
    }

    // Filter by category
    public function filterByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE category = ? AND is_active = 1
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();

        return $stmt;
    }
}
?>