<?php
!defined('_MODE_KEY') && exit('Access error!');
if($isExperts){
	$isFromLogin = $_SERVER['HTTP_REFERER'];
	if(strpos($isFromLogin,HTTPS_SERVER.'/login.php') !== false){
		$query = tep_db_get_one('SELECT e.uid FROM `experts_remarks` e,customers c 
								WHERE e.uid = '.(int)$customer_id.' and e.uid = c.customers_id and c.customers_group="1" LIMIT 1 ');
		if($query['uid'] == $customer_id){
			ObHeader(tep_href_link($baseUrl,'mod=home&uid='.$customer_id));
			exit;
		}
	}
}
$expertsData = array();
if($isExperts){
	$sql = "SELECT uid FROM `experts_remarks` where `uid`='{$customer_id}'";
	$query = tep_db_get_one($sql);
	if(!$query){
		$tmp=array();
		$tmp['photo'] = getPhoto('');
		$tmp['uid'] = $customer_id;
		$tmp['name'] = $customer_email_address;
		$tmp['sex'] = $customer_first_name;
		$tmp['remarks'] = 'ÇëÌîÐ´ÄúµÄ¡°×¨¼Ò¼ò½é¡±';
		$tmp['expertslef'] = ($isExperts && $customer_id==$tmp['uid'])?true:false;
		$expertsData[$customer_id]=$tmp;
	}
}
$osCsid = isset($_GET['osCsid'])?$_GET['osCsid']:'';
$order='';
$isExperts && $order = "CASE WHEN e.`uid`='{$customer_id}' THEN 0 ELSE 1 END,";
$where = " where e.uid = c.customers_id and c.customers_group='1' ";
$word = trim($word).'';
$_db_word = html_to_db($word);
if($word!=''){
	$_db_word = addslashes(tep_db_prepare_input($_db_word));
	$where .= " and (e.`name` like Binary '%".$_db_word."%' or e.`remarks` like Binary '%".$_db_word."%') ";
}

$db_perpage = 5;

$countsql = "SELECT count(e.`uid`) as rows FROM `experts_remarks` e,customers c {$where}";
$query = tep_db_get_one($countsql);
$total=(int)$query["rows"];
$pages=makePager($total,'page',$db_perpage,false);
//echo $pages;
$limit=" LIMIT ".($page-1)*$db_perpage.",$db_perpage;";
$sql = "SELECT e.* FROM `experts_remarks` e,customers c {$where} order by {$order} uid {$limit}";
$query = tep_db_query($sql);

$experts_uids = array();
while ($rt = tep_db_fetch_array($query)){
	$experts_uids[] = $rt['uid'];
	$rt['photo'] = getPhoto($rt['photo']);
	//$rt['name'] = str_replace($_db_word,"<i>{$_db_word}</i>",$rt['name']);
	//$rt['remarks'] = str_replace($_db_word,"<i>{$_db_word}</i>",$rt['remarks']);
	$rt['expertslef'] = ($isExperts && $customer_id==$rt['uid'])?true:false;
	$expertsData[$rt['uid']]=$rt;
}
if(count($experts_uids)){
	$experts_uids = "'".join("','",$experts_uids)."'";
	$query = tep_db_query("SELECT * FROM `experts_writings_type` WHERE `uid` in({$experts_uids}) order by tid");
	while ($rt = tep_db_fetch_array($query)){
		$expertsData[$rt['uid']]['writings_type'][$rt['group_id']][] = $rt;
	}
}

if($word==''){
		
}
//echo '<pre>';
//print_r($expertsData);
?>