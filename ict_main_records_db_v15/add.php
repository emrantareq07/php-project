<?php
session_name('ict_main_records_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('db/db.php');
include('includes/header.php');
?>



<div class="container-fluid">   
<div class="container-fluid mt-1 border p-2 rounded shadow">
    <h2 class="text-center m-0 fw-bold text-muted text-uppercase "> ICT Maintenance Records</h2>
    <h6 class="text-center m-0 fw-bold text-muted text-uppercase "> (ICT Division, BCIC.)</h6>
    
    <div class="d-flex justify-content-between align-items-start my-0">
        <!-- Left-aligned buttons -->
        <div>
            <a href="includes/add_designation.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Designation</a>
            <a href="includes/add_employee.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Employee</a>
            <a href="includes/add_division.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Division</a>
            <a href="includes/add_products.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Products</a>
        </div>

        <!-- Right-aligned buttons -->
        <div class="d-flex justify-content-end align-items-center gap-1 mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fa fa-plus"></i> Add Records
            </button>
            <a href="multi_searching.php" class="btn btn-primary">
                <i class="fa fa-search"></i> Multi-Search
            </a>
            <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                <button class="btn btn-warning" type="submit" name="submit">
                    <i class="fa fa-download"></i> Download DB
                </button>
            </form>
            <a href="includes/logout.php" class="btn btn-danger">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </div>

    </div>
<!-- DataTable -->
    <table id="recordsTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <!-- <th>ID</th> -->               
                <th>EMP ID</th> 
                <th>User Name</th>

                <th>Req. No</th>
                <th>Req. Date</th>
                <th>Product Name</th>
                <th>Serial No</th>
                <th>SRM</th>
                <th>SRM Ref. No.</th>
                <th>Bill/Challan No</th>
                <th>Remarks</th>
                <!-- <th>Vendor Name</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be dynamically loaded via PHP and AJAX -->
        </tbody>
    </table>
</div>
</div>

<!-- Add Record Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <!-- <div class="modal-dialog modal-lg"> -->  
        <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-muted" id="addModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST">
                     <div class="row g-2">
                    <div class="col-md">
                        <label for="requisition_no" class="form-label">Requisition No</label>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" required readonly>
                    </div>

                    <div class="col-md">
                        <label for="date" class="form-label">Requisition Date</label>
                    <input type="date" class="form-control" id="date" name="date" oninput="updateData();" required>
                    </div>
                </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="emp_id" class="form-label">EMP ID</label>
                            <input list="emp_ids" 
                                   type="text" 
                                   class="form-control" 
                                   id="emp_id" 
                                   name="emp_id" 
                                   placeholder="Enter EMP ID" 
                                   pattern="[0-9\-]+" 
                                   title="EMP ID must contain only digits and the '-' symbol." 
                                   required 
                                   onchange="fetchEmployeeDetails(this.value)">
                            <datalist id="emp_ids">
                                <?php
                                    $sqlemp_id = "SELECT emp_id FROM employees";
                                    $resultemp_id = mysqli_query($conn, $sqlemp_id);
                                    while ($rowemp_id = mysqli_fetch_array($resultemp_id)) {
                                        echo "<option value='" . htmlspecialchars($rowemp_id['emp_id'], ENT_QUOTES, 'UTF-8') . "'>";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <div class="col-md">
                            <label for="user_name" class="form-label">User Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="user_name" 
                                   name="user_name" 
                                   placeholder="Enter full name" 
                                   required>
                        </div>                        
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="division_dept" class="form-label">Division/Dept</label>
                            <input list="division_depts" 
                                   id="division_dept" 
                                   name="division_dept" 
                                   class="form-control" placeholder="Select Division/Dept"
                                   required>
                            <datalist id="division_depts">
                                <?php
                                    $sql = "SELECT division FROM division ORDER BY division ASC";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='" . htmlspecialchars($row['division'], ENT_QUOTES, 'UTF-8') . "'>";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label for="designation" class="form-label">Designation</label>
                            <input list="designations" 
                                   id="designation" 
                                   name="designation" 
                                   class="form-control" placeholder="Select Designation"
                                   required>
                            <datalist id="designations">
                                <?php
                                    $sql = "SELECT designation FROM designation ORDER BY designation ASC";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='" . htmlspecialchars($row['designation'], ENT_QUOTES, 'UTF-8') . "'>";
                                    }
                                ?>
                            </datalist>
                        </div>
                    </div>  

                    <div class="row g-2">
                        <div class="col-md"><label for="product_name" class="form-label">Product Name</label>
    
            <input list="product_list" class="form-control" id="my-input" autocomplete="off">
            
            <!-- Datalist for product selection -->
            <datalist id="product_list"></datalist>

            <!-- Selected Products Display -->
            <div id="selected-products" class="mt-2"></div>

            <!-- Hidden input to store selected product IDs -->
            <input type="hidden" id="selected_product_ids" name="selected_product_ids">
                        </div>


                    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    setDefaultDateAndUpdate();

    document.getElementById("addModal").addEventListener("shown.bs.modal", function () {
        setDefaultDateAndUpdate(); // Reset date and call updateData when modal opens
    });

    function setDefaultDateAndUpdate() {
        const dateField = document.getElementById('date');
        dateField.value = new Date().toISOString().split('T')[0]; // Set today's date
        updateData(); // Ensure updateData() is called after setting the date
    }
});

// Function to update both requisition number and product list
function updateData() {
    datelist();  // Fetch requisition number
    productlist();  // Fetch product list
}

// Function to fetch requisition number based on date
function datelist() {
    let selectedDate = document.getElementById('date').value;

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_requisition_id.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    document.getElementById('requisition_no').value = response.requisition_no || "";
                } catch (e) {
                    console.error("Invalid JSON response:", xhr.responseText);
                }
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

// Function to fetch product list based on date
function productlist() {
    let selectedDate = document.getElementById('date').value;
    let productList = document.getElementById('product_list');

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_products.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    productList.innerHTML = response.product_options;
                } catch (e) {
                    console.error("Invalid JSON response:", xhr.responseText);
                }
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

// Store selected products
// Store selected products and their corresponding amounts
let selectedProducts = {};

// Function to handle product selection
document.getElementById("my-input").addEventListener("input", function () {
    let input = this.value.trim();
    let datalistOptions = document.getElementById("product_list").options;
    let hiddenInput = document.getElementById("selected_product_ids");
    let selectedDisplay = document.getElementById("selected-products");

    for (let i = 0; i < datalistOptions.length; i++) {
        if (input === datalistOptions[i].value) {
            let productId = datalistOptions[i].getAttribute("data-id");

            if (!selectedProducts[productId]) {
                selectedProducts[productId] = input;

                // Create a tag-like display
                let span = document.createElement("span");
                span.className = "badge bg-primary m-1 d-inline-flex align-items-center";
                span.textContent = input;
                span.setAttribute("data-id", productId);

                // Add a remove button
                let removeBtn = document.createElement("button");
                removeBtn.className = "btn-close btn-close-white ms-2";
                removeBtn.setAttribute("aria-label", "Remove");
                removeBtn.type = "button";

                // Add event listener to remove product
                removeBtn.addEventListener("click", function () {
                    delete selectedProducts[productId];
                    span.remove();
                    removeAmountInput(productId); // Remove the corresponding amount input
                    updateHiddenInput();
                });

                span.appendChild(removeBtn);
                selectedDisplay.appendChild(span);

                // Add amount input for the selected product
                let amountInput = document.createElement("div");
                amountInput.className = "mt-2";
                amountInput.setAttribute("id", "amount-container-" + productId); // Assign unique ID to amount container
                amountInput.innerHTML = `
                    <label for="amount_${productId}">Amount for ${input}</label>
                    <input type="number" class="form-control" id="amount_${productId}" name="amounts[${productId}]" placeholder="Enter amount for ${input}">
                `;
                selectedDisplay.appendChild(amountInput);
            }

            // Reset input field
            this.value = "";
            break;
        }
    }

    updateHiddenInput();
});

// Function to remove the corresponding amount input when a product is deleted
function removeAmountInput(productId) {
    let amountInputContainer = document.getElementById("amount-container-" + productId);
    if (amountInputContainer) {
        amountInputContainer.remove();
    }
}

function updateHiddenInput() {
    let hiddenInput = document.getElementById("selected_product_ids");
    hiddenInput.value = Object.keys(selectedProducts).join(",");
}


</script>
         <div class="row g-2">
                        <div class="col-md">
                            <label for="srm" class="form-label">SRM</label>
                            <input type="text" class="form-control" id="srm" name="srm"  placeholder="Enter SRM">
                        </div>
                        <div class="col-md">
                            <label for="srm_ref_no" class="form-label">SRM Ref. No.</label>
                            <input type="text" class="form-control" id="srm_ref_no" name="srm_ref_no" placeholder="Enter SRM Ref. No.">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                        <label for="bill_or_challan_no" class="form-label">Bill/Challan No</label>
                        <input type="text" class="form-control" id="bill_or_challan_no" name="bill_or_challan_no" placeholder="Enter Bill/Challan No">
                        </div>
                    
                  <div class="col-md">
                        <label for="remarks" class="form-label fw-bold text-muted">Remarks</label>
                        <select class="form-select" 
                                id="remarks" 
                                name="remarks" 
                                required 
                                onchange="toggleVendorField()">
                            <!-- <option value="" disabled selected>--Select--</option> -->
                            <option value="Done By Vendor">Done By Vendor</option>
                            <option value="Done By ICT">Done By ICT</option>                            
                            <option value="Condemn">Condemn</option>
                        </select>
                    </div>

                    <div class="col-md">
                        <label for="vendor_name" class="form-label">Vendor Name</label>
                        <select class="form-select" 
                                id="vendor_name" 
                                name="vendor_name" 
                                >
                           
                            <?php
                                $sql = "SELECT vendor_name FROM vendor_list ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['vendor_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['vendor_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                            ?>
                        </select>
                    </div> 
                </div>

                    <button type="submit" class="btn btn-primary float-end my-2" name="submit"><i class="fa fa-save"></i> Add Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>

$(document).ready(function () {
    // Function to toggle vendor field based on remarks selection
    function toggleVendorField() {
        const remarksValue = $('#remarks').val();
        const vendorField = $('#vendor_name');

        if (remarksValue === 'Done By Vendor') {
            vendorField.prop('disabled', false); // Enable vendor select

            // Set the last vendor in the dropdown automatically
            const lastVendorOption = vendorField.find('option:last'); // Get the last option in the dropdown
            vendorField.val(lastVendorOption.val()); // Set the value of the vendor dropdown to the last vendor
        } else {
            vendorField.prop('disabled', true).val(''); // Disable and clear selection
        }
    }

    // Initialize on page load and attach the change event
    toggleVendorField();
    $('#remarks').on('change', toggleVendorField);
});

// Reset form fields when the modal is closed
$('#addModal').on('hidden.bs.modal', function () {
    $('#addForm')[0].reset(); // Reset all form fields
});

$(document).ready(function () {
    // Trigger AJAX when the EMP ID field changes
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        if (empId === "") {
            // Clear fields if no EMP ID is provided
            $('#user_name').val("");
            $('#designation').val("");
            $('#division_dept').val("");
            $('#place_of_posting').val("");
            return;
        }

        // AJAX request to fetch employee details
        $.ajax({
            url: "fetch_employee.php", // PHP file to fetch data
            type: "POST",
            data: { emp_id: empId },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Fill the form fields with fetched data
                        $('#user_name').val(data.user_name);
                        $('#designation').val(data.designation);
                        $('#division_dept').val(data.division_dept);
                        $('#place_of_posting').val(data.place_of_posting);
                    } else {
                        alert("Employee is New. Now Insert Details....");
                        // Clear fields if no data found
                        $('#user_name').val("");
                        $('#designation').val("");
                        $('#division_dept').val("");
                        $('#place_of_posting').val("");
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                   alert("Invalid server response.");
                }
            },
            error: function () {
                alert("An error occurred while fetching employee details.");
            }
        });
    });
});

</script>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $date = $_POST['date']; // Get the selected date

    // Fetch fiscal year for the given date
    // Fetch products available for the selected date within a fiscal year
    $sql = "SELECT id, name FROM product_tbl WHERE '$date' BETWEEN fiscal_start AND fiscal_end";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option data-id='" . $row['id'] . "' value='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "'></option>";
        }
    } else {
        echo "<option value=''>No products available</option>";
    }

    exit();


     
}


?>


<!-- Edit Record Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-muted" id="editModalLabel">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <!-- Hidden Field for Record ID -->
                    <input type="hidden" id="edit_id" name="id">
                   

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_requisition_no" class="form-label">Requisition No</label>
                            <input type="text" class="form-control" id="edit_requisition_no" name="requisition_no" required>
                        </div>
                        <div class="col-md">
                            <label for="edit_date" class="form-label">Requisition Date</label>
                            <!-- <input type="date" class="form-control" id="edit_date" name="date" required> -->
                         <input type="date" class="form-control" id="edit_date" name="date" value="<?php echo $edit_date; ?>" oninput="editProductList()">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_emp_id" class="form-label">EMP ID</label>
                            <input list="emp_ids" type="text" class="form-control" id="edit_emp_id" name="emp_id" required onchange="fetchEmployeeDetails(this.value)">
                        </div>
                        <div class="col-md">
                            <label for="edit_user_name" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="edit_user_name" name="user_name" required>
                        </div>
                        
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_division_dept" class="form-label">Division/Dept</label>
                            <input list="division_depts" id="edit_division_dept" name="division_dept" class="form-control" required>
                        </div>
                        <div class="col-md">
                            <label for="edit_designation" class="form-label">Designation</label>
                            <input list="designations" id="edit_designation" name="designation" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row g-2">  
                    <div class="col-md">
                            <label for="edit_product_name" class="form-label">Product Name</label>
                            <input list="edit_product_list" class="form-control" placeholder="Select a product" id="edit_product_name" name="product_name_display" required oninput="editProductList_reload()">
                            <datalist id="edit_product_list">
                                <?php //echo $editOptions; ?> <!-- Load default products -->
                            </datalist>
                                                                                                                                                                                                                                 
                            <input type="hidden" id="edit_product_id" name="product_id">
                        </div>                    
                    <script>


function editProductList_reload() {
    let selectedDate = document.getElementById('edit_date').value;
    console.log("Selected Edit Date: ", selectedDate); // Debugging

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "", true); // Same page request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log("Response: ", xhr.responseText); // Debugging output
                document.getElementById('edit_product_list').innerHTML = xhr.responseText;
            } else {
                console.error('Error: ' + xhr.status);
            }
        }
    };

    xhr.send("date=" + encodeURIComponent(selectedDate)); // Send date parameter via POST
}

// Automatically load products when the page loads
window.onload = function() {
    editProductList_reload();
};



function editProductList() {
        let selectedDate = document.getElementById('edit_date').value;
        console.log("Selected Edit Date: ", selectedDate); // Debugging

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // Same page request
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    console.log("Response: ", xhr.responseText); // Debugging output
                    document.getElementById('edit_product_list').innerHTML = xhr.responseText;
                } else {
                    console.error('Error: ' + xhr.status);
                }
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate)); // Send date parameter via POST
    }






                    document.getElementById("edit_product_name").addEventListener("input", function() {
                        let input = this.value;
                        let datalistOptions = document.getElementById("edit_product_list").options;
                        let hiddenInput = document.getElementById("edit_product_id");
                        hiddenInput.value = "";
                        for (let i = 0; i < datalistOptions.length; i++) {
                            if (datalistOptions[i].value === input) {
                                hiddenInput.value = datalistOptions[i].getAttribute("data-id");
                                break;
                            }
                        }
                    });
                    </script>

                        <div class="col-md">
                            <label for="edit_p_sn" class="form-label">Serial No</label>
                            <input type="text" class="form-control" id="edit_p_sn" name="p_sn" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_srm" class="form-label">SRM</label>
                            <input type="text" class="form-control" id="edit_srm" name="srm">
                        </div>
                        <div class="col-md">
                            <label for="edit_srm_ref_no" class="form-label">SRM Ref. No.</label>
                            <input type="text" class="form-control" id="edit_srm_ref_no" name="srm_ref_no">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_bill_or_challan_no" class="form-label">Bill/Challan No</label>
                            <input type="text" class="form-control" id="edit_bill_or_challan_no" name="bill_or_challan_no">
                        </div>
                        
                        <div class="col-md">
                            <label for="edit_remarks" class="form-label fw-bold text-muted">Remarks</label>
                            <select class="form-select" id="edit_remarks" name="remarks" required onchange="toggleEditVendorField1()">
                                <!-- <option value="" disabled selected>--Select--</option> -->
                                <option value="Done By Vendor">Done By Vendor</option>
                                <option value="Done By ICT">Done By ICT</option>
                                <option value="abc">abc</option>
                            </select>
                        </div>
                       <div class="col-md">
                        <label for="edit_vendor_name" class="form-label">Vendor Name</label>
                        <select class="form-select" id="edit_vendor_name" name="vendor_name" readonly>
                            <!-- <option value="" disabled selected>--Select--</option> -->
                            <?php
                            // Query to fetch vendor names from the database
                            $sql_vendor_name = "SELECT vendor_name FROM vendor_list;";
                            $result_vendor_name = mysqli_query($conn, $sql_vendor_name);

                            // Check if the query executed successfully
                            if ($result_vendor_name) {
                                // Fetch each row and add it as an option
                                while ($row_vendor_name = mysqli_fetch_array($result_vendor_name)) {
                                    echo "<option value='" . htmlspecialchars($row_vendor_name['vendor_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row_vendor_name['vendor_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                            } else {
                                // Display an error message if the query fails
                                echo "<option value='' disabled>Error fetching vendors</option>";
                            }
                            ?>
                        </select>
                    </div>

                    </div>

                    <button type="submit" class="btn btn-success float-end my-2"><i class="fa fa-save"></i> Update Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
// Handle Edit Record
        $('#recordsTable').on('click', '.edit-btn', function () {
            var id = $(this).data('id'); // Get the ID of the record to edit
            $.ajax({
                url: 'fetch_single_record.php', // PHP script to fetch a single record
                type: 'POST',
                data: { id: id },
                dataType: 'json', // Expect JSON response from server
                success: function (response) {
                    if (response.status === "success") {
                        var record = response.data;
                        // Populate edit modal fields
                        $('#edit_id').val(record.id);
                        $('#edit_requisition_no').val(record.requisition_no);
                        $('#edit_emp_id').val(record.emp_id);
                        $('#edit_user_name').val(record.user_name);
                        $('#edit_division_dept').val(record.division_dept);
                        $('#edit_designation').val(record.designation);
                        $('#edit_place_of_posting').val(record.place_of_posting);
                        //$('#edit_mobile_pabx').val(record.moblie_pabx);
                        $('#edit_date').val(record.date);
                        $('#edit_product_name').val(record.product_name);
                        $('#edit_p_sn').val(record.p_sn);
                        $('#edit_srm').val(record.srm);
                        $('#edit_srm_ref_no').val(record.srm_ref_no);
                        $('#edit_bill_challan_no').val(record.bill_or_challan_no);
                        $('#edit_remarks').val(record.remarks);
                        $('#edit_vendor_name').val(record.vendor_name);

                        // Show the edit modal
                        $('#editModal').modal('show');
                    } else {
                        alert('Error: ' + response.message); // Show error message
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('An error occurred while fetching the record.');
                }
            });
        });

});


// Handle Update Record
$('#editForm').submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Serialize form data
    var formData = $(this).serialize();

    $.ajax({
        url: 'update_record.php', // PHP script to handle updating record
        type: 'POST',
        data: formData, // Send serialized form data
        dataType: 'json', // Expect a JSON response
        success: function (response) {
            if (response.status === 'success') {
                // Show success message
                alert(response.message);

                // Close the modal
                $('#editModal').modal('hide');

                // Reload the DataTable (maintaining the current pagination)
                //table.ajax.reload(null, false); 
                $('#recordsTable').DataTable().ajax.reload(null, false); 
            } else {
                // Show error message
                alert('Error: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            // Log the error for debugging
            console.error('AJAX Error:', error);
            console.error('Response:', xhr.responseText);

            // Show generic error message
            alert('An error occurred while updating the record.');
        }
    });
});


$(document).ready(function () {
    // Function to toggle vendor field based on remarks selection for editing
    function toggleEditVendorField1() {
        const remarksValue = $('#edit_remarks').val();
        const vendorField = $('#edit_vendor_name');

        if (remarksValue === 'Done By Vendor') {
            vendorField.prop('disabled', false); // Enable vendor select field

            // Set the last vendor in the dropdown automatically
            const lastVendorOption = vendorField.find('option:last'); // Get the last option in the dropdown
            vendorField.val(lastVendorOption.val()); // Set the value of the vendor dropdown to the last vendor
        } else {
            vendorField.prop('disabled', true).val(''); // Disable and clear selection
        }
    }

    // Attach change event and initialize on page load
    $('#edit_remarks').on('change', toggleEditVendorField1);
    toggleEditVendorField1(); // Run on page load to set the correct state
});

</script>


<!-- View Modal HTML -->
<div id="viewEmployeeModal" class="modal fade" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-uppercase text-center text-muted flex-grow-1" id="viewEmployeeModalLabel">
                    <b>রেকর্ডের বিস্তারিত তথ্য</b>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" id="printableArea">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>আইডি:</th>
                            <td id="view_id"></td>
                        </tr>
                        <tr>
                            <th>রিকুইজিশন নং:</th>
                            <td id="view_requisition_no"></td>
                        </tr>
                        <tr>
                            <th>কর্মচারী আইডি:</th>
                            <td id="view_emp_id"></td>
                        </tr>
                        <tr>
                            <th>ব্যবহারকারীর নাম:</th>
                            <td id="view_user_name"></td>
                        </tr>
                        <tr>
                            <th>ডিভিশন/ডিপার্টমেন্ট:</th>
                            <td id="view_division_dept"></td>
                        </tr>
                        <tr>
                            <th>পদবী:</th>
                            <td id="view_designation"></td>
                        </tr>
                        <tr>
                            <th>পোস্টিং স্থান:</th>
                            <td id="view_place_of_posting"></td>
                        </tr>
                        <tr>
                            <th>মোবাইল/PABX:</th>
                            <td id="view_mobile_pabx"></td>
                        </tr>
                        <tr>
                            <th>তারিখ:</th>
                            <td id="view_date"></td>
                        </tr>
                        <tr>
                            <th>প্রোডাক্টের নাম:</th>
                            <td id="view_product_name"></td>
                        </tr>
                        <tr>
                            <th>সিরিয়াল নং:</th>
                            <td id="view_p_sn"></td>
                        </tr>
                        <tr>
                            <th>এসআরএম:</th>
                            <td id="view_srm"></td>
                        </tr>
                        <tr>
                            <th>এসআরএম রেফারেন্স নং:</th>
                            <td id="view_srm_ref_no"></td>
                        </tr>
                        <tr>
                            <th>বিল/চালান নং:</th>
                            <td id="view_bill_or_challan_no"></td>
                        </tr>
                        <tr>
                            <th>মন্তব্য:</th>
                            <td id="view_remarks"></td>
                        </tr>
                        <tr>
                            <th>ভেন্ডরের নাম:</th>
                            <td id="view_vendor_name"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="printButton"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

document.getElementById('printButton').addEventListener('click', function() {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center">রেকর্ডের বিস্তারিত তথ্য</h1>';
    // Create a container for the content to be printed
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = `
        <html>
        <head>
            <title>রেকর্ডের বিস্তারিত তথ্য</title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @font-face {
                    font-family: 'Nikosh', Times, serif;
                    src: url(Nikosh.ttf);
                }

                .imgcontainer {
                    text-align: center;
                    margin: 5px 0 12px 0;
                    position: relative;
                }

                img.avatar {
                    width: 25%;
                    border-radius: 50%;
                }

                * {
                    font-family: 'Open Sans', sans-serif;
                    font-family: 'Tiro Bangla', serif;
                    font-family: 'Nikosh', sans-serif;
                }
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;
    // Trigger the print dialog
    window.print();
    // Restore the original contents of the page after printing
    document.body.innerHTML = originalContents;    
    // Reload the page to reflect the original content and avoid any loss of functionality
    window.location.reload();
});
</script>


<!-- JavaScript Logic -->
<script>
$(document).ready(function() {
    var table = $('#recordsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'fetch_records.php',
            type: 'POST',
            dataSrc: function(json) {
                console.log('DataTable JSON Response:', json);
                return json.data;
            }
        },
        columns: [
            {
                data: 'emp_id',
                className: 'text-center align-middle' // Bootstrap classes for centering
            },
            {
                data: null, // Use null when combining multiple fields
                render: function (data, type, row) {
                    return `${row.user_name} <br> <span style="font-size: 0.80rem;">${row.designation} <br> ${row.division_dept}</span>`;
                }
            },
            { data: 'requisition_no', className: 'text-center align-middle' },
            { data: 'date', className: 'text-center align-middle' },
            {
                data: 'product_name',
                className: 'text-center align-middle',
                render: function(data) {
                    return data ? data : "N/A"; // Handle case where product_name might be null
                }
            },
            { data: 'p_sn', className: 'text-center align-middle' },
            { data: 'srm', className: 'text-center align-middle' },
            { data: 'srm_ref_no', className: 'text-center align-middle' },
            { data: 'bill_or_challan_no', className: 'text-center align-middle' },
            {
                data: null,
                className: 'text-center align-middle',
                render: function(data, type, row) {
                    // Rendering remarks and vendor name
                    if (row.remarks && row.vendor_name) {
                        return `${row.remarks} <br> <span class="small">(${row.vendor_name})</span>`;
                    } else if (row.remarks) {
                        return row.remarks;
                    } else if (row.vendor_name) {
                        return `<span class="small">${row.vendor_name}</span>`;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: null,
                className: 'text-center align-middle', // Center content horizontally and vertically
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm view-btn" data-id="${data.id}"><i class="fa fa-eye"></i></button>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${data.id}"><i class="fa fa-edit"></i> </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}"><i class="fa fa-trash"></i></button>
                    `;
                }
            }
        ]
    });

    // Form submission handling
$('#addForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Collect the amounts from the dynamically generated input fields
    let amounts = {};
    $('input[name^="amounts"]').each(function () {
        let productId = $(this).attr('name').match(/\[(.*?)\]/)[1]; // Extract product ID from the name
        amounts[productId] = $(this).val(); // Get the amount for this product
    });

    // Append the amounts to the form data
    let formData = $(this).serialize(); // Serialize form data
    for (let productId in amounts) {
        formData += `&amounts[${productId}]=${amounts[productId]}`; // Append the amount data
    }

    $.ajax({
        url: 'add_record.php', // Your PHP script for adding records
        method: 'POST',
        data: formData, // Send serialized form data including amounts
        dataType: 'json', // Ensure the response is parsed as JSON
        success: function (response) {
            if (response.status === "success") {
                // Show success message
                alert(response.message);

                // Reset the form
                $('#addForm')[0].reset();
                $('#selected-products').html(''); // Clear selected product tags
                selectedProducts = {}; // Reset selected products list

                // Reload the DataTable
                table.ajax.reload(null, false); // false keeps the current pagination

                // Hide the modal (Bootstrap 5)
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                if (modalInstance) {
                    modalInstance.hide(); // Properly hide the modal
                }
            } else {
                // Show error message
                alert('Error: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            // Log the error for debugging purposes
            console.error('AJAX Error:', error);
            console.error('Response:', xhr.responseText);

            // Show a generic error message
            alert('An error occurred while adding the record.');
        }
    });
});



    // Handle "View" Button Click
    $('#recordsTable').on('click', '.view-btn', function () {
        var id = $(this).data('id'); // Get the record ID
        $.ajax({
            url: 'fetch_single_record.php', // Backend script to fetch data
            type: 'POST',
            data: { id: id },
            success: function (response) {
                var record = JSON.parse(response); // Parse the JSON response
                if (record.status === 'success') {
                    var data = record.data;  // Access the 'data' property
                    // Populate the modal fields with the data
                    $('#view_id').text(data.id);
                    $('#view_requisition_no').text(data.requisition_no);
                    $('#view_emp_id').text(data.emp_id);
                    $('#view_user_name').text(data.user_name);
                    $('#view_division_dept').text(data.division_dept);
                    $('#view_designation').text(data.designation);
                    $('#view_place_of_posting').text(data.place_of_posting);
                    $('#view_mobile_pabx').text(data.mobile_pabx);
                    $('#view_date').text(data.date);
                    $('#view_product_name').text(data.product_name);
                    $('#view_p_sn').text(data.p_sn);
                    $('#view_srm').text(data.srm);
                    $('#view_srm_ref_no').text(data.srm_ref_no);
                    $('#view_bill_or_challan_no').text(data.bill_or_challan_no);
                    $('#view_remarks').text(data.remarks);
                    $('#view_vendor_name').text(data.vendor_name);

                    // Show the modal
                    $('#viewEmployeeModal').modal('show');
                } else {
                    alert(record.message); // Display error message if any
                }
            },
            error: function () {
                alert('Failed to fetch the record. Please try again.');
            },
        });
    });

    // Handle Delete Record
    $('#recordsTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: 'delete_record.php',  // PHP script to handle deletion
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    table.ajax.reload();
                }
            });
        }
    });
});

</script>

</body>
</html>