
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Work Requests View</title>
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
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-bottom: 4px solid rgba(255,255,255,0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .header-subtitle {
            opacity: 0.9;
            font-size: 16px;
        }

        .main-content {
            padding: 30px;
        }

        .info-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .info-header h2 {
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-badge {
            background: rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.4);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .info-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 600;
        }

        .type-badges {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .type-badge {
            padding: 12px 25px;
            background: #f8f9fa;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 120px;
        }

        .type-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        .type-badge.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .type-icon {
            font-size: 20px;
        }

        .type-count {
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            margin-left: auto;
        }

        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 180px;
        }

        .filter-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        select {
            padding: 12px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .view-btn {
            padding: 10px 20px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            background: white;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .view-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .view-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e1e5eb;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .stat-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-value {
            font-size: 42px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid #e1e5eb;
            border-radius: 10px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #f8f9fa;
            padding: 18px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 3px solid #e1e5eb;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid #e1e5eb;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8f9fa;
        }

        tr.new-request {
            background: rgba(212, 237, 218, 0.2);
            border-left: 4px solid #28a745;
        }

        .badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-ict { background: #17a2b8; color: white; }
        .badge-civil { background: #28a745; color: white; }
        .badge-transport { background: #fd7e14; color: white; }
        .badge-electrical { background: #dc3545; color: white; }
        .badge-complete { background: #d4edda; color: #155724; }
        .badge-incomplete { background: #fff3cd; color: #856404; }
        .badge-normal { background: #d1ecf1; color: #0c5460; }
        .badge-urgent { background: #ffeaa7; color: #856404; animation: pulse 2s infinite; }
        .badge-very-urgent { background: #f8d7da; color: #721c24; animation: pulse 1s infinite; }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .access-denied {
            text-align: center;
            padding: 60px 20px;
            background: #f8d7da;
            border-radius: 10px;
            border: 2px solid #f5c6cb;
        }

        .access-denied-icon {
            font-size: 60px;
            margin-bottom: 20px;
            color: #721c24;
        }

        .debug-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            border-left: 4px solid #6c757d;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
        }

        .debug-title {
            color: #495057;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .query-display {
            background: #343a40;
            color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
        }

        .sql-keyword {
            color: #ff6b6b;
            font-weight: bold;
        }

        .sql-string {
            color: #51cf66;
        }

        .sql-number {
            color: #748ffc;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .filter-bar {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .view-toggle {
                margin-left: 0;
                width: 100%;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            th, td {
                padding: 12px 10px;
                font-size: 13px;
            }
        }
    </style>
</head>