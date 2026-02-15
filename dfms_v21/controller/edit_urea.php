<?php
session_name('dfms');
//Start the session
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

include('../db/db.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Production Data</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Gradient Theme */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .gradient-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .gradient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .card-header-gradient {
            background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
            color: white !important;
            border-bottom: none !important;
            padding: 20px !important;
            border-radius: 15px 15px 0 0 !important;
        }
        
        /* Form Styling */
        .form-control {
            border: 2px solid #e9ecef !important;
            border-radius: 10px !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
        }
        
        .form-control:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
            transform: translateY(-1px);
        }
        
        .form-label {
            font-weight: 600 !important;
            color: #495057 !important;
            margin-bottom: 8px !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .form-label i {
            margin-right: 8px !important;
            color: #667eea !important;
        }
        
        /* Button Styling */
        .btn {
            border-radius: 10px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3) !important;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
            color: white !important;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3) !important;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
            color: white !important;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3) !important;
        }
        
        /* Alert Styling */
        .alert {
            border-radius: 10px !important;
            border: none !important;
            padding: 15px 20px !important;
            font-weight: 500 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            animation: slideIn 0.5s ease-out;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
            color: white !important;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Factory Badge */
        .factory-badge {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%) !important;
            color: white !important;
            padding: 8px 20px !important;
            border-radius: 20px !important;
            font-weight: 600 !important;
            display: inline-block !important;
            box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3) !important;
        }
        
        /* Progress Indicator */
        .progress-indicator {
            height: 4px;
            background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%);
            border-radius: 2px;
            margin-top: 5px;
            transition: width 0.3s ease;
        }
        
        /* User Type Badge */
        .user-type-badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
            color: white !important;
            padding: 4px 12px !important;
            border-radius: 15px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
        
        /* Textarea Styling */
        textarea.form-control {
            min-height: 120px !important;
            resize: vertical !important;
        }
        
        /* Select Styling */
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
            appearance: none !important;
        }
        
        /* Readonly Input Styling */
        .form-control[readonly] {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
            cursor: not-allowed;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .gradient-bg {
                padding: 20px !important;
                margin-bottom: 20px !important;
            }
            
            .gradient-card {
                margin-bottom: 20px !important;
            }
            
            .btn {
                padding: 10px 15px !important;
                font-size: 14px !important;
            }
        }
        
        /* Animation for Form */
        .form-animate {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="gradient-bg">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-3 text-white">
                                <i class="fas fa-edit me-3"></i>
                                Edit Production Data
                            </h2>
                            <p class="mb-0 text-white-75">
                                <i class="fa fa-industry me-2"></i>
                                <span class="factory-badge"><?php echo strtoupper($table); ?></span>
                                <span class="user-type-badge ms-2"><?php echo $user_type; ?></span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end text-center">
                            <div class="d-flex flex-column align-items-md-end">
                                <h6 class="text-white mb-2">
                                    <i class="fa fa-calendar-alt me-1"></i>
                                    <?php echo date('F d, Y'); ?>
                                </h6>
                                <a href="view_urea_report.php?username=<?php echo $_SESSION['username'] ?>" 
                                   class="btn btn-outline-light">
                                    <i class="fa fa-arrow-left me-2"></i> Back to Records
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Message -->
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <?php if(@$_GET['updated']): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Success!</h5>
                            <p class="mb-0">Production data has been updated successfully.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="gradient-card form-animate">
                    <div class="card-header card-header-gradient">
                        <h5 class="mb-0 text-white">
                            <i class="fa fa-database me-2"></i>
                            Edit Production Entry
                            <?php if(isset($_GET['id'])): ?>
                            <span class="badge bg-light text-dark ms-2">ID: <?php echo $_GET['id']; ?></span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM $table WHERE id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                $row = mysqli_fetch_array($query_run);
                                $product_produce = $row['product_produce'];
                        ?>
                        
                        <form action="edit_urea_code.php" method="POST" id="editForm">
                            <input type="hidden" name="id" value="<?php echo !empty($row['id']) ? $row['id'] : ''; ?>">
                            
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="date" class="form-label">
                                        <i class="fa fa-calendar-alt"></i> Date
                                    </label>
                                    <?php if ($_SESSION['user_type'] == 'sadmin'): ?>
                                        <input type="date" class="form-control" name="date" 
                                               id="date" value="<?php echo !empty($row['date']) ? $row['date'] : ''; ?>"
                                               onfocus="showProgress('date')">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="date" readonly 
                                               value="<?php echo !empty($row['date']) ? date('d M Y', strtotime($row['date'])) : ''; ?>">
                                    <?php endif; ?>
                                    <div class="progress-indicator" id="date-progress"></div>
                                </div>
                                
                                <!-- Product Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="product_produce" class="form-label">
                                        <i class="fa fa-cube"></i> Product
                                    </label>
                                    <?php if ($_SESSION['user_type'] == 'sadmin'): ?>
                                        <select class="form-control" name="product_produce" id="product_produce"
                                                onfocus="showProgress('product')">
                                            <option value="sanitary" <?php echo ($row['product_produce'] == 'sanitary') ? 'selected' : ''; ?>>Sanitary Ware</option>
                                            <option value="insulator" <?php echo ($row['product_produce'] == 'insulator') ? 'selected' : ''; ?>>Insulator</option>
                                            <option value="refractories" <?php echo ($row['product_produce'] == 'refractories') ? 'selected' : ''; ?>>Refractories</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="product_produce" readonly 
                                               value="<?php echo !empty($row['product_produce']) ? htmlspecialchars(ucfirst($row['product_produce'])) : ''; ?>">
                                    <?php endif; ?>
                                    <div class="progress-indicator" id="product-progress"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Daily Production -->
                                <div class="col-md-6 mb-4">
                                    <label for="daily" class="form-label">
                                        <i class="fa fa-chart-bar"></i> Daily Production (MT)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fa fa-weight-hanging"></i>
                                        </span>
                                        <input type="text" class="form-control" name="daily" 
                                               id="daily" value="<?php echo !empty($row['daily']) ? $row['daily'] : ''; ?>"
                                               onfocus="showProgress('daily')">
                                        <span class="input-group-text bg-light">MT</span>
                                    </div>
                                    <div class="progress-indicator" id="daily-progress"></div>
                                </div>
                                
                                <!-- Plant Load -->
                                <div class="col-md-6 mb-4">
                                    <label for="plant_load" class="form-label">
                                        <i class="fa fa-tachometer-alt"></i> Plant Load (%)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fa fa-industry"></i>
                                        </span>
                                        <input type="text" class="form-control" name="plant_load" 
                                               id="plant_load" value="<?php echo !empty($row['plant_load']) ? $row['plant_load'] : ''; ?>"
                                               onfocus="showProgress('load')">
                                        <span class="input-group-text bg-light">%</span>
                                    </div>
                                    <div class="progress-indicator" id="load-progress"></div>
                                </div>
                            </div>
                            
                            <!-- Remarks -->
                            <div class="mb-4">
                                <label for="remarks" class="form-label">
                                    <i class="fa fa-comment"></i> Remarks
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light align-items-start" style="padding-top: 12px;">
                                        <i class="fa fa-sticky-note"></i>
                                    </span>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="4"
                                              onfocus="showProgress('remarks')"><?php echo !empty($row['remarks']) ? $row['remarks'] : ''; ?></textarea>
                                </div>
                                <div class="progress-indicator" id="remarks-progress"></div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                                <button type="submit" name="update" class="btn btn-success btn-lg px-5">
                                    <i class="fa fa-save me-2"></i> Update Record
                                </button>
                                <a href="view_urea_report.php?username=<?php echo $_SESSION['username'] ?>" 
                                   class="btn btn-danger btn-lg px-5">
                                    <i class="fa fa-times me-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                        
                        <?php
                            } else {
                        ?>
                        <div class="text-center py-5">
                            <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h4 class="text-danger">Record Not Found</h4>
                            <p class="text-muted">The requested production record does not exist.</p>
                            <a href="view_urea_report.php?username=<?php echo $_SESSION['username'] ?>" 
                               class="btn btn-primary">
                                <i class="fa fa-arrow-left me-2"></i> Back to Records
                            </a>
                        </div>
                        <?php
                            }
                        } else {
                        ?>
                        <div class="text-center py-5">
                            <i class="fa fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <h4 class="text-danger">Invalid Request</h4>
                            <p class="text-muted">No record ID specified for editing.</p>
                            <a href="view_urea_report.php?username=<?php echo $_SESSION['username'] ?>" 
                               class="btn btn-primary">
                                <i class="fa fa-arrow-left me-2"></i> Back to Records
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Info Section -->
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-info border-0">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fa fa-info-circle me-2"></i> Editing Guidelines</h6>
                            <ul class="mb-0 small">
                                <li>Only super admins can edit dates and product types</li>
                                <li>Daily production should be in metric tons (MT)</li>
                                <li>Plant load percentage should be between 0-100</li>
                                <li>Remarks field is optional but recommended</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fa fa-history me-2"></i> Record Information</h6>
                            <div class="small">
                                <p class="mb-1"><strong>Factory:</strong> <?php echo strtoupper($table); ?></p>
                                <p class="mb-1"><strong>User Role:</strong> <?php echo ucfirst($user_type); ?></p>
                                <p class="mb-0"><strong>Session:</strong> <?php echo date('h:i A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide success message
        setTimeout(function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.transition = 'opacity 0.5s';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    if (successMessage.parentNode) {
                        successMessage.parentNode.removeChild(successMessage);
                    }
                }, 500);
            }
        }, 3000);
        
        // Progress indicator function
        function showProgress(field) {
            const progressBar = document.getElementById(field + '-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        }
        
        // Form validation
        document.getElementById('editForm')?.addEventListener('submit', function(e) {
            const dailyInput = document.getElementById('daily');
            const plantLoadInput = document.getElementById('plant_load');
            
            // Validate daily production
            if (dailyInput && !/^\d*\.?\d+$/.test(dailyInput.value.trim())) {
                e.preventDefault();
                dailyInput.classList.add('is-invalid');
                showToast('Please enter a valid number for daily production', 'error');
                return false;
            }
            
            // Validate plant load
            if (plantLoadInput) {
                const loadValue = parseFloat(plantLoadInput.value.trim());
                if (isNaN(loadValue) || loadValue < 0 || loadValue > 100) {
                    e.preventDefault();
                    plantLoadInput.classList.add('is-invalid');
                    showToast('Plant load must be between 0 and 100%', 'error');
                    return false;
                }
            }
            
            // Clear error states
            dailyInput?.classList.remove('is-invalid');
            plantLoadInput?.classList.remove('is-invalid');
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            document.body.appendChild(toastContainer);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function() {
                toastContainer.remove();
            });
        }
        
        // Initialize progress indicators for pre-filled fields
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = ['date', 'product', 'daily', 'load', 'remarks'];
            inputs.forEach(input => {
                const element = document.getElementById(input);
                if (element && element.value) {
                    const progressBar = document.getElementById(input + '-progress');
                    if (progressBar) {
                        progressBar.style.width = '100%';
                    }
                }
            });
            
            // Focus on first editable field
            const firstEditable = document.querySelector('input:not([readonly]), select:not([disabled])');
            if (firstEditable) {
                firstEditable.focus();
            }
        });
    </script>
</body>
</html>