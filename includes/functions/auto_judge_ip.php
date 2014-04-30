<?php
function auto_judge_ip($ip){
	$ip_sql = tep_db_query("select begin_n from china_ip_range where begin_n<=inet_aton('".(string)$ip."') AND end_n>=inet_aton('".(string)$ip."') limit 1 ");
	$ip_sql_row = tep_db_fetch_array($ip_sql);
	return $ip_sql_row['begin_n'];
}
//х║╣цД╞ююфВсОят
function get_browser_lange(){
	$run ='';
	if(preg_match('/zh\-cn/',strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']))){
		$run = 'zh-cn';
	}
	return $run;
}
?>