<?php
include 'db.php';

$today = date('Y-m-d');

$sql = "SELECT batch, training_title, start_date, end_date, organized_by 
        FROM authority_tbl 
        WHERE end_date >= ?
        ORDER BY start_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0): ?>
<div class="table-responsive">
  <table class="table table-bordered table-striped align-middle mb-0">
    <thead class="table-primary">
      <tr>
        <th>Batch</th>
        <th>Training Title</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Organized By</th>
        <th style="width:140px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['batch']) ?></td>
        <td><?= htmlspecialchars($row['training_title']) ?></td>
        <td><?= htmlspecialchars($row['start_date']) ?></td>
        <td><?= htmlspecialchars($row['end_date']) ?></td>
        <td><?= htmlspecialchars($row['organized_by']) ?></td>
        <td>
          <a href="#" 
             class="btn btn-sm btn-success"
             onclick="closeModalAndShowRegister(
                 '<?= addslashes($row['batch']) ?>',
                 '<?= addslashes($row['training_title']) ?>',
                 '<?= $row['start_date'] ?>',
                 '<?= $row['end_date'] ?>',
                 '<?= addslashes($row['organized_by']) ?>'
             ); return false;">
            Register here
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<div class="alert alert-info mb-0">No available trainings right now.</div>
<?php endif; ?>
