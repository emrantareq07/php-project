  <!-- Add this button at the beginning of your list to collapse all sections -->
<!-- <button id="collapse-all" class="btn btn-secondary mb-3">Collapse All</button> -->
<?php //include"header.php"?>

<!-- Include Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- Your HTML content -->

<style type="text/css">
  div.scrollmenu {
  /*background-color: #333;*/
  overflow: auto;
  white-space: nowrap;
}

/*div.scrollmenu a {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 10px;
  text-decoration: none;
}*/

div.scrollmenu a:hover {
  /*background-color: #777;*/
}
</style>
  <!-- Side-Nav -->
  <div data-bs-spy="scroll"  class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column scrollmenu" id="sidebar" >
    <ul class="nav flex-column text-white w-100 ">
<!-- 	<li class="nav-item">
	  <a href="/" class="d-flex  align-items-center pb-3 mb-3 h2 link-white  text-white my-1 text-decoration-none border-bottom">
      <svg class="bi me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
      <span class="fs-5 fw-semibold">Collapsible</span>
    </a>
	</li> -->
	
	
      <li class="nav-item">
        <a class="nav-link h3 text-muted my-2 text-light text-uppercase text-decoration-none border-bottom" href="#">
          Dashboard </br>
        </a>
      </li>
	        <li class="mb-1 ">
        <!-- <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
          <i class="bx bxs-home"></i>  &nbsp;Home
        </button> -->
        <a href="user_dashboard.php" class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
    <i class="bx bxs-home"></i> &nbsp;Home
</a>
      <!--   <div class="collapse" id="home-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div> -->
      </li>
	  
	  <ul class="list-unstyled ps-0 text-primary">
      <li class="mb-1 text-primary">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#personal_info-collapse" aria-expanded="false">
          <i class="bx bx-user-check"></i>&nbsp;Personal Info.
        </button>
        <div class="collapse" id="personal_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
			<li><a href="#" class="link-light rounded">Children Information</a></li>
          </ul>
        </div>
      </li>
      <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#edu_info-collapse" aria-expanded="false">
          <i class="bx bxs-book"></i>  &nbsp;Educational Info.
        </button>
        <div class="collapse" id="edu_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  	  <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#job_details-collapse" aria-expanded="false">
          <i class="bx bxs-pen"></i>  &nbsp;Job Details
        </button>
        <div class="collapse" id="job_details-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#service_history-collapse" aria-expanded="false">
          <i class="bx bx-command"></i>  &nbsp;Service History
        </button>
        <div class="collapse" id="service_history-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#training_info-collapse" aria-expanded="false">
          <i class=" bx bx-text"></i>  &nbsp;Training Information
        </button>
        <div class="collapse" id="training_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#prof_or_membership_info-collapse" aria-expanded="false">
          <i class="bx bx-sitemap"></i>  &nbsp;Prof./ Mermbership Info.
        </button>
        <div class="collapse" id="prof_or_membership_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  <li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#promotion_details-collapse" aria-expanded="false">
          <i class="bx bx-pulse"></i>  &nbsp;Promotion Details
        </button>
        <div class="collapse" id="promotion_details-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  	<li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#emp_bank_info-collapse" aria-expanded="false">
          <i class="bx bx-dollar"></i>  &nbsp;Emp Bank Info.
        </button>
        <div class="collapse" id="emp_bank_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  	  	<li class="mb-1 ">
        <button class="btn btn-toggle align-items-center rounded collapsed text-primary" data-bs-toggle="collapse" data-bs-target="#disciplinary_action_info-collapse" aria-expanded="false">
          <i class="bx bx-collapse"></i>  &nbsp;Disciplinary Action
        </button>
        <div class="collapse" id="disciplinary_action_info-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
             <li><a href="#" class="link-light rounded">Add</a></li>
            <li><a href="#" class="link-light rounded">Update</a></li>
            <li><a href="#" class="link-light rounded">View</a></li>
          </ul>
        </div>
      </li>
	  
	  	<!-- <li class="mb-1 "> -->
        <!-- <button class="btn btn-toggle align-items-center rounded collapsed text-primary" href="www.google.com" data-bs-toggle="collapse" data-bs-target="#-collapse" aria-expanded="false"> -->
          <!-- <i class="bx bxs-pen"></i>  &nbsp;Manage Users/Users List -->
        <!-- </button> -->
		<!-- </li> -->

    <!--   <li class="border-top my-3"></li>
      <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
          Account
        </button>
        <div class="collapse" id="account-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark rounded">New...</a></li>
            <li><a href="#" class="link-dark rounded">Profile</a></li>
            <li><a href="#" class="link-dark rounded">Settings</a></li>
            <li><a href="#" class="link-dark rounded">Sign out</a></li>
          </ul>
        </div>
      </li>
    </ul>
	  
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="bx bxs-dashboard"></i>
          <span class="mx-2">Home</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="bx bx-user-check"></i>
          <span class="mx-2">Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="bx bx-conversation"></i>
          <span class="mx-2">Contact</span>
        </a>
      </li>
    </ul>

    <div class="nav-link h4 w-100 mb-5">
      <a href="#"><i class="bx bxl-instagram-alt text-white"></i></a>
      <a href="#"><i class="bx bxl-twitter px-2 text-white"></i></a>
      <a href="#"><i class="bx bxl-facebook text-white"></i></a>
    </div> -->
  </div>
<!-- Include Bootstrap JS (make sure it's placed just before the closing </body> tag) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 -->
  <script>
document.addEventListener("DOMContentLoaded", function () {
  const collapseAllButton = document.getElementById("collapse-all");
  const collapseButtons = document.querySelectorAll("[data-bs-toggle='collapse']");

  collapseAllButton.addEventListener("click", function () {
    collapseButtons.forEach(function (button) {
      const targetId = button.getAttribute("data-bs-target");
      const target = document.querySelector(targetId);
      if (target && target.classList.contains("show")) {
        button.click();
      }
    });
  });
});
</script>


<script type="text/javascript">
var dataSpyList = [].slice.call(document.querySelectorAll('[data-bs-spy="scroll"]'))
dataSpyList.forEach(function (dataSpyEl) {
  bootstrap.ScrollSpy.getInstance(dataSpyEl)
    .refresh()
})
</script>