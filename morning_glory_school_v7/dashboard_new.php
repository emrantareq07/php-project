<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">

  <style>
    /*.sidebar-list { list-style: none; padding-left: 0; margin: 0; }
    .sidebar-list-item a {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: 8px 12px;
      text-decoration: none;
      color: #333;
    }
    .sidebar-list-item a:hover { background: #f0f0f0; border-radius: 6px; }
    #settingsMenu li a { font-size: 14px; }

    /* Chevron rotate animation */
    /*a[data-bs-toggle="collapse"] .chevron { transition: transform .2s ease; margin-left: auto; }
    a[data-bs-toggle="collapse"].collapsed .chevron { transform: rotate(0deg); }
    a[data-bs-toggle="collapse"]:not(.collapsed) .chevron { transform: rotate(180deg); }*/*/
  </style>
</head>
<body>
  <div class="grid-container">

    <!-- Header -->
    <header class="header">
      <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
      </div>
      <div class="header-left">
        <span class="material-icons-outlined">search</span>
      </div>
      <div class="header-right">
        <span class="material-icons-outlined">notifications</span>
        <span class="material-icons-outlined">email</span>
        <span class="material-icons-outlined">account_circle</span>
      </div>
    </header>
    <!-- End Header -->

    <!-- Sidebar -->
    <aside id="sidebar">
      <div class="sidebar-title d-flex justify-content-between align-items-center">
        <div class="sidebar-brand d-flex align-items-center gap-2">
          <span class="material-icons-outlined">inventory</span> Admin Dashboard
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
      </div>

      <ul class="sidebar-list">
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">dashboard</span> Add Notice</a>
        </li>
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">inventory_2</span> Message</a>
        </li>
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">fact_check</span> Latest News</a>
        </li>
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">add_shopping_cart</span> Teacher Info.</a>
        </li>
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">shopping_cart</span> Photo Gallery</a>
        </li>
        <li class="sidebar-list-item">
          <a href="#" target="_blank"><span class="material-icons-outlined">poll</span> Student Registration/Admission</a>
        </li>

        <!-- Settings collapsible -->
        <li class="sidebar-list-item">
          <a class="collapsed" href="#" role="button"
             data-bs-toggle="collapse" data-bs-target="#settingsMenu"
             aria-expanded="false" aria-controls="settingsMenu">
            <span class="material-icons-outlined">settings</span>
            Settings
            <span class="material-icons-outlined chevron">expand_more</span>
          </a>
          <ul class="collapse list-unstyled ps-4 mt-1" id="settingsMenu">
            <li class="sidebar-list-item">
              <a href="#" target="_blank"><span class="material-icons-outlined">poll</span> Manage Categories & Sub-Category</a>
            </li>
            <li class="sidebar-list-item">
              <a href="#" target="_blank"><span class="material-icons-outlined">poll</span> Add Subject</a>
            </li>
          </ul>
        </li>
      </ul>
    </aside>
    <!-- End Sidebar -->

    <!-- Main -->
    <main class="main-container">
      <div class="main-title"><p class="font-weight-bold">DASHBOARD</p></div>
      <div class="main-cards">
        <div class="card"><div class="card-inner"><p class="text-primary">PRODUCTS</p><span class="material-icons-outlined text-blue">inventory_2</span></div><span class="text-primary font-weight-bold">249</span></div>
        <div class="card"><div class="card-inner"><p class="text-primary">PURCHASE ORDERS</p><span class="material-icons-outlined text-orange">add_shopping_cart</span></div><span class="text-primary font-weight-bold">83</span></div>
        <div class="card"><div class="card-inner"><p class="text-primary">SALES ORDERS</p><span class="material-icons-outlined text-green">shopping_cart</span></div><span class="text-primary font-weight-bold">79</span></div>
        <div class="card"><div class="card-inner"><p class="text-primary">INVENTORY ALERTS</p><span class="material-icons-outlined text-red">notification_important</span></div><span class="text-primary font-weight-bold">56</span></div>
      </div>
      <div class="charts">
        <div class="charts-card"><p class="chart-title">Top 5 Products</p><div id="bar-chart"></div></div>
        <div class="charts-card"><p class="chart-title">Purchase and Sales Orders</p><div id="area-chart"></div></div>
      </div>
    </main>
    <!-- End Main -->

  </div>

  <!-- Scripts -->
  <!-- Bootstrap JS bundle (needed for collapse) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- ApexCharts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
  <!-- Custom JS -->
  <script src="js/scripts.js"></script>
</body>
</html>
