<?php
// Start session if needed
session_start();

// Include database configuration
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Master Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f8f9fc;
        }
        
        /* Solid color scheme - no gradients */
        .navbar {
            background-color: #1e2b3c !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .dashboard-header {
            background-color: #ffffff;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 3px solid #e74c3c;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .page-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
        }
        
        .page-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .stats-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stats-card.primary {
            border-left-color: #3498db;
        }
        
        .stats-card.success {
            border-left-color: #27ae60;
        }
        
        .stats-card.warning {
            border-left-color: #f39c12;
        }
        
        .stats-card.info {
            border-left-color: #3498db;
        }
        
        .stats-icon {
            font-size: 2.5rem;
            color: #bdc3c7;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0;
        }
        
        .stats-label {
            color: #7f8c8d;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .project-card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border-bottom: 3px solid transparent;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-bottom-color: #e74c3c;
        }
        
        .project-card .card-body {
            padding: 1.5rem;
        }
        
        .project-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        /* Solid background colors for project icons */
        .project-icon.blue { background-color: #3498db; color: #ffffff; }
        .project-icon.green { background-color: #27ae60; color: #ffffff; }
        .project-icon.orange { background-color: #f39c12; color: #ffffff; }
        .project-icon.red { background-color: #e74c3c; color: #ffffff; }
        .project-icon.purple { background-color: #9b59b6; color: #ffffff; }
        .project-icon.teal { background-color: #1abc9c; color: #ffffff; }
        .project-icon.dark { background-color: #34495e; color: #ffffff; }
        .project-icon.pink { background-color: #e84393; color: #ffffff; }
        .project-icon.indigo { background-color: #4834d4; color: #ffffff; }
        .project-icon.cyan { background-color: #00cec9; color: #ffffff; }
        
        .project-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .project-url {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            word-break: break-all;
        }
        
        .btn-project {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-project:hover {
            background-color: #2980b9;
            color: #ffffff;
        }
        
        .btn-project i {
            margin-right: 0.5rem;
        }
        
        .search-box {
            background-color: #ffffff;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .search-box:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .category-badge {
            background-color: #ecf0f1;
            color: #7f8c8d;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .category-badge:hover,
        .category-badge.active {
            background-color: #3498db;
            color: #ffffff;
        }
        
        .add-project-btn {
            background-color: #27ae60;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        
        .add-project-btn:hover {
            background-color: #229954;
            color: #ffffff;
        }
        
        .footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #ecf0f1;
        }
        
        .status-badge {
            background-color: #27ae60;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Modal animations and styles */
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Form input focus styles */
        .form-control:focus, .form-select:focus {
            border-color: #3498db !important;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        /* Color option hover effect */
        .color-option:hover {
            transform: scale(1.1);
            transition: transform 0.2s;
        }
        
        /* Selected color option */
        .color-option.selected {
            border-color: #2c3e50 !important;
            transform: scale(1.1);
        }
        
        /* Radio button customization */
        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }
        
        /* Badge styles */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        /* Button hover effects */
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            transition: all 0.2s;
        }
        
        /* Add project button style */
        .add-project-btn {
            background-color: #27ae60;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }
        
        .add-project-btn:hover {
            background-color: #229954;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(39, 174, 96, 0.3);
        }
        
        .add-project-btn i {
            transition: transform 0.2s;
        }
        
        .add-project-btn:hover i {
            transform: rotate(90deg);
        }

        /* Notification animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-th-large me-2"></i>
                Project Master Dashboard
            </a>
            <div class="ms-auto">
                <span class="text-white-50">
                    <i class="far fa-clock me-1"></i>
                    Last updated: Today at 10:30 AM
                </span>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="fas fa-project-diagram me-2" style="color: #e74c3c;"></i>
                        My Projects Overview
                    </h1>
                    <p class="page-subtitle mt-2">
                        Manage and access all your PHP projects from a single dashboard
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="add-project-btn" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-number" id="totalProjects">10</p>
                            <p class="stats-label">Total Projects</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-number" id="activeProjects">8</p>
                            <p class="stats-label">Active</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-number" id="maintenanceProjects">2</p>
                            <p class="stats-label">Maintenance</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-number" id="totalUsers">150+</p>
                            <p class="stats-label">Daily Users</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <input type="text" class="search-box" id="searchInput" placeholder="Search projects by name or URL...">
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap">
                    <span class="category-badge active" data-category="all">All</span>
                    <span class="category-badge" data-category="ecommerce">E-commerce</span>
                    <span class="category-badge" data-category="cms">CMS</span>
                    <span class="category-badge" data-category="crm">CRM</span>
                    <span class="category-badge" data-category="api">API</span>
                    <span class="category-badge" data-category="dashboard">Dashboard</span>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4" id="projectsContainer">
            <!-- Projects will be loaded dynamically via JavaScript -->
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
                
                <!-- Modal Header -->
                <div class="modal-header" style="background-color: #1e2b3c; border-bottom: 3px solid #e74c3c; padding: 1.5rem;">
                    <h5 class="modal-title text-white fw-600" id="addProjectModalLabel">
                        <i class="fas fa-plus-circle me-2" style="color: #e74c3c;"></i>
                        Add New Project
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body" style="padding: 2rem;">
                    <form id="addProjectForm">
                        <!-- Project Name -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-project-diagram me-2" style="color: #3498db;"></i>
                                Project Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="projectName" placeholder="e.g., E-Commerce Platform" required
                                   style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 0.75rem; transition: border-color 0.2s;">
                            <div class="form-text text-muted">Enter a descriptive name for your project</div>
                        </div>
                        
                        <!-- Project URL -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-link me-2" style="color: #27ae60;"></i>
                                Project URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" class="form-control" id="projectUrl" placeholder="https://your-project.com" required
                                   style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 0.75rem; transition: border-color 0.2s;">
                            <div class="form-text text-muted">Enter the full URL where your project is hosted</div>
                        </div>
                        
                        <!-- Category Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-tag me-2" style="color: #f39c12;"></i>
                                Category
                            </label>
                            <select class="form-select" id="projectCategory" style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 0.75rem;">
                                <option value="" selected disabled>Select a category</option>
                                <option value="E-commerce">E-commerce</option>
                                <option value="CMS">CMS</option>
                                <option value="CRM">CRM</option>
                                <option value="API">API</option>
                                <option value="Dashboard">Dashboard</option>
                                <option value="Blog">Blog</option>
                                <option value="Forum">Forum</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Status Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-circle me-2" style="color: #e74c3c;"></i>
                                Status
                            </label>
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="Active" checked>
                                        <label class="form-check-label" for="statusActive">
                                            <span class="badge" style="background-color: #27ae60; color: white;">Active</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusMaintenance" value="Maintenance">
                                        <label class="form-check-label" for="statusMaintenance">
                                            <span class="badge" style="background-color: #f39c12; color: white;">Maintenance</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusDev" value="Development">
                                        <label class="form-check-label" for="statusDev">
                                            <span class="badge" style="background-color: #3498db; color: white;">Development</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Icon Color Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-palette me-2" style="color: #9b59b6;"></i>
                                Icon Color
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="color-option selected" style="width: 35px; height: 35px; background-color: #3498db; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#3498db')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #27ae60; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#27ae60')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #f39c12; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#f39c12')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #e74c3c; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#e74c3c')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #9b59b6; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#9b59b6')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #1abc9c; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#1abc9c')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #34495e; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#34495e')"></div>
                                <div class="color-option" style="width: 35px; height: 35px; background-color: #e84393; border-radius: 8px; cursor: pointer; border: 3px solid transparent;" onclick="selectColor(this, '#e84393')"></div>
                            </div>
                            <input type="hidden" id="selectedColor" value="#3498db">
                        </div>
                        
                        <!-- Description (Optional) -->
                        <div class="mb-4">
                            <label class="form-label fw-600" style="color: #2c3e50;">
                                <i class="fas fa-align-left me-2" style="color: #7f8c8d;"></i>
                                Description (Optional)
                            </label>
                            <textarea class="form-control" id="projectDescription" rows="3" placeholder="Enter a brief description of your project..." 
                                      style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 0.75rem;"></textarea>
                        </div>
                    </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer" style="background-color: #f8f9fc; border-top: 2px solid #ecf0f1; padding: 1.5rem;">
                    <button type="button" class="btn" data-bs-dismiss="modal" 
                            style="background-color: #95a5a6; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; border: none;">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn" onclick="addNewProject(event)" 
                            style="background-color: #27ae60; color: white; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; border: none;">
                        <i class="fas fa-save me-2"></i>
                        Save Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        <i class="far fa-copyright me-1"></i>
                        2024 Project Master Dashboard. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted">
                        <i class="fas fa-code me-1"></i>
                        PHP Projects Manager v1.0
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script>
        // Variable to store selected color
        let selectedIconColor = '#3498db';
        
        // Function to select color
        function selectColor(element, color) {
            // Remove selected class from all color options
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked element
            element.classList.add('selected');
            
            // Update selected color
            selectedIconColor = color;
            document.getElementById('selectedColor').value = color;
        }

        // Sample projects data for demo (remove this when connecting to real API)
        const sampleProjects = [
            {
                id: 1,
                project_name: 'E-Commerce Platform',
                project_url: 'https://shop.example.com',
                category: 'E-commerce',
                status: 'Active',
                icon_color: '#3498db',
                description: 'Online shopping platform'
            },
            {
                id: 2,
                project_name: 'Content Management',
                project_url: 'https://cms.example.com',
                category: 'CMS',
                status: 'Active',
                icon_color: '#27ae60',
                description: 'Blog and content management'
            },
            {
                id: 3,
                project_name: 'Analytics Dashboard',
                project_url: 'https://analytics.example.com',
                category: 'Dashboard',
                status: 'Active',
                icon_color: '#f39c12',
                description: 'Real-time analytics'
            },
            {
                id: 4,
                project_name: 'CRM System',
                project_url: 'https://crm.example.com',
                category: 'CRM',
                status: 'Maintenance',
                icon_color: '#e74c3c',
                description: 'Customer relationship management'
            },
            {
                id: 5,
                project_name: 'Project Management',
                project_url: 'https://pm.example.com',
                category: 'Dashboard',
                status: 'Active',
                icon_color: '#9b59b6',
                description: 'Team task tracking'
            },
            {
                id: 6,
                project_name: 'Payment Gateway',
                project_url: 'https://payment.example.com',
                category: 'API',
                status: 'Active',
                icon_color: '#1abc9c',
                description: 'Payment processing'
            },
            {
                id: 7,
                project_name: 'Email Marketing',
                project_url: 'https://email.example.com',
                category: 'CRM',
                status: 'Active',
                icon_color: '#34495e',
                description: 'Email campaign management'
            },
            {
                id: 8,
                project_name: 'Invoice System',
                project_url: 'https://invoice.example.com',
                category: 'Dashboard',
                status: 'Maintenance',
                icon_color: '#e84393',
                description: 'Invoicing and billing'
            },
            {
                id: 9,
                project_name: 'Booking System',
                project_url: 'https://booking.example.com',
                category: 'E-commerce',
                status: 'Active',
                icon_color: '#4834d4',
                description: 'Appointment system'
            },
            // {
            //     id: 10,
            //     project_name: 'Database Manager',
            //     project_url: 'https://db.example.com',
            //     category: 'API',
            //     status: 'Active',
            //     icon_color: '#00cec9',
            //     description: 'Database administration'
            // }
        ];

        // Function to load projects
        function loadProjects() {
            // For demo, use sample data
            // In production, replace with API call
            displayProjects(sampleProjects);
            updateStats(sampleProjects);
        }
        // Replace the loadProjects function with:
// function loadProjects() {
//     const searchQuery = document.getElementById('searchInput')?.value || '';
//     const activeCategory = document.querySelector('.category-badge.active')?.getAttribute('data-category') || 'all';
    
//     let url = 'api/get_projects.php';
//     const params = new URLSearchParams();
//     if(searchQuery) params.append('search', searchQuery);
//     if(activeCategory !== 'all') params.append('category', activeCategory);
    
//     if(params.toString()) url += '?' + params.toString();
    
//     fetch(url)
//         .then(response => response.json())
//         .then(data => {
//             displayProjects(data.records || []);
//             updateStats(data.records || []);
//         })
//         .catch(error => {
//             console.error('Error loading projects:', error);
//             showNotification('Error loading projects', 'error');
//         });
// }

        // Function to display projects
        function displayProjects(projects) {
            const container = document.getElementById('projectsContainer');
            if(!container) return;
            
            const searchValue = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const activeCategory = document.querySelector('.category-badge.active')?.getAttribute('data-category') || 'all';
            
            // Filter projects
            let filteredProjects = projects;
            
            // Filter by search
            if(searchValue) {
                filteredProjects = filteredProjects.filter(project => 
                    project.project_name.toLowerCase().includes(searchValue) ||
                    project.project_url.toLowerCase().includes(searchValue)
                );
            }
            
            // Filter by category
            if(activeCategory !== 'all') {
                filteredProjects = filteredProjects.filter(project => 
                    project.category.toLowerCase() === activeCategory.toLowerCase()
                );
            }
            
            if(filteredProjects.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-folder-open fa-3x" style="color: #bdc3c7;"></i>
                        <h4 class="mt-3" style="color: #7f8c8d;">No projects found</h4>
                        <p class="text-muted">Click the "Add New Project" button to get started.</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            filteredProjects.forEach(project => {
                const iconClass = getIconForProject(project.project_name);
                const displayUrl = project.project_url.replace('https://', '').replace('http://', '');
                
                html += `
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="project-card">
                            <div class="card-body">
                                <div class="project-icon" style="background-color: ${project.icon_color}; color: white;">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                                <h5 class="project-title">${escapeHtml(project.project_name)}</h5>
                                <div class="project-url">
                                    <i class="fas fa-link me-1" style="font-size: 0.8rem;"></i>
                                    ${escapeHtml(displayUrl)}
                                </div>
                                <span class="status-badge mb-3 d-inline-block" style="background-color: ${getStatusColor(project.status)};">
                                    ${project.status}
                                </span>
                                <a href="${escapeHtml(project.project_url)}" target="_blank" class="btn-project">
                                    <i class="fas fa-external-link-alt"></i> Launch Project
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Function to update statistics
        function updateStats(projects) {
            const totalProjects = projects.length;
            const activeProjects = projects.filter(p => p.status === 'Active').length;
            const maintenanceProjects = projects.filter(p => p.status === 'Maintenance').length;
            
            document.getElementById('totalProjects').textContent = totalProjects;
            document.getElementById('activeProjects').textContent = activeProjects;
            document.getElementById('maintenanceProjects').textContent = maintenanceProjects;
        }

        // Function to add new project
        function addNewProject(event) {
            // Get form values
            const projectName = document.getElementById('projectName').value;
            const projectUrl = document.getElementById('projectUrl').value;
            const category = document.getElementById('projectCategory').value;
            const status = document.querySelector('input[name="status"]:checked')?.value || 'Active';
            const description = document.getElementById('projectDescription').value;
            
            // Validate required fields
            if (!projectName || !projectUrl) {
                showNotification('Please fill in all required fields (Project Name and URL)', 'error');
                return;
            }

            // Show loading state
            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            saveBtn.disabled = true;

            // Prepare data
            const projectData = {
                id: Date.now(), // Temporary ID for demo
                project_name: projectName,
                project_url: projectUrl,
                category: category || 'Other',
                status: status,
                icon_color: selectedIconColor,
                description: description
            };

            // Simulate API call
            setTimeout(() => {
                // Add to sample projects (in production, this would be an API call)
                sampleProjects.push(projectData);
                
                // Show success message
                showNotification('Project added successfully!', 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('addProjectModal')).hide();
                
                // Reset form
                document.getElementById('addProjectForm').reset();
                
                // Reset color selection
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                document.querySelector('.color-option').classList.add('selected');
                selectedIconColor = '#3498db';
                
                // Refresh projects
                loadProjects();
                
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }, 1000);
        }

        // Helper function to get icon for project
        function getIconForProject(projectName) {
            const name = projectName.toLowerCase();
            if(name.includes('ecommerce') || name.includes('shop') || name.includes('store')) return 'fa-shopping-cart';
            if(name.includes('cms') || name.includes('content')) return 'fa-cms';
            if(name.includes('analytics') || name.includes('dashboard')) return 'fa-chart-line';
            if(name.includes('crm') || name.includes('customer')) return 'fa-users-cog';
            if(name.includes('payment') || name.includes('gateway')) return 'fa-credit-card';
            if(name.includes('email') || name.includes('marketing')) return 'fa-envelope';
            if(name.includes('invoice') || name.includes('billing')) return 'fa-file-invoice';
            if(name.includes('booking') || name.includes('appointment')) return 'fa-calendar-alt';
            if(name.includes('database') || name.includes('db')) return 'fa-database';
            return 'fa-project-diagram';
        }

        // Helper function to get status color
        function getStatusColor(status) {
            switch(status) {
                case 'Active': return '#27ae60';
                case 'Maintenance': return '#f39c12';
                case 'Development': return '#3498db';
                default: return '#7f8c8d';
            }
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Notification function
        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existingNotification = document.querySelector('.notification');
            if(existingNotification) {
                existingNotification.remove();
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background-color: ${type === 'success' ? '#27ae60' : '#e74c3c'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease-out;
            `;
            
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    if(notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProjects();
            
            // Setup search with debounce
            let searchTimeout;
            document.getElementById('searchInput')?.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadProjects();
                }, 300);
            });
            
            // Setup category filters
            document.querySelectorAll('.category-badge').forEach(badge => {
                badge.addEventListener('click', function() {
                    document.querySelectorAll('.category-badge').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    loadProjects();
                });
            });
            
            // Initialize color selection on modal show
            const modal = document.getElementById('addProjectModal');
            if(modal) {
                modal.addEventListener('show.bs.modal', function () {
                    document.querySelector('.color-option').classList.add('selected');
                });
                
                // Reset form when modal is closed
                modal.addEventListener('hidden.bs.modal', function () {
                    document.getElementById('addProjectForm').reset();
                });
            }
        });
    </script>
</body>
</html>