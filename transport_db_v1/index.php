<?php 
include 'vehicle_crud.php'; 
include 'include/header.php';
?>
<div class="container-fluid ">
<div class="container-fluid mt-2 p-2">
    <div class="row align-items-center">
        <div class="col-sm-4"><h1 class="mb-4 text-uppercase fw-bold text-muted">BCIC Vehicle Database</h1></div>
        <div class="col-sm-4 text-center">
            
        </div>
        <div class="col-sm-4 text-end">
            <!-- Add Vehicle Button -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVehicleModal"><i class="fa fa-plus" style="font-size:16px;"></i> 
                Add Vehicle
            </button>
           <button type="button" class="btn btn-primary mb-3" onclick="window.location.href='report.php'">
                <i class="fa fa-file-pdf-o" style="font-size:16px;"></i> Report
            </button>

             <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#addVehicleModal"><i class="fa fa-sign-out" style="font-size:16px"></i>
                Logout
            </button>
           <!--  <a href="backend/logout.php" class="btn btn-danger d-inline-block">
                <i class="fa fa-sign-out" style="font-size:16px"></i> <span>Logout</span>
            </a> -->
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Responsive Table Wrapper -->
            <div class="table-responsive">
                <table id="vehicleTable" class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                           <!-- <th>ID</th>-->
						   <th>অবস্থা</th>
							<th>যানবাহনের ধরন</th>
                            <th>রেজিষ্টেশন নম্বর</th>                            
                            <th>প্রাপ্তির উৎস</th>
                            <th>ক্রয়/প্রাপ্তির সাল</th>
                            <th>চালিত কি:মি: </th>
                            <th>ব্যবহারকারীর নাম</th>
                            <th>পদবী</th>
                            <th>চালকের নাম </th>
                            <th>চালকের ধরন</th>
                            <th>বৈকল্যের সাল</th>
                            <th>বৈকল্যের কারণ</th>
                            <th>মেরামত</th>
                            <th>গৃহীত ব্যবস্থা</th>
                            <th>মন্তব্য</th>
                            <th>এ্যাকশন</th> 

                            <!-- <th>ID</th> 
                            <th>Vehicle Status</th>
                            <th>Reg No</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Source</th>
                            <th>Sourcing/Buying Year</th>
                            <th>Driven KM</th>
                            <th>User Name</th>
                            <th>User Designation</th>
                            <th>Driver Name</th>
                            <th>Driver Appointment Type</th>
                            <th>Year of Impairment</th>
                            <th>Cause of Impairment</th>
                            <th>Repair Status</th>
                            <th>Action Taken</th>
                            <th>Remarks</th>
                            <th>Actions</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <!-- <td><?php //echo htmlspecialchars($vehicle['id']); ?></td> -->
                            <td><?php echo htmlspecialchars($vehicle['vehicle_status']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['vehicle_source']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['sourcing_buying_year']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['driven_km']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['user_designation']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['driver_name']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['driver_appt_type']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['yearofimpairment']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['causeofimpairment']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['repair_status']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['action_taken']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['remarks']); ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning edit-btn" data-id="<?php echo $vehicle['id']; ?>"><i class="fa fa-edit" style="font-size:16px;"></i></a>
                                <a href="index.php?delete=<?php echo $vehicle['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash" style="font-size:16px;"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- End of .table-responsive -->
        </div>
    </div>
</div>
</div>

    <!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <!-- Vehicle Status -->
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="vehicle_status" class="form-label">Vehicle Status</label>
                                <!-- <input type="text" class="form-control" id="vehicle_status" name="vehicle_status" required> -->
                                <select class="form-select form-control" id="vehicle_status" name="vehicle_status" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="ব্যবহৃত">ব্যবহৃত</option>
                                    <option value="ব্যবহার অনুপযোগী">ব্যবহার অনুপযোগী</option>                            
                                </select>
                            </div>
                        </div>

                    <!-- Registration Number -->
                     <div class="col-md">
                    <div class="mb-3">
                        <label for="reg_no" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="reg_no" name="reg_no" required>
                    </div></div>

                    <!-- Vehicle Type -->
                     <div class="col-md">
                    <div class="mb-3">
                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                        <!-- <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required> -->
                        <select class="form-select form-control" id="vehicle_type" name="vehicle_type" required aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <option value="কার">কার</option>
                            <option value="পাজেরো">পাজেরো</option>
                            <option value="মাইক্রোবাস">মাইক্রোবাস</option>
                            <option value="জিপ">জিপ</option>
                            
                        </select>
                        </div>
                    </div>
                </div>

                    <div class="row g-2">
                        <div class="col-md">
                    <!-- Vehicle Source -->
                    <div class="mb-3">
                        <label for="vehicle_source" class="form-label">Vehicle Source</label>
                        <input type="text" class="form-control" id="vehicle_source" name="vehicle_source" required>
                    </div></div>

                    <!-- Sourcing/Buying Year -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="sourcing_buying_year" class="form-label">Sourcing/Buying Year</label>
                        <input type="number" class="form-control" id="sourcing_buying_year" name="sourcing_buying_year" min="1900" max="2099" required>
                    </div></div>

                    <!-- Driven KM -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="driven_km" class="form-label">Driven KM</label>
                        <input type="number" class="form-control" id="driven_km" name="driven_km" required>
                    </div>
                    </div>
                </div>
                    <div class="row g-2">
                        <div class="col-md">
                    <!-- User Name -->
                    <div class="mb-3">
                        <label for="user_name" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" required>
                    </div></div>

                    <!-- User Designation -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="user_designation" class="form-label">User Designation</label>
                        <input type="text" class="form-control" id="user_designation" name="user_designation" required>
                    </div></div>
                </div>
                    <div class="row g-2">
                        <div class="col-md">
                    <!-- Driver Name -->
                    <div class="mb-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" class="form-control" id="driver_name" name="driver_name" required>
                    </div></div>

                    <!-- Driver Appointment Type -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="driver_appt_type" class="form-label">Driver Appointment Type</label>
                        <!-- <input type="text" class="form-control" id="driver_appt_type" name="driver_appt_type" required> -->
                        <select class="form-select form-control" id="driver_appt_type" name="driver_appt_type" required aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <option value="আউটসোর্সিং">আউটসোর্সিং</option>
                            <option value="স্থায়ী">স্থায়ী</option>
                            <option value="দৈনিক ভিত্তিক">দৈনিক ভিত্তিক</option>
                        </select>
                    </div>
                    </div>
                </div>
                    <div class="row g-2">
                        <div class="col-md">
                    <!-- Year of Impairment -->
                    <div class="mb-3">
                        <label for="yearofimpairment" class="form-label">Year of Impairment</label>
                        <input type="number" class="form-control" id="yearofimpairment" name="yearofimpairment" min="1900" max="2099">
                    </div></div>

                    <!-- Cause of Impairment -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="causeofimpairment" class="form-label">Cause of Impairment</label>
                        <!-- <textarea class="form-control" id="causeofimpairment" name="causeofimpairment" rows="3"></textarea> -->
                        <input type="text" class="form-control" id="causeofimpairment" name="causeofimpairment">
                    </div></div></div>

                    <div class="row g-2">
                    <div class="col-md">
                    <!-- Repair Status -->
                    <div class="mb-3">
                        <label for="repair_status" class="form-label">Repair Status</label>
                        <!-- <input type="text" class="form-control" id="repair_status" name="repair_status"> -->
                         <select class="form-select form-control" id="repair_status" name="repair_status" required aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <option value="যোগ্য">যোগ্য</option>
                            <option value="অযোগ্য">অযোগ্য</option>                            
                        </select>
                    </div></div>

                    <!-- Action Taken -->
                    <div class="col-md">
                    <div class="mb-3">
                        <label for="action_taken" class="form-label">Action Taken</label>
                        <!-- <textarea class="form-control" id="action_taken" name="action_taken" rows="3"></textarea> -->
                        <select class="form-select form-control" id="action_taken" name="action_taken" required aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <option value="বিক্রয়">বিক্রয়</option>
                            <option value="নিলাম">নিলাম</option>  
                            <option value="জমাকরণ">জমাকরণ</option>
                            <option value="অন্যান্য">অন্যান্য</option>                           
                        </select>
                    </div></div></div>
                    <!-- jQuery Script -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                    $(document).ready(function () {
                        $('#vehicle_status').change(function () {
                            let selectedValue = $(this).val();

                            if (selectedValue === 'ব্যবহৃত') {
                                $('#yearofimpairment, #causeofimpairment, #action_taken, #repair_status')
                                    .prop('disabled', true).val('');
                                $('#vehicle_source, #driven_km, #user_name, #user_designation, #driver_name, #driver_appt_type')
                                    .prop('disabled', false); // Ensure these fields are enabled
                            } 
                            else if (selectedValue === 'ব্যবহার অনুপযোগী') {
                                $('#vehicle_source, #driven_km, #user_name, #user_designation, #driver_name, #driver_appt_type')
                                    .prop('disabled', true).val('');
                                $('#yearofimpairment, #causeofimpairment, #action_taken, #repair_status')
                                    .prop('disabled', false); // Ensure impairment fields are enabled
                            } 
                            else {
                                // Enable all fields if neither option is selected
                                $('#yearofimpairment, #causeofimpairment, #action_taken, #repair_status, #vehicle_source, #driven_km, #user_name, #user_designation, #driver_name, #driver_appt_type')
                                    .prop('disabled', false);
                            }
                        });
                    });

                    </script>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <!-- <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea> -->
                        <input type="text" class="form-control" id="remarks" name="remarks">

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="add" class="btn btn-primary">Add Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vehicle Modal -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <!-- Hidden ID Field -->
                    <input type="hidden" id="edit_id" name="id">

                    <!-- Vehicle Status -->
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_vehicle_status" class="form-label">Vehicle Status</label>
                                <select class="form-select form-control" id="edit_vehicle_status" name="vehicle_status" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="ব্যবহৃত">ব্যবহৃত</option>
                                    <option value="ব্যবহার অনুপযোগী">ব্যবহার অনুপযোগী</option>
                                </select>
                            </div>
                        </div>

                        <!-- Registration Number -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_reg_no" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="edit_reg_no" name="reg_no" required>
                            </div>
                        </div>

                        <!-- Vehicle Type -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_vehicle_type" class="form-label">Vehicle Type</label>
                                <select class="form-select form-control" id="edit_vehicle_type" name="vehicle_type" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="কার">কার</option>
                                    <option value="পাজেরো">পাজেরো</option>
                                    <option value="মাইক্রোবাস">মাইক্রোবাস</option>
                                    <option value="জিপ">জিপ</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- Vehicle Source -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_vehicle_source" class="form-label">Vehicle Source</label>
                                <input type="text" class="form-control" id="edit_vehicle_source" name="vehicle_source" required>
                            </div>
                        </div>

                        <!-- Sourcing/Buying Year -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_sourcing_buying_year" class="form-label">Sourcing/Buying Year</label>
                                <input type="number" class="form-control" id="edit_sourcing_buying_year" name="sourcing_buying_year" min="1900" max="2099" required>
                            </div>
                        </div>

                        <!-- Driven KM -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_driven_km" class="form-label">Driven KM</label>
                                <input type="number" class="form-control" id="edit_driven_km" name="driven_km" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- User Name -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_user_name" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="edit_user_name" name="user_name" required>
                            </div>
                        </div>

                        <!-- User Designation -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_user_designation" class="form-label">User Designation</label>
                                <input type="text" class="form-control" id="edit_user_designation" name="user_designation" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- Driver Name -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_driver_name" class="form-label">Driver Name</label>
                                <input type="text" class="form-control" id="edit_driver_name" name="driver_name" required>
                            </div>
                        </div>

                        <!-- Driver Appointment Type -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_driver_appt_type" class="form-label">Driver Appointment Type</label>
                                <select class="form-select form-control" id="edit_driver_appt_type" name="driver_appt_type" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="আউটসোর্সিং">আউটসোর্সিং</option>
                                    <option value="স্থায়ী">স্থায়ী</option>
                                    <option value="দৈনিক ভিত্তিক">দৈনিক ভিত্তিক</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- Year of Impairment -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_yearofimpairment" class="form-label">Year of Impairment</label>
                                <input type="number" class="form-control" id="edit_yearofimpairment" name="yearofimpairment" min="1900" max="2099">
                            </div>
                        </div>

                        <!-- Cause of Impairment -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_causeofimpairment" class="form-label">Cause of Impairment</label>
                                <input type="text" class="form-control" id="edit_causeofimpairment" name="causeofimpairment">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- Repair Status -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_repair_status" class="form-label">Repair Status</label>
                                <select class="form-select form-control" id="edit_repair_status" name="repair_status" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="যোগ্য">যোগ্য</option>
                                    <option value="অযোগ্য">অযোগ্য</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Taken -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="edit_action_taken" class="form-label">Action Taken</label>
                                <select class="form-select form-control" id="edit_action_taken" name="action_taken" required aria-label="Default select example">
                                    <option selected disabled value="">--Select--</option>
                                    <option value="বিক্রয়">বিক্রয়</option>
                                    <option value="নিলাম">নিলাম</option>
                                    <option value="জমাকরণ">জমাকরণ</option>
                                    <option value="অন্যান্য">অন্যান্য</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <script>
                    $(document).ready(function () {
                        $('#edit_vehicle_status').change(function () {
                            let selectedValue = $(this).val();

                            if (selectedValue === 'ব্যবহৃত') {
                                $('#edit_yearofimpairment, #edit_causeofimpairment, #edit_action_taken, #edit_repair_status')
                                    .prop('disabled', true);
                                $('#edit_vehicle_source, #edit_driven_km, #edit_user_name, #edit_user_designation, #edit_driver_name, #edit_driver_appt_type')
                                    .prop('disabled', false); // Ensure these fields are enabled
                            } 
                            else if (selectedValue === 'ব্যবহার অনুপযোগী') {
                                $('#edit_vehicle_source, #edit_driven_km, #edit_user_name, #edit_user_designation, #edit_driver_name, #edit_driver_appt_type')
                                    .prop('disabled', true);
                                $('#edit_yearofimpairment, #edit_causeofimpairment, #edit_action_taken, #edit_repair_status')
                                    .prop('disabled', false); // Ensure impairment fields are enabled
                            } 
                            else {
                                // Enable all fields if neither option is selected
                                $('#edit_yearofimpairment, #edit_causeofimpairment, #edit_action_taken, #edit_repair_status, #edit_vehicle_source, #edit_driven_km, #edit_user_name, #edit_user_designation, #edit_driver_name, #edit_driver_appt_type')
                                    .prop('disabled', false);
                            }
                        });
                    });

                    </script>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label for="edit_remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="update" class="btn btn-primary">Update Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Initialize DataTables -->

    <script>
        $(document).ready(function() {
    $('#vehicleTable').DataTable({
        dom: "<'row'<'col-md-4'l><'col-md-4 text-start'B><'col-md-4'f>>" + 
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':not(:last-child)' // Excludes the last column (Actions)
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)' // Excludes the last column (Actions)
                }
            }
        ]
    });
});

// $(document).ready(function() {
//     $('#vehicleTable').DataTable({
//         dom: "<'row'<'col-md-6 text-start'lB><'col-md-6'f>>" + // Add 'l' for page length dropdown
//              "<'row'<'col-md-12'tr>>" +
//              "<'row'<'col-md-5'i><'col-md-7'p>>",
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ]
//     });
// });

// $(document).ready(function() {
//     $('#vehicleTable').DataTable({
//         dom: "<'row'<'col-md-4'l><'col-md-4 text-start'B><'col-md-4'f>>" + 
//              "<'row'<'col-md-12'tr>>" +
//              "<'row'<'col-md-5'i><'col-md-7'p>>",
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ]
//     });
// });

    </script>

    <!-- JavaScript for Edit Modal -->
    <script>
        // Function to populate the edit modal with data
        function populateEditModal(vehicle) {
            document.getElementById('edit_id').value = vehicle.id;
            document.getElementById('edit_vehicle_status').value = vehicle.vehicle_status;
            document.getElementById('edit_reg_no').value = vehicle.reg_no;
            document.getElementById('edit_vehicle_type').value = vehicle.vehicle_type;
            document.getElementById('edit_vehicle_source').value = vehicle.vehicle_source;
            document.getElementById('edit_sourcing_buying_year').value = vehicle.sourcing_buying_year;
            document.getElementById('edit_driven_km').value = vehicle.driven_km;
            document.getElementById('edit_user_name').value = vehicle.user_name;
            document.getElementById('edit_user_designation').value = vehicle.user_designation;
            document.getElementById('edit_driver_name').value = vehicle.driver_name;
            document.getElementById('edit_driver_appt_type').value = vehicle.driver_appt_type;
            document.getElementById('edit_yearofimpairment').value = vehicle.yearofimpairment;
            document.getElementById('edit_causeofimpairment').value = vehicle.causeofimpairment;
            document.getElementById('edit_repair_status').value = vehicle.repair_status;
            document.getElementById('edit_action_taken').value = vehicle.action_taken;
            document.getElementById('edit_remarks').value = vehicle.remarks;
        }

        // Event listener for edit buttons
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const vehicleId = this.getAttribute('data-id');
                    fetch(`get_vehicle.php?id=${vehicleId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateEditModal(data);
                            new bootstrap.Modal(document.getElementById('editVehicleModal')).show();
                        });
                });
            });
        });
    </script>
</body>
</html>