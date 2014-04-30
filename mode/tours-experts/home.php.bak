<?php
!defined('_MODE_KEY') && exit('Access error!');
//====================Writings============================================================
$sql = "SELECT b.aid,b.tid, b.uid, a.name, b.title, b.hits,b.`time` ,b.is_pic
	FROM experts_writings_type a
	INNER JOIN experts_writings b ON a.tid = b.tid
	INNER JOIN (
	SELECT tid, MAX(  `time` ) AS mtime
	FROM experts_writings
	WHERE uid =  '{$uid}'
	GROUP BY tid
	)c ON a.tid = c.tid
	WHERE b.uid =  '{$uid}' and a.uid=b.uid
	AND 3>(
		SELECT Count(`aid`) 
		FROM  experts_writings 
		WHERE  `aid`=b.`aid` AND `time`>b.`time`)
	and b.is_draft='0'
	ORDER BY mtime DESC ,  `time` DESC , a.tid";
$maxWritings = 25;
$limit=" LIMIT 0,{$maxWritings};";

$query = tep_db_query($sql.$limit);
$expertsWritings = array();
$thisTypeName = '';
while ($rt = tep_db_fetch_array($query)){
	$rt['time'] = date('m/d/Y',strtotime($rt['time']));
	if($tid && !$thisTypeName){
		$thisTypeName = $rt['name'];
	}
	$expertsWritings[]=$rt;
}

//===================recom Products==============================================================
$recomProducts = array();
if($expertsInfo['recom']){
	require(DIR_FS_LANGUAGES . $language . '/product_info.php' );
	require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
	$now_date = date('Y-m-d');
	$expires_date = $now_date.date(' H:i:s');
	//此为取价格列时需要的条件
	$specilWhere = " s.status='1' 
				AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00') ";
	//价格列
	$specilClunmsName = " round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value) ";
	$sql = "SELECT p.is_visa_passport, p.products_id, pd.products_name,pd.products_small_description ,

			   s.expires_date,s.status as s_status,
			   
			   pdrp.value as pdrp_value,pdrp.`people_number`,pdrp.`status` as pdrp_status,
			   pdrp.`products_departure_date_begin` as pdrp_pddb,pdrp.`products_departure_date_end` as pdrp_pdde,
			   pdrp.excluding_dates as pdrp_eld_date,
			   
			   pbgo.`one_or_two_option`,pbgo.`status` as pbgo_status,
			   pbgo.`products_departure_date_begin` as pbgo_pddb,pbgo.`products_departure_date_end` as pbgo_pdde,
			   pbgo.`excluding_dates` as pbgo_eld_date,
			   
			   p.products_image,  p.products_vacation_package, p.products_video, p.products_durations,p.display_room_option,
			   p.products_durations_type, p.products_durations_description, p.departure_city_id,p.departure_end_city_id, 
			   p.products_model,  p.products_is_regular_tour,  p.manufacturers_id, round(p.products_price/cur.value) as products_price, 
			   p.products_tax_class_id, p.products_class_id,p.tour_type_icon,p.use_buy_two_get_one_price,p.min_num_guest,
			   {$specilClunmsName} as final_price
			FROM  ".TABLE_PRODUCTS . " p 
			left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id 
			left join products_buy_two_get_one pbgo on  pbgo.products_id = p.products_id 
			left join products_double_room_preferences pdrp on pdrp.products_id=p.products_id,
			" . TABLE_TRAVEL_AGENCY . " ta, ". TABLE_PRODUCTS_DESCRIPTION . " pd ,".TABLE_CURRENCIES." cur 
			WHERE p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' 
			and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code 
			and p.`products_id` ='{$expertsInfo['recom']}' ";
	$recomProducts = tep_db_get_one($sql);
	if($recomProducts){
		//当前显示价格,价格已在SQL语句里运算
		if($recomProducts['s_status']=='1' && ($recomProducts['expires_date']>$expires_date || $recomProducts['expires_date']=='' || $recomProducts['expires_date']=='0000-00-00 00:00:00')){
			$recomProducts['old_price']=$recomProducts['products_price'];
		}
		//出团时间
		$recomProducts['operate'] = tep_get_display_operate_info($recomProducts['products_id'],1);
		//结伴发贴数
		$recomProducts['companion'] = (int)get_product_companion_post_num($recomProducts['products_id']);
		//问题咨询数
		$recomProducts['question'] = (int)get_product_question_num($recomProducts['products_id']);
		//产品评论数
		$recomProducts['reviews'] = (int)get_product_reviews_num($recomProducts['products_id']);
		//照片数
		$recomProducts['photos'] = (int)get_traveler_photos_num($recomProducts['products_id']);
		//出发城市departure_city_id,目的城市departure_end_city_id
		$recomProducts['departure_city_id'] = explode(',',$recomProducts['departure_city_id']);
		$recomProducts['departure_end_city_id'] = explode(',',$recomProducts['departure_end_city_id']);
		$city_ids = array_merge($recomProducts['departure_city_id'],$recomProducts['departure_end_city_id']);
		$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in ('".join("','",$city_ids)."')  order by city");
		$recomProducts['s_city']=$recomProducts['e_city']=array();
		while($cityclass = tep_db_fetch_array($cityquery)){
			if(in_array($cityclass['city_id'],$recomProducts['departure_city_id']))$recomProducts['s_city'][$cityclass['city_id']] = $cityclass['city'];
			if(in_array($cityclass['city_id'],$recomProducts['departure_end_city_id']))$recomProducts['e_city'][$cityclass['city_id']] = $cityclass['city'];
		}
		unset($recomProducts['departure_city_id'],$recomProducts['departure_end_city_id']);
		//持续时间
		if($recomProducts['products_durations_type'] == 0){
				$recomProducts['products_durations_type'] =  TEXT_DURATION_DAYS;
		}else if($recomProducts['products_durations_type'] == 1){
				$recomProducts['products_durations_type'] =  TEXT_DURATION_HOURS;
		}else if($recomProducts['products_durations_type'] == 2){
				$recomProducts['products_durations_type']=  TEXT_DIRATION_MINUTES;
		}
		//==================优惠 START=============================
		$recomProducts['gift']['num']=0;
		//====================特价========================
		if(
		($recomProducts['s_status']=='1'
		&& ($recomProducts['expires_date']>$expires_date || $recomProducts['expires_date']=='' || $recomProducts['expires_date']=='0000-00-00 00:00:00')
		)
		|| preg_match('~specil-jia~is',$recomProducts['tour_type_icon']) ){
			$recomProducts['gift']['num']++;
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['name'] = db_to_html('特价');
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['key'] = 'specil';
		}
		unset($recomProducts['s_status'],$recomProducts['expires_date']);
		//=================双人折扣======================
		if(
		($recomProducts['pdrp_status']=='1' && $recomProducts['people_number']=='2' 
		&& ($recomProducts['pdrp_pddb']<="{$now_date} 00:00:00" || $recomProducts['pdrp_pddb']=='' || $recomProducts['pdrp_pddb']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pdrp_pdde']>="{$now_date} 00:00:00" || $recomProducts['pdrp_pdde']=='' || $recomProducts['pdrp_pdde']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pdrp_eld_date']=='' || !preg_match("~{$now_date}~is",$recomProducts['pdrp_eld_date']))
		)
		|| preg_match('~2-pepole-spe~is',$recomProducts['tour_type_icon']) ){
			$recomProducts['gift']['num']++;
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['name'] = db_to_html('双人折扣');
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['key'] = '2pepole';
		}
		unset($recomProducts['pdrp_status'],$recomProducts['people_number'],$recomProducts['pdrp_pddb'],$recomProducts['pdrp_pdde'],$recomProducts['pdrp_eld_date']);
		//=================买2送1==========================
		if(
		($recomProducts['pbgo_status']=='1' && $recomProducts['products_class_id']=='4' && $recomProducts['use_buy_two_get_one_price']=='1' 
		&& ($recomProducts['one_or_two_option']=='0' || $recomProducts['one_or_two_option']=='1')
		&& ($recomProducts['pbgo_pddb']<="{$now_date} 00:00:00" || $recomProducts['pbgo_pddb']=='' || $recomProducts['pbgo_pddb']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pbgo_pdde']>="{$now_date} 00:00:00" || $recomProducts['pbgo_pdde']=='' || $recomProducts['pbgo_pdde']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$recomProducts['pbgo_eld_date']))
		)
		|| preg_match('~buy2-get-1~is',$recomProducts['tour_type_icon']) ){
			$recomProducts['gift']['num']++;
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['name'] = db_to_html('买2送1');
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['key'] = 'b2g1';
		}
		//=================买2送2==========================
		if(
		($recomProducts['pbgo_status']=='1' && $recomProducts['products_class_id']=='4' && $recomProducts['use_buy_two_get_one_price']=='1' 
		&& ($recomProducts['one_or_two_option']=='0' || $recomProducts['one_or_two_option']=='2')
		&& ($recomProducts['pbgo_pddb']<="{$now_date} 00:00:00" || $recomProducts['pbgo_pddb']=='' || $recomProducts['pbgo_pddb']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pbgo_pdde']>="{$now_date} 00:00:00" || $recomProducts['pbgo_pdde']=='' || $recomProducts['pbgo_pdde']=='0000-00-00 00:00:00') 
		&& ($recomProducts['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$recomProducts['pbgo_eld_date']))
		)
		|| preg_match('~buy2-get-2~is',$recomProducts['tour_type_icon']) ){
			$recomProducts['gift']['num']++;
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['name'] = db_to_html('买2送2');
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['key'] = 'b2g2';
		}
		unset($recomProducts['pbgo_status'],$recomProducts['use_buy_two_get_one_price'],$recomProducts['pbgo_pddb'],$recomProducts['pbgo_pdde'],$recomProducts['pbgo_eld_date']);
		
		unset($recomProducts['tour_type_icon'],$recomProducts['products_class_id']);
		//=====================低价保证==========================
		if(strpos(','.LOW_PRICE_GUARANTEE_PRODUCTS.',',",{$recomProducts['products_id']},")!==false){
			$recomProducts['gift']['num']++;
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['name'] = db_to_html('低价保证');
			$recomProducts['gift']['item'][$recomProducts['gift']['num']]['key'] = 'low ';
		}
		//==================优惠 END=============================
		$recomProducts['products_name1']=strstr($recomProducts['products_name'], '**');
		if($recomProducts['products_name1']!='' && $recomProducts['products_name1']!==false){
			$recomProducts['products_name']=str_replace($recomProducts['products_name1'],'',$recomProducts['products_name']);
		}
		$recomProducts['final_price'] = $currencies->display_price($recomProducts['final_price'],tep_get_tax_rate($recomProducts['products_tax_class_id']));
		if($recomProducts['old_price']){
			$recomProducts['old_price'] = $currencies->display_price($recomProducts['old_price'],tep_get_tax_rate($recomProducts['products_tax_class_id']));
		}
		//==================积分信息================================
		if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
			if ($recomProducts['points_newprice'] = tep_get_products_special_price($recomProducts['products_id'])) {
				$products_price_points = tep_display_points($recomProducts['points_newprice'],tep_get_tax_rate($recomProducts['products_tax_class_id']));
			} else {
				$products_price_points = tep_display_points($recomProducts['products_price'],tep_get_tax_rate($recomProducts['products_tax_class_id']));
			}
			
			$products_points = tep_calc_products_price_points($products_price_points);
			$products_points = get_n_multiple_points($products_points , $recomProducts['products_id']);
			if ((USE_POINTS_FOR_SPECIALS == 'true') || $recomProducts['points_newprice'] == false) {
				$recomProducts['points_info'] = sprintf(TEXT_PRODUCT_POINTS ,number_format($products_points,POINTS_DECIMAL_PLACES));
			}
		}
	}
}
//=======================FAQ=========================================================
$query = tep_db_query("SELECT * FROM (SELECT q.`q_id`,q.`q_content`,q.`q_modified_time`,q.`customers_id` ,
							a.a_id,a.`a_content`,a.`a_modified_time`,a.`a_useful`,a.`a_useless`
							FROM  `experts_question` q, experts_answers a
							WHERE q.q_id = a.q_id AND q.uid =  '{$uid}'
							ORDER BY a.a_modified_time DESC)tmp group by q_id ORDER BY a_modified_time DESC limit 4;");
$FaqData = array();
while($rt = tep_db_fetch_array($query)){
	$rt['customers_name'] = tep_customers_name($rt['customers_id']);
	$rt['a_modified_time'] = date('m/d/Y H:i',strtotime($rt['a_modified_time']));
	$rt['q_modified_time'] = date('m/d/Y H:i',strtotime($rt['q_modified_time']));
	$FaqData[] = $rt;
}
?>