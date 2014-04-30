<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php');
header('Content-Type:text/html;charset='.CHARSET);

$ajax = true;

if(in_array($_GET['action'], array("get_end_city", "get_start_city", "get_day_type"))){
	$_tmpArray['startCity'] = array();
	$_tmpArray['endCity'] = array();
	$_tmpArray['dayType'] = array();
	if((int)$_POST['d'] && tep_not_null($_POST['fcw']) && tep_not_null($_POST['tcw'])){}else{
		// 根据出发城市取得对应的目的地和天数 --Howard {
		if($_GET['action']=="get_end_city"){
			$city = html_to_db(tep_db_prepare_input(trim(ajax_to_general_string($_POST['fcw']))));
			//返回目的景点{
			$sql = tep_db_query('SELECT dctd.end_city_id, ttc.city_id FROM '.TABLE_TOUR_CITY.' ttc, departure_city_to_destinations_attractions dctd WHERE ttc.city_id = dctd.start_city_id and ttc.city= "'.tep_db_input($city).'" and ttc.departure_city_status="1" GROUP BY dctd.end_city_id ORDER BY dctd.end_city_id ASC ');
			$endCities = array();
			$start_city_id = 0;
			while($rows = tep_db_fetch_array($sql)){
				$endCities[]=$rows['end_city_id'];
				$start_city_id = $rows['city_id'];
			}
			
			$endCitiesStr = implode(',',$endCities);
			$end_cities = tep_get_city_names($endCitiesStr);
			foreach((array)$end_cities as $key => $value){
				$_tmpArray['endCity'][] = iconv(CHARSET,'utf-8',db_to_html($value));
			}
			//返回目的景点}
			//返回天数{
			//if(!(int)$_POST['d']){
			$sql_str = 'SELECT DISTINCT products_durations FROM `products` WHERE 1 and FIND_IN_SET("'.$start_city_id.'",departure_city_id) and products_status="1" and products_durations_type="0" order by products_durations ';
			$sql = tep_db_query($sql_str);
			$_array = array();
			while($rows = tep_db_fetch_array($sql)){
				switch (true) {
					case $rows['products_durations'] <= 1 :
						$_array[] = '1';
						break;
					case $rows['products_durations'] >= 2 && $rows['products_durations'] < 3 :
						$_array[] = '2';
						break;
					case $rows['products_durations'] >= 3 && $rows['products_durations'] < 4 :
						$_array[] = '3';
						break;
					case $rows['products_durations'] >= 4 && $rows['products_durations'] < 5 :
						$_array[] = '4';
						break;
					case $rows['products_durations'] >= 5 && $rows['products_durations'] < 6 :
						$_array[] = '5';
						break;
					case $rows['products_durations'] >= 6 && $rows['products_durations'] < 7 :
						$_array[] = '6';
						break;
					case $rows['products_durations'] >= 7 && $rows['products_durations'] < 8 :
						$_array[] = '7';
						break;
					case $rows['products_durations'] >= 8 && $rows['products_durations'] < 9 :
						$_array[] = '8';
						break;
					case $rows['products_durations'] >= 9 && $rows['products_durations'] < 10 :
						$_array[] = '9';
						break;
					case $rows['products_durations'] >= 10 && $rows['products_durations'] < 11 :
						$_array[] = '10';
						break;
					case $rows['products_durations'] >= 11 && $rows['products_durations'] < 12 :
						$_array[] = '11';
						break;
				}
			}
			if (in_array('2', $_array) || in_array('3', $_array)) {
				$_array[] = '12';
			}
			if (in_array('4', $_array) || in_array('5', $_array)) {
				$_array[] = '13';
			}
			if (in_array('6', $_array) || in_array('7', $_array)) {
				$_array[] = '14';
			}
			if (in_array('8', $_array) || in_array('9', $_array)) {
				$_array[] = '15';
			}
			if (in_array('10', $_array) || in_array('11', $_array)) {
				$_array[] = '16';
			}
			$_array = array_unique($_array);
			$_str = implode(',',$_array);
			if(tep_not_null($_str)){
				$_tmpArray['dayType'] = explode(',',$_str);
			}
			//}
			//返回天数}		
		}
		// 根据出发城市取得对应的目的地和天数 }
		// 根据目的地取得对应的出发城市和天数 --Howard {
		if($_GET['action']=="get_start_city"){
			$end_city = html_to_db(tep_db_prepare_input(trim(ajax_to_general_string($_POST['tcw']))));
			$sql = tep_db_query('SELECT dctd.start_city_id FROM tour_city ttc, departure_city_to_destinations_attractions dctd WHERE ttc.city_id = dctd.end_city_id and ttc.city= "'.tep_db_input($end_city).'" GROUP BY dctd.start_city_id ORDER BY dctd.start_city_id ASC ');
			$startCities = array();
			while($rows = tep_db_fetch_array($sql)){
				$startCities[]=$rows['start_city_id'];
			}
			$startCitiesStr = implode(',',$startCities);
			$start_cities = tep_get_city_names($startCitiesStr);
			foreach((array)$start_cities as $key => $value){
				$_tmpArray['startCity'][] = iconv(CHARSET,'utf-8',db_to_html($value));
			}
			//返回天数{
			//if(!(int)$_POST['d']){
			$end_city_attraction_id = tep_get_city_id($end_city);
			$sql_str = 'SELECT DISTINCT p.products_durations FROM `products` p, products_destination pd WHERE p.products_id=pd.products_id and (FIND_IN_SET("'.$end_city_attraction_id.'",departure_end_city_id) || pd.city_id="'.$end_city_attraction_id.'") and p.products_status="1" and p.products_durations_type="0" group by p.products_durations order by p.products_durations ';
			$sql = tep_db_query($sql_str);
			$_array = array();
			while($rows = tep_db_fetch_array($sql)){
				switch (true) {
					case $rows['products_durations'] <= 1 :
						$_array[] = '1';
						break;
					case $rows['products_durations'] >= 2 && $rows['products_durations'] < 3 :
						$_array[] = '2';
						break;
					case $rows['products_durations'] >= 3 && $rows['products_durations'] < 4 :
						$_array[] = '3';
						break;
					case $rows['products_durations'] >= 4 && $rows['products_durations'] < 5 :
						$_array[] = '4';
						break;
					case $rows['products_durations'] >= 5 && $rows['products_durations'] < 6 :
						$_array[] = '5';
						break;
					case $rows['products_durations'] >= 6 && $rows['products_durations'] < 7 :
						$_array[] = '6';
						break;
					case $rows['products_durations'] >= 7 && $rows['products_durations'] < 8 :
						$_array[] = '7';
						break;
					case $rows['products_durations'] >= 8 && $rows['products_durations'] < 9 :
						$_array[] = '8';
						break;
					case $rows['products_durations'] >= 9 && $rows['products_durations'] < 10 :
						$_array[] = '9';
						break;
					case $rows['products_durations'] >= 10 && $rows['products_durations'] < 11 :
						$_array[] = '10';
						break;
					case $rows['products_durations'] >= 11 && $rows['products_durations'] < 12 :
						$_array[] = '11';
						break;
				}
			}
			if (in_array('2', $_array) || in_array('3', $_array)) {
				$_array[] = '12';
			}
			if (in_array('4', $_array) || in_array('5', $_array)) {
				$_array[] = '13';
			}
			if (in_array('6', $_array) || in_array('7', $_array)) {
				$_array[] = '14';
			}
			if (in_array('8', $_array) || in_array('9', $_array)) {
				$_array[] = '15';
			}
			if (in_array('10', $_array) || in_array('11', $_array)) {
				$_array[] = '16';
			}
			$_array = array_unique($_array);
			$_str = implode(',',$_array);
			if(tep_not_null($_str)){
				$_tmpArray['dayType'] = explode(',',$_str);
			}
			//}
			//返回天数}		
		}
		// 根据目的地取得对应的出发城市和天数 }
	}
	echo json_encode($_tmpArray);
	exit;
}

//SELECT p.`departure_city_id`,p.`departure_end_city_id` From products_to_categories p2c,products p where 1 and p.products_status = '1' and p.products_id = p2c.products_id GROUP BY `departure_end_city_id`
if($_GET['datatype']=='fcw' || $_GET['datatype']=='tcw'){
	if($_GET['datatype']=='fcw'){	//取得出发城市
		$fcw = trim(tep_db_prepare_input(ajax_to_general_string($_POST['val'])));
		$sql = tep_db_query('SELECT ttc.city FROM '.TABLE_TOUR_CITY.' ttc, departure_city_to_destinations_attractions dctd WHERE ttc.city_id = dctd.start_city_id and ttc.city Like Binary "'.tep_db_input(html_to_db($fcw)).'%" and ttc.departure_city_status="1" and ttc.is_attractions!="1" GROUP BY ttc.city_id ORDER BY dctd.start_city_id ASC Limit 10 ');
		
		while($rows = tep_db_fetch_array($sql)){
			//$startCities[]=$rows['start_city_id'].$rows['city'];
			echo db_to_html(tep_db_output($rows['city']))."\n";
		}
	}
	if($_GET['datatype']=='tcw'){	//取得目的城市[要根据出发城市和目的城市输入的数据取得]
		$tcw = trim(tep_db_prepare_input(ajax_to_general_string($_POST['val'])));
		$fcw = trim(tep_db_prepare_input($_GET['val0']));
		if(tep_not_null($tcw)){
			$sql = tep_db_query('SELECT ttc.city FROM '.TABLE_TOUR_CITY.' ttc, departure_city_to_destinations_attractions dctd WHERE ttc.city_id = dctd.end_city_id and ttc.city Like Binary "'.tep_db_input(html_to_db($tcw)).'%" and ttc.is_attractions!="1" GROUP BY ttc.city_id ORDER BY dctd.end_city_id ASC Limit 10 ');
			while($rows = tep_db_fetch_array($sql)){
				echo db_to_html(tep_db_output($rows['city']))."\n";
			}
		}elseif(tep_not_null($fcw)){
			$sql = tep_db_query('SELECT dctd.end_city_id FROM '.TABLE_TOUR_CITY.' ttc, departure_city_to_destinations_attractions dctd WHERE ttc.city_id = dctd.start_city_id and ttc.city Like Binary "'.tep_db_input(html_to_db($fcw)).'%" and ttc.is_attractions!="1" GROUP BY dctd.end_city_id ORDER BY dctd.end_city_id ASC Limit 10 ');
			$endCities = array();
			while($rows = tep_db_fetch_array($sql)){
				$endCities[] = $rows['end_city_id'];
			}
			$endCitiesStr = implode(',',$endCities);
			$end_cities = tep_get_city_names($endCitiesStr);
			if(is_array($end_cities) && tep_not_null($end_cities[0])){
				foreach($end_cities as $key => $val){
					echo db_to_html($val)."\n";
				}
			}
		}
		
	}
	/*
	require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');
	$dataTables = TABLE_PRODUCTS_TO_CATEGORIES . " p2c," . TABLE_PRODUCTS . " p ";//要查询的常规表
	$dataWhere = " where 1 and p.products_status = '1' and p.products_id = p2c.products_id ";//要查询的常规条件
	$clunms = 'departure_city_id';
	$_GET['datatype']=='tcw' && $clunms = 'departure_end_city_id';
	$city_sql = "SELECT p.`{$clunms}` From {$dataTables} {$dataWhere} GROUP BY p.`{$clunms}`";
	$query = tep_db_query($city_sql);
	$_City_ID = array();
	$_City_ID[0] = 0;
	while($row = tep_db_fetch_array($query)){
		if($row[$clunms]){
			$row[$clunms] = explode(',',$row[$clunms]);
			foreach($row[$clunms] as $v){
				$v && $_City_ID[$v] = $v;
			}
		}
	}
	
	$_City_ID = "'".join("','",$_City_ID)."'";
	
	$where = '';
	if(tep_not_null($_POST['val'])){
		$_POST['val'] =trim(utf8tohtml(urldecode($_POST['val'].'')));
		$where .= " and c.city like Binary '%".html_to_db($_POST['val'])."%'";
	}
	if($_GET['datatype']=='fcw'){
		$where .= " and c.departure_city_status='1' and c.`is_attractions`!='1'";
	}
	$limit_num = 10;
	if((int)$_POST['limit']){
		$limit_num = (int)$_POST['limit'];
	}
	
	$sql = "SELECT c.city_id AS id, c.city AS name,c.departure_city_status,c.`is_attractions` FROM tour_city c, zones z, countries AS co
		WHERE c.state_id = z.zone_id AND z.zone_country_id = co.countries_id
		AND c.city_id IN ({$_City_ID}) {$where}  ORDER BY c.city_id DESC limit {$limit_num}";
		
	$query = tep_db_query($sql);
	
	while($row = tep_db_fetch_array($query)){
		echo db_to_html($row['name'])."\n";
	}
	*/
}else{
	if(!tep_not_null($_POST['val'])){	//当没有任何值时显示常量中定义的值
		if(defined('DEFAULT_KEYWORDS') && tep_not_null(DEFAULT_KEYWORDS)){
			$key_string = explode(',',DEFAULT_KEYWORDS);
			foreach((array)$key_string as $key => $val){
				if(tep_not_null($val)){ echo db_to_html(trim($val))."\n"; }
			}
		}
		exit;
	}
	
	//把景点列出
	$city = tep_db_output(ajax_to_general_string(tep_db_prepare_input($_POST['val'])));
	$limit_num = 20;
	if((int)$_POST['limit']){
		$limit_num = (int)$_POST['limit'];
	}
	//$sql = tep_db_query('SELECT city as keyword FROM `tour_city` where city Like Binary "%'.$city.'%" and departure_city_status ="1" and is_attractions >0 Limit '.$limit_num);
	
	$sql = tep_db_query('SELECT thesaurus_text as keyword FROM `keyword_thesaurus` where thesaurus_text Like Binary "%'.html_to_db($city).'%" and thesaurus_state ="1" and use_search ="1" and thesaurus_text_length<10 Order By thesaurus_text_length Limit '.$limit_num);
	
	while($rows = tep_db_fetch_array($sql)){
		echo db_to_html($rows["keyword"])."\n";	
	}
}
?>