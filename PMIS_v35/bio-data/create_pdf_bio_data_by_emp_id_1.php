 <?php  
// create_pdf_bio_data_by_emp_id.php
session_start();
$_SESSION['emp_id']=$_GET["emp_id"];
$_SESSION['emp_id'];

include('includes/header.php');

 function fetch_data_admin(){
 	$emp_id=$_SESSION['emp_id'];
 
      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "pmis_db");  
	  
      //$sql = "SELECT * FROM ajax_todo_tbl WHERE `date` LIKE '{$date}%'";
	  $sql="SELECT DISTINCT * from users u INNER JOIN job_desc j ON u.emp_id=j.emp_id  where u.emp_id='$emp_id' LIMIT 1 ";
	  //$sql="SELECT DISTINCT * from users u INNER JOIN job_desc j ON u.emp_id=j.emp_id inner join prof_membership p on j.emp_id=p.emp_id where u.emp_id='$emp_id' LIMIT 1 ";
      //$sql="SELECT * from job_desc where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 	
			 
	while($row = mysqli_fetch_assoc($result)) {       
      
      $output .= '
	        <table border="1" cellspacing="0" cellpadding="3" align="center" style=" border: 1px solid white; border-color: #96D4D4;border-collapse: collapse; border-style: dotted;" >
	  <thead> 
           <tr style="background-color:green;color:white;">  
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
					 
				<p style="margin : 0 !important; padding-top:-20px !important; margin-top:-20px !important;" class="m-2"></p>
					 
				<table border="1" cellspacing="0" cellpadding="3" align="center" style="border-color:gray;" >
	  				<thead>
					   <tr style="background-color:green;color:white;">  
							 
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
					   <tr style="background-color:#a53be3;color:white;">
					   <th ><b>Grade</b></th>  
							<th ><b>Designation</b></th>
							<th ><b>Basic Pay</b></th>
							<th ><b>Scale</b></th>  
							<th ><b>Increment Due Date</b></th>
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
                          <td>'.$row["incr_due_date"].'</td>  
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
	  
	  <p style="margin : 0 !important; padding-top:-20px !important; margin-top:-20px !important;" class="m-2"></p>	

	<table border="1" cellspacing="0" cellpadding="3" align="center"  >
	  <thead> 
           <tr style="background-color:#ffffff;color:black;"> 		   
                 
                <th colspan="5" style="text-align:left;"><b>Professional / Membership Information</b></th>  
           </tr>
			<tr style="background-color:#c9c2c1;color:black;"> 		   
                 
                <th ><b>Membership No.</b></th>  
                <th ><b>Mem. Type</b></th>  
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
	  
	  <p style="margin : 0 !important; padding-top:-40px !important; margin-top:-40px !important;" class="m-1"></p>	

	<table border="1" cellspacing="0" cellpadding="3" align="center" style="border-left-width:0.1px;border-right-width:0.1px;border-bottom-width:0.1px;border-right-color:gray; margin-bottom:100px;" >
	  <thead> 
	  <tr style="background-color:#ffffff;color:black;"> 		   
                 
                <th colspan="7" style="text-align:left;"><b>Educational Qualification</b></th>  
             
           </tr>
           <tr style="background-color:#c9c2c1;color:black;">  
                 
	        <th>Examination</th>
			<th>Subject/ Group</th>
			<th>Institute Name</th>
	        <th>Board</th>
	        <th>CGPA/Division/Class</th>
			<th>Passing Year</th>
			<th>Course Duration</th>
            </tr></thead>

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
	  
	   <p style="margin : 10 !important; padding-top:-20px !important; margin-top:-20px !important;" class="m-5"></p>	

	<table border="1" border-color="gray" cellspacing="0" cellpadding="3" align="center" style="border-color:gray;" >
	  <thead> 
	   <tr style="background-color:#ffffff;color:black;"> 		   
                 
                <th colspan="6" style="text-align:left;"><b>Training Information</b></th>  
           </tr>
           <tr style="background-color:#c9c2c1;color:black;">  
                 
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
      require_once('tcpdf/tcpdf.php'); 
//Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {

			//Page header
			public function Header() {
				// Logo
				$image_file = K_PATH_IMAGES.'image/bcic.jpg';
				$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				// Set font
				$this->SetFont('helvetica', 'B', 10);
				// Title
				$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			}

			//Page footer
			public function Footer() {
				// Position at 15 mm from bottom
				$this->SetY(-15);
				// Set font
				$this->SetFont('helvetica', 'I', 8);
				// Page number
				$this->Cell(0, 10, '[Design & developed by Md. Tareq Emran, Programmer.]  ::-- Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			}
		}
	  
	  // create new PDF document
	$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      //$obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Bio Data");  
	
// set default header data
$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set default monospaced font 
	
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica','Nikosh','Nirmala UI');
	  	$obj_pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      $obj_pdf->SetHeaderMargin(PDF_MARGIN_TOP);   
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '2', PDF_MARGIN_RIGHT,'2');  

	  	// set margins
		// $obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		// $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


      $obj_pdf->setPrintHeader(false);  
      //$obj_pdf->setPrintFooter(false);  
      // $obj_pdf->SetAutoPageBreak(TRUE, 9); 

      // set auto page breaks
		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      $obj_pdf->SetFont('helvetica', 9); 
	  	
	// set auto page breaks
	$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set text shadow effect
	$obj_pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.1, 'depth_h'=>0.1, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));



	// set image scale factor
		$obj_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			//require_once(dirname(__FILE__).'/lang/ind.php');
			$obj_pdf->setLanguageArray($l);
		}


		// set default font subsetting mode
		$obj_pdf->setFontSubsetting(true);
			
		// set font
		//$obj_pdf->SetFont('nikosh', 'helvetica', 12);
		$obj_pdf->SetFont('helvetica','', 9);
      $obj_pdf->AddPage('L',"A4");
	  $strBNFont = TCPDF_FONTS::addTTFfont('tcpdf/Nikosh.ttf', 'TrueTypeUnicode', '', 32); 
	$obj_pdf->SetFont($strBNFont, '', 9, '', 'true'); 

	$obj_pdf->SetTextColor(145, 136, 135);
	
// Image example with resizing
$obj_pdf->Image('image/bcic.jpg', 30, 10, 12, 12, 'JPG', 'http://www.bcic.gov.bd', '', true, 0, '', false, false, 0, false, false, false);


	  	$emp_id=$_SESSION['emp_id'];

      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "pmis_db"); 
      $sql="SELECT seniority_no from users where emp_id='$emp_id'";
	  $result = mysqli_query($connect, $sql); 
	
			 
		$row = mysqli_fetch_assoc($result);
			$seniority_no=$row['seniority_no'];

			 $content = '';  
      $content .= '<h3></h3>
      <h2 align="center" class="text-success text-uppercase" style="color:#918887; text-transform: uppercase;">Bangladesh Chemical Industries Corporation (BCIC) </h2>
	  <h4 align="center" class=""> Employee ID: '.$_SESSION['emp_id'].'&nbsp;&nbsp;&nbsp;'.' Seniority No :'. $seniority_no .'&nbsp;&nbsp;&nbsp;'.' Date :'. date('d-m-Y') .' '.$_SESSION['seniority_no'].' </h4>
	 
	  <h4 align="center" style="color:#918887; text-transform: uppercase;"><b>BCIC Officers BIO-DATA sheet</b></h4>
	  
      
	 
		   ';  

	 
     
      $content .= fetch_data_admin();  
      $content .= '';  
	  

	 //  $obj_pdf->writeHTML($content); 

	  
		// ob_end_clean(); 	  
  //     $obj_pdf->Output('sample.pdf', 'I'); 

     

// Write the content to the PDF
$obj_pdf->writeHTML($content, true, false, true, false, '');
ob_end_clean();
// Output the PDF
$obj_pdf->Output('bio_data.pdf', 'I'); 
 }  
 ?>  
