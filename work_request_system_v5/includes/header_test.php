<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request Form</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --info-color: #7209b7;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --transport-color: #4CAF50;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-attachment: fixed;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 45px rgba(31, 38, 135, 0.25);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeIn 0.8s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .header-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
            animation: wave 10s linear infinite;
        }
        
        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(50px); }
        }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }
        
        .user-badge:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Transport Section Styling */
        .transport-section {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            border: 2px solid rgba(76, 175, 80, 0.3);
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.15);
        }
        
        .transport-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A, #4CAF50);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        .transport-section.show {
            display: block;
            animation: slideDown 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
            transform: translateY(0);
        }
        
        .transport-section.hide {
            display: none;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
                max-height: 0;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                max-height: 1000px;
            }
        }
        
        .transport-header {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            margin: -25px -25px 25px -25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .transport-header i {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .transport-field {
            border: 2px solid #e8f5e9;
            background: white;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .transport-field:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2), 0 5px 15px rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }
        
        /* Regular sections that hide when Transport is selected */
        .regular-section {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 1000px;
            opacity: 1;
            overflow: hidden;
        }
        
        .regular-section.hide {
            max-height: 0;
            opacity: 0;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 15px;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
            background: white;
        }
        
        .form-label {
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }
        
        .required::after {
            content: " *";
            color: var(--danger-color);
            font-weight: bold;
        }
        
        /* Urgency Badges */
        .urgency-badge {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .urgency-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent, rgba(67, 97, 238, 0.05));
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .urgency-badge:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .urgency-badge.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.05));
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
        }
        
        .badge-normal {
            color: #4299e1;
            border-color: #4299e1;
        }
        
        .badge-urgent {
            color: #ed8936;
            border-color: #ed8936;
        }
        
        .badge-very-urgent {
            color: #f56565;
            border-color: #f56565;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 15px;
            padding: 16px 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 40px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, #3a56d4 0%, #2e0a8c 100%);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border: 2px solid #e2e8f0;
            color: #4a5568;
            border-radius: 15px;
            padding: 16px 32px;
            font-weight: 700;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
            border-color: #cbd5e0;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Floating Info Button */
        .floating-info {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .info-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: float 3s ease-in-out infinite;
        }
        
        .info-btn:hover {
            transform: scale(1.15) rotate(15deg);
            box-shadow: 0 20px 40px rgba(67, 97, 238, 0.5);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        /* Alerts */
        .alert-custom {
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(76, 201, 240, 0.15) 0%, rgba(67, 97, 238, 0.15) 100%);
            border-left: 5px solid var(--success-color);
            color: #2d3748;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(247, 37, 133, 0.15) 0%, rgba(242, 100, 104, 0.15) 100%);
            border-left: 5px solid var(--danger-color);
            color: #2d3748;
        }
        
        /* Section Headers */
        .section-header {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }
        
        /* Form Section Animation */
        .form-section {
            transition: all 0.3s ease;
            padding: 25px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .form-section:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.7);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        /* Character Count */
        .char-count {
            font-size: 13px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
            background: rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .char-count.warning {
            background: rgba(247, 37, 133, 0.1);
            color: var(--danger-color);
        }
        
        .char-count.success {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }
        
        /* Quick Tips */
        .quick-tips {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(114, 9, 183, 0.1));
            border: 2px solid rgba(67, 97, 238, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        /* Forgot Link */
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s;
        }
        
        .forgot-link:hover {
            color: var(--secondary-color);
        }
        
        .forgot-link:hover::after {
            width: 100%;
        }
        
        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 25px 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .glass-card {
                margin: 10px;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .transport-section {
                padding: 20px;
                margin: 15px 0;
            }
            
            .transport-header {
                padding: 12px 20px;
                margin: -20px -20px 20px -20px;
            }
            
            .floating-info {
                bottom: 20px;
                right: 20px;
            }
            
            .info-btn {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }
    </style>
</head>