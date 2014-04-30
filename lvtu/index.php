<?php
error_reporting(E_ALL);// &~E_NOTICE);
ini_set('display_errors',1);
ini_set('zlib.output_compression', 'On');
/* 设置时间所在地 */
//date_default_timezone_set('PRC');
date_default_timezone_set('Asia/Shanghai');
header("HTTP/1.0 200 OK");
header("Status: 200 OK");
require 'public/Global.php';
try {
	Controller::handle()->execute();
} catch (Exception $e) {
	header('content-type:text/html;charset=gb2312');
	echo $e->getMessage();
}
?>
