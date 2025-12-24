<div class="form-row">
  <div class="form-group col-md-4">
    <label>Employee ID</label>
    <input type="text" name="emp_id" class="form-control" required>
  </div>
  <div class="form-group col-md-4">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="form-group col-md-4">
    <label>Designation</label>
    <input type="text" name="designation" class="form-control">
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-4">
    <label>Division</label>
    <input type="text" name="division" class="form-control">
  </div>
  <div class="form-group col-md-4">
    <label>Section</label>
    <input type="text" name="section" class="form-control">
  </div>
  <div class="form-group col-md-4">
    <label>Mobile No</label>
    <input type="text" name="mobile_no" class="form-control">
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label>Email</label>
    <input type="email" name="email_id" class="form-control">
  </div>

<div class="form-group col-md-6">
  <label for="requested_type">Requested Type</label>
  <select class="form-control" id="requested_type" name="requested_type" required>
    <option value="">-- Select Request Type --</option>
    <option value="Civil">Work Request for Civil Work</option>
    <option value="MTS">Work Request for MTS Work</option>
    <option value="ICT">Work Request for ICT Work</option>
  </select>
</div>

</div>


                <div class="item-section">
                    <h5><i class="fas fa-file-alt"></i> Request</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="item_table">
                            <thead>
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <button type="button" id="add_item" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Add 
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Remarks</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="item_body">
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select item-term-select" name="items[]" >
                                            <option value="">Select Requested item</option>
                                            <option value="CPU">CPU</option>
                                            <option value="Monitor">Monitor</option>
                                            <option value="Printer">Printer</option>
                                        </select>
                                        <div class="duplicate-error">This term has already been selected</div>
                                    </td>
                                    <td><input type="text" class="form-control exam-actual-input" name="item_desc" ></td>
                                    <td><input type="text" class="form-control exam-payment-input" name="remarks" ></td>
                                    
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-exam-row" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

<div class="form-group">
  <label>Extra Item</label>
  <textarea name="extra_item" class="form-control"></textarea>
</div>
