<?php
!defined('_MODE_KEY') && exit('Access error!');
if(!$isExpertsSelf){
	showmsg('访问错误\r\n您不是该专家,您不能访问这里！',tep_href_link($baseUrl,"mod=home&uid={$uid}"));
}
$crumbTitle = '由我推荐而形成的订单';
$the_title = $crumbTitle.' - '.$the_title;
$breadcrumb->add(db_to_html($crumbTitle),tep_href_link($baseUrl,"uid={$uid}&mod=affiliate"));

//======================================================
$where = " where a.affiliate_id = '{$uid}'	and a.affiliate_isvalid = 1 ";
if($_GET['sd']){
	$where .= " and a.affiliate_date>='{$_GET['sd']} 00:00:00'";
}
if($_GET['ed']){
	$where .= " and a.affiliate_date<='{$_GET['ed']} 00:00:00'";
}

$sallow = "'".join("','",$affiliateStatusAllow)."'";
$sumsql = "select  SUM( a.affiliate_payment ) as payment 
	from " . TABLE_AFFILIATE_SALES . " a 
    left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
    left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
    {$where} and o.orders_status in({$sallow})";
$query = tep_db_get_one($sumsql);
$affiliatePayment = $currencies->display_price($query['payment'], '');

$countsql = "select  count(o.orders_id) as rows 
	from " . TABLE_AFFILIATE_SALES . " a 
    left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
    left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
    {$where}";
$sql = "select  a.*, o.orders_status as orders_status_id, os.orders_status_name as orders_status 
	from " . TABLE_AFFILIATE_SALES . " a 
    left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
    left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
    {$where} order by a.affiliate_date DESC";

$query = tep_db_query($countsql);
$query = tep_db_fetch_array($query);
$total=(int)$query["rows"];
$db_perpage = 15;
$pages=makePager($total,'page',$db_perpage,false);
$limit=" LIMIT ".($page-1)*$db_perpage.",$db_perpage;";

$query = tep_db_query($sql.$limit);
$affiliateData = array();
while ($rt = tep_db_fetch_array($query)){
	$rt['affiliate_date'] = date('m/d/Y',strtotime($rt['affiliate_date']));
	$rt['affiliate_payment'] = $currencies->display_price($rt['affiliate_payment'], '');
	$rt['affiliate_value'] = $currencies->display_price($rt['affiliate_value'], '');
	$rt['affiliate_products'] = tep_get_product_name_by_order_id($rt['affiliate_orders_id']);
	$affiliateData[]=$rt;
}
?>