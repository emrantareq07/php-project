<?php include "db.php"; ?>
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
            border-bottom: 2px solid transparent;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-bottom-color: #e74c3c;
        }
        
        .project-card .card-body {
            padding: 1rem;
        }
        
        .project-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: #ffffff;
        }
        
        /* Solid background colors for project icons */
        .project-icon.blue { background-color: #3498db; }
        .project-icon.green { background-color: #27ae60; }
        .project-icon.orange { background-color: #f39c12; }
        .project-icon.red { background-color: #e74c3c; }
        .project-icon.purple { background-color: #9b59b6; }
        .project-icon.teal { background-color: #1abc9c; }
        .project-icon.dark { background-color: #34495e; }
        .project-icon.pink { background-color: #e84393; }
        .project-icon.indigo { background-color: #4834d4; }
        .project-icon.cyan { background-color: #00cec9; }
        
        .project-title {
            font-size: 1.25rem;
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
        
        .btn-project {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .btn-project:hover {
            background-color: #2980b9;
            color: #ffffff;
        }
        
        .btn-project i {
            margin-right: 0.5rem;
        }
        
        .btn-edit {
            background-color: #f39c12;
            color: #ffffff;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .btn-edit:hover {
            background-color: #e67e22;
            color: #ffffff;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: #ffffff;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
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
        
        .category-filter {
            background: #ffffff;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #ecf0f1;
        }
        
        .category-badge {
            background-color: #ecf0f1;
            color: #7f8c8d;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .category-badge:hover,
        .category-badge.active {
            background-color: #3498db;
            color: #ffffff;
        }
        
        /* Status badge styles */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            color: #ffffff;
        }
        
        .footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #ecf0f1;
        }

        /* Project image */
        .project-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .no-image {
            width: 100%;
            height: 120px;
            background: #ecf0f1;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #95a5a6;
            font-size: 2rem;
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
                <a class="btn btn-primary" href="admin/login.php" target="_blank">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Admin Login
                </a>
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
                        All Projects
                    </h1>
                    <p class="page-subtitle mt-2">
                        Manage and access all your PHP projects from a single dashboard
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-1">
        <!-- Stats Cards -->
        <div class="row mb-1">
            <div class="col-md-3">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-number" id="totalProjects">0</p>
                            <p class="stats-label">Total Projects</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-folder" style="font-size:48px;color:#16a3a6;"></i>
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
                            <i class="fas fa-play-circle" style="font-size:48px;color:green;"></i>
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
                            <i class="fas fa-tools"style="font-size:48px;color:#841a87;"></i>
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
                            <i class="fas fa-code" style="font-size:48px;color:#d19317;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-0">
            <div class="col-md-6 mb-0 mb-md-0">
                <input type="text" class="search-box" id="searchInput" placeholder="Search projects by name...">
            </div>
            <div class="col-md-6">
                <div class="category-filter">
                    <span class="category-badge active" data-category="all">All</span>
                    <span class="category-badge" data-category="Website">Website</span>
                    <span class="category-badge" data-category="System">System</span>
                    <span class="category-badge" data-category="Other">Other</span>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4" id="projectsContainer">
            <!-- Projects will be loaded dynamically -->
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        <i class="far fa-copyright me-1"></i><?php
                    $currentYear = date("Y");
                    echo $currentYear;
                    ?>
                         Project Master Dashboard. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted">
                        <i class="fas fa-code me-1"></i>
                        Desing, Developed & Maintenance by ICT Division, BCIC. 
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script>
        let projectsData = [];
        let currentFilter = {search: '', category: 'all'};

        function loadProjects() {
            fetch("admin/api/get_projects.php")
                .then(res => res.json())
                .then(data => {
                    projectsData = data.records;
                    updateStats(projectsData);
                    displayProjects(projectsData);
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById("projectsContainer").innerHTML = `
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-circle fa-3x" style="color: #e74c3c;"></i>
                                <h4 class="mt-3" style="color: #7f8c8d;">Error loading projects</h4>
                                <p class="text-muted">Please try again later.</p>
                            </div>
                        </div>
                    `;
                });
        }

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

        function displayProjects(projects) {
            const container = document.getElementById("projectsContainer");
            
            // Filter projects
            let filtered = projects.filter(p => {
                let match = true;
                if (currentFilter.search) {
                    match = p.project_name.toLowerCase().includes(currentFilter.search.toLowerCase());
                }
                if (currentFilter.category !== 'all') {
                    match = match && p.category === currentFilter.category;
                }
                return match;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x" style="color: #bdc3c7;"></i>
                            <h4 class="mt-3" style="color: #7f8c8d;">No projects found</h4>
                            <p class="text-muted">Try adjusting your search or filter criteria.</p>
                        </div>
                    </div>
                `;
                return;
            }

            let html = '';
            filtered.forEach(project => {
                const icon = getIconForProject(project.project_name);
                
                // Image HTML
                let imageHtml = '';
                if (project.screenshot) {
                    imageHtml = `<img src="uploads/${project.screenshot}" class="project-image" alt="${project.project_name}">`;
                } else {
                    imageHtml = `<div class="no-image"><i class="fas ${icon}"></i></div>`;
                }

                // Determine link for project
                let projectLink = project.project_url;
                let launchText = 'Launch';
                let launchIcon = 'fa-rocket';
                if (project.status === 'Maintenance' || project.status === 'Development') {
                    projectLink = `status_page.php?id=${project.id}`;
                    launchText = 'View Status';
                    launchIcon = 'fa-info-circle';
                }

                // Status color
                const statusColor = getStatusColor(project.status);

                html += `
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="project-card">
                        <div class="card-body text-center">
                            ${imageHtml}
                            <div class="project-icon" style="background-color: ${project.icon_color || '#3498db'};">
                                <i class="fas ${icon}"></i>
                            </div>
                            <h5 class="project-title">${escapeHtml(project.project_name)}</h5>
                            <div class="project-url">
                                <i class="fas fa-link me-1"></i>
                                ${escapeHtml(project.project_url)}
                            </div>
                            <span class="status-badge" style="background-color: ${statusColor};">
                                ${project.status}
                            </span>
                            <a href="${projectLink}" class="btn-project" target="_blank">
                                <i class="fas ${launchIcon}"></i> ${launchText}
                            </a>
                        </div>
                    </div>
                </div>
                `;
            });

            container.innerHTML = html;
        }

        // Search input
        document.getElementById("searchInput").addEventListener("keyup", (e) => {
            currentFilter.search = e.target.value;
            displayProjects(projectsData);
        });

        // Category filter
        document.querySelectorAll(".category-badge").forEach(b => {
            b.addEventListener("click", function() {
                document.querySelectorAll(".category-badge").forEach(badge => {
                    badge.classList.remove('active');
                });
                this.classList.add('active');
                currentFilter.category = this.dataset.category;
                displayProjects(projectsData);
            });
        });

        // Icon helper
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

        // Status color helper
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

        // Load projects on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProjects();
        });
    </script>
</body>
</html>