<?php

$NewImage =imagecreatefromjpeg("image/captcha_img.jpg");//image create by existing image and as back ground 

$LineColor = imagecolorallocate($NewImage,192,192,192);//line color 
$TextColor = imagecolorallocate($NewImage, 0, 0, 0);//text color-white

imageline($NewImage,18,1,1,18,$LineColor);//create line 1 on image 
//imageline($NewImage,28,1,1,28,$LineColor);//create line 1 on image 
imageline($NewImage,38,1,1,38,$LineColor);//create line 1 on image 
//imageline($NewImage,48,1,1,48,$LineColor);//create line 1 on image 
imageline($NewImage,58,1,1,58,$LineColor);//create line 1 on image 
//imageline($NewImage,68,1,1,68,$LineColor);//create line 1 on image 
imageline($NewImage,78,1,1,78,$LineColor);//create line 1 on image 
//imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image 

imagestring($NewImage, 5, 15, 5, base64_decode($_GET['code']), $TextColor);// Draw a random string horizontally 

//$_SESSION['key'] = $ResultStr;// carry the data through session

header("Content-type: image/jpeg");// out out the image 

imagejpeg($NewImage);//Output image to browser 

?>
