<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$is_admin = ($username === 'admin');
$role = $_SESSION['role'] ?? '';
$table = 'vacant_statistics_tbl';

// Helper function to convert CSV to array
function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

// Helper function for Bangla numbers
function enToBn($number) {
    $en = array('0','1','2','3','4','5','6','7','8','9');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($en, $bn, (string)$number);
}

// Structure definition
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9), 'grade_range' => '১-৯'],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10], 'grade_range' => '১০'],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16), 'grade_range' => '১১-১৬'],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20), 'grade_range' => '১৭-২০'],
    'শ্রমিক' => ['grades' => range(1, 20), 'grade_range' => '১-২০']
];

$total_grades = 0;
foreach ($structure as $classData) {
    $total_grades += count($classData['grades']);
}

// Handle actions
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM $table WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: vacant_statistics_details.php");
    exit;
}

if (isset($_GET['clone_id'])) {
    $clone_id = $conn->real_escape_string($_GET['clone_id']);
    $clone_sql = "INSERT INTO $table (factory_name, entry_date, granted_post, in_service, eligible_promotion, direct_recruit, created_at, updated_at)
                  SELECT factory_name, CURDATE(), granted_post, in_service, eligible_promotion, direct_recruit, NOW(), NOW() 
                  FROM $table WHERE id = '$clone_id'";
    if ($conn->query($clone_sql)) {
        $_SESSION['message'] = "Record cloned successfully with current date!";
    } else {
        $_SESSION['error'] = "Error cloning record: " . $conn->error;
    }
    header("Location: vacant_statistics_details.php");
    exit;
}

// Fetch all records
$sql = "SELECT * FROM $table ORDER BY entry_date DESC, factory_name ASC";
$result = $conn->query($sql);

// Group records by month-year
$monthGroups = [];
if ($result && $result->num_rows > 0) {
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $entry_date = !empty($row['entry_date']) ? $row['entry_date'] : $row['created_at'];
        $month_year = date("Y-m", strtotime($entry_date));
        
        // Calculate sums for this record
        $granted_array = csvToArray($row['granted_post']);
        $service_array = csvToArray($row['in_service']);
        $promo_array = csvToArray($row['eligible_promotion']);
        $direct_array = csvToArray($row['direct_recruit']);
        
        // Pad arrays
        while (count($granted_array) < $total_grades) $granted_array[] = 0;
        while (count($service_array) < $total_grades) $service_array[] = 0;
        while (count($promo_array) < $total_grades) $promo_array[] = 0;
        while (count($direct_array) < $total_grades) $direct_array[] = 0;
        
        $row['granted_total'] = array_sum($granted_array);
        $row['service_total'] = array_sum($service_array);
        $row['promo_total'] = array_sum($promo_array);
        $row['direct_total'] = array_sum($direct_array);
        $row['vacant_total'] = $row['promo_total'] + $row['direct_total'];
        $row['granted_array'] = $granted_array;
        $row['service_array'] = $service_array;
        $row['promo_array'] = $promo_array;
        $row['direct_array'] = $direct_array;
        
        if (!isset($monthGroups[$month_year])) {
            $monthGroups[$month_year] = [];
        }
        $monthGroups[$month_year][] = $row;
    }
    $result->data_seek(0);
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <title>Vacant Statistics | Man Power Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
  <style type="text/css">
    body { font-family: 'Noto Sans Bengali', sans-serif; background: #f8f9fa; }
    .print-btn {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        color: white;
    }
    .print-btn:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        color: white;
    }
    .combine-print-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
    }
    .combine-print-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #1e7e34 100%);
        color: white;
    }
    .month-group-header {
        background-color: #e9ecef !important;
        font-weight: bold;
    }
    .badge-count {
        font-size: 0.8em;
        margin-left: 5px;
    }
    .vacant-badge {
        background-color: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.85em;
    }
    .btn-action {
        padding: 4px 8px;
        margin: 0 2px;
    }
    @media print {
        .no-print { display: none !important; }
        .print-section { margin: 0; padding: 0; }
        body { background: white; }
        .container { margin: 0; padding: 0; width: 100%; }
    }
  </style>
</head>
<body>

<div class="container mt-3 shadow rounded p-4">
  <h2><i class="fas fa-chart-line me-2"></i>শূন্য পদ পরিসংখ্যান</h2>
  
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="mb-3 no-print">   
    <button class="btn btn-success" onclick="window.print()">
      <i class="fas fa-print"></i> Print All
    </button>
    <a href="vacant_statistics.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add New
    </a>
    <a href="<?php echo $is_admin ? 'admin_dashboard.php' : 'dashboard.php'; ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>
         
  <div class="table-responsive print-section">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Entry Date</th>
          <th>Month' Year</th>
          <th>Factory Name</th>
          <th>Granted Post</th>
          <th>In Service</th>
          <th>Vacant Post</th>
          <th>Created at</th>
          <th>Updated at</th>
          <th class="no-print">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php 
          $current_month = '';
          while ($row = $result->fetch_assoc()): 
            $entry_date = !empty($row['entry_date']) ? $row['entry_date'] : $row['created_at'];
            $month_year = date("F' Y", strtotime($entry_date));
            $month_key = date("Y-m", strtotime($entry_date));
            $factory_name = $row['factory_name'];
            
            // Calculate totals
            $granted_sum = array_sum(csvToArray($row['granted_post']));
            $service_sum = array_sum(csvToArray($row['in_service']));
            $promo_sum = array_sum(csvToArray($row['eligible_promotion']));
            $direct_sum = array_sum(csvToArray($row['direct_recruit']));
            $vacant_sum = $promo_sum + $direct_sum;
            
            // Check if this is a new month group
            if ($month_key !== $current_month):
              $current_month = $month_key;
              $month_record_count = isset($monthGroups[$month_key]) ? count($monthGroups[$month_key]) : 0;
          ?>
            <tr class="month-group-header">
              <td colspan="10" class="text-center">
                  <strong>মাস: <?php echo $month_year; ?></strong> 
                  <span class="badge bg-primary badge-count"><?php echo enToBn($month_record_count); ?> টি রেকর্ড</span>
                  <button class="btn btn-sm combine-print-btn ms-3 month-print-btn no-print" 
                          data-month-key="<?php echo $month_key; ?>" 
                          data-month-name="<?php echo $month_year; ?>">
                      <i class="fas fa-print"></i> এই মাস প্রিন্ট করুন
                  </button>
               </td>
             </tr>
          <?php 
            endif; 
          ?>
          
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo date('d-m-Y', strtotime($entry_date)); ?></td>
              <td><?php echo $month_year; ?></td>
              <td><strong><?php echo htmlspecialchars($factory_name); ?></strong></td>
              <td><?php echo enToBn($granted_sum); ?></td>
              <td><?php echo enToBn($service_sum); ?></td>
              <td class="text-danger fw-bold"><?php echo enToBn($vacant_sum); ?> <span class="vacant-badge">শূন্য</span></td>
              <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
              <td><?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></td>
              <td class="no-print">
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-info btn-action" onclick="viewRecord(<?php echo $row['id']; ?>)" data-bs-toggle="modal" data-bs-target="#viewModal" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                  <a href="vacant_statistics1.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-action" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="?clone_id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-action" title="Clone" onclick="return confirm('Clone this record?')">
                    <i class="fas fa-copy"></i>
                  </a>
                  <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-action" title="Delete" onclick="return confirm('Delete this record?')">
                    <i class="fas fa-trash"></i>
                  </a>
                  <button class="btn btn-success btn-action print-single-btn" data-id="<?php echo $row['id']; ?>" title="Print">
                    <i class="fas fa-print"></i>
                  </button>
                </div>
              </td>
             </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center">No records found</td>
           </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">শূন্য পদ পরিসংখ্যান - বিস্তারিত</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewModalBody">
        <div class="text-center p-5">
          <i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// View record function
function viewRecord(id) {
    $('#viewModalBody').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...</div>');
    
    $.ajax({
        url: 'view_vacant_record.php',
        type: 'GET', 
        data: {id: id},
        success: function(response) {
            $('#viewModalBody').html(response);
            $('#viewModal').modal('show');
        },
        error: function() {
            $('#viewModalBody').html('<div class="alert alert-danger">Error loading record details.</div>');
        }
    });
}

// Print single record
$(document).on('click', '.print-single-btn', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    window.open('view_vacant_record.php?id=' + id + '&print=1', '_blank', 'width=1000,height=800,scrollbars=1');
});

// Print month combine
$(document).on('click', '.month-print-btn', function(e) {
    e.preventDefault();
    const monthKey = $(this).data('month-key');
    const monthName = $(this).data('month-name');
    window.open('print_month_combine.php?month_key=' + monthKey + '&month_name=' + encodeURIComponent(monthName), '_blank', 'width=1200,height=800,scrollbars=1');
});

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>

</body>
</html>