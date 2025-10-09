<?php
session_name('friendsforeve03');
if(session_status() === PHP_SESSION_NONE) session_start();
require '../db/db.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Friendsforeve03 - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
table.dataTable tbody tr { cursor: default; }
.modal-lg { max-width: 800px; }
</style>
</head>
<body>
<div class="container py-4 rounded shadow my-4 border border-4 border-info">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-muted">Friends For Ever (Batch-2003) - Contacts</h2>
    <div>
      <a href="logout.php" class="btn btn-outline-danger me-2"><i class="fa fa-sign-out"></i> Logout</a>
      <a href="../index.php" class="btn btn-outline-info me-2" target="_blank"><i class="fa fa-eye"></i> Public View</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Add New</button>
    </div>
  </div>

  <div class="mb-3">
    <label for="statusFilter" class="form-label fw-bold">Filter by Status:</label>
    <select id="statusFilter" class="form-select" style="max-width:250px;">
      <option value="">All</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
    </select>
  </div>

  <div class="table-responsive">
    <table id="friendsTable" class="display table table-striped" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Mobile</th>
          <th>Alt Mobile</th>
          <th>Email</th>
          <th>Occupation</th>
          <th>Jobplace</th>
          <th>Address</th>
          <th>Status</th>
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
            <i class="fa fa-user-plus" style="font-size:48px;color:red"></i> Add Friend
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label>Name</label><input name="name" required class="form-control"></div>
            <div class="col-md-6"><label>Mobile</label><input name="mobile" required class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label>Alt Mobile</label><input name="alt_mobile" class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label>Email</label><input name="email" type="email" class="form-control"></div>
            <div class="col-md-6"><label>Occupation</label><input name="occupation" class="form-control"></div>
            <div class="col-md-6"><label>Jobplace</label><input name="jobplace" class="form-control"></div>
            <div class="col-md-6">
              <label>Blood Group</label>
              <select name="blood_group" class="form-select" required>
                <option value="">--Select--</option>
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>O+</option><option>O-</option>
                <option>AB+</option><option>AB-</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Profile Image</label>
              <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="col-12"><label>Address</label><textarea name="address" rows="2" class="form-control" required></textarea></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
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
          <h5 class="modal-title">Edit Friend</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="row g-3">
            <div class="col-md-6"><label>Name</label><input name="name" id="edit_name" required class="form-control"></div>
            <div class="col-md-6"><label>Mobile</label><input name="mobile" id="edit_mobile" required class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label>Alt Mobile</label><input name="alt_mobile" id="edit_alt_mobile" class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label>Email</label><input name="email" id="edit_email" type="email" class="form-control"></div>
            <div class="col-md-6"><label>Occupation</label><input name="occupation" id="edit_occupation" class="form-control"></div>
            <div class="col-md-6"><label>Jobplace</label><input name="jobplace" id="edit_jobplace" class="form-control"></div>
            <div class="col-12"><label>Address</label><textarea name="address" id="edit_address" rows="2" class="form-control"></textarea></div>
            <div class="col-md-6">
              <label>Blood Group</label>
              <select name="blood_group" id="edit_blood_group" class="form-select" required>
                <option value="">Select</option>
                <option value="A+">A+</option><option value="A-">A-</option>
                <option value="B+">B+</option><option value="B-">B-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option>
                <option value="O+">O+</option><option value="O-">O-</option>
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
          <button type="submit" class="btn btn-primary">Update</button>
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

    var table = $('#friendsTable').DataTable({
        ajax: {
            url: 'get_admin.php',
            type: 'POST',
            data: function(d){ d.status = $('#statusFilter').val(); },
            dataType: 'json',
            error: function(xhr){ console.error(xhr.responseText); alert('AJAX Error! Check console.'); }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'mobile' },
            { data: 'alt_mobile' },
            { data: 'email' },
            { data: 'occupation' },
            { data: 'jobplace' },
            { data: 'address' },
            { data: 'status' },
            { 
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-outline-warning editBtn" data-id="${row.id}" title="Edit"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-success approveBtn" data-id="${row.id}" title="Approve"><i class="fa fa-check"></i></button>
                        <a href="delete.php?id=${row.id}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?')" title="Delete"><i class="fa fa-trash"></i></a>
                    `;
                }
            }
        ],
        pageLength: 25

    });

    // Simple function to check for pending records
      function checkPending() {
          $.ajax({
              url: 'get_admin.php',
              type: 'POST',
              data: { status: 'pending' },
              dataType: 'json',
              success: function(response) {
                  if (response.data && response.data.length > 0) {
                      table.ajax.reload();
                  }
              }
          });
      }

      // Check every 5 seconds
      setInterval(checkPending, 5000);

    $('#statusFilter').on('change', function(){ table.ajax.reload(); });

    // Load data into edit modal
    $('#friendsTable').on('click', '.editBtn', function(){
        var id = $(this).data('id');
        $.get('get.php', {id: id}, function(resp){
            if(resp && resp.id){
                $('#edit_id').val(resp.id);
                $('#edit_name').val(resp.name);
                $('#edit_mobile').val(resp.mobile);
                $('#edit_alt_mobile').val(resp.alt_mobile);
                $('#edit_email').val(resp.email);
                $('#edit_occupation').val(resp.occupation);
                $('#edit_jobplace').val(resp.jobplace);
                $('#edit_address').val(resp.address);
                $('#edit_blood_group').val(resp.blood_group);

                if(resp.image){
                    $('#edit_preview').attr('src', resp.image).removeClass('d-none');
                } else {
                    $('#edit_preview').addClass('d-none');
                }

                new bootstrap.Modal(document.getElementById('editModal')).show();
            } else { alert(resp.error || 'Record not found'); }
        }, 'json');
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
            url: form.attr('id') === 'addForm' ? 'save.php' : 'update.php',
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
    $('#friendsTable').on('click', '.approveBtn', function(){
        var id = $(this).data('id');
        $.post('approve.php', {id:id}, function(res){ alert(res); table.ajax.reload(); });
    });

});
</script>
</body>
</html>
