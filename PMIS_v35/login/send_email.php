<?php
include('smtp/PHPMailerAutoload.php');
$to1='emrantareq09@gmail.com';
$subject1='text';
$msg1='1234';

echo smtp_mailer($to1,$subject1,$msg1);
function smtp_mailer($to,$subject, $msg){
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    //$mail->SMTPDebug = 2; 
    $mail->Username = "bcic.info@gmail.com";
    $mail->Password = "mbcdgmmjwiclhopo";
    $mail->SetFrom("bcic.info@gmail.com");
    $mail->Subject = $subject;
    $mail->Body =$msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
    ));
    if(!$mail->Send()){
        echo $mail->ErrorInfo;
    }else{
        return 'Sent';
    }
}
?>