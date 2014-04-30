<?php
#输出缩图文件，目前适用于jpeg和png图像，暂不支持gif图片
//$input_file = 'zhh04.jpg';
//$out_file = 'zhh04_sm.jpg';
//$max_width=150;
//$max_height=100;

function out_thumbnails($input_file, $out_file, $max_width=100, $max_height=100){	
	// File and new size
	$filename = $input_file;
	$out_file = $out_file;
	// Get image sizes and mime type 
	$image_array = getimagesize($filename);
	//如果图片是非png、gif和jpeg图片则停止继续
	if($image_array[2]!=1 && $image_array[2]!=2 && $image_array[2]!=3){
		return false;
	}
	
	list($width, $height) = $image_array;
	$newwidth = $width;
	$newheight = $height;
	//长度比的最大值
	$max_value = intval(($max_height/$max_width)*100)/100;
	//计算图像长宽比
	$ratio_value = intval(($height/$width)*100)/100;
	if($max_value >= $ratio_value){
		if($width > (int)$max_width){	//长比高大
			$newwidth = (int)$max_width;
			$newheight = (int)($newwidth * $ratio_value);
		}
	}else{
		if($height > (int)$max_height){
			$newheight = (int)$max_height;
			$newwidth = (int)($newheight/$ratio_value);
		}
	}
	
	// Load
	if(function_exists('imagecreatetruecolor')){
		$thumb=imagecreatetruecolor($newwidth, $newheight);//创建新图片并指定大小
	}else{
		$thumb = imagecreate($newwidth, $newheight);
	}
	switch ($image_array[2]) {	//取得图片类型
		case 1:   $source = @imagecreatefromgif($filename);  break;
		case 2:   $source = @imagecreatefromjpeg($filename);  break;
		case 3:   $source = @imagecreatefrompng($filename);  /*imagesavealpha($filename, true);*/  break;
	}

	if(function_exists('imagecopyresampled')){
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);	//不失真
	}else{
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 	//失真
	}
	
	switch ($image_array[2]) {	//输出图片到文件$dstFile
		case 1:   imagegif($thumb,$out_file);  break;
		case 2:   imagejpeg($thumb,$out_file,100);   break;
		case 3:   /*imagesavealpha($thumb, true);*/ imagepng($thumb,$out_file);  break;
	}
	@imagedestroy($thumb);
	@imagedestroy($source);
	return $out_file;
}
//echo out_thumbnails($input_file, $out_file, $max_width, $max_height);


	

?>