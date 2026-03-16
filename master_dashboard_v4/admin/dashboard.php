<?php
session_name('project_master_dashboard');
session_start();
include "../db.php";
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Project Master</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
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
            cursor: move;
            margin-bottom: 20px;
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
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: #ffffff;
        }
        
        .project-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .project-url {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            word-break: break-all;
            text-align: center;
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 6px;
        }
        
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            color: #ffffff;
            width: 100%;
            text-align: center;
        }
        
        .btn-edit {
            background-color: #f39c12;
            color: #ffffff;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
        }
        
        .btn-edit:hover {
            background-color: #e67e22;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(243, 156, 18, 0.2);
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: #ffffff;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.2);
        }
        
        .btn-add {
            background-color: #27ae60;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }
        
        .btn-add:hover {
            background-color: #229954;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(39, 174, 96, 0.3);
        }
        
        .btn-logout {
            background-color: #e74c3c;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background-color: #c0392b;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.2);
        }
        
        /* Modal Styles */
        .modal-content {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .modal-header {
            background-color: #1e2b3c;
            color: white;
            border-bottom: 3px solid #e74c3c;
            padding: 1.5rem;
        }
        
        .modal-header h5 {
            color: white;
            font-weight: 600;
            margin: 0;
        }
        
        .modal-header .btn-close {
            background-color: white;
            opacity: 0.8;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-footer {
            background-color: #f8f9fc;
            border-top: 2px solid #ecf0f1;
            padding: 1.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .color-option {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-block;
            margin: 0 5px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option.selected {
            border-color: #2c3e50;
            transform: scale(1.1);
        }
        
        .drag-handle {
            color: #bdc3c7;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .drag-handle i {
            cursor: move;
        }
        
        .footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #ecf0f1;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-th-large me-2"></i>
                Project Master Admin
            </a>
            <div class="ms-auto d-flex gap-2">
                <span class="text-white-50 me-3">
                    <i class="far fa-user-circle me-1"></i>
                    <?php echo $_SESSION['admin']; ?>
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
                        <i class="fas fa-cog me-2" style="color: #e74c3c;"></i>
                        Admin Dashboard
                    </h1>
                    <p class="page-subtitle mt-2">
                        Manage your projects with drag & drop ordering
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="logout.php" class="btn-logout me-2 text-decoration-none">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </a>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add Project
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
                            <p class="stats-number" id="totalProjects">0</p>
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
                            <p class="stats-number" id="activeProjects">0</p>
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
                            <p class="stats-number" id="maintenanceProjects">0</p>
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
                            <p class="stats-number" id="developmentProjects">0</p>
                            <p class="stats-label">Development</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-code"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drag & Drop Hint -->
        <div class="alert alert-info d-flex align-items-center mb-4" style="background-color: #e7f5ff; border: none; color: #1e7fb0;">
            <i class="fas fa-arrows-alt me-3 fa-2x"></i>
            <div>
                <strong>Drag & Drop Enabled</strong><br>
                You can reorder projects by dragging the cards. The order will be automatically saved.
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4" id="projectsContainer">
            <!-- Projects will be loaded dynamically -->
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>
                        <i class="fas fa-plus-circle me-2" style="color: #e74c3c;"></i>
                        Add New Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-project-diagram me-2" style="color: #3498db;"></i>
                                Project Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="project_name" placeholder="e.g., E-Commerce Platform" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-link me-2" style="color: #27ae60;"></i>
                                Project URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" class="form-control" name="project_url" placeholder="https://your-project.com" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-tag me-2" style="color: #f39c12;"></i>
                                        Category
                                    </label>
                                    <select class="form-select" name="category">
                                        <option value="Website">Website</option>
                                        <option value="System">System</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-circle me-2" style="color: #e74c3c;"></i>
                                        Status
                                    </label>
                                    <select class="form-select" name="status">
                                        <option>Active</option>
                                        <option>Maintenance</option>
                                        <option>Development</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-left me-2" style="color: #7f8c8d;"></i>
                                Description
                            </label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Enter a brief description..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-camera me-2" style="color: #9b59b6;"></i>
                                Screenshot
                            </label>
                            <input type="file" class="form-control" name="screenshot">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-palette me-2" style="color: #e84393;"></i>
                                Icon Color
                            </label>
                            <div>
                                <span class="color-option" style="background:#3498db" onclick="selectColor(this,'#3498db')"></span>
                                <span class="color-option" style="background:#e74c3c" onclick="selectColor(this,'#e74c3c')"></span>
                                <span class="color-option" style="background:#27ae60" onclick="selectColor(this,'#27ae60')"></span>
                                <span class="color-option" style="background:#f39c12" onclick="selectColor(this,'#f39c12')"></span>
                                <span class="color-option" style="background:#9b59b6" onclick="selectColor(this,'#9b59b6')"></span>
                                <span class="color-option" style="background:#1abc9c" onclick="selectColor(this,'#1abc9c')"></span>
                                <span class="color-option" style="background:#34495e" onclick="selectColor(this,'#34495e')"></span>
                                <span class="color-option" style="background:#e84393" onclick="selectColor(this,'#e84393')"></span>
                            </div>
                            <input type="hidden" id="selectedColor" name="icon_color" value="#3498db">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #95a5a6; border: none;">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('addForm').dispatchEvent(new Event('submit'))" style="background-color: #27ae60; border: none;">
                        <i class="fas fa-save me-2"></i>
                        Save Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>
                    <i class="fas fa-edit me-2" style="color: #e74c3c;"></i>
                    Edit Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" id="editSelectedColor" name="icon_color" value="#3498db">
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-project-diagram me-2" style="color: #3498db;"></i>
                            Project Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="project_name" id="edit_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-link me-2" style="color: #27ae60;"></i>
                            Project URL <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control" name="project_url" id="edit_url" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag me-2" style="color: #f39c12;"></i>
                                    Category
                                </label>
                                <select class="form-select" name="category" id="edit_category">
                                    <option value="Website">Website</option>
                                    <option value="System">System</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-circle me-2" style="color: #e74c3c;"></i>
                                    Status
                                </label>
                                <select class="form-select" name="status" id="edit_status">
                                    <option>Active</option>
                                    <option>Maintenance</option>
                                    <option>Development</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-2" style="color: #7f8c8d;"></i>
                            Description
                        </label>
                        <textarea class="form-control" name="description" id="edit_desc" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-camera me-2" style="color: #9b59b6;"></i>
                            Update Screenshot (optional)
                        </label>
                        <input type="file" class="form-control" name="screenshot">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-palette me-2" style="color: #e84393;"></i>
                            Icon Color
                        </label>
                        <div>
                            <span class="color-option" style="background:#3498db" onclick="selectEditColor(this,'#3498db')"></span>
                            <span class="color-option" style="background:#e74c3c" onclick="selectEditColor(this,'#e74c3c')"></span>
                            <span class="color-option" style="background:#27ae60" onclick="selectEditColor(this,'#27ae60')"></span>
                            <span class="color-option" style="background:#f39c12" onclick="selectEditColor(this,'#f39c12')"></span>
                            <span class="color-option" style="background:#9b59b6" onclick="selectEditColor(this,'#9b59b6')"></span>
                            <span class="color-option" style="background:#1abc9c" onclick="selectEditColor(this,'#1abc9c')"></span>
                            <span class="color-option" style="background:#34495e" onclick="selectEditColor(this,'#34495e')"></span>
                            <span class="color-option" style="background:#e84393" onclick="selectEditColor(this,'#e84393')"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #95a5a6; border: none;">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('editForm').dispatchEvent(new Event('submit'))" style="background-color: #3498db; border: none;">
                    <i class="fas fa-save me-2"></i>
                    Update Project
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
                        Admin Panel v1.0
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Global variables
        let selectedColor = '#3498db';

        // Color selection function
        function selectColor(el, color) {
            document.querySelectorAll('.color-option').forEach(e => e.classList.remove('selected'));
            el.classList.add('selected');
            selectedColor = color;
            document.getElementById('selectedColor').value = color;
        }

        // Load projects function
        function loadProjects() {
            fetch("api/get_projects.php")
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('projectsContainer');
                    container.innerHTML = '';
                    
                    // Update stats
                    updateStats(data.records);
                    
                    // Sort by sort_order
                    data.records.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0)).forEach(p => {
                        let icon = getIconForProject(p.project_name);
                        
                        container.innerHTML += `
                            <div class="col-lg-3 col-md-4 col-sm-6 project-card" data-id="${p.id}">
                                <div class="card-body">
                                    <div class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="project-icon" style="background:${p.icon_color || '#3498db'}">
                                        <i class="fas ${icon}"></i>
                                    </div>
                                    <h5 class="project-title">${escapeHtml(p.project_name)}</h5>
                                    <div class="project-url">
                                        <i class="fas fa-link me-1"></i>
                                        ${escapeHtml(p.project_url)}
                                    </div>
                                    <span class="status-badge" style="background:${getStatusColor(p.status)}">
                                        ${p.status}
                                    </span>
                                    <button class="btn-edit" onclick="openEdit(${p.id})">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteProject(${p.id})">
                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                    </button>
                                </div>
                            </div>
                        `;
                    });

                    // Initialize drag & drop
                    if (data.records.length > 0) {
                        new Sortable(container, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function(evt) {
                                let ids = [...container.querySelectorAll('.project-card')].map(e => e.dataset.id);
                                fetch('api/reorder_projects.php', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify({ids: ids})
                                })
                                .then(r => r.json())
                                .then(d => {
                                    if(d.success) {
                                        showNotification('Projects reordered successfully!', 'success');
                                    }
                                });
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification('Error loading projects', 'error');
                });
        }

        // Update statistics
        function updateStats(projects) {
            const total = projects.length;
            const active = projects.filter(p => p.status === 'Active').length;
            const maintenance = projects.filter(p => p.status === 'Maintenance').length;
            const development = projects.filter(p => p.status === 'Development').length;
            
            document.getElementById('totalProjects').textContent = total;
            document.getElementById('activeProjects').textContent = active;
            document.getElementById('maintenanceProjects').textContent = maintenance;
            document.getElementById('developmentProjects').textContent = development;
        }

        // Add form submit
        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let fd = new FormData(this);
            fd.set('icon_color', selectedColor);

            fetch('api/save_project.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(d => {
                showNotification(d.message, 'success');

                // Hide the modal
                let addModalEl = document.getElementById('addModal');
                let modal = bootstrap.Modal.getInstance(addModalEl);
                modal.hide();

                // Reset the form
                this.reset();
                selectedColor = '#3498db';
                document.querySelectorAll('.color-option').forEach(e => e.classList.remove('selected'));
                document.querySelector('.color-option').classList.add('selected');
                document.getElementById('selectedColor').value = selectedColor;

                loadProjects();
            })
            .catch(err => {
                showNotification('Error saving project', 'error');
            });
        });

        // Open edit modal
        function openEdit(id) {
            fetch('api/get_projects.php')
                .then(r => r.json())
                .then(d => {
                    let p = d.records.find(x => x.id == id);
                    document.getElementById('edit_id').value = p.id;
                    document.getElementById('edit_name').value = p.project_name;
                    document.getElementById('edit_url').value = p.project_url;
                    document.getElementById('edit_category').value = p.category;
                    document.getElementById('edit_status').value = p.status;
                    document.getElementById('edit_desc').value = p.description || '';
                    
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }

        // Edit form submit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let fd = new FormData(this);

            fetch('api/update_project.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(d => {
                showNotification(d.message, 'success');

                // Hide the modal
                let editModalEl = document.getElementById('editModal');
                let modal = bootstrap.Modal.getInstance(editModalEl);
                modal.hide();

                loadProjects();
            })
            .catch(err => {
                showNotification('Error updating project', 'error');
            });
        });

        // Delete project
        function deleteProject(id) {
            if(!confirm('Are you sure you want to delete this project?')) return;
            
            fetch('api/delete_project.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + id
            })
            .then(r => r.json())
            .then(d => {
                showNotification(d.message, 'success');
                loadProjects();
            })
            .catch(err => {
                showNotification('Error deleting project', 'error');
            });
        }

        // Helper function to get icon for project
        function getIconForProject(name) {
            const n = name.toLowerCase();
            if(n.includes("shop") || n.includes("store") || n.includes("ecommerce")) return "fa-shopping-cart";
            if(n.includes("cms")) return "fa-cogs";
            if(n.includes("dashboard") || n.includes("analytics")) return "fa-chart-line";
            if(n.includes("crm")) return "fa-users";
            if(n.includes("blog")) return "fa-blog";
            if(n.includes("api")) return "fa-cloud";
            if(n.includes("game")) return "fa-gamepad";
            if(n.includes("mobile") || n.includes("app")) return "fa-mobile-alt";
            if(n.includes("invoice")) return "fa-file-invoice";
            if(n.includes("email")) return "fa-envelope";
            return "fa-project-diagram";
        }

        // Helper function to get status color
        function getStatusColor(status) {
            if(status === "Active") return "#27ae60";
            if(status === "Maintenance") return "#f39c12";
            if(status === "Development") return "#3498db";
            return "#7f8c8d";
        }

        // Escape HTML helper
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Notification function
        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existingNotification = document.querySelector('.notification');
            if(existingNotification) existingNotification.remove();
            
            // Create notification
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
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    if(notification.parentNode) notification.remove();
                }, 300);
            }, 3000);
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);

        // Load projects on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProjects();
            
            // Set first color option as selected in add modal
            document.querySelector('.color-option')?.classList.add('selected');
        });
    </script>
</body>
</html>