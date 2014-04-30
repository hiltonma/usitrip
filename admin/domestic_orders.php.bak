<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('domestic_orders');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
//检查登录管理员是否有权限
//$aaaaaa = time();
 
//结伴同游的订单暂时从转账支付系统里过滤掉 start
$travel_sql = tep_db_query('SELECT DISTINCT orders_id FROM `orders_travel_companion` ');
$not_in = "0";
while($travel_rows = tep_db_fetch_array($travel_sql)){
	$not_in.= ",".$travel_rows["orders_id"];
}
//结伴同游的订单暂时从转账支付系统里过滤掉 end

$order_by = ' ORDER BY o.emergency_order DESC, dos.emergency_order DESC, o.orders_id DESC ';
$sql = 'SELECT o.orders_id, o.us_to_cny_rate, o.customers_id, o.payment_method, o.date_purchased, op.products_id, op.products_departure_date,
dos.bank, dos.real_charge, dos.dransfer_date, dos.emergency_order, dos.dransfer_name, RMB, dos.charge_date, dos.payer, value_rmb, money_type ,
IF(dos.value>0,dos.value,ot.value) as value, IF(dos.orders_status>0,dos.orders_status,o.orders_status) as orders_status, 
IF(dos.customers_name!="",dos.customers_name,o.customers_name) as customers_name, 
IF(dos.orders_status>0,dos.orders_status,o.orders_status) as orders_status, 
IF(dos.products_name!="",dos.products_name,op.products_name) as products_name  
';

$from = ' FROM orders AS o LEFT JOIN domestic_orders AS dos ON dos.orders_id = o.orders_id, orders_products AS op, orders_total AS ot   ';
$where = ' WHERE o.orders_id = op.orders_id AND op.orders_id = ot.orders_id AND o.payment_method = "银行转账(中国)" AND ot.class = "ot_total" AND o.orders_id not in('.$not_in.') ';

//搜索选项 start
if($_GET['search']=="1"){
	//$_GET['orders_status']="all";	//搜索时设置为全部订单
	if(tep_not_null($_GET["s_search_order_id"])){
		$where.=' and o.orders_id= "'.(int)$_GET["s_search_order_id"].'" ';
	}
	if(tep_not_null($_GET["s_search_order"])){
		$where.=' and (o.customers_name Like Binary "%'.tep_db_input($_GET["s_search_order"]).'%" || dos.customers_name Like Binary "%'.tep_db_input($_GET["s_search_order"]).'%") ';
	}
	if(tep_not_null($_GET["s_search_bank"])){
		$where.=' and dos.bank="'.tep_db_input($_GET["s_search_bank"]).'" ';
	}
	if(tep_not_null($_GET["s_search_order_total_1"])){
		$where.=' and (ot.value Like Binary "%'.tep_db_input($_GET["s_search_order_total_1"]).'%" || dos.value Like Binary "%'.tep_db_input($_GET["s_search_order_total_1"]).'%") ';
	}
	if(tep_not_null($_GET["s_search_value_rmb"])){
		$where.=' and value_rmb Like Binary "%'.tep_db_input($_GET["s_search_value_rmb"]).'%" ';
	}
	if(tep_not_null($_GET["s_search_charge_captured"])){
		$where.=' and (dos.real_charge Like Binary "%'.tep_db_input($_GET["s_search_charge_captured"]).'%" || RMB Like Binary "%'.tep_db_input($_GET["s_search_charge_captured"]).'%" )';
	}
	if (tep_not_null($_GET['s_search_collection_time_start']) || tep_not_null($_GET['s_search_collection_time_end'])) {
		if (tep_not_null($_GET['s_search_collection_time_start'])) {
			$where .=' AND dos.charge_date >= "' . $_GET['s_search_collection_time_start'] . ' 00:00:00" ';
		}
		if (tep_not_null($_GET['s_search_collection_time_end'])) {
			$where .=' AND dos.charge_date <= "' . $_GET['s_search_collection_time_end'] . ' 23:59:59" ';
		}
	}
	if (tep_not_null($_GET['search_start_date_dep']) || tep_not_null($_GET['search_end_date_dep'])) {
		if (tep_not_null($_GET['search_start_date_dep'])) {
			$where .=' AND op.products_departure_date >= "' . $_GET['search_start_date_dep'] . ' 00:00:00" ';
		}
		if (tep_not_null($_GET['search_end_date_dep'])) {
			$where .=' AND op.products_departure_date <= "' . $_GET['search_end_date_dep'] . ' 23:59:59" ';
		}
	}
	if (tep_not_null($_GET['search_start_date_order']) || tep_not_null($_GET['search_end_date_order'])) {
		if (tep_not_null($_GET['search_start_date_order'])) {
			$where .=' AND o.date_purchased >= "' . $_GET['search_start_date_order'] . ' 00:00:00" ';
		}
		if (tep_not_null($_GET['search_end_date_order'])) {
			$where .=' AND o.date_purchased <= "' . $_GET['search_end_date_order'] . ' 23:59:59" ';
		}
	}
}
//搜索选项 end


//状态筛选 start
if($_GET['orders_status']!="all" ){
	if((int)$_GET['orders_status']){
		$where .= ' and o.orders_status="'.$_GET['orders_status'].'" ';
	}elseif($_GET['search']!="1"){	//无搜索的情况下默认才是pending状态
		$_GET['orders_status'] = '1';
		$where.=' and o.orders_status= "1" ';
	}
}
//样式
$Pending = 'DdSelect';
$all_orders ='Dd';
$pp = 'Dd';
$china = 'Dd';
$pr = 'Dd';
$ppr = 'Dd';
$Cancelled = 'Dd';

if(($_GET['orders_status']=='all')|| ($_GET['orders_status']!='1' && $_GET['search']=="1")){ $all_orders = 'DdSelect'; $Pending ='Dd';}
if($_GET['orders_status']=='100054'){ $pp = 'DdSelect'; $Pending =$all_orders='Dd';}
if($_GET['orders_status']=='100071'){ $china = 'DdSelect'; $Pending =$all_orders='Dd';}
if($_GET['orders_status']=='100027'){ $pr = 'DdSelect'; $Pending =$all_orders='Dd';}
if($_GET['orders_status']=='100052'){ $ppr = 'DdSelect'; $Pending =$all_orders='Dd';}
if($_GET['orders_status']=='6'){ $Cancelled = 'DdSelect'; $Pending =$all_orders='Dd';}
//状态筛选 end


$companion_query_numrows = 0;

$group_by = ' GROUP BY emergency_order DESC, o.orders_id DESC ';
$sql_onload = $sql . $from . $where . $group_by . $order_by;
$companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
$sql_onload_query = tep_db_query($sql_onload);

//echo $sql_onload;

//echo $sql_onload;
$class_1 = 'Tab_1_myjb';
$class_2 = 'Tab_2_myjb';
//银行统计start
$ccb_array ='';
$icbc_array= '';
$boc_array ='';
$cmb_array ='';

$bankNameCcb = '';
$CcbOrdersNum = 0;
$CcbOrdersTotal = 0;
$CcbRealChargeTotalUsd = 0;
$CcbRealChargeTotalCny = 0;

$bankNameCmb = '';
$CmbOrdersNum = 0;
$CmbOrdersTotal = 0;
$CmbRealChargeTotalUsd = 0;
$CmbRealChargeTotalCny = 0;

$bankNameIcbc = '';
$IcbcOrdersNum = 0;
$IcbcOrdersTotal = 0;
$IcbcRealChargeTotalUsd = 0;
$IcbcRealChargeTotalCny = 0;

$bankNameBoc= '';
$BocOrdersNum = 0;
$BocOrdersTotal = 0;
$BocRealChargeTotalUsd = 0;
$BocRealChargeTotalCny = 0;

//银行统计end
$sql_onload_bank =  $sql_onload;
$sql_onload_bank_query = tep_db_query($sql_onload_bank);
while($bank_statistics_rows=tep_db_fetch_array($sql_onload_bank_query)){

	switch($bank_statistics_rows['bank']){
		case '中国建设银行':$bankNameCcb='中国建设银行';$CcbOrdersNum++;$CcbOrdersTotal+=$bank_statistics_rows['value'];if($bank_statistics_rows['money_type']=='美元'){$CcbRealChargeTotalUsd+=$bank_statistics_rows['real_charge'];}else if($bank_statistics_rows['money_type']=='人民币'){$CcbRealChargeTotalCny+=$bank_statistics_rows['RMB'];}break;
		case '中国银行':$bankNameBoc='中国银行';$BocOrdersNum++;$BocOrdersTotal+=$bank_statistics_rows['value'];if($bank_statistics_rows['money_type']=='美元'){$BocRealChargeTotalUsd+=$bank_statistics_rows['real_charge'];}else if($bank_statistics_rows['money_type']=='人民币'){$BocRealChargeTotalCny+=$bank_statistics_rows['RMB'];}break;
		case '中国工商银行':$bankNameIcbc='中国工商银行';$IcbcOrdersNum++;$IcbcOrdersTotal+=$bank_statistics_rows['value'];if($bank_statistics_rows['money_type']=='美元'){$IcbcRealChargeTotalUsd+=$bank_statistics_rows['real_charge'];}else if($bank_statistics_rows['money_type']=='人民币'){$IcbcRealChargeTotalCny+=$bank_statistics_rows['RMB'];}break;
		case '中国招商银行':$bankNameCmb='中国招商银行';$CmbOrdersNum++;$CmbOrdersTotal+=$bank_statistics_rows['value'];if($bank_statistics_rows['money_type']=='美元'){$CmbRealChargeTotalUsd+=$bank_statistics_rows['real_charge'];}else if($bank_statistics_rows['money_type']=='人民币'){$CmbRealChargeTotalCny+=$bank_statistics_rows['RMB'];}break;
		default:break;
	}
}

//更新汇率
$server_used = CURRENCY_SERVER_PRIMARY;
$currency_query = tep_db_query("select * from  currencies where code='CNY'");
$currency_rows = tep_db_fetch_array($currency_query);
$new_rate = $currency_rows['value'];




require(domestic . '/' . DIR_WS_INCLUDES . FILENAME_DOMESTIC_HEADER);
if ((int)$login_admin_group=='1'|| $admin_type!=false) {

$option_array_head = array();
$option_array_head[0] = array('id' => '', 'text' => '');
$option_array_head[1] = array('id' => '中国建设银行', 'text' => '中国建设银行');
$option_array_head[2] = array('id' => '中国银行', 'text' => '中国银行');
$option_array_head[3] = array('id' => '中国工商银行', 'text' => '中国工商银行');
$option_array_head[4] = array('id' => '中国招商银行', 'text' => '中国招商银行');
$option_array_head[5] = array('id' => '所有银行', 'text' => '所有银行');
$option_array_head = db_to_html($option_array_head);
$optin_array_money = array();
$option_array_money[0] = array('id' => '美元', 'text' =>'美元');
$option_array_money[1] = array('id' => '人民币', 'text' => '人民币');
$optin_array_money =db_to_html($optin_array_money);
$option_array_status = array();
$option_array_status[0] = array('id'=>'noselect','text'=>'请选择状态');
$option_array_status[1] = array('id'=>'payment pending','text'=>'payment pending');
$option_array_status[2] = array('id'=>'Need Check Bank Account','text'=>'Need Check Bank Account');
$option_array_status[3] = array('id'=>'Partial Payment Received','text'=>'Partial Payment Received');
$option_array_status[4] = array('id'=>'Payment Received','text'=>'Payment Received');
$option_array_status =db_to_html($option_array_status);


?>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('domestic_orders');
$list = $listrs->showRemark();
?>
<div class="main" style="min-width:1200px; ">
    <div class="ItemsTj">
        <h1 class="ItemsH1"  id="tit" onclick="showHideLyer(this,'CI_content','ItemsH1Select')">搜索条件</h1>

        <div class="ItemsTjContent" id="CI_content">
            <?php echo tep_draw_form('form_head_search', 'domestic_orders.php', tep_get_all_get_params(array('page', 'y', 'x', 'action')), 'get'); ?>
            <table width="1000" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td><?php echo db_to_html('订单号:'); ?></td><td><?php echo tep_draw_input_field('s_search_order_id', '', 'class="textAll"') ?></td>
                    <td><?php echo db_to_html('订购人:'); ?></td><td><?php echo tep_draw_input_field('s_search_order', '', 'class="textAll"') ?></td>
                    <td><?php echo db_to_html('转账银行:'); ?></td><td><?php echo tep_draw_pull_down_menu('s_search_bank', $option_array_head, '', 'class="selectText"'); ?></td>
                    
                    </tr>
                <tr>
                    <td><?php echo db_to_html('应收总金额:'); ?></td><td><?php echo tep_draw_input_field('s_search_order_total_1', '', 'class="textAll"') ?></td>
                    <td><?php echo db_to_html('实收金额:'); ?></td><td><?php echo tep_draw_input_field('s_search_charge_captured', '', 'class="textAll"') ?></td>
                    <td><?php echo db_to_html('收款时间:'); ?></td>
                    <td><?php echo tep_draw_input_field('s_search_collection_time_start', '', ' id="datepicker_5" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?>至<?php echo tep_draw_input_field('s_search_collection_time_end', '', ' id="datepicker_6" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?></td>
                    
                    
                </tr>
                <tr>
                    <td><?php echo db_to_html('应收人民币:'); ?></td>
					<td><?php echo tep_draw_input_field('s_search_value_rmb', '', 'class="textAll"') ?></td>
					<td><?php echo db_to_html('出团日期:'); ?></td>
                    <td><?php echo tep_draw_input_field('search_start_date_dep', '', ' id="datepicker_1" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?>至<?php echo tep_draw_input_field('search_end_date_dep', '', ' id="datepicker_2" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?></td>
                    <td><?php echo db_to_html('下单日期:'); ?></td>
                    <td><?php echo tep_draw_input_field('search_start_date_order', '', ' id="datepicker_3" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?>至<?php echo tep_draw_input_field('search_end_date_order', '', ' id="datepicker_4" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?></td>
                    
                    
                   
                </tr>
                


                <tr><td colspan="8" class="ButtonAligh">
                        <button class="Allbutton" type="submit">搜索</button>
						<input name="search" type="hidden" id="search" value="1">
                    </td></tr>
            </table>
            <?php echo '</form>'; ?>
        </div>
    </div>
    <?php
            require(DIR_WS_CLASSES . 'currencies.php');
            include(DIR_WS_CLASSES . 'order.php');
            $option_array = array();
            $option_array[0] = array('id' =>'nobank', 'text' =>'请选择银行');
            $option_array[1] = array('id' => '中国建设银行', 'text' => '中国建设银行');
            $option_array[2] = array('id' => '中国银行', 'text' => '中国银行');
            $option_array[3] = array('id' => '中国工商银行', 'text' => '中国工商银行');
            $option_array[4] = array('id' => '中国招商银行', 'text' => '中国招商银行');
            $option_array = db_to_html($option_array);

            //永远判断附加服务是酒店 还是 接送机
            $hotel_array = array('0' => '酒店', '1' => '酒店升级', '2' => '酒店入住');
            $airplane_array = array('0' => '&#27231;&#22580;&#25509;&#27231;', '1' => '接机', '2' => '机&#22330;接送', '3' => '机场接送', '4' => '机场接机', '5' => '机场接机[Airport Pickup]');

    ?>
     <td><?php echo tep_draw_hidden_field('rows_bk', '','id="rows_bk_id"') ?></td>
            <div class="ItemsLb">
                <h1 class="ItemsH1"  id="Lb" onclick="showHideLyer(this,'Lb_content','ItemsH1Select')">订单列表</h1>
                <div class="ItemsLbContent" id="Lb_content">
                    <div id="TabPanel1" class="TabPanel_myjb">
                        <ul class="TabPanelGroup_myjb">
                            <li id="li_1"class="<?= $class_1 ?>" tabindex="0"><span><a id="a_1" href="<?= tep_href_link('domestic_orders.php', 'search=1&'); ?>" ><?php echo db_to_html('常规订单') ?></a></span></li>
                            <li id="li_2"class="<?= $class_2 ?>" tabindex="0" style="display:none"><span><a id="a_2"href="<?= tep_href_link('domestic_orders.php', 'do_search=1&crash_orders=1&search=1&' . tep_get_all_get_params(array('page', 'x', 'y', 'crash_orders', 'o_search', ''))); ?>"><?php echo db_to_html('紧急订单') ?></a></span></li>
                        </ul>
                        <div class="Tab_Content_myjb">
                            <div class="Tab_Content11_myjb" id="list3">
                                <ul class="DdZt">
                                 <li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=1&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>"  class="<?=$Pending?>"><?php echo db_to_html('Pending') ?></a></li>   
								 <li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=100054&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>" class="<?=$pp?>"><?php echo db_to_html('payment pending') ?></a></li>
								 <li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=100071&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>" class="<?=$china?>"><?php echo db_to_html('Need Check Bank Account') ?></a></li>
								 
								 <li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=100052&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>" class="<?=$ppr?>"><?php echo db_to_html('Partial Payment Received') ?></a></li>
								 <li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=100027&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>" class="<?=$pr?>"><?php echo db_to_html('Payment Received') ?></a></li>
								<li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=6&'.tep_get_all_get_params(array('page', 'x', 'y', 'orders_status'))); ?>"  class="<?=$Cancelled?>"><?php echo db_to_html('Cancelled 已为您取消订单') ?></a></li>
								
								<li><a href="<?= tep_href_link('domestic_orders.php', 'orders_status=all'); ?>"  class="<?=$all_orders?>"><?php echo db_to_html('全部订单') ?></a></li>
                                </ul>
                                <table id="tab"  class="DdTab" style="min-width:1200px;"  border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <th>订单号</th>
                                        <th>行程名称</th>
                                        <th >订购人</th>
                                        <th>下单日期</th>
                                        <th>出团日期</th>
                                        <th>收款日期</th>
                                        <th>应收总金额</th>
                                        <th>应收人民币</th>
                                        <th>实收金额</th>
                                        <th>转账银行</th>
                                        <th>订单状态</th>
                                        <th>操作</th>
                                    </tr>
                            <?php
                            while ($rows = tep_db_fetch_array($sql_onload_query)) {
								
								//查银行
                                $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $rows['orders_id'] . '"');
                                $bank_rows = tep_db_fetch_array($bank_query);
                                //当前日期与出团日期天数少于7天的自动设置成紧急订单 60*60*24*7 start
                                $week = 60 * 60 * 24 * 7;
                                $order = new order($rows['orders_id']);
								$min_products_departure_date_num = strtotime($order->products[0]['products_departure_date']);
								for($j = 0, $k = sizeof($order->products); $j < $k; $j++){
									$tmp_time_num = strtotime($order->products[$j]['products_departure_date']);
									if($tmp_time_num<$min_products_departure_date_num){
										$min_products_departure_date_num = $tmp_time_num;
									}
								}
								
								if (($min_products_departure_date_num - time()) < $week && ($min_products_departure_date_num >= time()) ) {
                                    $can_up = false;
									//check orders status，未完成的订单才能自动添加为紧急订单
									$check_sql = tep_db_query('SELECT orders_id FROM `orders` WHERE orders_id ="' . $rows['orders_id'] . '" and (orders_status="1" || orders_status="100054") ');
									$check_row = tep_db_fetch_array($check_sql);
									if((int)$check_row['orders_id']){
										$can_up = true;
									}
									
									if($can_up==true){
										if ((int) $bank_rows['orders_id']) {
											tep_db_query('UPDATE domestic_orders SET emergency_order = "1" WHERE orders_id ="' . $rows['orders_id'] . '"');
										} else {
											$sql_data_array_insert = array('orders_id' => $rows['orders_id'],
												'customers_id' => $rows['customers_id'],
												'customers_name' => ajax_to_general_string($rows['customers_name']),
												'products_name' => ajax_to_general_string($rows['products_name']),
												'date_purchased' => $rows['date_purchased'],
												'products_departure_date' => $rows['products_departure_date'],
												'bank' => ajax_to_general_string($bank_rows['bank']),
												'orders_status' => $rows['orders_status'],
												'value' => $rows['value'],
												'admin_id' => $_SESSION['login_id'],
												'emergency_order' => '1');
											tep_db_perform('domestic_orders', $sql_data_array_insert);
											$checked = "checked='checked' disabled";
										}
										//把订单表的emergency_order也设置为1
										tep_db_query('UPDATE orders SET emergency_order = "1" WHERE orders_id ="' . $rows['orders_id'] . '"');
									}
                                }
								//当前日期与出团日期天数少于7天的自动设置成紧急订单 60*60*24*7 end
								
                                $tom_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $rows['orders_id'] . '"');
                                $tom_rows = tep_db_fetch_array($tom_query);
                               
                                $rows_num++;
                                if (strlen($rows) < 2) {
                                    $rows_num = '0' . $rows_num;
                                }
                                $bg_color = "";
                                if ((int) $rows_num % 2 == 0 && (int) $rows_num) {
                                    $bg_color = "none repeat scroll 0 0 #F5F5F5;";
                                }
								$products_name = $rows['products_name'];
                                $products_link = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $rows['products_id']);
                                $products_link = eregi_replace('/admin', '', $products_link);

                                $currencies = new currencies();
                                
								//取得折扣前的订单总金额 start
								$easy_discount_sql = tep_db_query('select value, class from orders_total where orders_id="'.$rows['orders_id'].'" and ( class="ot_easy_discount" || class="ot_total" ) ');
								$o_total = 0;
								$o_e_discount = 0;
								while($easy_discount = tep_db_fetch_array($easy_discount_sql)){
									if($easy_discount['class']=="ot_total"){ $o_total=$easy_discount['value']; }
									if($easy_discount['class']=="ot_easy_discount"){ $o_e_discount=$easy_discount['value']; }
								}
								$after_total = $o_total+abs($o_e_discount);
								$tours_cash = $currencies->format($after_total, true, $order->info['currency'], $order->info['currency_value']);
								//取得折扣前的订单总金额 end
								

                                
                                //判断该订单是否已经编辑过状态,如果该订单已经在domestic_orders表则，orders_status数据从domestic_orders里取。
                                $orders_status_now = $rows['orders_status'];
								if ((int)$bank_rows['orders_id'] && $rows['orders_status'] != '100006') {
                                    $update_insert = '1';
                                    //$orders_status_now = $bank_rows['orders_status'];
                                } else {
                                    $update_insert = '0';
                                    //$orders_status_now = $rows['orders_status'];
                                }
                                //查询改订单的操作历史
                                $operation_history_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . $rows['orders_id'] . '"ORDER BY `update_time` ASC');

                                $checked = '';
                                if ($bank_rows['emergency_order'] == '1') {
                                    $checked = "checked='checked'";
                                }
                                $bank_rows_realcharge_total ='';
                                $realcharge_total = "0.00";
								$shiShouJineBZ = $bank_rows['money_type'];
								// panda 拆分实收金额为分别 填写美元和人民币 start
                                /*
                                if($bank_rows['money_type']=='美元'){
                                    $realcharge_total = number_format($bank_rows['real_charge'],2,'.','');
									$bank_rows_realcharge_total ='$'.$realcharge_total;
									
                                }else if($bank_rows['money_type']=='人民币'){
                                   $realcharge_total = number_format($bank_rows['RMB'],2,'.','');
								   $bank_rows_realcharge_total ='￥'.$realcharge_total;
                                }*/
                                $shiShouJine_dollar = number_format($bank_rows['real_charge'],2,'.','');
								$bank_rows_realcharge_total_dollar ='$'.$shiShouJine_dollar;
                                $shiShouJine_rmb = number_format($bank_rows['RMB'],2,'.','');
								$bank_rows_realcharge_total_rmb ='￥'.$shiShouJine_rmb;
                                // panda 拆分实收金额为分别 填写美元和人民币 start
                                $yingShouRmb_rows='';
                                $yingShouRmb ='';
                                if($bank_rows['value_rmb']!=NULL&&$bank_rows['value_rmb']!='0'){
                                	$yingShouRmb = number_format($bank_rows['value_rmb'],2,'.','');
                                	$yingShouRmb_rows ='￥'. $yingShouRmb;

                                }else{
                                    $yingShouRmb = $bank_rows['value_rmb'];
                                }
								
								//如果应收人民币值为空则从订单中取值换算
								if(!tep_not_null($yingShouRmb) || $yingShouRmb<1){
									$tmp_v = round($rows['value'] * $currencies->get_value('CNY'),0); //按当前日的汇率换算
									if($rows['us_to_cny_rate']>0){	//按下单日的汇率换算
										$tmp_v = round($rows['value'] * $rows['us_to_cny_rate'],0);
										
									}
									$yingShouRmb = number_format($tmp_v,2,'.','');
									
									//$yingShouRmb =  $order->info['currency'].":". $order->info['currency_value'];
                                	$yingShouRmb_rows ='￥'. $yingShouRmb;
								}
								
                                //$shiShouJine = $realcharge_total;
                                //查人民币收款的
                                $rmb_query=tep_db_query('select text from orders_total where orders_id="'.$rows['orders_id'].'" and class="ot_total"');
                                $rmb_rows = tep_db_fetch_array($rmb_query);
                                $rmb_text = $rmb_rows['text'];
                                $rmb_usd_attach_tours_cash = $tours_cash;
                                $rmb_usd_attach= '';
                                if($rows['us_to_cny_rate']>0){
									$rate = $rows['us_to_cny_rate']; //统一汇率
								}else{
									$rate = $new_rate;
								}
								if(preg_match("/(&#65509|￥)/",$rmb_text)){
                                    $rmb_usd_attach ='(￥'.number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($rmb_text))),2,'.','').')';//附加人民币
                                    $rmb_usd_attach_tours_cash = '$'.number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($tours_cash)))/$rate,2,'.','').'('.$tours_cash.')';
                                }else{
									//不是人民币的订单也兑换成人民币值供显示
									$rmb_usd_attach= ' (￥'.number_format(round($rows['value'] * $rate,0),2,'.','').')';
									$rmb_usd_attach_tours_cash = '$'.$after_total.' (￥'.number_format(round($after_total*$rate,0),2,'.','').')';
								}
								
                              $charge_date_rows_bk = '';
                              if(substr($bank_rows['charge_date'],0,10)!='0000-00-00'&&substr($bank_rows['charge_date'],0,10)!=null){
                                  $charge_date_rows_bk =  substr($bank_rows['charge_date'],0,10);
                              }
                              
                              $disabled_submit = '';
                              if ($rows['orders_status'] == '100006') {
                              	$disabled_submit = 'disabled=disabled';
                              }
                               
                            ?>
                                <tr  class="Tabselect" onclick="open_detail_table_content(<?= $rows['orders_id'] ?>)"  id="show_orders_details_<?= $rows['orders_id'] ?>" style=" background:<?= $bg_color;?>" >
                                    <td>
									<?php
									$tmp_sql = tep_db_query('SELECT emergency_order FROM `domestic_orders` WHERE orders_id="'.$rows['orders_id'].'" Limit 1');
									$tmp_row = tep_db_fetch_array($tmp_sql);
									if($tmp_row['emergency_order']=="1"){
										echo '<span style="color:#F00; margin-left:-15px;">急</span>';
									}
									?>
									<a href="<?php echo tep_href_link('edit_orders.php','oID='.$rows['orders_id'])?>" onclick="window.open(this.href)" class="DdXc"><?php echo tep_db_output($rows['orders_id']) ?></a>
									</td>
                                    <td nowrap="nowrap"><a href="<?= $products_link ?>" onclick="window.open(this.href)" class="DdXc" title="<?= $products_name ?>"><?php echo cutword($products_name, 10) ?></a></td>
                                    <td><?php echo tep_db_output($rows['customers_name']); ?></td>
                                    <td><?php echo substr(tep_db_output($rows['date_purchased']), 0, 10) ?></td>
                                    <td><?php echo substr(tep_db_output($rows['products_departure_date']), 0, 10) ?></td>
                                    <td id="charge_date_<?= $rows['orders_id'] ?>"><?php echo $charge_date_rows_bk; ?></td>
                                    <td>
									<?php 
									$yingShouUsdTotal = '$' . number_format(tep_db_output($rows['value']),2,'.','');
									echo $yingShouUsdTotal;
									?></td>
                                    <td  id="dransfer_rmb_row_<?= $rows['orders_id'] ?>"><?php echo  $yingShouRmb_rows; ?></td>
                                    <td  id="dransfer_total_row_<?= $rows['orders_id'] ?>">
									<?php
									//如果美元支付，应收总金额和实收金额比较是否一致，如果人民币支付，应收人民币和实收金额比较不符，实收金额是红的
									if(tep_not_null($bank_rows_realcharge_total_dollar) || tep_not_null($bank_rows_realcharge_total_rmb)){
										if($bank_rows_realcharge_total_rmb!=$yingShouRmb_rows && $bank_rows_realcharge_total_dollar!=$yingShouUsdTotal){
                                            $bank_rows_realcharge_total_str = '<span style="color:#F00">';
                                            if ($bank_rows_realcharge_total_dollar != '$0.00' ){
                                                $bank_rows_realcharge_total_str .= $bank_rows_realcharge_total_dollar;
                                            }                                           
                                            if ($bank_rows_realcharge_total_rmb != '￥0.00'){
                                                $bank_rows_realcharge_total_str .= '('.$bank_rows_realcharge_total_rmb.')';
                                            }
											echo $bank_rows_realcharge_total_str .= '</span>';
										}else{
											echo $bank_rows_realcharge_total_dollar .'('. $bank_rows_realcharge_total_rmb.')';
										}
									}
									?>
									</td>
                                    <td  id="dransfer_bank_row_<?= $rows['orders_id'] ?>"><?php echo $bank_rows['bank']; ?></td>
                                    <td  id="orders_status_row_<?= $rows['orders_id'] ?>"><?php 
									if($orders_status_now=="1" || $orders_status_now=="100052" || $orders_status_now=="100054" || $orders_status_now=="100071" || $orders_status_now=="100027"){
										//只显示payment pending，Need Check Bank Account，Payment Received的状态
										/*if($orders_status_now=="1"){
											echo "payment pending";
										}else{
										}*/
										echo str_replace('（Chinese Account）','',tep_get_orders_status_name(tep_db_output($orders_status_now)));
									}else{
										echo '<span style="color:#CCCCCC">'.tep_get_orders_status_name(tep_db_output($orders_status_now)).'</span>';
									}
									?></td>
                                    <td><a id="orders_detail_<?= $rows['orders_id'] ?>" href="#" class="Xiangxi"><?php echo '查看详情' ?></a></td>
                                </tr>
                                <tr id="orders_detail_tr_<?= $rows['orders_id'] ?>" style="display:none">
                                    <td colspan="12"  class="TabselectBg">
                                        <div  class="DdXxContent">
                                            <form action="" enctype="multipart/form-data" method="post" id="orders_edit_<?= $rows['orders_id'] ?>" name="form_orders_edit" onsubmit="orders_edit_submit('<?= $rows['orders_id'] ?>');return false;">
                                                <table  class="DdTabXx" width="90%">
                                                <?php
                                                for ($j = 0, $k = sizeof($order->products[0]['attributes']); $j < $k; $j++) {
                                                    echo '<tr><td  class="ColHui" colspan="2"><b>' . $order->products[0]['attributes'][$j]['option'] . ': ' . $order->products[0]['attributes'][$j]['value'];
                                                    if ($order->products[0]['attributes'][$j]['price'] != '0') {
                                                        $value_bk = $currencies->format($order->products[0]['attributes'][$j]['price'] * $order->products[0]['qty'], true, $order->info['currency'], $order->info['currency_value']);
                                                        echo ' (' . $order->products[0]['attributes'][$j]['prefix'] . $currencies->format($order->products[0]['attributes'][$j]['price'] * $order->products[0]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                                                        if (in_array($order->products[0]['attributes'][$j]['option'], $hotel_array)) {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_hidden_field('hotel_value', $value_bk) . '';
                                                        } else if (in_array($order->products[0]['attributes'][$j]['option'], $airplane_array)) {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_hidden_field('pickup_value', $value_bk) . '';
                                                        } else if ($order->products[0]['attributes'][$j]['option'] == '送机') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_hidden_field('leave_value', $value_bk) . '';
                                                        }
                                                    }
                                                    echo '</b></td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                                <tr>
												<td class="ColHui" width="280" valign="top">折扣前订单总金额:&nbsp;<b><?php echo $rmb_usd_attach_tours_cash; ?></b></td>
												<td class="ColHui">订购人:&nbsp;<b><?php echo $rows['customers_name'] ?></b>
														&nbsp;&nbsp;
														<?php
														/*if($rows['customers_id']<1){
															echo $rows['customers_id'].":::".$rows['orders_id'];
														}*/
														?>
												&nbsp;&nbsp;&nbsp;&nbsp;订购人邮箱:<span id="customers_email_address_<?=$rows['orders_id']?>"><b><?php echo tep_get_customers_email($rows['customers_id']);?></b></span>
														&nbsp;&nbsp;&nbsp;&nbsp;
														订购人电话:<span id="customers_phone_<?=$rows['orders_id']?>"><b><?php echo tep_get_customers_phones($rows['customers_id']);?></b></span>
												</td>
												</tr>
                                                <tr>
                                                   <td class="ColHui">应收总金额:&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo  '$'.number_format($rows['value'],2,'.','').$rmb_usd_attach;?></b></td>
												   <td class="ColHui">应收人民币:&nbsp;<b><?php
                                                      
                                                        //if(preg_match("/(&#65509;|￥)/",$rmb_text)){
                                                           //echo  tep_draw_input_field('dransfer_total_rmb', number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($rmb_text))),2,'.',''), 'class="textAll_table"') ;
                                                       // }else{
                                                        echo  tep_draw_input_field('dransfer_total_rmb', $yingShouRmb, 'class="textAll_table"');
                                                       // }?></b>
                                                         
                                                </tr>
                                                <tr>
                                                  <td  class="ColHui">收款银行:&nbsp;<?php $option_array = db_to_html($option_array); echo tep_draw_pull_down_menu('bank_type', $option_array, $bank_rows['bank'], '" class="selectText_cc1"');?></td>
												  <td  class="ColHui">实收金额:&nbsp;&nbsp;&nbsp;
                                                       <?php 
													   	if ($admin_type != 'service' && $admin_type != false){
													   		$only_disabled = "";
													   	}else{
															$only_disabled = "disabled";
														}
													   //echo tep_draw_pull_down_menu('money_type', $option_array_money,$shiShouJineBZ, $only_disabled.' " class="selectText_cc2"');
													   //echo " ".tep_draw_input_field('actual_collection', $shiShouJine, $only_disabled.' id=actual_collection class="textAll_table"');
													   echo '美元';
                                                       echo " ".tep_draw_input_field('actual_collection_dollar', $shiShouJine_dollar, $only_disabled.' id=actual_collection_dollar class="textAll_table"');
                                                       echo ' 人民币';
                                                       echo " ".tep_draw_input_field('actual_collection_rmb', $shiShouJine_rmb, $only_disabled.' id=actual_collection_rmb class="textAll_table"');
                                                       ?>
											      &nbsp;&nbsp; &nbsp;&nbsp; 付款人:&nbsp;<?php echo tep_draw_input_field('payer_name', $bank_rows['payer'], 'class="textAll_table"') ?>
                                                        &nbsp;&nbsp; &nbsp;&nbsp; 收款时间:&nbsp;
                                                        <?php echo tep_draw_input_field('select_collection_time', substr($bank_rows['charge_date'], 0, 10), 'id="datepicker_' . $rows['orders_id'] . '" class="textTime"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"'); ?>
                                                       
                                                  </td>

                                                </tr>
												</table>
												<table  class="DdTabXx" >


                                                <?php
                                                        $history_table_style = 'display:none';
                                                        // if ($admin_type == 'master' || $admin_type == 'accountant' || tep_db_num_rows($admin_is_see_query) > '0') {
                                                        if ((int) $bank_rows['orders_id'] && (int) tep_db_num_rows($operation_history_query)) {
                                                            $history_table_style = ' ';
                                                        }
                                                ?>
                                                        <tr><td colspan="5" style="<?= $history_table_style ?>" id="table_style_<?= $rows['orders_id'] ?>"  class="TabFg"><table  class="DdTabXxCaozuo" border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <th>操作人</th>
                                                                        <th>更新状态</th>
                                                                        <th>应收金额</th>
                                                                        <th>实际收款</th>
                                                                        <th>更新时间</th>
                                                                        <th>备注</th>
                                                                        <th>邮件状态</th>
                                                                        <th style="display:none">ID</th>
                                                                    </tr>
                                                            <?php
                                                            while ($operation_history_rows = tep_db_fetch_array($operation_history_query)) {
                                                            ?>

                                                                <tr>
                                                                    <td><?= tep_get_admin_customer_name($operation_history_rows['admin_id']) ?></td>
                                                                    <td><?= $operation_history_rows['manager_history'] ?></td>
                                                                    <td><?= number_format(($bank_rows['value']),2,'.','')  ?></td>
                                                                <td><?=$shiShouJine_dollar?>(<?=$shiShouJine_rmb?>)</td>
                                                                <td><?= $operation_history_rows['update_time'] ?></td>
                                                                <td><?= $operation_history_rows['notes'] ?></td>
                                                                <?php
                                                                if((int)$operation_history_rows['email_sented']){
                                                                     echo "<td class='send-email-notcic'>已发送邮件</td>";
                                                                }else{
                                                                    echo "<td id='td_email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><input type='button' class='AllbuttonHui' style='padding-left:5px; padding-right:5px; ' value='未发送邮件'  id='email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><script type='text/javascript'>$('#email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."').bind('click',function(){ $('#notic_id_pop_" . $orders_id . "').trigger('click');});</script></td>";

                                                                }
                                                                ?>
                                                            </tr>
                                                            <?php } ?>
                                                            </table></td></tr>
                                                <?php
                                                                //}
                                                                //}
                                                                if ($admin_type != 'service' && $admin_type != false) {
                                                ?>
                                                                   
                                                  
                                                       
                                                <?php } ?>


                                                                <tr>
                                                                    <td class="colhui">支付状态:&nbsp;<?php echo tep_draw_pull_down_menu('status_type', $option_array_status,'请选择状态', ' class="selectText_cc1" id="select_status_'.$rows['orders_id'].'"');?>
                                                                     <input id="orders_status_submit_id_<?=$rows['orders_id']?>" name="orders_status_submit_name" type="button" value="更新" onclick="orders_status_submit_id_up_action(<?=$rows['orders_id']?>)" class="Allbutton" style="margin-top:10px; padding-left:5px; padding-right:5px;">
                                                                     <span id="notic_status_<?=$rows['orders_id']?>" class="status-advice" style="display:none">请更新状态</span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="colhui">
																    <div style="float:left; width:56px;">备注:</div>
                                                                    <div style="float:left; margin:10px 0 0 0;"><?php echo tep_draw_textarea_field('notes', 'virtual', 50, 5, '', 'class="DdTextarea" id="notes_'.$rows['orders_id'].'"') ?></div>
																	</td>
                                                                </tr>
                                                                <tr><td><input class="checkDd" type="checkbox" value="1" name="emergency_order" id="emergency_order" <?= $checked ?>>将订单设为紧急订单</td></tr>
																<?php
																//取得客户给我们的留言
																$cus_coments_sql = tep_db_query('SELECT comments FROM `orders_status_history` WHERE orders_id ="'.$rows['orders_id'].'" and customer_notified ="1" ORDER BY `orders_status_history_id` ASC  LIMIT 1');
																$cus_coments = tep_db_fetch_array($cus_coments_sql);
																if(tep_not_null($cus_coments['comments'])){
																?>
																<tr><td>客户留言：</td></tr>
																<tr><td><?php echo nl2br(tep_db_output($cus_coments['comments']));?></td></tr>
																<?php
																}
																?>

                                                                <tr>
                                                                    <td colspan="8" class="ButtonAligh" align="center">
																	<?php
																	if($disabled_submit!=""){
																	?>
																	<input id="orders_edit_submit_id_<?= $rows['orders_id']?>" name="orders_edit_submit_name" type="button" value="确定" style="margin-top:10px;" onclick="alert('<?php echo db_to_html("此状态的订单不允许编辑！")?>')" class="Allbutton">
																	<?php 
																	}else{
																	?>
																	<input id="orders_edit_submit_id_<?= $rows['orders_id']?>" name="orders_edit_submit_name" type="submit" value="确定" style="margin-top:10px;" <?= $disabled_submit ?>  class="Allbutton">
                                                                    <?php
																	}
																	?>
																	<input id="orders_edit_canncel_<?=$rows['orders_id']?>" name="reset" type="button" value="取消" style="margin-top:10px;" <?= $disabled_submit ?> class="AllbuttonHui"></td>
                                                                    <td><input name="update_or_insert_orders" type="hidden" id="check_update_insert" value="<?= $update_insert ?>"></td>
                                                                    <td><?php echo tep_draw_hidden_field('orders_id', $rows['orders_id']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('customers_id', $rows['customers_id']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('customers_name', $rows['customers_name']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('products_name', $rows['products_name']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('products_departure_date', $rows['products_departure_date']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('orders_total', $rows['value']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('admin_id', $_SESSION['login_id']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('date_purchased', $rows['date_purchased']) ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('orders_status_bk', $orders_status_now,'id="orders_status_bk_'.$rows['orders_id'].'"') ?></td>
                                                                    <td><?php echo tep_draw_hidden_field('tours_value', $tours_cash) ?></td>
                                                                     <td><?php echo tep_draw_hidden_field('usitrip', 'tom','id="tom_'.$rows['orders_id'].'"') ?></td>
                                                                     <td><?php echo tep_draw_hidden_field('update_status', '0', 'id="tom_status_' . $rows['orders_id'] .'"') ?></td>
                                                                     <td><?php echo tep_draw_hidden_field('style_bk',$bg_color,'id="style_bk_' . $rows['orders_id'] .'"') ?></td>


                                                                </tr>
                                                            </table>
                                                        </form>
                                                    <a href="ajax_domestic_orders.php?nyroModalSel=notice&orders_id=<?= $rows['orders_id'] ?>" style="display:none;" id="notic_id_pop_<?=$rows['orders_id'] ?>" onclick="LoadToDom('pop_obj',this.href)">通知客户</a>
													</div>

                                                </td>
                                            </tr>


                            <?php
							}
							
							?>
                                                        </table>
														







		
														
                                                        <tr>
                                                            <td colspan="<?= $colspan ?>"><table border="0" width="99%" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                        <td align="right"><div class="pageBot"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'y', 'x', 'action', 'SortSubmit', 'sort_order_array'))); ?>&nbsp;</div><div class="pageBot"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                        
                        <?php
                                                            if (($admin_type != 'service' && $admin_type != false)||(int)$login_admin_group=='1') {
                        ?>
                                                                <table width="99%"  class="Ddbutton" border="0" cellpadding="0" cellspacing="0" ><tr><td class="ButtonAlighright"><button class="AllbuttonHui" id="allBankGoHome">金额统计</button></td></tr></table>
                        <?php
						//银行统计start
						$ccb_array = creatArray($bankNameCcb,$CcbOrdersNum,$CcbOrdersTotal,$CcbRealChargeTotalUsd,$CcbRealChargeTotalCny);
                        $icbc_array = creatArray($bankNameIcbc,$IcbcOrdersNum,$IcbcOrdersTotal,$IcbcRealChargeTotalUsd,$IcbcRealChargeTotalCny);
                        $boc_array = creatArray($bankNameBoc,$BocOrdersNum,$BocOrdersTotal,$BocRealChargeTotalUsd,$BocRealChargeTotalCny);
                        $cmb_array = creatArray($bankNameCmb,$CmbOrdersNum,$CmbOrdersTotal,$CmbRealChargeTotalUsd,$CmbRealChargeTotalCny);
						$bank_statistics_array = array('0' => $ccb_array,
							'1' => $icbc_array,
							'2' => $boc_array,
							'3' => $cmb_array);
						$all_bank_orders_total = '';
						$all_bank_real_charge_total = '';
						$all_bank_real_total =  $ccb_array['real_charge_total_usd']+$ccb_array['real_charge_total_cny']*(1/$new_rate)+$boc_array['real_charge_total_usd']+$boc_array['real_charge_total_cny']*(1/$new_rate)+$icbc_array['real_charge_total_usd']+$icbc_array['real_charge_total_cny']*(1/$new_rate)+$cmb_array['real_charge_total_usd']+$cmb_array['real_charge_total_cny']*(1/$new_rate);
                        ?>
                                                                <script type="text/javascript">
                                                                    $("#allBankGoHome").click(function(){
                                                                          $("#bankStatistics").toggle();
                                                                          //$("#allBankGoHome").replaceWith('<button id="allBankGoHide" class="AllbuttonHui">金额统计</button>');

                                                                    });
                                                                    $("#buttonClose").click(function(){
                                                                        window.close();
                                                                    })
                                                                  
                                                                </script>

                                                                <table  id ="bankStatistics"class="DdShoukuan" border="0" cellpadding="0" cellspacing="0" style="display:none">

                                                                    <tr>
                                                                        <th>银行</th>
                                                                        <th>订单数</th>
                                                                        <th class="fLeft">应收金额总计</th>
                                                                        <th class="fLeft">实收美元金额总计</th>
                                                                        <th class="fLeft">实收人民币金额总计</th>
                                                                        <th class="fCenter">占总额%</th>
                                                                    </tr>
                            <?php
                                                                for ($j = 0; $j < sizeof($bank_statistics_array); $j++) {
                                                                     if($bank_statistics_array[$j]['bank_name']!=''){
                                                                    $all_bank_orders_total+=$bank_statistics_array[$j]['orders_total'];
                                                                    $all_bank_real_charge_total+=$bank_statistics_array[$j]['real_charge_total_usd'];
                                                                    $all_bank_real_charge_total_cny+=$bank_statistics_array[$j]['real_charge_total_cny'];
                            ?>
                                                                    <tr>
                                                                        <td ><?= $bank_statistics_array[$j]['bank_name']; ?></td>
                                                                        <td><?= $bank_statistics_array[$j]['orders_num']; ?></td>
                                                                        <td class="fLeft"><?= '$'.number_format($bank_statistics_array[$j]['orders_total'],2,'.',''); ?></td>
                                                                        <td class="fLeft"><?= '$'.number_format($bank_statistics_array[$j]['real_charge_total_usd'],2,'.',''); ?></td>
                                                                         <td class="fLeft"><?= '￥'.number_format($bank_statistics_array[$j]['real_charge_total_cny'],2,'.',''); ?></td>
                                                                        <td class="fCenter" >
                                    <?php
                                                                    if ($all_bank_real_total > '0') {
                                                                        echo number_format(((($bank_statistics_array[$j]['real_charge_total_usd']+$bank_statistics_array[$j]['real_charge_total_cny']*(1/$new_rate)) / $all_bank_real_total) * 100), 2,'.','') . '%';
                                                                    }
                                    ?>
                                                                </td>
                                                            </tr>

                            <?php } }?>
                                                                <tfoot>
                                                                    <tr><td></td><td></td><td class="fLeft">总计：<span class="Colbold"><?= '$'.number_format($all_bank_orders_total,2,'.','') ?></span></td><td class="fLeft ColOrange"><?= '$'.number_format($all_bank_real_charge_total,2,'.','') ?></td><td class="fLeft ColOrange"><?= '￥'.number_format($all_bank_real_charge_total_cny,2,'.','') ?></td><td></td></tr>
                                                                </tfoot>
                                                            </table>

                        <?php 
                       
                        } ?>
                                                            <!--<table width="99%" border="0" cellpadding="0" cellspacing="0" ><tr><td class="ButtonAlighright"><button id="buttonClose"class="AllbuttonHui">关闭</button></td></tr></table>-->
                                                            <div class="clear"></div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    <!--
                                                    var Global_defaultTab = 0;
                                                    var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabPanel1");
                                                    //-->
                                                </script>
                                            </div>
                                        </div>
                                    </div>



<?php
 }else{
     echo '<h1>'.db_to_html('你没有足够的权限').'</h1>';
 }
require(domestic . '/' . DIR_WS_INCLUDES . FILENAME_DOMESTIC_FOOTER);
//echo $sql_onload ;
//print_r($order);
//$bbbbb = time();
//echo ($bbbbb-$aaaaaa);

?>