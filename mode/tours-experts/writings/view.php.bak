<?php
!defined('_MODE_KEY') && exit('Access error!');

tep_db_query("update `experts_writings` set hits=hits+1 WHERE `aid`='{$aid}' and `uid`='{$uid}' and `is_draft`='0'");
$expWhere = $isExpertsSelf?'':" and w.`is_draft`='0'";
$sql = "SELECT t.name as typename,w.* FROM `experts_writings` w,`experts_writings_type` t WHERE w.`tid`=t.`tid` and w.`aid`='{$aid}' and w.`uid`='{$uid}' ".$expWhere;
$writings = tep_db_get_one($sql);
if(!$writings){
	showmsg('访问错误\r\n连接地址错误或文章不存在！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
}
$writings['time'] = date('m/d/Y H:i',strtotime($writings['time']));
$crumb['Title'] = $writings['typename'];
$crumb['NoSeo'] = true;
$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&tid={$writings['tid']}");
$crumbData[] = $crumb;

$crumb['Title'] = $writings['title'];
$crumb['NoSeo'] = false;
$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&action=view&aid={$writings['aid']}");
$crumbData[] = $crumb;
//=================================================================================
$writings['prev'] = tep_db_get_one("SELECT `uid`,`aid`,`title` FROM `experts_writings` WHERE `aid`<'{$aid}' and `uid`='{$uid}' and `is_draft`='0' order by aid desc limit 1");
$writings['next'] = tep_db_get_one("SELECT `uid`,`aid`,`title` FROM `experts_writings` WHERE `aid`>'{$aid}' and `uid`='{$uid}' and `is_draft`='0' order by aid limit 1");
//=================================================================================
$now_date = date('Y-m-d');
$expires_date = $now_date.date(' H:i:s');
//此为取价格列时需要的条件
$specilWhere = " s.status='1' 
			AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00') ";
//价格列
$specilClunmsName = " round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value) ";
$sql = "SELECT ep.* , p.products_model, pd.products_name,p.products_tax_class_id,
		{$specilClunmsName} as final_price
		FROM  `experts_writings_products` ep, products p
		left join specials s on s.products_id = p.products_id, 
		tour_travel_agency ta, products_description pd ,currencies cur
		WHERE ep.`products_id` = p.`products_id` AND ep.`products_id` = pd.`products_id` 
		AND p.products_status = '1' and pd.language_id = '1' and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code
		AND ep.`aid` =  '{$aid}' AND ep.`uid` =  '{$uid}'";
		
$query = tep_db_query($sql);
$wProducts = array();
while ($rt = tep_db_fetch_array($query)){
	//当前显示价格,价格已在SQL语句里运算
	$rt['products_name1']=strstr($rt['products_name'], '**');
	if($rt['products_name1']!='' && $rt['products_name1']!==false)$rt['products_name']=str_replace($rt['products_name1'],'',$rt['products_name']);
	$rt['final_price'] = $currencies->display_price($rt['final_price'],tep_get_tax_rate($rt['products_tax_class_id']));

	$rt['ordernum'] = getProducts_OrderNum($rt['uid'],$rt['products_id']);//取得对应产品的推广定单数
	
	$wProducts[]=$rt;
}
?>