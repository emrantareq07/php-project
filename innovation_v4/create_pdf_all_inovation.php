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

 //            'margin_left' => 20,
	// 'margin_right' => 20,
	// 'margin_top' => 10,
	// 'margin_bottom' => 10
        ]);
//$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);	

// $mpdf = new \Mpdf\Mpdf([   '',    // mode - default ''
                // '',    // format - A4, for example, default ''
                // 0,     // font size - default 0
                // '',    // default font family
                // 15,    // margin_left
                // 15,    // margin right
                // 58,     // margin top
                // 60,    // margin bottom
                // 6,     // margin header
                // 0,     // margin footer
                // 'L' ]);  // L - landscape, P - portrait	

//$mpdf->AddPage('L');


$mpdf->AddPage('L','','','b','off');
//$mpdf->showImageErrors = true;		
// Database Connection 
$conn = new mysqli('localhost', 'root', '', 'innovation_db');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}
// Select data from MySQL database
$select = "SELECT * FROM innovation_tbl";
// $select = "SELECT * FROM `innovation_tbl`";
$result = $conn->query($select);
$data = array();
while($row = $result->fetch_object()){
	$data .= '<tr >'
		  .'<td style="border: 1px solid gray;">'.$row->fiscal_year.'</td>'
		  .'<td style="border: 1px solid gray;  text-align: justify; ">'.$row->title_of_invention.'</td>'
		  .'<td style="border: 1px solid gray">'.$row->inventors_name."<br>".$row->designation."<br>".$row->inventors_emp_id."<br>".$row->proposed_workplace.'</td>'
		  .'<td style="border: 1px solid gray; text-align: justify;">'.$row->des_of_invention.'</td>'
		  .'<td style="border: 1px solid gray;text-align: center;">'.$row->imple_status.'</td>'
	  		.'<td style="border: 1px solid gray; text-align: center;">'.$row->replicate_eligibility.'</td>'
		  .'<td style="border: 1px solid gray;text-align: center;">'.$row->feedback.'</td>'
		   
		  .'<td style="border: 1px solid gray;text-align: center;">'.$row->remarks.'</td> 
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


$pdfcontent = '<p align=left style="padding-left:100px"><img src="./images/BCIC_logo.jpg " width="90"></p>
<h1 style="text-align: center; text-transform:uppercase;padding-top: -140px;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন </h1>
  
  <h2 style="text-align: center; text-transform:uppercase; padding-top: -20px; padding-right:-30px;">
  Bangladesh Chemical Industries Corporation</h2>




<h3 style="text-align: center; padding-top: -20px;" >বিসিআইসি ভবন, ৩০-৩১ দিলকুশা বা/এ, ঢাকা-১০০০। </h3>
<h2 style="text-align: center; padding-top: -10px;color:#3d0a61;">বাস্তবায়নে: আইসিটি বিভাগ, বিসিআইসি। </h2>
<h2 class="text-danger text-center" style="text-align: center; color:#09143b;text-transform:uppercase; text-style:underline;font-weight: bold; padding-top: -20px;">
		বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ</h2>
<table autosize="1" border="1" cellpadding="3" cellspacing="2" style="border-spacing: 2px;  border-collapse: collapse; padding-top: -10px;">
		<tr>
		 <thead>
		 
		 <th style="border: 1px solid gray; background-color:#096b20;color:white">অর্থবছর</th>
	    <th style="border: 1px solid gray; background-color:#096b20;color:white ">সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
		<th style="border: 1px solid gray; background-color:#096b20;color:white">উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
	    
		<th style="border: 1px solid gray; background-color:#096b20;color:white">সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা</th>
	    <th style="border: 1px solid gray; background-color:#096b20;color:white">বাস্তাবায়নের অবস্থা</th>
		<th style="border: 1px solid gray; background-color:#096b20;color:white">রেপ্লিকেট যোগ্যতা</th>
		<th style="border: 1px solid gray; background-color:#096b20;color:white"> ফলাফল </th>
		
		<th style="border: 1px solid gray; background-color:#096b20;color:white">মন্তব্য</th>
		
		  </thead>
  		</tr>
		'.$data.'
		</table>';
//call watermark content and image


$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; 

//call watermark content and image
$mpdf->SetWatermarkText('BCIC');
$mpdf->showWatermarkText = true;
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->watermarkImageAlpha = 0.5;
$mpdf->showImageErrors = true;

$mpdf->SetHTMLFooter('
<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
    <tr>
        <td width="33%">{DATE d-m-Y}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">Design & Developed By: Md. Tareq Emran, Programmer.</td>
    </tr>
</table>');  // Note that the second parameter is optional : default = 'O' for ODD
$mpdf->SetHTMLFooter('
<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
    <tr>
        <td width="33%"><span style="font-weight: bold; font-style: italic;">Design & Developed By: Md. Tareq Emran</span></td>
        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right; ">{DATE d-m-Y}</td>
    </tr>
</table>', 'E');
$mpdf->WriteHTML($pdfcontent);
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