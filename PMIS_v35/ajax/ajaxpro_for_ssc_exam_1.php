<?php
include('../db/db.php');
// for ssc
$id =$_POST["category_id"];
if(isset($id)){
	$i;
 if ($id == 1 || $id == 2 || $id == 4 || $id == 5 || $id == 32 ) {
     $i = 1;
} elseif($id == 33){
    $i = 4;
}
else {
     $i = 2;
 }
$sql="SELECT * from subject_ssc where subject_id= $i OR id=1";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
echo "<option value='".$row['id']."'>".$row['name']."</option>";
    }
}

// for hsc
$hsc_examid =$_POST["hsc_examid"];
if(isset($hsc_examid)){
	$i_hsc;
     if ($hsc_examid == 1 || $hsc_examid == 7 || $hsc_examid == 8 || $hsc_examid == 9 || $hsc_examid == 12 || $hsc_examid == 11) {
         $i_hsc = 1;
    } 
    elseif ($hsc_examid == 13) {
        $i_hsc = 3;
    }
    else {
         $i_hsc = 2;
     }

$sql_hsc="SELECT * from subject_ssc where subject_id= $i_hsc OR id=1";
$result_hsc = mysqli_query($conn, $sql_hsc);
?>
<!-- <option value="">Select </option> -->
<?php
while ($row11 = mysqli_fetch_array($result_hsc)) {
echo "<option value='".$row11['id']."'>".$row11['name']."</option>";
    }
}

// for honors
$honors_examid =$_POST["honors_examid"];
if(isset($honors_examid)){
	$i_hsc;
 if ($honors_examid == 18 || $honors_examid == 1) {
     $i_hsc = 0;
}
else {
     $i_hsc = 1;
 }

$sql_honors="SELECT * from subject_graduation where exam_id= $i_hsc OR id=1";
$result_honors = mysqli_query($conn, $sql_honors);
?>
<!-- <option value="">Select </option> -->
<?php
while ($row12 = mysqli_fetch_array($result_honors)) {
echo "<option value='".$row12['id']."'>".$row12['name']."</option>";
    }
}

// for masters
$masters_examid =$_POST["masters_examid"];
if(isset($masters_examid)){
    $i_hsc;
 if ($masters_examid == 29|| $masters_examid == 1) {
     $i_hsc = 0;
} else {
     $i_hsc = 1;
 }

$sql_masters="SELECT * from subject_graduation where exam_id= $i_hsc OR id=1";
$result_masters = mysqli_query($conn, $sql_masters);
?>
<!-- <option value="">Select </option> -->
<?php
while ($row13 = mysqli_fetch_array($result_masters)) {
echo "<option value='".$row13['id']."'>".$row13['name']."</option>";
    }
} 
?>


