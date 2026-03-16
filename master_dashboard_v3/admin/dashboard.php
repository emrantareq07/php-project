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
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<style>
body {background:#121212;color:#fff;}
.project-card {background:#1e1e1e;border-radius:8px;padding:15px;margin-bottom:20px;}
.project-icon {width:60px;height:60px;border-radius:50%;display:flex;justify-content:center;align-items:center;margin:0 auto 10px;}
.project-url {font-size:12px;color:#ccc;overflow-wrap:anywhere;}
.status-badge {color:#fff;padding:2px 6px;border-radius:4px;font-size:12px;}
.color-option {width:20px;height:20px;border-radius:50%;display:inline-block;margin:0 3px;cursor:pointer;}
.color-option.selected {border:2px solid #fff;}
</style>
</head>
<body>
<div class="container py-4">
<h2 class="mb-3">Projects Dashboard</h2>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Project</button>
<a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out-alt me-2 "></i>Logout</a>
<div class="row" id="projectsContainer"></div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content bg-dark text-light">
<div class="modal-header"><h5>Add Project</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>

<div class="modal-body">
<form id="addForm" enctype="multipart/form-data">
<input class="form-control mb-2" name="project_name" placeholder="Project Name" required>
<input class="form-control mb-2" name="project_url" placeholder="Project URL" required>
<!-- <input class="form-control mb-2" name="category" placeholder="Category"> -->
<select class="form-control mb-2" name="category">
            <option value="Website">Website</option>
            <option value="System">System</option>
            <option value="Other">Other</option>
          </select>
<select class="form-control mb-2" name="status">
<option>Active</option><option>Maintenance</option><option>Development</option>
</select>
<textarea class="form-control mb-2" name="description" placeholder="Description"></textarea>
<input type="file" class="form-control mb-2" name="screenshot">
<div class="mb-2">Icon Color:
<span class="color-option" style="background:#3498db" onclick="selectColor(this,'#3498db')"></span>
<span class="color-option" style="background:#e74c3c" onclick="selectColor(this,'#e74c3c')"></span>
<input type="hidden" id="selectedColor" name="icon_color" value="#3498db">
</div>
<button class="btn btn-primary w-100">Save</button>
</form>
</div></div></div></div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content bg-dark text-light">
<div class="modal-header"><h5>Edit Project</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<form id="editForm" enctype="multipart/form-data">
<input type="hidden" name="id" id="edit_id">
<input class="form-control mb-2" name="project_name" id="edit_name" placeholder="Project Name" required>
<input class="form-control mb-2" name="project_url" id="edit_url" placeholder="Project URL" required>
<!-- <input class="form-control mb-2" name="category" id="edit_category" placeholder="Category"> -->
<select class="form-control mb-2" name="category" id="edit_category" placeholder="Category">
            <option value="Website">Website</option>
            <option value="System">System</option>
            <option value="Other">Other</option>
          </select>
<select class="form-control mb-2" name="status" id="edit_status">
<option>Active</option><option>Maintenance</option><option>Development</option>
</select>
<textarea class="form-control mb-2" name="description" id="edit_desc" placeholder="Description"></textarea>
<input type="file" class="form-control mb-2" name="screenshot">
<button class="btn btn-primary w-100">Update</button>
</form>
</div></div></div></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

// Global
let selectedColor='#3498db';

function selectColor(el,color){document.querySelectorAll('.color-option').forEach(e=>e.classList.remove('selected'));el.classList.add('selected');selectedColor=color;document.getElementById('selectedColor').value=color;}

function loadProjects(){
fetch("api/get_projects.php").then(r=>r.json()).then(data=>{
const container=document.getElementById('projectsContainer');
container.innerHTML='';
data.records.sort((a,b)=>a.sort_order-b.sort_order).forEach(p=>{
let icon='fa-project-diagram';
if(p.project_name.toLowerCase().includes('shop')) icon='fa-shopping-cart';
container.innerHTML+=`
<div class="col-md-3 project-card" data-id="${p.id}">
<div class="project-icon" style="background:${p.icon_color}"><i class="fas ${icon}"></i></div>
<h5>${p.project_name}</h5>
<div class="project-url">${p.project_url}</div>
<span class="status-badge" style="background:${p.status==='Active'?'#27ae60':p.status==='Maintenance'?'#f39c12':'#3498db'}">${p.status}</span>
<div class="mt-2">
<button class="btn btn-warning btn-sm w-100 mb-1" onclick="openEdit(${p.id})">Edit</button>
<button class="btn btn-danger btn-sm w-100" onclick="deleteProject(${p.id})">Delete</button>
</div></div>`;});

// Drag & Drop
Sortable.create(container,{animation:150,onEnd:function(evt){
let ids=[...container.querySelectorAll('.project-card')].map(e=>e.dataset.id);
fetch('api/reorder_projects.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({ids})});
}});
});
}

// Add
// document.getElementById('addForm').addEventListener('submit',function(e){
// e.preventDefault();
// let fd=new FormData(this);
// fd.set('icon_color',selectedColor);
// fetch('api/save_project.php',{method:'POST',body:fd})
// .then(r=>r.json()).then(d=>{alert(d.message);document.getElementById('addForm').reset();loadProjects();});
// });

document.getElementById('addForm').addEventListener('submit', function(e){
    e.preventDefault();

    let fd = new FormData(this);
    fd.set('icon_color', selectedColor);

    fetch('api/save_project.php', {
        method: 'POST',
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);

        // Hide the modal properly
        let addModalEl = document.getElementById('addModal');
        let modal = bootstrap.Modal.getInstance(addModalEl);
        modal.hide();

        // Reset the form
        this.reset();
        selectedColor = '#3498db'; // reset color
        document.getElementById('selectedColor').value = selectedColor;

        loadProjects();
    });
});
// Edit
function openEdit(id){
fetch('api/get_projects.php').then(r=>r.json()).then(d=>{
let p=d.records.find(x=>x.id==id);
document.getElementById('edit_id').value=p.id;
document.getElementById('edit_name').value=p.project_name;
document.getElementById('edit_url').value=p.project_url;
document.getElementById('edit_category').value=p.category;
document.getElementById('edit_status').value=p.status;
document.getElementById('edit_desc').value=p.description;
new bootstrap.Modal(document.getElementById('editModal')).show();
});
}
document.getElementById('editForm').addEventListener('submit', function(e){
    e.preventDefault();

    let fd = new FormData(this);
    fd.set('icon_color', selectedColor);

    fetch('api/update_project.php', {
        method: 'POST',
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);

        // Hide the modal properly
        let editModalEl = document.getElementById('editModal');
        let modal = bootstrap.Modal.getInstance(editModalEl);
        modal.hide();

        loadProjects();
    });
});

document.getElementById('editForm').addEventListener('submit',function(e){
e.preventDefault();
let fd=new FormData(this);
fetch('api/update_project.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{alert(d.message);loadProjects();});
});

// Delete
function deleteProject(id){if(!confirm('Delete project?')) return;fetch('api/delete_project.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'id='+id}).then(r=>r.json()).then(d=>{alert(d.message);loadProjects();});}

loadProjects();

</script>
</body>
</html>