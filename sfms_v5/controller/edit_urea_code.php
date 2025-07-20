<?php
session_start();
$table=$_SESSION['username'];

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

 $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = "";
  $dbname = 'dfms_db';

  $link= mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  $conn =mysqli_select_db($link,$dbname);
// include('db/db.php');

if(isset($_POST['update'])){

$id =$_POST['id'];
$daily = $_POST['daily'];
$date  = $_POST['date'];
            
$month=date('m',strtotime($date));
$year=date('Y',strtotime($date));

if($month==7 || $month==8 || $month==9 || $month==10 || $month==11 || $month==12 ){
  $year11=$year;
}
else{
  $year11=$year-1;
}

$yearrange1="$year11-07-01";
$year11 = $year11+1;
$yearrange2="$year11-06-30";


  $x=mysqli_query($link,"SELECT MIN(id) AS minimum FROM $table group by month_id");

  $ax=0;
  while($bx=mysqli_fetch_assoc($x)){
     $dx=$bx['minimum'];
  if($dx==$id){

      $ax=$id;
  }
 }

$c;

// check 1st date of month 
if($ax==$id){

$am=mysqli_query($link,"SELECT * FROM $table where id=$id");
$bm=mysqli_fetch_assoc($am);
$c=$bm['month_id'];

$sql=mysqli_query($link,"SELECT MIN(id) AS minimum_1 FROM $table where date between '$yearrange1' and '$yearrange2'");
    
    while($row1=mysqli_fetch_assoc($sql)){
       $minimum_1=$row1['minimum_1'];
       //echo $minimum_1;     
      }         
 
   // fiscal year first date of month
   if($id==$minimum_1){
    mysqli_query($link,"UPDATE $table SET daily='$daily' , monthly='$daily', yearly='$daily'  where id=$id");
   }
   else{
    // previous month yearly value 
    $ag=mysqli_query($link,"SELECT * FROM $table where id=($id-1)");
    $bg=mysqli_fetch_assoc($ag);
    //echo $b['id'];
    //$c=$b['id'];
    $dg=$bg['yearly'];
    $ju=$dg+$daily;

     mysqli_query($link,"UPDATE $table SET daily='$daily' , monthly='$daily',yearly='$ju' where id=$id");

   }     
}
// any date of month 1st or not 
elseif ($ax!=$id){
$a=mysqli_query($link,"SELECT * FROM $table where id=$id");
    $b=mysqli_fetch_assoc($a);
    //echo $b['id'];
    //$c=$b['id'];
    $c=$b['month_id'];
    $d=$b['id'];
    //echo $c;

    $e=mysqli_query($link,"SELECT * FROM $table where id=($id-1)");
    $f=mysqli_fetch_assoc($e);
    
    $g=$f['monthly'];
    $g2=$f['yearly'];

    $h=$g+$daily;
    $j=$g2+$daily;
     //echo $e;
     mysqli_query($link,"UPDATE $table SET daily='$daily' , monthly='$h' , yearly='$j' where id=$id");

     }

//update same id then update related all id
    $p=$id+1;

    $m=mysqli_query($link,"SELECT * FROM $table where month_id=$c");
    $rowcount = mysqli_num_rows($m);
      
      $x2=mysqli_query($link,"SELECT MIN(id) AS min FROM $table where month_id=$c");
      $bm=mysqli_fetch_assoc($x2);
      $dn=$bm['min'];
      $ml=$p-$dn;

      $rowcount=$rowcount-$ml;
       
      while($rowcount!=0){
      $a1=mysqli_query($link,"SELECT * FROM $table where id=($p-1)");
      $b1=mysqli_fetch_assoc($a1);
    //echo $b['id'];
    //$c=$b['id'];
    $d1=$b1['monthly'];
    $d11=$b1['yearly'];

    $a2=mysqli_query($link,"SELECT * FROM $table where id=$p");
    $b2=mysqli_fetch_assoc($a2);
    //echo $b['id'];
    //$c=$b['id'];
    $d2=$b2['daily'];
    $T=$d2+$d1;
    $tk=$d2+$d11;

    mysqli_query($link,"UPDATE $table SET  monthly='$T' , yearly='$tk' where id=$p");
    $p++;
    $rowcount--;
       }

    $h=$p;
    //echo $h;

// for find max id for update yearly date that fiscal year
  $m9=mysqli_query($link,"SELECT max(id) as max_id FROM $table where date between '$yearrange1' and '$yearrange2'");
  $b9=mysqli_fetch_assoc($m9);
  $rowcount5=$b9['max_id'];

    // $rowcount5 = mysqli_num_rows($m9);
//echo $rowcount5;
    while($h<=$rowcount5){  

    $a4=mysqli_query($link,"SELECT * FROM $table where id=($h-1)");

    $b4=mysqli_fetch_assoc($a4);
  
    $d4=$b4['yearly'];
     //echo $d4;
    $a41=mysqli_query($link,"SELECT * FROM $table where id=$h");
    $b41=mysqli_fetch_assoc($a41);
    //echo $b['id'];
    $c=$b41['month_id'];
    $d41=$b41['daily'];
    $d111=$d4+$d41;

    mysqli_query($link,"UPDATE $table SET yearly='$d111' where id=$h");

      $s=mysqli_query($link,"SELECT * FROM $table where month_id=$c");

       $rowcount1 = mysqli_num_rows($s);
       $rowcount1=$rowcount1-1;
       $h1=$h+1;
      
       while($rowcount1!=0){

     $a7=mysqli_query($link,"SELECT * FROM $table where id=($h1-1)");
     $b7=mysqli_fetch_assoc($a7);
    //echo $b['id'];
    //$c=$b['id'];
    $d7=$b7['yearly'];
    $d71=$b7['monthly'];

    $a5=mysqli_query($link,"SELECT * FROM $table where id=$h1");
    $b5=mysqli_fetch_assoc($a5);
    //echo $b['id'];
    //$c=$b['id'];
    $d5=$b5['daily'];

    $T1=$d7+$d5;
    $T11=$d71+$d5;

    mysqli_query($link,"UPDATE $table SET monthly='$T11',  yearly='$T1' where id=$h1");

    $rowcount1--;
    $h1++;
       }
    $h=$h1;

}
// header("location:edit_urea.php?updated=successfully");

  echo '<script type="text/javascript">';
  echo 'alert("Data Updated successfully!!!");';
  echo 'window.location.href="view_urea_report.php"';
  echo '</script>';
 
    }

?>


