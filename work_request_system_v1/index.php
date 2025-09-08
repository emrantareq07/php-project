<?php
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "work_request_db";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// =====================
// DELETE REQUEST
// =====================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Delete items first
    $stmt = $conn->prepare("DELETE FROM work_request_items WHERE request_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete main request
    $stmt = $conn->prepare("DELETE FROM work_request_tbl WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// =====================
// STATUS UPDATE
// =====================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_request'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE work_request_tbl SET status=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// =====================
// INSERT / UPDATE REQUEST
// =====================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['emp_id'])) {
    $request_id    = $_POST['request_id'] ?? '';
    $emp_id        = trim($_POST['emp_id']);
    $name          = trim($_POST['name']);
    $designation   = trim($_POST['designation']);
    $division      = trim($_POST['division']);
    $section       = trim($_POST['section']);
    $mobile_no     = trim($_POST['mobile_no']);
    $email_id      = trim($_POST['email_id']);
    $requested_type= trim($_POST['requested_type']);
    $extra_item    = trim($_POST['extra_item']);

    if ($request_id) {
        // Update request
        $stmt = $conn->prepare("UPDATE work_request_tbl 
            SET emp_id=?, name=?, designation=?, division=?, section=?, mobile_no=?, email_id=?, requested_type=?, extra_item=?, updated_at=NOW()
            WHERE id=?");
        $stmt->bind_param("sssssssssi", $emp_id, $name, $designation, $division, $section, $mobile_no, $email_id, $requested_type, $extra_item, $request_id);
        $stmt->execute();
        $stmt->close();

        // Clear old items
        $stmt = $conn->prepare("DELETE FROM work_request_items WHERE request_id=?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new request
        $stmt = $conn->prepare("INSERT INTO work_request_tbl 
            (emp_id, name, designation, division, section, mobile_no, email_id, requested_type, extra_item, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("sssssssss", $emp_id, $name, $designation, $division, $section, $mobile_no, $email_id, $requested_type, $extra_item);
        $stmt->execute();
        $request_id = $stmt->insert_id;
        $stmt->close();
    }

    // Insert items
    if (!empty($_POST['items'])) {
        foreach ($_POST['items'] as $index => $item) {
            if ($item) {
                $item_desc = $_POST['item_desc'][$index] ?? '';
                $remarks   = $_POST['remarks'][$index] ?? '';
                $stmt = $conn->prepare("INSERT INTO work_request_items (request_id, item, item_desc, remarks) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $request_id, $item, $item_desc, $remarks);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// =====================
// FETCH REQUESTS
// =====================
$conn->query("SET SESSION group_concat_max_len = 10000"); // avoid truncation
$sql = "SELECT r.*, 
        GROUP_CONCAT(CONCAT(i.item, IF(i.item_desc!='', CONCAT(' (', i.item_desc, ')'), '')) SEPARATOR ', ') as all_items
        FROM work_request_tbl r
        LEFT JOIN work_request_items i ON r.id = i.request_id
        GROUP BY r.id ORDER BY r.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Work Requests</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Work Requests</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addRequestModal">
      <i class="fa fa-plus"></i> Add Request
    </button>
  </div>

  <!-- DataTable -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">All Work Requests</div>
    <div class="card-body">
      <table id="requestTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Division</th>
            <th>Section</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Items</th>
            <th>Status</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['emp_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['designation']) ?></td>
            <td><?= htmlspecialchars($row['division']) ?></td>
            <td><?= htmlspecialchars($row['section']) ?></td>
            <td><?= htmlspecialchars($row['mobile_no']) ?></td>
            <td><?= htmlspecialchars($row['email_id']) ?></td>
            <td><?= htmlspecialchars($row['all_items']) ?></td>
            <td>
              <span class="badge badge-<?= $row['status']=="pending"?'warning':'success' ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['updated_at'] ?></td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-info editBtn" data-id="<?= $row['id'] ?>"><i class="fa fa-edit"></i></button>
                <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this request?');">
                  <i class="fa fa-trash"></i>
                </a>
                <?php if($row['status']=="pending"): ?>
                  <form method="post" style="display:inline-block">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="status" value="complete">
                    <button type="submit" name="update_request" class="btn btn-success">
                      <i class="fa fa-check"></i>
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add/Edit Request Modal -->
<div class="modal fade" id="addRequestModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="request_id" id="request_id"> <!-- FIXED -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add / Edit Request</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- Employee Info -->
          <div class="form-row">
            <div class="form-group col-md-4"><label>Employee ID</label><input type="text" name="emp_id" class="form-control" required></div>
            <div class="form-group col-md-4"><label>Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group col-md-4"><label>Designation</label><input type="text" name="designation" class="form-control"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4"><label>Division</label><input type="text" name="division" class="form-control"></div>
            <div class="form-group col-md-4"><label>Section</label><input type="text" name="section" class="form-control"></div>
            <div class="form-group col-md-4"><label>Mobile</label><input type="text" name="mobile_no" class="form-control"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6"><label>Email</label><input type="email" name="email_id" class="form-control"></div>
            <div class="form-group col-md-6"><label>Requested Type</label>
              <select name="requested_type" class="form-control" required>
                <option value="">Select</option>
                <option>Civil</option><option>MTS</option><option>ICT</option>
              </select>
            </div>
          </div>

          <!-- Item Section -->
          <h5 class="mt-3">Request Items</h5>
          <table class="table table-bordered" id="item_table">
            <thead>
              <tr><th>Item</th><th>Description</th><th>Remarks</th><th></th></tr>
            </thead>
            <tbody id="item_body"></tbody>
          </table>
          <button type="button" id="add_item" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Item</button>

          <div class="form-group mt-3">
            <label>Extra Item</label>
            <textarea name="extra_item" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Request</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap4.min.js"></script>


<script>
$(function () {
  const ictItems = ["CPU", "Monitor", "Printer", "Others"];
  const mtsItems = ["Tubelight", "Fan", "AC", "Switch", "Others"];

  // Update items dropdown based on requested_type
  $('select[name="requested_type"]').on('change', function () {
    const type = $(this).val();
    $('#item_body').empty(); // clear old rows

    if (type === "Civil") {
      // Civil: only desc + remarks
      addRow([], true);
    } else if (type === "ICT") {
      addRow(ictItems);
    } else if (type === "MTS") {
      addRow(mtsItems);
    }
  });

  // Function to add row
  function addRow(items = [], civil = false) {
    let itemColumn = '';
    if (!civil) {
      itemColumn = `<select class="form-control item-select" name="items[]">
                      <option value="">Select Item</option>
                      ${items.map(i => `<option value="${i}">${i}</option>`).join("")}
                    </select>`;
    } else {
      itemColumn = `<input type="hidden" name="items[]" value="Civil"> 
                    <span class="badge badge-info">Civil Work</span>`;
    }

    const newRow = `<tr>
        <td>${itemColumn}</td>
        <td><input type="text" class="form-control" name="item_desc[]"></td>
        <td><input type="text" class="form-control" name="remarks[]"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-row">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>`;

    $('#item_body').append(newRow);
    checkRemoveButton();
  }

  // Add more rows button
  $('#add_item').on('click', function (e) {
    e.preventDefault();
    const type = $('select[name="requested_type"]').val();
    if (type === "ICT") addRow(ictItems);
    else if (type === "MTS") addRow(mtsItems);
    else if (type === "Civil") addRow([], true);
    else alert("Please select Requested Type first!");
  });

  // Remove row
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
    checkRemoveButton();
  });

  // Prevent duplicate item selection
  $(document).on('change', '.item-select', function () {
    const selected = $(this).val();
    let count = 0;
    $('.item-select').each(function () {
      if ($(this).val() === selected) count++;
    });
    if (count > 1) {
      alert("Item already selected!");
      $(this).val('');
    }
  });

  // Disable remove if only one row
  function checkRemoveButton() {
    if ($('#item_body tr').length === 1) {
      $('#item_body .remove-row').prop('disabled', true);
    } else {
      $('#item_body .remove-row').prop('disabled', false);
    }
  }

  // Initialize with ICT by default
  $('select[name="requested_type"]').trigger('change');
});
</script>

<script>
$(document).on('click', '.editBtn', function(){
    const id = $(this).data('id');

    $.ajax({
        url: 'get_request.php',
        type: 'GET',
        data: {id: id},
        success: function(response){
            const data = JSON.parse(response);

            // Fill form fields
            $('#request_id').val(data.id);
            $('[name="emp_id"]').val(data.emp_id);
            $('[name="name"]').val(data.name);
            $('[name="designation"]').val(data.designation);
            $('[name="division"]').val(data.division);
            $('[name="section"]').val(data.section);
            $('[name="mobile_no"]').val(data.mobile_no);
            $('[name="email_id"]').val(data.email_id);
            $('[name="requested_type"]').val(data.requested_type).trigger('change');
            $('[name="extra_item"]').val(data.extra_item);

            // Clear items and reload
            $('#item_body').empty();
            data.items.forEach(item => {
                let row = `<tr>
                    <td><input type="text" class="form-control" name="items[]" value="${item.item}"></td>
                    <td><input type="text" class="form-control" name="item_desc[]" value="${item.item_desc}"></td>
                    <td><input type="text" class="form-control" name="remarks[]" value="${item.remarks}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
                </tr>`;
                $('#item_body').append(row);
            });

            $('#addRequestModal').modal('show');
        }
    });
});
</script>
<script>
$(document).on('click', '.statusBtn', function(){
    const id = $(this).data('id');
    const status = $(this).data('status');
    const row = $(this).closest('tr');

    $.ajax({
        url: 'update_status.php',
        type: 'POST',
        data: {id: id, status: status},
        success: function(res){
            const response = JSON.parse(res);
            if(response.success){
                // Update badge in table
                row.find('td:nth-child(10)').html(
                  `<span class="badge badge-${status === 'pending' ? 'warning' : 'success'}">
                     ${status.charAt(0).toUpperCase() + status.slice(1)}
                   </span>`
                );
                // Remove the button since status is complete
                row.find('.statusBtn').remove();
            } else {
                alert("Failed to update status.");
            }
        }
    });
});
</script>


</body>
</html>
