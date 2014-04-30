<?php
ini_set('memory_limit','256M');
header("Content-type: text/html; charset=gb2312");
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

$action = true;
if($action!=true){
	die('$action 动作没有打开');
}


/*
只转换php文档和html或htm或js或css文档
如非php文档里面有big5或utf-8或gb2312的则不替换
*/
$old_file_name = array();
$dir_array = array();
for($j=0; $j<1; $j++){
	$old_file_name[$j] = dirname(__FILE__).'/'.($j+1).'_usitrip_com.20090901.sql';
	$dir_array[$j] = '/'.($j+1).'_usitrip_com.20090901.gb.sql';
}

$i_max = count($dir_array);
//$i_max =1;
echo 'Begin...<br>';
for($i=0; $i<$i_max; $i++){
	$filse_name = dirname(__FILE__).$dir_array[$i];
	$string = file_get_contents($old_file_name[$i]);
	if($string==false){
		echo 'Can not get conters'.$old_file_name[$i].'<br>';
	}
	$string = iconv('big5','gb2312'.'//IGNORE',$string);
	//$string = stripslashes($string);
	/*$string = preg_replace("/许\\/","许",$string);
	$string = preg_replace("/功\/","功",$string);
	$string = preg_replace("/蓋\/","蓋",$string);
	$string = preg_replace("/餐\/","餐",$string);
	$string = preg_replace("/崴\/","崴",$string);
	$string = preg_replace("/玥\/","玥",$string);
	$string = preg_replace("/谷\/","谷",$string);
	$string = preg_replace("/娉\/","娉",$string);
	$string = preg_replace("/罡\/","罡",$string);
	
	$string = ereg_replace('\"','"',$string);
	*/

	$change = true; 
	if($change == true){
		$handle = fopen($filse_name, 'w+b');
		if($handle){
			fwrite($handle, $string);
			fclose($handle);
			echo 'Changed '.$filse_name.'<br>';
		}else{
			echo 'Can not fopen'.$filse_name;
			exit;
		}
	}
	sleep(0);
			
}

echo 'Changed Done.<br>';
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <input type="text" name="textfield" />
</form>
</body>
</html>
