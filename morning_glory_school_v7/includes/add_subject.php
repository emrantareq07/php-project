<?php
// Database connection
$host = 'localhost';
$dbname = 'morning_glory_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize variables
$subject = '';
$class = '';
$sub_code = '';
$id = 0;
$edit_mode = false;

// CRUD Operations
if (isset($_POST['save'])) {
    // Create operation
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $sub_code = $_POST['sub_code'];
    
    $stmt = $pdo->prepare("INSERT INTO subjects (subject, class, sub_code) VALUES (?, ?, ?)");
    $stmt->execute([$subject, $class, $sub_code]);
    
    $_SESSION['message'] = "Record has been saved!";
    $_SESSION['msg_type'] = "success";
    
    header("location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    // Delete operation
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "danger";
    
    header("location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['edit'])) {
    // Prepare for edit operation
    $id = $_GET['edit'];
    $edit_mode = true;
    
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    
    $subject = $row['subject'];
    $class = $row['class'];
    $sub_code = $row['sub_code'];
}

if (isset($_POST['update'])) {
    // Update operation
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $sub_code = $_POST['sub_code'];
    
    $stmt = $pdo->prepare("UPDATE subjects SET subject = ?, class = ?, sub_code = ? WHERE id = ?");
    $stmt->execute([$subject, $class, $sub_code, $id]);
    
    $_SESSION['message'] = "Record has been updated!";
    $_SESSION['msg_type'] = "info";
    
    header("location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch all records
$stmt = $pdo->query("SELECT * FROM subjects");
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-1">
        <h2 class="text-center mb-4 text-muted text-uppercase">Subject Management</h2>
        
        <!-- Display messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
            ?>
        <?php endif; ?>
        
        <!-- Form -->
        <div class="card mb-4">
            <div class="card-header">
                <?= $edit_mode ? 'Edit Subject' : 'Add New Subject' ?>
            </div>
            <div class="card-body">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               value="<?= $subject ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class" required>
                            <option value="">Select Class</option>
                            <optgroup label="Pre-School">
                                <option value="Play" <?= $class == 'Play' ? 'selected' : '' ?>>Play Group</option>
                                <option value="Pre-Nursery" <?= $class == 'Pre-Nursery' ? 'selected' : '' ?>>Pre-Nursery</option>
                                <option value="Nursery" <?= $class == 'Nursery' ? 'selected' : '' ?>>Nursery</option>
                            </optgroup>
                            <optgroup label="Kindergarten">
                                <option value="KG" <?= $class == 'KG' ? 'selected' : '' ?>>KG (Kindergarten)</option>
                                <option value="LKG" <?= $class == 'LKG' ? 'selected' : '' ?>>LKG (Lower KG)</option>
                                <option value="UKG" <?= $class == 'UKG' ? 'selected' : '' ?>>UKG (Upper KG)</option>
                            </optgroup>
                            <optgroup label="Primary School">
                                <option value="One" <?= $class == 'One' ? 'selected' : '' ?>>One</option>
                                <option value="Two" <?= $class == 'Two' ? 'selected' : '' ?>>Two</option>
                                <option value="Three" <?= $class == 'Three' ? 'selected' : '' ?>>Three</option>
                                <option value="Four" <?= $class == 'Four' ? 'selected' : '' ?>>Four</option>
                                <option value="Five" <?= $class == 'Five' ? 'selected' : '' ?>>Five</option>
                            </optgroup>
                            <!-- <optgroup label="Secondary School">
                                <option value="Class 6" <?= $class == 'Class 6' ? 'selected' : '' ?>>Class 6</option>
                                <option value="Class 7" <?= $class == 'Class 7' ? 'selected' : '' ?>>Class 7</option>
                                <option value="Class 8" <?= $class == 'Class 8' ? 'selected' : '' ?>>Class 8</option>
                            </optgroup>
                            <optgroup label="High School">
                                <option value="Class 9" <?= $class == 'Class 9' ? 'selected' : '' ?>>Class 9</option>
                                <option value="Class 10" <?= $class == 'Class 10' ? 'selected' : '' ?>>Class 10</option>
                            </optgroup>
                            <optgroup label="Higher Secondary">
                                <option value="Class 11" <?= $class == 'Class 11' ? 'selected' : '' ?>>Class 11</option>
                                <option value="Class 12" <?= $class == 'Class 12' ? 'selected' : '' ?>>Class 12</option>
                            </optgroup> -->
                        </select>
                    </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sub_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="sub_code" name="sub_code" 
                               value="<?= $sub_code ?>" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <?php if ($edit_mode): ?>
                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancel</a>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success" name="save">Save</button>
                        <?php endif; ?>
                        <a href="../dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                Subject List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Subject Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['subject'] ?></td>
                                <td><?= $row['class'] ?></td>
                                <td><?= $row['sub_code'] ?></td>
                                <td>
                                    <a href="<?= $_SERVER['PHP_SELF'] ?>?edit=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= $_SERVER['PHP_SELF'] ?>?delete=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Close session if you opened it
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}
?>