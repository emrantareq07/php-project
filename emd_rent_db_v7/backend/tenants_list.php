<?php

session_name('emd_rent_db');
session_start();
include_once '../db/database.php';
include_once 'header.php';
require_once("config.php");
include_once 'update_navbar.php';
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];

// $tenants_tbl = $_GET['tenants_tbl'] ?? ''; // Retrieve the tenants table from the URL
// $_SESSION['tenants_tbl'] = $_GET['tenants_tbl'];
// $tenants_tbl = $_SESSION['tenants_tbl'];
?>
<div class="container-fluid p-3 rounded shadow">
<table class="table table-bordered table-striped table-hover">
	<thead class="table-dark text-center">
		<tr>					
			<th>SL.NO.</th>				  
            <th>Table Name</th>
			<th>Tenants Name</th>							
			<th>Address</th>										
			<th>Action</th>
		</tr>
	</thead>
	<tbody>			
	<?php
	function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
	}

    $result = mysqli_query($conn, "SELECT * FROM company"); 
    if (mysqli_num_rows($result) > 0) {
        while ($row1 = mysqli_fetch_array($result)) {            
				?>
                <tr id="<?php echo $row1["id"]; ?>" class="text-center">
                    <td><?php echo $row1["id"]; ?></td>                    
                    <td><?php echo $row1["table_name"]; ?></td>                    
                    <td><?php echo $row1["tenants_name"]; ?></td>                   
                    <td><?php echo $row1["address"]; ?></td> 
                    <td>
				<a href="edit_tenants_list.php?id=<?php echo $row1['id']; ?>" style="text-decoration: none"><i class="fa fa-edit"></i>
				    </a>&nbsp;
					<a href="delete_tentants.php?id=<?php echo $row1['id']; ?>" style="text-decoration: none"><i class="fa fa-trash"></i>
				    </a>&nbsp;
                    </td>
                </tr>
                <?php 
					}
				}
                ?>
            </tbody>
        </table>
</div>