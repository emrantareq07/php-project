
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