<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
 
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
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
 table,th{
	 
 }
 
 </style>
 
</head>
<body>
<div class="container">
<?php


// Include mpdf library file
require_once __DIR__ . '/vendor/autoload.php';
//$mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf([
            'default_font' => 'bangla',
            'mode' => 'utf-8'
        ]);
//$mpdf->showImageErrors = true;		
// Database Connection 
$conn = new mysqli('localhost', 'root', '', 'innovation_db');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}
// Select data from MySQL database
$select = "SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation WHERE fiscal_year='২০১৮-১৯'
UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation WHERE fiscal_year='২০১৯-২০'";
$result = $conn->query($select);
$data = array();
while($row = $result->fetch_object()){
	$data .= '<tr >'
		  .'<td style="border: 1px solid gray;" align=center>'.$row->fiscal_year.'</td>'
		  .'<td style="border: 1px solid gray;text-align:justify;" align=center>'.$row->no_of_award.'</td>'
		  
		  .'<td style="border: 1px solid gray; text-align: justify;" align=center>'.$row->no_of_officer.'</td>
		   
		  </tr>';
		  
		  }
		
		  


// Take PDF contents in a variable
//$mpdf->Image('./images/BCIC_logo.jpg', 0, 0, 210, 297, 'jpg', '', true, false);
// First ensure that you are on an Even page


// Then set the headers for the next page before you add the page
$mpdf->SetHTMLHeader('
<div style="text-align: right; border-bottom: 0px solid #000000; font-weight: bold; font-size: 10pt;">
    
</div>','O');
$mpdf->SetHTMLHeader('
<div style="border-bottom: 0px solid #000000; font-weight: bold; font-size: 10pt;">
    
</div>','E');

$pdfcontent = '<p align=left style="padding-left:-30px"><img src="./images/BCIC_logo.jpg " width="90"></p>
<h1 style="text-align: center; text-transform:uppercase;padding-top: -110px;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন </h1>
  
  <h2 style="text-align: center; text-transform:uppercase; padding-top: -20px; padding-right:-30px;">
  Bangladesh Chemical Industries Corporation</h2>

<h3 style="text-align: center; padding-top: -20px;" >বিসিআইসি ভবন, ৩০-৩১ দিলকুশা বা/এ, ঢাকা-১০০০। </h3>
<h2 style="text-align: center;">বাস্তবায়নে: আইসিটি বিভাগ, বিসিআইসি। </h2>
		<h2 class="text-info" style="text-align: center; text-transform:uppercase; text-style:underline;font-weight: bold;">Innovation Database</h2>
		<table width="100%" autosize="1" border="1" cellpadding="3" cellspacing="2" style="border-spacing: 2px;  border-collapse: collapse;">
		<tr>
		 <thead>
		 <th class="text-center pb-4">অর্থবছর</th>
				  <th class="text-center pb-4">পুরুস্কার সংখ্যা</th>
				
				 <th class="text-center pb-4"> কর্মকর্তার সংখ্যা</th>
		
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
$mpdf->showImageErrors = true;
$mpdf->SetHTMLFooter('
<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
    <tr>
        <td width="33%">{DATE j-m-Y}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">My document</td>
    </tr>
</table>');  // Note that the second parameter is optional : default = 'O' for ODD
$mpdf->SetHTMLFooter('
<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
    <tr>
        <td width="33%"><span style="font-weight: bold; font-style: italic;">My document</span></td>
        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right; ">{DATE j-m-Y}</td>
    </tr>
</table>', 'E');
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
</div>
</body>
</html>