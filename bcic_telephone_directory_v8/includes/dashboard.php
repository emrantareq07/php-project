<?php
session_name('bcic_tel_db');
if(session_status() === PHP_SESSION_NONE) session_start();
require '../db/db.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BCIC Employees - Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

 <!-- Bengali Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
    <style>
        /* Bengali Font Stack */
        .bn {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Kalpurush', 'Arial', sans-serif;
            line-height: 1.6;
        }
        
        /* English Font Stack */
        .en {
            font-family: 'Arial', 'Helvetica', sans-serif;
        }
        
        /* Apply to all elements by default */
        body {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Arial', sans-serif;
        }
        
        /* Header specific styles */
        .header-bangla {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
            font-weight: 600;
        }
        
        .header-english {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: 500;
        }
        
        /* Navigation styles */
        .nav-bangla {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
            font-size: 1.1rem;
        }
  * {
    font-family: 'Open Sans', sans-serif;

    font-family: 'Tiro Bangla', serif;
    font-family: 'Noto Sans Bengali', sans-serif;

    font-family: 'Nikosh', sans-serif;

    font-family: 'Nikosh', serif;
}
.required-field::after { content: "*"; color: red; margin-left: 4px; }
table.dataTable tbody tr { cursor: default; }
.modal-lg { max-width: 900px; }
</style>
</head>
<body>
<div class="container-fluid py-4 rounded shadow  border">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-muted">BCIC Employees - Admin Panel</h2>
    <div>
      <a href="logout.php" class="btn btn-outline-danger me-2"><i class="fa fa-sign-out"></i> Logout</a>
      <a href="../index.php" class="btn btn-outline-info me-2" target="_blank"><i class="fa fa-eye"></i> Public View</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Add Employee</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <label for="departmentFilter" class="form-label fw-bold">Department:</label>
      <select id="departmentFilter" class="form-select">
        <option value="">All Departments</option>
        <option value="Administration">Administration</option>
        <option value="Commerce">Commerce</option>
        <option value="Finance">Finance</option>
        <option value="Technical">Technical</option>
        <option value="Medical">Medical</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="statusFilter" class="form-label fw-bold">Status:</label>
      <select id="statusFilter" class="form-select">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="statusFilter" class="form-label fw-bold">System Status:</label>
      <select id="systemStatusFilter" class="form-select">
        <option value="">All Status</option>
        <option value="approved">Approved</option>
        <option value="pending">Pending</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table id="employeesTable" class="display table table-striped" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>EMP ID</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Division/Office</th>
          <th>Department</th>
          <th>Mobile</th>
          <th>Office Phone</th>
          <th>Email</th>
          <th>PABX/Intercom</th>
          <th>Status</th>
          <th>System Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-uppercase text-muted">
            <i class="fa fa-user-plus" style="font-size:48px;color:green"></i> Add Employee           
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label required-field">EMP ID</label><input name="emp_id" required class="form-control"></div>
            <div class="col-md-6"><label class="form-label required-field">Name</label><input name="name" required class="form-control"></div>
            <div class="col-md-6"><label class="form-label required-field">Designation</label><input name="designation" required class="form-control"></div>
            <div class="col-md-6"><label class="form-label required-field">Division/Office</label>
                <!-- <input name="division" class="form-control"> -->
                <input list="divisions" id="division" name="division" class="form-control" required>
                <datalist id="divisions">
                    <?php
                        $sql = "SELECT * FROM division ORDER BY div_name ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['div_name'] . "'>";
                        }
                    ?>
                </datalist>
            </div>
            <div class="col-md-6"><label>Department</label>
                <!-- <input name="department" class="form-control"> -->
                <input list="departments" id="department" name="department" class="form-control" required>
                <datalist id="departments">
                    <?php
                        $sql = "SELECT * FROM department ORDER BY name ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['name'] . "'>";
                        }
                    ?>
                </datalist>

            </div>  
            <div class="col-md-6"><label>Office Phone</label><input name="phone_office" class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label>PABX/Intercom</label><input name="intercom" class="form-control"></div>
            <div class="col-md-6"><label class="form-label required-field">Mobile</label>
                <!-- <input name="mobile" required class="form-control" inputmode="tel"> -->
                 <input autocomplete="on" name="mobile" class="form-control" required inputmode="tel" 
                     pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                     maxlength="11" placeholder="01XXXXXXXXX">
            </div>
            <div class="col-md-6"><label>Email</label><input name="email" type="email" class="form-control"></div>
            <div class="col-md-6"><label>Fax</label><input name="fax" class="form-control"></div>
                        <!-- Personal Details -->
            <div class="col-12 col-md-6">
              <label class="form-label">Blood Group</label>
              <select name="blood_group" class="form-select">
                <option value="" selected disabled>Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Status</label>
              <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Profile Image</label>
              <input type="file" name="image" accept="image/*" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="row g-3">
            <div class="col-md-6"><label>EMP ID</label><input name="emp_id" id="edit_emp_id" required class="form-control"></div>
            <div class="col-md-6"><label>Name</label><input name="name" id="edit_name" required class="form-control"></div>
            <div class="col-md-6"><label>Designation</label><input name="designation" id="edit_designation" required class="form-control"></div>
            <div class="col-md-6"><label>Division/ Office</label><input name="division" id="edit_division" class="form-control"></div>
            <div class="col-md-6"><label>Department</label><input name="department" id="edit_department" class="form-control"></div>                        
            <div class="col-md-6"><label>Office Phone</label><input name="phone_office" id="edit_phone_office" class="form-control"></div>
            <div class="col-md-6"><label>PABX/Intercom</label><input name="intercom" id="edit_intercom" class="form-control"></div>
            <div class="col-md-6"><label>Mobile</label><input name="mobile" id="edit_mobile" required class="form-control"></div>
            <div class="col-md-6"><label>Email</label><input name="email" id="edit_email" type="email" class="form-control"></div>
            <div class="col-md-6"><label>Fax</label><input name="fax" id="edit_fax" class="form-control"></div>
            <div class="col-md-6"><label>Blood Group</label><input name="blood_group" id="edit_blood_group" class="form-control"></div>
            <div class="col-md-6">
              <label>Status</label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Profile Image</label>
              <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
              <div class="mt-2">
                <img id="edit_preview" src="" alt="Preview" width="80" height="80" class="rounded-circle border d-none">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){

    var table = $('#employeesTable').DataTable({
        ajax: {
            url: 'get_employees.php',
            type: 'POST',
            data: function(d){ 
                d.department = $('#departmentFilter').val(); 
                d.status = $('#statusFilter').val(); 
                d.system_status = $('#systemStatusFilter').val(); 
            },
            dataType: 'json',
            error: function(xhr){ 
                console.error(xhr.responseText); 
                alert('AJAX Error! Check console.'); 
            }
        },
        columns: [
            { data: 'id' },
            { data: 'emp_id' },
            { data: 'name' },
            { data: 'designation' },
            { data: 'division' },
            { data: 'department' },
            { data: 'mobile' },
            { data: 'phone_office' },
            { data: 'email' },
            { data: 'intercom' },
            { 
                data: 'status',
                render: function(data){
                    return data === 'active' ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { 
                data: 'system_status',
                render: function(data){
                    return data === 'approved' ? 
                        '<span class="badge bg-success">Approved</span>' : 
                        '<span class="badge bg-warning">Pending</span>';
                        '<span class="badge bg-danger">Rejected</span>';
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-outline-warning editBtn" data-id="${row.id}" title="Edit"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-${row.status === 'active' ? 'danger' : 'success'} statusBtn" data-id="${row.id}" data-status="${row.status}" title="${row.status === 'active' ? 'Deactivate' : 'Activate'}">
                            <i class="fa fa-${row.status === 'active' ? 'ban' : 'check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success approveBtn" data-id="${row.id}" title="Approve"><i class="fa fa-check"></i></button>
                        <a href="delete_employee.php?id=${row.id}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this employee?')" title="Delete"><i class="fa fa-trash"></i></a>
                    `;
                }
            }
        ],
        pageLength: 10 
    });

    // Filter handlers
    $('#departmentFilter, #statusFilter,#systemStatusFilter').on('change', function(){ 
        table.ajax.reload(); 
    });

    // Load data into edit modal
    $('#employeesTable').on('click', '.editBtn', function(){
        var id = $(this).data('id');
        $.get('get_employee.php', {id: id}, function(resp){
            if(resp && resp.id){
                $('#edit_id').val(resp.id);
                $('#edit_emp_id').val(resp.emp_id);
                $('#edit_name').val(resp.name);
                $('#edit_designation').val(resp.designation);
                $('#edit_department').val(resp.department);
                $('#edit_division').val(resp.division);
                $('#edit_blood_group').val(resp.blood_group);
                $('#edit_phone_office').val(resp.phone_office);
                $('#edit_intercom').val(resp.intercom);
                $('#edit_mobile').val(resp.mobile);
                $('#edit_email').val(resp.email);
                $('#edit_fax').val(resp.fax);
                $('#edit_status').val(resp.status);

                if(resp.image){
                    $('#edit_preview').attr('src', resp.image).removeClass('d-none');
                } else {
                    $('#edit_preview').addClass('d-none');
                }

                new bootstrap.Modal(document.getElementById('editModal')).show();
            } else { 
                alert(resp.error || 'Employee not found'); 
            }
        }, 'json');
    });

    // Status toggle
    $('#employeesTable').on('click', '.statusBtn', function(){
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        
        if(confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this employee?`)) {
            $.post('toggle_status.php', {id: id, status: newStatus}, function(res){ 
                alert(res); 
                table.ajax.reload(); 
            });
        }
    });

    // Preview new image before upload
    $('#edit_image').on('change', function(){
        const file = this.files[0];
        if(file){
            $('#edit_preview').attr('src', URL.createObjectURL(file)).removeClass('d-none');
        }
    });

    // Unified add/edit form submit
    $('#addForm, #editForm').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: form.attr('id') === 'addForm' ? 'save_employee.php' : 'update_employee.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                alert(res);
                table.ajax.reload();
                form[0].reset();
                bootstrap.Modal.getInstance(form.closest('.modal')[0]).hide();
            },
            error: function(xhr){
                console.error(xhr.responseText);
                alert('Error while saving. Check console.');
            }
        });
    });

        // Approve button
    $('#employeesTable').on('click', '.approveBtn', function(){
        var id = $(this).data('id');
        $.post('approve.php', {id:id}, function(res){ alert(res); table.ajax.reload(); });
    });

});
</script>
</body>
</html>