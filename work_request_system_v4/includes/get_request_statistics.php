<?php
// get_request_statistics.php
session_name('factory_work_request_db');
session_start();
header('Content-Type: text/html');

// Check if user is logged in and is admin/sadmin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '<p>Unauthorized access</p>';
    exit;
}

$user_role = $_SESSION['role'] ?? 'user';
if ($user_role !== 'admin' && $user_role !== 'sadmin') {
    echo '<p>Insufficient permissions</p>';
    exit;
}

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo '<p>Database connection failed</p>';
    exit;
}

// Get detailed statistics
$stats_sql = "
    SELECT 
        -- Overall stats
        COUNT(*) as total_requests,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as pending,
        
        -- Urgency stats
        SUM(CASE WHEN status = 'very urgent' THEN 1 ELSE 0 END) as very_urgent,
        SUM(CASE WHEN status = 'urgent' THEN 1 ELSE 0 END) as urgent,
        SUM(CASE WHEN status = 'normal' THEN 1 ELSE 0 END) as normal,
        
        -- Type stats
        SUM(CASE WHEN w_req_type = 'ICT' THEN 1 ELSE 0 END) as ict,
        SUM(CASE WHEN w_req_type = 'Civil' THEN 1 ELSE 0 END) as civil,
        SUM(CASE WHEN w_req_type = 'Transport' THEN 1 ELSE 0 END) as transport,
        SUM(CASE WHEN w_req_type = 'Electrical' THEN 1 ELSE 0 END) as electrical,
        
        -- Time-based stats
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today,
        SUM(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 ELSE 0 END) as yesterday,
        SUM(CASE WHEN YEARWEEK(created_at) = YEARWEEK(CURDATE()) THEN 1 ELSE 0 END) as this_week,
        SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as this_month,
        
        -- Completion time stats (average days to complete)
        AVG(CASE WHEN w_com_status = 'complete' THEN DATEDIFF(updated_at, created_at) END) as avg_completion_days,
        
        -- Requester stats
        COUNT(DISTINCT requester_id) as unique_requesters,
        
        -- Division stats
        COUNT(DISTINCT division) as divisions_involved
    FROM work_request_tbl
";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// Get top requesters
$top_requesters_sql = "
    SELECT 
        requester_id,
        full_name,
        designation,
        division,
        COUNT(*) as request_count,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as completed_count,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as pending_count
    FROM work_request_tbl
    GROUP BY requester_id, full_name, designation, division
    ORDER BY request_count DESC
    LIMIT 10
";

$top_requesters_result = $conn->query($top_requesters_sql);
$top_requesters = $top_requesters_result->fetch_all(MYSQLI_ASSOC);

// Get monthly trend
$monthly_trend_sql = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as request_count,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as completed_count
    FROM work_request_tbl
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 6
";

$monthly_trend_result = $conn->query($monthly_trend_sql);
$monthly_trend = $monthly_trend_result->fetch_all(MYSQLI_ASSOC);

$conn->close();

// Calculate percentages
$completion_rate = $stats['total_requests'] > 0 ? ($stats['completed'] / $stats['total_requests']) * 100 : 0;
$pending_rate = $stats['total_requests'] > 0 ? ($stats['pending'] / $stats['total_requests']) * 100 : 0;
?>
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
        <!-- Overall Stats -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
            <h3 style="margin-bottom: 15px; color: #333;">📊 Overall Statistics</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $stats['total_requests']; ?></div>
                    <div style="font-size: 12px; color: #666;">Total Requests</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #27ae60;"><?php echo $stats['completed']; ?></div>
                    <div style="font-size: 12px; color: #666;">Completed</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #e74c3c;"><?php echo $stats['pending']; ?></div>
                    <div style="font-size: 12px; color: #666;">Pending</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #f39c12;"><?php echo $stats['unique_requesters']; ?></div>
                    <div style="font-size: 12px; color: #666;">Unique Requesters</div>
                </div>
            </div>
        </div>
        
        <!-- Urgency Stats -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #e74c3c;">
            <h3 style="margin-bottom: 15px; color: #333;">⚠️ Urgency Breakdown</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #e74c3c;"><?php echo $stats['very_urgent']; ?></div>
                    <div style="font-size: 12px; color: #666;">Very Urgent</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #f39c12;"><?php echo $stats['urgent']; ?></div>
                    <div style="font-size: 12px; color: #666;">Urgent</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #3498db;"><?php echo $stats['normal']; ?></div>
                    <div style="font-size: 12px; color: #666;">Normal</div>
                </div>
            </div>
        </div>
        
        <!-- Type Stats -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #9b59b6;">
            <h3 style="margin-bottom: 15px; color: #333;">🔧 Request Types</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #9b59b6;"><?php echo $stats['ict']; ?></div>
                    <div style="font-size: 12px; color: #666;">ICT</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #1abc9c;"><?php echo $stats['civil']; ?></div>
                    <div style="font-size: 12px; color: #666;">Civil</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #e67e22;"><?php echo $stats['transport']; ?></div>
                    <div style="font-size: 12px; color: #666;">Transport</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #e74c3c;"><?php echo $stats['electrical']; ?></div>
                    <div style="font-size: 12px; color: #666;">Electrical</div>
                </div>
            </div>
        </div>
        
        <!-- Time Stats -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #2ecc71;">
            <h3 style="margin-bottom: 15px; color: #333;">⏰ Recent Activity</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #2ecc71;"><?php echo $stats['today']; ?></div>
                    <div style="font-size: 12px; color: #666;">Today</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #27ae60;"><?php echo $stats['yesterday']; ?></div>
                    <div style="font-size: 12px; color: #666;">Yesterday</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #3498db;"><?php echo $stats['this_week']; ?></div>
                    <div style="font-size: 12px; color: #666;">This Week</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: bold; color: #9b59b6;"><?php echo $stats['this_month']; ?></div>
                    <div style="font-size: 12px; color: #666;">This Month</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Requesters -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #333;">🏆 Top 10 Requesters</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #e9ecef;">
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Rank</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Name</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Division</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Total</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Completed</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_requesters as $index => $requester): ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 10px; font-size: 12px;">#<?php echo $index + 1; ?></td>
                        <td style="padding: 10px; font-size: 12px;">
                            <strong><?php echo htmlspecialchars($requester['full_name']); ?></strong>
                            <div style="font-size: 11px; color: #666;"><?php echo htmlspecialchars($requester['designation']); ?></div>
                        </td>
                        <td style="padding: 10px; font-size: 12px;"><?php echo htmlspecialchars($requester['division']); ?></td>
                        <td style="padding: 10px; font-size: 12px; font-weight: bold;"><?php echo $requester['request_count']; ?></td>
                        <td style="padding: 10px; font-size: 12px; color: #27ae60;"><?php echo $requester['completed_count']; ?></td>
                        <td style="padding: 10px; font-size: 12px; color: #e74c3c;"><?php echo $requester['pending_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Monthly Trend -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
        <h3 style="margin-bottom: 15px; color: #333;">📈 6-Month Trend</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #e9ecef;">
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Month</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Total Requests</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Completed</th>
                    <th style="padding: 10px; text-align: left; font-size: 12px;">Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthly_trend as $trend): 
                    $completion_rate_month = $trend['request_count'] > 0 ? 
                        round(($trend['completed_count'] / $trend['request_count']) * 100, 1) : 0;
                ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 10px; font-size: 12px;">
                            <?php echo date('F Y', strtotime($trend['month'] . '-01')); ?>
                        </td>
                        <td style="padding: 10px; font-size: 12px; font-weight: bold;"><?php echo $trend['request_count']; ?></td>
                        <td style="padding: 10px; font-size: 12px; color: #27ae60;"><?php echo $trend['completed_count']; ?></td>
                        <td style="padding: 10px; font-size: 12px;">
                            <div style="background: #e9ecef; border-radius: 10px; height: 10px; width: 100%;">
                                <div style="background: #27ae60; height: 100%; border-radius: 10px; width: <?php echo $completion_rate_month; ?>%;"></div>
                            </div>
                            <div style="font-size: 11px; margin-top: 5px;"><?php echo $completion_rate_month; ?>%</div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Summary -->
    <div style="margin-top: 20px; padding: 15px; background: #d4edda; border-radius: 8px; text-align: center;">
        <h4 style="color: #155724; margin-bottom: 10px;">📋 Summary</h4>
        <p style="color: #155724; font-size: 14px;">
            Overall Completion Rate: <strong><?php echo round($completion_rate, 1); ?>%</strong> | 
            Average Completion Time: <strong><?php echo round($stats['avg_completion_days'] ?? 0, 1); ?> days</strong> | 
            Divisions Involved: <strong><?php echo $stats['divisions_involved']; ?></strong>
        </p>
    </div>
</div>