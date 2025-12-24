<?php
// view_candidates.php
require_once 'db_connection.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Get total count
$count_stmt = $pdo->query("SELECT COUNT(*) as total FROM candidates_tbl_new");
$total_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_count / $limit);

// Get data
$stmt = $pdo->prepare("SELECT * FROM candidates_tbl_new ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Candidates</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f5f5f5; }
        .pagination { margin: 20px 0; }
        .pagination a { padding: 5px 10px; margin: 0 5px; border: 1px solid #ddd; text-decoration: none; }
        .pagination a.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <h1>Imported Candidates (Total: <?php echo $total_count; ?>)</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Roll No</th>
            <th>Name</th>
            <th>Post</th>
            <th>Gender</th>
            <th>District</th>
            <th>Written</th>
            <th>Viva</th>
            <th>Committee</th>
            <th>Imported At</th>
        </tr>
        <?php foreach ($candidates as $candidate): ?>
        <tr>
            <td><?php echo $candidate['id']; ?></td>
            <td><?php echo htmlspecialchars($candidate['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($candidate['name']); ?></td>
            <td><?php echo htmlspecialchars($candidate['post_name']); ?></td>
            <td><?php echo htmlspecialchars($candidate['gender']); ?></td>
            <td><?php echo htmlspecialchars($candidate['home_district']); ?></td>
            <td><?php echo $candidate['written_marks']; ?></td>
            <td><?php echo $candidate['viva_marks']; ?></td>
            <td><?php echo htmlspecialchars($candidate['committe_name']); ?></td>
            <td><?php echo $candidate['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>">Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?>">Next</a>
        <?php endif; ?>
    </div>
    
    <br>
    <a href="upload_form.html">← Import More Data</a>
</body>
</html>