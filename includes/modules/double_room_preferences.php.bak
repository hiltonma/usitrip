<?php
/**
 * //双人一房的优惠模块
//根据Y团类型优惠N元每人，返回值是N元
//数据库名称products_double_room_preferences
 */
function double_room_preferences($products_id, $departure_date=''){
	$products_id = tep_get_prid($products_id);
	if(!(int)$products_id){ return false; }
	if($departure_date==""){ $departure_date = date('Y-m-d');}
	if(tep_not_null($departure_date)){
		$departure_date = substr($departure_date,0,10);
		if(strlen($departure_date)!=10){
			echo "Date erroe";
		}
	}

	$sql_str = ('SELECT products_double_room_preferences_id,value,excluding_dates FROM `products_double_room_preferences` WHERE products_id="'.(int)$products_id.'" AND status="1" AND people_number="2" AND (products_departure_date_begin <= "'.$departure_date.' 00:00:00" || products_departure_date_begin="0000-00-00 00:00:00" || products_departure_date_begin="") AND (products_departure_date_end >="'.$departure_date.' 23:59:59" || products_departure_date_end="0000-00-00 00:00:00" || products_departure_date_end="" ) Order By value DESC limit 1 ');
	$sql = tep_db_query($sql_str);
	//echo $sql_str;
	$row = tep_db_fetch_array($sql);
	if((int)$row['products_double_room_preferences_id']){
		if(strstr($row['excluding_dates'],$departure_date)){
			return false;
		}
		return $row['value'];
	}
	return false;
}

?>