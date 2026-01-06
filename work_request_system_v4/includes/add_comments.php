<?php
// ===============================
// SESSION & CONFIG
// ===============================
session_name('factory_work_request_db');

require_once '../db/config.php';

// Auth check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}
$request_id    = intval($_GET['id'] ?? 0);
$user_id       = $_SESSION['user_id'];
$user_role     = $_SESSION['role'];
$user_division = $_SESSION['division'] ?? '';
$user_section  = $_SESSION['section'] ?? '';

// ===============================
// GET REQUEST ID
// ===============================
//$request_id = intval($_GET['id'] ?? 0);

if ($request_id <= 0) {
    die('Invalid request ID');
}

// ===============================
// FETCH REQUEST (NO ROLE FILTER)
// ===============================
$sql = "SELECT * FROM work_request_tbl WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request) {
    die('Request not found');
}

// ===============================
// UPDATE REMARKS
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $remarks = trim($_POST['remarks'] ?? '');

    if ($remarks === '') {
        $error = "Remarks required";
    } else {

        if($_SESSION['role']=='user'){

            $update_sql = "UPDATE work_request_tbl
                       SET remarks = ?, updated_at = NOW()
                       WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $remarks, $request_id);
        $update_stmt->execute();
        $update_stmt->close();        

        header("Location: my_work_requests_list.php");
        exit;
        }
        else{

        $update_sql = "UPDATE work_request_tbl
                       SET w_com_div_remarks = ?, updated_at = NOW()
                       WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $remarks, $request_id);
        $update_stmt->execute();
        $update_stmt->close();        

        header("Location: incoming_work_request.php");
        exit;
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Remarks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Update Remarks</h4>
            <small>Request #<?= $request_id ?></small>
        </div>

        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="6" required><?= htmlspecialchars($_POST['remarks'] ?? $request['remarks']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="incoming_work_request.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const t = document.getElementById('remarks');
t.style.height = 'auto';
t.style.height = t.scrollHeight + 'px';
t.addEventListener('input', () => {
    t.style.height = 'auto';
    t.style.height = t.scrollHeight + 'px';
});
</script>

</body>
</html>
