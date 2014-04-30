<?php
require('route.global.php');
require(API_ROOT.'product_travel.global.php');
$route_id = intval($_GET['route_id']);
if(!$route_id){
	message('please give me "route_id" parameters!');
}elseif(!$productsData[$route_id]){
	message('You can not access the product!');
}
$now_date = date('Y-m-d');
$expires_date = $now_date.date(' H:i:s');
require(DIR_FS_FUNCTIONS . 'webmakers_added_functions.php');
//=========query======================
$product_query = tep_db_query("select p.products_info_tpl, p.tour_type_icon, p.products_class_id,p.products_class_content,
							p.products_durations,p.products_durations_type,p.products_video,p.products_type,p.operate_start_date ,
							p.operate_end_date,p.products_single,p.products_single_pu,p.products_double,p.products_triple,
							p.products_quadr,p.products_kids, p.display_room_option,p.maximum_no_of_guest,p.products_id, p.is_visa_passport, 
							p.products_is_regular_tour, p.products_model, p.provider_tour_code, p.products_quantity, p.products_image,
							p.products_image_med, p.products_image_lrg, pd.products_url,p.products_tax_class_id, 
							round(p.products_price/cur.value) as products_price,
							round(IF(s.status='1' 
							AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00'), 
							s.specials_new_products_price, p.products_price)/cur.value) as final_price,
							s.expires_date,s.status as s_status,
							p.products_date_added, p.products_date_available, p.manufacturers_id, p.departure_city_id, p.departure_end_city_id,
							p.agency_id, p.display_pickup_hotels, p.display_itinerary_notes, p.display_hotel_upgrade_notes,p.products_map, 
							p.products_stock_status, p.upgrade_to_product_id,
							pd.products_notes, pd.products_name, pd.products_description, pd.products_small_description, 
							pd.products_pricing_special_notes,  pd.products_other_description, pd.products_package_excludes, 
							pd.products_package_special_notes
							from " . TABLE_PRODUCTS . " p
							left join " . TABLE_SPECIALS . " s on s.products_id = p.products_id," .
							TABLE_TRAVEL_AGENCY . " ta, ". TABLE_PRODUCTS_DESCRIPTION . " pd ,".TABLE_CURRENCIES." cur 
							where p.products_status = '1' and p.products_id = '" . $route_id . "' and pd.products_id = p.products_id
							and pd.language_id='{$languages_id}'
							and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code ");
$product = tep_db_fetch_array($product_query);
if(!$product){
	message('This product is not found!');
}
$product['products_name1']=strstr($product['products_name'], '**');
if($product['products_name1']!='' && $product['products_name1']!==false)$product['products_name']=str_replace($product['products_name1'],'',$product['products_name']);
//出发城市和结束城市
$product['departure_city_id'] = explode(',',$product['departure_city_id']);
$product['departure_end_city_id'] = explode(',',$product['departure_end_city_id']);
$city_ids = array_merge($product['departure_city_id'],$product['departure_end_city_id']);
$cityquery = tep_db_query("select c.city_id,c.city,ct.countries_name,ct.countries_iso_code_3 from " . TABLE_TOUR_CITY . " c,regions r,countries ct where c.regions_id=r.regions_id and ct.countries_id=r.countries_id and c.city_id in ('".join("','",$city_ids)."')  order by c.city");
$product['s_city']=$product['e_city']=array();
while($cityclass = tep_db_fetch_array($cityquery)){
	$city_string = $cityclass['countries_name'].'('.$cityclass['countries_iso_code_3'].')|'.$cityclass['city'];
	if(in_array($cityclass['city_id'],$product['departure_city_id'])){
		$product['s_city'][$cityclass['city_id']] = $city_string;
	}
	if(in_array($cityclass['city_id'],$product['departure_end_city_id'])){
		$product['e_city'][$cityclass['city_id']] = $city_string;
	}
}
unset($product['departure_city_id'],$product['departure_end_city_id']);
//持续时间
if($product['products_durations_type'] == 0){
		$product['products_durations_type'] =  '天';
}else if($product['products_durations_type'] == 1){
		$product['products_durations_type'] =  '小时';
}else if($product['products_durations_type'] == 2){
		$product['products_durations_type']=  '分';
}
if($product['s_status']=='1' && ($product['expires_date']>$expires_date || $product['expires_date']=='' || $product['expires_date']=='0000-00-00 00:00:00')){
	$product['old_price']=$product['products_price'];
}
//出团时间
$product['operate'] = tep_get_display_operate_info($product['products_id'],1);
//=========query end==================
$HTTP_SERVER = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
$dom = createDom();
$domroot = cel("routeInfo",$dom);

$id = cel("id");
$id->appendChild(cval($route_id));

$bookurl = cel("bookurl");
$url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $route_id)."?utm_source={$u}&utm_medium={$utmMedium}&utm_term={$utmTerm}";
$bookurl->appendChild(cval($url));

$name = cel("name");
$name->appendChild(cval($product['products_name']));
$name1 = cel("name1");
$name1->appendChild(cval($product['products_name1']));

$img = cel("img");
$img->appendChild(cval(DIR_WS_IMAGES.$product['products_image']));

$from = cel("from");
$from->appendChild(cval(join(',',$product['s_city'])));

$to = cel("to");
$to->appendChild(cval(join(',',$product['e_city'])));

if($product['old_price']){
	$delprice = cel("delprice");
	$delprice->appendChild(cval($currencies->display_price($product['old_price'],tep_get_tax_rate($product['products_tax_class_id'],STORE_COUNTRY,STORE_ZONE))));
}

$price = cel("price");
$price->appendChild(cval($currencies->display_price($product['final_price'],tep_get_tax_rate($product['products_tax_class_id'],STORE_COUNTRY,STORE_ZONE))));

$plan = cel("plan");
$plan->appendChild(cval(join('##',$product['operate'])));

$duration = cel("duration");
$duration->appendChild(cval($product['products_durations'].' '.$product['products_durations_type']));

$description = cel("description");
$description->appendChild(cval($product['products_small_description']));

$dayplans = cel("dayplans");
$travelData=array();
$travelQuery = tep_db_query("SELECT * FROM `products_travel` WHERE `products_id`='{$route_id}' and `langid`='{$languages_id}' ORDER BY  `travel_index` ASC");
$isAccData = false;
while($travel = tep_db_fetch_array($travelQuery)){
	if(!empty($travel['travel_content'])){
		$isAccData = true;
	}
	$travelData[$travel['travel_index']]=$travel;
}
if(!count($travelData) || !$isAccData){
	$travelData = formatTravelData($product['products_description']);
}
foreach($travelData as $travel){
	$day = cel("day",$dayplans);
	
	$index = cel("index",$day);
	$index->appendChild(cval($travel['travel_index']));
	
	$title = cel("title",$day);
	$title->appendChild(cval($travel['travel_name']));
	
	$img = cel("img",$day);
	if($travel['travel_img'] && !ereg('^http(s)?://',$travel['travel_img'])){
		$travel['travel_img'] = DIR_WS_IMAGES.$travel['travel_img'];
	}
	$img->appendChild(cval($travel['travel_img']));
	
	$hotel = cel("hotel",$day);
	$travel['travel_hotel'] = str_replace("\r\n",'##',$travel['travel_hotel']);
	$hotel->appendChild(cval($travel['travel_hotel']));
	
	$description = cel("description",$day);
	$description->appendChild(cval($travel['travel_content']));
}


outDom($dom);
?>