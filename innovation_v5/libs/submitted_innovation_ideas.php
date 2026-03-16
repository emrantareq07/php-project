<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

/* ===============================
   GET ACTIVE FISCAL YEAR
=================================*/
$recent_query = "
    SELECT fiscal_year 
    FROM tbl_innovation_idea 
    WHERE idea_status='active'
    ORDER BY id DESC
    LIMIT 1
";

$recent_result = mysqli_query($conn, $recent_query);
$row_fiscal_year = mysqli_fetch_assoc($recent_result);
$fiscal_year = $row_fiscal_year['fiscal_year'] ?? '';

/* ===============================
   FETCH INNOVATION DATA
=================================*/
// $sql = "SELECT * FROM tbl_innovation WHERE fiscal_year='$fiscal_year' && (status='submitted idea'|| status='primarily idea') ORDER BY id DESC";
// $result = mysqli_query($conn, $sql);

/* ===============================
   FETCH INNOVATION DATA
=================================*/
$sql = "SELECT * 
        FROM tbl_innovation 
        WHERE fiscal_year = '$fiscal_year' 
          AND (status = 'submitted idea' OR status = 'primarily selected') 
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innovation List - BCIC</title>

    <!-- Latest Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
         * {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        /* Main Container */
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Card */
        .header-card {
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .title-section h2 {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .title-section p {
            color: #718096;
            margin: 0;
            font-size: 0.95rem;
        }

        .fiscal-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .fiscal-badge i {
            margin-right: 8px;
        }

        .btn-back {
            background: white;
            color: #667eea;
            padding: 12px 25px;
            border: 2px solid #667eea;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Row */
        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .stat-item i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-item .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .stat-item .value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 5px;
        }

        /* Table Styles */
        .table-container {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table {
            margin: 0;
            width: 100%;
        }

        .table thead {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .table thead th {
            color: gray;
            font-weight: 600;
            padding: 15px 12px;
            border: none;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background: #f7fafc;
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Employee Info */
        .employee-info {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            font-weight: 600;
            color: #2d3748;
        }

        .employee-id {
            font-size: 0.8rem;
            color: #718096;
        }

        /* Idea Title */
        .idea-title {
            text-align: left;
            max-width: 250px;
        }

        /* Badges */
        .badge-cost {
            background: #dbeafe;
            color: #1e40af;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-status {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .badge-approved {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-pending {
            background: #feebc8;
            color: #744210;
        }

        .badge-rejected {
            background: #fed7d7;
            color: #742a2a;
        }

        .badge-rank {
            background: #e9d8fd;
            color: #553c9a;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
        }

        .badge-prize {
            background: #fbbf24;
            color: #92400e;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
        }

        .badge-no {
            background: #e2e8f0;
            color: #4a5568;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        /* Action Buttons */
        .btn-action {
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.3s;
            margin: 0 3px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            background: #667eea;
            color: white;
            border: none;
        }

        .btn-view:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            color: white;
        }

        .btn-edit {
            background: #48bb78;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background: #38a169;
            transform: translateY(-2px);
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #2d3748;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #718096;
        }

        /* Footer */
        .footer-note {
            margin-top: 25px;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .footer-note a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-card {
                flex-direction: column;
                text-align: center;
            }
            
            .stats-row {
                flex-direction: column;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 10px;
            }
            
            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 10px;
                border: none;
                border-bottom: 1px dashed #e2e8f0;
            }
            
            .table tbody td:last-child {
                border-bottom: none;
            }
            
            .table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #2d3748;
                margin-right: 10px;
            }
            
            .idea-title {
                max-width: none;
            }
            
            .btn-action {
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid-custom">
        
        <!-- Header Card -->
        <div class="header-card" data-aos="fade-down">
            <div class="title-section">
                <h2><i class="fas fa-lightbulb me-2" style="color: #667eea;"></i>Submitted Innovation Ideas</h2>
                <p>Manage and review all innovation submissions</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="fiscal-badge">
                    <i class="fas fa-calendar-alt"></i>
                    Active Fiscal Year: <?= htmlspecialchars($fiscal_year) ?>
                </div>
                
                <a href="../dashboard.php" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card" data-aos="fade-up">
            
            <!-- Statistics Row -->
            <?php 
            $total_count = mysqli_num_rows($result);
            $approved_count = 0;
            $pending_count = 0;
            $total_cost = 0;
            
            if($result && $total_count > 0) {
                mysqli_data_seek($result, 0);
                while($row = mysqli_fetch_assoc($result)) {
                    if($row['status'] == 'Approved') $approved_count++;
                    if($row['status'] == 'Pending') $pending_count++;
                    $total_cost += $row['cost'];
                }
                mysqli_data_seek($result, 0);
            }
            ?>
            
            <div class="stats-row">
                <div class="stat-item" data-aos="fade-right" data-aos-delay="100">
                    <i class="fas fa-clipboard-list"></i>
                    <div class="label">Total Submissions</div>
                    <div class="value"><?= $total_count ?></div>
                </div>
                
                <div class="stat-item" data-aos="fade-right" data-aos-delay="200">
                    <i class="fas fa-check-circle"></i>
                    <div class="label">Approved</div>
                    <div class="value"><?= $approved_count ?></div>
                </div>
                
                <div class="stat-item" data-aos="fade-right" data-aos-delay="300">
                    <i class="fas fa-clock"></i>
                    <div class="label">Pending</div>
                    <div class="value"><?= $pending_count ?></div>
                </div>
                
                <div class="stat-item" data-aos="fade-right" data-aos-delay="400">
                    <i class="fas fa-coins"></i>
                    <div class="label">Total Cost (BDT)</div>
                    <div class="value">৳ <?= number_format($total_cost) ?></div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Designation</th>
                                <th>Idea Title</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Rank</th>
                                <th>Prize</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php $i=1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr data-aos="fade-up" data-aos-delay="<?= $i * 50 ?>">
                                    <td data-label="SL No"><?= $i++ ?></td>
                                    
                                    <td data-label="Employee">
                                        <div class="employee-info">
                                            <span class="employee-name"><?= htmlspecialchars($row['fullname']) ?></span>
                                            <span class="employee-id"><?= htmlspecialchars($row['emp_id']) ?></span>
                                        </div>
                                    </td>
                                    
                                    <td data-label="Designation"><?= htmlspecialchars($row['designation']) ?></td>
                                    
                                    <td data-label="Idea Title" class="idea-title">
                                        <?= htmlspecialchars($row['title_of_idea']) ?>
                                    </td>
                                    
                                    <td data-label="Cost">
                                        <span class="badge-cost">
                                            <i class="fas fa-taka me-1"></i><?= number_format($row['cost']) ?>
                                        </span>
                                    </td>
                                    
                                    <td data-label="Status">
                                        <?php 
                                        $status = $row['status'] ?? 'Pending';
                                        if($status == 'Approved'): ?>
                                            <span class="badge-status badge-approved">
                                                <i class="fas fa-check-circle me-1"></i>Approved
                                            </span>
                                        <?php elseif($status == 'Pending'): ?>
                                            <span class="badge-status badge-pending">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-status badge-rejected">
                                                <i class="fas fa-times-circle me-1"></i><?= htmlspecialchars($status) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td data-label="Rank">
                                        <?php if(!empty($row['rank'])): ?>
                                            <span class="badge-rank">
                                                <i class="fas fa-trophy me-1"></i><?= $row['rank'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-no">-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td data-label="Prize">
                                        <?php if(!empty($row['prize']) && $row['prize'] != 'no'): ?>
                                            <span class="badge-prize">
                                                <i class="fas fa-award me-1"></i><?= htmlspecialchars($row['prize']) ?>
                                                <?php if(!empty($row['prize_amount'])): ?>
                                                    <br><small>৳ <?= number_format($row['prize_amount']) ?></small>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-no">-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td data-label="Actions">
                                        <a href="view_innovation.php?id=<?= $row['id'] ?>" 
                                           class="btn-action btn-view text-decoration-none" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <a href="edit_innovation.php?id=<?= $row['id'] ?>" 
                                           class="btn-action btn-edit text-decoration-none"
                                           title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard"></i>
                                            <h4>No Innovations Found</h4>
                                            <p>There are no innovation submissions for the active fiscal year: <strong><?= htmlspecialchars($fiscal_year) ?></strong></p>
                                            <a href="add_new_innovation.php" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus me-2"></i>Add New Innovation
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Table Footer Info -->
            <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="d-flex justify-content-between align-items-center mt-4 text-muted">
                <div>
                    <i class="fas fa-info-circle me-2"></i>
                    Showing <?= $total_count ?> record<?= $total_count != 1 ? 's' : '' ?>
                </div>
                <div>
                    <i class="fas fa-calendar me-2"></i>
                    Fiscal Year: <?= htmlspecialchars($fiscal_year) ?>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Footer -->
        <div class="footer-note">
            <p>Design & Developed by <a href="#">Md. Tareq Emran</a>, Programmer, ICT Division, BCIC</p>
        </div>
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

        // Add tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>