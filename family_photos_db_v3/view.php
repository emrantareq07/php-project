<?php
// view.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT filename, photo, created_at FROM photos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die('Photo not found.');
}
$row = $res->fetch_assoc();
$fname = htmlspecialchars($row['filename']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $fname ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>img.full-photo { max-width:100%; height:auto; }</style>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm">
        <div class="card-body text-center p-4">
          <h3 class="mb-3"><?= $fname ?></h3>
          <p class="text-muted small mb-3">Uploaded: <?= htmlspecialchars($row['created_at']) ?></p>

          <div class="mb-3">
            <img src="data:image/jpeg;base64,<?= base64_encode($row['photo']); ?>" alt="<?= $fname ?>" class="full-photo rounded">
          </div>

          <div class="d-flex justify-content-center gap-2">
            <a href="download.php?id=<?= $id ?>" class="btn btn-success">Download</a>
            <a href="list.php" class="btn btn-outline-secondary">Back to Gallery</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
