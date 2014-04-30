<?php
/*
照片分享上传图片文件
1.先将文件存到/tmp
2.在用户填写了相关信息过后才存到images/reviews/
*/

$tmp_microtime = str_replace(array('.',' '),array('',''),microtime());
$new_name = 'detail_'.mt_rand().'_'.$tmp_microtime;

/*定义文件路径*/
$dir = $_SERVER['DOCUMENT_ROOT'].'/tmp/';
$http_url = 'http://'.$_SERVER['HTTP_HOST'].'/tmp/';

$headers = getallheaders();
$exc_name = preg_replace('/^.*\./','.',$headers['Image-Name']);
/*上传后文件名称*/
$new_name .= strtolower($exc_name);

$image_name = $dir.$new_name;
$file = fopen($image_name, 'wb');
if(fwrite($file, $GLOBALS['HTTP_RAW_POST_DATA'])=== FALSE){
	echo "0";
	exit();
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/functions/webmakers_added_functions.php');
imageCompression($image_name,250, str_replace('detail_','thumb_',$image_name));

echo "1"."|".$new_name."|".$dir."|".$image_name."|".$http_url.$new_name."|".$http_url.str_replace('detail_','thumb_',$new_name);	//状态码|文件名|目录名|全名（目录+文件）|图片网址|图片缩略图
fclose($file);

?>