<?php
session_start();
$length = 5;
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$captcha_code = '';
for ($i = 0; $i < $length; $i++) {
    $captcha_code .= $chars[rand(0, strlen($chars)-1)];
}
$_SESSION['captcha'] = $captcha_code;

$width = 120;
$height = 40;
$image = imagecreate($width, $height);

$bg_color = imagecolorallocate($image, 230, 230, 230);
$text_color = imagecolorallocate($image, 0, 0, 0);

imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

$font_size = 5;
imagestring($image, $font_size, 20, 10, $captcha_code, $text_color);

header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
