<?php
// import_simple.php
require_once 'db_connection.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... file validation code ...
    
    try {
        $spreadsheet = IOFactory::load($file_tmp_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        
        $sql = "INSERT INTO candidates_tbl_new (
                    roll_no, post_name, name, father, mother, dob, gender, religion, 
                    quota, home_district, ssc_result, hsc_result, gra_result, mas_result, 
                    written_marks, viva_marks, committe_name, created_at, updated_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        $success_count = 0;
        
        for ($row = 2; $row <= $highestRow; $row++) {
            // Read by column position (A=1, B=2, C=3, etc.)
            $roll_no = trim($worksheet->getCell('A' . $row)->getValue());          // A: roll_no
            $post_code = trim($worksheet->getCell('B' . $row)->getValue());        // B: post_code
            $post_name = trim($worksheet->getCell('C' . $row)->getValue());        // C: post_name
            $name = trim($worksheet->getCell('D' . $row)->getValue());             // D: name
            $father = trim($worksheet->getCell('E' . $row)->getValue());           // E: father
            $mother = trim($worksheet->getCell('F' . $row)->getValue());           // F: mother
            $dob = convertExcelDate($worksheet->getCell('G' . $row)->getValue());  // G: dob
            $gender = trim($worksheet->getCell('I' . $row)->getValue());           // I: gender
            $religion = trim($worksheet->getCell('M' . $row)->getValue());         // M: religion
            $quota = trim($worksheet->getCell('N' . $row)->getValue());            // N: quota
            $home_district = trim($worksheet->getCell('P' . $row)->getValue());    // P: home_district
            
            // Get academic results from their columns
            $ssc_result = getNumericResult($worksheet->getCell('AQ' . $row)->getValue());  // AQ: ssc_result
            $hsc_result = getNumericResult($worksheet->getCell('AX' . $row)->getValue());  // AX: hsc_result
            $gra_result = getNumericResult($worksheet->getCell('BM' . $row)->getValue());  // BM: gra_result
            $mas_result = getNumericResult($worksheet->getCell('BQ' . $row)->getValue());  // BQ: mas_result
            
            if (!empty($roll_no) && !empty($name)) {
                $stmt->execute([
                    $roll_no, $post_name, $name, $father, $mother, $dob, $gender, $religion,
                    $quota, $home_district, $ssc_result, $hsc_result, $gra_result, $mas_result,
                    $default_written, $default_viva, $committe_name
                ]);
                $success_count++;
            }
        }
        
        echo "Imported $success_count records successfully!";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>