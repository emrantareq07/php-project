<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Projects</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<style>
body {background:#121212;color:#fff;}
.project-card {background:#1e1e1e;color:#fff;border-radius:8px;margin-bottom:20px;padding:15px;}
.project-icon {width:60px;height:60px;border-radius:50%;display:flex;justify-content:center;align-items:center;margin:0 auto 10px;}
.project-url {font-size:12px;overflow-wrap:anywhere;color:#ccc;}
.status-badge {color:#fff;padding:2px 6px;border-radius:4px;font-size:12px;}
</style>
</head>
<body>
<div class="container py-4">
<h2 class="mb-3 text-uppercase">All Projects</h2>

<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search projects...">

<div class="mb-3">
<span class="badge bg-secondary category-badge" data-category="all">All</span>
<span class="badge bg-primary category-badge" data-category="Website">Website</span>
<span class="badge bg-success category-badge" data-category="System">System</span>
<span class="badge bg-warning text-dark category-badge" data-category="Other">Other</span>
</div>

<div class="row" id="projectsContainer"></div>
</div>

<script>
let projectsData = [];
let currentFilter = {search:'', category:'all'};

function loadProjects(){
    fetch("admin/api/get_projects.php")
    .then(res => res.json())
    .then(data => {
        projectsData = data.records;
        displayProjects(projectsData);
    })
    .catch(err => {
        console.error(err);
        document.getElementById("projectsContainer").innerHTML = '<p class="text-danger">Error loading projects</p>';
    });
}

function displayProjects(projects){
    const container = document.getElementById("projectsContainer");
    container.innerHTML = '';

    // Filter projects
    let filtered = projects.filter(p => {
        let match = true;
        if(currentFilter.search){
            match = p.project_name.toLowerCase().includes(currentFilter.search.toLowerCase());
        }
        if(currentFilter.category !== 'all'){
            match = match && p.category === currentFilter.category;
        }
        return match;
    });

    if(filtered.length === 0){
        container.innerHTML = '<p class="text-center py-5">No projects found</p>';
        return;
    }

    // Build HTML
    let html = '';
    filtered.forEach(project => {
        const icon = getIconForProject(project.project_name);
        let image = project.screenshot 
            ? `<img src="uploads/${project.screenshot}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-bottom:10px">`
            : '';

        // Determine link for project
        let projectLink = project.project_url;
        let launchText = 'Launch';
        if(project.status === 'Maintenance' || project.status === 'Development'){
            projectLink = `status_page.php?id=${project.id}`;
            launchText = 'View Status';
        }

        html += `
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="project-card card mb-3">
                <div class="card-body text-center">
                    ${image}
                    <div class="project-icon" style="background:${project.icon_color}">
                        <i class="fas ${icon}"></i>
                    </div>
                    <h5>${project.project_name}</h5>
                    <div class="project-url">${project.project_url}</div>
                    <span class="badge" style="background:${getStatusColor(project.status)}">${project.status}</span>
                    <div class="mt-3">
                        <a href="${projectLink}" class="btn btn-success btn-sm w-100 mb-2">${launchText}</a>
                       
                    </div>
                </div>
            </div>
        </div>
        `;
    });
     // <button onclick="openEditModal(${project.id})" class="btn btn-warning btn-sm w-100 mb-2">Edit</button>
                        // <button onclick="deleteProject(${project.id})" class="btn btn-danger btn-sm w-100">Delete</button>

    container.innerHTML = html;
}

// Search input
document.getElementById("searchInput").addEventListener("keyup", e => {
    currentFilter.search = e.target.value;
    displayProjects(projectsData);
});

// Category filter
document.querySelectorAll(".category-badge").forEach(b => {
    b.addEventListener("click", function(){
        currentFilter.category = this.dataset.category;
        displayProjects(projectsData);
    });
});

// Icon helper
function getIconForProject(name){
    const n = name.toLowerCase();
    if(n.includes("shop")) return "fa-shopping-cart";
    if(n.includes("cms")) return "fa-cogs";
    if(n.includes("dashboard")) return "fa-chart-line";
    if(n.includes("crm")) return "fa-users";
    return "fa-project-diagram";
}

// Status color helper
function getStatusColor(status){
    if(status === "Active") return "#27ae60";
    if(status === "Maintenance") return "#f39c12";
    if(status === "Development") return "#3498db";
    return "#7f8c8d";
}

// Load projects on page load
loadProjects();
</script>
</body>
</html>