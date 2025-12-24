<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username']; // Examiner username

// Find the committee assigned to this examiner
$committeeQuery = "SELECT DISTINCT exam_schedule_id, committe_name 
                   FROM committee_tbl 
                   WHERE mobile_no = '$username'";
$committeeResult = mysqli_query($conn, $committeeQuery);

$exam_schedule_id = [];
$committeeNames = [];

while ($row = mysqli_fetch_assoc($committeeResult)) {
    $exam_schedule_id[] = $row['exam_schedule_id'];
    $committeeNames[] = $row['committe_name'];
}

// ✅ Remove this — $row is undefined outside the loop
// $_SESSION['exam_schedule_id'] = $row['exam_schedule_id'];

$candidates = [];

if (!empty($exam_schedule_id)) {
    $exam_schedule_idList = "'" . implode("','", $exam_schedule_id) . "'";
    $candidateQuery = "
        SELECT 
            c.*, 
            v.viva_marks, 
            v.remarks
        FROM candidates_tbl c
        INNER JOIN exam_schedule_tbl e 
            ON c.exam_schedule_id = e.id
        LEFT JOIN viva_marks_tbl v 
            ON c.id = v.candidate_id
            AND v.examiner_username = '$username'
        WHERE e.id IN ($exam_schedule_idList)
    ";

    $candidateResult = mysqli_query($conn, $candidateQuery);

    $candidates = [];
    while ($row = mysqli_fetch_assoc($candidateResult)) {
        $candidates[] = $row;
    }
}

include('header.php');
?>

<body class="bg-light">
<div class="container mt-4">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-2"><i class="fas fa-chalkboard-teacher me-2"></i>Examiner Dashboard</h3>
                <div class="welcome-user">
                    <i class="fas fa-user-circle"></i>
                    <span>Welcome, <?= $username ?></span>
                </div>
            </div>
            <a href="logout.php" class="btn btn-light"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card stats-1">
                <i class="fas fa-users"></i>
                <h3><?= count($candidates) ?></h3>
                <p>Total Candidates</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-2">
                <i class="fas fa-clipboard-check"></i>
                <h3><?= count(array_filter($candidates, function($c) { return !empty($c['viva_marks']); })) ?></h3>
                <p>Evaluated</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-3">
                <i class="fas fa-tasks"></i>
                <h3><?= count(array_filter($candidates, function($c) { return empty($c['viva_marks']); })) ?></h3>
                <p>Pending Evaluation</p>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Candidates List</h5>
            <span class="badge bg-light text-dark">Committee: <?= implode(", ", $committeeNames) ?></span>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-primary text-center sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Father's Name</th>
                            <th>District</th>
                            <th>Designation</th>
                            <th>Written Marks</th>
                            <th>Viva Marks</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($candidates)) { 
                            foreach ($candidates as $row) { 
                                $statusClass = '';
                                if ($row['status'] == 'Passed') $statusClass = 'bg-success';
                                else if ($row['status'] == 'Failed') $statusClass = 'bg-danger';
                                else $statusClass = 'bg-warning';
                        ?>
                        <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['roll_no'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['fathers_name'] ?></td>
                            <td><?= $row['district'] ?></td>
                            <td><?= $row['designation'] ?></td>
                            <td class="text-center"><?= $row['written_marks'] ?: '-' ?></td>
                            <td class="text-center">
                                <?= fmod($row['viva_marks'], 1) == 0 
                                    ? number_format($row['viva_marks'], 0) 
                                    : number_format($row['viva_marks'], 2); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-status <?= $statusClass ?>">
                                    <?= $row['status'] ?: 'Pending' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($row['image'])): ?>
                                    <img src="<?= $row['image'] ?>" alt="Photo" class="candidate-img">
                                <?php else: ?>
                                    <img src="default.png" alt="No Image" class="candidate-img">
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success vivaBtn" data-row='<?= json_encode($row) ?>'>
                                    <i class="fas fa-edit me-1"></i>Add Viva Marks
                                </button>
                            </td>
                        </tr>
                        <?php } } else { ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-3 d-block"></i>
                                    No candidates found for your committee.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Viva Marks Modal -->
<div class="modal fade" id="vivaModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-star me-2"></i>Add Viva Marks</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="vivaForm">
        <div class="modal-body">
            <input type="hidden" name="id" id="candidate_id">

            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-4 text-center">
                    <img id="candidate_image" src="default.png" alt="Candidate Image" class="img-fluid rounded-circle" style="max-height:150px; border: 3px solid var(--primary);">
                </div>
                <div class="col-md-8">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Name</label>
                            <input type="text" class="form-control" id="candidate_name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Roll No</label>
                            <input type="text" class="form-control" id="candidate_roll" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Designation</label>
                            <input type="text" class="form-control" id="candidate_designation" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">District</label>
                            <input type="text" class="form-control" id="candidate_district" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Date of Birth</label>
                            <input type="text" class="form-control" id="candidate_dob" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Written Marks</label>
                            <input type="text" class="form-control" id="candidate_written" readonly placeholder="N/A">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-success">Enter Viva Marks(Out of 20)</label>
                    <input type="number" name="viva_marks" id="viva_marks" class="form-control" placeholder="Enter viva marks" min="0" step="0.01" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-info">Remarks</label>
                    <input type="text" name="candidate_remarks" id="candidate_remarks" class="form-control" placeholder="Add remarks (optional)">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Save Marks</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).on('click', '.vivaBtn', function() {
    const data = JSON.parse($(this).attr('data-row'));

    $('#candidate_id').val(data.id);
    $('#candidate_name').val(data.name);
    $('#candidate_roll').val(data.roll_no);
    $('#candidate_designation').val(data.designation);
    $('#candidate_district').val(data.district);
    $('#candidate_dob').val(data.dob);
    $('#candidate_written').val(data.written_marks || 'N/A');
    $('#viva_marks').val(data.viva_marks);
    $('#candidate_remarks').val(data.remarks);

    if (data.image && data.image.trim() !== '') {
        $('#candidate_image').attr('src', data.image);
    } else {
        $('#candidate_image').attr('src', 'default.png');
    }

    new bootstrap.Modal(document.getElementById('vivaModal')).show();
});

// Submit viva marks via AJAX
$('#vivaForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'update_viva_marks.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            alert(res);
            location.reload();
        },
        error: function() {
            alert('Error saving viva marks. Please try again.');
        }
    });
});
</script>
</body>
</html>