<!-- Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="employeeForm" autocomplete="off">
            <input type="hidden" name="id" id="empId">
            <input type="hidden" name="employee_type" id="empType">

            <!-- Officer Fields -->
            <div class="employee-fields officer-fields d-none">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Factory Name</label>
                        <input type="text" name="factory_name" class="form-control" placeholder="Factory Name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Division</label>
                        <input type="text" name="division" class="form-control" placeholder="Division">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" placeholder="Department">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" placeholder="Designation">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Grade</label>
                        <input type="text" name="grade" class="form-control" placeholder="Grade">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Male</label>
                        <input type="number" name="male" class="form-control" placeholder="Male" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Female</label>
                        <input type="number" name="female" class="form-control" placeholder="Female" min="0" step="1" value="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Remarks">
                    </div>
                </div>
            </div>

            <!-- Staff Fields -->
            <div class="employee-fields staff-fields d-none">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Factory Name</label>
                        <input type="text" name="factory_name" class="form-control" placeholder="Factory Name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Grade Class</label>
                        <input type="text" name="grade_class" class="form-control" placeholder="Grade Class">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Grade</label>
                        <input type="text" name="grade" class="form-control" placeholder="Grade">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Male</label>
                        <input type="number" name="male" class="form-control" placeholder="Male" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Female</label>
                        <input type="number" name="female" class="form-control" placeholder="Female" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Sanctioned</label>
                        <input type="number" name="sanctioned_post" class="form-control" placeholder="Sanctioned" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Filled</label>
                        <input type="number" name="filled_post" class="form-control" placeholder="Filled" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Vacant</label>
                        <input type="number" name="vacant_post" class="form-control" placeholder="Vacant" min="0" step="1" value="0" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Remarks">
                    </div>
                </div>
            </div>

            <!-- Worker Fields -->
            <div class="employee-fields worker-fields d-none">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Factory Name</label>
                        <input type="text" name="factory_name" class="form-control" placeholder="Factory Name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" placeholder="Designation">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Grade</label>
                        <input type="text" name="grade" class="form-control" placeholder="Grade">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Male</label>
                        <input type="number" name="male" class="form-control" placeholder="Male" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Female</label>
                        <input type="number" name="female" class="form-control" placeholder="Female" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Sanctioned</label>
                        <input type="number" name="sanctioned_post" class="form-control" placeholder="Sanctioned" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Filled</label>
                        <input type="number" name="filled_post" class="form-control" placeholder="Filled" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Vacant</label>
                        <input type="number" name="vacant_post" class="form-control" placeholder="Vacant" min="0" step="1" value="0" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Remarks">
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="saveEmployeeBtn" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JS to auto-calculate vacant post -->
<script>
$(document).on('input', 'input[name="sanctioned_post"], input[name="filled_post"]', function(){
    var parent = $(this).closest('.employee-fields');
    var sanctioned = parseInt(parent.find('input[name="sanctioned_post"]').val()) || 0;
    var filled = parseInt(parent.find('input[name="filled_post"]').val()) || 0;
    parent.find('input[name="vacant_post"]').val(Math.max(sanctioned - filled, 0));
});
</script>