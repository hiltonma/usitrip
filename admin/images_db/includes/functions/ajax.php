<?php
//ajax±à¼­×ª»»Æ÷
function code_change($str){
	global $ajax;
	if($ajax==true){
		$str = iconv(CHARSET,"utf-8",$str);
	}
	return $str;
}

function code_change_db($str){
	global $ajax;
	if($ajax==true){
		$str = iconv("utf-8",CHARSET,$str);
	}
	return $str;
}
function auto_conversion_post(){
	global $ajax;
	if($ajax==true){
		foreach( $_POST as $key => $value){
			$_POST[$key] = code_change_db($value);
		}
	}
	
	return $_POST;
}
auto_conversion_post();
?>