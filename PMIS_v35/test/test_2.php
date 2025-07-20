<?php 

$tomorrow = date('Y-m-d');
$dob='1987-02-06';
$prl = date('Y-m-d', strtotime('-1 day', strtotime($dob. '+59 years')));
$pension = date('Y-m-d', strtotime($prl. '+1 years'));

echo $prl.'<br>';echo $pension;

?>