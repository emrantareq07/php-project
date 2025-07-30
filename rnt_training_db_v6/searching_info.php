<?php
session_name('rnt_training_db');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db/db.php');
include('includes/header.php');

if (isset($_POST['submit'])) {
    $training_do_or_not = $_POST['training_do_or_not'] ?? 'do';
    $params = [];
    $types = "";
    
    if ($training_do_or_not === 'do') {
        // Query for employees who HAVE completed the training
        $sql = "SELECT DISTINCT
                    e.id, e.emp_id, e.name, e.designation, e.place_of_posting,
                    o.training_type, o.training_title,
                    COALESCE(o.start_date, 'N/A') AS start_date,
                    COALESCE(o.end_date, 'N/A') AS end_date,
                    o.order_attachment, o.t_institute
                FROM employees e
                JOIN office_order o ON o.id = e.ref_id
                WHERE 1=1";
        
        if (!empty($_POST['emp_id'])) {
            $sql .= " AND e.emp_id = ?";
            $params[] = $_POST['emp_id'];
            $types .= "s";
        }
        if (!empty($_POST['designation'])) {
            $sql .= " AND e.designation = ?";
            $params[] = $_POST['designation'];
            $types .= "s";
        }
        if (!empty($_POST['place_of_posting']) && $_POST['place_of_posting'] !== 'All') {
            $sql .= " AND e.place_of_posting = ?";
            $params[] = $_POST['place_of_posting'];
            $types .= "s";
        }
        if (!empty($_POST['training_title'])) {
            $sql .= " AND o.training_title = ?";
            $params[] = $_POST['training_title'];
            $types .= "s";
        }
        if (!empty($_POST['training_type'])) {
            $sql .= " AND o.training_type = ?";
            $params[] = $_POST['training_type'];
            $types .= "s";
        }
    } else {
        // Query for employees who have NOT completed the training
       $sql = "SELECT 
            e.id, e.emp_id, e.name, e.designation, e.place_of_posting
        FROM employees e
        WHERE 1=1";
        
        if (!empty($_POST['emp_id'])) {
            $sql .= " AND e.emp_id = ?";
            $params[] = $_POST['emp_id'];
            $types .= "s";
        }
        if (!empty($_POST['designation'])) {
            $sql .= " AND e.designation = ?";
            $params[] = $_POST['designation'];
            $types .= "s";
        }
        if (!empty($_POST['place_of_posting']) && $_POST['place_of_posting'] !== 'All') {
            $sql .= " AND e.place_of_posting = ?";
            $params[] = $_POST['place_of_posting'];
            $types .= "s";
        }

        // Correct exclusion logic
        if (!empty($_POST['training_title'])) {
            $sql .= " AND e.emp_id NOT IN (
                SELECT DISTINCT eo.emp_id
                FROM employees eo
                JOIN office_order o ON eo.ref_id = o.id
                WHERE o.training_title = ?
            )";
            $params[] = $_POST['training_title'];
            $types .= "s";
        }

    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
?>
        <div class="modal" id="noRecordsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">No Records Found</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>No records were found for the selected criteria.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="okButton" class="btn btn-primary">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                let modal = new bootstrap.Modal(document.getElementById("noRecordsModal"));
                modal.show();
                document.getElementById("okButton").addEventListener("click", () => {
                    window.location.href = "multi_searching.php";
                });
            });
        </script>
<?php
    } else {
?>
        <div class="container-fluid mt-2">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Total Found: <?= $result->num_rows ?>
                        <button id="printButton" class="btn btn-danger float-end me-2"><i class="fa fa-print"></i> Print</button>
                        <a href="multi_searching.php" class="btn btn-secondary float-end me-2"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                </div>
                <div class="card-body bg-light">
                <table id="employeeTable" class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>SL</th>
                            <th>EMP ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Place of Posting</th>
                            <th>Training Type</th>
                            <th>Training Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Institute</th>
                            <th>Attachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$sl}</td>";
                            echo "<td>{$row['emp_id']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['designation']}</td>";
                            echo "<td>{$row['place_of_posting']}</td>";
                            echo "<td>" . (!empty($row['training_type']) ? $row['training_type'] : 'N/A') . "</td>";
                            echo "<td>" . (!empty($row['training_title']) ? $row['training_title'] : 'N/A') . "</td>";
                            echo "<td>" . (!empty($row['start_date']) ? $row['start_date'] : 'N/A') . "</td>";
                            echo "<td>" . (!empty($row['end_date']) ? $row['end_date'] : 'N/A') . "</td>";
                            echo "<td>" . (!empty($row['t_institute']) ? $row['t_institute'] : 'N/A') . "</td>";
                            echo "<td>";
                            if (!empty($row['order_attachment'])) {
                                echo "<a href='uploads/{$row['order_attachment']}' target='_blank'>View</a>";
                            } else {
                                echo "No Attachment";
                            }
                            echo "</td>";
                            echo "</tr>";
                            $sl++;
                        }
                        ?>
                    </tbody>
                </table>
                <div class="text-end">
                    <button id="printButton" class="btn btn-danger">Print</button>
                    <a href="multi_searching.php" class="btn btn-secondary">Back</a>
                </div>
            </div>

            </div>
        </div>
        <script>
            document.getElementById('printButton').addEventListener('click', function () {
                const table = document.getElementById('employeeTable').cloneNode(true);
                let attachmentIndex = -1;
                table.querySelectorAll('th').forEach((th, idx) => {
                    if (th.textContent.trim() === 'Attachment') {
                        attachmentIndex = idx;
                    }
                });
                if (attachmentIndex !== -1) {
                    table.querySelectorAll('tr').forEach(row => {
                        const cells = row.children;
                        if (cells.length > attachmentIndex) {
                            cells[attachmentIndex].remove();
                        }
                    });
                }
                const win = window.open('', '_blank');
                win.document.write(`
                    <html>
                        <head>
                            <title>Print - Employee Training Report</title>
                            <style>
                                table { border-collapse: collapse; width: 100%; }
                                th, td { border: 1px solid #333; padding: 5px; text-align: left; }
                                th { background: #eee; }
                                body { font-family: sans-serif; }
                            </style>
                        </head>
                        <body>
                            <h3 style="text-align: center;">Employee Training Report</h3>
                            ${table.outerHTML}
                        </body>
                    </html>
                `);
                win.document.close();
                win.focus();
                win.print();
                win.close();
            });
        </script>
<?php
    }
}
include('includes/footer.php');
?>