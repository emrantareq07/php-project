<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>

<title>Project Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background:#f4f6f9;
}

.project-card{
background:white;
border-radius:10px;
box-shadow:0 3px 8px rgba(0,0,0,0.1);
margin-bottom:20px;
}

.project-icon{
width:60px;
height:60px;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
color:white;
font-size:22px;
margin:auto;
margin-bottom:10px;
}

.project-url{
font-size:13px;
color:#666;
word-break:break-all;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">

<h3>Project Dashboard</h3>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
  Add Project
</button>

</div>


<div class="row mb-4">

<div class="col-md-3">
<div class="card text-center">
<div class="card-body">
<h4 id="totalProjects">0</h4>
<p>Total</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center">
<div class="card-body">
<h4 id="activeProjects">0</h4>
<p>Active</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center">
<div class="card-body">
<h4 id="maintenanceProjects">0</h4>
<p>Maintenance</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center">
<div class="card-body">
<h4 id="developmentProjects">0</h4>
<p>Development</p>
</div>
</div>
</div>

</div>


<div class="row" id="projectsContainer"></div>

</div>


<!-- ADD MODAL -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="addProjectForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <input class="form-control mb-2" name="project_name" placeholder="Project Name" required>

          <input class="form-control mb-2" name="project_url" placeholder="Project URL" required>

          <select class="form-control mb-2" name="category">
            <option value="Website">Website</option>
            <option value="System">System</option>
            <option value="Other">Other</option>
          </select>

          <select class="form-control mb-2" name="status">
            <option>Active</option>
            <option>Maintenance</option>
            <option>Development</option>
          </select>

          <input class="form-control mb-2" name="icon_color" value="#3498db">

          <textarea class="form-control" name="description" placeholder="Description"></textarea>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Save Project</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

function loadProjects(){

fetch("api/get_projects.php")

.then(res=>res.json())

.then(data=>{

displayProjects(data.records)

updateStats(data.stats)

})

}


function displayProjects(projects){

const container=document.getElementById("projectsContainer")

let html=""

projects.forEach(project=>{

html+=`

<div class="col-md-3">

<div class="project-card">

<div class="card-body text-center">

<div class="project-icon" style="background:${project.icon_color}">
<i class="fas fa-code"></i>
</div>

<h5>${project.project_name}</h5>

<div class="project-url">${project.project_url}</div>

<span class="badge bg-primary">${project.status}</span>

<div class="mt-3">

<a href="${project.project_url}" target="_blank" class="btn btn-success btn-sm w-100 mb-2">
Launch
</a>

<button onclick="deleteProject(${project.id})" class="btn btn-danger btn-sm w-100">
Delete
</button>

</div>

</div>
</div>

</div>

`

})

container.innerHTML=html

}


function updateStats(stats){

document.getElementById("totalProjects").innerText=stats.total
document.getElementById("activeProjects").innerText=stats.active
document.getElementById("maintenanceProjects").innerText=stats.maintenance
document.getElementById("developmentProjects").innerText=stats.development

}

document.getElementById("addProjectForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("api/save_project.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            alert("Project Added Successfully!");

            // Hide modal properly
            const modalEl = document.getElementById('addProjectModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            this.reset(); // reset form
            loadProjects(); // reload projects
        }else{
            alert(data.message || "Error saving project");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Server error");
    });
});


function deleteProject(id){

if(!confirm("Delete project?")) return

fetch("api/delete_project.php",{

method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"id="+id

})
.then(res=>res.json())
.then(data=>{

loadProjects()

})

}

loadProjects()

</script>

</body>
</html>