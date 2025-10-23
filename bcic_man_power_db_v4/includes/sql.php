
// Create user table if not exists
$createTableQuery = "
CREATE TABLE IF NOT EXISTS `$table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reports_month VARCHAR(50) NOT NULL,
    employee_type ENUM('officer','staff','worker') NOT NULL,
    factory_name VARCHAR(255),
    division VARCHAR(255),
    department VARCHAR(255),
    designation VARCHAR(255),
    grade_class VARCHAR(255),
    grade VARCHAR(100),
    male INT DEFAULT 0,
    female INT DEFAULT 0,
    sanctioned_post INT DEFAULT 0,
    filled_post INT DEFAULT 0,
    vacant_post INT DEFAULT 0,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createTableQuery);