 <?php  
include('../includes/header.php');
session_start();
 function fetch_data_admin($emp_id){
      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "pmis_db");  
      $emp_id=$emp_id;
      //print_r($emp_id);
		$tomorrow = date('Y-m-d');
		$year=date('Y',strtotime($tomorrow));		
		$year1=$year+1;
		$date4='01/07/'.$year;//2023
		$date3='01/07/'.$year1;//2024
		$sql="SELECT * from basic_info u join  job_desc j 
		ON u.emp_id=j.emp_id
		where j.emp_id='$emp_id'
		-- AND j.next_incr_date='2023-07-01'
		AND j.job_status='In Service'		
		";	  
      $output .= ' <br/><br/><br/><br/>
	        <table align="left" border="0" cellspacing="0" cellpadding="3"  style=" border-collapse: collapse; font-size: 14px;" width="100%">
	  		'; 
	  		 $result = mysqli_query($connect, $sql);
	  		  // $ref_no='36.091.018.01.02.7734.2023';
	  		if (mysqli_num_rows($result) > 0) { 
			while($row = mysqli_fetch_assoc($result)) {
			$next_incr_date=$row['next_incr_date'];
			$tomorrow = date('Y-m-d');
			$year=date('Y',strtotime($tomorrow));			
			$year1=$year+1;
			$date4='01/07/'.$year;
			$date3='01/07/'.$year1;
			$basic=$row['basic'];
			$incr_granted=$row["incr_granted"];
			$present_basic=(int)$basic+(int)$incr_granted;

     $output .= '
           		<tr>	
           		 <th width="120"></th>
                 <th ><b>EMP ID</b></th>
                 <td width="10">:</td>
                 <td width="100%">&nbsp;&nbsp;&nbsp;&nbsp;'.$row["emp_id"].'</td>  
                 </tr>

                <tr><th ></th>
                <th ><b>Name</b></th> 
                <td >:</td>
                <td >&nbsp;&nbsp;&nbsp;&nbsp;'.$row["name"].'</td>
                </tr>

                <tr><th></th>
                <th ><b>Designation</b></th><td>:</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$row["designation"].'</td>
                </tr>
                <tr> <th></th> 
           		<th ><b>Place of Posting</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$row["place_of_posting"].'</td>
           		</tr>
           		<tr>  <th></th>
           		<th ><b>Scale of Pay</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$row["scale"].'</td>
           		</tr>
           		<tr>  <th></th>
           		<th ><b>Date of Increment</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$date4.'</td>
           		</tr>
           		<tr>  <th></th>
           		<th ><b>Increment Granted</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$row["incr_granted"].'</td>
           		</tr>
           		<tr>  <th></th>
           		<th ><b>Present Basic Pay</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$row["basic"].'</td>
           		
           		</tr>
           		<tr>  <th></th>
           		<th ><b>Basic After Inrement</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$present_basic.'</td>
           		</tr>

           		<tr>  <th></th>
           		<th ><b>Next Increment Date</b></th><td>:</td>
           		<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$date3.'</td>
           		</tr>           
 			'; 
    }  
  }
     $output .= '</table>' ; 
	$output .= '<br/> <br/><br/><br/><br/><br/><br/>';
     $output .= '<table border="0" cellspacing="0" cellpadding="3" style=" border-color: white;border-collapse: collapse; border-style: dotted; font-size:13px;">     		
     		<tr> 
				<td></td>
				<td align="left" >C.C &nbsp;&nbsp;  TO:</td><td></td> <td width="100%"> &nbsp;&nbsp;For Chief Of Personnel</td>
				<td></td>
				</tr>

				<tr> 
				<td></td>
				<td width="100%">1.Person Concerened</td>
				<td > </td>
				<td></td>
				</tr>

				<tr> 
				<td></td>
				<td width="100%">2. Head of Enterprise/Division</td>
				<td > </td>
				<td></td>
				</tr>
				<tr> 
				<td></td>
				<td width="100%">3. Controller of Accounts, BCIC H.O.</td>
				<td > </td>
				<td></td>
				</tr>
				<tr> 
				<td></td>
				<td width="100%">4. Head of Accounts of Enterprise</td>
				<td > </td>
				<td></td>
				</tr>
				<tr> 
				<td></td>
				<td width="100%">5. Secy. CPF, Trust, BCIC H.O.</td>
				<td > </td>
				<td></td>
				</tr>
				<tr> 
				<td></td>
				<td width="100%">6. Personal File</td>
				<td > </td>
				<td></td>
				</tr>
				<tr>
				<td ></td>
				<td></td>				
				<td></td>  <td></td> <td></td> <td></td> <td></td> <td></td>
            </tr>
            <tr>  
     		<td></td>  <td></td> <td></td> <td></td> <td></td> <td></td>

            </tr>
            '; 

   $output .= '</table>'; 
      return $output;
       //$result1->free();
	 mysqli_close($connect);
 } //end admin function
 
 if(isset($_POST["create_pdf"])){  
      require_once('../tcpdf/tcpdf.php'); 
		//Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
			//Page header
			public function Header() {
				// Logo
				// $image_file = K_PATH_IMAGES.'../image/BCIC_logo copy.jpg';
				// $this->Image($image_file, 15, 15, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
				$this->Cell(0, 10, '[Design & developed by.]  ::-- Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			}
		}
	  
      $obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      //$obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Increment Letter");  
	// set default header data
	$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		// set default monospaced font 
	  $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica','Nikosh','Nirmala UI');
	  $obj_pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $obj_pdf->SetHeaderMargin(PDF_MARGIN_TOP);   
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '3', PDF_MARGIN_RIGHT,'2');  
      $obj_pdf->setPrintHeader(false);  
      //$obj_pdf->setPrintFooter(false);  
      $obj_pdf->SetAutoPageBreak(TRUE, 9);  
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
      	$obj_pdf->AddPage('P',"A4");
	 	$strBNFont = TCPDF_FONTS::addTTFfont('../tcpdf/Nikosh.ttf', 'TrueTypeUnicode', '', 32); 
		$obj_pdf->SetFont($strBNFont, '', 9, '', 'true'); 
	
	//$obj_pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0); 
	// set color for text
	$obj_pdf->SetTextColor(145, 136, 135);
	
	// $obj_pdf->Image('../image/bcic.jpg', 17, 15, 12, 12, 'JPG', 'http://www.bcic.gov.bd', '', true, 0, '', false, false, 0, false, false, false);

	// $obj_pdf->Image('image/BCIC_logo copy.jpg', 17, 25, 15, 15, 'JPG', 'http://www.bcic.gov.bd', '', true, 0, '', false, false, 0, false, false, false);

     $connect = mysqli_connect("localhost", "root", "", "pmis_db"); 
     	$sql="SELECT * from basic_info u join  job_desc j 
		ON u.emp_id=j.emp_id 
		-- AND j.next_incr_date='2023-07-01'
		AND j.job_status='In Service'
		";
	  $result = mysqli_query($connect, $sql); 
	if (mysqli_num_rows($result) > 0) { 
	while($row = mysqli_fetch_assoc($result))  
      {
			$division=$row['division'];
			$section=$row['section'];
			$next_incr_date=$row['next_incr_date'];
			$emp_id=$row['emp_id'];						
			$explodeEmp_idWithNegative = explode("-", $emp_id);			
			$emp_id_split= $explodeEmp_idWithNegative[0];			  
			$tomorrow = date('Y-m-d');
			$year=date('Y',strtotime($tomorrow));			
			$year1=$year+1;
			$date4='01/07/'.$year;
			$i=1;
			while($i<=1){
			 $content = ''; 
	// $obj_pdf->Image('../images/BCIC_logo copy.jpg', 17, 25, 15, 15, 'JPG', 'http://www.bcic.gov.bd', '', true, 0, '', false, false, 0, false, false, false);
      $content .= '<h3></h3><p></p>
      <div style="border:3px solid #441487"><div style="background-color:#9fd1a7;">
      <h4 lang="bn" style="text-align:center;color:black"> <img src="../images/org-name.jpg" alt="Girl in a jacket" width="500" height="40"> </h4>
      <h2 style="text-align:center;color:black">   Bangladesh Chemical Industries Corporation (BCIC) </h2>
	  <h4 align="center" style="color:black; text-transform: uppercase;">Bcic Bhaban, 30-31 Dilkusha C/A, Dhaka-1000. </h4>
    
	 </div><div style="color: white; background-color:#518f5b;text-transform: uppercase; text-color:white; text-align: center; text-decoration:none; font-weight: bold; font-size: 26px; font-family: Arial, Helvetica, sans-serif; border: 1px solid #4CAF50;" >Increment Letter 
	 </div>
	 <br/>

	 <span style="text-align: left;font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ref.: 36.091.018.01.02.'.$emp_id_split.'.'.$year.'              </span> 
	 <label style="text-align:right;font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; Date:
	 '.$date4.'</label>

<br/><br/><div style="color:black; text-align: center; height: 500px; width: 100%; border: 1px solid #4CAF50; font-weight: bold; font-size: 18px; text-transform: uppercase; background-color:#9fd1a7; font-family: Arial, Helvetica, sans-serif;">Subject: Granting of annual increment<br/> effective from '.$date4.'</div>  
	   ';  
 	$content .= fetch_data_admin($emp_id); 
      $content .= '</div>'; 
      $content .= '';
      $content .= '<h4 style="text-align:center;"><b>N.B. Please notify any change in your service history with the Employee Number</b> </h4>
      '; 
 	$obj_pdf->writeHTML($content);
 			$i++;
 			}
		 }

		}
    ob_end_clean(); 
	$now=date('d-m-Y');	  
    $obj_pdf->Output('Increment Letter-'.$now.'.pdf', 'I');  
 }  	  

 ?>    
