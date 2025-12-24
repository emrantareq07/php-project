<?php
// install_excel.php
// This file helps install PhpSpreadsheet if not already installed
?>
<!DOCTYPE html>
<html>
<head>
    <title>Install Excel Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .code {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            font-family: monospace;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .success { color: green; }
        .error { color: red; }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Install PHP Excel Library</h1>
        
        <?php
        // Check if composer is available
        $composer_available = false;
        $php_version_ok = version_compare(PHP_VERSION, '7.2.0') >= 0;
        
        // Check for composer.json
        if (file_exists('composer.json')) {
            echo "<p class='success'>✓ composer.json found</p>";
        } else {
            echo "<p class='error'>✗ composer.json not found</p>";
        }
        
        // Check PHP version
        if ($php_version_ok) {
            echo "<p class='success'>✓ PHP version " . PHP_VERSION . " is OK</p>";
        } else {
            echo "<p class='error'>✗ PHP version must be 7.2.0 or higher (current: " . PHP_VERSION . ")</p>";
        }
        
        // Check if PhpSpreadsheet is already installed
        $phpspreadsheet_installed = class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet');
        $phpexcel_installed = class_exists('PHPExcel');
        
        if ($phpspreadsheet_installed) {
            echo "<p class='success'>✓ PhpSpreadsheet is already installed!</p>";
        } elseif ($phpexcel_installed) {
            echo "<p class='success'>✓ PHPExcel is already installed!</p>";
        }
        ?>
        
        <h2>Installation Options:</h2>
        
        <h3>Option 1: Using Composer (Recommended)</h3>
        <p>Run this command in your terminal:</p>
        <div class="code">
            composer require phpoffice/phpspreadsheet
        </div>
        
        <h3>Option 2: Manual Installation</h3>
        <p>Download and extract PhpSpreadsheet:</p>
        <div class="code">
            // Download from: https://github.com/PHPOffice/PhpSpreadsheet/releases<br>
            // Extract to your project folder<br>
            // Include autoload.php
        </div>
        
        <h3>Option 3: Install PHPExcel (Legacy)</h3>
        <div class="code">
            // Download from: https://github.com/PHPOffice/PHPExcel<br>
            // Or use Composer:<br>
            composer require phpoffice/phpexcel
        </div>
        
        <h3>Quick Test</h3>
        <div class="code">
            &lt;?php<br>
            require 'vendor/autoload.php';<br>
            <br>
            use PhpOffice\PhpSpreadsheet\Spreadsheet;<br>
            use PhpOffice\PhpSpreadsheet\Writer\Xlsx;<br>
            <br>
            $spreadsheet = new Spreadsheet();<br>
            $sheet = $spreadsheet->getActiveSheet();<br>
            $sheet->setCellValue('A1', 'Hello World!');<br>
            <br>
            $writer = new Xlsx($spreadsheet);<br>
            $writer->save('hello_world.xlsx');<br>
            echo "Excel file created successfully!";<br>
            ?&gt;
        </div>
        
        <a href="excel_import.php" class="btn">← Back to Excel Import</a>
        <a href="csv_import.php" class="btn" style="background: #6c757d;">Use CSV Import Instead</a>
    </div>
</body>
</html>