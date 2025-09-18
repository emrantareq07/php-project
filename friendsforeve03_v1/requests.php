<?php
// require 'auth_check.php';
require 'db.php';

if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $pdo->prepare("SELECT * FROM friend_requests WHERE id=?");
    $stmt->execute([$id]);
    if ($req = $stmt->fetch()) {
        // insert into friends table
        $insert = $pdo->prepare("INSERT INTO friends (name,mobile,alt_mobile,email,occupation,jobplace,address) 
                                 VALUES (?,?,?,?,?,?,?)");
        $insert->execute([$req['name'],$req['mobile'],$req['alt_mobile'],$req['email'],$req['occupation'],$req['jobplace'],$req['address']]);
        // update request status
        $pdo->prepare("UPDATE friend_requests SET status='approved' WHERE id=?")->execute([$id]);
    }
    header("Location: requests.php?msg=Approved");
    exit;
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $pdo->prepare("UPDATE friend_requests SET status='Rejected' WHERE id=?")->execute([$id]);
    header("Location: requests.php?msg=Rejected");
    exit;
}

$rows = $pdo->query("SELECT * FROM friend_requests ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pending Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Pending Requests</h2>
  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>
  <table class="table table-bordered">
    <thead><tr><th>Name</th><th>Mobile</th><th>Email</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['mobile']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= $r['status'] ?></td>
        <td>
          <?php if ($r['status']=='Pending'): ?>
            <a href="?approve=<?= $r['id'] ?>" class="btn btn-sm btn-success">Approve</a>
            <a href="?reject=<?= $r['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
