<?php
require('global.php');
require(API_ROOT.'product_travel.global.php');
$where = " where pd.language_id='{$languages_id}' and pd.products_id = p.products_id and p.products_durations >='2' and p.products_durations_type < '1' ";
$pid = trim($_GET['pid'].'');
if(!$pid){
	message('please give me "pid" parameters!');
}
if($pid == 'all' && $_GET['open']=='1'){
	$where .= "";
}elseif(strpos($pid,',')!==false && $_GET['open']=='1'){
	$pid = explode(',',$pid);
	$pid = '\''.join("','",$pid).'\'';
	$where .= " and pd.products_id in({$pid}) ";
}elseif(strpos($pid,'-')!==false && $_GET['open']=='1'){
	$pid = explode('-',$pid);
	$pid[0] && $where .= " and pd.products_id >='{$pid[0]}' ";
	$pid[1] && $where .= " and pd.products_id <='{$pid[1]}' ";
}else{
	$pid = intval($_GET['pid']);
	if($pid<1){
		message('please give me "pid" parameters!');
	}
	$where .= " and pd.products_id ='{$pid}' ";
}
$dom = createDom();
$domroot = cel("updatedList",$dom);
set_time_limit(0);
$product_query = tep_db_query('select pd.products_id,pd.products_description from '. TABLE_PRODUCTS_DESCRIPTION . ' pd,'.TABLE_PRODUCTS.' p '.$where);
while($product = tep_db_fetch_array($product_query)){
	$travelData=array();
	$travelQuery = tep_db_query("SELECT * FROM `products_travel` WHERE `products_id`='{$product['products_id']}' and `langid`='{$languages_id}' ORDER BY  `travel_index` ASC");
	$isAccData = false;
	while($travel = tep_db_fetch_array($travelQuery)){
		if(!empty($travel['travel_content'])){
			$isAccData = true;
		}
		$travelData[$travel['travel_index']]=$travel;
	}
	$productNode = cel("product");
	$productNode->setAttribute("id",$product['products_id']);
	if(!count($travelData) || !$isAccData || $_GET['exec']=='1'){
		$travelData = formatTravelData($product['products_description']);
		tep_db_query('delete from products_travel where products_id="'.(int)$product['products_id'].'"');
		foreach($travelData as $travel){
			$travelSql = array();
			$travelSql[] = "`products_id`='{$product['products_id']}'";
			$travelSql[] = "`langid`='{$languages_id}'";
			foreach($travel as $tkey=>$tval){
				$travelSql[] = "`{$tkey}`='".mysql_real_escape_string($tval)."'";
			}
			tep_db_query('insert into products_travel set '.join(',',$travelSql));
		}
		$productNode->appendChild(cval('This product has been updated itinerary!'));
	}else{
		$productNode->appendChild(cval('This product has new itinerary!'));
	}
}
outDom($dom);
?>