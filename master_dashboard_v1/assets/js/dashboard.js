function loadProjects(){

fetch("api/get_projects.php")
.then(res => res.json())
.then(data => {

let html = "";

let total = 0;
let active = 0;
let maintenance = 0;
let development = 0;

data.forEach(p => {

total++;

if(p.status=="Active") active++;
if(p.status=="Maintenance") maintenance++;
if(p.status=="Development") development++;

html += `
<div class="col-md-4">

<div class="project-card">

<div class="card-body">

<div class="project-icon" style="background:${p.icon_color}20;color:${p.icon_color}">
<i class="fas fa-folder"></i>
</div>

<h5 class="project-title">${p.project_name}</h5>

<p class="project-url">${p.project_url}</p>

<span class="badge bg-secondary">${p.category}</span>

<br><br>

<span class="status-badge" style="background:${getStatusColor(p.status)}">
${p.status}
</span>

<div class="mt-3">

<a href="${p.project_url}" target="_blank" class="btn-project">
Open Project
</a>

<button class="btn btn-sm btn-warning mt-2 w-100" onclick="editProject(${p.id})">
Edit
</button>

<button class="btn btn-sm btn-danger mt-2 w-100" onclick="deleteProject(${p.id})">
Delete
</button>

</div>

</div>
</div>

</div>
`;

});

document.getElementById("projectsContainer").innerHTML = html;

document.getElementById("totalProjects").innerText = total;
document.getElementById("activeProjects").innerText = active;
document.getElementById("maintenanceProjects").innerText = maintenance;
document.getElementById("developmentProjects").innerText = development;

});

}

function getStatusColor(status){

if(status=="Active") return "#27ae60";
if(status=="Maintenance") return "#f39c12";
if(status=="Development") return "#3498db";

}

