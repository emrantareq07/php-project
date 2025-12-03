<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .description {
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .databases-list {
            margin-bottom: 30px;
        }
        
        .database-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .database-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .backup-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: block;
            width: 100%;
            transition: background 0.3s;
        }
        
        .backup-btn:hover {
            background: #2980b9;
        }
        
        .backup-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
        
        .progress-container {
            margin-top: 20px;
            display: none;
        }
        
        .progress-bar {
            height: 20px;
            background: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress {
            height: 100%;
            background: #2ecc71;
            width: 0%;
            transition: width 0.3s;
        }
        
        .status {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .download-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: none;
        }
        
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Database Backup System</h1>
            <p class="description">Click the button below to download backups of all your databases in a single click.</p>
        </header>
        
        <div class="notification success" id="success-notification">
            Backup completed successfully! Your download will start shortly.
        </div>
        
        <div class="notification error" id="error-notification">
            An error occurred during the backup process. Please try again.
        </div>
        
        <div class="databases-list">
            <h2>Databases to be backed up:</h2>
            <div class="database-item">
                <span class="database-name">blrrdb</span>
                <span class="database-size">Size: N/A</span>
            </div>
            <div class="database-item">
                <span class="database-name">dfmsdb</span>
                <span class="database-size">Size: N/A</span>
            </div>
            <div class="database-item">
                <span class="database-name">ictmainrecordsdb</span>
                <span class="database-size">Size: N/A</span>
            </div>
        </div>
        
        <button class="backup-btn" id="backup-btn">Download All Databases Backup</button>
        
        <div class="progress-container" id="progress-container">
            <div class="progress-bar">
                <div class="progress" id="progress-bar"></div>
            </div>
            <div class="status" id="status-text">Preparing backup...</div>
        </div>
        
        <a href="#" class="download-link" id="download-link">Click here if download doesn't start automatically</a>
        
        <footer>
            <p>Database Backup System &copy; 2023 | Secure and reliable</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backupBtn = document.getElementById('backup-btn');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const statusText = document.getElementById('status-text');
            const downloadLink = document.getElementById('download-link');
            const successNotification = document.getElementById('success-notification');
            const errorNotification = document.getElementById('error-notification');
            
            backupBtn.addEventListener('click', function() {
                // Reset UI
                progressBar.style.width = '0%';
                successNotification.style.display = 'none';
                errorNotification.style.display = 'none';
                
                // Show progress
                progressContainer.style.display = 'block';
                backupBtn.disabled = true;
                statusText.textContent = 'Preparing backup...';
                
                // Simulate progress for demo purposes
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    progressBar.style.width = progress + '%';
                    
                    if (progress === 5) statusText.textContent = 'Connecting to database servers...';
                    if (progress === 25) statusText.textContent = 'Backing up blrr_db...';
                    if (progress === 50) statusText.textContent = 'Backing up dfms_db...';
                    if (progress === 75) statusText.textContent = 'Backing up ict_main_records_db...';
                    if (progress === 90) statusText.textContent = 'Compressing backup files...';
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        statusText.textContent = 'Backup complete! Preparing download...';
                        
                        // Simulate server processing time
                        setTimeout(() => {
                            // In a real scenario, this would be an actual PHP request
                            simulateBackupCompletion();
                        }, 1500);
                    }
                }, 200);
            });
            
            function simulateBackupCompletion() {
                // For demonstration purposes, we're just showing a success message
                // In a real implementation, this would be handled by the PHP backend
                
                successNotification.style.display = 'block';
                downloadLink.style.display = 'block';
                downloadLink.href = '#'; // This would point to the actual backup file
                
                statusText.textContent = 'Backup completed successfully!';
                backupBtn.disabled = false;
                
                // In a real implementation, the page would redirect to download the file
                // window.location.href = 'backup.php?action=download';
            }
        });
    </script>
</body>
</html>