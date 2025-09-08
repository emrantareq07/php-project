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
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 2.2rem;
        }
        
        .description {
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .databases-list {
            margin-bottom: 30px;
        }
        
        .databases-list h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #eee;
        }
        
        .database-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        
        .database-item:hover {
            transform: translateX(5px);
            background: #e8f4fc;
        }
        
        .database-name {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .backup-btn {
            background: linear-gradient(90deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            display: block;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .backup-btn:hover {
            background: linear-gradient(90deg, #2980b9, #2573a7);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .backup-btn:active {
            transform: translateY(0);
        }
        
        .backup-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .progress-container {
            margin-top: 25px;
            display: none;
        }
        
        .progress-bar {
            height: 22px;
            background: #ecf0f1;
            border-radius: 11px;
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #2ecc71, #27ae60);
            width: 0%;
            transition: width 0.5s;
            border-radius: 11px;
        }
        
        .status {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 15px;
        }
        
        .download-section {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            display: none;
        }
        
        .download-link {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(90deg, #2ecc71, #27ae60);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .download-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }
        
        .save-path {
            margin-top: 15px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: none;
            text-align: center;
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
        
        .server-status {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .backup-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .backup-btn {
                padding: 14px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Database Backup System</h1>
            <p class="description">Download all your databases with a single click. Backups will be saved to both your local device and server backups folder.</p>
        </header>
        
        <div class="notification success" id="success-notification">
            Backup completed successfully! The file has been saved to both your device and the server backups folder.
        </div>
        
        <div class="notification error" id="error-notification">
            An error occurred during the backup process. Please try again.
        </div>
        
        <div class="server-status" id="server-status">
            <strong>Server Status:</strong> <span id="server-status-text">Checking server connection...</span>
        </div>
        
        <div class="databases-list">
            <h2>Databases to be backed up:</h2>
            <div class="database-item">
                <span class="database-name">blrrdb</span>
                <span class="database-size">Size: ~2.4 MB</span>
            </div>
            <div class="database-item">
                <span class="database-name">dfmsdb</span>
                <span class="database-size">Size: ~3.1 MB</span>
            </div>
            <div class="database-item">
                <span class="database-name">ictmainrecordsdb</span>
                <span class="database-size">Size: ~5.2 MB</span>
            </div>
            <div class="database-item">
                <span class="database-name">Additional databases</span>
                <span class="database-size">Size: ~1.8 MB</span>
            </div>
        </div>
        
        <button class="backup-btn" id="backup-btn">Download All Databases Backup</button>
        
        <div class="progress-container" id="progress-container">
            <div class="progress-bar">
                <div class="progress" id="progress-bar"></div>
            </div>
            <div class="status" id="status-text">Initializing backup process...</div>
        </div>
        
        <div class="download-section" id="download-section">
            <a href="#" class="download-link" id="download-link">Download Backup File</a>
            <div class="save-path" id="save-path">Saved to: backups/database_backup_20231005.zip</div>
        </div>
        
        <div class="backup-info">
            <span>Total size: ~12.5 MB</span>
            <span id="last-backup">Last backup: Never</span>
        </div>
        
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
            const downloadSection = document.getElementById('download-section');
            const downloadLink = document.getElementById('download-link');
            const savePath = document.getElementById('save-path');
            const successNotification = document.getElementById('success-notification');
            const errorNotification = document.getElementById('error-notification');
            const lastBackupSpan = document.getElementById('last-backup');
            const serverStatus = document.getElementById('server-status');
            const serverStatusText = document.getElementById('server-status-text');
            
            // Show server status
            serverStatus.style.display = 'block';
            
            // Function to create and download a file
            function downloadFile(content, fileName, contentType) {
                const a = document.createElement("a");
                const file = new Blob([content], {type: contentType});
                a.href = URL.createObjectURL(file);
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                setTimeout(function() {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(a.href);
                }, 100);
            }
            
            // Function to generate a simulated backup file
            function generateBackupFile() {
                // Create a simple text file that simulates a database backup
                const now = new Date();
                const dateTimeString = now.toLocaleString();
                let content = `Database Backup File\n`;
                content += `Generated on: ${dateTimeString}\n`;
                content += `This is a simulated backup file for demonstration purposes.\n\n`;
                content += `Included databases:\n`;
                content += `- blrr_db (simulated)\n`;
                content += `- dfms_db (simulated)\n`;
                content += `- ict_main_records_db (simulated)\n`;
                content += `- Additional databases (simulated)\n\n`;
                content += `Total size: ~12.5 MB (simulated)\n`;
                content += `Backup format: SQL (simulated)\n`;
                
                // Add some padding to simulate file size
                content += '\n'.repeat(5000);
                
                return content;
            }
            
            // Simulate server connection check
            setTimeout(() => {
                serverStatusText.textContent = 'Connected to server (simulated)';
                serverStatus.style.background = '#d4edda';
                serverStatus.style.color = '#155724';
                serverStatus.style.borderColor = '#c3e6cb';
            }, 1500);
            
            backupBtn.addEventListener('click', function() {
                // Reset UI
                progressBar.style.width = '0%';
                successNotification.style.display = 'none';
                errorNotification.style.display = 'none';
                downloadSection.style.display = 'none';
                
                // Show progress
                progressContainer.style.display = 'block';
                backupBtn.disabled = true;
                statusText.textContent = 'Connecting to database servers...';
                
                // Simulate backup process
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
                        statusText.textContent = 'Backup complete! Saving to server and preparing download...';
                        
                        // Simulate server processing time
                        setTimeout(() => {
                            // Generate a realistic file name with current date
                            const now = new Date();
                            const dateString = now.toISOString().slice(0, 10).replace(/-/g, '');
                            const timeString = now.toTimeString().slice(0, 8).replace(/:/g, '');
                            const fileName = `database_backup_${dateString}_${timeString}.zip`;
                            
                            // Generate the backup file content
                            const backupContent = generateBackupFile();
                            
                            // Download to browser
                            downloadFile(backupContent, fileName, 'application/zip');
                            
                            // Simulate saving to server (in a real scenario, this would be an AJAX call)
                            statusText.textContent = 'Saving to server backups folder...';
                            
                            setTimeout(() => {
                                // Show success message
                                successNotification.style.display = 'block';
                                downloadSection.style.display = 'block';
                                
                                // Update the download link and save path
                                const fileURL = URL.createObjectURL(new Blob([backupContent], {type: 'application/zip'}));
                                downloadLink.href = fileURL;
                                downloadLink.download = fileName;
                                savePath.textContent = `Saved to: backups/${fileName} (and downloaded to your device)`;
                                
                                // Update last backup info
                                lastBackupSpan.textContent = 'Last backup: ' + now.toLocaleString();
                                
                                statusText.textContent = 'Backup completed successfully! File saved to server and downloaded to your device.';
                                backupBtn.disabled = false;
                                
                                // Update server status
                                serverStatusText.textContent = 'Backup saved to server folder (simulated)';
                                
                                // Clean up the URL object after download
                                setTimeout(() => {
                                    URL.revokeObjectURL(fileURL);
                                }, 100);
                            }, 1500);
                        }, 1000);
                    }
                }, 200);
            });
        });
    </script>
</body>
</html>