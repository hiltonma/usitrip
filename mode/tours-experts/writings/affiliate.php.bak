<?php
!defined('_MODE_KEY') && exit('Access error!');

$products_id = intval($_GET['products_id']);
$uid = intval($_GET['uid']);
$aid = intval($_GET['aid']);
$sql = "SELECT * FROM `experts_writings_products` WHERE `aid`='{$aid}' and `uid`='{$uid}' and `products_id`='{$products_id}'";
$writings_products = tep_db_get_one($sql);
if(!$writings_products){
	!$_SERVER['HTTP_REFERER'] && $_SERVER['HTTP_REFERER'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings");
	ObHeader($_SERVER['HTTP_REFERER']);
}else{
	$sql = "UPDATE `experts_writings_products` SET `hits`=`hits`+1 WHERE `aid`='{$aid}' and `uid`='{$uid}' and `products_id`='{$products_id}'";	
	tep_db_query($sql);
}

$href = tep_href_link(FILENAME_PRODUCT_INFO,"products_id={$products_id}&ref={$uid}&affiliate_banner_id=1");
ObHeader($href);
exit;
?>