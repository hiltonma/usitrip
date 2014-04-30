<?php
/**
 * 服务端运行的Cron脚本，每分钟执行一次。If you will edit the file, Please contact Howard.
 * @author Howard
 * @modify by Howard at 2011-06-18 00:35:03
 */
ini_set('display_errors', '1'); 
error_reporting(E_ALL & ~E_NOTICE);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/configure_local.php')){
	  include('includes/configure_local.php');
  }elseif(file_exists('includes/local/configure.php')){
	  include('includes/local/configure.php');
  }else{
// include server parameters
	include('includes/configure.php');
  }

$log_name = basename(__FILE__,'.php').'_logs.txt';
$file = DIR_FS_CATALOG."tmp/".$log_name;


if($_SERVER['SERVER_PROTOCOL']=="HTTP/1.1"){
}else{
	$smsajax = true;	
}

$alarm = false;

//如果系统内存小于100M则报警 {===============================================
$freeNum = 100;	//内存报警阀值 M
/*
             total       used       free     shared    buffers     cached
Mem:          3952       2449       100          0         32       1516
-/+ buffers/cache:        899       3052
Swap:         3999         82       3917
*/

$timeNum = 300;
if($files = file($file)){
	//print_r($files);
	for($i=sizeof($files); $i>=0; $i--){
		$str = trim($files[$i]);
		if(strpos($str,'SMS:')!==false){
			$lastTime = str_replace('SMS:','',$str);
			$timeNum = time()-strtotime($lastTime);
			//echo $timeNum."\n";
			break;
		}
	}
}
if($handle = popen('free -m', 'r')){
	//echo "'$handle'; " . gettype($handle) . "\n";
	$read = fread($handle, 2096);
	$read_array = explode("\n",$read);
	
	$r0s = explode(" ",preg_replace('/[[:space:]]+/',' ',$read_array[0]));
	$r1s = explode(" ",preg_replace('/[[:space:]]+/',' ',$read_array[1]));
	if($r0s[3]=='free' && $r1s[3]<$freeNum && $timeNum >= 300){	//隔5分钟发一次
		$alarm = true;
	}
	pclose($handle);
}

//如果系统内存小于100M则报警 }===============================================


if($alarm == true){
	$dir = dirname(__FILE__);
	define('DIR_FS_DOCUMENT_ROOT',str_replace('/admin','/',$dir));
	include($dir.'/includes/classes/cpunc_sms.php');
	
	$strMobile = '18982114235';
	$content = '系统报警TFF '.$_SERVER['SSH_CONNECTION']."\n".$r0s[3].":".$r1s[3];
	$chartset = 'GB2312';
	
	define('CPUNC_SWITCH','true');
	define('CPUNC_ID','xmzhh2000');
	define('CPUNC_PWD','tIFf19lUmps');
	
	$cpunc = new cpunc_SMS;
	$done = $cpunc->SendSMS($strMobile,$content, $chartset);

	$echo_str = 'SMS:'.date("Y-m-d H:i:s")."\n";
	
	
	//写日志
	if($handle = @fopen($file, 'ab')){
		if(flock($handle, LOCK_EX)){
			fwrite($handle, $echo_str);
			flock($handle, LOCK_UN);
		}
		fclose($handle);
	}
	echo $echo_str;

exit;
}

?>