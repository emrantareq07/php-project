<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

$success = "";

if (isset($_POST['save'])) {

    $division_code = $_POST['division_code']; // e.g., 100
$designation_bn = $_POST['designation_bn'];
$designation_en = $_POST['designation_en'];
$post = $_POST['post'];
$code_count = $_POST['code']; // multiplier
$responsibilities = $_POST['responsibilities'];

$max_codes = []; // track last number per division

for ($i = 0; $i < count($designation_bn); $i++) {

    if (empty($designation_bn[$i]) || empty($designation_en[$i])) continue;

    $repeat = (int)$code_count[$i];
    if ($repeat < 1) $repeat = 1;

    // Only fetch max numeric suffix once per division
    if (!isset($max_codes[$division_code])) {
        // This query extracts the numeric suffix after the division code
        $res = $conn->query("
            SELECT MAX(CAST(SUBSTRING(code, " . (strlen($division_code)+1) . ") AS UNSIGNED)) AS max_suffix
            FROM all_employee 
            WHERE code LIKE '{$division_code}%'
        ");
        $row = $res->fetch_assoc();
        $max_codes[$division_code] = $row['max_suffix'] ? (int)$row['max_suffix'] : 0;
    }

    $start = $max_codes[$division_code] + 1;

    // Insert the required number of rows
    for ($r = 0; $r < $repeat; $r++) {
        $full_code = $division_code . ($start + $r);

        $stmt = $conn->prepare(
            "INSERT INTO all_employee
            (designation_bn, designation_en, post, code, responsibilities)
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssss",
            $designation_bn[$i],
            $designation_en[$i],
            $post[$i],
            $full_code,
            $responsibilities[$i]
        );
        $stmt->execute();
    }

    // Update max code in memory
    $max_codes[$division_code] += $repeat;
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Entry</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#e0f2ff,#f8f9fa);
}
.table thead th{
    vertical-align: middle;
    text-align: center;
}
.table tbody td{
    vertical-align: middle;
}
</style>
</head>

<body>

<div class="container-fluid mt-4">
<div class="row justify-content-center">
<div class="col-xl-11">

<div class="card shadow-lg border-0">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fa-solid fa-users-gear me-2"></i>
            Multiple Employee Entry
        </h5>
        <span class="badge bg-warning text-dark">Dynamic Rows</span>
    </div>

    <div class="card-body bg-white">

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-primary">
                        <i class="fa-solid fa-code me-1"></i> Division Code
                    </label>
                    <input type="text"
                           class="form-control form-control-lg border-primary"
                           name="division_code"
                           placeholder="Enter division code"
                           required>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="employee_table">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Designation (BN)</th>
                            <th>Designation (EN)</th>
                            <th>Post</th>
                            <th>Code Count</th>
                            <th>Responsibilities</th>
                            <th width="90">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="designation_bn[]" class="form-control" required></td>
                            <td><input type="text" name="designation_en[]" class="form-control" required></td>
                            <td><input type="text" name="post[]" class="form-control"></td>
                            <td><input type="number" name="code[]" class="form-control" value="1" min="1"></td>
                            <td><input type="text" name="responsibilities[]" class="form-control"></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove_row">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="add_staff" class="btn btn-success">
                    <i class="fa fa-plus me-1"></i> Add New Row
                </button>

                <button type="submit" name="save" class="btn btn-primary btn-lg">
                    <i class="fa fa-save me-1"></i> Save All Data
                </button>
            </div>

        </form>
    </div>

    <div class="card-footer text-muted text-center small">
        <i class="fa-solid fa-circle-info me-1"></i>
        Each row will insert "Code Count" times internally. Full code auto-generates with Division Code prefix.
    </div>
</div>

</div>
</div>
</div>

<script>
document.getElementById('add_staff').addEventListener('click', function () {
    let tbody = document.querySelector('#employee_table tbody');

    let rowTemplate = `
    <tr>
        <td><input type="text" name="designation_bn[]" class="form-control" required></td>
        <td><input type="text" name="designation_en[]" class="form-control" required></td>
        <td><input type="text" name="post[]" class="form-control"></td>
        <td><input type="number" name="code[]" class="form-control" value="1" min="1"></td>
        <td><input type="text" name="responsibilities[]" class="form-control"></td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm remove_row">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', rowTemplate);
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove_row')) {
        e.target.closest('tr').remove();
    }
});
</script>

</body>
</html>
