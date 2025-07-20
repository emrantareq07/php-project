<?php
//increment_update.php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];


include('db/db.php');

  
 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id']))
{
	$emp_id=$_SESSION['emp_id'];

	$sql=" CREATE TRIGGER update_basic_trigger BEFORE UPDATE ON job_desc
	FOR EACH ROW
	BEGIN
  -- Check if the next_increment_date is being updated to '01-07-2023'
  IF NEW.next_increment_date = '2023-07-01' THEN
    -- Retrieve the next row's basic value
    SET NEW.basic = (
      SELECT basic 
      FROM basic 
      WHERE id > NEW.id 
      ORDER BY id ASC 
      LIMIT 1
    );
  END IF;
END//";
	if(mysqli_query($conn,$sql))
	{
		//echo "Update successfully";
		header("location:edit_child_info.php?submitted=successfully");
			
	}
	else
		print mysqli_error();
	//echo 'invalid';

//}
	}
  ?>