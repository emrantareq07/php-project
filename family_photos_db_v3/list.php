<?php
// list.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$msg = '';
if (isset($_GET['msg'])) {
    $msg = '<div class="alert alert-success">' . htmlspecialchars($_GET['msg']) . '</div>';
}
if (isset($_GET['err'])) {
    $msg = '<div class="alert alert-danger">' . htmlspecialchars($_GET['err']) . '</div>';
}

$result = $conn->query("SELECT id, filename, photo, created_at FROM photos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Gallery</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .photo-card img { height: 200px; object-fit: cover; border-radius: .5rem; }
  </style>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <h2 class="text-center mb-3">📂 Photo Gallery</h2>
  <?= $msg ?>

  <div class="text-center mb-4">
    <a href="upload.php" class="btn btn-primary">⬆ Upload More</a>
  </div>

  <?php if ($result->num_rows > 0): ?>
    <div class="row g-4">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php $id = (int)$row['id']; $fname = htmlspecialchars($row['filename']); ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card shadow-sm photo-card h-100">
            <img src="data:image/jpeg;base64,<?= base64_encode($row['photo']); ?>" class="card-img-top" alt="<?= $fname ?>">
            <div class="card-body text-center d-flex flex-column">
              <p class="card-text small text-muted mb-2"><?= $fname ?></p>
              <div class="mt-auto d-flex gap-2 justify-content-center">
                <a href="view.php?id=<?= $id ?>" class="btn btn-sm btn-outline-primary">View</a>
                <a href="download.php?id=<?= $id ?>" class="btn btn-sm btn-outline-success">Download</a>

                <!-- Delete button triggers modal -->
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delModal<?= $id ?>">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="delModal<?= $id ?>" tabindex="-1" aria-labelledby="delModalLabel<?= $id ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" action="delete.php">
                <div class="modal-header">
                  <h5 class="modal-title" id="delModalLabel<?= $id ?>">Delete Photo</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete <strong><?= $fname ?></strong>?
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Yes, delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">No photos uploaded yet.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
