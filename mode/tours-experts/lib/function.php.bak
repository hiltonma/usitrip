<?php
$urlParme=$_GET;
function makesearchUrl($mod='',$val='',$dir=false){
	global $urlParme,$baseUrl;
	$url = makeDirUrl($urlParme,$mod,$val,$dir);
	return tep_href_link($baseUrl,$url);
}

function getPhoto($photo){
	if(trim($photo)==''){
		$photo1 = 'image/expert_user_nopic.jpg';
	}else{
		$photo1 = DIR_WS_IMAGES .'photos/'.$photo;
		if(!is_file(DIR_PHOTOS_FS_IMAGES.$photo)){
			$photo1 = 'image/expert_user_nopic.jpg';
		}
	}
	return $photo1;
}
function getWritingsType($uid){
	$writingsType = array();
	$query = tep_db_query("SELECT * FROM `experts_writings_type` WHERE `uid`='{$uid}' order by tid");
	while ($rt = tep_db_fetch_array($query)){
		$writingsType[$rt['group_id']][] = $rt;
	}
	return $writingsType;
}
function getProducts_OrderNum($uid,$products_id=0){
	//global $affiliateStatusAllow;
	$products_id = intval($products_id);
	if($products_id){
		$sql = "select count(o.orders_id) as rows
			from " . TABLE_ORDERS . " o," . TABLE_AFFILIATE_SALES . " a,orders_products op
			where a.affiliate_orders_id = o.orders_id and a.affiliate_isvalid ='1'
			and op.orders_id = o.orders_id and op.products_id='{$products_id}'
			and a.affiliate_id = '{$uid}'";
	}else{
		global $languages_id;
		$sql = "select count(o.orders_id) as rows
			from " . TABLE_ORDERS . " o
			left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "'),
			" . TABLE_AFFILIATE_SALES . " a
			where a.affiliate_orders_id = o.orders_id and a.affiliate_isvalid ='1'
			and a.affiliate_id = '{$uid}'";
	}
	$query = tep_db_get_one($sql);
	$query = intval($query['rows']);
	return $query;
}
function getProducts_OrderPayment($uid){
	global $languages_id,$language,$currencies,$affiliateStatusAllow;
	$sallow = "'".join("','",$affiliateStatusAllow)."'";
	/*$sql = "select SUM( a.affiliate_payment ) as payment
		from " . TABLE_ORDERS . " o
		left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "'),
		" . TABLE_AFFILIATE_SALES . " a
		where a.affiliate_orders_id = o.orders_id and a.affiliate_isvalid ='1'
		and o.orders_status in({$sallow}) and a.affiliate_id = '{$uid}'";*/
		
	$sql = "select  SUM( a.affiliate_payment ) as payment from 
			" . TABLE_AFFILIATE_SALES . " a 
			left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
			left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
			where a.affiliate_id = '{$uid}'	and a.affiliate_isvalid = 1 and o.orders_status in({$sallow})";
	$query = tep_db_get_one($sql);
	$query = $currencies->display_price($query['payment'], '');
	return $query;
}
?>