<?php
require('includes/application_top.php');

//ini_set('display_errors' ,'1');
//error_reporting(E_ALL^E_NOTICE);

//权限判定
$order_dispaly_status_control_access =$login_groups_id == '1' ? true:false;
$VIN_NOTICE = '';
$VIN_ERROR = '';
//权限判定结束
if(!defined('VIN_TMP_PATH'))define('VIN_TMP_PATH' , realpath('./templates/ver1/').DIRECTORY_SEPARATOR); //模板路径
//设置模板变量

/*$not_in_ids = '11,47,100011,100024,100034,100035,100039,100028,100041,100047,100049,100050,100058,100059';
$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id not in(" . $not_in_ids . ")  AND language_id = '" . (int) $languages_id . "' AND orders_status_display='1'  ORDER BY `orders_status_name` ASC ");
$orders_statuses = array();
while ($orders_status = tep_db_fetch_array($orders_status_query)) {

    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
        'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}

print_r($orders_status_array);*/
function fetchOrderTotal($orders_id , $usd2cnyRate=7,$rateValue=1){
	$query = 'SELECT value  FROM '.TABLE_ORDERS_TOTAL.' WHERE orders_id='.intval($orders_id)." AND class='ot_total'";
	$info = tep_db_fetch_array(tep_db_query($query));
	$totalUSD = floatval($info['value']);
	$totalCNY = $usd2cnyRate == 0 ? "Null":($totalUSD/(float)$rateValue)*(float)$usd2cnyRate;	
	$totalCNY = floor($totalCNY*100)/100;
	return array('usd'=>$totalUSD  , 'cny'=>$totalCNY);
}
function isDate($str){
	return preg_match('/^\d\d\d\d-\d{1,2}-\d{1,2}$/',$str) == 1;
}
/**
*根据会员积分卡 查询用户积分卡的激活状态
**/
function getRelateCustomer($pointcards_id){

	$sql = "SELECT c.customers_firstname ,ci.customers_info_date_account_created, c.customers_id FROM ".TABLE_POINTCARDS_TO_CUSTOMERS.' p INNER JOIN '. TABLE_CUSTOMERS.' c ON  p.customers_id = c.customers_id    INNER JOIN '.TABLE_CUSTOMERS_INFO.' ci ON  p.customers_id = ci.customers_info_id   WHERE p.pointcards_id = '.intval($pointcards_id);
	$query = tep_db_query($sql);
	$data = tep_db_fetch_array($query);
	if($data ==false){
		return array('customer_info'=>'','created'=>'');
	}else{
		$info = db_to_html(''.$data['customers_firstname']).' &nbsp;&nbsp;<a href="'.tep_href_link(FILENAME_POINTCARDS_ORDER, ''.'pointcards_id_start='.$pointcards_id, 'NONSSL').'">查看消费情况 </a>- <a href="'.tep_href_link(FILENAME_CUSTOMERS, ''.'page=1&action=edit&cID='.$data['customers_id'], 'NONSSL').'">查看会员资料</a>';

		return array('customer_info'=>$info,'created'=>$data['customers_info_date_account_created']);
	}

	
}

if($_GET['action'] == 'status'){
	//卡号
	$cond = array();
		if(is_numeric($_GET['pointcards_id_start']) && is_numeric($_GET['pointcards_id_end'])){
			$cond[] = '( p.pointcards_id >= '.intval($_GET['pointcards_id_start']).' AND  p.pointcards_id <= '.intval(tep_db_prepare_input($_GET['pointcards_id_end'])).")";
		}elseif(is_numeric($_GET['pointcards_id_start'])){
			$cond[]= ' p.pointcards_id = '.intval(tep_db_prepare_input($_GET['pointcards_id_start']));
		}else{}
	if(!empty($cond)) $condSql = ' WHERE '.implode(' AND ',$cond);else $condSql=' WHERE 1';
	//列印所有积分卡的激活情况
	$sql = "SELECT *	 FROM ".TABLE_POINTCARDS.' p '.$condSql.' ORDER by p.pointcards_id ASC';
	$current_page = $_GET['page'];
	$max_rows_per_page =50;
	$pager = new splitPageResults($current_page,$max_rows_per_page,$sql,$query_num_rows);
	$pageTotal = ceil($query_num_rows/$max_rows_per_page );
	$status_query = tep_db_query($sql);
	$records = array();
	while ($order = tep_db_fetch_array($status_query)) {		
		$customerInfo = getRelateCustomer($order['pointcards_id']);
		$records[] = array_merge($order,$customerInfo);
	}

	$get = $_GET;
		unset($get['page']);
		$split_left_content = $pager->display_count($query_num_rows, $max_rows_per_page, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		$split_right_content = $pager->display_links($query_num_rows, $max_rows_per_page, MAX_DISPLAY_PAGE_LINKS,intval($_GET['page']) , http_build_query($get ));
	include(VIN_TMP_PATH.'pointcards_status.php');
}else{
		//处理查询 INNER JOIN
		//卡号
		$cond = array();
		if(is_numeric($_GET['pointcards_id_start']) && is_numeric($_GET['pointcards_id_end'])){
			$cond[] = '( p.pointcards_id >= '.intval($_GET['pointcards_id_start']).' AND  p.pointcards_id <= '.intval(tep_db_prepare_input($_GET['pointcards_id_end'])).")";
		}elseif(is_numeric($_GET['pointcards_id_start'])){
			$cond[]= ' p.pointcards_id = '.intval(tep_db_prepare_input($_GET['pointcards_id_start']));
		}else{}
		//时间
		if(isDate($_GET['timeStart'])&& isDate($_GET['timeEnd'])){
			$timeStart = tep_db_prepare_input($_GET['timeStart']);
			$timeEnd = tep_db_prepare_input($_GET['timeEnd']);
			 $cond[] = " ( o.date_purchased > '".$timeStart." 0:0:0' AND o.date_purchased < '".$timeEnd."   23:59:59' )";
		}else if(isDate($_GET['timeStart'])){
			$timeStart = tep_db_prepare_input($_GET['timeStart']);
			$cond[] = "  o.date_purchased > '".$timeStart." 0:0:0'";
		}else{
		}
		//状态
		$status = intval($_GET['configuration_value']);
		if($status != 0 ) $cond[] = 'orders_status = '.$status;
		//排序
		$order =  $_GET['order'] == 'asc'  ?' ASC' : ' DESC';
		$sqlOrder = '';
		switch($_GET['sort'] ){
			case'date_purchased':$sqlOrder =  'o.date_purchased '.$order;break;
			case'customers_id':$sqlOrder =  'o.customers_id '.$order;break;
			case'orders_id':$sqlOrder =  'o.orders_id '.$order;break;
			case'order_cost':$sqlOrder =  'o.order_cost '.$order;break;
			case'pointcards_id':$sqlOrder =  'p.pointcards_id '.$order;break;
			default:{$sqlOrder =  'p.pointcards_id ASC';}
		}
		//
		$sql = "SELECT p.pointcards_id,o.orders_id ,o.customers_name,o.customers_id,o.currency,o.currency_value,
		o.us_to_cny_rate,o.date_purchased,o.order_cost,o.orders_status 
			 FROM ".TABLE_POINTCARDS_TO_CUSTOMERS.' p INNER JOIN '. TABLE_ORDERS.' o ON  p.customers_id = o.customers_id ';
		if(!empty($cond)) $sql = $sql.' WHERE '.implode(' AND ',$cond);
		$sql.=' ORDER BY '.$sqlOrder;
		//echo $sql;
		$current_page = $_GET['page'];
		$max_rows_per_page =50;
		$pager = new splitPageResults($current_page,$max_rows_per_page,$sql,$query_num_rows);
		$pageTotal = ceil($query_num_rows/$max_rows_per_page );
		$orders_query = tep_db_query($sql);
		$records = array();
		while ($order = tep_db_fetch_array($orders_query)) {		
			$currenciesInfo = fetchOrderTotal($order['orders_id'],floatval($order['us_to_cny_rate']));
			$order['order_cost_cny'] = $currenciesInfo['cny'];
			$order['order_cost_usd'] = $currenciesInfo['usd'];
			$records[] = $order;
		}
		if(empty($records)){	$VIN_ERROR = db_to_html('没有查到该会员积分卡用户的订单资料,请修改搜索条件进行查询');}

		$get = $_GET;
		unset($get['page']);
		$split_left_content = $pager->display_count($query_num_rows, $max_rows_per_page, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		$split_right_content = $pager->display_links($query_num_rows, $max_rows_per_page, MAX_DISPLAY_PAGE_LINKS,intval($_GET['page']) , http_build_query($get ));

		//载入模板以及layout
		include(VIN_TMP_PATH.'pointcards_search.php');
}
//----------
require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>