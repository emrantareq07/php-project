<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
 
 <style>
 
 html, body, div {
      font-family: bangla;
    }
	
 .textLayer > table {
   font-family: bangla;
   color: transparent;
   white-space: pre;
   cursor: text;
   transform-origin: 0% 0%;
 }
 
 </style>
 
</head>
<body>

<?php


// Include mpdf library file
require_once __DIR__ . '/vendor/autoload.php';
//$mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf([
            'default_font' => 'bangla',
            'mode' => 'utf-8'
        ]);
// Database Connection 
$conn = new mysqli('localhost', 'root', '', 'ajaxcrud_db');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}
// Select data from MySQL database
$select = "SELECT * FROM `innovation`";
$result = $conn->query($select);
$data = array();
while($row = $result->fetch_object()){
	$data .= '<tr >'
		  .'<td style="border: 1px solid gray;">'.$row->fiscal_year.'</td>'
		  .'<td style="border: 1px solid gray;  text-align: justify; ">'.$row->title_of_invention.'</td>'
		  .'<td style="border: 1px solid gray">'.$row->inventors_name."<br>".$row->inventors_designation."<br>".$row->inventors_emp_id."<br>".$row->proposed_workplace.'</td>'
		  .'<td style="border: 1px solid gray; text-align: justify;">'.$row->des_of_invention.'</td>'
		  .'<td style="border: 1px solid gray">'.$row->imple_status.'</td>'
		  .'<td style="border: 1px solid gray">'.$row->replicate_eligibility.'</td>  
		  </tr>';
}

// Take PDF contents in a variable
$pdfcontent = '<h1></h1>
		<h2 style="text-align: center; text-transform:uppercase;">Innovation Database</h2>
		<table autosize="1" border="1" cellpadding="2" cellspacing="2" style="border-spacing: 2px;  border-collapse: collapse;">
		<tr>
		 <thead>
		 <th style="border: 1px solid gray; background-color:#949253">অর্থবছর</th>
    <th style="border: 1px solid gray; background-color:#949253">উদ্ভাবনের শিরোনাম</th>
	<th style="border: 1px solid gray; background-color:#949253">উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
    
	<th style="border: 1px solid gray; background-color:#949253">উদ্ভাবনের বর্নণা</th>
    <th style="border: 1px solid gray; background-color:#949253">বাস্তাবয়নের অবস্থা</th>
	<th style="border: 1px solid gray; background-color:#949253">রেপ্লিকেট যোগ্যতা</th>
		
		  </thead>
  
		
		
   
		</tr>
		'.$data.'
		</table>';

$mpdf->WriteHTML($pdfcontent);

$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; 

//call watermark content and image
$mpdf->SetWatermarkText('BCIC');
$mpdf->showWatermarkText = true;
$mpdf->watermarkTextAlpha = 0.1;

//output in browser
$mpdf->Output();		
?>

<?php 
/* include('database.php');


$database = new Database();
$result = $database->runQuery("SELECT * FROM innovation ORDER BY id desc");
$header = $database->runQuery("SELECT UCASE(`COLUMN_NAME`) 
FROM innovation ORDER BY id desc)");

require('fpdf/fpdf.php');;
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

foreach($header as $heading) {
	foreach($heading as $column_heading)
		$pdf->Cell(95,12,$column_heading,1);
}
foreach($result as $row) {
	$pdf->Ln();
	foreach($row as $column)
		$pdf->Cell(95,12,$column,1);
}
$pdf->Output(); */



?>

</body>
</html>