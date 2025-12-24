<?php
// viva_candidates_list.php
include 'db_connection.php';

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = '';
$search_condition = '';
$search_params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $search_condition = "WHERE roll_no LIKE ? OR name LIKE ? OR post_name LIKE ? OR committe_name LIKE ?";
    $search_param = "%$search%";
    $search_params = [$search_param, $search_param, $search_param, $search_param];
}

// Get total records
$count_sql = "SELECT COUNT(*) as total FROM candidates_tbl_new $search_condition";
$count_stmt = $conn->prepare($count_sql);
if (!empty($search_params)) {
    $count_stmt->bind_param(str_repeat('s', count($search_params)), ...$search_params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Get candidates data with pagination
$sql = "SELECT 
            id,
            roll_no,
            post_name,
            name,
            father,
            mother,
            dob,
            gender,
            religion,
            quota,
            home_district,
            ssc_result,
            hsc_result,
            gra_result,
            mas_result,
            written_marks,
            viva_marks,
            committe_name,
            created_at
        FROM candidates_tbl_new 
        $search_condition 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

if (!empty($search_params)) {
    $types = str_repeat('s', count($search_params)) . 'ii';
    $params = array_merge($search_params, [$limit, $offset]);
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viva Candidates List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 20px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            font-size: 2.2rem;
            margin: 0;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .back-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #f0f2ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .content {
            padding: 30px;
        }
        
        /* Search Box */
        .search-box {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-box form {
            display: flex;
            gap: 10px;
            flex: 1;
            min-width: 300px;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            outline: none;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 2px solid #667eea;
            min-width: 150px;
        }
        
        .stats-card .count {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stats-card .label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            overflow-x: auto;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        tr:hover {
            background: #f8f9ff;
        }
        
        /* Status badges */
        .committee-badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .marks-display {
            font-weight: bold;
            text-align: center;
        }
        
        .viva-marks {
            color: #4CAF50;
        }
        
        .written-marks {
            color: #2196F3;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .pagination a, .pagination span {
            padding: 10px 15px;
            background: #f8f9ff;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .pagination .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 10px auto;
            }
            
            .content {
                padding: 15px;
            }
            
            .header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }
            
            .header-actions {
                justify-content: center;
            }
            
            .search-box form {
                min-width: 100%;
            }
            
            .stats-card {
                min-width: 120px;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 10px;
            }
        }
        
        /* Loading Animation */
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .loading i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #667eea;
        }
        
        /* No Data */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fas fa-users"></i> Viva Candidates List</h1>
                <p>Total <?php echo $total_records; ?> candidates found</p>
            </div>
            <div class="header-actions">
                <a href="javascript:void(0)" onclick="printTable()" class="back-btn">
                    <i class="fas fa-print"></i> Print
                </a>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="back-btn">
                    <i class="fas fa-sync"></i> Refresh
                </a>
                <a href="your_import_page.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Import
                </a>
            </div>
        </div>
        
        <div class="content">
            <div class="search-box">
                <form method="GET" action="">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Search by Roll No, Name, Post, or Committee..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
                
                <div class="stats-card">
                    <div class="count"><?php echo $total_records; ?></div>
                    <div class="label">Total Candidates</div>
                </div>
            </div>
            
            <div class="table-container">
                <?php if ($result->num_rows > 0): ?>
                    <table id="candidatesTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Roll No</th>
                                <th>Post Name</th>
                                <th>Name</th>
                                <th>Father</th>
                                <th>Mother</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Religion</th>
                                <th>Quota</th>
                                <th>District</th>
                                <th>SSC</th>
                                <th>HSC</th>
                                <th>Graduation</th>
                                <th>Masters</th>
                                <th>Written</th>
                                <th>Viva</th>
                                <th>Committee</th>
                                <th>Added On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $serial = $offset + 1;
                            while ($row = $result->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?php echo $serial++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['roll_no']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['post_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['father']); ?></td>
                                <td><?php echo htmlspecialchars($row['mother']); ?></td>
                                <td><?php echo !empty($row['dob']) ? date('d/m/Y', strtotime($row['dob'])) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['religion']); ?></td>
                                <td><?php echo htmlspecialchars($row['quota']); ?></td>
                                <td><?php echo htmlspecialchars($row['home_district']); ?></td>
                                <td><?php echo htmlspecialchars($row['ssc_result']); ?></td>
                                <td><?php echo htmlspecialchars($row['hsc_result']); ?></td>
                                <td><?php echo htmlspecialchars($row['gra_result']); ?></td>
                                <td><?php echo htmlspecialchars($row['mas_result']); ?></td>
                                <td class="marks-display written-marks"><?php echo $row['written_marks']; ?></td>
                                <td class="marks-display viva-marks"><?php echo $row['viva_marks']; ?></td>
                                <td><span class="committee-badge"><?php echo htmlspecialchars($row['committe_name']); ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-slash"></i>
                        <h3>No Candidates Found</h3>
                        <p><?php echo !empty($search) ? "No results for '$search'. Try a different search term." : "No candidates have been imported yet."; ?></p>
                        <?php if (!empty($search)): ?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-btn" style="margin-top: 15px;">
                                <i class="fas fa-times"></i> Clear Search
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                        <i class="fas fa-angle-double-left"></i> First
                    </a>
                    <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                        <i class="fas fa-angle-left"></i> Prev
                    </a>
                <?php else: ?>
                    <span class="disabled"><i class="fas fa-angle-double-left"></i> First</span>
                    <span class="disabled"><i class="fas fa-angle-left"></i> Prev</span>
                <?php endif; ?>
                
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                if ($start_page > 1) {
                    echo '<span>...</span>';
                }
                
                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                        echo '<span class="active">' . $i . '</span>';
                    } else {
                        echo '<a href="?page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . '">' . $i . '</a>';
                    }
                }
                
                if ($end_page < $total_pages) {
                    echo '<span>...</span>';
                }
                ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                        Next <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="?page=<?php echo $total_pages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                        Last <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php else: ?>
                    <span class="disabled">Next <i class="fas fa-angle-right"></i></span>
                    <span class="disabled">Last <i class="fas fa-angle-double-right"></i></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Optional: DataTables for enhanced features -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    
    <script>
        // Initialize DataTable if you want enhanced features
        $(document).ready(function() {
            $('#candidatesTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                paging: false,
                info: false,
                searching: false,
                order: [[0, 'asc']]
            });
        });
        
        function printTable() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Candidates List</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background: #667eea; color: white; padding: 10px; text-align: left; }
                td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
                .print-header { text-align: center; margin-bottom: 20px; }
                .print-header h1 { color: #667eea; }
                .print-info { margin-bottom: 20px; color: #666; }
                @media print {
                    body { margin: 0; padding: 20px; }
                    .no-print { display: none; }
                }
            `);
            printWindow.document.write('</style></head><body>');
            
            printWindow.document.write('<div class="print-header">');
            printWindow.document.write('<h1>Viva Candidates List</h1>');
            printWindow.document.write('<div class="print-info">');
            printWindow.document.write('<p>Generated on: ' + new Date().toLocaleDateString() + '</p>');
            printWindow.document.write('<p>Total Candidates: <?php echo $total_records; ?></p>');
            printWindow.document.write('</div></div>');
            
            printWindow.document.write(document.querySelector('.table-container').innerHTML);
            printWindow.document.write('</body></html>');
            
            printWindow.document.close();
            printWindow.print();
        }
        
        // Highlight search term in table
        <?php if (!empty($search)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTerm = '<?php echo addslashes($search); ?>';
            const tableCells = document.querySelectorAll('#candidatesTable td');
            
            tableCells.forEach(cell => {
                const text = cell.textContent;
                const regex = new RegExp(searchTerm, 'gi');
                if (regex.test(text)) {
                    cell.innerHTML = text.replace(regex, match => `<span style="background-color: yellow; font-weight: bold;">${match}</span>`);
                }
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$stmt->close();
$count_stmt->close();
$conn->close();
?>