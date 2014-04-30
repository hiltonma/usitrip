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

//取得图片的缩略图
function get_thumbnails($src_file_name,$head_string="thumb_"){
	$new_dir_file_src = dirname($src_file_name).'/'.$head_string.basename($src_file_name);
	$old_dir_file_src = $src_file_name;
	if(file_exists($new_dir_file_src)){ return $new_dir_file_src; }
	return $old_dir_file_src;
}
//取得图片的缩略图，最快方式，不检查图片是否存在
function get_thumbnails_fast($src_file_name){
	return str_replace('http://208.109.123.18/images/picture/detail_', 'http://208.109.123.18/images/picture/thumb_', $src_file_name);
}

// 裁剪图片
function out_image_cut($src_img, $new_file ,$dst_w=160, $dst_h=120){    
    $imgarr = getimagesize($src_img);  // 获取原图尺寸       
    $src_w = $imgarr[0];
    $src_h = $imgarr[1];
    $type = $imgarr['mime'];
    
    $dst_scale = $dst_h/$dst_w; //目标图像长宽比
    $src_scale = $src_h/$src_w; // 原图长宽比    
    
    if($src_scale>=$dst_scale){  // 过高
    $w = intval($src_w);
    $h = intval($dst_scale*$w);

    $x = 0;
    $y = ($src_h - $h)/3;
    }
    else{ // 过宽
    $h = intval($src_h);
    $w = intval($h/$dst_scale);

    $x = ($src_w - $w)/2;
    $y = 0;
    }    
    // 剪裁
    switch ($type)
    {
        case  'image/jpeg':        
            $source = imagecreatefromjpeg($src_img);            
            break ;
        case  'image/png':
            $source = imagecreatefrompng($src_img);
            break ;        
        default :
            echo '<script>alert("不支持的图片类型!")</script>';
            exit;
            break ; 
    }
    $croped=imagecreatetruecolor($w, $h);
    imagecopy($croped,$source,0,0,$x,$y,$src_w,$src_h);

    // 缩放
    $scale = $dst_w/$w;
    $target = imagecreatetruecolor($dst_w, $dst_h);
    $final_w = intval($w*$scale);
    $final_h = intval($h*$scale);
    imagecopyresampled($target,$croped,0,0,0,0,$final_w,$final_h,$w,$h);

    // 保存   
    switch ($type)
    {
        case  'image/jpeg':        
            imagejpeg($target, $new_file);
            break ;
        case  'image/png':
            imagepng($target, $new_file);
            break ;        
        default :
             echo '<script>alert("不支持的图片类型!")</script>';
            break ; 
    }    
    imagedestroy($target);
    
    return $new_file;
}


?>