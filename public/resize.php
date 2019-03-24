<?php
//$_GET['data'] = "uploaded/noimage.png;200;300";
$data = explode(";", $_GET['data']);
$filename = $data[0];
if (mb_substr($filename, 0, 1) == '/') $filename = mb_substr($filename, 1);
$maxx = $data[1];
$maxy = $data[2];
$img = file_get_contents($filename);
//$handler = fopen($filename, 'rb');
//$img = fread($handler, filesize($filename));
$image = imagecreatefromstring($img);
$width = imagesx($image);
$height = imagesy($image); 
$kx = $width / $maxx;
$ky = $height / $maxy;
if ($kx > 1 && $ky > 1) {
	if ($kx == $ky) {
		$new_width = $width / $kx;
		$new_height = $height / $ky;
	} elseif ($kx > $ky) {
		$new_width = $width / $kx;
		$new_height = $height / $kx;
	} elseif ($kx < $ky) {
		$new_width = $width / $ky;
		$new_height = $height / $ky;
	}
} elseif ($kx > 1) {
	$new_width = $width / $kx;
	$new_height = $height / $kx;
} elseif ($ky > 1) {
	$new_width = $width / $ky;
	$new_height = $height / $ky;
} else {
	$new_width = $width;
	$new_height = $height;
}
$new_height = round($new_height);
$new_width = round($new_width);
$thumb = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
header('Content-type: image/png');
imagepng($thumb);