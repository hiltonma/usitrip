<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('includes/application_top.php');
$ajax='true';

//用户结伴同游订单信息编辑
if($_GET['ajax_action']=='1' && (int)$_GET['travel_companion_id']){
	
	$travel_companion_id = (int)$_GET['travel_companion_id'];
	//取出旧数据以修改电子参团凭证用
	$old_sql = tep_db_query('SELECT guest_name, products_id FROM `orders_travel_companion` WHERE orders_travel_companion_id="'.$travel_companion_id.'" AND orders_id="'.(int)$_POST['order_id'].'" Limit 1');
	$old_rows = tep_db_fetch_array($old_sql);
	$old_name = $old_rows['guest_name'];
	$old_products_id = $old_rows['products_id'];
	
	
	//修改中文名
	$ch_name = array();
	foreach((array)$_POST['js_ch_name'] as $key => $val){
		if((int)$_GET['travel_companion_id'] == $key){
			$ch_name[$key] = $val;
		}
	}
	//修改英文名
	$en_name = array();
	foreach((array)$_POST['js_en_name_xin'] as $key => $val){
		if((int)$_GET['travel_companion_id'] == $key){
			$en_name[$key] = $val.' '.$_POST['js_en_name_min'][$key];
		}
	}
	
	$final_name = $ch_name[$travel_companion_id].'  ['.$en_name[$travel_companion_id].']';
	$final_name = html_to_db(ajax_to_general_string($final_name));
	tep_db_query('update orders_travel_companion SET guest_name ="'.$final_name.'" WHERE orders_travel_companion_id="'.$travel_companion_id.'" AND orders_id="'.(int)$_POST['order_id'].'"');
	
	//修改电子参团凭证人名
	tep_db_query('update '.TABLE_ORDERS_PRODUCTS_ETICKET.' SET guest_name = REPLACE(guest_name, "'.$old_name.'", "'.$final_name.'" )  WHERE products_id="'.(int)$old_products_id.'" AND orders_id="'.(int)$_POST['order_id'].'"');

	echo general_to_ajax_string(db_to_html($final_name));
}
?>