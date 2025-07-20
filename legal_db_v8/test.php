<?php
// Database connection info 
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = "legal_db";
$conn = mysqli_connect($host, $username, $password, $dbname);


function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
}

//$k=187;
// for($i=1;$i<=560;$i++){
	
// 	$englishNumbers=$i;
// 	$banglaNumbers = englishToBanglaNumber($englishNumbers);
// $sql="UPDATE legal_tbl set id_bn='$banglaNumbers' where id= '$englishNumbers'";
// 	// $sql="UPDATE legal_tbl set hearing_result='বিচারাধীন' where id= '$i'";
// // $sql="UPDATE legal_tbl set id=$k where id= '$i'";

           
//     $result=mysqli_query($conn,$sql);
//     //$k++;

// }

?>




<?php


 $query1 = "SELECT * FROM legal_tbl where hearing_result='বিচারাধীন' ";
$result1 = mysqli_query($conn, $query1);
$i=1;

?>
 	                           <table cclass="table table-bordered text-white fw-bold" style="width: 100%;">
                                    <thead>
                                      <tr >
                                        <!-- <th class="rounded-pill bg-dark">আদালত</th>
                                        <th class="rounded-pill bg-dark">মোট</th>
                                        <th class="rounded-pill bg-dark">পক্ষে</th>
                                        <th class="rounded-pill bg-dark">বিপক্ষে</th>
                                        <th class="rounded-pill bg-dark">বিচারাধীন</th> -->

                                        <th >আদালত</th>
                                        <th >মোট</th>
                                        <th >পক্ষে</th>
                                       
                                      </tr>
                                    </thead>
                                    <tbody>

<?php

 while ($row6 = mysqli_fetch_assoc($result1)) {

 	$hearing_result = $row6['hearing_result'];
	$englishNumbers=$i;
	$banglaNumbers = englishToBanglaNumber($englishNumbers);



?>

  <tr>

 
    <td><?php echo $englishNumbers; ?></td>
    <td><?php echo $banglaNumbers; ?></td>
    <td><?php echo $hearing_result; ?></td>
  
    
  </tr>

                                      
                                 


<?php

$i++;
 }


?>

   </tbody>
                                  </table>




