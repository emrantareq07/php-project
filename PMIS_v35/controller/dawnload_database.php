<?php


// if(isset($_POST['submit'])){
// include_once('Mysqldump.php');

// $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=pmis_db', 'root', '');
// $f=date('d-m-Y');
// // $f = 'pmis_db_' . date('d-m-Y');

// $dump->start("../db/pmis_db_$f.sql");    

// echo '<script>window.location.href = "../admin/utilities.php";</script>';
//         exit();
// }



function createDatabaseBackup($outputFile){
 
include_once('Mysqldump.php');
include('../smtp/PHPMailerAutoload.php');

$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=pmis_db', 'root', '');
 $dump->start($outputFile);


$dump1 = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=pmis_db', 'root', '');
$f=date('d-m-Y');
$dump1->start("../db/pmis_db_$f.sql");
//$dump->start("D:/database/pmis_db_$f.sql");

$mail=new PHPMailer(true);
$mail->isSMTP();
$mail->Host="smtp.gmail.com";
$mail->Port=587;
$mail->SMTPSecure="tls";
$mail->SMTPAuth=true;

$mail->Username = "pmisict@gmail.com";  
$mail->Password = "jhuimijoiytxcbrw";
$mail->SetFrom("pmisict@gmail.com");

$mail->addAddress("emrantareq09@gmail.com");

$mail->IsHTML(true);
$mail->Subject="BCIC PMIS ".$f;
$mail->Body="Database Backup";
$mail->AddAttachment("../db/pmis_db_$f.sql");
$mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
));
if($mail->send()){
        //echo "Please check your email id for password";
}else{
        //echo "Error occur";
}

}

if (isset($_POST['submit'])) {
    $backupFilename = '../db/pmis_db_' . date('d-m-Y') . '.sql';
    createDatabaseBackup($backupFilename);
     header('Content-Type: application/octet-stream');
     header('Content-Disposition: attachment; filename="' . $backupFilename . '"');
    readfile($backupFilename);

    exit;
}

?>
