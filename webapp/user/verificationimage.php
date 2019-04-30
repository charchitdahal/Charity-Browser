<?php
// ----------------------------------------- 
//  The Web Help .com
// ----------------------------------------- 

header('Content-type: image/jpeg');

$width = 250;
$height = 50;

$my_image = imagecreatetruecolor($width, $height);

imagefill($my_image, 0, 0, 0xFFFFFF);

// add noise
for ($c = 0; $c < 40; $c++){
	$x = rand(0,$width-2);
	$y = rand(0,$height-1);
	imagesetpixel($my_image, $x, $y, 0x09F);
	}

$x = rand(1,10);
$y = rand(1,10);

$rand_string = rand(0,9);
$rand_ascii = chr(rand(ord("a"), ord("z")));
//$rand_caps = chr(rand(ord("A"), ord("Z")));
$rand_string2 = rand(0,99);


$rand_i = $rand_string . $rand_ascii . $rand_string2 ;
imagestring($my_image, 9, $x, $y, $rand_i, 0x09F);

setcookie('tntcon',(md5($rand_i).'a4xn'));

imagejpeg($my_image);
imagedestroy($my_image);
?>