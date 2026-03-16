<?php
// index.php
// Test database connection
try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    $test_query = "SELECT 1";
    $stmt = $db->prepare($test_query);
    $stmt->execute();
} catch(Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Master Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #f8f9fc; }
        
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
        
        .stats-card.primary { border-left-color: #3498db; }
        .stats-card.success { border-left-color: #27ae60; }
        .stats-card.warning { border-left-color: #f39c12; }
        .stats-card.info { border-left-color: #3498db; }
        
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
        
        .project-card .card-body { padding: 1.5rem; }
        
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
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }
        
        .add-project-btn:hover {
            background-color: #229954;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(39, 174, 96, 0.3);
        }
        
        .add-project-btn i { transition: transform 0.2s; }
        .add-project-btn:hover i { transform: rotate(90deg); }
        
        .footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #ecf0f1;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white;
        }
        
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
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db !important;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        .color-option {
            transition: all 0.2s;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option.selected {
            border-color: #2c3e50 !important;
            transform: scale(1.1);
        }
        
        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
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
        
        .connection-status {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            z-index: 9999;
        }
        
        .connection-status.success {
            background-color: #27ae60;
            color: white;
        }
        
        .connection-status.error {
            background-color: #e74c3c;
            color: white;
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
                    <span id="liveTime"></span>
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

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <input type="text" class="search-box" id="searchInput" placeholder="Search projects by name or URL...">
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap">
                    <span class="category-badge active" data-category="all">All</span>
                    <span class="category-badge" data-category="E-commerce">E-commerce</span>
                    <span class="category-badge" data-category="CMS">CMS</span>
                    <span class="category-badge" data-category="CRM">CRM</span>
                    <span class="category-badge" data-category="API">API</span>
                    <span class="category-badge" data-category="Dashboard">Dashboard</span>
                    <span class="category-badge" data-category="Other">Other</span>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4" id="projectsContainer">
            <!-- Projects will be loaded dynamically -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading projects...</p>
            </div>
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
                    <form id="addProjectForm" method="POST">
    
<div class="mb-3">
<label class="form-label">Project Name</label>
<input type="text" class="form-control" name="project_name" id="projectName" required>
</div>

<div class="mb-3">
<label class="form-label">Project URL</label>
<input type="url" class="form-control" name="project_url" id="projectUrl" required>
</div>

<div class="mb-3">
<label class="form-label">Category</label>
<select class="form-select" name="category" id="projectCategory">
<option value="E-commerce">E-commerce</option>
<option value="CMS">CMS</option>
<option value="CRM">CRM</option>
<option value="API">API</option>
<option value="Dashboard">Dashboard</option>
<option value="Other">Other</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Status</label>

<div class="form-check">
<input class="form-check-input" type="radio" name="status" value="Active" checked>
<label class="form-check-label">Active</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="status" value="Maintenance">
<label class="form-check-label">Maintenance</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="status" value="Development">
<label class="form-check-label">Development</label>
</div>

</div>

<input type="hidden" name="icon_color" id="selectedColor" value="#3498db">

<div class="mb-3">
<label class="form-label">Description</label>
<textarea class="form-control" name="description" id="projectDescription"></textarea>
</div>

<button type="submit" class="btn btn-success w-100">
<i class="fas fa-save"></i> Save Project
</button>

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
                        <i class="fas fa-database me-1"></i>
                        Database: Connected
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>

        document.getElementById("addProjectForm").addEventListener("submit", function(e){

        e.preventDefault();

        const formData = new FormData(this);

        fetch("save_project.php", {
        method: "POST",
        body: formData
        })
        .then(res => res.json())
        .then(data => {

        if(data.status == "success"){

        alert("Project Added Successfully");

        location.reload();

        }else{

        alert(data.message);

        }

        });

        });
        
        // Global variables
        let selectedIconColor = '#3498db';
        let currentFilter = {
            search: '',
            category: 'all'
        };
        let searchTimeout;

        // Update live time
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleString('en-US', { 
                hour: 'numeric', 
                minute: 'numeric', 
                hour12: true 
            });
            document.getElementById('liveTime').textContent = `Last updated: Today at ${timeStr}`;
        }
        updateTime();
        setInterval(updateTime, 60000);

        // Select color function
        function selectColor(element, color) {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedIconColor = color;
            document.getElementById('selectedColor').value = color;
        }

        // Load projects from database
        function loadProjects() {
            const container = document.getElementById('projectsContainer');
            
            // Build URL with filters
            let url = 'api/get_projects.php';
            const params = new URLSearchParams();
            
            if (currentFilter.search) {
                params.append('search', currentFilter.search);
            }
            if (currentFilter.category && currentFilter.category !== 'all') {
                params.append('category', currentFilter.category);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }

            // Show loading
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading projects...</p>
                </div>
            `;

            // Fetch projects
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    displayProjects(data.records || []);
                    updateStats(data.stats || { total: 0, active: 0, maintenance: 0 });
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-exclamation-circle fa-3x" style="color: #e74c3c;"></i>
                            <h4 class="mt-3" style="color: #7f8c8d;">Error loading projects</h4>
                            <p class="text-muted">${error.message}</p>
                        </div>
                    `;
                });
        }

        // Display projects
        function displayProjects(projects) {
            const container = document.getElementById('projectsContainer');
            
            if (!projects || projects.length === 0) {
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
            projects.forEach(project => {
                const iconClass = getIconForProject(project.project_name);
                const displayUrl = (project.project_url || '').replace('https://', '').replace('http://', '');
                
                html += `
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="project-card">
                            <div class="card-body">
                                <div class="project-icon" style="background-color: ${project.icon_color || '#3498db'}; color: white;">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                                <h5 class="project-title">${escapeHtml(project.project_name)}</h5>
                                <div class="project-url">
                                    <i class="fas fa-link me-1" style="font-size: 0.8rem;"></i>
                                    ${escapeHtml(displayUrl)}
                                </div>
                                <span class="status-badge mb-3 d-inline-block" style="background-color: ${getStatusColor(project.status)};">
                                    ${project.status || 'Active'}
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

        // Update statistics
        function updateStats(stats) {
            document.getElementById('totalProjects').textContent = stats.total || 0;
            document.getElementById('activeProjects').textContent = stats.active || 0;
            document.getElementById('maintenanceProjects').textContent = stats.maintenance || 0;
            
            // Calculate development projects (total - active - maintenance)
            const dev = (stats.total || 0) - (stats.active || 0) - (stats.maintenance || 0);
            document.getElementById('developmentProjects').textContent = dev;
        }

        // Add new project
        function addNewProject(event) {
            // Get form values
            const projectName = document.getElementById('projectName').value.trim();
            const projectUrl = document.getElementById('projectUrl').value.trim();
            const category = document.getElementById('projectCategory').value;
            const status = document.querySelector('input[name="status"]:checked')?.value || 'Active';
            const description = document.getElementById('projectDescription').value.trim();
            
            // Validate
            if (!projectName || !projectUrl) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }

            // Validate URL format
            try {
                new URL(projectUrl);
            } catch {
                showNotification('Please enter a valid URL (include http:// or https://)', 'error');
                return;
            }

            // Show loading
            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            saveBtn.disabled = true;

            // Prepare data
            const projectData = {
                project_name: projectName,
                project_url: projectUrl,
                category: category || 'Other',
                status: status,
                icon_color: selectedIconColor,
                description: description
            };

            // Send to server
            fetch('api/add_project.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(projectData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Project added successfully!', 'success');
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('addProjectModal')).hide();
                    
                    // Reset form
                    document.getElementById('addProjectForm').reset();
                    document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
                    document.querySelector('.color-option').classList.add('selected');
                    selectedIconColor = '#3498db';
                    
                    // Reload projects
                    loadProjects();
                } else {
                    showNotification(data.message || 'Error adding project', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Network error. Please try again.', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // Helper: Get icon for project
        function getIconForProject(projectName) {
            const name = (projectName || '').toLowerCase();
            if (name.includes('ecommerce') || name.includes('shop') || name.includes('store')) return 'fa-shopping-cart';
            if (name.includes('cms') || name.includes('content')) return 'fa-cms';
            if (name.includes('analytics') || name.includes('dashboard')) return 'fa-chart-line';
            if (name.includes('crm') || name.includes('customer')) return 'fa-users-cog';
            if (name.includes('payment') || name.includes('gateway')) return 'fa-credit-card';
            if (name.includes('email') || name.includes('marketing')) return 'fa-envelope';
            if (name.includes('invoice') || name.includes('billing')) return 'fa-file-invoice';
            if (name.includes('booking') || name.includes('appointment')) return 'fa-calendar-alt';
            if (name.includes('database') || name.includes('db')) return 'fa-database';
            return 'fa-project-diagram';
        }

        // Helper: Get status color
        function getStatusColor(status) {
            switch(status) {
                case 'Active': return '#27ae60';
                case 'Maintenance': return '#f39c12';
                case 'Development': return '#3498db';
                default: return '#7f8c8d';
            }
        }

        // Helper: Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
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
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load projects
            loadProjects();
            
            // Search input
            document.getElementById('searchInput').addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentFilter.search = this.value;
                    loadProjects();
                }, 500);
            });
            
            // Category filters
            document.querySelectorAll('.category-badge').forEach(badge => {
                badge.addEventListener('click', function() {
                    document.querySelectorAll('.category-badge').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter.category = this.dataset.category;
                    loadProjects();
                });
            });
            
            // Modal reset
            document.getElementById('addProjectModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('addProjectForm').reset();
            });
        });
    </script>
</body>
</html>