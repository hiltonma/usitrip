<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once('includes/application_top.php');
header("Content-type: text/html; charset=".CHARSET);
//成都IP暂时不开放{
if(tep_not_null($_GET['ip'])){
	$chengdu_ip = $_GET['ip']."\n";
	if($handle = fopen($cd_ips_file, 'ab')){
		if(flock($handle , LOCK_EX)){
			fwrite($handle, $chengdu_ip);
			flock($handle , LOCK_UN);
		}
		fclose($handle);
	}
}
//成都IP暂时不开放}
?>