<?php
include 'db.php';

// Pagination setup (optional if needed)
$limit = 12; // photos per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Base query
$where = " WHERE 1=1";

// Search by filename
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where .= " AND filename LIKE '%$search%'";
}

// Filter by date range
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $conn->real_escape_string($_GET['from']);
    $to = $conn->real_escape_string($_GET['to']);
    $where .= " AND DATE(uploaded_at) BETWEEN '$from' AND '$to'";
} elseif (!empty($_GET['from'])) {
    $from = $conn->real_escape_string($_GET['from']);
    $where .= " AND DATE(uploaded_at) >= '$from'";
} elseif (!empty($_GET['to'])) {
    $to = $conn->real_escape_string($_GET['to']);
    $where .= " AND DATE(uploaded_at) <= '$to'";
}

// Count total records for pagination
$countResult = $conn->query("SELECT COUNT(*) AS total FROM photos $where");
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch photos
$query = "SELECT id, filename, uploaded_at FROM photos $where ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Family Photo Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php include 'navbar.php'; ?>

<div class="container py-5">

<h2 class="fw-bold mb-4">📂 Family Photo Gallery</h2>
<a href="upload.php" class="btn btn-primary float-end">Upload New Photo</a>
<!-- Search & Filter Form -->
<form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by filename..."
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="from" class="form-control" value="<?php echo isset($_GET['from']) ? $_GET['from'] : ''; ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="to" class="form-control" value="<?php echo isset($_GET['to']) ? $_GET['to'] : ''; ?>">
    </div>
    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-success">Filter</button>
    </div>
</form>

<!-- Gallery -->
<div class="row g-4">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='col-md-3'>
          <div class='card shadow-sm h-100'>
            <img src='view.php?id={$row['id']}' class='card-img-top' alt='{$row['filename']}' style='object-fit:cover; height:200px;'>
            <div class='card-body text-center'>
              <h6 class='card-title text-truncate'>{$row['filename']}</h6>
              <p class='card-text'><small class='text-muted'>Uploaded: {$row['uploaded_at']}</small></p>
              <a href='delete.php?id={$row['id']}' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"Delete this photo?\");'>🗑 Delete</a>
            </div>
          </div>
        </div>";
    }
} else {
    echo "<div class='alert alert-warning'>No photos found.</div>";
}
?>
</div>

<!-- Pagination -->
<nav class="mt-4">
<ul class="pagination justify-content-center">
<?php
if ($totalPages > 1) {
    $queryString = $_GET;
    unset($queryString['page']);
    $baseUrl = '?' . http_build_query($queryString);

    $prevDisabled = ($page <= 1) ? "disabled" : "";
    echo "<li class='page-item $prevDisabled'><a class='page-link' href='$baseUrl&page=" . ($page - 1) . "'>Previous</a></li>";

    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? "active" : "";
        echo "<li class='page-item $active'><a class='page-link' href='$baseUrl&page=$i'>$i</a></li>";
    }

    $nextDisabled = ($page >= $totalPages) ? "disabled" : "";
    echo "<li class='page-item $nextDisabled'><a class='page-link' href='$baseUrl&page=" . ($page + 1) . "'>Next</a></li>";
}
?>
</ul>
</nav>

</div>
</body>
</html>
