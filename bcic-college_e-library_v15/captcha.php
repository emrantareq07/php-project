<?php
session_start(); // Start the session

// Generate a random 5-digit number for the CAPTCHA
$text = rand(10000, 99999);
$_SESSION["vercode"] = $text;

// Set the dimensions of the image
$height = 25;
$width = 65;

// Create the image
$image_p = imagecreate($width, $height);
$black = imagecolorallocate($image_p, 0, 0, 0);
$white = imagecolorallocate($image_p, 255, 255, 255);

// Fill the background with white
imagefilledrectangle($image_p, 0, 0, $width, $height, $white);

// Set the font size and add the text to the image
$font_size = 14;
imagestring($image_p, $font_size, 5, 5, $text, $black);

// Set the content type header to output the image as JPEG
header('Content-Type: image/jpeg');

// Output the image
imagejpeg($image_p);

// Free up memory
imagedestroy($image_p);
exit; // Stop further execution to avoid outputting anything else
?>