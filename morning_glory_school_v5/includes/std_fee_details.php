<?php
session_start();
include 'config.php';
include 'form.php'; // This includes the form at the top of the page

// Fetch all records
$stmt = $pdo->query("SELECT * FROM student_exam_fees ORDER BY entry_date DESC");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Student Exam Fee Records</h5>
            <a href="form.php" class="btn btn-light">
                <i class="fas fa-plus me-1"></i> Add New
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="recordsTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Entry Date</th>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Month/Year</th>
                            <th>Exam Fee</th>
                            <th>Total</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($row['entry_date'])) ?></td>
                            <td><?= htmlspecialchars($row['roll_no']) ?></td>
                            <td><?= htmlspecialchars($row['std_name']) ?></td>
                            <td><?= htmlspecialchars($row['class']) ?></td>
                            <td><?= htmlspecialchars($row['section']) ?></td>
                            <td><?= htmlspecialchars($row['month']) ?>/<?= $row['year'] ?></td>
                            <td>₹<?= number_format($row['exam_fee'], 2) ?></td>
                            <td>₹<?= number_format($row['total'], 2) ?></td>
                            <td><?= htmlspecialchars($row['remarks']) ?></td>
                            <td>
                                <a href="form.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="process.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#recordsTable').DataTable({
        responsive: true,
        order: [[0, 'desc']] // Sort by entry date descending
    });
});
</script>