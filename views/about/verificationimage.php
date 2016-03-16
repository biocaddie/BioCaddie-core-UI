<?php

header('Content-type: image/jpeg');
$width = 50;
$height = 24;
$my_image = imagecreatetruecolor($width, $height);
imagefill($my_image, 0, 0, 0xFFFFFF);
for ($c = 0; $c < 40; $c++){
    $x = rand(0,$width-1);
    $y = rand(0,$height-1);
    imagesetpixel($my_image, $x, $y, 0x000000);
}

$x = rand(1,10);
$y = rand(1,10);
$rand_string = rand(1000,9999);
imagestring($my_image, 5, $x, $y, $rand_string, 0x000000);
setcookie('tntcon',(md5($rand_string).'a4xn'));
imagejpeg($my_image);
imagedestroy($my_image);

?>