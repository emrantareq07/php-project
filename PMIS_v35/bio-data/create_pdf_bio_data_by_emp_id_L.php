 <?php  
 ini_set('memory_limit', '1G');
// create_pdf_bio_data_by_emp_id.php
session_start();
$_SESSION['emp_id']=$_GET["emp_id"];
$_SESSION['emp_id'];

include('../includes/header.php');

 function fetch_data_admin(){
 	$emp_id=$_SESSION['emp_id'];
 
      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "pmis_db");  
	  
      //$sql = "SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'";
	  $sql="SELECT DISTINCT * from basic_info u INNER JOIN job_desc j ON u.emp_id=j.emp_id  where u.emp_id='$emp_id' LIMIT 1 ";
	  //$sql="SELECT DISTINCT * from users u INNER JOIN job_desc j ON u.emp_id=j.emp_id inner join prof_membership p on j.emp_id=p.emp_id where u.emp_id='$emp_id' LIMIT 1 ";
      //$sql="SELECT * from job_desc where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 	
			 
	while($row = mysqli_fetch_assoc($result)) {       
      
      $output .= '
	        <table border="1" cellspacing="0" cellpadding="3" align="center" style=" border: 1px solid white; border-color: #96D4D4;border-collapse: collapse; border-style: dotted;" >
	  <thead> 
     <tr style="background-color:#ffffff;color:black;">       
                 
                <th colspan="7" style="text-align:left;"><b>General Information</b></th>  
           </tr>
           <tr style="background-color:#c9c2c1;color:black;">  
                <th ><b>Seniority No.</b></th>
                <th ><b>Name</b></th>  
                <th ><b>Fathers Name</b></th>  
                <th ><b>Mothers Name</b></th>  
                <th ><b>Home District</b></th>
				        <th ><b>Permanent Address</b></th>  
                <th ><b>Present Address</b></th>  
                
	
           </tr></thead>
		   <tbody>
           <tr> 
                <meta charset="utf-8"/>
	              <td>'.$row["seniority_no"].'</td>
                <td>'.$row["name"].'</td>  
                <td>'.$row["fathersName"].'</td>  
                <td>'.$row["mothersName"].'</td>  
                <td>'.$row["home_dist"].'</td> 
	              <td>'.$row["present_add"].'</td>  
                <td>'.$row["permanent_add"].'</td> 

           </tr>
				 </tbody>
			</table> 
					 
				<h6></h6>
					 
				<table border="1" cellspacing="0" cellpadding="3" align="center" style="border-color:gray;" >
	  				<thead>
					   <tr style="background-color:#c9c2c1;color:black;">   
							 
							<th ><b>Date of Birth</b></th>  
							<th ><b>Date of Joining</b></th>  
							<th ><b>Date of Last Promotion</b></th> 
							<th ><b>PRL Date</b></th>
							<th ><b>Maritial Status</b></th>  
							<th ><b>Religon</b></th>
							<th ><b>Gender</b></th>
							<th ><b>Appointment Type</b></th>
							<th ><b>Cadre</b></th>
							<th ><b>Sub Cadre</b></th>													
										
					   </tr>
					   </thead>
					   
					 <tbody>
		         <tr> 
                    <meta charset="utf-8"/>
			  
                    <td>'.$row["dob"].'</td>  
                    <td>'.$row["doj"].'</td>  
                    
                    <td>'.$row["dolp"].'</td>
			              <td>'.$row["prl"].'</td>						  
      						  <td>'.$row["maritial_status"].'</td> 
      						  <td>'.$row["religious"].'</td>  
                    <td>'.$row["gender"].'</td> 
			              <td>'.$row["appoint_type"].'</td>  
                    <td>'.$row["cadre"].'</td>  
                    <td>'.$row["sub_cadre"].'</td>  

	  
               </tr>
					 </tbody>
					 
					 </table>
					 <table border="1" cellspacing="0" cellpadding="3" align="center" style=" border: 1px solid white; border-color: #96D4D4;border-collapse: collapse; border-style: dotted;" >
	  
					 <thead>
					   <tr style="background-color:#c9c2c1;color:black;"> 
					   <th ><b>Grade</b></th>  
							<th ><b>Designation</b></th>
							<th ><b>Basic Pay</b></th>
							<th ><b>Scale</b></th>  
							<th ><b>Next Increment Date</b></th>
							<th ><b>Next Promotion Date</b></th> 
						
							<th ><b>Place of Posting</b></th>  
							<th ><b>Division</b></th>
							<th ><b>Section</b></th>
							<th ><b>Last Promotion Date</b></th>
					   </tr>
				</thead>
				<tbody>
					 <tr>
             <td>'.$row["grade"].'</td> 
						  <td>'.$row["designation"].'</td>  
              <td>'.$row["basic"].'</td> 
						  
						  <td>'.$row["scale"].'</td>  
              <td>'.$row["next_incr_date"].'</td>  
              <td>'.$row["next_promo_date"].'</td>  
              <td>'.$row["place_of_posting"].'</td> 
              <td>'.$row["division"].'</td>  
              <td>'.$row["section"].'</td> 
              <td>'.$row["last_promo_date"].'</td>
					 </tr>
					 
					 </tbody>
					</table>

	  ';  
      }  
      
     

      $output .= '
	  
	  <h6></h6>	

	<table border="1" cellspacing="0" cellpadding="3" align="center"  >
	  <thead> 
           <tr style="background-color:#ffffff;color:black;"> 		   
                 
                <th colspan="5" style="text-align:left;"><b>Professional / Membership Information</b></th>  
           </tr>
			       <tr style="background-color:#c9c2c1;color:black;"> 		   
                 
                <th ><b>Membership No.</b></th>  
                <th ><b>Membership Type</b></th>  
                <th ><b>Name of Institute</b></th>  
                <th ><b>Country</b></th>
				        <th ><b>Validity</b></th>  
                
                
	
            </tr></thead>

           ';
      $sql1="SELECT * from prof_membership where emp_id='$emp_id'";
	  $result1 = mysqli_query($connect, $sql1); 
	//$result_admin =   mysql_query("SELECT * FROM admin_fc ORDER BY id ASC");
	//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'");
		if (mysqli_num_rows($result1) > 0) {
		//if ($result1->num_rows > 0) {	 
	while($row = mysqli_fetch_assoc($result1))  
      { 


           $output .= '
		   <tbody>
					<tr> 
            <meta charset="utf-8"/>

            <td>'.$row["mem_no"].'</td>  
            <td>'.$row["type"].'</td>  
            <td>'.$row["institute"].'</td>  
            <td>'.$row["country"].'</td> 
            <td>'.$row["validity"].'</td>  
                          
				  
          </tr>
				 </tbody>
			 
	   ';  
      }
  }else {
			  $output .= '<tbody><tr> <td colspan="5" style="background-color:#ffffff;color:red;">No Record Found!!!</td>  </tr></tbody> ';
			}
     $output .= '</table>'; 

$output .= '
	  
	 <h6></h6>

	<table border="1" cellspacing="0" cellpadding="3" align="center" style="border-left-width:0.1px;border-right-width:0.1px;border-bottom-width:0.1px;border-right-color:gray; margin-bottom:100px;" >
	  <thead> 
	  <tr style="background-color:#ffffff;color:black;"> 		   
                 
          <th colspan="7" style="text-align:left;"><b>Educational Qualification</b></th>  
             
           </tr>
           <tr style="background-color:#c9c2c1;color:black;font-weight: bold;">  
                 
	        <th>Examination</th>
			   <th>Subject/ Group</th>
			   <th>Institute Name</th>
	        <th>Board</th>
	        <th>CGPA/Division/Class</th>
			   <th>Passing Year</th>
			   <th>Course Duration</th>
            </tr>
     </thead>

           ';
      $sql="SELECT * from edu_info where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 
	//$result_admin =   mysql_query("SELECT * FROM admin_fc ORDER BY id ASC");
	//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'");
	if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result))  
      { 


           $output .= '
		   <tbody>
					<tr> 
              <meta charset="utf-8"/>
              <td>'.$row["ssc_exam"].'</td>  
              <td>'.$row["ssc_group_name"].'</td> 
              <td>'.$row["ssc_inst_name"].'</td>  
              <td>'.$row["ssc_board_or_univ"].'</td> 
              <td>'.$row["ssc_div_or_cgpa"].'</td>  
              <td>'.$row["ssc_passing_year"].'</td> 
              <td></td>

            </tr>
         	<tr> 
              <meta charset="utf-8"/>
              <td>'.$row["hsc_exam"].'</td>  
              <td>'.$row["hsc_group_name"].'</td> 
              <td>'.$row["hsc_inst_name"].'</td>  
              <td>'.$row["hsc_board_or_univ"].'</td> 
              <td>'.$row["hsc_div_or_cgpa"].'</td>  
              <td>'.$row["hsc_passing_year"].'</td> 
               <td></td>
				  
             </tr>

                 <tr>
			      		<td>'.$row["honors_exam"].'</td>  
                <td>'.$row["honors_group_name"].'</td> 
                <td>'.$row["honors_inst_name"].'</td>  
                <td>'.$row["honors_board_or_univ"].'</td> 
	              <td>'.$row["honors_div_or_cgpa"].'</td>  
                <td>'.$row["honors_passing_year"].'</td> 
                <td>'.$row["honors_course_duration"].'</td> 
			      </tr>

			      <tr>
		           <td>'.$row["masters"].'</td>  
              <td>'.$row["ms_group_name"].'</td> 
              <td>'.$row["ms_inst_name"].'</td>  
              <td>'.$row["ms_board_or_univ"].'</td> 
             <td>'.$row["ms_div_or_cgpa"].'</td>  
              <td>'.$row["ms_passing_year"].'</td> 
              <td>'.$row["ms_course_duration"].'</td> 
			       
			      </tr>
		</tbody>
			 
	   ';  
      }
      }else {
			  $output .= '<tbody><tr> <td colspan="7" style="background-color:#ffffff;color:red;">No Record Found!!!</td>  </tr></tbody> ';
			}
     $output .= '</table>'; 

      $output .= '
    
     <h6></h6> 

  <table border="1" border-color="gray" cellspacing="0" cellpadding="3" align="center" style="border-color:gray;" >
    <thead> 
     <tr style="background-color:#ffffff;color:black;">        
                 
            <th colspan="6" style="text-align:left;"><b>Training Information</b></th>  
           </tr>
        <tr style="background-color:#c9c2c1;color:black;font-weight: bold;">  
                 
            <th>Training Type</th>
            <th>Title</th>
    
            <th>Institute</th>
            <th>Country</th>
            <th>Period</th>
           <th>Month/ Year</th>
                
                
  
           </tr></thead>

           ';
      $sql="SELECT * from training_info where emp_id='$emp_id'";
    $result = mysqli_query($connect, $sql); 
  //$result_admin =   mysql_query("SELECT * FROM admin_fc ORDER BY id ASC");
  //$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'");
      if (mysqli_num_rows($result) > 0) { 


  while($row = mysqli_fetch_assoc($result))  
      { 


           $output .= '
       <tbody>
          <tr> 
            <meta charset="utf-8"/>
            <td>'.$row["type"].'</td>  
            <td>'.$row["title"].'</td> 
            <td>'.$row["institute"].'</td>  
            <td>'.$row["country"].'</td> 
            <td>'.$row["period"].'</td>  
            <td>'.$row["month_year"].'</td> 
          
           </tr>
         </tbody>
       
     ';  
      }
  }else {
        $output .= '<tbody><tr> <td colspan="6" style="background-color:#ffffff;color:red;">No Record Found!!!</td>  </tr></tbody> ';
      }
     $output .= '</table>';

 $output .= '
	  
	   <h6></h6>

	<table border="1" border-color="gray" cellspacing="0" cellpadding="3" align="center" style="border-color:gray;" >
	  <thead> 
	   <tr style="background-color:#ffffff;color:black;"> 		   
                 
            <th colspan="6" style="text-align:left;"><b>Training Information</b></th>  
           </tr>
        <tr style="background-color:#c9c2c1;color:black;font-weight: bold;">  
                 
  	  			<th>Training Type</th>
          	<th>Title</th>
		
				    <th>Institute</th>
        		<th>Country</th>
         		<th>Period</th>
		 		   <th>Month/ Year</th>
                
                
	
           </tr></thead>

           ';
      $sql="SELECT * from training_info where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 
	//$result_admin =   mysql_query("SELECT * FROM admin_fc ORDER BY id ASC");
	//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'");
			if (mysqli_num_rows($result) > 0) { 


	while($row = mysqli_fetch_assoc($result))  
      { 


           $output .= '
		   <tbody>
					<tr> 
              <meta charset="utf-8"/>
              <td>'.$row["type"].'</td>  
              <td>'.$row["title"].'</td> 
              <td>'.$row["institute"].'</td>  
              <td>'.$row["country"].'</td> 
              <td>'.$row["period"].'</td>  
              <td>'.$row["month_year"].'</td> 

         </tr>
				 </tbody>
			 
	   ';  
      }
  }else {
			  $output .= '<tbody><tr> <td colspan="6" style="background-color:#ffffff;color:red;">No Record Found!!!</td>  </tr></tbody> ';
			}
     $output .= '</table>'; 


      return $output;
       //$result1->free();

	 mysqli_close($connect);

 } //end admin function
 
 
 if(isset($_POST["create_pdf"]))  
 {  
      require_once('../tcpdf/tcpdf.php'); 

      // Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Logo image file path
    const PDF_HEADER_LOGO = '../image/bcic.jpg';
    // Logo width in millimeters
    const PDF_HEADER_LOGO_WIDTH = 20;
        // Watermark text
    const WATERMARK_TEXT = 'BCIC';

    //Page header
    public function Header() {
        // Logo
          // Set header logo
    	        // Display header only on the first page
        if ($this->getPage() === 1) {
        $logo = self::PDF_HEADER_LOGO;
        $logoWidth = self::PDF_HEADER_LOGO_WIDTH;
        // $image_file = K_PATH_IMAGES.'image/bcic.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Image($logo, 20, 15, $logoWidth, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
         // Set header text color to gray
            $this->SetTextColor(128);

                
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'BANGLADESH CHEMICAL INDUSTRIES CORPORATION (BCIC)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
     //   parent::Header();
    }
}

// public function Footer() {
// 	// Set footer font and color
//         $this->SetFont('helvetica', 'I', 8);
//         $this->SetTextColor(128);
//         // $pageNo = $this->getAliasNumPage().'/'.$this->getAliasNbPages();
//         // $this->Cell(0, 10, $pageNo, 0, false, 'C', 0, '', 0, false, 'T', 'M');
//         $this->Cell(0, 10, '[Design & developed by Md. Tareq Emran, Programmer.]  ::-- Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

//         // Set footer watermark text
//         $this->SetAlpha(0.3);
//         $this->SetFont('helvetica', 'B', 80);
//         $this->SetTextColor(200, 200, 200);
        
//         // Calculate diagonal angle
//         $angle = 45;

//         // Get page dimensions
//         $pageWidth = $this->getPageWidth();
//         $pageHeight = $this->getPageHeight();

//         // Calculate watermark position
//         $x = $pageWidth / 2;
//         $y = $pageHeight / 2;

//         // Rotate the coordinate system
//         $this->StartTransform();
//         $this->Rotate($angle, $x, $y);

//         // Output the watermark
//         $this->Cell(0, 0, self::WATERMARK_TEXT, 0, false, 'C', 0, '', 0, false, 'M', 'M');

//         // Restore the coordinate system
//         $this->StopTransform();
//     }


    // Page footer
    // public function Footer() {
    // 	        // Set footer watermark text
    //     $this->SetAlpha(0.3);
    //     // Position at 15 mm from bottom
    //     // $this->SetY(-15);
    //     // Set font
    //     $this->SetFont('helvetica', 'I', 8);
    //     // Page number
    //     // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //     $this->Cell(0, 10, '[Design & developed by Md. Tareq Emran, Programmer.]  ::-- Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //     $this->SetTextColor(200, 200, 200);
    //     $this->RotatedText(35, 150, self::WATERMARK_TEXT, 45);
    // }

 // public function Footer() {
 //        // Set footer font and color
 //        $this->SetFont('helvetica', 'I', 8);
 //        $this->SetTextColor(128);

 //        // Set footer text
 //        // $pageNo = $this->getAliasNumPage().'/'.$this->getAliasNbPages();
 //        // $this->Cell(0, 10, $pageNo, 0, false, 'C', 0, '', 0, false, 'T', 'M');
 //        $this->Cell(0, 10, '[Design & developed by Md. Tareq Emran, Programmer.]  ::-- Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
 //    }


public function Footer() {
        // Set footer font and color
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128);

        // Set footer text
        $currentPage = $this->getPage();
        $totalPages = $this->getNumPages();
        $footerText = '[Design & developed by Md. Tareq Emran, Programmer.]  ::-- Page '.$currentPage.'/'.$totalPages;

        // Write the footer text
        $this->SetY(-15);
        $this->Cell(0, 10, $footerText, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

// public function Watermark() {
//     // Set footer watermark text
//     $this->SetAlpha(0.3);
//     // Set watermark font and color
//     $this->SetFont('helvetica', 'B', 80);
//     $this->SetTextColor(200, 200, 200);

//     // Calculate diagonal angle
//     $angle = 45;

//     // Get page dimensions
//     $pageWidth = $this->getPageWidth();
//     $pageHeight = $this->getPageHeight();

//     // Calculate watermark position
//     $x = $pageWidth / 2;
//     $y = $pageHeight / 2;

//     // Rotate the coordinate system
//     $this->StartTransform();
//     $this->Rotate($angle, $x, $y);

//     // Output the watermark
//     $this->Cell($pageHeight, 0, self::WATERMARK_TEXT, 0, false, 'C', 0, '', 0, false, 'M', 'M');

//     // Restore the coordinate system
//     $this->StopTransform();
// }


    // public function Watermark() {
    // 	 	        // Set footer watermark text
    //     $this->SetAlpha(0.3);
    //     // Set watermark font and color
    //     $this->SetFont('helvetica', 'B', 80);
    //     $this->SetTextColor(200, 200, 200);

    //     // Calculate diagonal angle
    //     $angle = 45;

    //     // Get page dimensions
    //     $pageWidth = $this->getPageWidth();
    //     $pageHeight = $this->getPageHeight();

    //     // Calculate watermark position
    //     $x = $pageWidth / 2;
    //     $y = $pageHeight / 2;

    //     // Rotate the coordinate system
    //     $this->StartTransform();
    //     $this->Rotate($angle, $x, $y);

    //     // Output the watermark
    //     $this->Cell($pageHeight, 0, self::WATERMARK_TEXT, 0, false, 'C', 0, '', 0, false, 'M', 'M');

    //     // Restore the coordinate system
    //     $this->StopTransform();
    // }
    // public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false) {
    //     parent::AddPage($orientation, $format, $keepmargins, $tocpage);
    //     $this->Watermark(); // Call the Watermark() method on each new page
    // }

    // Helper method to rotate text
    // protected function RotatedText($x, $y, $txt, $angle) {
    //     $txt = str_split($txt);
    //     $this->StartTransform();
    //     foreach ($txt as $char) {
    //         $this->Rotate($angle, $x, $y);
    //         $this->Text($x, $y, $char);
    //         $x += $this->GetStringWidth($char);
    //     }
    //     $this->StopTransform();
    // }
}
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// create new PDF document
// $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Md. Tareq Emran');
$pdf->SetTitle('BIO DATA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
// $pdf->SetFont('times', 'BI', 12);
//$pdf->SetFont('helvetica','BI', 9); 
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();




	  $emp_id=$_SESSION['emp_id'];

    $output = '';  
    $connect = mysqli_connect("localhost", "root", "", "pmis_db"); 
    $sql="SELECT seniority_no from basic_info where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 
	
			 
		$row = mysqli_fetch_assoc($result);
		$seniority_no=$row['seniority_no']; 
	  $content = '';  
      $content .= '
      
	  <h4 align="center" class=""> Employee ID: '.$_SESSION['emp_id'].'&nbsp;&nbsp;&nbsp;'.' Seniority No :'. $seniority_no .'&nbsp;&nbsp;&nbsp;'.' Date : '. date('d-m-Y') .' '.$_SESSION['seniority_no'].' </h4>
	 
	  <h4 align="center" style="color:#918887; text-transform: uppercase;"><b>BCIC Officers BIO-DATA sheet</b></h4>
	  
      
	 
		   ';  

	 
     
      $content .= fetch_data_admin();  
      $content .= '';  
	  

// print a block of text using Write()
// $pdf->Write(0, $content, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($content, true, false, true, false, '');
// Call the Watermark() method to display the watermark
//$pdf->Watermark();
// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('bio_data.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

     

// Write the content to the PDF
// $obj_pdf->writeHTML($content, true, false, true, false, '');
// ob_end_clean();
// // Output the PDF
// $obj_pdf->Output('bio_data.pdf', 'I'); 
 }  
 ?>  
