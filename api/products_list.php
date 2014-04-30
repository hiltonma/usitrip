<?php
/**
 * 合作API：用于输出XML文档的产品列表给对方
 * @author Howard
 * 
 */
$startTime = microtime(true);
ini_set("max_execution_time", 600); //10分钟
set_time_limit(0);


require('global.php');
require_once(DIR_FS_FUNCTIONS.'webmakers_added_functions.php');

require_once(DIR_FS_FUNCTIONS . 'get_avaliabledate.php');

//添加MCache模块 提供缓存功能
define('USE_MCACHE',true);
require_once DIR_FS_CATALOG."includes/classes/mcache.php";
$mcache = MCache::instance();
//账号审查
define('INCLUDE_AUTH_PASS',true);
include(API_ROOT.'auth_pass.php');

if(!(int)$api_categories_id){ die('No api_categories_id'); }

//xml文件缓存
clearstatcache();
$xmlfile = dirname(__FILE__).'/xml_file/api_categories_id_'.$api_categories_id.'.'.CHARSET.'.xml';
if(file_exists($xmlfile)){
	$fInfos = stat($xmlfile);
	if(date('Ymd')==date('Ymd',$fInfos['mtime'])){
		header('Content-Type: text/xml; charset=UTF-8');
		echo file_get_contents($xmlfile);
		//echo (microtime(true)-$startTime);
		exit;
	}
}

//取得产品数据
$datas = array();
$sql = tep_db_query('select * from products_to_categories ptc, products p, products_description pd where ptc.categories_id="'.(int)$api_categories_id.'" and p.products_id = ptc.products_id and pd.products_id = p.products_id and p.products_status="1" and p.products_stock_status="1" Group By p.products_id ');
$n=0;
while($rows = tep_db_fetch_array($sql)){
	$datas[$n] = $rows;
	//出发城市 {
	if($rows['departure_city_id'] == ''){
		$rows['departure_city_id'] = 0;
	}
	$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $rows['departure_city_id'] . ") order by c.city Limit 1");
	$city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query);
	$datas[$n]['city'] = $city_class_departure_at['city'];
	//}
	//目的城市(途经景点)
	$datas[$n]['destination_city'] = array();
	$destination_ids = tep_get_product_destination_city_ids($rows['products_id']);
	$datas[$n]['destination_city'] = tep_get_city_names(implode(',', (array)$destination_ids));
	
	//产品价格 {
	$tax_rate_val_get = tep_get_tax_rate($rows['products_tax_class_id']);
	if ($new_price = tep_get_products_special_price($rows['products_id'])) {
		$datas[$n]['price'] = $currencies->display_price($new_price, $tax_rate_val_get);
	} else {
		$datas[$n]['price'] = $currencies->display_price($rows['products_price'], $tax_rate_val_get);
	}
	$datas[$n]['price'] = str_replace('.00','',$datas[$n]['price']);
	//}
	//行程天数 {
	if($rows['products_durations_type']<1 ){
		$datas[$n]['days'] = (int)$rows['products_durations'];
	}elseif($rows['products_durations_type']==1){
		$datas[$n]['days'] = ceil((int)$rows['products_durations']/24);
	}else{
		$datas[$n]['days'] = ceil((int)$rows['products_durations']/24/60);
	}
	//}
	//出团日期
	//$datas[$n]['startdayinfo'] = html_to_db(strip_tags(tep_get_display_operate_info($rows['products_id'])));
	$datas[$n]['startdayinfo'] = '天天发团';
	//}
	//具体的出团日期
	$datas[$n]['startdays']="";
	$array_avaliabledate_store = $mcache->product_departure_date($rows['products_id']);
	$array_avaliabledate_store = remove_soldout_dates($rows['products_id'], (array)$array_avaliabledate_store);
	array_multisort($array_avaliabledate_store,SORT_ASC);
	$dayLoopNum = 0;
	foreach((array)$array_avaliabledate_store as $key => $val){
		$dayLoopNum++;
		if($dayLoopNum<200){
			$datas[$n]['startdays'].=substr($key,0,10).',';
		}else{
			break;
		}
	}
	$datas[$n]['startdays'] = substr($datas[$n]['startdays'],0,-1);
	if(strlen($datas[$n]['startdays'])<10){
		$datas[$n]['startdays'] = date('Y-m-d');
	}
	//}
	
	//行程介绍
	$routes_sql = tep_db_query('SELECT * FROM `products_travel` WHERE `products_id` ="'.$rows['products_id'].'" ORDER BY `travel_index` ASC ');
	$daysNum = 0;
	$datas[$n]['route'] = array();
	while($routes_rows = tep_db_fetch_array($routes_sql)){
		$daysNum++;
		$datas[$n]['route'][$daysNum] = $routes_rows;
	}
	//}
	//具体价格日期表
	$datePrice = json_decode(tep_get_product_month_price_datas($rows['products_id']), true);
	$datas[$n]['datePrice'] = array();
	if($datePrice){
		foreach ($datePrice as $year => $val_0){
			foreach ($val_0 as $month => $val_1){
				foreach ($val_1 as $day => $val_2){
					$datas[$n]['datePrice'][] = array('date'=>date('Y-m-d', strtotime($year.'-'.$month.'-'.$day)), 'price'=>str_replace(',','',$val_2['p']), 'priceOfChild'=>str_replace(',','',$val_2['detail']['kids']['text']));
				}
			}
		}
	}
	//print_r($datas[$n]['datePrice']);exit;
	//tep_get_product_date_price($product_id, $availabletourdate);

	$n++;
}

$app_class = '';
switch($api_categories_id){
	case 270: //酷讯
		$app_class = 'kuxun_api';		
	break;
	case 322:	//途牛
		echo "途牛未加"; exit;
	break;
	case 367:	//去哪儿
		$app_class = 'qunar_api';
	break;
	default:
		die('此目录无对应的api');
	break;
}
if($app_class){
	require_once(dirname(__FILE__)."/app_class/{$app_class}.php");
	$dom = new DOMDocument('1.0', 'UTF-8');
	$apps = new $app_class($dom, $datas);
}

if(is_object($dom)){
	header('Content-Type: text/xml; charset=UTF-8');	
	$fileContents = $dom->saveXML();
	echo $fileContents;
	//生成静态页面
	if($apps->ouputStatic === true){
		file_put_contents($xmlfile,$fileContents);
	}	
}
?>