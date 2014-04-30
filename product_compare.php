<?php
require('includes/application_top.php');
require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');

$pids = '0';
$maxChecked =3;
$prodcutDB = $_COOKIE['prodcutDB'];
!is_array($prodcutDB) && $prodcutDB=array();
$i=0;
foreach($prodcutDB as $pid=>$item){
	$i++;
	if($i>$maxChecked){
		$i--;
		unset($prodcutDB[$pid]);
		dCookie("prodcutDB[{$pid}][name]",'');
		dCookie("prodcutDB[{$pid}][no]",'');
	}elseif($item['name']=='' || $item['no']==''){
		$i--;
		unset($prodcutDB[$pid]);
		dCookie("prodcutDB[{$pid}][name]",'');
		dCookie("prodcutDB[{$pid}][no]",'');
	}else{
		$pid = intval($pid);
		$pids .= ",{$pid}";
	}
}

$dataWhere = " where 1 and p.products_status = '1' and p.products_id in({$pids}) ";//要查询的常规条件
//要查询的常规表
$dataTables = TABLE_PRODUCTS . " p 
				left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id 
				left join products_buy_two_get_one pbgo on  pbgo.products_id = p.products_id 
				left join products_double_room_preferences pdrp on pdrp.products_id=p.products_id,
				" . TABLE_TRAVEL_AGENCY . " ta, ". TABLE_PRODUCTS_DESCRIPTION . " pd ,".TABLE_CURRENCIES." cur ";
				
$now_date = date('Y-m-d');
$expires_date = $now_date.date(' H:i:s');
//此为取价格列时需要的条件
$specilWhere = " s.status='1' 
			AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00') ";
//价格列
$specilClunmsName = " round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value) ";

$dataTableClunms = "p.products_id, pd.products_name,pd.products_small_description ,
			   pd.products_other_description, pd.products_package_excludes,

			   s.expires_date,s.status as s_status,s.specials_new_products_price,
			   
			   pdrp.value as pdrp_value,pdrp.`people_number`,pdrp.`status` as pdrp_status,
			   pdrp.`products_departure_date_begin` as pdrp_pddb,pdrp.`products_departure_date_end` as pdrp_pdde,
			   pdrp.excluding_dates as pdrp_eld_date,
			   
			   pbgo.`one_or_two_option`,pbgo.`status` as pbgo_status,
			   pbgo.`products_departure_date_begin` as pbgo_pddb,pbgo.`products_departure_date_end` as pbgo_pdde,
			   pbgo.`excluding_dates` as pbgo_eld_date,
			   
			   p.products_single,p.products_single_pu,p.products_double,p.products_triple,p.products_quadr,p.products_kids,
			   p.products_image,  p.products_vacation_package, p.products_video, p.products_durations,p.display_room_option,
			   p.products_durations_type, p.products_durations_description, p.departure_city_id,p.departure_end_city_id, 
			   p.products_model,  p.products_is_regular_tour,  p.manufacturers_id, round(p.products_price/cur.value) as products_price, 
			   p.products_tax_class_id, p.products_class_id,p.tour_type_icon,p.use_buy_two_get_one_price,p.min_num_guest,p.is_hotel,p.is_transfer, 
			   {$specilClunmsName} as final_price ";

$dataWhere .= " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' 
				and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code ";

$querySQL = "select {$dataTableClunms} from {$dataTables} {$dataWhere} group by p.products_id";

$DataList = array();
$maxTravelnum = 0;
if($pids!='0'){
$query = tep_db_query($querySQL);
$_isHotels = array();
while ($rt = tep_db_fetch_array($query)){//格式化数据
	//对比页面只是对比属性一致的产品，也就是说行程只能和行程对比，酒店只能和酒店对比。
	$_isHotels[]=(int)$rt['is_hotel'];
	//如果是酒店对比则要读取酒店的信息
	if((int)$rt['is_hotel']){
		require_once(DIR_FS_FUNCTIONS . 'hotels_functions.php');
		$hotelQuery = tep_db_query('SELECT hotel_stars, meals_id, internet_id, approximate_location_id, hotel_phone, hotel_address FROM `hotel` WHERE products_id ="'.$rt['products_id'].'" ');
		$hoteRow = tep_db_fetch_array($hotelQuery);
		foreach((array)$hoteRow as $key => $val){
			$rt[$key] = $val;
		}
	}
	
	//当前显示价格,价格已在SQL语句里运算
	if($rt['s_status']=='1' && ($rt['expires_date']>$expires_date || $rt['expires_date']=='' || $rt['expires_date']=='0000-00-00 00:00:00')){
		$rt['old_price']=$rt['products_price'];
	}
	//行程
	$travelsql = "SELECT `travel_name`, `travel_img`, `travel_hotel` FROM  `products_travel` 
				where products_id='{$rt['products_id']}' and langid='" . (int)$languages_id . "' order by travel_index";
	$travelquery = tep_db_query($travelsql);
	$rt['travel']=array();
	while ($travel = tep_db_fetch_array($travelquery)){
		$travel_hotel_tmp=explode("\r",$travel['travel_hotel']);
		$travel['travel_hotel']=array();
		foreach($travel_hotel_tmp as $ht){
			if(trim($ht)!=''){
				$hotel_tmp = explode('|',$ht);
				$hotel['name'] = $hotel_tmp[0];
				$hotel['star'] = intval($hotel_tmp[1].'');
				$travel['travel_hotel'][]=$hotel;
			}
		}
		$rt['travel'][] = $travel;
	}
	$maxTravelnum = max(count($rt['travel']),$maxTravelnum);
	//主要景点
	$rt['products_small_description'] = stripslashes2(db_to_html($rt['products_small_description']));
	//pd.products_other_description, pd.products_package_excludes,
	$rt['products_other_description'] = stripslashes2(db_to_html($rt['products_other_description']));
	$rt['products_package_excludes'] = stripslashes2(db_to_html($rt['products_package_excludes']));
	
	//出团时间
	$rt['operate'] = tep_get_display_operate_info($rt['products_id'],1);
	//结伴发贴数
	$rt['companion'] = (int)get_product_companion_post_num($rt['products_id']);
	//问题咨询数
	$rt['question'] = (int)get_product_question_num($rt['products_id']);
	//产品评论数
	$rt['reviews'] = (int)get_product_reviews_num($rt['products_id']);
	//照片数
	$rt['photos'] = (int)get_traveler_photos_num($rt['products_id']);
	//出发城市departure_city_id,目的城市departure_end_city_id
	$rt['departure_city_id'] = explode(',',$rt['departure_city_id']);
	$rt['departure_end_city_id'] = explode(',',$rt['departure_end_city_id']);
	$city_ids = array_merge($rt['departure_city_id'],$rt['departure_end_city_id']);
	$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in ('".join("','",$city_ids)."')  order by city");
	$rt['s_city']=$rt['e_city']=array();
	while($cityclass = tep_db_fetch_array($cityquery)){
		if(in_array($cityclass['city_id'],$rt['departure_city_id']))$rt['s_city'][$cityclass['city_id']] = $cityclass['city'];
		if(in_array($cityclass['city_id'],$rt['departure_end_city_id']))$rt['e_city'][$cityclass['city_id']] = $cityclass['city'];
	}
	unset($rt['departure_city_id'],$rt['departure_end_city_id']);
	//持续时间
	if($rt['products_durations_type'] == 0){
			$rt['products_durations_type'] =  TEXT_DURATION_DAYS;
	}else if($rt['products_durations_type'] == 1){
			$rt['products_durations_type'] =  TEXT_DURATION_HOURS;
	}else if($rt['products_durations_type'] == 2){
			$rt['products_durations_type']=  TEXT_DIRATION_MINUTES;
	}
	//==================优惠 START=============================
	$rt['gift']['num']=0;
	//====================特价========================
	if(
	($rt['s_status']=='1'
	&& ($rt['expires_date']>$expires_date || $rt['expires_date']=='' || $rt['expires_date']=='0000-00-00 00:00:00')
	)
	|| preg_match('~specil-jia~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('特价');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'specil';
	}
	unset($rt['s_status'],$rt['expires_date']);
	//=================双人折扣======================
	if(
	($rt['pdrp_status']=='1' && $rt['people_number']=='2' 
	&& ($rt['pdrp_pddb']<="{$now_date} 00:00:00" || $rt['pdrp_pddb']=='' || $rt['pdrp_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pdrp_pdde']>="{$now_date} 00:00:00" || $rt['pdrp_pdde']=='' || $rt['pdrp_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pdrp_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pdrp_eld_date']))
	)
	|| preg_match('~2-pepole-spe~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('双人折扣');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = '2pepole';
	}
	unset($rt['pdrp_status'],$rt['people_number'],$rt['pdrp_pddb'],$rt['pdrp_pdde'],$rt['pdrp_eld_date']);
	//=================买2送1==========================
	if(
	($rt['pbgo_status']=='1' && $rt['products_class_id']=='4' && $rt['use_buy_two_get_one_price']=='1' 
	&& ($rt['one_or_two_option']=='0' || $rt['one_or_two_option']=='1')
	&& ($rt['pbgo_pddb']<="{$now_date} 00:00:00" || $rt['pbgo_pddb']=='' || $rt['pbgo_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_pdde']>="{$now_date} 00:00:00" || $rt['pbgo_pdde']=='' || $rt['pbgo_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pbgo_eld_date']))
	)
	|| preg_match('~buy2-get-1~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('买2送1');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'b2g1';
	}
	//=================买2送2==========================
	if(
	($rt['pbgo_status']=='1' && $rt['products_class_id']=='4' && $rt['use_buy_two_get_one_price']=='1' 
	&& ($rt['one_or_two_option']=='0' || $rt['one_or_two_option']=='2')
	&& ($rt['pbgo_pddb']<="{$now_date} 00:00:00" || $rt['pbgo_pddb']=='' || $rt['pbgo_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_pdde']>="{$now_date} 00:00:00" || $rt['pbgo_pdde']=='' || $rt['pbgo_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pbgo_eld_date']))
	)
	|| preg_match('~buy2-get-2~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('买2送2');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'b2g2';
	}
	unset($rt['pbgo_status'],$rt['use_buy_two_get_one_price'],$rt['pbgo_pddb'],$rt['pbgo_pdde'],$rt['pbgo_eld_date']);
	
	unset($rt['tour_type_icon'],$rt['products_class_id']);
	//==================优惠 END=============================
	$rt['products_name1']=strstr($rt['products_name'], '**');
	if($rt['products_name1']!='' && $rt['products_name1']!==false)$rt['products_name']=str_replace($rt['products_name1'],'',$rt['products_name']);
	$DataList[]=$rt;
}
}

$_h3Title = '行程对比';
$_prodNameTitle = '行程名称';
$_prodModelTitle = '行程编号';
if($_isHotels[0]==1){
	$_h3Title = '产品对比';
	$_prodNameTitle = '产品名称';
	$_prodModelTitle = '产品编号';
}

$the_key_words = db_to_html($_h3Title);
$the_desc = db_to_html($_h3Title);
$breadcrumb->add(db_to_html($_h3Title), '');


$content = 'product_compare';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>