<?php 
session_name('transport_db');
include 'vehicle_crud.php'; 
include 'header.php';

// Helper function for safe number formatting
function formatNumber($value) {
    if (empty($value) || !is_numeric($value)) {
        return '0';
    }
    return number_format((float)$value);
}
?>

<div class="container-fluid">
    <!-- Main Content Container -->
    <div class="container-fluid mt-3 p-3 rounded shadow-sm bg-light">
        
        <!-- Header Section -->
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-car me-2"></i>BCIC Vehicle Database
                </h1>
                <p class="text-muted mb-0">Complete vehicle management system</p>
            </div>
            <!-- <div class="col-md-4 text-center"> -->
                <!-- Optional: Add search or filter component here -->
            <!-- </div> -->
            <div class="col-md-4 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="fas fa-plus-circle me-1"></i> Add Vehicle
                    </button>
                    <a href="report.php" class="btn btn-success">
                        <i class="fas fa-file-pdf me-1"></i> Report
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-table me-2"></i>Vehicle Records
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="vehicleTable" class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>id</th>
                                <th>অবস্থা</th>
                                <th>যানবাহনের ধরন</th>
                                <th>রেজিষ্টেশন নম্বর</th>                            
                                <th>প্রাপ্তির উৎস</th>
                                <th>ক্রয়/প্রাপ্তির সাল</th>
                                <th>চালিত কি:মি:</th>
                                <th>ব্যবহারকারীর নাম</th>
                                <th>পদবী</th>
                                <th>চালকের নাম</th>
                                <th>চালকের ধরন</th>
                                <th>বৈকল্যের সাল</th>
                                <th>বৈকল্যের কারণ</th>
                                <th>মেরামত</th>
                                <th>গৃহীত ব্যবস্থা</th>
                                <th>মন্তব্য</th>
                                <th class="text-center">এ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <tr><td><?php echo htmlspecialchars($vehicle['id']); ?></td>
                                <td>
                                    <span class="badge <?php echo $vehicle['vehicle_status'] === 'ব্যবহৃত' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($vehicle['vehicle_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['vehicle_source']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['sourcing_buying_year']); ?></td>
                                <td><?php echo formatNumber($vehicle['driven_km']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['user_designation']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['driver_name']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['driver_appt_type']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['yearofimpairment']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['causeofimpairment']); ?></td>
                                <td>
                                    <span class="badge <?php echo $vehicle['repair_status'] === 'যোগ্য' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($vehicle['repair_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($vehicle['action_taken']); ?></td>
                                <td>
                                    <small class="text-muted"><?php echo htmlspecialchars($vehicle['remarks']); ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning edit-btn" data-id="<?php echo $vehicle['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="dashboard.php?delete=<?php echo $vehicle['id']; ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this vehicle?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The rest of your modal and JavaScript code remains the same -->

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addVehicleModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New Vehicle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="addVehicleForm">
                    <!-- Vehicle Status and Basic Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_status" class="form-label fw-semibold">Vehicle Status *</label>
                                <select class="form-select" id="vehicle_status" name="vehicle_status" required>
                                    <option selected disabled value="">-- Select Status --</option>
                                    <option value="ব্যবহৃত">ব্যবহৃত</option>
                                    <option value="ব্যবহার অনুপযোগী">ব্যবহার অনুপযোগী</option>                            
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_no" class="form-label fw-semibold">Registration Number *</label>
                                <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Enter registration number" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_type" class="form-label fw-semibold">Vehicle Type *</label>
                                <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                    <option selected disabled value="">-- Select Type --</option>
                                    <option value="কার">কার</option>
                                    <option value="পাজেরো">পাজেরো</option>
                                    <option value="মাইক্রোবাস">মাইক্রোবাস</option>
                                    <option value="জিপ">জিপ</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Source and Usage Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-source me-2"></i>Source & Usage
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_source" class="form-label fw-semibold">Vehicle Source *</label>
                                <input type="text" class="form-control" id="vehicle_source" name="vehicle_source" placeholder="Enter source" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sourcing_buying_year" class="form-label fw-semibold">Sourcing/Buying Year *</label>
                                <input type="number" class="form-control" id="sourcing_buying_year" name="sourcing_buying_year" min="1900" max="2099" placeholder="YYYY" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="driven_km" class="form-label fw-semibold">Driven KM *</label>
                                <input type="number" class="form-control" id="driven_km" name="driven_km" placeholder="Enter kilometers" required>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-user me-2"></i>User Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name" class="form-label fw-semibold">User Name *</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter user name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_designation" class="form-label fw-semibold">User Designation *</label>
                                <input type="text" class="form-control" id="user_designation" name="user_designation" placeholder="Enter designation" required>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-id-card me-2"></i>Driver Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="driver_name" class="form-label fw-semibold">Driver Name *</label>
                                <input type="text" class="form-control" id="driver_name" name="driver_name" placeholder="Enter driver name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="driver_appt_type" class="form-label fw-semibold">Driver Appointment Type *</label>
                                <select class="form-select" id="driver_appt_type" name="driver_appt_type" required>
                                    <option selected disabled value="">-- Select Type --</option>
                                    <option value="আউটসোর্সিং">আউটসোর্সিং</option>
                                    <option value="স্থায়ী">স্থায়ী</option>
                                    <option value="দৈনিক ভিত্তিক">দৈনিক ভিত্তিক</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Impairment Information (Conditional) -->
                    <div class="row g-3 mb-4 impairment-section">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-exclamation-triangle me-2"></i>Impairment Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="yearofimpairment" class="form-label fw-semibold">Year of Impairment</label>
                                <input type="number" class="form-control" id="yearofimpairment" name="yearofimpairment" min="1900" max="2099" placeholder="YYYY">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="causeofimpairment" class="form-label fw-semibold">Cause of Impairment</label>
                                <input type="text" class="form-control" id="causeofimpairment" name="causeofimpairment" placeholder="Enter cause">
                            </div>
                        </div>
                    </div>

                    <!-- Repair and Action Information (Conditional) -->
                    <div class="row g-3 mb-4 repair-section">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-tools me-2"></i>Repair & Action
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="repair_status" class="form-label fw-semibold">Repair Status</label>
                                <select class="form-select" id="repair_status" name="repair_status">
                                    <option selected disabled value="">-- Select Status --</option>
                                    <option value="যোগ্য">যোগ্য</option>
                                    <option value="অযোগ্য">অযোগ্য</option>                            
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action_taken" class="form-label fw-semibold">Action Taken</label>
                                <select class="form-select" id="action_taken" name="action_taken">
                                    <option selected disabled value="">-- Select Action --</option>
                                    <option value="বিক্রয়">বিক্রয়</option>
                                    <option value="নিলাম">নিলাম</option>  
                                    <option value="জমাকরণ">জমাকরণ</option>
                                    <option value="অন্যান্য">অন্যান্য</option>                           
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks" class="form-label fw-semibold">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Additional remarks..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" name="add" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vehicle Modal (Structure similar to Add Modal) -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editVehicleModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Vehicle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit form structure identical to add form, just with 'edit_' prefix -->
                <form method="post" id="editVehicleForm">
                    <input type="hidden" id="edit_id" name="id">
                    
                                        <!-- Vehicle Status and Basic Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_status" class="form-label fw-semibold">Vehicle Status *</label>
                                <select class="form-select" id="edit_vehicle_status" name="vehicle_status" required>
                                    <option selected disabled value="">-- Select Status --</option>
                                    <option value="ব্যবহৃত">ব্যবহৃত</option>
                                    <option value="ব্যবহার অনুপযোগী">ব্যবহার অনুপযোগী</option>                            
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_no" class="form-label fw-semibold">Registration Number *</label>
                                <input type="text" class="form-control" id="edit_reg_no" name="reg_no" placeholder="Enter registration number" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_type" class="form-label fw-semibold">Vehicle Type *</label>
                                <select class="form-select" id="edit_vehicle_type" name="vehicle_type" required>
                                    <option selected disabled value="">-- Select Type --</option>
                                    <option value="কার">কার</option>
                                    <option value="পাজেরো">পাজেরো</option>
                                    <option value="মাইক্রোবাস">মাইক্রোবাস</option>
                                    <option value="জিপ">জিপ</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Source and Usage Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-source me-2"></i>Source & Usage
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_source" class="form-label fw-semibold">Vehicle Source *</label>
                                <input type="text" class="form-control" id="edit_vehicle_source" name="vehicle_source" placeholder="Enter source" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sourcing_buying_year" class="form-label fw-semibold">Sourcing/Buying Year *</label>
                                <input type="number" class="form-control" id="edit_sourcing_buying_year" name="sourcing_buying_year" min="1900" max="2099" placeholder="YYYY" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="driven_km" class="form-label fw-semibold">Driven KM *</label>
                                <input type="number" class="form-control" id="edit_driven_km" name="driven_km" placeholder="Enter kilometers" required>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-user me-2"></i>User Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name" class="form-label fw-semibold">User Name *</label>
                                <input type="text" class="form-control" id="edit_user_name" name="user_name" placeholder="Enter user name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_designation" class="form-label fw-semibold">User Designation *</label>
                                <input type="text" class="form-control" id="edit_user_designation" name="user_designation" placeholder="Enter designation" required>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-id-card me-2"></i>Driver Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="driver_name" class="form-label fw-semibold">Driver Name *</label>
                                <input type="text" class="form-control" id="edit_driver_name" name="driver_name" placeholder="Enter driver name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="driver_appt_type" class="form-label fw-semibold">Driver Appointment Type *</label>
                                <select class="form-select" id="edit_driver_appt_type" name="driver_appt_type" required>
                                    <option selected disabled value="">-- Select Type --</option>
                                    <option value="আউটসোর্সিং">আউটসোর্সিং</option>
                                    <option value="স্থায়ী">স্থায়ী</option>
                                    <option value="দৈনিক ভিত্তিক">দৈনিক ভিত্তিক</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Impairment Information (Conditional) -->
                    <div class="row g-3 mb-4 impairment-section">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-exclamation-triangle me-2"></i>Impairment Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="yearofimpairment" class="form-label fw-semibold">Year of Impairment</label>
                                <input type="number" class="form-control" id="edit_yearofimpairment" name="yearofimpairment" min="1900" max="2099" placeholder="YYYY">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="causeofimpairment" class="form-label fw-semibold">Cause of Impairment</label>
                                <input type="text" class="form-control" id="edit_causeofimpairment" name="causeofimpairment" placeholder="Enter cause">
                            </div>
                        </div>
                    </div>

                    <!-- Repair and Action Information (Conditional) -->
                    <div class="row g-3 mb-4 repair-section">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">
                                <i class="fas fa-tools me-2"></i>Repair & Action
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="repair_status" class="form-label fw-semibold">Repair Status</label>
                                <select class="form-select" id="edit_repair_status" name="repair_status">
                                    <option selected disabled value="">-- Select Status --</option>
                                    <option value="যোগ্য">যোগ্য</option>
                                    <option value="অযোগ্য">অযোগ্য</option>                            
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action_taken" class="form-label fw-semibold">Action Taken</label>
                                <select class="form-select" id="edit_action_taken" name="action_taken">
                                    <option selected disabled value="">-- Select Action --</option>
                                    <option value="বিক্রয়">বিক্রয়</option>
                                    <option value="নিলাম">নিলাম</option>  
                                    <option value="জমাকরণ">জমাকরণ</option>
                                    <option value="অন্যান্য">অন্যান্য</option>                           
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks" class="form-label fw-semibold">Remarks</label>
                                <textarea class="form-control" id="edit_remarks" name="remarks" rows="2" placeholder="Additional remarks..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" name="update" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-1"></i> Update Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript and CSS Dependencies -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
        font-size: 0.95rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
    }
    
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .modal-header {
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .modal-content {
        border-radius: 0.5rem;
        border: none;
    }
    
    .border-bottom {
        border-color: #dee2e6 !important;
    }


</style>

<script>
    // Initialize DataTable with enhanced options
$(document).ready(function() {
    const vehicleTable = $('#vehicleTable').DataTable({
        dom: "<'row'<'col-md-3'l><'col-md-6 text-center'B><'col-md-3 text-end'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        buttons: {
            dom: {
                container: {
                    tag: 'div',
                    className: 'btn-group btn-group-sm'
                }
            },
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn btn-outline-secondary',
                    text: '<i class="fas fa-copy"></i> Copy'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-outline-success',
                    text: '<i class="fas fa-file-excel"></i> Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-outline-danger',
                    text: '<i class="fas fa-file-pdf"></i> PDF'
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-info',
                    text: '<i class="fas fa-print"></i> Print'
                }
            ]
        },
        responsive: true,
        order: [[1, 'asc']]
    });
});

    // Vehicle status toggle functionality
    $(document).ready(function() {
        function toggleFields() {
            const status = $('#vehicle_status').val();
            
            if (status === 'ব্যবহৃত') {
                $('.impairment-section, .repair-section').slideUp();
                $('#yearofimpairment, #causeofimpairment, #action_taken, #repair_status')
                    .prop('disabled', true).val('');
            } else if (status === 'ব্যবহার অনুপযোগী') {
                $('.impairment-section, .repair-section').slideDown();
                $('#yearofimpairment, #causeofimpairment, #action_taken, #repair_status')
                    .prop('disabled', false);
            }
        }

        $('#vehicle_status').change(toggleFields);
        toggleFields(); // Initialize on page load

        // Same for edit modal
        $('#edit_vehicle_status').change(function() {
            const status = $(this).val();
            
            if (status === 'ব্যবহৃত') {
                $('#edit_yearofimpairment, #edit_causeofimpairment, #edit_action_taken, #edit_repair_status')
                    .prop('disabled', true).val('');
            } else if (status === 'ব্যবহার অনুপযোগী') {
                $('#edit_yearofimpairment, #edit_causeofimpairment, #edit_action_taken, #edit_repair_status')
                    .prop('disabled', false);
            }
        });
    });

    // Edit vehicle modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const vehicleId = this.getAttribute('data-id');
                
                fetch(`get_vehicle.php?id=${vehicleId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate edit form fields
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_vehicle_status').value = data.vehicle_status;
                        document.getElementById('edit_reg_no').value = data.reg_no;
                        document.getElementById('edit_vehicle_type').value = data.vehicle_type;
                        document.getElementById('edit_vehicle_source').value = data.vehicle_source;
                        document.getElementById('edit_sourcing_buying_year').value = data.sourcing_buying_year;
                        document.getElementById('edit_driven_km').value = data.driven_km;
                        document.getElementById('edit_user_name').value = data.user_name;
                        document.getElementById('edit_user_designation').value = data.user_designation;
                        document.getElementById('edit_driver_name').value = data.driver_name;
                        document.getElementById('edit_driver_appt_type').value = data.driver_appt_type;
                        document.getElementById('edit_yearofimpairment').value = data.yearofimpairment;
                        document.getElementById('edit_causeofimpairment').value = data.causeofimpairment;
                        document.getElementById('edit_repair_status').value = data.repair_status;
                        document.getElementById('edit_action_taken').value = data.action_taken;
                        document.getElementById('edit_remarks').value = data.remarks;
                        
                        // Show modal
                        const editModal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
                        editModal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching vehicle data:', error);
                        alert('Error loading vehicle data. Please try again.');
                    });
            });
        });
    });

    // Form validation
    $(document).ready(function() {
        $('#addVehicleForm, #editVehicleForm').on('submit', function(e) {
            let isValid = true;
            
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
</script>

</body>
</html>