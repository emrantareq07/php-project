    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>BCIC</h3>
            <p>Innovation Database</p>
        </div>
        
        <div class="sidebar-menu">  
            <?php if ($role === 'admin'): ?>
                <!-- Admin Menu -->
                <a href="#" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="add_new_innovation.php">
                    <i class="fas fa-plus-circle"></i> Add Old Ideas
                </a>
                <a href="libs/fiscal_year_list.php">
                    <i class="fas fa-calendar"></i> Add Fiscal Years
                </a>
                <a href="add_designation.php">
                    <i class="fas fa-briefcase"></i> Designations
                </a>
                <a href="statistics_report.php">
                    <i class="fas fa-chart-bar"></i> Statistics
                </a>
                <a href="libs/all_innovations.php">
                    <i class="fas fa-list"></i> All Innovations
                </a>
                <a href="reports.php">
                    <i class="fas fa-file-pdf"></i> Reports
                </a>
                <a href="libs/innovation_ideas.php">
                    <i class="fas fa-cog"></i> Idea Settings
                </a>
                <a href="libs/submitted_innovation_ideas.php">
                    <i class="fas fa-info-circle"></i> Submitted Idea 
                    <span class="badge-count"><?php echo $submitted_idea_count; ?></span>
                </a>
            <?php else: ?>
                <!-- User Menu -->
                <a href="#" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="my_innovations.php">
                    <i class="fas fa-list"></i> My Innovations
                </a>
                <a href="add_new_innovation.php">
                    <i class="fas fa-plus-circle"></i> Submit New Idea
                </a>
            <?php endif; ?>
            
            <a href="logout.php" class="btn-action btn-danger-solid" style="margin: 20px 10px; display: block;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>