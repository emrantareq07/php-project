<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");

// Authentication check
if (!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
    header("Location: login.php");
    exit();
}

// Validate ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);
$msg = "";

// Fetch existing notice data
$noticeData = null;
$stmt = $conn->prepare("SELECT * FROM notice_board WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Notice not found.");
}

$noticeData = $result->fetch_assoc();
$stmt->close();

// Function to generate random string
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notice = trim($_POST['notice']);
    $date = $_POST['date'];
    $fileUpdated = false;
    $fileName = $noticeData['filename']; // Default to existing filename

    // Validate required fields
    if (empty($notice) || empty($date)) {
        $msg = "<div class='alert alert-danger'>Please fill all required fields.</div>";
    } else {
        // Handle file upload if present
        if (isset($_FILES['pdf_file']['name']) && $_FILES['pdf_file']['error'] === 0) {
            $fileType = $_FILES["pdf_file"]["type"];
            $fileSize = $_FILES["pdf_file"]["size"];
            $fileTmp = $_FILES["pdf_file"]["tmp_name"];
            $originalName = basename($_FILES["pdf_file"]["name"]);
            
            // Generate new filename with random string
            $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
            $fileBaseName = pathinfo($originalName, PATHINFO_FILENAME);
            $randomString = generateRandomString(6);
            $fileName = $fileBaseName . '_' . $randomString . '.' . $fileExt;

            if ($fileType != "application/pdf") {
                $msg = "<div class='alert alert-danger'>Only PDF files are allowed.</div>";
            } elseif ($fileSize > 1000000) {
                $msg = "<div class='alert alert-danger'>File size should be less than 1MB.</div>";
            } else {
                // Delete old file if it exists
                if (!empty($noticeData['filename']) && file_exists("./pdf/" . $noticeData['filename'])) {
                    unlink("./pdf/" . $noticeData['filename']);
                }
                
                // Upload new file
                if (move_uploaded_file($fileTmp, "./pdf/" . $fileName)) {
                    $fileUpdated = true;
                } else {
                    $msg = "<div class='alert alert-danger'>Failed to upload file.</div>";
                }
            }
        }

        // Only proceed if no errors
        if (empty($msg)) {
            $query = $fileUpdated
                ? "UPDATE notice_board SET notice=?, dated=?, filename=? WHERE id=?"
                : "UPDATE notice_board SET notice=?, dated=? WHERE id=?";

            $stmt = $conn->prepare($query);
            
            if ($fileUpdated) {
                $stmt->bind_param("sssi", $notice, $date, $fileName, $id);
            } else {
                $stmt->bind_param("ssi", $notice, $date, $id);
            }

            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>Notice updated successfully.</div>";
                // Refresh notice data after update
                $noticeData['notice'] = $notice;
                $noticeData['dated'] = $date;
                if ($fileUpdated) {
                    $noticeData['filename'] = $fileName;
                }
            } else {
                $msg = "<div class='alert alert-danger'>Error updating notice.</div>";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Notice</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Edit Notice</h4>
                </div>
                <div class="card-body">
                    <?php echo $msg; ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label class="form-label">Notice Title</label>
                            <textarea name="notice" class="form-control" required rows="3"><?php echo htmlspecialchars($noticeData['notice']); ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required value="<?php echo htmlspecialchars($noticeData['dated']); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Current File</label><br>
                            <?php if (!empty($noticeData['filename'])): ?>
                                <a href="pdf/<?php echo htmlspecialchars($noticeData['filename']); ?>" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-file-pdf me-1"></i><?php echo htmlspecialchars($noticeData['filename']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No file attached</span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Replace PDF File (optional)</label>
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                            <small class="text-muted">Max size: 1MB (Will replace existing file)</small>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-save me-1"></i>Update Notice
                            </button>
                            <a href="add_new_notice.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>