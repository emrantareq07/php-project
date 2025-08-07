<?php 
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

if (!isset($_GET['id'])) {
    header("Location: latest_news.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo_conn->prepare("SELECT * FROM latest_news  WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header("Location: latest_news.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit News</h1>
        
        <div class="card">
            <div class="card-body">
                <form action="process_news.php" method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $news['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="news" class="form-label">News Content</label>
                        <textarea class="form-control" id="news" name="news" rows="5" required><?= htmlspecialchars($news['news']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="latest_news.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>