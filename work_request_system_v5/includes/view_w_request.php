<?php
// incoming_work_request.php - SIMPLE VERSION
session_name('factory_work_request_db');

require_once '../db/config.php';


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get request ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: incoming_w_request.php?error=invalid_id");
    exit;
}

// Fetch the specific work request with only the specified fields
$sql = "SELECT 
            id, 
            emp_id, 
            date, 
            w_req_type, 
            w_location, 
            w_description, 
            w_com_division, 
            w_com_section, 
            w_com_status, 
            status, 
            remarks, 
            requester_id, 
            full_name, 
            designation, 
            division, 
            section, 
            created_at, 
            updated_at 
        FROM work_request_tbl 
        WHERE id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: incoming_w_request.php?error=not_found");
    exit;
}

$request = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Work Request #<?php echo htmlspecialchars($request['id']); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            body {
                font-size: 12pt;
            }
            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .border {
                border: 1px solid #000 !important;
            }
            .signature-area {
                min-height: 80px;
                border-bottom: 1px solid #000;
                margin-top: 40px;
            }
            .page-break {
                page-break-before: always;
            }
        }
        
        .print-only {
            display: none;
        }
        
        .signature-area {
            min-height: 100px;
            border-bottom: 2px solid #dee2e6;
            margin-top: 20px;
            position: relative;
        }
        
        .signature-label {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .detail-row {
            border-bottom: 1px solid #f0f0f0;
            padding: 0.75rem 0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #212529;
        }
        
        .print-header {
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .company-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .company-header h2 {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-header h4 {
            color: #666;
            margin-bottom: 20px;
        }
        
        .print-footer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Print Header (only shows when printing) -->
        <div class="print-only company-header">
            <h2>COMPANY NAME</h2>
            <h4>Work Request Form</h4>
            <p>Document No: WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></p>
        </div>
        
        <!-- Action Buttons (Hidden when printing) -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>
                        <i class="bi bi-file-text me-2"></i>
                        Work Request Details #<?php echo htmlspecialchars($request['id']); ?>
                    </h2>
                    <div>
                        <button onclick="window.print()" class="btn btn-outline-primary me-2">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                        <a href="incoming_work_request.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Request Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Request Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Request ID:</div>
                                <div class="col-8 detail-value">WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Request Date:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['date']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Work Type:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['w_req_type']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Location:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['w_location']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Status:</div>
                                <div class="col-8 detail-value">
                                    <span class="badge bg-<?php echo $request['status'] == 'approved' ? 'success' : ($request['status'] == 'pending' ? 'warning' : ($request['status'] == 'rejected' ? 'danger' : 'secondary')); ?> status-badge">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Completion:</div>
                                <div class="col-8 detail-value">
                                    <span class="badge bg-<?php echo $request['w_com_status'] == 'complete' ? 'success' : 'warning'; ?> status-badge">
                                        <?php echo htmlspecialchars($request['w_com_status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Created:</div>
                                <div class="col-8 detail-value"><?php echo date('M d, Y h:i A', strtotime($request['created_at'])); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Updated:</div>
                                <div class="col-8 detail-value"><?php echo date('M d, Y h:i A', strtotime($request['updated_at'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Requester Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Requester Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Employee ID:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['emp_id']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Full Name:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['full_name']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Designation:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['designation']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Division:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['division']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Section:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['section']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Requester ID:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['requester_id']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignment Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Assignment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Assigned Division:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['w_com_division']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="row">
                                <div class="col-4 detail-label">Assigned Section:</div>
                                <div class="col-8 detail-value"><?php echo htmlspecialchars($request['w_com_section']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Description & Remarks Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-card-text me-2"></i>Work Details</h5>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="row">
                        <div class="col-12 mb-2 detail-label">Work Description:</div>
                        <div class="col-12 detail-value">
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($request['w_description'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="detail-row mt-3">
                    <div class="row">
                        <div class="col-12 mb-2 detail-label">Remarks:</div>
                        <div class="col-12 detail-value">
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($request['remarks'] ?: 'No remarks provided')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Break for Printing -->
        <div class="page-break"></div>
        
        <!-- Signatures Section (Visible in print) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pen me-2"></i>Approvals & Signatures</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Requester Signature -->
                    <div class="col-md-6 mb-4">
                        <h6 class="mb-3">Requester's Acknowledgement</h6>
                        <div class="signature-area">
                            <div class="signature-label">
                                Signature & Date
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Name:</strong> <?php echo htmlspecialchars($request['full_name']); ?><br>
                            <strong>Employee ID:</strong> <?php echo htmlspecialchars($request['emp_id']); ?><br>
                            <strong>Division:</strong> <?php echo htmlspecialchars($request['division']); ?>
                        </div>
                    </div>
                    
                    <!-- Department Head/Approver Signature -->
                    <div class="col-md-6 mb-4">
                        <h6 class="mb-3">Department Head Approval</h6>
                        <div class="signature-area">
                            <div class="signature-label">
                                Signature & Date
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Name:</strong> _________________________<br>
                            <strong>Designation:</strong> Department Head<br>
                            <strong>Status:</strong> <?php echo htmlspecialchars($request['status']); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <!-- Completion Division Signature -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Completion Division Authorization</h6>
                        <div class="signature-area">
                            <div class="signature-label">
                                Signature & Date
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Division:</strong> <?php echo htmlspecialchars($request['w_com_division']); ?><br>
                            <strong>Section:</strong> <?php echo htmlspecialchars($request['w_com_section']); ?><br>
                            <strong>Completion Status:</strong> <?php echo htmlspecialchars($request['w_com_status']); ?>
                        </div>
                    </div>
                    
                    <!-- Work Completion Verification -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Work Completion Verification</h6>
                        <div class="signature-area">
                            <div class="signature-label">
                                Signature & Date
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Verified By:</strong> _________________________<br>
                            <strong>Designation:</strong> Quality Inspector/Supervisor<br>
                            <strong>Date Verified:</strong> _________________________
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Print Footer -->
        <div class="print-only print-footer">
            <hr>
            <p class="text-center">
                <strong>Work Request Form</strong> | 
                WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?> | 
                Generated on: <?php echo date('Y-m-d H:i:s'); ?> | 
                Page 1 of 1
            </p>
        </div>
        
        <!-- Action Buttons (Bottom - Hidden when printing) -->
        <div class="row mt-4 no-print">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <?php
                    // Get user role from session
                    $user_role = $_SESSION['role'] ?? 'user';
                    
                    // Show Complete button only for authorized users when status is incomplete
                    if ($request['w_com_status'] === 'incomplete' && 
                        ($user_role === 'admin' || $user_role === 'sadmin' || 
                         $user_role === 'division_head' || $user_role === 'section_head')): ?>
                        <a href="complete_request.php?id=<?php echo $request['id']; ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Mark this request as complete?')">
                            <i class="bi bi-check-circle me-1"></i> Mark as Complete
                        </a>
                    <?php endif; ?>
                    
                    <div>
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                        <a href="incoming_work_request.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhance print functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Print button functionality
            const printButtons = document.querySelectorAll('[onclick*="print"]');
            printButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-printer me-1"></i> Preparing print...';
                    this.disabled = true;
                    
                    // Small delay to show loading state
                    setTimeout(() => {
                        window.print();
                        // Restore button
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }, 1000);
                    }, 500);
                });
            });
            
            // Keyboard shortcut for printing (Ctrl + P)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
            });
        });
    </script>
</body>
</html>