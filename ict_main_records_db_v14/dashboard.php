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
            <a href="dateRange_searching.php" class="btn btn-primary">
                <i class="fa fa-search"></i> Date Range-Search
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
                <!-- <th>Serial No</th> -->
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
                            <input type="date" class="form-control" id="date" name="date" required>
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

                    <div class="row g-2 mt-3">                       
                        <div class="col-md">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input list="product_list" class="form-control" id="my-input" autocomplete="off">
                            <datalist id="product_list"></datalist>
                            <div id="selected-products" class="mt-2"></div>
                            <input type="hidden" id="selected_product_ids" name="selected_product_ids">
                            <input type="hidden" id="selected_amounts" name="selected_amounts">
                            <input type="hidden" id="selected_p_sn" name="selected_p_sn">
                        </div>
                    </div>

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
                    <button type="submit" class="btn btn-primary float-end my-2">
                        <i class="fa fa-save"></i> Add Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    setDefaultDateAndUpdate();

    // When modal is opened, reset and update fields
    document.getElementById("addModal").addEventListener("shown.bs.modal", function () {
        setDefaultDateAndUpdate();
    });

    // When date changes, update requisition number and product list
    document.getElementById("date").addEventListener("input", function () {
        updateData();
    });

    function setDefaultDateAndUpdate() {
        const dateField = document.getElementById('date');
        dateField.value = new Date().toISOString().split('T')[0]; // Set today's date
        updateData();
    }
});

// Function to update requisition number and product list
function updateData() {
    datelist();
    productlist();
}

// Fetch requisition number
function datelist() {
    let selectedDate = document.getElementById('date').value;

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_requisition_id.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                document.getElementById('requisition_no').value = response.requisition_no || "";
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

// Fetch product list
function productlist() {
    let selectedDate = document.getElementById('date').value;
    let productList = document.getElementById('product_list');

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_products.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                productList.innerHTML = response.product_options;
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

let selectedProducts = {};

//for reset modal
document.getElementById("addModal").addEventListener("hidden.bs.modal", function () {
    selectedProducts = {}; // Clear the selected products object
    document.getElementById("selected-products").innerHTML = ""; // Clear the display
    document.getElementById("selected_product_ids").value = ""; // Clear hidden input
    document.getElementById("selected_amounts").value = ""; // Clear hidden input
    document.getElementById("selected_p_sn").value = ""; // Clear hidden input
    document.getElementById("my-input").value = ""; // Clear the product input field
    document.getElementById("addForm").reset(); // Reset the entire form
    setDefaultDateAndUpdate(); // Reset the date field and update data
});
//close
document.getElementById("my-input").addEventListener("input", function () {
    let input = this.value.trim();
    let datalistOptions = document.getElementById("product_list").options;
    let selectedDisplay = document.getElementById("selected-products");

    for (let i = 0; i < datalistOptions.length; i++) {
        if (input === datalistOptions[i].value) {
            let productId = datalistOptions[i].getAttribute("data-id");
            let productSerial = datalistOptions[i].getAttribute("data-serial"); // Get p_sn value

                if (selectedProducts[productId]) {
                alert("This product is already selected!");
                this.value = ""; // Clear the input field
                return; // Stop further execution
            }

            if (!selectedProducts[productId]) {
                selectedProducts[productId] = { 
                    name: input, 
                    amount: "0", 
                    p_sn: "0" // Always default to "0"
                };

                let div = document.createElement("div");
                div.className = "d-flex align-items-center mb-2";
                div.setAttribute("data-id", productId);

                let span = document.createElement("span");
                span.className = "badge bg-primary p-2 me-2";
                span.textContent = input;

                let amountInput = document.createElement("input");
                amountInput.type = "number";
                amountInput.className = "form-control me-2";
                amountInput.style.width = "100px";
                amountInput.placeholder = "Amount";
                amountInput.value = "0"; // Start with 0

                let pSnInput = document.createElement("input");
                pSnInput.type = "text";
                pSnInput.className = "form-control me-2";
                pSnInput.style.width = "150px";
                pSnInput.placeholder = "Product Serial No";
                pSnInput.value = "0"; // Always default to 0

                // Event listeners for amount field
                amountInput.addEventListener("focus", function () {
                    if (this.value === "0") this.value = "";
                });

                amountInput.addEventListener("blur", function () {
                    if (this.value.trim() === "") this.value = "0";
                    selectedProducts[productId].amount = this.value;
                    updateHiddenInputs();
                });

                amountInput.addEventListener("input", function () {
                    selectedProducts[productId].amount = this.value.trim() === "" ? "0" : this.value;
                    updateHiddenInputs();
                });

                // Event listener for p_sn field
                pSnInput.addEventListener("focus", function () {
                    if (this.value === "0") this.value = "";
                });

                pSnInput.addEventListener("blur", function () {
                    if (this.value.trim() === "") this.value = "0";
                    selectedProducts[productId].p_sn = this.value;
                    updateHiddenInputs();
                });

                pSnInput.addEventListener("input", function () {
                    selectedProducts[productId].p_sn = this.value.trim() === "" ? "0" : this.value;
                    updateHiddenInputs();
                });

                                    // Restrict comma input dynamically
                    pSnInput.addEventListener("keypress", function (event) {
                        if (event.key === ",") {
                            event.preventDefault(); // Block the comma from being typed
                        }
                    });

                    pSnInput.addEventListener("input", function () {
                        this.value = this.value.replace(/,/g, ""); // Remove any pasted commas
                        selectedProducts[productId].p_sn = this.value.trim() || "0";
                        updateHiddenInputs();
                    });
                    //RETRICT COMMA END

                let removeBtn = document.createElement("button");
                removeBtn.className = "btn btn-danger btn-sm";
                removeBtn.textContent = "X";
                removeBtn.addEventListener("click", function () {
                    delete selectedProducts[productId];
                    div.remove();
                    updateHiddenInputs();
                });

                div.appendChild(span);
                div.appendChild(amountInput);
                div.appendChild(pSnInput);
                div.appendChild(removeBtn);
                selectedDisplay.appendChild(div);
            }

            this.value = ""; // Clear the input field
            break;
        }
    }

    updateHiddenInputs();
});

// Update hidden inputs with selected product data
function updateHiddenInputs() {
    let productIds = Object.keys(selectedProducts);

    if (productIds.length === 0) {
        // If no products are selected, set default values
        document.getElementById("selected_product_ids").value = "0";
        document.getElementById("selected_amounts").value = "0";
        document.getElementById("selected_p_sn").value = "0";
    } else {
        // If products are selected, join their values
        let productAmounts = productIds.map(id => selectedProducts[id].amount.trim() || "0").join(",");
        let productSerials = productIds.map(id => selectedProducts[id].p_sn.trim() || "0").join(",");

        document.getElementById("selected_product_ids").value = productIds.join(",");
        document.getElementById("selected_amounts").value = productAmounts;
        document.getElementById("selected_p_sn").value = productSerials;
    }
}

// Ensure hidden inputs are updated before form submission
document.getElementById("addForm").addEventListener("submit", function (event) {
    updateHiddenInputs();
});

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
<!-- //add complete -->

<!-- Edit start -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-muted" id="editModalLabel">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="row g-2">
                        <div class="col-md">
                            <label for="edit_requisition_no" class="form-label">Requisition No</label>
                            <input type="text" class="form-control" id="edit_requisition_no" name="requisition_no" required readonly>
                        </div>
                        <div class="col-md">
                            <label for="edit_date" class="form-label">Requisition Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
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

                    <div class="row g-2 mt-3">
                        <div class="col-md">
                            <label for="edit_product_input" class="form-label">Product Name</label>
                            <input list="edit_product_list" class="form-control" id="edit_product_input" autocomplete="off">
                            <datalist id="edit_product_list"></datalist>
                            <div id="edit_selected_products" class="mt-2"></div>
                            <input type="hidden" id="edit_selected_product_ids" name="selected_product_ids">
                            <input type="hidden" id="edit_product_amounts" name="product_amounts">
                            <input type="hidden" id="edit_selected_p_sn" name="selected_p_sn">
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
<!-- ////stoppppp//// -->
<script>
$(document).ready(function () {
    // Trigger AJAX when the EMP ID field changes
    $('#edit_emp_id').on('change', function () {
        const empId = $(this).val().trim();

        if (empId === "") {
            // Clear fields if no EMP ID is provided
            $('#edit_user_name').val("");
            $('#edit_designation').val("");
            $('#edit_division_dept').val("");
            $('#edit_place_of_posting').val("");
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
                        $('#edit_user_name').val(data.user_name);
                        $('#edit_designation').val(data.designation);
                        $('#edit_division_dept').val(data.division_dept);
                        $('#edit_place_of_posting').val(data.place_of_posting);
                    } else {
                        alert("Employee is New. Now Insert Details....");
                        // Clear fields if no data found
                        $('#edit_user_name').val("");
                        $('#edit_designation').val("");
                        $('#edit_division_dept').val("");
                        $('#edit_place_of_posting').val("");
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


$(document).ready(function () {
    // Fetch and populate products dynamically when date changes
    $('#edit_date').on('change', function () {
        let selectedDate = $(this).val();
        fetchEditProductList(selectedDate);
    });

    // Open Edit Modal & Load Data
$('#recordsTable').on('click', '.edit-btn', async function () {
    var id = $(this).data('id');

    try {
        // Fetch the record data
        const response = await $.ajax({
            url: 'fetch_single_record.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json'
        });

        if (response.status === "success") {
            var record = response.data;

            // Populate modal fields
            $('#edit_id').val(record.id);
            $('#edit_requisition_no').val(record.requisition_no);
            $('#edit_emp_id').val(record.emp_id);
            $('#edit_user_name').val(record.user_name);
            $('#edit_division_dept').val(record.division_dept);
            $('#edit_designation').val(record.designation);
            $('#edit_place_of_posting').val(record.place_of_posting);
            $('#edit_moblie_pabx').val(record.moblie_pabx);
            $('#edit_date').val(record.date);
            $('#edit_srm').val(record.srm);
            $('#edit_srm_ref_no').val(record.srm_ref_no);
            $('#edit_bill_or_challan_no').val(record.bill_or_challan_no);
            $('#edit_remarks').val(record.remarks);
            $('#edit_vendor_name').val(record.vendor_name);

            // Clear previous product selections
            $('#edit_selected_products').html('');

            // Wait for the product list to load
            await fetchEditProductList(record.date);

            // Add products to the edit list
            if (record.products) {
                $.each(record.products, function (productId, data) {
                    addProductToEditList(productId, data.amount, data.p_sn);
                });
            }

            // Open modal only after everything is loaded
            $('#editModal').modal('show');
        } else {
            alert('Error: ' + response.message);
        }
    } catch (error) {
        console.error('AJAX Error:', error.responseText);
        alert('An error occurred while fetching the record.');
    }
});

    // Function to fetch product list
    function fetchEditProductList(date) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'get_products.php',
                type: 'POST',
                data: { date: date },
                dataType: 'json',
                success: function (response) {
                    $('#edit_product_list').html(response.product_options);
                    resolve();
                },
                error: function () {
                    console.error("Error fetching product list.");
                    reject();
                }
            });
        });
    }

    // Function to add product to the edit list in the modal
       function addProductToEditList(productId, amount, p_sn) {
        let productDisplay = $('#edit_selected_products');
        let productName = $('#edit_product_list option[data-id="' + productId + '"]').val();

        if (!productName) {
            console.error(`Product with ID ${productId} not found.`);
            return;
        }

        if ($('#edit_selected_products input[data-id="' + productId + '"]').length > 0) {
            alert('Product is already added.');
            return;
        }

        let row = $('<div class="input-group mb-2"></div>');
        row.append('<span class="input-group-text">' + productName + '</span>');

        let amountInput = $('<input type="number" class="form-control" placeholder="Amount" data-id="' + productId + '">').val(amount || 0); 
        let p_snInput = $('<input type="text" class="form-control" placeholder="P_SN" data-id="' + productId + '">').val(p_sn || '0');

        // Restrict comma input dynamically
        p_snInput.on('keypress', function (event) {
            if (event.key === ",") {
                event.preventDefault(); // Block the comma from being typed
            }
        });

        p_snInput.on('input', function () {
            this.value = this.value.replace(/,/g, ""); // Remove any pasted commas
            updateHiddenInputs();
        });

//END COMMA RESTRICTION
    amountInput.on('input', updateHiddenInputs);
    p_snInput.on('input', updateHiddenInputs);
    let removeBtn = $('<button class="btn btn-danger">X</button>').on('click', function () {
        row.remove();
        updateHiddenInputs();
    });
    row.append(amountInput);
    row.append(p_snInput);
    row.append(removeBtn);
    productDisplay.append(row);
    updateHiddenInputs();
    }

    function updateHiddenInputs() {
        let selectedProducts = {};
        let productAmounts = {};
        let productPSNs = {};

        $('#edit_selected_products .input-group').each(function () {
            let productId = $(this).find('input').first().data('id');
            let amount = $(this).find('input[placeholder="Amount"]').val().trim() || "0";
            let p_sn = $(this).find('input[placeholder="P_SN"]').val().trim() || "0";

            selectedProducts[productId] = productId;
            productAmounts[productId] = amount;
            productPSNs[productId] = p_sn;
        });

        $('#edit_selected_product_ids').val(Object.keys(selectedProducts).join(','));
        $('#edit_product_amounts').val(Object.values(productAmounts).join(','));
        $('#edit_selected_p_sn').val(Object.values(productPSNs).join(','));
    }

    // Handle the form submission to update the record
    $('#editForm').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'update_record.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#editModal').modal('hide');
                    $('#recordsTable').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr) {
                console.error('AJAX Error:', xhr.responseText);
                alert('An error occurred while updating the record.');
            }
        });
    });

    // Handle adding new products
    $('#edit_product_input').on('change', function () {
        let selectedProductName = $(this).val();
        let selectedProductOption = $('#edit_product_list option').filter(function () {
            return $(this).val() === selectedProductName;
        });

        if (selectedProductOption.length > 0) {
            let productId = selectedProductOption.data('id');
            addProductToEditList(productId, 0); // Add product with default amount 0
            $(this).val(''); // Clear the input field
        } else {
            alert('Please select a valid product from the list.');
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
                          <!--   <tr>
                                <th>ID:</th>
                                <td id="view_id"></td>
                            </tr> -->
                            <tr>
                                <th>Requisition No:</th>
                                <td id="view_requisition_no"></td>
                            </tr>
                            <tr>
                                <th>Employee ID:</th>
                                <td id="view_emp_id"></td>
                            </tr>
                            <tr>
                                <th>User Name:</th>
                                <td id="view_user_name"></td>
                            </tr>
                            <tr>
                                <th>Division/Department:</th>
                                <td id="view_division_dept"></td>
                            </tr>
                            <tr>
                                <th>Designation:</th>
                                <td id="view_designation"></td>
                            </tr>
                            <tr>
                                <th>Place of Posting:</th>
                                <td id="view_place_of_posting"></td>
                            </tr>
                            
                            <tr>
                                <th>Requisition Date:</th>
                                <td id="view_date"></td>
                            </tr>
                            <tr>
                                <th>Product Name:</th>
                                <td id="view_product_name"></td>
                            </tr>
                          
                            <tr>
                                <th>SRM:</th>
                                <td id="view_srm"></td>
                            </tr>
                            <tr>
                                <th>SRM Reference No:</th>
                                <td id="view_srm_ref_no"></td>
                            </tr>
                            <tr>
                                <th>Bill/Challan No:</th>
                                <td id="view_bill_or_challan_no"></td>
                            </tr>
                            <tr>
                                <th>Remarks:</th>
                                <td id="view_remarks"></td>
                            </tr>
                            <tr>
                                <th>Vendor Name:</th>
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
       // Handle "View" Button Click
$('#recordsTable').on('click', '.view-btn', function () {
    var id = $(this).data('id'); // Get the record ID
    $.ajax({
        url: 'view_fetch_single_record.php', // Backend script to fetch data
        type: 'POST',
        data: { id: id },
        dataType: 'json', // Add this to automatically parse JSON
        success: function (record) {
            if (record.status === 'success') {
                var data = record.data;  // Access the 'data' property
                // Populate the modal fields with the data
                //$('#view_id').text(data.id);
                $('#view_requisition_no').text(data.requisition_no);
                $('#view_emp_id').text(data.emp_id);
                $('#view_user_name').text(data.user_name);
                $('#view_division_dept').text(data.division_dept);
                $('#view_designation').text(data.designation);
                $('#view_place_of_posting').text(data.place_of_posting);
                $('#view_mobile_pabx').text(data.mobile_pabx);
                $('#view_date').text(data.date);
                $('#view_product_name').html(record.product_names); // Use html() instead of text() for formatted content
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
        // dom: 'Blfrtip', // Add buttons to the DOM
       dom: '<"row mb-0"<"col-md-12 d-flex justify-content-center"B>>' +
       '<"row mb-0"<"col-md-6"l><"col-md-6"f>>' +
       '<"row"<"col-12"tr>>' +
       '<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
       
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-copy"></i> Copy',
                titleAttr: 'Copy to clipboard',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Strip HTML tags and handle line breaks for plain text
                            return data.replace(/<br\s*\/?>/gi, "\n")
                                      .replace(/<[^>]*>/g, "");
                        }
                    }
                }
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Export to Excel',
                className: 'btn btn-success btn-sm',
                filename: 'Records_Export_' + new Date().toISOString().slice(0, 10),
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Clean up HTML for Excel export
                            return data.replace(/<br\s*\/?>/gi, "\n")
                                      .replace(/<[^>]*>/g, "");
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                titleAttr: 'Export to PDF',
                className: 'btn btn-danger btn-sm',
                filename: 'Records_Export_' + new Date().toISOString().slice(0, 10),
                orientation: 'landscape', // Set landscape orientation
                pageSize: 'A4', // Optional: you can use 'A4', 'LEGAL', etc.
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Clean up HTML for PDF export
                            return data.replace(/<br\s*\/?>/gi, "\n")
                                      .replace(/<[^>]*>/g, "");
                        }
                    }
                },
                customize: function(doc) {
                    // Set font sizes
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    // Set margins
                    doc.pageMargins = [20, 40, 20, 40];

                    // Set column widths to auto-fit
                    var colCount = doc.content[1].table.body[0].length;
                    doc.content[1].table.widths = Array(colCount).fill('*');

                    // Add custom title
                    doc.content.unshift({
                        text: 'ICT Maintenance Records',
                        text: 'Design and Developed by ICT Division, BCIC',
                        margin: [0, 0, 0, 10],
                        alignment: 'center',
                        fontSize: 12,
                        bold: true
                    });
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                titleAttr: 'Print table',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Clean up HTML for print
                            return data.replace(/<br\s*\/?>/gi, "\n")
                                      .replace(/<[^>]*>/g, "");
                        }
                    }
                },
                customize: function(win) {
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10pt');
                    
                    $(win.document.body).find('h1')
                        .css('text-align', 'center')
                        .css('font-size', '14pt');
                    
                    $(win.document.body).find('th')
                        .css('background-color', '#f8f9fa')
                        .css('font-weight', 'bold');

                    // Add custom titles at the top of the PDF
                    $(win.document.body).find('table').before(`
                       
                        <div style="text-align: center; font-size: 10pt; font-style: italic; margin-bottom: 10px;">
                            (Design and Developed by ICT Division, BCIC)
                        </div>
                    `);
                }
            }
        ],
        columns: [
            {
                data: 'emp_id',
                className: 'text-center align-middle'
            },
            {
                data: null,
                render: function (data, type, row) {
                    // For export formats, return plain text
                    if (type === 'export') {
                        return row.user_name + '\n' + row.designation + '\n' + row.division_dept;
                    }
                    return `${row.user_name} <br> <span style="font-size: 0.80rem;">${row.designation} <br> ${row.division_dept}</span>`;
                }
            },
            { data: 'requisition_no', className: 'text-center align-middle' },
            { 
                data: 'date', 
                className: 'text-center align-middle',
                render: function(data, type) {
                    // Format date for display and export
                    if (type === 'display' || type === 'filter') {
                        return data ? new Date(data).toLocaleDateString() : 'N/A';
                    }
                    return data;
                }
            },
            {
                data: 'product_name',
                className: 'text-center align-middle',
                render: function(data) {
                    return data ? data : "N/A";
                }
            },
            { data: 'srm', className: 'text-center align-middle' },
            { data: 'srm_ref_no', className: 'text-center align-middle' },
            { data: 'bill_or_challan_no', className: 'text-center align-middle' },
            {
                data: null,
                className: 'text-center align-middle',
                render: function(data, type, row) {
                    // Handle different formats for display vs export
                    if (type === 'export') {
                        let result = '';
                        if (row.remarks) result += row.remarks;
                        if (row.remarks && row.vendor_name) result += '\n';
                        if (row.vendor_name) result += '(' + row.vendor_name + ')';
                        return result;
                    }
                    
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
                    className: 'text-center align-middle no-print',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return `
                                <button class="btn btn-primary btn-sm view-btn" data-id="${data.id}"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${data.id}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}"><i class="fa fa-trash"></i></button>
                            `;
                        }
                        return '';
                    }
                }
        ],        
        
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

    // Collect the p_sn values from the dynamically generated input fields
    let pSnValues = {};
    $('input[name^="p_sn"]').each(function () {
        let productId = $(this).attr('name').match(/\[(.*?)\]/)[1]; // Extract product ID from the name
        pSnValues[productId] = $(this).val(); // Get the p_sn value for this product
    });

    // Append the amounts and p_sn values to the form data
    let formData = $(this).serialize(); // Serialize form data
    for (let productId in amounts) {
        formData += `&amounts[${productId}]=${amounts[productId]}`; // Append the amount data
    }
    for (let productId in pSnValues) {
        formData += `&p_sn[${productId}]=${pSnValues[productId]}`; // Append the p_sn data
    }

    $.ajax({
        url: 'add_record.php', // Your PHP script for adding records
        method: 'POST',
        data: formData, // Send serialized form data including amounts and p_sn
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