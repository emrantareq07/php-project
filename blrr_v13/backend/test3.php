<?php 
$a = 1;
$b = 2;
$c = '';
$d = 3;

$result = implode(', ', array_filter([$a, $b, $c, $d], fn($value) => $value !== ''));

echo $result;
?>