<?php
require('includes/application_top.php');

//added sql start
function added_sql(){
	$fields_array = array();
	$fields = mysql_list_fields(DB_DATABASE,'orders_products');
	while($r=mysql_fetch_field($fields)){  
		$fields_array[] = $r->name;  
	}
	if(!in_array("admin_confirm",$fields_array)){
		mysql_query('
		ALTER TABLE `orders_products` ADD `admin_confirm` ENUM( "Y", "N" ) DEFAULT "N" NOT NULL ,
		ADD `admin_confirm_final_price_cost` DECIMAL( 15, 2 ) NOT NULL ,
		ADD `admin_confirm_customer_invoice_no` VARCHAR( 64 ) NOT NULL ,
		ADD `admin_confirm_customer_invoice_total` DOUBLE NOT NULL ,
		ADD `admin_confirm_date` DATETIME NOT NULL ,
		ADD `admin_confirm_admin_id` INT NOT NULL ;
		');
	}
}
added_sql();
//added sql end

$error_msn = '';
$error = false;
$success_msn = '';
//ajax提交到数据库start {
//确认成本
if($_POST && $_GET['action']=='confirm'){
	tep_db_query('UPDATE `orders_products` SET 
		`admin_confirm` = "Y",
		`admin_confirm_final_price_cost` = "'.tep_db_input(tep_db_prepare_input($_POST['admin_confirm_final_price_cost'])).'",
		`admin_confirm_customer_invoice_no` = "'.tep_db_input(tep_db_prepare_input($_POST['admin_confirm_customer_invoice_no'])).'",
		`admin_confirm_customer_invoice_total` = "'.tep_db_input(tep_db_prepare_input($_POST['admin_confirm_customer_invoice_total'])).'",
		`admin_confirm_date` = NOW(),
		`admin_confirm_admin_id` = "'.(int)$login_id.'" WHERE `orders_products_id` = "'.(int)$_POST['orders_products_id'].' ";
	');
	
	$js_str = '[JS]';
	$js_str .= 'jQuery("#confirm_td_'.(int)$_POST['orders_products_id'].'").html("<font color=#00CC00>match</font>");';
	$js_str .= 'jQuery("#tr_'.(int)$_POST['orders_products_id'].'").css("background", "#FFF");';
	$js_str .= '[/JS]';
	echo $js_str;
	exit;
}
//财务提交备注
if($_POST && $_GET['action']=='update_admin_comment'){
	tep_db_query('UPDATE `orders_products` SET `admin_comment` = "'.ajax_to_general_string(tep_db_input(tep_db_prepare_input($_POST['admin_comment']))).'" WHERE `orders_products_id` = "'.(int)$_POST['orders_products_id'].' ";	');
	$js_str = '[JS]';
	$js_str .= 'jQuery("#CommentForm_'.(int)$_POST['orders_products_id'].'").find("input[type=\'submit\']").val(\'提交成功\');';
	$js_str .= '[/JS]';
	echo $js_str;
	exit;
}
//更新中国财务人员或美国财务人员填写的成本
if($_GET['action']=='submit_cost_and_final_price_china_or_usa' && (int)$_GET['orders_products_id']){
	if(isset($_GET['before_departure_cost'])){
		tep_db_query('UPDATE `orders_products` SET before_departure_cost = "'.tep_db_input(tep_db_prepare_input($_GET['before_departure_cost'])).'" WHERE orders_products_id="'.(int)$_GET['orders_products_id'].'" ');
	}
	if(isset($_GET['china_bookkeeper_final_price_cost'])){
		tep_db_query('UPDATE `orders_products` SET china_bookkeeper_final_price_cost = "'.tep_db_input(tep_db_prepare_input($_GET['china_bookkeeper_final_price_cost'])).'" WHERE orders_products_id="'.(int)$_GET['orders_products_id'].'" ');
	}
	if(isset($_GET['usa_bookkeeper_final_price_cost'])){
		tep_db_query('UPDATE `orders_products` SET usa_bookkeeper_final_price_cost = "'.tep_db_input(tep_db_prepare_input($_GET['usa_bookkeeper_final_price_cost'])).'" WHERE orders_products_id="'.(int)$_GET['orders_products_id'].'" ');
	}
	if(isset($_GET['usa_bookkeeper_final_price'])){
		tep_db_query('UPDATE `orders_products` SET usa_bookkeeper_final_price = "'.tep_db_input(tep_db_prepare_input($_GET['usa_bookkeeper_final_price'])).'" WHERE orders_products_id="'.(int)$_GET['orders_products_id'].'" ');
	}
	echo 'Ajax submit OK!';
	exit;
}
//ajax提交到数据库end }

//js 代码中的变量
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');

$title = BOX_ACCOUNTS_PAYABLE_REPORTS;
$breadcrumb->add($title, tep_href_link('accounts_payable_reports.php'));

//main SQL
$FinalPriceCostFields = ' IF(op.admin_confirm="Y", op.admin_confirm_final_price_cost, op.final_price_cost) ';
$CustomerInvoiceTotalFields = ' IF(op.admin_confirm="Y", op.admin_confirm_customer_invoice_total, op.customer_invoice_total) ';
$CustomerInvoiceNoFields = ' IF(op.admin_confirm="Y", op.admin_confirm_customer_invoice_no, op.customer_invoice_no) ';

$tables = ' `orders_products` op, `orders` o, products p ';
$fields = ' p.provider_tour_code, o.* , op.*, '.$CustomerInvoiceNoFields.' as customer_invoice_no, '.$CustomerInvoiceTotalFields.' as customer_invoice_total, '.$FinalPriceCostFields.' as FinalPriceCost ';
//应付款表的条件是：已下单等确认、系统下单和地接已回复
/*"1-01已下单，需加急核实位置"，
"1-02已下单等确认"，
"1-03已更新给地接"
"2-01地接回复，正在处理中"
"2-02地接已确认"
"2-03地接已确认但需补航班"
"2-04地接已确认但需其他信息"*/
//$where = ' p.products_id = op.products_id AND o.orders_id = op.orders_id AND osh.orders_id = o.orders_id AND osh.orders_status_id IN ('.ORDERS_STATUS_ID.') ';
$where = ' p.products_id = op.products_id AND o.orders_id = op.orders_id AND o.orders_id in (select DISTINCT osh.orders_id FROM orders_status_history osh where osh.orders_status_id IN (100009,100072,100102,100127,100122,100110,100102,100103,100116) ) ';
$gruop_by = ' GROUP BY op.orders_products_id ';
$order_by = ' o.orders_id DESC ';
//where

//排序的连接标签 start
$orders_sort_up_img = $purchased_sort_up_img = $departure_sort_up_img = $invoice_amount_sort_up_img = $invoice_number_sort_up_img = $cost_sort_up_img = $sales_sort_up_img = $gp_sort_up_img ='arrow_up_normal.gif';
$orders_sort_down_img = $purchased_sort_down_img = $departure_sort_down_img = $invoice_amount_sort_down_img = $invoice_number_sort_down_img = $cost_sort_down_img = $sales_sort_down_img =$gp_sort_down_img = 'arrow_down_normal.gif';

if(!tep_not_null($_GET['sort_type']) || $_GET['sort_type']=='orders'){
	if($_GET['sort']=='up'){
		$orders_sort_up_img = 'arrow_up.gif';
		$order_by = ' o.orders_id ASC ';
	}else{
		$orders_sort_down_img = 'arrow_down.gif';
		$order_by = ' o.orders_id DESC ';
	}
}else{
	switch($_GET['sort_type']){
		case 'purchased':
			if($_GET['sort']=='up'){
				$purchased_sort_up_img = 'arrow_up.gif';
				$order_by = ' o.date_purchased ASC ';
			}else{
				$purchased_sort_down_img = 'arrow_down.gif';
				$order_by = ' o.date_purchased DESC ';
			}
		break;
		case 'departure':
			if($_GET['sort']=='up'){
				$departure_sort_up_img = 'arrow_up.gif';
				$order_by = ' op.products_departure_date ASC ';
			}else{
				$departure_sort_down_img = 'arrow_down.gif';
				$order_by = ' op.products_departure_date DESC ';
			}
		break;
		case 'invoice_amount':
			if($_GET['sort']=='up'){
				$invoice_amount_sort_up_img = 'arrow_up.gif';
				$order_by = ' customer_invoice_total ASC ';
				
			}else{
				$invoice_amount_sort_down_img = 'arrow_down.gif';
				$order_by = ' customer_invoice_total DESC ';
			}
		break;
		case 'invoice_number':
			if($_GET['sort']=='up'){
				$invoice_number_sort_up_img = 'arrow_up.gif';
				$order_by = ' customer_invoice_no ASC ';
				
			}else{
				$invoice_number_sort_down_img = 'arrow_down.gif';
				$order_by = ' customer_invoice_no DESC ';
			}
		break;
		case 'cost':
			if($_GET['sort']=='up'){
				$cost_sort_up_img = 'arrow_up.gif';
				$order_by = ' FinalPriceCost ASC ';
			}else{
				$cost_sort_down_img = 'arrow_down.gif';
				$order_by = ' FinalPriceCost DESC ';
			}
		break;
		case 'sales':
			if($_GET['sort']=='up'){
				$sales_sort_up_img = 'arrow_up.gif';
				$order_by = ' op.final_price ASC ';
			}else{
				$sales_sort_down_img = 'arrow_down.gif';
				$order_by = ' op.final_price DESC ';
			}
		break;
		case 'gp':
			$fields .= ' , ((((op.final_price)-('.$FinalPriceCostFields.'))/abs(op.final_price))*100) as GrossProfit ';
			if($_GET['sort']=='up'){
				$gp_sort_up_img = 'arrow_up.gif';
				$order_by = ' GrossProfit ASC ';
			}else{
				$gp_sort_down_img = 'arrow_down.gif';
				$order_by = ' GrossProfit DESC ';
			}
		break;
		
	}
}

$ex_params = '&'.tep_get_all_get_params(array('page','y','x', 'action','sort_type','sort'));

$orders_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=orders&sort=up'.$ex_params).'"><img src="images/'.$orders_sort_up_img.'" /></a>';
$orders_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=orders&sort=down'.$ex_params).'"><img src="images/'.$orders_sort_down_img.'" /></a>';

$purchased_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=purchased&sort=up'.$ex_params).'"><img src="images/'.$purchased_sort_up_img.'" /></a>';
$purchased_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=purchased&sort=down'.$ex_params).'"><img src="images/'.$purchased_sort_down_img.'" /></a>';
													  
$departure_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=departure&sort=up'.$ex_params).'"><img src="images/'.$departure_sort_up_img.'" /></a>';
$departure_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=departure&sort=down'.$ex_params).'"><img src="images/'.$departure_sort_down_img.'" /></a>';
													  
$invoice_amount_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=invoice_amount&sort=up'.$ex_params).'"><img src="images/'.$invoice_amount_sort_up_img.'" /></a>';
$invoice_amount_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=invoice_amount&sort=down'.$ex_params).'"><img src="images/'.$invoice_amount_sort_down_img.'" /></a>';
														   
$cost_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=cost&sort=up'.$ex_params).'"><img src="images/'.$cost_sort_up_img.'" /></a>';
$cost_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=cost&sort=down'.$ex_params).'"><img src="images/'.$cost_sort_down_img.'" /></a>';

$sales_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=sales&sort=up'.$ex_params).'"><img src="images/'.$sales_sort_up_img.'" /></a>';
$sales_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=sales&sort=down'.$ex_params).'"><img src="images/'.$sales_sort_down_img.'" /></a>';

$gp_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=gp&sort=up'.$ex_params).'"><img src="images/'.$gp_sort_up_img.'" /></a>';
$gp_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=gp&sort=down'.$ex_params).'"><img src="images/'.$gp_sort_down_img.'" /></a>';

$invoice_number_sort_up_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=invoice_number&sort=up'.$ex_params).'"><img src="images/'.$invoice_number_sort_up_img.'" /></a>';
$invoice_number_sort_down_link = '<a href="'.tep_href_link('accounts_payable_reports.php','sort_type=invoice_number&sort=down'.$ex_params).'"><img src="images/'.$invoice_number_sort_down_img.'" /></a>';

//排序的连接标签 end

//搜索start
if(tep_not_null($_GET['S_orders_id'])){
	$where .=' AND o.orders_id ="'.(int)$_GET['S_orders_id'].'" ';
	$gruop_by = ' GROUP BY op.orders_products_id ';
	$S_Provider = $_GET['S_Provider']="";
	$S_start_date = $_GET['S_start_date']="";
	$S_end_date = $_GET['S_end_date']="";
	$Filter = $_GET['Filter']="";
}else{
	
	if(tep_not_null($_GET['S_orders_owners'])){	//工号搜索
		$where .= ' AND FIND_IN_SET("'.tep_db_input(tep_db_prepare_input($_GET['S_orders_owners'])).'",o.orders_owners) ';
	}
	if(tep_not_null($_GET['S_provider_tour_code'])){	//地接团号
		$where .= ' AND p.provider_tour_code Like "'.tep_db_input(tep_db_prepare_input($_GET['S_provider_tour_code'])).'%" ';
	}
	if(tep_not_null($_GET['S_customer_invoice_no'])){	//发票号
		$where .= ' AND (op.customer_invoice_no Like "'.tep_db_input(tep_db_prepare_input($_GET['S_customer_invoice_no'])).'%" ';
		//　按文档要求，增加对财务备注的搜索 by lwkai add 2012-10-29
		$where .= ' OR op.admin_comment Like "%' . tep_db_input(tep_db_prepare_input($_GET['S_customer_invoice_no'])) . '%") ';
	}
	if(tep_not_null($_GET['S_Provider'])){
		$where .= ' AND p.products_id = op.products_id AND (';
		$where .= ' p.agency_id="'.(int)$_GET['S_Provider'].'" ';
		
		$agency_sql_str = 'SELECT tta.agency_code FROM tour_travel_agency tta WHERE tta.agency_id="'.(int)$_GET['S_Provider'].'" ';
		$agency_sql = tep_db_query($agency_sql_str);
		$agency_row = tep_db_fetch_array($agency_sql);
		if(tep_not_null($agency_row['agency_code'])){
			$where .=' || (CONCAT(";",p.provider_tour_code_sub,";") Like CONCAT("%;","'.tep_db_prepare_input($agency_row['agency_code']).'",";%") ) ';
		}
		$where .=')';
		$gruop_by = ' GROUP BY op.orders_products_id ';
	}
	
	if(tep_not_null($_GET['S_start_date'])){
		if(!tep_not_null($_GET['S_end_date']) || $_GET['S_start_date']>$_GET['S_end_date']){
			$tmp_array = explode('-',$_GET['S_start_date']);
			$_GET['S_end_date'] = date('Y-m-d', mktime(0,0,0,($tmp_array[1]+1),0,$tmp_array[0]));
		}
		//echo $_GET['S_end_date'];
	}
	if(tep_not_null($_GET['S_end_date'])){
		if(!tep_not_null($_GET['S_start_date']) || $_GET['S_start_date']>$_GET['S_end_date']){
			$tmp_array = explode('-',$_GET['S_end_date']);
			$_GET['S_start_date'] = date('Y-m-d', mktime(0,0,0,$tmp_array[1],1,$tmp_array[0]));
		}
		//echo $_GET['S_start_date'];
	}
	//订单下单类型
	if(isset($_GET['S_is_other_owner']) && $_GET['S_is_other_owner']!==''){
		$where.=' and o.is_other_owner in('.tep_db_prepare_input(tep_db_output(rawurldecode($_GET['S_is_other_owner']))).') ';
	}
	$S_start_date = $_GET['S_start_date'] ? $_GET['S_start_date'] : date('Y').'-01-01';
	$S_end_date = $_GET['S_end_date'] ? $_GET['S_end_date'] : date('Y').'-12-31';
	//默认打开当月的
	/*
	if(!tep_not_null($S_start_date)){
		$S_start_date = date('Y-m')."-01";
	}
	if(!tep_not_null($S_end_date)){
		$tmp_array = explode('-',$S_start_date);
		$S_end_date = date('Y-m-d', mktime(0,0,0,($tmp_array[1]+1),0,$tmp_array[0]));;
	}
	*/
	
	if((int)$_GET['Filter']){
		switch($_GET['Filter']){
			case "1":	//当期应付，出发时间在$S_start_date（含）以后的并在$S_end_date（含）以前的出发的订单
				$where .=' AND op.products_departure_date >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
				$where .=' AND op.products_departure_date <="'.tep_db_prepare_input($S_end_date).' 23:59:59" ';
				$gruop_by = ' GROUP BY op.orders_products_id ';
			break;
			case "2":	//未来支付，出发时间在$S_end_date（不含）以后的订单
				$where .=' AND op.products_departure_date >"'.tep_db_prepare_input($S_end_date).' 00:00:00" ';
				$gruop_by = ' GROUP BY op.orders_products_id ';
			break;
			case "3":	//按购买日期
				$where .=' AND o.date_purchased >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
				$where .=' AND o.date_purchased <="'.tep_db_prepare_input($S_end_date).' 23:59:59" ';
				$gruop_by = ' GROUP BY op.orders_products_id ';
			break;
			case "4":	//amount与Cost不符
				$where .=' AND op.products_departure_date >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
				$where .=' AND op.products_departure_date <="'.tep_db_prepare_input($S_end_date).' 23:59:59" ';
				$where .=' AND ('.$FinalPriceCostFields.' != '.$CustomerInvoiceTotalFields.')';
				$gruop_by = ' GROUP BY op.orders_products_id ';
			break;
			case "5":	//已Match
				$where .=' AND op.admin_confirm="Y" ';
				//$where .=' AND op.admin_confirm_date >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
				//$where .=' AND op.admin_confirm_date <="'.tep_db_prepare_input($S_end_date).' 23:59:59" ';
				$where .=' AND op.products_departure_date >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
				$where .=' AND op.products_departure_date <="'.tep_db_prepare_input($S_end_date).' 23:59:59" ';
				$gruop_by = ' GROUP BY op.orders_products_id ';
			break;
		}
	}else{
		//全部应付，出发时间在$S_start_date（含）以后的订单（和$S_end_date无关了）；
		$where .=' AND op.products_departure_date >="'.tep_db_prepare_input($S_start_date).' 00:00:00" ';
		$gruop_by = ' GROUP BY op.orders_products_id ';
		$Filter = $_GET['Filter'] = "0";
	}
}

$where .= ' AND p.products_model_sub ="" '; //不显示组合团主单的信息

//搜索end

//order by
$sql_str = 'SELECT '.$fields.' FROM '.$tables.' WHERE '.$where.$gruop_by.' ORDER BY '.$order_by;
//echo $sql_str;

$invoice_total =0;
$cost_total =0;
$_final_price_total =0;
$sql_sum_str = 'SELECT ('.$CustomerInvoiceTotalFields.') as amount, ('.$FinalPriceCostFields.') as cost, op.final_price as final_price_total FROM '.$tables.' WHERE '.$where.$gruop_by;
$sql_sum_query = tep_db_query($sql_sum_str);
while($sum = tep_db_fetch_array($sql_sum_query)){
	$invoice_total += $sum['amount'];
	$cost_total += $sum['cost'];
	$_final_price_total+= $sum['final_price_total'];
}
$invoice_total = '$'.number_format($invoice_total,2,'.',',');
$cost_total = '$'.number_format($cost_total,2,'.',',');
$_final_price_total = '$'.number_format($_final_price_total,2,'.',',');
//echo $sql_sum_str."<br>".$invoice_total."<br>";
//echo $sql_str;

//Provider菜单start
$S_ProviderSelectMenu = '';
$provider_array = array(array('id' => '', 'text' => '--ALL--'));
$provider_raw = "select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name asc";
$provider_query = tep_db_query($provider_raw);

while ($provider_result = tep_db_fetch_array($provider_query)) {
	$provider_array[] = array('id' => $provider_result['agency_id'], 'text' => $provider_result['agency_name']);
}
$S_ProviderSelectMenu = tep_draw_pull_down_menu('S_Provider', $provider_array, $_GET['S_Provider'], ' onChange="this.form.submit();" ');
//Provider菜单end

//订单下单类型菜单
$S_is_other_ownerSelectMenu =tep_draw_pull_down_menu('S_is_other_owner',array(array('id'=>'', 'text'=>'----不限----'), array('id'=>'1', 'text'=>'客人自行下单(无销售跟踪和工号链接)'), array('id'=>'3,2,0', 'text'=>'普通订单')),$_GET['is_other_owner']);

if(tep_not_null($_GET['download']) && $_GET['download']=="1"){
	
	//下载到本地
	header("Content-type: text/html; charset=utf-8");	//用utf-8格式下载才行
	$filename = date("YmdHis").'.xls';
	//header("Content-type: text/x-csv");
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Transfer-Encoding:binary");
	header("Content-Disposition: attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
	header("HTTP/1.0 200 OK");
	header("Status: 200 OK");
	ob_start();
	
	$FilterOption = ALL_DEAL_WITH; //'全部应付';
	if($_GET['Filter']=="1"){
		$FilterOption = THE_TAX_CURRENTLY_PAYABLE; //'当期应付';
	}
	if($_GET['Filter']=="2"){
		$FilterOption = FUTURE_PAYMENT; //'未来支付';
	}
	if($_GET['Filter']=="3"){
		$FilterOption = PURCHASED_DATE_PAYMENT; //'按购买日期';
	}
	if($_GET['Filter']=="4"){
		$FilterOption = COST_NOT_MATCH_AMOUNT; //'amount与Cost不符';
	}
	if($_GET['Filter']=="5"){
		$FilterOption = COST_MATCH_AMOUNT; //'已Match';
	}
	$ProviderStr = 'All Provider';
	if((int)$_GET['S_Provider']){
		for($i=0; $i<sizeof($provider_array); $i++){
			if($provider_array[$i]['id']==$_GET['S_Provider']){
				$ProviderStr = $provider_array[$i]['text'];
				break;
			}
		}
	}
	
	//echo '"订单ID","工号","客人下单时间","客人付款时间","出发时间","地接团号","发票号","发票金额","底价","出团后底价","USA财务底价","实收价","GP(%)","是/否已确认"'."\n";
	echo '<table border="1" cellpadding="0" cellspacing="0">';
	echo '<tr><td>订单ID</td><td>工号</td><td>客人下单时间</td><td>客人付款时间</td><td>出发时间</td><td>地接团号</td><td>发票号</td><td>发票金额($)</td><td>底价($)</td><td>出团前底价($)</td><td>出团后底价($)</td><td>USA财务底价($)</td><td>实收价($)</td><td>USA财务实收价($)</td><td>GP(%)</td><td>订单状态</td><td>是/否已确认</td><td>财务备注</td></tr>';
}else{
	
	$query_numrows = 0;
	$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = 20;
	$split = new splitPageResults($_GET['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $query_numrows);
}
$query = tep_db_query($sql_str);
$datas = array();
$loop = 0;
while($rows = tep_db_fetch_array($query)){
	$datas[$loop] = $rows;
	$job_19 = tep_get_order_owner_jobs_id($datas[$loop]['orders_id']);
	$datas[$loop]['job_19'] = ($job_19 == '19') ? $job_19 : '';
	$datas[$loop]['orderStatus'] = tep_db_output(tep_get_orders_status_name($rows['orders_status']));
	$datas[$loop]['_customer_invoice_total'] = '$'.number_format($rows['customer_invoice_total'],2,'.',',');
	$datas[$loop]['_final_price_cost'] = '$'.number_format($rows['FinalPriceCost'],2,'.',',');
	$datas[$loop]['_final_price'] = '$'.number_format($rows['final_price'],2,'.',',');
	
	if(!isset($datas[$loop]['GrossProfit'])){
		if($datas[$loop]['final_price'] != 0){
			$datas[$loop]['GrossProfit'] = tep_round((((($datas[$loop]['final_price'])-($datas[$loop]['FinalPriceCost']))/abs($datas[$loop]['final_price']))*100), 0);
		}else{
			$datas[$loop]['GrossProfit'] = "0";
		}
	}else{
		$datas[$loop]['GrossProfit'] = tep_round($datas[$loop]['GrossProfit'], 0);
	}
	$datas[$loop]['GrossProfit'] .= '%';
	
	$datas[$loop]['cost_color'] = '';
	if($datas[$loop]['_customer_invoice_total']!=$datas[$loop]['_final_price_cost']){
		$datas[$loop]['cost_color'] = 'color:#F00';
	}
	
	if(!tep_not_null($rows['agency_name'])){	//如果没有Provider字段则需要取得Provider
		$agency_sql = tep_db_query('SELECT p.provider_tour_code_sub, tta.agency_name FROM `products` p, tour_travel_agency tta WHERE tta.agency_id = p.agency_id AND p.products_id="'.$rows['products_id'].'" ');
		$agency_row = tep_db_fetch_array($agency_sql);
		if(tep_not_null($agency_row['provider_tour_code_sub'])){
			$provider_tour_code_sub = get_provider_tour_code_sub($rows['products_id']);
			if(is_array($provider_tour_code_sub) && (int)sizeof($provider_tour_code_sub)){
				foreach((array)$provider_tour_code_sub as $val){
					$sub_agency_sql = tep_db_query('SELECT agency_name FROM tour_travel_agency WHERE agency_code="'.tep_db_prepare_input($val).'" ');
					$sub_agency_row = tep_db_fetch_array($sub_agency_sql);
					if(tep_not_null($sub_agency_row['agency_name'])){
						$agency_row['agency_name'].="<br />".$sub_agency_row['agency_name'];
					}
				}
			}
		}
		$datas[$loop]['agency_name'] = $rows['agency_name'] = $agency_row['agency_name'];
		$datas[$loop]['_tr_background_color'] = '#F7F7F7';
		$datas[$loop]['has_confirm'] = "N";
		$datas[$loop]['MatchMsn']="";
		$datas[$loop]['disabled']="";
		
		if($datas[$loop]['admin_confirm']=="Y"){
			$datas[$loop]['_tr_background_color'] = "";
			$datas[$loop]['has_confirm'] = "Y";
			$datas[$loop]['MatchMsn']= ADMIN_NAME.tep_get_admin_customer_name($datas[$loop]['admin_confirm_admin_id'])." Date:".tep_datetime_short($datas[$loop]['admin_confirm_date']);
		}elseif($datas[$loop]['_customer_invoice_total']!=$datas[$loop]['_final_price_cost']){
			//$datas[$loop]['disabled']='disabled="disabled"';	//如果发票金额与底价不符不能确认
			$datas[$loop]['disabled']="";	//如果发票金额与底价不符也能确认 howard fixed by 2013-06-04
		}
		$datas[$loop]['orders_links'] = tep_href_link('edit_orders.php','oID='.$datas[$loop]['orders_id'].'&action=edit');
		$datas[$loop]['products_links'] = tep_catalog_href_link('product_info.php','products_id='.$datas[$loop]['products_id']);
		$datas[$loop]['products_admin_links'] = tep_href_link('categories.php','action=new_product&pID='.$datas[$loop]['products_id']);
		$datas[$loop]['products_name_sub'] = cutword($datas[$loop]['products_name'],20);
	}
	
	$datas[$loop]['date_paid'] = implode(', ', (array)tep_get_date_of_paid($datas[$loop]['orders_id'],'Y-m-d'));	//客人付款时间
	$datas[$loop]['travelPeopleNumber'] = tep_get_travel_adult_child_total($rows['total_room_adult_child_info']);		//参团人数
	$datas[$loop]['paymentPaidStr'] = 'Unpaid';		//是否已支付
	switch($rows['payment_paid']){
		case '0':
			$datas[$loop]['paymentPaidStr'] = 'Unpaid';
		break;
		case '1':
			$datas[$loop]['paymentPaidStr'] = 'Paid';
		break;
		case '2':
			$datas[$loop]['paymentPaidStr'] = 'Final Payment Pending';
		break;
		case '3':
			$datas[$loop]['paymentPaidStr'] = 'Partially Paid';
		break;
	}
	$loop++;
}

if(tep_not_null($_GET['download']) && $_GET['download']=="1"){
	$size_result = sizeof($datas);
	for($i = 0; $i < $size_result; $i++) {
		$job_19 = tep_get_order_owner_jobs_id($datas[$i]['orders_id']);
		echo '<tr><td>'.$datas[$i]['orders_id'].'</td><td>'.($job_19=='19' ? $job_19.',' : '').$datas[$i]['orders_owners'].'</td><td>'.substr($datas[$i]['date_purchased'],0,10).'</td><td>'.$datas[$i]['date_paid'].'</td><td>'.substr($datas[$i]['products_departure_date'],0,10).'</td><td>'.tep_db_output($datas[$i]['provider_tour_code']).'</td><td>'.tep_db_output($datas[$i]['customer_invoice_no']).'</td><td>'.str_replace('$','',$datas[$i]['_customer_invoice_total']).'</td><td>'.str_replace('$','',$datas[$i]['_final_price_cost']).'</td><td>'.str_replace('$','',$datas[$i]['before_departure_cost']).'</td><td>'.str_replace('$','',$datas[$i]['china_bookkeeper_final_price_cost']).'</td><td>'.str_replace('$','',$datas[$i]['usa_bookkeeper_final_price_cost']).'</td><td>'.str_replace('$','',$datas[$i]['_final_price']).'</td><td>'.str_replace('$','',$datas[$i]['usa_bookkeeper_final_price']).'</td><td>'.$datas[$i]['GrossProfit'].'</td><td>' . $datas[$i]['orderStatus'] . '<td>'.$datas[$i]['has_confirm'].'</td><td>'.tep_db_output($datas[$i]['admin_comment']).'</td></tr>';
	}
	echo '<tr><td colspan="18">'.SEARCH_DATE.$S_start_date.SEARCH_DATE_TO.$S_end_date.' '.SEARCH_FILTER.$FilterOption.' Provider：'.$ProviderStr.'</td></tr>';
	echo '<tr><td colspan="18">'.INVOICE_NUMBER_SUM.$invoice_total.' '.COST_SUM.$cost_total.' '.'实收总计:'.$_final_price_total.'</td></tr>';
	echo '</table>';
	echo iconv('gb2312','utf-8//IGNORE',ob_get_clean());
	exit;
}

//分页区域
$split_left_content = $split->display_count($query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
$split_right_content = $split->display_links($query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); 
$rows_total = $split->query_num_rows;

$href_accounts_payable_reports = tep_href_link('accounts_payable_reports.php');

$main_file_name = "accounts_payable_reports";
$JavaScriptSrc[] = 'includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js';
$JavaScriptSrc[] = 'includes/javascript/zhh_system_words_list.js';
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$JavaScriptSrc[] = 'includes/javascript/calendar.js';
$CssArray = array(); //清空application_top中设置的CSS
//$CssArray[] = 'includes/stylesheet.css';
$CssArray[] = 'includes/jquery-1.3.2/nyroModal.css';
$CssArray[] = 'css/new_sys_indexDdan.css';
$CssArray[] = 'css/new_sys_index.css';
$CssArray[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.css';
include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');
require(DIR_WS_INCLUDES . 'application_bottom.php');

?>