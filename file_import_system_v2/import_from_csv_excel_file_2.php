<?php
include 'db_connection.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Default values from form or set defaults
$committee_name = isset($_POST['committe_name']) ? $_POST['committe_name'] : 'Com-1';
$default_viva = isset($_POST['default_viva']) ? floatval($_POST['default_viva']) : 20;
$default_written = isset($_POST['default_written']) ? floatval($_POST['default_written']) : 0;

if (isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $original_filename = $_FILES['file']['name'];
    
    // Update default values from form submission
    $committee_name = $_POST['committe_name'] ?? 'Com-1';
    $default_viva = floatval($_POST['default_viva'] ?? 20);
    $default_written = floatval($_POST['default_written'] ?? 0);

    // Detect file type
    if ($ext == 'csv') {
        $reader = IOFactory::createReader('Csv');
        $reader->setDelimiter(",");
        $reader->setEnclosure('"');
        $file_type_icon = '📄';
        $file_type_text = 'CSV File';
    } else {
        $reader = IOFactory::createReader('Xlsx');
        $file_type_icon = '📊';
        $file_type_text = strtoupper($ext) . ' File';
    }

    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    $header = array_shift($rows);

    $totalRows = count($rows);
    $batchSize = 50;
    $failedRows = [];
    $bulkValues = [];
    $successCount = 0;
    $duplicateCount = 0;
    $emptyRows = 0;

    // Progress container
    echo '<div class="progress-container">';
    echo '<div class="progress-header">';
    echo "<h3>📤 Importing File: <span class='filename'>$original_filename</span></h3>";
    echo "<p>$file_type_icon $file_type_text | 📊 Total Rows: $totalRows</p>";
    echo '</div>';

    foreach ($rows as $index => $row) {
        // Skip empty rows
        if (count(array_filter($row)) == 0) {
            $emptyRows++;
            continue;
        }

        // Map column
        $get = function($colName) use ($header, $row) {
            $i = array_search($colName, $header);
            return $i !== false ? trim($row[$i]) : NULL;
        };

        $roll_no       = $get('roll_no');
        $post_name     = $get('post_name');
        $name          = $get('name');
        $father        = $get('father');
        $mother        = $get('mother');
        $dob_raw       = $get('dob');
        $gender        = $get('gender');
        $religion      = $get('religion');
        $quota         = $get('quota');
        $home_district = $get('home_district');
        $ssc_result    = $get('ssc_result');
        $hsc_result    = $get('hsc_result');
        $gra_result    = $get('gra_result');
        $mas_result    = $get('mas_result');

        // Convert DOB to MySQL format
        $dob = null;
        if (!empty($dob_raw)) {
            if (is_numeric($dob_raw)) {
                $dob = date('Y-m-d', ($dob_raw - 25569) * 86400);
            } else {
                $t = strtotime($dob_raw);
                if ($t !== false) $dob = date('Y-m-d', $t);
            }
        }

        // Skip duplicate roll_no
        $check = $conn->prepare("SELECT 1 FROM candidates_tbl_new WHERE roll_no=?");
        $check->bind_param("s", $roll_no);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            $duplicateCount++;
            continue;
        }
        $check->close();

        // Use form values
        $writtenMarks = $default_written;
        $vivaMarks = $default_viva;
        $committee = $committee_name;

        // Prepare bulk insert values for candidates_tbl_new
        $bulkValues[] = "(
            '{$conn->real_escape_string($roll_no)}',
            '{$conn->real_escape_string($post_name)}',
            '{$conn->real_escape_string($name)}',
            '{$conn->real_escape_string($father)}',
            '{$conn->real_escape_string($mother)}',
            '{$dob}',
            '{$conn->real_escape_string($gender)}',
            '{$conn->real_escape_string($religion)}',
            '{$conn->real_escape_string($quota)}',
            '{$conn->real_escape_string($home_district)}',
            '{$conn->real_escape_string($ssc_result)}',
            '{$conn->real_escape_string($hsc_result)}',
            '{$conn->real_escape_string($gra_result)}',
            '{$conn->real_escape_string($mas_result)}',
            {$writtenMarks},
            {$vivaMarks},
            '{$committee}',
            NOW(),
            NOW()
        )";

        // Insert into viva_applicants (single row)
        $dobIndex = array_search('dob', $header);
        if ($dobIndex !== false) $row[$dobIndex] = $dob;
        $vivaValues = array_map(fn($v) => "'".$conn->real_escape_string($v)."'", $row);
        $sqlViva = "INSERT INTO viva_applicants (`".implode("`,`",$header)."`) VALUES (".implode(",",$vivaValues).")";
        if (!$conn->query($sqlViva)) {
            $failedRows[] = $row;
        } else {
            $successCount++;
        }

        // Bulk insert every $batchSize
        if (count($bulkValues) >= $batchSize) {
            $sqlBulk = "INSERT INTO candidates_tbl_new (
                roll_no, post_name, name, father, mother, dob, gender, religion,
                quota, home_district, ssc_result, hsc_result, gra_result, mas_result,
                written_marks, viva_marks, committe_name, created_at, updated_at
            ) VALUES ".implode(',', $bulkValues);
            $conn->query($sqlBulk);
            $bulkValues = [];
        }

        // Update progress bar
        $progress = intval((($index+1)/$totalRows)*100);
        echo "<script>updateProgress($progress);</script>";
        flush();
        ob_flush();
    }

    // Insert remaining bulk rows
    if (count($bulkValues) > 0) {
        $sqlBulk = "INSERT INTO candidates_tbl_new (
            roll_no, post_name, name, father, mother, dob, gender, religion,
            quota, home_district, ssc_result, hsc_result, gra_result, mas_result,
            written_marks, viva_marks, committe_name, created_at, updated_at
        ) VALUES ".implode(',', $bulkValues);
        $conn->query($sqlBulk);
    }

    // Show import summary
    echo '<div class="import-summary">';
    echo '<h4>📊 Import Summary</h4>';
    echo "<div class='summary-grid'>";
    echo "<div class='summary-item success'><span class='count'>$successCount</span><span class='label'>Successful</span></div>";
    echo "<div class='summary-item duplicate'><span class='count'>$duplicateCount</span><span class='label'>Duplicates Skipped</span></div>";
    echo "<div class='summary-item empty'><span class='count'>$emptyRows</span><span class='label'>Empty Rows</span></div>";
    echo "<div class='summary-item failed'><span class='count'>".count($failedRows)."</span><span class='label'>Failed</span></div>";
    echo "</div>";
    
    if (!empty($failedRows)) {
        echo "<div class='failed-rows'><p>❌ Failed to insert ".count($failedRows)." rows into viva_applicants.</p></div>";
    }
    
    echo "<div class='default-values-summary'>";
    echo "<h5>⚙️ Applied Default Values:</h5>";
    echo "<ul>";
    echo "<li><strong>Committee Name:</strong> $committee_name</li>";
    echo "<li><strong>Default Viva Marks:</strong> $default_viva</li>";
    echo "<li><strong>Default Written Marks:</strong> $default_written</li>";
    echo "</ul>";
    echo "</div>";
    echo '</div>';

    echo "<script>
        setTimeout(function() {
            document.querySelector('.import-complete').style.display = 'block';
        }, 500);
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel/CSV Import System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .content {
            padding: 40px;
        }
        
        .upload-box {
            border: 3px dashed #667eea;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            background: #f8f9ff;
            transition: all 0.3s;
        }
        
        .upload-box:hover {
            border-color: #764ba2;
            background: #f0f2ff;
        }
        
        .upload-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        
        .file-label:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .default-values {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #667eea;
        }
        
        .default-values h3 {
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #555;
            font-size: 1.1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            outline: none;
        }
        
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.4);
        }
        
        .progress-container {
            margin-top: 40px;
            background: #f8f9ff;
            border-radius: 15px;
            padding: 30px;
            display: none;
        }
        
        .progress-container.active {
            display: block;
        }
        
        .progress-header {
            margin-bottom: 20px;
        }
        
        .filename {
            color: #667eea;
            font-weight: 600;
        }
        
        .progress-bar-container {
            height: 25px;
            background: #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50 0%, #2E7D32 100%);
            width: 0%;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .import-summary {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border: 2px solid #e0e0e0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .summary-item {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
        }
        
        .summary-item.success { background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); }
        .summary-item.duplicate { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); }
        .summary-item.empty { background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%); }
        .summary-item.failed { background: linear-gradient(135deg, #F44336 0%, #C62828 100%); }
        
        .summary-item .count {
            display: block;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .import-complete {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-top: 30px;
            display: none;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
            }
            
            .content {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-import"></i> Excel/CSV Import System</h1>
            <p>Import candidate data with custom default values</p>
            <a href="viva_candidates_list.php" class="btn btn-light"><i class="fas fa-eye me-2"></i>View</a>
        </div>
        
        <div class="content">
            <form method="post" enctype="multipart/form-data">
                <div class="upload-box">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h3>Upload Excel or CSV File</h3>
                    <p>Supported formats: .csv, .xlsx, .xls</p>
                    
                    <input type="file" name="file" id="file" class="file-input" accept=".csv,.xlsx,.xls" required>
                    <label for="file" class="file-label">
                        <i class="fas fa-folder-open"></i> Choose File
                    </label>
                    <div id="file-name">No file chosen</div>
                </div>
                
                <div class="default-values">
                    <h3><i class="fas fa-cog"></i> Default Values Configuration</h3>
                    
                    <div class="form-group">
                        <label for="committe_name">👥 Committee Name</label>
                        <input type="text" name="committe_name" id="committe_name" value="<?php echo htmlspecialchars($committee_name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="default_viva">🎤 Default Viva Marks</label>
                        <input type="number" name="default_viva" id="default_viva" value="<?php echo $default_viva; ?>" step="0.01" min="0" max="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="default_written">✍️ Default Written Marks</label>
                        <input type="number" name="default_written" id="default_written" value="<?php echo $default_written; ?>" step="0.01" min="0" max="100">
                    </div>
                </div>
                
                <button type="submit" name="import" class="submit-btn">
                    <i class="fas fa-upload"></i> Start Import Process
                </button>
            </form>
            
            <div id="progressContainer" class="progress-container">
                <div class="progress-header">
                    <h3>📤 Importing File: <span id="importFilename" class="filename"></span></h3>
                    <p id="fileInfo"></p>
                </div>
                
                <div class="progress-bar-container">
                    <div id="progressBar" class="progress-bar">0%</div>
                </div>
                <p id="progressText">Processing...</p>
                
                <div id="importSummary" class="import-summary" style="display: none;">
                    <!-- Summary will be inserted here by JavaScript -->
                </div>
            </div>
            
            <div id="importComplete" class="import-complete">
                <h3><i class="fas fa-check-circle"></i> Import Completed Successfully!</h3>
                <p>Your data has been imported with the configured default values.</p>
            </div>
        </div>
    </div>

    <script>
        // File name display
        document.getElementById('file').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('importFilename').textContent = fileName;
            
            // Show file info
            var file = e.target.files[0];
            if (file) {
                var fileSize = (file.size / 1024 / 1024).toFixed(2);
                var fileType = file.name.split('.').pop().toUpperCase();
                var icon = fileType === 'CSV' ? '📄' : '📊';
                document.getElementById('fileInfo').innerHTML = 
                    icon + ' ' + fileType + ' File | 📏 Size: ' + fileSize + ' MB';
            }
        });
        
        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            var file = document.getElementById('file').files[0];
            if (!file) {
                alert('Please select a file first!');
                e.preventDefault();
                return;
            }
            
            // Show progress container
            document.getElementById('progressContainer').classList.add('active');
            document.getElementById('importComplete').style.display = 'none';
            
            // Reset progress bar
            updateProgress(0);
        });
        
        function updateProgress(percent) {
            var bar = document.getElementById('progressBar');
            bar.style.width = percent + '%';
            bar.innerHTML = percent + '%';
            
            var text = document.getElementById('progressText');
            if (percent < 100) {
                text.innerHTML = `Processing... ${percent}% complete`;
            } else {
                text.innerHTML = 'Processing completed! Finalizing...';
            }
        }
    </script>
</body>
</html>