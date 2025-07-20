 <?php 
include 'vehicle_crud.php'; 
include 'include/header.php';
?>
<div class="container-fluid mt-2">
    <div class="row align-items-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            <h1 class="mb-4 text-uppercase fw-bold text-muted">BCIC Vehicle Database</h1>
            <h4 class="mb-4 text-uppercase fw-bold text-muted">Reports</h4>
            <div class="mb-3">
                <label for="vehicle_status" class="form-label fw-bold">Vehicle Status</label>
                <select id="vehicleStatus" class="form-select" onchange="filterData()">
                <option value="">সব দেখান</option>
                <option value="ব্যবহৃত">ব্যবহৃত</option>
                <option value="ব্যবহার অনুপযোগী">ব্যবহার অনুপযোগী</option>
            </select>

            <button type="button" class="btn btn-primary mb-3" onclick="printPreview()">
                <i class="fa fa-print" style="font-size:16px;"></i> Print Preview
            </button>

            <table class="table table-bordered" id="vehicleTable">
                <thead>
                    <tr>
                        <th>Reg No</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Source</th>
                        <th>Driven KM</th>
                        <th>User Name</th>
                        <th>Driver Name</th>
                    </tr>
                </thead>
                <tbody id="vehicleData">
                    <!-- Filtered data will be inserted here -->
                </tbody>
            </table>

            </div>
        </div>
        <div class="col-sm-4 text-end"></div>
 
</div>
</div>
</body>
</html>