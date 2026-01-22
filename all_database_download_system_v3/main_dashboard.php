<?php
session_start();
// if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
//     header('Location: login.php'); exit;
// }
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <title>Database Backup Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
      --dark-bg: #0f172a;
      --card-bg: #1e293b;
      --glass-bg: rgba(255, 255, 255, 0.05);
    }
    
    * {
      font-family: 'Poppins', sans-serif;
    }
    
    body {
      background: var(--dark-bg);
      color: #f8fafc;
      min-height: 100vh;
      background-image: 
        radial-gradient(circle at 10% 20%, rgba(100, 126, 234, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 20%);
    }
    
    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      transition: all 0.3s ease;
    }
    
    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .gradient-primary {
      background: var(--primary-gradient);
    }
    
    .gradient-success {
      background: var(--success-gradient);
    }
    
    .gradient-warning {
      background: var(--warning-gradient);
    }
    
    .stat-card {
      border-radius: 15px;
      padding: 25px;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: translate(30%, -30%);
    }
    
    .btn-gradient {
      background: var(--primary-gradient);
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-gradient:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(100, 126, 234, 0.3);
      color: white;
    }
    
    .btn-gradient::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }
    
    .btn-gradient:hover::after {
      left: 100%;
    }
    
    .dashboard-header {
      padding: 40px 0;
      text-align: center;
      position: relative;
    }
    
    .dashboard-header h1 {
      font-size: 2.8rem;
      font-weight: 700;
      background: linear-gradient(90deg, #667eea, #764ba2, #f56565);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 10px;
    }
    
    .dashboard-header p {
      font-size: 1.1rem;
      opacity: 0.8;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .feature-icon {
      width: 70px;
      height: 70px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      margin-bottom: 20px;
      background: rgba(255, 255, 255, 0.1);
    }
    
    .module-card {
      height: 100%;
      padding: 30px;
      text-align: center;
    }
    
    .module-card h3 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    .module-card p {
      color: #cbd5e1;
      line-height: 1.6;
      margin-bottom: 25px;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 20px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
    }
    
    .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: white;
    }
    
    .recent-activity {
      padding: 25px;
    }
    
    .activity-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .activity-item:last-child {
      border-bottom: none;
    }
    
    .activity-icon {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(100, 126, 234, 0.7); }
      70% { box-shadow: 0 0 0 10px rgba(100, 126, 234, 0); }
      100% { box-shadow: 0 0 0 0 rgba(100, 126, 234, 0); }
    }
    
    .footer {
      margin-top: 50px;
      padding: 20px 0;
      text-align: center;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #94a3b8;
    }
    
    .theme-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
    }
    
    @media (max-width: 768px) {
      .dashboard-header h1 {
        font-size: 2rem;
      }
      
      .module-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<!-- Theme Toggle Button -->
<div class="theme-toggle">
  <button class="btn btn-outline-light rounded-circle" id="themeToggle">
    <i class="bi bi-moon-stars"></i>
  </button>
</div>

<div class="container-fluid px-4">
  <!-- Header Section -->
  <div class="dashboard-header">
    <div class="row align-items-center">
      <div class="col-lg-8 mx-auto">
        <h1>Database Backup Manager</h1>
        <p class="mb-4">Secure, automated database backups with email notifications and cloud storage options</p>
        
        <!-- User Profile -->
        <div class="user-profile glass-card d-inline-flex">
          <div class="avatar">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="user-info">
            <h5 class="mb-0">Welcome, Administrator</h5>
            <small class="text-muted">Last login: Today, 10:30 AM</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="stat-card gradient-primary">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h3 class="mb-2">24</h3>
            <p class="mb-0">Total Databases</p>
          </div>
          <i class="bi bi-database display-6 opacity-50"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card gradient-success">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h3 class="mb-2">156</h3>
            <p class="mb-0">Backups Created</p>
          </div>
          <i class="bi bi-archive display-6 opacity-50"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card gradient-warning">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h3 class="mb-2">2.4 GB</h3>
            <p class="mb-0">Total Storage</p>
          </div>
          <i class="bi bi-hdd display-6 opacity-50"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="glass-card p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h3 class="mb-2">100%</h3>
            <p class="mb-0">System Health</p>
          </div>
          <div class="text-success">
            <i class="bi bi-check-circle-fill display-6"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Action Cards -->
  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="glass-card module-card pulse">
        <div class="feature-icon mx-auto">
          <i class="bi bi-envelope-paper"></i>
        </div>
        <h3>Backup & Email</h3>
        <p>Create a complete backup of all databases and automatically send the compressed file to your email address. Perfect for regular automated backups.</p>
        <div class="d-grid gap-2">
          <a href="dashboard_send_mail.php" class="btn btn-gradient">
            <i class="bi bi-cloud-arrow-up me-2"></i> Start Backup with Email
          </a>
          <small class="text-muted mt-2">
            <i class="bi bi-info-circle me-1"></i> Backup will be sent to: pmisict@gmail.com
          </small>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="glass-card module-card">
        <div class="feature-icon mx-auto">
          <i class="bi bi-download"></i>
        </div>
        <h3>Direct Download</h3>
        <p>Quickly backup all databases and download the ZIP file directly to your computer. No email required. Fast and efficient for immediate needs.</p>
        <div class="d-grid gap-2">
          <a href="dashboard.php" class="btn btn-outline-light btn-lg">
            <i class="bi bi-cloud-download me-2"></i> Download Backup Only
          </a>
          <small class="text-muted mt-2">
            <i class="bi bi-lightning me-1"></i> Faster processing, direct download
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Features -->
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="glass-card recent-activity">
        <h4 class="mb-4"><i class="bi bi-clock-history me-2"></i> Recent Activity</h4>
        <div class="activity-item">
          <div class="activity-icon bg-primary bg-opacity-25 text-primary">
            <i class="bi bi-database"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">Full database backup completed</h6>
            <small class="text-muted">2 hours ago • 24 databases • 450MB</small>
          </div>
          <span class="badge bg-success">Successful</span>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon bg-success bg-opacity-25 text-success">
            <i class="bi bi-envelope-check"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">Backup email sent successfully</h6>
            <small class="text-muted">Yesterday • Sent to: pmisict@gmail.com</small>
          </div>
          <span class="badge bg-success">Delivered</span>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon bg-warning bg-opacity-25 text-warning">
            <i class="bi bi-gear"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">System maintenance performed</h6>
            <small class="text-muted">3 days ago • Cleaned old backups</small>
          </div>
          <span class="badge bg-info">Completed</span>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="glass-card p-4 h-100">
        <h4 class="mb-4"><i class="bi bi-sliders me-2"></i> Quick Actions</h4>
        <div class="d-grid gap-3">
          <button class="btn btn-outline-primary">
            <i class="bi bi-eye me-2"></i> View Backup History
          </button>
          <button class="btn btn-outline-info">
            <i class="bi bi-gear me-2"></i> Configure Settings
          </button>
          <button class="btn btn-outline-warning">
            <i class="bi bi-clock me-2"></i> Schedule Backups
          </button>
          <a href="login.php?logout=1" class="btn btn-outline-danger mt-3">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </div>
        
        <div class="mt-4 pt-4 border-top">
          <small class="text-muted d-block mb-2">
            <i class="bi bi-shield-check me-1"></i> System Status: <span class="text-success">All services operational</span>
          </small>
          <small class="text-muted d-block">
            <i class="bi bi-calendar-check me-1"></i> Next scheduled backup: <span class="text-info">Tomorrow, 02:00 AM</span>
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer mt-5">
    <div class="row">
      <div class="col-lg-6 mx-auto">
        <p class="mb-2">
          <i class="bi bi-cpu me-1"></i> Database Backup Manager v2.1 • 
          <span class="text-info">Secure • Automated • Reliable</span>
        </p>
        <p class="mb-0 small">
          <i class="bi bi-c-circle me-1"></i> 2024 Backup System • 
          <span class="text-muted">Last updated: Today 11:45 AM</span>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Theme Toggle
  document.getElementById('themeToggle').addEventListener('click', function() {
    const html = document.documentElement;
    const icon = this.querySelector('i');
    
    if (html.getAttribute('data-bs-theme') === 'dark') {
      html.removeAttribute('data-bs-theme');
      html.style.setProperty('--dark-bg', '#f8fafc');
      html.style.setProperty('--card-bg', '#ffffff');
      html.style.setProperty('--glass-bg', 'rgba(0, 0, 0, 0.03)');
      icon.className = 'bi bi-sun';
      this.classList.replace('btn-outline-light', 'btn-outline-dark');
    } else {
      html.setAttribute('data-bs-theme', 'dark');
      html.style.setProperty('--dark-bg', '#0f172a');
      html.style.setProperty('--card-bg', '#1e293b');
      html.style.setProperty('--glass-bg', 'rgba(255, 255, 255, 0.05)');
      icon.className = 'bi bi-moon-stars';
      this.classList.replace('btn-outline-dark', 'btn-outline-light');
    }
  });

  // Add animation to cards on load
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      setTimeout(() => {
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 100);
    });
  });
</script>

</body>
</html>