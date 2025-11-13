<?php 
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$table_name=$_SESSION['table_name'];

echo $table_name;
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
} 

include_once 'db/database.php';
include_once 'backend/header.php';
$today_date=date("Y-m-d");
?>
<div class="container-fluid ">
<!-- <p id="success"></p> -->
	<div class="table-wrapper border rounded shadow p-2">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-3">
					<h2 class="text-muted text-center"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b> </h2>
					<span class="text-primary fw-bold"><small>Username : [--<?php echo $_SESSION['username']; ?>--]</small></span><br>
					<span class="text-success fw-bold"><small>Office : [--<?php echo $office; ?>--]</small></span><br>
					<span class="text-warning fw-bold"><small>Logged In As a : [--<?php echo $user_type; ?>--]</small></span>
				</div>
				<div class="col-sm-9 text-end">
					<?php
					if($user_type=='sadmin'){   
						?>
						<h4><a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
						</h4>
							<h4>
						<a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database </a>
						</h4>
						<?php
						 }
						?>
					<a href="#addEmployeeModal" class="btn btn-outline-success " data-toggle="modal"><i class="fa fa-plus" style="font-size:16px"></i> <span> Add New Document</span></a>	

					<a href="backend/search_new.php?table_name=<?=$_SESSION['table_name']?>" class="btn btn-outline-warning "><i class="fa fa-search" style="font-size:16px"></i> <span> Search</span></a>
					<a href="#" class="btn btn-outline-success "><i class="fa fa-eye" style="font-size:16px"></i> <span> Show All</span></a>

					<a href="backend/show_all_old.php?table_name=<?=$_SESSION['table_name']?>" class="btn btn-outline-success "><i class="fa fa-eye" style="font-size:16px"></i> <span> Show Old Document</span></a>

					<a href="backend/logout.php" class="btn btn-outline-danger" ><i class="fa fa-trash" style="font-size:16px"></i><span> Logout</span></a>	

					<!-- <a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print</a>	 -->			

					<hr>
					<?php				
					
					if($user_type=='admin'){
					?>

					<!-- <a href="backend/directors_meeting.php?dir_tbl=dir_com" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (Commercial)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (Finance)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (T&E)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (P&I)</span></a>
					<a href="backend/search_by_date.php" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
					<span>Director (P&R)</span></a>
					<hr> -->
					
					<?php }; ?>					
				
				<!-- <a href="backend/print_all.php" target="_blank" class="btn btn-outline-primary btn-mb"><i class="fa fa-print" style="font-size:16px"></i> Print All</a> -->
				</div>
			</div>
		</div> 
		<!-- <hr> -->

		<table class="table table-bordered table-striped table-hover">
			<thead class="table-dark text-center">
				<tr>					
					<th>ক্রম</th>
					<th>পত্র প্রাপ্তি তারিখ</th>
					<th>প্রাপক</th>
					<th>ডকেট নং</th>
					<th>স্মারক নং</th>					
					<th>পাঠানোর তারিখ </th>	
					<th>পত্র প্রেরক</th>
					<th>ডিভিশন/অফিস</th>
					<th>বিষয়বস্তু</th>
					<th>গন্তব্য</th>					
					<th>বিতরণ তারিখ</th>
					<!-- <th>চেয়ারম্যান নোট</th>					
					<th>মন্তব্য</th> -->	
					<th>মাধ্যম</th>					
					<th>অ্যাকশন</th>
				</tr>
			</thead>
			<tbody>			
			<?php

			function englishToBanglaNumber($number) {
			    $englishNumbers = range(0, 9);
			    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
			    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
				}

	if($user_type=='user' && $table_name=='chairman'){

			$today_date=date("Y-m-d");
			$result = mysqli_query($conn,"SELECT * FROM $table_name order by id DESC");
			// $result = mysqli_query($conn,"SELECT * FROM $table where date='$today_date'");
				$i=1;
				if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$entry_date=$row["entry_date"];
					$send_date=$row["send_date"];
					$distribution_date=$row["distribution_date"];
					$d_number=$row['d_number'];
					$destination=$row["destination"];
					$destination_drop=$row["destination_drop"];
					$destination_drop =  rtrim($destination_drop, ',');					
					if($destination!=''){
						$destination_drop=$destination_drop.', '.$destination;
					}
			?>
			 <tr id="<?php echo $row["id"]; ?>" class=" text-center">			 
				<td><?php echo englishToBanglaNumber($i); ?></td>
				<td><?php echo englishToBanglaNumber($entry_date); ?></td>
				<td><?php echo $row["recipient"]; ?></td>
				<td><?php echo englishToBanglaNumber($d_number); ?></td>
				<!-- <td><?php //echo $row["attention"]; ?></td> -->
				<td><?php echo $row["ref_number"]; ?></td>			 	
				<td><?php echo englishToBanglaNumber($send_date); ?></td>
				<td><?php echo $row["sender"]; ?></td>
				<td><?php echo $row["div_dept_office"];?>
				<td><?php echo $row["subject"]; ?></td>			 	
				<!-- <td><?php echo $row["chairman_note"]; ?></td>
				<td><?php echo $row["comments"]; ?></td> -->
				<td><?php echo $destination_drop; ?></td>
				<td><?php echo englishToBanglaNumber($distribution_date); ?></td>
				<td><?php echo $row["medium"];?> 
				<!-- <i class="fa fa-edit update" data-toggle="tooltip" style="font-size:24px"			 -->
			 	</td>				
				<td>
				 
				<a href="#viewEmployeeModal" class="edit" data-toggle="modal" style="text-decoration: none">
				    <i class="fa fa-eye view " data-toggle="tooltip"
				    data-id="<?php echo $row['id']; ?>"
				    data-entry_date="<?php echo englishToBanglaNumber($entry_date); ?>"
				    data-recipient="<?php echo $row['recipient']; ?>"
				    data-d_number="<?php echo englishToBanglaNumber($row['d_number']); ?>"
				    data-attention="<?php echo $row['attention']; ?>"
				    data-ref_number="<?php echo englishToBanglaNumber($row['ref_number']); ?>"
				    data-send_date="<?php echo englishToBanglaNumber($send_date);  ?>"
				    data-sender="<?php echo $row['sender']; ?>"
				    data-div_dept_office="<?php echo $row['div_dept_office']; ?>"
				    data-subject="<?php echo $row['subject']; ?>"
				    data-chairman_note="<?php echo $row['chairman_note']; ?>"
				    data-comments="<?php echo $row['comments']; ?>"
				    data-medium="<?php echo $row['medium']; ?>"	
				    data-destination="<?php echo $row['destination']; ?>"
				    data-distribution_date="<?php echo englishToBanglaNumber($distribution_date); ?>"
				    data-destination_drop="<?php echo $row['destination_drop']; ?>"						
				    title="View" style="font-size:20px; color:blue;"></i>
				</a>&nbsp;

				<a href="edit.php?id=<?= $row['id']; ?>" class="" data-toggle="tooltip" title="Edit"><i class="fa fa-edit" style="font-size:20px; color:black;"></i> </a>
				</td>
			</tr>
			<?php
			$i++;
				}
			 }
 			else {
				echo "<p class='btn btn-danger btn-md '>  No Record Found!!!</p>";
			}		 
		}

else {
    $result = mysqli_query($conn, "SELECT * FROM $table_name ORDER BY id DESC");
    $i = 1;
    if (mysqli_num_rows($result) > 0) {
        while ($row1 = mysqli_fetch_array($result)) {
            $unique_id = $row1["unique_id"];

            $table_parent = preg_replace('/[^a-zA-Z]/', '', $unique_id);
          

            $entry_date = $row1["entry_date"];
            $distribution_date = $row1["distribution_date"];
            $destination = $row1["destination"];
            $destination_drop = rtrim($row1["destination_drop"], ',');
            if ($destination != '') {
                $destination_drop .= ', ' . $destination;
            }
            $d_number = $row1["d_number"];

            // $result_for_chair = mysqli_query($conn, "SELECT * FROM $table_parent WHERE unique_id='$unique_id'");
            // while ($row = mysqli_fetch_array($result_for_chair)) {
                 $send_date = $row1["send_date"];
?>
                <tr id="<?php echo $row1["id"]; ?>" class="text-center">
                    <td><?php echo englishToBanglaNumber($i); ?></td>
                    <td><?php echo englishToBanglaNumber($entry_date); ?></td>
                    <td><?php echo $row1["recipient"]; ?></td>
                    <td><?php echo englishToBanglaNumber($row1["d_number"]); ?></td>
                    <td><?php echo englishToBanglaNumber($row1["ref_number"]); ?></td>
                    <td><?php echo englishToBanglaNumber($send_date); ?></td>
                    <td><?php echo $row1["sender"]; ?></td>
                    <td><?php echo $row1["div_dept_office"]; ?></td>
                    <td><?php echo $row1["subject"]; ?></td>
                    <td><?php echo $destination_drop; ?></td>
                    <td><?php echo englishToBanglaNumber($distribution_date); ?></td>
                    <td><?php echo $row1["medium"]; ?></td>
                    <td>
                    <a href="#viewEmployeeModal" class="edit" data-toggle="modal" style="text-decoration: none">
                        <i class="fa fa-eye viewothers" data-toggle="tooltip"
                           data-id="<?php echo $row1['id']; ?>"
						    data-entry_date="<?php echo englishToBanglaNumber($entry_date); ?>"
						    data-recipient="<?php echo $row['recipient']; ?>"
						    data-d_number="<?php echo englishToBanglaNumber($row1['d_number']); ?>"
						    data-attention="<?php echo $row1['attention']; ?>"
						    data-ref_number="<?php echo $row1['ref_number']; ?>"
						    data-send_date="<?php echo englishToBanglaNumber($send_date);  ?>"
						    data-sender="<?php echo $row1['sender']; ?>"
						    data-div_dept_office="<?php echo $row1['div_dept_office']; ?>"
						    data-subject="<?php echo $row1['subject']; ?>"
						    data-chairman_note="<?php echo $row1['chairman_note']; ?>"
						    data-comments="<?php echo $row1['comments']; ?>"
						    data-medium="<?php echo $row1['medium']; ?>"	
						    data-destination="<?php echo $row1['destination']; ?>"
						    data-distribution_date="<?php echo englishToBanglaNumber($row1["distribution_date"]); ?>"
						    data-destination_drop="<?php echo $row1['destination_drop']; ?>"					
                           title="View" style="font-size:20px; color:blue;"></i>
                    </a>&nbsp;
                        <a href="edit_others.php?id=<?= $row1['id'] ?>&unique_id=<?= $row1['unique_id'] ?>" class="">
                            <i class="fa fa-edit" style="font-size:20px; color:black;"></i>
                        </a>
                    </td>
                </tr>
<?php
                $i++;
            // }
        }
    } else {
        echo "<p class='btn btn-danger btn-md'>No Record Found!!!</p>";
    }
}
?>
			</tbody>
		</table>		
	</div>
</div>

<!-- View Modal HTML -->
<div id="viewEmployeeModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="">                
                <div class="modal-header d-flex justify-content-center">
				    <h4 class="modal-title text-uppercase text-center text-muted flex-grow-1"><b>পত্র প্রাপ্তি রেজিস্টার বিস্তারিত</b></h4>
				    <button type="button" class="btn-close" data-dismiss="modal"></button>
				</div>
                <div class="modal-body" id="printableArea">
                    <table class="table table-bordered">
                        <tbody>                           
                            <tr>
                                <th>পত্র প্রাপ্তি তারিখ:</th>
                                <td id="entry_date_u"></td>
                            </tr>
                            <tr>
                                <th>প্রাপক:</th>
                                <td id="recipient_u"></td>
                            </tr>
                            <tr>
                                <th>ডকেট নং:</th>
                                <td id="d_number_u"></td>
                            </tr>
                            <tr>
                                <th>দৃষ্টি আকর্ষণ:</th>
                                <td id="attention_u"></td>
                            </tr>
                            <tr>
                                <th>স্মারক নং:</th>
                                <td id="ref_number_u"></td>
                            </tr>
                            <tr>
                                <th>পাঠানোর তারিখ:</th>
                                <td id="send_date_u"></td>
                            </tr>
                            <tr>
                                <th>পত্র প্রেরক:</th>
                                <td id="sender_u"></td>
                            </tr>
                            <tr>
                                <th>ডিভিশন/ডিপার্টমেন্ট/অফিস:</th>
                                <td id="div_dept_office_u"></td>
                            </tr>
                            <tr>
                                <th width="30%">বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু:</th>
                                <td id="subject_u"></td>
                            </tr>
                             <tr>
                                <th>গন্তব্য:</th>
                                <td id="destination_drop_u"><?php //echo $destination_drop;?></td>
                            </tr>
                            <tr>
                                <th>গন্তব্য (লিখুন):</th>
                                <td id="destination_u"></td>
                            </tr>
                            <tr>
                                <th>বিতরণ তারিখ:</th>
                                <td id="distribution_date_u"></td>
                            </tr>
                            <tr>
                                <th>চেয়ারম্যান নোট:</th>
                                <td id="chairman_note_u"></td>
                            </tr>
                            <tr>
                                <th>মন্তব্য:</th>
                                <td id="comments_u"></td>
                            </tr>
                            <tr>
                                <th>মাধ্যম (হার্ডকপি/ফ্যাক্স/ই-মেইল):</th>
                                <td id="medium_u"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="2" name="type">
                    <input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
	// for viewothers
	$(document).on('click','.viewothers',function(e) {
		var id=$(this).attr("data-id");
		var entry_date=$(this).attr("data-entry_date");
		var recipient=$(this).attr("data-recipient");
		var d_number=$(this).attr("data-d_number");
		var attention=$(this).attr("data-attention");
		var ref_number=$(this).attr("data-ref_number");
		var send_date=$(this).attr("data-send_date");
		var sender=$(this).attr("data-sender");
		var div_dept_office=$(this).attr("data-div_dept_office");
		var subject=$(this).attr("data-subject");
		var chairman_note=$(this).attr("data-chairman_note");
		var comments=$(this).attr("data-comments");
		var medium=$(this).attr("data-medium");
		var destination_drop=$(this).attr("data-destination_drop");
		var destination=$(this).attr("data-destination");
		var distribution_date=$(this).attr("data-distribution_date");

		// Populate table cells with data
    $('#destination_drop_u').text(destination_drop);
    $('#entry_date_u').text(entry_date);
    $('#recipient_u').text(recipient);
    $('#d_number_u').text(d_number);
    $('#attention_u').text(attention);
    $('#ref_number_u').text(ref_number);
    $('#send_date_u').text(send_date);
    $('#sender_u').text(sender);
    $('#div_dept_office_u').text(div_dept_office);
    $('#subject_u').text(subject);
    $('#chairman_note_u').text(chairman_note);
    $('#comments_u').text(comments);
    $('#medium_u').text(medium);
    $('#destination_u').text(destination);
    $('#distribution_date_u').text(distribution_date);
	});

    document.getElementById('print').addEventListener('click', function() {
        // Get the content to be printed
        var printContents = document.getElementById('printableArea').innerHTML;
        var originalContents = document.body.innerHTML;
        // Create a title to prepend to the content
        var title = '<h1 class="text-center">পত্র প্রাপ্তি রেজিস্টার</h1>';        
        // Set the new print content with title
        document.body.innerHTML = title + printContents;
        // Print the document
        window.print();
        // Restore the original content
        document.body.innerHTML = originalContents;
        // Optionally reload to restore the original page content after printing
        location.reload();
    });
</script>

<!-- add modal -->
<div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="user_form" role="form">
                <!-- Modal Header -->              
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title text-uppercase mx-auto fw-bold">পত্র প্রাপ্তি রেজিস্টার এন্ট্রি ফরম</h4>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating mb-2 mt-2">
                                <input type="date" class="form-control" id="entry_date" name="entry_date" required>
                                <label for="entry_date">পত্র প্রাপ্তি তারিখ :</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-2 mt-2">
                                <!-- <input type="text" class="form-control" id="recipient" name="recipient" required> -->
			                   <select class="form-select form-control" id="recipient" name="recipient"  aria-label="Default select example">
			                    <option selected disabled value="">--Select--</option>
			                     <?php
				                  $sql = "SELECT division_bn FROM  division where id not in(2,3,4)";
				                  $result = mysqli_query($conn, $sql);
				                  while($row = mysqli_fetch_array($result)) {
				                   echo "<option value='".$row['division_bn']."'>".$row['division_bn']."</option>";
				                  }
			                  ?>   
			                </select>
                                <label for="recipient">প্রাপক :</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-group">
                                <label>ডকেট নং :</label>
                                <?php
				                  $sql_d_number = "SELECT MAX(d_number) AS max_d_number FROM $table_name";
				                  $result_d_number = mysqli_query($conn, $sql_d_number);
				                	$row_d_number = mysqli_fetch_array($result_d_number); 
				                	$row_d_number_max= $row_d_number['max_d_number']+1;
			                  ?>   
                                <input type="text" class="form-control bg-light" id="d_number" name="d_number" value="<?php echo englishToBanglaNumber($row_d_number_max)?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label>দৃষ্টি আকর্ষণ :</label>
                                <input type="text" class="form-control" id="attention" name="attention">
                            </div>
                        </div>
                    </div>   

                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>স্মারক নং :</label>
                                <input type="text" class="form-control" id="ref_number" name="ref_number" >                    
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>পাঠানোর তারিখ :</label>
                                <input type="date" class="form-control" id="send_date" name="send_date" required>                   
                            </div>
                        </div>
                    </div> 

                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>পত্র প্রেরক:</label>
                                <input type="text" class="form-control" id="sender" name="sender" required>                    
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>ডিভিশন/ডিপার্টমেন্ট/অফিস :</label>
                                <input type="text" class="form-control" id="div_dept_office" name="div_dept_office">                  
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-1 mt-1">
                        <label>বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু:</label>
                        <textarea class="form-control" rows="1" id="subject" name="subject" required></textarea> 
                    </div>   

                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>চেয়ারম্যান নোট:</label>
                                <input type="text" class="form-control" id="chairman_note" name="chairman_note">                    
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group mb-1 mt-1">
                                <label>মন্তব্য:</label>
                                <input type="text" class="form-control" id="comments" name="comments">                  
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>মাধ্যম (হার্ডকপি/ফ্যাক্স/ই-মেইল)</label>
                        <select class="form-select form-control" id="medium" name="medium" required aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <option value="হার্ডকপি">হার্ডকপি</option>
                            <option value="ই-মেইল">ই-মেইল</option>
                            <option value="ফ্যাক্স">ফ্যাক্স</option>
                        </select>
                    </div>

                </div>
                
                <div class="modal-footer">
                    <input type="hidden" value="1" name="type">
                    <input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-success" id="btn-add"><i class="fa fa-save" style="font-size:16px"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal HTML -->
<?php
// Fetch record by ID (for demo, using a static ID of 10)
// $sql_query = "SELECT destination_drop FROM chairman";
// $result_query = mysqli_query($conn, $sql_query);
// $row_query = mysqli_fetch_assoc($result_query);
// $selected_destination_drop = $row_query['destination'];
?>
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<form id="update_form">				
				<div class="modal-header">
			        <h4 class="modal-title text-uppercase">পত্র প্রাপ্তি রেজিস্টার সংশোধন ফরম</h4>
			        <button type="button" class="btn-close" data-dismiss="modal"></button>
			      </div>
				<div class="modal-body">
					<input type="hidden" id="id_u" name="id" class="form-control" required>	
					<!-- New Destination Field -->
					<div class="row g-2">    
					    <div class="col-md">
					        <div class="form-group mb-1 mt-1">
					            <label> গন্তব্য (নির্বাচন করুন): </label>
					           <select class="form-control" id="destination_drop_u" name="destination_drop" required>
								    <option value="" >--Select--</option>
								    <?php
								    // Fetch divisions from the database
								    $sql = "SELECT division FROM division";
								    $result = mysqli_query($conn, $sql);
								    while($row = mysqli_fetch_array($result)){
								        $division = $row['division'];
								        // Check if this option should be selected
								        $selected = ($division == $selected_destination_drop) ? 'selected' : '';
								        echo "<option value='$division' $selected>$division</option>";
								    }
								    ?>
								</select>
									   </div>
								</div> 
							</div>
					
					<div class="row g-2">
				          <div class="col-md">
				          <div class="form-group"><label> গন্তব্য (লিখুন): </label>
				           <!-- <input type="text" class="form-control" id="destination_u" placeholder="" name="destination" required> -->
				           <textarea class="form-control" placeholder="" rows="1" id="destination_u" value="" name="destination" ></textarea>
				        
				        </div>
				          </div>
				       <div class="col-md">
				          <div class="form-group">
				          	<label> বিতরণ তারিখ:  </label>
				          <input type="date" class="form-control" id="distribution_date_u" placeholder="" name="distribution_date"> </div>
				          </div>
		      		</div>					

					<div class="row g-2">
				          <div class="col-md">
				          <div class="form-floating mb-2 mt-2">
				           <input type="date" class="form-control" id="entry_date_u" placeholder="" name="entry_date" required>
				        <label for="entry_date"> পত্র প্রাপ্তি তারিখ :</label>
				        </div>
				          </div>
				          <div class="col-md">
				          <div class="form-floating mb-2 mt-2">
				          <input type="text" class="form-control" id="recipient_u" placeholder="" name="recipient">
				          <label for="recipient">প্রাপক  :</label>
				        </div>
				          </div>
				      </div>	

					 <div class="row g-2">
				          <div class="col-md">
				          <div class="form-group"><label> ডকেট নং : </label>
				           <input type="text" class="form-control" id="d_number_u" placeholder="" name="d_number" required>
				        
				        </div>
				          </div>
				          <div class="col-md">
				          <div class="form-group">
				          	<label> দৃষ্টি আকর্ষণ : </label>
				          <input type="text" class="form-control" id="attention_u" placeholder="" name="attention">				          
				        </div>
				          </div>
		      		</div>	

		      		<div class="row g-2">
				       <div class="col-md">
				          <div class="form-group mb-1 mt-1"><label> স্মারক নং : </label>
				           <input type="text" class="form-control" id="ref_number_u" placeholder="" name="ref_number" required>				        
				        </div>
				          </div>

				        <div class="col-md">
				          <div class="form-group mb-1 mt-1">
				          	<label> পাঠানোর তারিখ : </label>
				          <input type="date" class="form-control" id="send_date_u" placeholder="" name="send_date">				          
				        </div>
				          </div>
		      		</div>	

					<div class="row g-2">
				       <div class="col-md">
				          <div class="form-group mb-1 mt-1"><label> পত্র প্রেরক: </label>
				           <input type="text" class="form-control" id="sender_u" placeholder="" name="sender" required>				        
				        </div>
				          </div>

				        <div class="col-md">
				          <div class="form-group mb-1 mt-1">
				          <label> ডিভিশন/ডিপার্টমেন্ট/অফিস : </label>
				          <input type="text" class="form-control" id="div_dept_office_u" placeholder="" name="div_dept_office" value="">				          
				        </div>
				          </div>
		      		</div>

					<div class="form-group mb-1 mt-1">
						<label> বিবরণ/বিষয়/সারসংক্ষেপ/বিষয়বস্তু: </label>
						<!-- <input type="text"  name="subject" class="form-control" id="subject_u"> -->
						<textarea class="form-control" placeholder="" rows="1" id="subject_u" value="" name="subject" ></textarea> 
					</div>	

					<div class="row g-2">
				       <div class="col-md">
				          <div class="form-group mb-1 mt-1">
				          	<label> চেয়ারম্যান নোট: </label>
				           <input type="text" class="form-control" id="chairman_note_u" placeholder="" name="chairman_note" required>				        
				        </div>
				          </div>

				        <div class="col-md">
				          <div class="form-group mb-1 mt-1">
				          <label> মন্তব্য: </label>
				          <input type="text" class="form-control" id="comments_u" placeholder="" value="" name="comments">				          
				        </div>
				          </div>
		      		</div>

		      		<div class="row g-2">
				       <div class="col-md">
				          <div class="form-group mb-1 mt-1">
				          	<label> মাধ্যম (হার্ডকপি/ফ্যাক্স/ই-মেইল) </label>
				           <input type="text" class="form-control" id="medium_u" placeholder="" name="medium" required>				        
				        </div>
				          </div>
		      		</div>		
												
				</div>
				<div class="modal-footer">
				<input type="hidden" value="2" name="type">
					<input type="button" class="btn btn-outline-danger" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-info" id="update"><i class="fa fa-upload" style="font-size:16px"></i> Update</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>		
				      <!-- Modal Header -->
			      <div class="modal-header">
			        <h4 class="modal-title text-uppercase">Delete User</h4>
			        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			      </div>
				<div class="modal-body">
					<input type="hidden" id="id_d" name="id" class="form-control">					
					<p>Are you sure you want to delete these Records?</p>
					<p class="text-warning"><small>This action cannot be undone.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-outline-secondary" data-dismiss="modal" value="Cancel">
					<button type="button" class="btn btn-danger" id="delete">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div> 
</body>
</html> 
<?php //include_once"backend/footer.php";?>

<!-- Chosen JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script> -->

<!-- Bootstrap 5.3.3 JS (Optional, if you need Bootstrap's JavaScript components) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">

	// $(document).on('click', '.view', function(e) {
 //    var entry_date = $(this).attr("data-entry_date");
 //    $('#entry_date_u').text(entry_date);    
	// });



	// for View Chairman table
	$(document).on('click','.view',function(e) {
		var id=$(this).attr("data-id");
		var entry_date=$(this).attr("data-entry_date");
		var recipient=$(this).attr("data-recipient");
		var d_number=$(this).attr("data-d_number");
		var attention=$(this).attr("data-attention");
		var ref_number=$(this).attr("data-ref_number");
		var send_date=$(this).attr("data-send_date");
		var sender=$(this).attr("data-sender");
		var div_dept_office=$(this).attr("data-div_dept_office");
		var subject=$(this).attr("data-subject");
		var chairman_note=$(this).attr("data-chairman_note");
		var comments=$(this).attr("data-comments");
		var medium=$(this).attr("data-medium");
		var destination_drop=$(this).attr("data-destination_drop");
		var destination=$(this).attr("data-destination");
		var distribution_date=$(this).attr("data-distribution_date");

		// Populate table cells with data
    $('#destination_drop_u').text(destination_drop);
    $('#entry_date_u').text(entry_date);
    $('#recipient_u').text(recipient);
    $('#d_number_u').text(d_number);
    $('#attention_u').text(attention);
    $('#ref_number_u').text(ref_number);
    $('#send_date_u').text(send_date);
    $('#sender_u').text(sender);
    $('#div_dept_office_u').text(div_dept_office);
    $('#subject_u').text(subject);
    $('#chairman_note_u').text(chairman_note);
    $('#comments_u').text(comments);
    $('#medium_u').text(medium);
    $('#destination_u').text(destination);
    $('#distribution_date_u').text(distribution_date);
	});


	// for edit
	// $(document).on('click','.update',function(e) {
	// 	var id=$(this).attr("data-id");
	// 	var entry_date=$(this).attr("data-entry_date");
	// 	var recipient=$(this).attr("data-recipient");
	// 	var d_number=$(this).attr("data-d_number");
	// 	var attention=$(this).attr("data-attention");
	// 	var ref_number=$(this).attr("data-ref_number");
	// 	var send_date=$(this).attr("data-send_date");
	// 	var sender=$(this).attr("data-sender");
	// 	var div_dept_office=$(this).attr("data-div_dept_office");
	// 	var subject=$(this).attr("data-subject");
	// 	var chairman_note=$(this).attr("data-chairman_note");
	// 	var comments=$(this).attr("data-comments");
	// 	var medium=$(this).attr("data-medium");
	// 	var destination=$(this).attr("data-destination");
	// 	var distribution_date=$(this).attr("data-distribution_date");
	// 	var destination_drop=$(this).attr("data-destination_drop");

	// 	$('#id_u').val(id);
	// 	$('#entry_date_u').val(entry_date);
	// 	$('#recipient_u').val(recipient);
	// 	$('#d_number_u').val(d_number);
	// 	$('#attention_u').val(attention);
	// 	$('#ref_number_u').val(ref_number);

	// 	$('#send_date_u').val(send_date);
	// 	$('#sender_u').val(sender);
	// 	$('#div_dept_office_u').val(div_dept_office);
	// 	$('#subject_u').val(subject);
	// 	$('#chairman_note_u').val(chairman_note);

	// 	$('#comments_u').val(comments);
	// 	$('#medium_u').val(medium);
	// 	$('#destination_u').val(destination);
	// 	$('#distribution_date_u').val(distribution_date);
	// 	$('#destination_drop_u').val(destination_drop);
	// });


	
</script>

