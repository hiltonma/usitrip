<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");

//限制30秒不能刷新  开始{
if( in_array($login_groups_id, array(5,48,7,1))){
	if(isset($_COOKIE['order_time'])&&$_COOKIE['re_url']==$_SERVER['REQUEST_URI']&&!$_POST){
		if(time()-$_COOKIE['order_time']<REFRESH_ORDERS_TIME){
			die("每次刷新请间隔".REFRESH_ORDERS_TIME."S！thank you ！！！");
		}
	}
	setcookie('order_time',time(),time()+3600);
	setcookie('re_url',$_SERVER['REQUEST_URI'],time()+3600);
}
//限制30秒不能刷新  结束}

require('includes/application_top.php');

$remark_gid = isset($_GET['agret']) ? $_GET['agret'] : '';
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('orders'.$remark_gid);
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require(DIR_WS_CLASSES . 'Price_Change_Alert.class.php');
require('includes/classes/visa.php');
require(DIR_WS_CLASSES . 'currencies.php');

$PCA = new Price_Change_Alert;
$currencies = new currencies();

$orders_statuses = array();
$orders_status_array = array();
$confirm_exclude_orders_status = array();

//默认订单列表不显示的订单状态编号(已取消\撤销原单，下新单等) start {
switch($login_groups_id){
	case '42':	//op默认的排除状态，如果有"地接确认与更新"则不排除这些状态，以地接确认与更新为准
		$confirm_exclude_orders_status = array(6,100130,100002,100005,100006);
	break;
	case '5':	//主管要默认排除的一些订单状态
		$confirm_exclude_orders_status = array(6);
	break;
}

if($_GET["status"]){
	$confirm_exclude_orders_status = array();
}elseif(isset($_GET["btnsubmit"]) || $_GET["exclude_orders_status"] || $_GET['next_job_number']){
	if(is_array($_GET["exclude_orders_status"])){
		$confirm_exclude_orders_status = $_GET["exclude_orders_status"];
	}elseif($_GET["exclude_orders_status"]!=''){
		$confirm_exclude_orders_status = explode(',', $_GET["exclude_orders_status"]);
	}else{
		$confirm_exclude_orders_status = array();
	}
}
//默认订单列表不显示的订单状态编号(已取消\撤销原单，下新单等) end }
//默认只显示3个月前的订单 start {
/*if(!isset($_GET["date_purchased_start"])){
	$_GET["date_purchased_start"] = $date_purchased_start = date('Y-m-d', strtotime('-3 months'));
	$_GET['purchased_time_zone'] = 'America/Los_Angeles';
}
*/
//默认只显示3个月前的订单 end }


//by vincent 订单避免重复更新管理优化 begin 
//设置由美国客服和中国客服跟踪的订单状态码
//2011-7-25 Payment Pending 组下状态Payment Pending（Chinese Account）100054和CC Auth Failed 100060 默认为中国Office跟进的状态，需要在Status List中标记为红色。
//2011-7-26 Payment Pending 组下状态Payment Pending(100094) 支付待定改默认为美国Office跟进的状态，需要在Status List中标记为蓝色
$followup_array_us =array('100009','100072','100004','100019','100085','100088','100089','100092','100021','100046','100045','100094');
$followup_array_cn=array(/*'100094',*/'100083','100036','100012','100026','100084','100086','100087','100090','100091','100057','100075','100020','100054','100060');
//followup exclude array
$followup_exclude_arr = array('100002','100097','100095','6','100005','100006','100077'); //todo:Task-13_订无论这些订单之前处在何标签下，当状态更新为以上7种中任何一种时，都不显示在folloe up标签下。
//订单避免重复更新管理优化 end 

//tom added 日本团的自带磁带提示信息
//JPTK82-1407，JPTK82-1413， JPTK82-1414， JPTK82-1414， JPTK82-1414， JPTK82-1414，JPTK82-1413，JPTK82-1413
$japan_array = array('JPTK82-1407','JPTK82-1413','JPTK82-1414','JPTK82-1414','JPTK82-1414','JPTK82-1414','JPTK82-1413','JPTK82-1413','JPTK82-1402','JPTK82-1404');

$not_in_ids = '11,47,100011,100024,100034,100035,100039,100028,100041,100047,100049,100050,100058,100059';

$_orders_status_query = tep_db_query("select os.orders_status_id, os.orders_status_name from " . TABLE_ORDERS_STATUS . " os, orders_status_groups osg where osg.os_groups_id = os.os_groups_id and os.orders_status_id not in(" . $not_in_ids . ")  AND os.language_id = '" . (int) $languages_id . "' AND os.orders_status_display='1' Group By os.orders_status_id ORDER BY os.orders_status_name ASC ");

while ($orders_status = tep_db_fetch_array($_orders_status_query)) {
    $orders_statuses[] = array(
		'id' => $orders_status['orders_status_id'],
        'text' => $orders_status['orders_status_name']
	);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}

$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
    switch ($action) {
        case 'deleteconfirm':
            $oID = tep_db_prepare_input($_GET['oID']);
            $restock1 = $HTTP_POST_VARS['restock'];
            if ($restock1 == 'on') {
                $restock11 = 'true';
            }
            tep_remove_order($oID, $HTTP_POST_VARS['restock']);
            tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
            break;	
		//OP或销售主管已经看了地接的回复内容要把订单号闪烁去掉 start {
		case 'op_has_see_provider_re':
			if($_POST['ajax']=='true' && (int)$_POST['orders_id']){
				$ret = tep_get_order_start_stop((int)$_POST['orders_id'],0);
				echo (int)$ret;
			}
			exit;
			break;
			//OP或销售主管已经看了地接的回复内容要把订单号闪烁去掉 end }
		
		// 设置一个订单为包团订单，即团购 by lwkai added 2013-05-15
		case 'set_packet_group':
			if ($can_packet_group == true) {
				$packet_order_id = isset($_GET['orders_id']) ? intval($_GET['orders_id']) : 0;
				if ($packet_order_id > 0) {
					tep_db_perform(TABLE_ORDERS,array('is_packet_group'=>'1'),'update',"orders_id='" . $packet_order_id . "'");
					$messageStack->add_session('设置为包团成功！', 'success');
				} else {
					$messageStack->add_session('设置为包团失败！', 'error');
				}
			} else {
				$messageStack->add_session('对不起！您没权进行此项操作！', 'error');
			}
			tep_redirect($_SERVER['HTTP_REFERER']);
			//tep_redirect(tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('orders_id','action'))));
			break;
		// 取消某一个订单为团购订单，即取消是包团 by lwkai added 2013-05-15
		case 'unset_packet_group':
			if ($can_packet_group == true) {
				$packet_order_id = isset($_GET['orders_id']) ? intval($_GET['orders_id']) : 0;
				if ($packet_order_id > 0) {
					tep_db_perform(TABLE_ORDERS,array('is_packet_group'=>'0'),'update',"orders_id='" . $packet_order_id . "'");
					$messageStack->add_session('取消包团成功！', 'success');
				} else {
					$messageStack->add_session('取消包团失败！', 'error');
				}
			} else {
				$messageStack->add_session('对不起！您没权进行此项操作！', 'error');
			}
			tep_redirect($_SERVER['HTTP_REFERER']);
			//tep_redirect(tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('orders_id','action'))));
			break;
    }
}


if (($action == 'edit') && isset($_GET['oID'])) {
    $_GET['oID'] = str_replace(ORDER_EMAIL_PRIFIX_NAME, '', trim($_GET['oID']));
    $oID = tep_db_prepare_input($_GET['oID']);
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int) $oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
        //tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'search=' . $oID));
        //$order_exists = false;
        //$messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    } else {
        tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action'))));
    }
}

if (($action == 'eticket') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int) $oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
        $order_exists = false;
        $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"	content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link rel="stylesheet" type="text/css"	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript"	src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<!--<div id="spiffycalendar" class="text"></div>-->
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
<!--
function popupWindow(url) {
    window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
//-->
</script>
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-blink.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<?php
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
?>
<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
		ajax = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.') ?>");
}
</script>


<?php
function RTESafe($strText) {
    //returns safe code for preloading in the RTE
    $tmpString = trim($strText);
    //convert all types of single quotes
    $tmpString = str_replace(chr(145), chr(39), $tmpString);
    $tmpString = str_replace(chr(146), chr(39), $tmpString);
    //$tmpString = str_replace("'", "&#39;", $tmpString);
    //convert all types of double quotes
    $tmpString = str_replace(chr(147), chr(34), $tmpString);
    $tmpString = str_replace(chr(148), chr(34), $tmpString);
    //replace carriage returns & line feeds
    $tmpString = str_replace(chr(10), "", $tmpString);
    $tmpString = str_replace(chr(13), "\\n", $tmpString);
    return $tmpString;
}
?>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$('.blink').blink();
});
</script>
<style type="text/css">
a.a-btn{
	display:inline-block;
	padding:3px;
	border:1px solid #ccc;
	background:-webkit-gradient(linear, left top, left bottom, from(#eee), to(#00abed));/* Chrome, Saf4+ */
	background:-moz-linear-gradient(top, #eee, #00abed); /* Firefox */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#00abed', GradientType='0');
}
a.a-btn-active{
display:inline-block;
	padding:3px;
	border:1px solid #f80;
	background:-webkit-gradient(linear, left top, left bottom, from(#ffab5d), to(#ff6200));/* Chrome, Saf4+ */
	background:-moz-linear-gradient(top, #ffab5d, #ff6200); /* Firefox */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffab5d', endColorstr='#ff6200', GradientType='0');
	color:#fff;
}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('orders'.$remark_gid);
$list = $listrs->showRemark('agret='.$remark_gid);
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
			</table>
		</td>
		<!-- body_text //-->
		<td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					<td id="search_box" width="100%" style="border: 1px dashed #CCCCCC;">
						<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td valign="top">
										<h3 class="order_search_bt"><?php echo HEADING_TITLE; ?></h3>
									</td>
								</tr>
								<tr>
									<td>
<?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get',' enctype="application/x-www-form-urlencoded" '); ?>
                                            <table width="100%"
											class="text_right">
											<tr>
<?php
        if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
            $oID = $_GET['search'];
        }
?>
                                                    <td width="147"><?php echo HEADING_ORDER_ID;?>:</td>
												<td width="186" align="left"><?php echo tep_draw_input_field('oID', $oID, 'size="12" class="order_text2"') . tep_draw_hidden_field('action', 'edit'); ?></td>
												<td align="right">客户姓名或邮箱:</td>
												<td align="left"><?php echo tep_draw_input_field('customers_name', $customers_name, 'size="12" class="order_text2"');?></td>
												<td>出团日期过滤:</td>
												<td align="left"><?php
        $date_options = array();
        $date_options[] = array('id' => '', 'text' => TEXT_ALL_ORDERS);
        //$date_options[] = array('id' => '3', 'text' => 'Three Days');
		$date_options[] = array('id' => '4', 'text' => 'four days ');//包含从今天起“出团日期”为四天以内的订单
		$date_options[] = array('id' => '3', 'text' => 'three days and 16 hours');
        $date_options[] = array('id' => '7', 'text' => 'One Week');
        $date_options[] = array('id' => '14', 'text' => 'Two Weeks');
        $date_options[] = array('id' => '31', 'text' => 'One Month');
        $date_options[] = array('id' => 'greater', 'text' => 'Future');
        echo tep_draw_pull_down_menu('start_date', $date_options, '', 'onChange="this.form.submit();" class="order_option2"');
?></td>
											</tr>
											<tr>
												<td>团号:</td>
												<td align="left"><?php echo tep_draw_input_field('sch_tour_code', $sch_tour_code, 'size="12" class="order_text2"');?></td>
                                                    <?php if ($access_full_edit == 'true' || $login_groups_id == '2' || $login_groups_id == '7') { ?>
                                                    <td><?php echo TABEL_HEADING_SALES_AMT; ?> </td>
												<td align="left"><?php echo tep_draw_input_field('sales_amt', $_GET['sales_amt'], 'size="12" class="order_text2"'); ?></td>
                                                        <?php } ?>																	
                                                    <td width="205"><?php echo HEADING_TITLE_STATUS; ?></td>
												<td width="205" align="left"><?php
        echo tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();" class="order_option2"');
?></td>
											</tr>

											<tr>
												<td>出团日期:</td>
												<td align="left">													
													<?php echo tep_draw_input_field('dept_start_date', tep_get_date_disp($dept_start_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
													至
													<?php echo tep_draw_input_field('dept_end_date', tep_get_date_disp($dept_end_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
													</td>
												<td width="170">地接最后回复时间:</td>
												<td width="205" align="left">
												  <?php echo tep_draw_input_field('provider_last_re_time_start', tep_get_date_disp($provider_last_re_time_start), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
												  至
												  <?php echo tep_draw_input_field('provider_last_re_time_end', tep_get_date_disp($provider_last_re_time_end), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
												  </td>
												<td><?php echo TEXT_PROVIDERS; ?></td>
												<td align="left"><?php
            $cond_status_ws_providers = "";
            if ($_GET['status'] != "") {
                $tmp_query = tep_db_query("SELECT DISTINCT p.agency_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o WHERE p.products_id=op.products_id AND o.orders_id=op.orders_id AND o.orders_status='" . tep_db_prepare_input($_GET['status']) . "' ");
				$in_agency_id_str = '';
				while($tmp_rows = tep_db_fetch_array($tmp_query)){
					$in_agency_id_str.=$tmp_rows['agency_id'].',';
				}
				$in_agency_id_str = substr($in_agency_id_str,0,-1);
				if(tep_not_null($in_agency_id_str)){
					$cond_status_ws_providers = " WHERE agency_id IN (".$in_agency_id_str.")";
				}
            }
            $provider_array = array(array('id' => '', 'text' => SELECT_NONE));
            $provider_raw = "select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " " . $cond_status_ws_providers . " order by agency_name asc";
			$provider_query = tep_db_query($provider_raw);
			//echo $provider_raw;
			
            while ($provider_result = tep_db_fetch_array($provider_query)) {
                $provider_array[] = array('id' => $provider_result['agency_id'], 'text' => $provider_result['agency_name']);
            }
            echo tep_draw_pull_down_menu('providers', $provider_array, $_GET['providers'], ' onChange="this.form.submit();" class="order_option2"');
?></td>

											</tr>
											<tr>
<?php if ($access_full_edit == 'true' || $login_groups_id == '2' || $login_groups_id == '7') { ?>			  			  
                                                <td> <?php echo TABEL_HEADING_INVOICE_NO; ?></td>
												<td align="left"><?php echo tep_draw_input_field('invoice_no', $_GET['invoice_no'], 'size="12" class="order_text2"'); ?></td>			  
												<?php } ?>
                                                    <td>接受回访时间:</td>
												<td align="left"><?php
                                                        $ret_time_option = array();
                                                        $ret_time_option[] = array('id' => '', 'text' => '--All--');
                                                        $ret_time_option[] = array('id' => 'Morning', 'text' => 'Morning');
                                                        $ret_time_option[] = array('id' => 'Midday', 'text' => 'Midday');
                                                        $ret_time_option[] = array('id' => 'Afternoon', 'text' => 'Afternoon');
                                                        $ret_time_option[] = array('id' => 'Night', 'text' => 'Night');
                                                        echo tep_draw_pull_down_menu('ret_time', $ret_time_option, '', 'onChange="this.form.submit();" class="order_option2"');
                                                        ?></td>
												<td>回访状态:</td>
												<td align="left"><?php
                                                        $ret_state = array();
                                                        $ret_state[] = array('id' => '', 'text' => '--All--');
                                                        $ret_state[] = array('id' => '0', 'text' => 'Unfinished');
                                                        $ret_state[] = array('id' => '1', 'text' => 'Finished');
                                                        echo tep_draw_pull_down_menu('is_ret', $ret_state, '', 'onChange="this.form.submit();" class="order_option2"');
                                                        ?></td>
											</tr>
											<tr>
                                                    <?php if ($access_full_edit == 'true') { ?>
                                                    <td> <?php echo TABEL_HEADING_INVOICE_AMT; ?></td>
												<td align="left"><?php echo tep_draw_input_field('invoice_amt', $_GET['invoice_amt'], 'size="12" class="order_text2"'); ?></td>
                                                  <?php } ?>
                                                    <td>订单类型:</td>
												<td align="left"><?php
                                                        $sch_order_type_data = array();
                                                        $sch_order_type_data[] = array('id' => '0', 'text' => '--All--');
                                                        $sch_order_type_data[] = array('id' => '1', 'text' => '结伴同游');
                                                        $sch_order_type_data[] = array('id' => '2', 'text' => '团体购买订单（老团购）');
                                                        $sch_order_type_data[] = array('id' => '3', 'text' => '新团购订单');
                                                        $sch_order_type_data[] = array('id' => '4', 'text' => '新团购订单（限量团）');
                                                        $sch_order_type_data[] = array('id' => '5', 'text' => '新团购订单（限时团）');
                                                        echo tep_draw_pull_down_menu('sch_order_type', $sch_order_type_data, '', 'onChange="this.form.submit();" class="order_option2"');
                                                        ?></td>
												<td align="right"><b>Amount:</b></td>
												<td align="left">
													<p>
														总计：<input type="text" style="color: #999"
															value="<?php echo $total_price_for_search = tep_not_null($_GET['total_price_for_search']) ? strtoupper($_GET['total_price_for_search']) : '$/￥';  ?>"
															size="12" name="total_price_for_search"
															class="cal-TextBox" />
													</p>
													<p>
														TBC ：<input type="text" style="color: #999"
															value="<?php echo $tbc_price_for_search = tep_not_null($_GET['tbc_price_for_search']) ? strtoupper($_GET['tbc_price_for_search']) : 'TBC$/￥';  ?>"
															size="12" name="tbc_price_for_search" class="cal-TextBox" />
													</p>
												</td>
											</tr>
											<tr>
												<td>电话号码:</td>
												<td align="left">
												  <?php echo tep_draw_input_field('customers_telephone', '', 'size="12" class="order_text2"'); ?>
												 <br /> <label><?php echo tep_draw_checkbox_field('include_account_telephone','1'); ?> 包括用户账号中的电话</label>
												</td>

												<td>付款状态:</td>
												<td align="left">
													<?php
													$orders_products_payment_status_options = array();
													$orders_products_payment_status_options[] = array('id'=>'','text'=>'--All--');
													$orders_products_payment_status_options = array_merge($orders_products_payment_status_options, tep_get_orders_products_payment_status_array());
													echo tep_draw_pull_down_menu('orders_products_payment_status', $orders_products_payment_status_options, '', 'onChange="this.form.submit();" class="order_option2"');
													?>
													<br /> <label><?php echo tep_draw_checkbox_field('no_call_provider','1'); ?> 未向地接下单</label>

												</td>
												<td align="right">&nbsp;</td>
												<td align="left">
													订单归属(工号)：
													<?php echo tep_draw_input_num_en_field('orders_owners','','class="order_text2" style="width:40px;"');?>
													<br />
													当前处理人(工号)：
													<?php echo tep_draw_input_num_en_field('next_job_number','','class="order_text2" style="width:40px;"');?>
													<br />
													销售链接工号：
													<?php echo tep_draw_input_num_en_field('orders_owner_admin_id_job_number','','class="order_text2" style="width:40px;"');?>
													</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td align="left"><label><?php echo tep_draw_checkbox_field('show_warning_on_admin','1'); ?> 客人更新航班信息</label>
													<label><?php echo tep_draw_checkbox_field('have_need_read_message','1'); ?> 有未读留言</label>
												</td>

												<td>OP备注:</td>
												<td align="left"><?php echo tep_draw_input_field('remark', $remark, 'size="12" class="order_text2"');?></td>
												<td align="right" valign="top">购买日期:</td>
												<td align="left">
													<?php echo tep_draw_input_field('date_purchased_start', tep_get_date_disp($date_purchased_start), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"').tep_draw_input_num_en_field('hour_purchased_start','','class="order_text2" style="width:20px;"').'点'.tep_draw_input_num_en_field('minute_purchased_start','','class="order_text2" style="width:20px;"').'分';?>
													<br /> 至 <br />
													<?php echo tep_draw_input_field('date_purchased_end', tep_get_date_disp($date_purchased_end), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"').tep_draw_input_num_en_field('hour_purchased_end','','class="order_text2" style="width:20px;"').'点'.tep_draw_input_num_en_field('minute_purchased_end','','class="order_text2" style="width:20px;"').'分';?>
													<?php echo tep_draw_pull_down_menu('purchased_time_zone', array( array('id'=>'America/Los_Angeles','text'=>'洛杉矶时间'), array('id'=>'Asia/Shanghai','text'=>'北京时间'), array('id'=>'America/New_York','text'=>'纽约时间'), array('id'=>'Pacific/Honolulu','text'=>'夏威夷时间')), rawurldecode($_GET['purchased_time_zone']), ' ');?>
													</td>
											</tr>
											<tr>
												<td align="right" valign="top"><a href="javascript:void(0);"
													onclick="$('#exclude_orders_status_td').toggle()">排除的订单状态:</a></td>
													<?php
													//允许过滤的订单状态
													$_display = 'display:none;';
													$_can_exclude_orders_status = array_merge(array('1','6','100006'), $confirm_exclude_orders_status);
													$_can_exclude_all_orders_status = true;
													$check_input_boxs = '';
													foreach((array)$orders_statuses as $checkboxs){
														if($_can_exclude_all_orders_status === true || in_array($checkboxs['id'], $_can_exclude_orders_status)){
															$_checked = '';
															$_class = 'orders_seach_exclude_status';
															if(is_array($confirm_exclude_orders_status) && in_array($checkboxs['id'], $confirm_exclude_orders_status)){
																$_checked = ' checked="checked" ';
																$_class .= ' orders_seach_exclude_status_checked';
																$_display = ' ';
															}
															$check_input_boxs .= '<label title="'.$checkboxs['text'].'" class="'.$_class.'"><input name="exclude_orders_status[]" type="checkbox" value="'.$checkboxs['id'].'" '.$_checked.' /> '.$checkboxs['text'].'</label>';
														}
													}
													?>
													<td colspan="5" align="left" id="exclude_orders_status_td" style="<?php echo $_display;?>">
													<?php echo $check_input_boxs;?>
													</td>
											</tr>
											<tr>
												<td colspan="6" align="center">
                                                    <?php
													if ($_GET['agret'] != "") {
														echo tep_draw_hidden_field('agret', $_GET['agret']);
													}
													
													$_title = '';
													if($can_see_non_payment_orders !== true){
														$_title = '您不能看别人的未付款订单';
													}
													?>
                                                    <input type="submit"
													name="btnsubmit" value="<?php echo IMAGE_SEARCH; ?>"
													title="<?= $_title;?>" class="but_5" />&nbsp;&nbsp;[<a
													href="<?php echo tep_href_link("orders.php");?>"
													target="_self">清除搜索条件</a>]
												</td>
											</tr>

										</table>
                                                        <?php echo '</form>'; ?>

                                        </td>
								</tr>
							</table>
							<form name="visa_search" method="post"
								action="visa.php?action=search" style="" target="_blank">
								<?php
								$visa = new visa();
								?>
								<table border="0" cellpadding="8"
									style="border: 1px solid #CCCCCC; background-color: #EEEEEE; text-align: center;">
									<tr>
										<td><span style="font-weight: bolder; color: #0033FF">签证订单搜索</span></td>
										<td>走四方订单号<br /><?php echo tep_draw_input_field('orders_id','','style="ime-mode:none; width:60px;"');?>
										</td>
										<td>签证订单号<br /><?php echo tep_draw_input_field('visa_orderid','','style="ime-mode:none; width:60px;"');?></td>
										<td>付款方式<br />
											<?php echo tep_draw_pull_down_menu('ORD_PAY_TAG', $visa->show_ORD_PAY_TAG(),'','',false);?>
										</td>
										<td>订单状态<br />
											<?php echo tep_draw_pull_down_menu('ORD_ADM_STA_TAG', $visa->show_ORD_ADM_STA_TAG(),'','',false);?>
										</td>
										<td>签证状态<br />
											<?php echo tep_draw_pull_down_menu('VIS_STATUS', $visa->show_VIS_STATUS(),'','',false);?>		
										</td>
										<td>付款状态<br />
												<?php echo tep_draw_pull_down_menu('vis_pay_status', array(array("id"=>"","text"=>"----"),array("id"=>"1","text"=>"已付"),array("id"=>"0","text"=>"未付")),'','',false);?>
										</td>
										<td>付款时间<br />
										<?php echo tep_draw_input_field('pay_date_start','','style="ime-mode:none; width:80px;" readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="开始"');?>
										-<?php echo tep_draw_input_field('pay_date_end','','style="ime-mode:none; width:80px;" readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="结束"');?>
										</td>
										<td>客人姓名<br />
										<?php echo tep_draw_input_field('customers_name','','style="width:60px;"');?>
										</td>
										<td>客人电话<br />
										<?php echo tep_draw_input_field('customers_telephone','','style="ime-mode:none; width:80px;"');?>
										</td>
										<!--<td>客人邮箱<br/>
										<?php echo tep_draw_input_field('customers_email_address','','style="ime-mode:none; width:80px;"');?>
										</td>//-->
										<td><input name="" type="submit" vlaue="QUERY" /></td>
									</tr>
								</table>
							</form>
						</td>
					</tr>
					<tr>
						<td><a href="javascript:void(0);"
							onclick="$('#search_box').toggle()">隐藏/展开搜索框</a></td>
					</tr>


<?php
                                                        // howard added Orders Warning start	  
                                                        $OldDateTime = date('Y-m-d 23:59:59', (time() - (15 * 24 * 60 * 60)));

                                                        $cc_check_sql = tep_db_query('SELECT orders_id FROM `orders` WHERE orders_status not in (0,6,100005,100058,100006) AND date_purchased < "' . $OldDateTime . '" AND cc_number !="" AND cc_number is not null ');

                                                        $array_1 = array();
                                                        while ($cc_check = tep_db_fetch_array($cc_check_sql)) {
                                                            $check_oh_sql = tep_db_query('SELECT orders_id FROM `orders_status_history` WHERE orders_id="' . (int) $cc_check['orders_id'] . '" AND orders_status_id ="100006" limit 1');
                                                            $check_oh_row = tep_db_fetch_array($check_oh_sql);
                                                            if (!(int) $check_oh_row['orders_id']) {
                                                                $array_1[] = $cc_check['orders_id'];
                                                            }
                                                        }


                                                        $flight_check_sql = tep_db_query('SELECT count(*) as count
                             FROM orders_product_flight f, orders o
                             WHERE f.orders_id = o.orders_id AND f.show_warning_on_admin = "1" AND (
                             o.orders_status <>6 AND o.orders_status <>100058 AND o.orders_status <>100002 AND o.orders_status <>100005 AND o.orders_status <>100006
)');
                                                        $flight_check_row = tep_db_fetch_array($flight_check_sql);
														
														//取得未读取的订单留言总条数
														$orders_message_query = tep_db_query("select count(*) as count from orders_message where has_read!=1 ");
														$orders_message = tep_db_fetch_array($orders_message_query);
														
														
														//provider updates里面仅显示供应商通过 provider account更新状态后，超过6小时客服未处理的订单
														//$orders_blinking_check_sql = tep_db_query('SELECT count(*) as count FROM '.TABLE_ORDERS.' WHERE is_blinking="1" AND  ADDTIME(is_blinking_date,"6:00:00")  < NOW()');
														//$orders_blinking_check_sql = tep_db_query('SELECT count(*) as count FROM '.TABLE_ORDERS.' WHERE is_blinking="1" ');
	 													//$orders_blinking_check_row = tep_db_fetch_array($orders_blinking_check_sql);
														//以上数据暂不统计了，没什么意义 howard added
														//order_up_no_change_status已付款订单，订单金额没有变化，订单内容有更新：参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改
														$no_change_status_orders_sql = tep_db_query('SELECT count(*) as count FROM '.TABLE_ORDERS.' WHERE order_up_no_change_status="1" ');
	 													$no_change_status_orders_row = tep_db_fetch_array($no_change_status_orders_sql);
														
														//取得财务需要处理的订单总数
														$need_accounting_todo_total_sql = tep_db_query('SELECT count(*) as count FROM '.TABLE_ORDERS.' WHERE need_accounting_todo="1" ');
														$need_accounting_todo_total_row = tep_db_fetch_array($need_accounting_todo_total_sql);
														
?>
                        <tr>
						<td class="main" colspan="2">
							<?php
							//自动给当前选中的Orders Warning项目添加颜色 Howard added by 2013-02-20
							for($_i = 0; $_i<10; $_i++){
								${'owb'.$_i.'_style'} = '';	//预定变量
							}
							if($_GET['select_b']){
								${$_GET['select_b']} = 'style="color:#FF6601"';	//如：$owb0_style = 'style="color:#FF6601"';	
							}
							
							?>
							<ul class="orders_warning">
								<li><strong>Orders Warning</strong></li>
								<?php
								//5-02已发电子参团凭证 、已出团获积分、8-01撤销原单，下新单1 、 0-05客人要求取消,递交主管审核 、9-05 Refunded 已退款(全额退款）、3-02行程确认信(但航班等部分信息待补) 。以上状态订单不在【价格已变动订单】中显示
								$price_update_orders = $PCA->get_product_price_update_orders('0,2','','6,100006,100130,100152,100005,100083');
								if($price_update_orders){?>
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'in_orders_id='.implode(',',$price_update_orders));?>" title="<?php echo implode(', ',$price_update_orders);?>"><b class="errorText">未付款订单价格变更提醒<?php echo sizeof($price_update_orders);?></b></a>]</li>
								<?php }?>	
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'no_call_provider=1&orders_products_payment_status=1&exclude_orders_status=100134,100136&select_b=owb0_style&is_packet_group=1');?>"
									title="邀请函/签证订单[100136]、已付款未下单需退款订单不在此列显示[100134]"><b
										<?php echo $owb0_style;?>>已付款未下单</b></a>]
								</li>
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'no_call_provider=1&orders_products_payment_status=2&exclude_orders_status=100134,100136&select_b=owb1_style');?>"
									title="邀请函/签证订单100136、已付款未下单需退款订单不在此列显示"><b
										<?php echo $owb1_style;?>>已部分付款未下单</b></a>]
								</li>

								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'has_call_provider_wait_confirm=1&select_b=owb2_style');?>"
									title="最后一次下单的订单都到此列表，地接已回复，OP已发电子参团凭证就离开此列表"><b
										<?php echo $owb2_style;?>>已下单等确认</b></a>]
								</li>
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'filteration_get=PU&select_b=owb3_style');?>"
									title="地接已经确认各更新了的订单列表，操作员去处理之前不管当前订单状态是什么此列表都不会丢失！"><b
										<?php echo $owb3_style;?>>地接确认与更新</b><span class="errorText"><b><?php // echo (int)$orders_blinking_check_row['count']?></b></span></a>]
								</li>
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'op_think_problems=1&select_b=owb4_style');?>"
									title="包括:
3.行程确认信 => 3-02行程确认信(但航班等部分信息待补) (orders_status_id=100083)
4.需补充参离团信息 => 4-01再次请客人提供参离团信息 (orders_status_id=100012)
4.需补充参离团信息 => 4-02请结伴同游者提供航班参离团信息 (orders_status_id=100078)
5.已发电子参团凭证 => 5-03已发参团凭证，但操作员认为有问题的订单 (orders_status_id=100135)"><b
										<?php echo $owb4_style;?>>操作员认为有问题的订单</b></a>]
								</li>
								<li>[<a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'need_accounting_todo=1&select_b=owb5_style');?>"><b
										<?php echo $owb5_style;?>>财务要处理的订单</b><b class="errorText"><?= (int)$need_accounting_todo_total_row['count'];?></b></a>]
								</li>
								<li>[<a
									href="<?php echo tep_href_link('orders_warning.php', 'action=flight_check'); ?>"
									target="_blank"><b <?php echo $owb6_style;?>>客人更新航班信息及留言</b><span
										class="errorText"><b><?php echo ((int)$flight_check_row['count']+(int)$orders_message['count']) ?></b></span></a>]
								</li>
								<li><a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'next_job_number='.tep_get_job_number_from_admin_id($login_id).'&select_b=owb7_style&exclude_orders_status[]=6'); ?>"><b
										<?php echo $owb7_style;?>>我要处理的订单</b></a></li>
								<li><a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'orders_owners='.tep_get_job_number_from_admin_id($login_id).'&select_b=owb8_style'); ?>"><b
										<?php echo $owb8_style;?>>我的订单</b></a></li>
								<li><a
									href="<?php echo tep_href_link(FILENAME_ORDERS,'order_up_no_change_status=1&select_b=owb9_style');?>" title="已付款订单，订单金额没有变化，订单内容有更新：参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改都在此表列出！"><b <?php echo $owb9_style;?>>未产生费用，有更新的订单</b><span class="errorText"><b><?php echo (int)$no_change_status_orders_row['count']?></b></span></a></li>
								
								<li><a href="<?php echo tep_href_link(FILENAME_TOUR_CODE_DECODE); ?>" target="_blank"><b>查看产品编号与供应商编码</b></a></li>
								<li><a href="<?php echo tep_href_link(FILENAME_ORDERS,'status=100154&select_b=owb10_style')?>" <?= $owb10_style;?>><b>主管审核有问题需销售继续跟进</b></a></li>
								<li><a href="<?php echo tep_href_link(FILENAME_ORDERS,'is_again_paid=1&select_b=owb11_style')?>" <?= $owb11_style;?>><b>再次付款需更新订单</b></a></li>
								<li><a href="<?php echo tep_href_link(FILENAME_ORDERS,'start_date=7&orders_products_payment_status=1&exclude_orders_status[]=100002&date_purchased_start=&orders_owners='.tep_get_job_number_from_admin_id($login_id)).'&select_b=owb12_style'?>" <?= $owb12_style;?>><b>一周之内需检查</b></a></li>
								<li><a href="<?php echo tep_href_link(FILENAME_ORDERS,'owner_is_change=1');?>" <?= $owb12_style;?>><b>重新分配的订单</b></a></li>
							</ul>

						</td>
					</tr>
                                            
											<?php
                                                        // howard added Orders Warning end
                                            ?>
            
                                    <tr>

						<td>
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top">
										<table border="0" width="100%" cellspacing="0" cellpadding="2">   
													 <?php                                                       

                                                        $href_get_params = tep_get_all_get_params(array('page', 'oID', 'action', 'sort', 'order'));
														$img_up = '<img src="images/arrow_up.gif" border="0">';
														$img_down = '<img src="images/arrow_down.gif" border="0">';
														$HEADING_CUSTOMERS = TABLE_HEADING_CUSTOMERS;

                                                        $HEADING_DATE_PURCHASED = TABLE_HEADING_DATE_PURCHASED;

                                                        $HEADING_DATE_PURCHASED .= '<a href="'. tep_href_link('orders.php','sort=date&order=ascending&'.$href_get_params).'">&nbsp;'.$img_up.'</a>';
                                                        $HEADING_DATE_PURCHASED .= '<a href="' .tep_href_link('orders.php','sort=date&order=decending&'.$href_get_params). '">&nbsp;'.$img_down.'</a>';

                                                        $HEADING_AD_SOURCE = 'Ad Source';

                                                        $HEADING_DATE_OF_DEPARTURE = '出团日期';
                                                        $HEADING_DATE_OF_DEPARTURE .= '<a href="' .tep_href_link('orders.php','sort=departure_date&order=ascending&'.$href_get_params). '">&nbsp;'.$img_up.'</a>';
                                                        $HEADING_DATE_OF_DEPARTURE .= '<a href="' .tep_href_link('orders.php','sort=departure_date&order=decending&'.$href_get_params). '">&nbsp;'.$img_down.'</a>';

                                                        $HEADING_LAST_MODIFY_DATE   = '更新日期';
                                                        $HEADING_LAST_MODIFY_DATE .= '<a href="' .tep_href_link('orders.php','sort=last_modify_date&order=ascending&'.$href_get_params). '">&nbsp;'.$img_up.'</a>';
                                                        $HEADING_LAST_MODIFY_DATE .= '<a href="' .tep_href_link('orders.php','sort=last_modify_date&order=decending&'.$href_get_params). '">&nbsp;'.$img_down.'</a>';
														
                                                        $HEADING_LAST_STATUS_MODIFY_BY = TXT_LAST_STATUS_CHANGED_BY;
														
                                                        $HEADING_PROVIDER_LAST_RE_TIME = '地接回复时间';
                                                        $HEADING_PROVIDER_LAST_RE_TIME .= '<a href="' .tep_href_link('orders.php','sort=provider_last_re_time&order=ascending&'.$href_get_params). '">&nbsp;'.$img_up.'</a>';
                                                        $HEADING_PROVIDER_LAST_RE_TIME .= '<a href="' .tep_href_link('orders.php','sort=provider_last_re_time&order=decending&'.$href_get_params). '">&nbsp;'.$img_down.'</a>';
														
														$HEADING_CURRENT_DEAL_WITH = '当前处理人';
														//$HEADING_CURRENT_DEAL_WITH .= '<a title="按紧急度升序排序" href="' .tep_href_link('orders.php','sort=current_deal_with&order=ascending&'.$href_get_params). '">&nbsp;'.$img_up.'</a>';
														$HEADING_CURRENT_DEAL_WITH .= '<a title="按紧急度降序排序" href="' .tep_href_link('orders.php','sort=current_deal_with&order=decending&'.$href_get_params). '">&nbsp;'.$img_down.'</a>';
														?>
        
                                                        <tr
												class="dataTableHeadingRow">
												<td class="dataTableHeadingContent"><?php echo HEADING_ORDER_ID; ?></td>
												<td class="dataTableHeadingContent"><?php echo $HEADING_CUSTOMERS; ?></td>
												<!--<td class="dataTableHeadingContent"><?php echo $HEADING_AD_SOURCE; ?></td>
															<td class="dataTableHeadingContent">Ref. URL</td>-->
												<td class="dataTableHeadingContent" align="right" nowrap> <?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
												<td class="dataTableHeadingContent" align="right"><span
													style="color: #666666; font-size: 12px; font-weight: normal; border: 1px dotted #999999;">销售链接</span><br />订单归属</td>
												<td class="dataTableHeadingContent" nowrap><?php echo $HEADING_DATE_PURCHASED; ?></td>
												<td class="dataTableHeadingContent" nowrap><?php echo $HEADING_DATE_OF_DEPARTURE; ?></td>
												<td class="dataTableHeadingContent"><?php echo $HEADING_LAST_MODIFY_DATE; ?></td>
												<td class="dataTableHeadingContent"><?php echo $HEADING_LAST_STATUS_MODIFY_BY; ?></td>
												<td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_TOUR_CODE; ?></td>
												<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDER_TOUR_CODE; ?></td>
												<!--<td class="dataTableHeadingContent" align="right">Follow Up</td>-->
												<td class="dataTableHeadingContent" align="right"><?php echo $HEADING_PROVIDER_LAST_RE_TIME;?></td>
												<td class="dataTableHeadingContent" align="right">付款状态</td>
												<td class="dataTableHeadingContent" align="right"><?php echo $HEADING_CURRENT_DEAL_WITH;?></td>
												<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
												<?php if ($can_packet_group == true) { ?>
												<td class="dataTableHeadingContent" align="right" width="70">包团</td>
												<?php }?>
												<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
											</tr> 
                                                <?php
                                                    //by panda 增加从orders_status中comment搜索TBC字样选项 start
                                                
                                                        if (tep_not_null($_GET['tbc_price_for_search']) && (strtoupper($_GET['tbc_price_for_search']) != 'TBC$/￥')){
                                                            $Fields = 'o.*, ot.value as order_total_num, ot.text as order_total, GROUP_CONCAT( osh.comments ) comments';
                                                            $Tables = TABLE_ORDERS.' o , '.TABLE_ORDERS_TOTAL.' ot, '.TABLE_ORDERS_STATUS_HISTORY.' osh';
                                                            $Where = ' 1 AND ot.orders_id = o.orders_id AND o.orders_id = osh.orders_id  AND ot.class = "ot_total"';
                                                            //by panda 增加从orders_status中comment搜索TBC字样选项 end
                                                            $GrounpBy = 'GROUP BY o.orders_id ';
                                                        }else{
                                                            $Fields = 'o.*, ot.value as order_total_num, ot.text as order_total';
                                                            $Tables = TABLE_ORDERS.' o , '.TABLE_ORDERS_TOTAL.' ot';
                                                            $Where = ' 1 AND ot.orders_id = o.orders_id  AND ot.class = "ot_total"';
                                                            //by panda 增加从orders_status中comment搜索TBC字样选项 end
                                                            $GrounpBy = ' ';
                                                        }
														//默认的排序方式
														$OrderBy = ' o.is_top DESC, o.orders_status asc, o.orders_id DESC ';
														
														$sortorder = 'order by ';
                                                        $addedtable = '';
                                                        $addextracondition = '';
														//排序 start {
														if(tep_not_null($_GET["sort"])){
															if ($_GET["order"] == 'ascending'){ $sort_az = " asc"; }else{ $sort_az = " desc"; }
															switch($_GET["sort"]){
																case "date":
																	$OrderBy = " o.date_purchased ".$sort_az;
																break;
																case "departure_date":	//按出发日期排序
																	if(trim($sort_az)=="asc"){
																		$Fields .= ", min(op.products_departure_date) AS opdate ";
																	}else{
																		$Fields .= ", max(op.products_departure_date) AS opdate ";
																	}
																	if(!preg_match('/ op /',$Tables)){
																		$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																		$Where .= ' AND op.orders_id=o.orders_id ';
																		$GrounpBy = ' group by o.orders_id ';
																	}
																	$OrderBy = " opdate ".$sort_az;
																break;
																case "provider_last_re_time":	//按地接最后回复排序
																	if(!preg_match('/ op /',$Tables)){
																		$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																		$Where .= ' AND op.orders_id=o.orders_id ';
																		$GrounpBy = ' group by o.orders_id ';
																	}
																	
																	if(!preg_match('/ popsh /',$Tables)){
																		$Tables .= ', provider_order_products_status_history popsh ';
																		$Where .= ' AND op.orders_products_id=popsh.orders_products_id ';
																		$GrounpBy = ' group by o.orders_id ';
																	}
																	$Where .= ' AND popsh.popc_user_type="1" ';
																	
																	$OrderBy = " popsh.provider_status_update_date ".$sort_az;
																break;
																case "last_modify_date":
																	$OrderBy = " o.last_modified ".$sort_az;
																break;
																case "current_deal_with":	//按当前处理人的紧急度排序
																	$OrderBy = " o.need_next_urgency ".$sort_az;
																break;											
															}
														}
														//排序 end }
														//不包含的订单状态start{
														if($confirm_exclude_orders_status){	
															$Where .= ' AND o.orders_status NOT IN('.implode(',',$confirm_exclude_orders_status).') ';
														}
														//不包含的订单状态end}
														if($_GET['agret']=='1'){
															$Where .= ' AND o.is_agret="1" ';
														}
														//地接确认与更新
														if(isset($_GET['filteration_get']) && $_GET['filteration_get']!=''){
															//$Where .= ' AND (o.is_blinking="1"  AND  ADDTIME(is_blinking_date,"6:00:00")  < NOW() )';
															$Where .= ' AND o.is_blinking="1" ';
														}
														
														if(isset($_GET['need_accounting_todo']) && $_GET['need_accounting_todo']=='1'){
															$Where .= ' AND o.need_accounting_todo="1" ';
														} 
														
														if(tep_not_null($_GET['ret_time'])){
															$Tables .= ', orders_return_visit rv ';
															$Where .= ' AND rv.orders_id=o.orders_id AND rv.visit_time="'.tep_db_prepare_input($_GET['ret_time']).'" ';
															$GrounpBy = ' group by o.orders_id ';
														}
														if($_GET['is_ret']=="0" || $_GET['is_ret']=="1"){
															$Where .= ' AND o.is_ret="'.$_GET['is_ret'].'" ';
														}
														
                                                        if (isset($_GET['cID'])) {
                                                            $cID = tep_db_prepare_input($_GET['cID']);
															$Where .= ' AND o.customers_id="'.$cID.'" ';

                                                        } elseif (tep_not_null($_GET['status'])) {

                                                            $status = tep_db_prepare_input($_GET['status']);
															$Where .= ' AND o.orders_status="'.$status.'" ';
															//订单避免重复更新优化BEGIN
															//Vincent 2011-6-12 如果有查询status字段而没有指定followup_team_type字段
															//而状态又处于cn.us follow下则自动加入过滤字段
															if(!tep_not_null($_GET['followup_team_type'])){																
																if(in_array($status,$followup_array_us)){
																		$Where .=' AND ( o.followup_team_type = 1 OR o.followup_team_type=0 ) ';
																}else if(in_array($status,$followup_array_cn)){
																		$Where .=' AND ( o.followup_team_type = 2 OR o.followup_team_type=0 ) ';
																}
															}
															//订单避免重复更新优化 END

															
                                                        } elseif (tep_not_null($_GET['start_date'])) {

                                                            $start_date = tep_db_prepare_input($_GET['start_date']);

                                                            switch ($_GET['start_date']) {
                                                                case '3':
																	/* 110718-2_ 优化为，可筛选出three days and 16 hours（即3天零16个小时）的订单 By Panda*/ 
																	$start_date_where = " and o.orders_status not in(100002,100006,6)  and TIMESTAMPDIFF(SQL_TSI_MINUTE,'" . date('Y-m-d H:i:s') ."',op.products_departure_date)>=0 and TIMESTAMPDIFF(SQL_TSI_MINUTE,'" . date('Y-m-d H:i:s') . "',op.products_departure_date)<=5280";		
                                                                    break;
																 case '4':
																	 //four days.搜索结果包含从今天起“出团日期”为四天以内的订单。比如今天是10月26日，那么搜索出的结果应该显示“出团日期”为10月26、27、28、29的订单(注意：是按照日期判定，不是时间)。100077 CC Voided 100006 Charge Captured （I）6 Cancelled 已为您取消订单100005 Refunded 已退款
																	$start_date_where = " and o.orders_status not in(100077,100006,6,100005)  and TIMESTAMPDIFF(SQL_TSI_MINUTE,'".date('Y-m-d' ,time())."',op.products_departure_date)>=0  AND TIMESTAMPDIFF(SQL_TSI_MINUTE,'".date('Y-m-d' ,time())."',op.products_departure_date)<=".(60*24*4);
                                                                    break;
                                                                case '7':
                                                                    $start_date_where = " and o.orders_status not in(100002,100006,6)  and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=7";
                                                                    break;
                                                                case '14':
                                                                    $start_date_where = " and o.orders_status not in(100002,100006,6)  and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=14";
                                                                    break;
                                                                case '31':
                                                                    $start_date_where = " and o.orders_status not in(100002,100006,6)  and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=31";
                                                                    break;
                                                                case 'greater':
                                                                    $start_date_where = " and o.orders_status not in(100002,100006,6)  and op.products_departure_date > '" . date('Y-m-d') . "'";
                                                                    break;
                                                                default:
                                                                    $start_date_where = " ";
                                                                    break;
                                                            }
															
															$Where .= $start_date_where;
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}

                                                        }
                                                        
														if(tep_not_null($_GET['search'])){
															$Where .= ' AND o.orders_id Like Binary ("'.(int)$_GET['search'].'%") ';
														}
														//OP备注搜索 start {
														if(tep_not_null($_GET['remark'])){
															if(!preg_match('/ ork /',$Tables)){
																$Tables .= ', orders_remark ork ';
																$Where .= ' AND ork.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															$Where .= ' AND ork.remark Like Binary ("%'.tep_db_prepare_input($_GET['remark']).'%") ';
														}
														//OP备注搜索 end }
														//Howard fixed 出团日期start {
														if(tep_not_null($_GET['dept_start_date']) || tep_not_null($_GET['dept_end_date'])){
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															
															if(tep_not_null($_GET['dept_start_date'])){
																$make_start_date = tep_get_date_db($_GET['dept_start_date'])." 00:00:00";
																$Where .= ' AND op.products_departure_date>="'.$make_start_date.'" ';
																
															}
															if(tep_not_null($_GET['dept_end_date'])){
																$make_end_date = tep_get_date_db($_GET['dept_end_date'])." 23:59:59";
																$Where .= ' AND op.products_departure_date<="'.$make_end_date.'" ';
																
															}
														}
														//Howard fixed 出团日期end }
														//Howard added 购买日期start {
														if(tep_not_null($_GET['date_purchased_start']) || tep_not_null($_GET['date_purchased_end'])){
															if(tep_not_null($_GET['date_purchased_start'])){
																$_hour = $_minute = '00';
																if($_GET['hour_purchased_start']){
																	$_hour = min((int)$_GET['hour_purchased_start'],23);
																}
																if($_GET['minute_purchased_start']){
																	$_minute = min((int)$_GET['minute_purchased_start'],59);
																}
																$date_purchased_start = date('Y-m-d H:i:s', strtotime(tep_get_date_db($_GET['date_purchased_start'])." ".$_hour.":".$_minute.":00"));
																$sql_date_purchased_start = zone_time_to_system_zone_time($date_purchased_start, rawurldecode($_GET['purchased_time_zone']));
		
																$Where .= ' AND o.date_purchased>="'.$sql_date_purchased_start.'" ';
																
															}
															if(tep_not_null($_GET['date_purchased_end'])){
																$_hour = '23'; $_minute = '59';
																if($_GET['hour_purchased_end']){
																	$_hour = min((int)$_GET['hour_purchased_end'],23);
																}
																if($_GET['minute_purchased_end']){
																	$_minute = min((int)$_GET['minute_purchased_end'],59);
																}
																$date_purchased_end = tep_get_date_db($_GET['date_purchased_end'])." ".$_hour.":".$_minute.":59";
																$sql_date_purchased_end = zone_time_to_system_zone_time($date_purchased_end, rawurldecode($_GET['purchased_time_zone']));
																
																$Where .= ' AND o.date_purchased<="'.$sql_date_purchased_end.'" ';
															}
														}
														//Howard added 购买日期end }														
														//Howard added 地接最后回复时间start {
														if(tep_not_null($_GET['provider_last_re_time_start']) || tep_not_null($_GET['provider_last_re_time_end'])){
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															
															if(!preg_match('/ popsh /',$Tables)){
																$Tables .= ', provider_order_products_status_history popsh ';
																$Where .= ' AND op.orders_products_id=popsh.orders_products_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															
															$Where .= ' AND popsh.popc_user_type="1" ';
															
															if(tep_not_null($_GET['provider_last_re_time_start'])){
																$tmp_date_start = date('Y-m-d 00:00:00', strtotime($_GET['provider_last_re_time_start']));
																$Where .= ' AND popsh.provider_status_update_date >="'.$tmp_date_start.'" ';
															}
															if(tep_not_null($_GET['provider_last_re_time_end'])){
																$tmp_date_end = date('Y-m-d 23:59:59', strtotime($_GET['provider_last_re_time_end']));
																$Where .= ' AND popsh.provider_status_update_date <="'.$tmp_date_end.'" ';
															}
														}
														//Howard added 地接最后回复时间end }
														
														if(tep_not_null($_GET['sales_amt'])){
															$Where .= ' AND op.final_price Like Binary ("' . tep_db_prepare_input($_GET['sales_amt']) . '%") ';
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}                                                                                                               
														if(tep_not_null($_GET['sch_order_type'])){
															$sch_order_type = intval($_GET['sch_order_type']);
															if($sch_order_type==1){
																$Where .= ' AND otcom.orders_id=o.orders_id ';
																if(!preg_match('/ orders_travel_companion otcom /',$Tables)){
																	$Tables .= ', orders_travel_companion otcom ';
																	$GrounpBy = ' group by o.orders_id ';
																}
															}else if($sch_order_type==2){
																$Where .= ' AND op.group_buy_discount>0 ';
																if(!preg_match('/ '.TABLE_ORDERS_PRODUCTS.' op /',$Tables)){
																	$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																	$Where .= ' AND op.orders_id=o.orders_id ';
																	$GrounpBy = ' group by o.orders_id ';
																}
															}else if($sch_order_type==3 || $sch_order_type==4 || $sch_order_type==5){
																//新团购的SQL {
																$Where .= ' AND op.is_new_group_buy="1" ';
																if(!preg_match('/ '.TABLE_ORDERS_PRODUCTS.' op /',$Tables)){
																	$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																	$Where .= ' AND op.orders_id=o.orders_id ';
																	$GrounpBy = ' group by o.orders_id ';
																}
																
																if($sch_order_type==4){
																	$Where .= ' AND op.new_group_buy_type=1 ';
																}
																if($sch_order_type==5){
																	$Where .= ' AND op.new_group_buy_type=2 ';
																}
																//新团购的SQL }
															}
														}
														if(tep_not_null($_GET['sch_tour_code'])){
															$Where .= ' AND op.products_model Like "%'.tep_db_prepare_input($_GET['sch_tour_code']).'%" ';
															if(!preg_match('/ '.TABLE_ORDERS_PRODUCTS.' op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														if(tep_not_null($_GET['invoice_amt']) || tep_not_null($_GET['invoice_no'])){
															if(tep_not_null($_GET['invoice_amt'])){
																$Where .= ' and oph.invoice_amount Like Binary ("' . tep_db_prepare_input($_GET['invoice_amt']) . '%") ';
															}
															if(tep_not_null($_GET['invoice_no'])){
																$Where .= ' and oph.invoice_number Like Binary ( "' . tep_db_prepare_input($_GET['invoice_no']) . '%" )';
															}
															if(!preg_match('/ oph /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY.' oph ';
																$Where .= ' oph.orders_products_id = op.orders_products_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														if((int)$_GET['providers']){
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
															if(!preg_match('/ p /',$Tables)){
																$Tables .= ', '.TABLE_PRODUCTS.' p ';
																$Where .= ' AND p.products_id=op.products_id ';
															}
															$Where .= " AND p.agency_id =".(int)$_GET['providers'];
														}
														
														if(tep_not_null($_GET['customers_name'])){
															$c_sql = tep_db_query('SELECT customers_id FROM `customers` WHERE customers_firstname Like Binary ("%'.tep_db_prepare_input($_GET['customers_name']).'%") or customers_lastname Like Binary ("%'.tep_db_prepare_input($_GET['customers_name']).'%") or customers_email_address Like Binary ("'.tep_db_prepare_input($_GET['customers_name']).'%")');
															$c_id = array();
															while($c_rows = tep_db_fetch_array($c_sql)){
																$c_id[] = $c_rows['customers_id'];
															}
															if((int)sizeof($c_id)){
																$Where .= ' AND o.customers_id IN('.implode(',',$c_id).') ';
															}else{
																$Where .= ' AND o.customers_name Like Binary ("%'.tep_db_prepare_input($_GET['customers_name']).'%") ';
															}
														}
														//by vincent 订单避免重复更新管理优化 begin 
														if(in_array($_GET['followup_team_type'] , array('1','2','3'))){
															$Where .= ' AND o.followup_team_type = '.intval($_GET['followup_team_type']);
														}
														if(strpos($Where , 'o.followup_team_type = 3') === false && $_GET['from_follow_box']=="1"){ //如果不是查询china night shift功能则过滤掉china night shift订单
															$Where.= ' AND o.followup_team_type <> 3';
														}
														//by vincent 订单避免重复更新管理优化 end 
                                                                                                                                                                                                                                
														//by panda 增加Amount搜索选项 start {  
                                                        // 按订单总价搜索                                                         
                                                        if (tep_not_null($_GET['total_price_for_search'])  && ($_GET['total_price_for_search'] != '$/￥')){
                                                            $total_price = $_GET['total_price_for_search'];
                                                            $arr = explode(',', $total_price);
                                                            $total_price_for_search = '';
                                                            for ($i=0; $i < count($arr); $i++){
                                                                $total_price_for_search .= $arr[$i]; 
                                                            }                                                            
                                                            if (strpos($total_price_for_search , '$') !== false){                                                             
                                                                $total_price_for_search_arr = array_values(array_filter(explode('$', $total_price_for_search)));                                                                
                                                                $total_price = $total_price_for_search_arr[0];   
                                                                $total_price_format = number_format($total_price, 2, '.', ',');
                                                                $total_price_format2 = number_format($total_price);
                                                                //$Where .= ' AND (ot.text LIKE Binary("%$'.$total_price_format.'%") OR ot.text LIKE Binary("%$'.$total_price_format2.'%")) '; 
                                                                $total_price_format3 = $total_price + 1;
                                                                $Where .= ' AND o.currency = "USD" AND (ot.value = '.$total_price.')';
                                                            }                                                                
                                                            if(strpos($total_price_for_search , '￥') !== false){                                                                                                                              
                                                                $total_price_for_search_arr = array_values(array_filter(explode('￥', $total_price_for_search)));                                                                
                                                                $total_price = $total_price_for_search_arr[0]; 
                                                                $total_price_format = number_format($total_price, 2, '.', ',');
                                                                $total_price_format2 = number_format($total_price);
                                                                //$use_new_sql = true;                                                                
                                                                //$orders_query_raw_new_sql = "SELECT o.*, (o.us_to_cny_rate * ot.value) AS rmb, ot.value as order_total_num ,ot.text as order_total FROM `orders` AS o, orders_total AS ot  WHERE
                                                                // o.orders_id = ot.orders_id  AND o.us_to_cny_rate !=0 AND ROUND((o.us_to_cny_rate * ot.value),0) =".$total_price; 
                                                                $Where .= ' AND o.us_to_cny_rate !=0 AND ROUND((o.us_to_cny_rate * ot.value),0) ='.$total_price;
                                                            }
                                                        }
                                                        //按TBC字样搜索
                                                        if (tep_not_null($_GET['tbc_price_for_search'])   && (strtoupper($_GET['tbc_price_for_search']) != 'TBC$/￥')){
                                                            $tbc_price = strtoupper($_GET['tbc_price_for_search']);   
                                                            $arr = explode(',', $tbc_price);
                                                            $tbc_price_for_search = '';
                                                            for ($i=0; $i < count($arr); $i++){
                                                                $tbc_price_for_search .= $arr[$i]; 
                                                            }
                                                            if (strpos($tbc_price_for_search, '$') !== false){
                                                                $tbc_price_for_search_arr = array_values(array_filter(explode('TBC$', $tbc_price_for_search)));                                                                
                                                                $tbc_price = $tbc_price_for_search_arr[0]; 
                                                                $tbc_price = number_format($tbc_price, 2, '.', ',');
                                                                $Where .= ' AND (comments LIKE Binary("%TBC$'.$tbc_price.'%") OR comments LIKE Binary("%TBC$'.(int)$tbc_price_for_search_arr[0].'%") OR comments LIKE Binary("%tbc$'.$tbc_price.'%") OR comments LIKE Binary("%tbc$'.(int)$tbc_price_for_search_arr[0].'%"))';
                                                            }
                                                            if (strpos($tbc_price_for_search, '￥') !== false){
                                                                $tbc_price_for_search_arr = array_values(array_filter(explode('TBC￥', $tbc_price_for_search)));                                                                
                                                                $tbc_price = $tbc_price_for_search_arr[0]; 
                                                                $tbc_price = number_format($tbc_price, 2, '.', ',');                                                                
                                                                $Where .= ' AND (comments LIKE Binary("%TBC￥'.$tbc_price.'%") OR comments LIKE Binary("%TBC￥'.(int)$tbc_price_for_search_arr[0].'%") OR comments LIKE Binary("%tbc￥'.$tbc_price.'%") OR comments LIKE Binary("%tbc￥'.(int)$tbc_price_for_search_arr[0].'%"))';
                                                            }                                                            
                                                        }
														
														//按电话号码搜索{
														if(tep_not_null($_GET['customers_telephone'])){
															$Where.= ' AND ( o.customers_telephone Like "%'.tep_db_prepare_input($_GET['customers_telephone']).'%" ';
															$tmpWhere = '';
															if($_GET['include_account_telephone']=="1"){
																$Tables .= '';
																if(!preg_match('/ '.TABLE_CUSTOMERS.' c /',$Tables)){
																	$Tables .= ', '.TABLE_CUSTOMERS.' c ';
																	$GrounpBy = ' group by o.orders_id ';
																	$tmpWhere.= ' AND c.customers_id=o.customers_id ';
																}

																$Where .= ' || c.customers_telephone Like "%'.tep_db_prepare_input($_GET['customers_telephone']).'%" ';
																$Where .= ' || c.customers_mobile_phone Like "%'.tep_db_prepare_input($_GET['customers_telephone']).'%" ';
																$Where .= ' || c.customers_cellphone Like "%'.tep_db_prepare_input($_GET['customers_telephone']).'%" ';
																
															}
															$Where.= ') ';
															$Where.= $tmpWhere;
														}
														//按电话号码搜索}
														
														//按付款状态搜索{
														if(tep_not_null($_GET['orders_products_payment_status'])){
															$Where.= ' AND op.orders_products_payment_status = "'.tep_db_prepare_input($_GET['orders_products_payment_status']).'" ';
															if(!preg_match('/ '.TABLE_ORDERS_PRODUCTS.' op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														//按付款状态搜索}
														//客人更新航班信息搜索{
														if(tep_not_null($_GET['show_warning_on_admin']) && $_GET['show_warning_on_admin']=="1"){
															$Where.= ' AND opf.show_warning_on_admin = "1" ';
															if(!preg_match('/ orders_product_flight opf /',$Tables)){
																$Tables .= ', orders_product_flight opf ';
																$Where .= ' AND opf.orders_id=o.orders_id  AND (o.orders_status <>6 AND o.orders_status <>100058 AND o.orders_status <>100002 AND o.orders_status <>100005 AND o.orders_status <>100006) ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														//客人更新航班信息搜索}
														//有未读留言搜索{
														if(tep_not_null($_GET['have_need_read_message']) && $_GET['have_need_read_message']=="1"){
															$Where.= ' AND om.has_read!="1" ';
															if(!preg_match('/ orders_message om /',$Tables)){
																$Tables .= ', orders_message om ';
																$Where .= ' AND om.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														//有未读留言搜索}
														
																											
														//按订单归属的工号搜索{
														if(tep_not_null($_GET['orders_owners'])){
															$Where.= ' AND FIND_IN_SET("'.tep_db_prepare_input($_GET['orders_owners']).'",o.orders_owners) ';
														}
														//按订单归属的工号搜索}
														
														//按当前处理人的工号搜索{
														if(tep_not_null($_GET['next_job_number'])){
															$_next_admin_id = tep_get_admin_id_from_job_number($_GET['next_job_number']);
															if((int)$_next_admin_id){
																if(!preg_match('/ orders_status_history osh /',$Tables)){
																	$Tables .= ', orders_status_history osh ';
																	$Where .= ' AND osh.orders_id=o.orders_id ';
																	$GrounpBy = ' group by o.orders_id ';
																}
																
																$Where.= ' AND ( (o.next_admin_id = "'.tep_db_prepare_input($_next_admin_id).'" and o.need_next_admin="1") ';
																$Where.= ' || (osh.next_admin_id = "'.tep_db_prepare_input($_next_admin_id).'" and osh.is_processing_done="0" ) ';
																$Where.= ' ) ';
															}else{
																$Where.= ' AND o.next_admin_id = "123456789" ';
															}
														}
														//按当前处理人的工号搜索}
														//销售链接工号搜索{
														if(tep_not_null($_GET['orders_owner_admin_id_job_number'])){
															if($_GET['orders_owner_admin_id_job_number']==19){
																$Where.=' AND o.is_other_owner=1 ';
															}else{
																$_orders_owner_admin_id = tep_get_admin_id_from_job_number($_GET['orders_owner_admin_id_job_number']);
																$Where.= ' AND o.orders_owner_admin_id="'.(int)$_orders_owner_admin_id.'" ';
															}
														}
														//销售链接工号搜索}
														
														//已付款订单，订单金额没有变化，订单内容有更新：参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改{
														if($_GET['order_up_no_change_status']=="1"){
															$Where.= ' AND o.order_up_no_change_status = "1" ';
														}
														//已付款订单，订单金额没有变化，订单内容有更新：参团日期、航班、乘车点、客人姓名、性别、参团人数、房间数、联系电话任意一项有更改}
														//销售不能看别人的未付款订单需要在此做限制{
														//此需求由吕姐口头讲述，Howard added by 20121031
														if($can_see_non_payment_orders != true && !tep_not_null($_GET['customers_name']) && !tep_not_null($_GET['customers_telephone'])&&!$_GET['owner_is_change'] ){
															$Where .= ' AND (o.orders_owner_admin_id="'.(int)$login_id.'" || o.orders_owner_admin_id="0" || o.next_admin_id="'.(int)$login_id.'" || FIND_IN_SET("'.tep_get_job_number_from_admin_id((int)$login_id).'",o.orders_owners) ';
															if(!tep_not_null($_GET['orders_products_payment_status'])){
																$Where .= ' || op.orders_products_payment_status = "1" ';
															}
															// 加上超时条件 by lwkai added start
															$Where .= ' || (o.orders_status = "100154" && o.is_timeout = 1) ';
															// 加上超时条件 by lwkai added end
															$Where .= ' )  ';

															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where  .= ' AND op.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}
														}
														//销售不能看别人的未付款订单需要在此做限制}
                                                        //未下单搜索 Howard added{ 
														if(tep_not_null($_GET['no_call_provider']) && $_GET['no_call_provider']=="1"){
																$Where .= ' AND o.orders_id NOT IN( SELECT distinct orders_id FROM `orders_status_history` WHERE orders_status_id in ("100127","100009","100072","100122")) '; //这几个状态是指已经给地接下过单了的
																if (isset($_GET['is_packet_group']) && intval($_GET['is_packet_group'])) { // 因为不只是这里no_call_provider==1 所以附加了一个参数来判断
																	$Where .= ' AND o.is_packet_group = 0 '; // 只显示非小包团的订单（个性化订制） by lwkai added 2013-05-15
																}
																$GrounpBy = ' group by o.orders_id ';
														
														}
														//未下单搜索}
														//已下单等确认{
														//最后一次下单的订单都到此列表，若地接已回复，OP已发电子参团凭证就离开此列表(只要发了信息给地接都算下单)
														if(tep_not_null($_GET['has_call_provider_wait_confirm']) && $_GET['has_call_provider_wait_confirm']=="1"){
															
															if(!preg_match('/ op /',$Tables)){
																$Tables .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																$Where .= ' AND op.orders_id=o.orders_id ';
															}
															if(!preg_match('/ opspnr /',$Tables)){
																$Tables .= ', orders_products_sent_provider_not_re opspnr ';
																$Where .= ' AND op.orders_products_id = opspnr.orders_products_id ';
															}															
															
															$GrounpBy = ' group by o.orders_id ';
														}
														//}
														//操作员认为有问题{
														if(tep_not_null($_GET['op_think_problems']) && $_GET['op_think_problems']=="1"){
															if(!preg_match('/ ooptp /',$Tables)){
																$Tables .= ', orders_op_think_problems ooptp ';
																$Where .= ' AND ooptp.orders_id=o.orders_id ';
																$GrounpBy = ' group by o.orders_id ';
															}														
														}
														// 新增的一个新状态 主管审核有问题，需要销售继续跟进 指定时间内未处理所有人能见到的处理
														/*$Tables .= ", orders_status_update osup "; // 订单修改状态记录的开始时间点
														$Tables .= ", orders_status_urgency osur"; // 订单修改状态的紧急度
														$Where .= " AND o.need_next_urgency = osur.name";*/
														//}
														if($_GET['in_orders_id']){ //指定了某些订单号
															$Where .= ' AND o.orders_id in('.$_GET['in_orders_id'].') ';
														}
														//再次付款需更新的订单
														if($_GET['is_again_paid']=="1"){
															$Where .= ' AND o.is_again_paid="1" ';
														}
														if ($can_only_see_visa == true) {
															$login_number = tep_get_admin_customer_name($login_id);
															$Where .= ' AND find_in_set("' . $login_number . '",o.orders_owners) ';
														}
														if($_GET['owner_is_change'])
														$Where.=' AND o.orders_owners="" AND o.is_other_owner<>1';
														//订单列表SQL语句
														//$OrderBy = ' o.is_top DESC, '.$OrderBy; //添加置顶排序
														$orders_query_raw = 'SELECT '.$Fields.' FROM '.$Tables.' WHERE '.$Where.$GrounpBy.' ORDER BY '.$OrderBy;
														//$orders_query_raw .=  ",find_in_set('119',o.orders_owners)";
                                                        //die($orders_query_raw);
														//by panda 增加Amount搜索选项 end  }
														// by lwkai added 循环遍历状态为 主管审核有问题，需销售继续跟进的订单 start {
														// 根据orders_status_update 记录的时间 与 orders_status_urgency中允许的时长做比较，如果超过时长，则把orders中的is_timeout更新为1
														$temp_sql = "update orders o,orders_status_urgency osur,orders_status_update osup set o.is_timeout =1 where osup.orders_id=o.orders_id and osup.urgency_name = o.need_next_urgency and o.need_next_urgency = osur.name and osur.orders_status_id=o.orders_status and timestampdiff(HOUR,osup.change_date,'" . date('Y-m-d H:i:s') . "') > osur.time";
														tep_db_query($temp_sql);
														
														// by lwkai added 循环遍历状态为 主管审核有问题，需销售继续跟进的订单 end}
                                                        //echo $orders_query_raw;                                                        
                                                        if(0){	//这句查询很慢的算总价的，暂时不开
															$orders_total_sum_sql = 'SELECT sum(value) as orders_total_sum from (SELECT ot.value FROM '.$Tables.' WHERE '.$Where.$GrounpBy.') tmp';                                                        
															//$orders_total_sum_sql = 'SELECT ot.value as orders_total_sum FROM '.$Tables.' WHERE '.$Where.$GrounpBy;
															//print_r($orders_total_sum_sql);
															$orders_total_sum_query = tep_db_query($orders_total_sum_sql);
															$orders_total_sum = 0.0000;
															while($rt = tep_db_fetch_array($orders_total_sum_query)){
																$orders_total_sum += (float)$rt['orders_total_sum'];
															}
	
															$orders_total_sum = number_format($orders_total_sum, 2, '.', ',');
														}
														
														if(in_array($login_id,array(19,246))){
															echo $orders_query_raw.'<hr>'; //exit;
														}
														
														$orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_query_raw, $orders_query_numrows);
                                                        $orders_query = tep_db_query($orders_query_raw);
														$page_order_total = 0.00;
														
                                                        while ($orders = tep_db_fetch_array($orders_query)) {
															
															$page_order_total += (float)$orders['order_total_num'];
                                                            if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {

                                                                $oInfo = new objectInfo($orders);
                                                            }

															//已阅读地接回复的确认按钮
															$op_has_see_provider_re_button = '';
															//OP备注添加到按钮提示
															$op_title = '已阅读地接回复的内容'."\n";
															
															$op_remarks_text = '';
															$op_remarks = get_orders_op_remark($orders['orders_id']);
															if($op_remarks){
																//$op_remarks_text.= 'OP备注'."\n";
																foreach($op_remarks as $op_remark){
																	$op_remarks_text.= '角色：'.$op_remark['role']."|工号：".$op_remark['admin_job_number']."|时间：".date('Y-m-d H:i',strtotime($op_remark['add_date']))."\n备注内容：".tep_db_output($op_remark['remark'])."\n\n";
																}
															}

															if($allow_message_to_provider === true && $orders['is_blinking']=="1"){
																$op_has_see_provider_re_button = '<button type="button" title="'.$op_title.'" onClick="op_has_see_provider_re('.(int)$orders['orders_id'].', this);">确认处理OK</button>';
															}
															
                                                            if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
																$onclick_str = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'" ';
																if($op_has_see_provider_re_button!=''){
																	$onclick_str = '';
																}
                                                                echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" '.$onclick_str.'>' . "\n";
                                                            } else {
																$onclick_str = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'" ';
                                                                if($op_has_see_provider_re_button!=''){
																	$onclick_str = '';
																}
																echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" '.$onclick_str.'>' . "\n";
                                                            }
                                                ?>
            

											<td	class="dataTableContent" nowrap="nowrap">            
                                                <?php
															if($orders['is_top']){
																echo "<img src='images/icons/warning.gif' align='absmiddle'>";
															}
															if($orders['owner_is_change']==1){
																echo '<font color="#FF9966">重新分配</font>';
															}
                                                            if ($orders['is_agret'] == '1') {
                                                                echo "<img src='images/call{$orders['is_ret']}.gif' align='absmiddle'>";
                                                            }
															
															if($orders['is_blinking'] == '1'){				
																echo '<b><span class="blink">'.$orders['orders_id'].'</span></b>';					
															}else{
																echo '<b>'.$orders['orders_id'].'</b>';
															}
															
															echo $op_has_see_provider_re_button;
															
															if(tep_not_null($op_remarks_text)){
																echo '<span title="'.$op_remarks_text.'">[OP备注]</span>';
															}
                                                ?>                                                              
                                                <?php
															//Phone booking
															if($orders['is_phonebooking'] == '1'){
																echo '<b style="color:#FF9900">Phone Booking</b>';
															}
                                                            //结伴同游
                                                            if (is_travel_comp((int) $orders['orders_id']) > 0) {
                                                                echo '<br><b style="color:#FF9900">结伴同游</b> ';
                                                            }
                                                            //结伴同游 end
                                                            //团购标记
                                                            if (have_group_buy((int) $orders['orders_id']) > 0) {
                                                                echo '<br><b style="color:#006699">团体预定</b> ';
                                                            }
                                                            //团购标记 end
                                                            
															//新团购{
                                                            if (have_new_group_buy((int) $orders['orders_id']) > 0) {
																echo '<br><b style="color:#006699">新团购 ';
																$sql = tep_db_query("select new_group_buy_type from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".(int)$orders['orders_id']."' and new_group_buy_type>0");
																while($row = tep_db_fetch_array($sql)){
																	if($row['new_group_buy_type']=="1"){
																		echo '限量团 ';
																	}
																	if($row['new_group_buy_type']=="2"){
																		echo '限时团 ';
																	}
																}
																
																if (is_no_sel_date_for_group_buy((int) $orders['orders_id']) > 0) {
																	echo '未定出发日期 ';
																}
																echo '</b>';
															}
															
															//新团购}
															
															//Group Deal Discount(特别团购)
															$check_any_product_featured = tep_db_query("select products_room_info from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".(int)$orders['orders_id']."' and is_diy_tours_book='2' and group_buy_discount > 0");
															if(tep_db_num_rows($check_any_product_featured)>0){
															echo '<b style="color:#006699">'.TXT_GROUP_DEAL_DISCOUNT.'</b>';
															}
															
															//Group Deal Discount end
															
															if(!tep_not_null($orders['customers_name'])){
																$customers_info = tep_get_customers_info($orders['customers_id']);
																$orders['customers_name'] = $customers_info['customers_firstname'];
															}
															
					
                                                ?>
                                                            </td>

											<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?>
            
                                                            <?php
                                                            $is_double_book_bool_array_check = is_double_booked((int) $orders['orders_id']);
                                                            if ($is_double_book_bool_array_check[0] == true) {
                                                                echo $is_double_book_bool_array_check[1];
                                                            }
                                                ?>
                                                            </td>
											<td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
														<?php
															//$_order_owner_admin = tep_get_order_owner_admin($orders['orders_id']);
															//$orders_owner_jobs_id=tep_get_order_owner_jobs_id($orders['orders_id']);
															if ($orders['is_other_owner'] == 1){
																$orders_owner_jobs_id='19';
															}else{
																$orders_owner_jobs_id='';
															}
															if($orders['orders_owner_commission']==0.5){
																$my_tmp=explode(',', $orders['orders_owners']);
																$orders_owner_jobs_id1=$my_tmp[0];
															}else{
																$orders_owner_jobs_id1=$orders['orders_owners'];
															}
															$my_tmp=explode(',', $orders['orders_owners']);
																$orders_owner_jobs_id1=$my_tmp[0];
															//tep_get_order_owner_jobs_id($orders['orders_id']);//$orders['is_other_owner']==1?'19':$_order_owner_admin; 
															?> 
															<?php 
															/*
															switch ($orders['orders_owner_commission']) {
																case 1: 
																	switch ($orders['is_other_owner']){
																		case 2 : $show_tilte= '销售跟踪的订单'; break;
																		case 1 : $show_tilte= '系统自动掉单';break;
																		case 3 : $show_tilte= '客服帮客户下单';break;
																		default: $show_tilte= '客户自己带连接下单'; break;
																	}
																	break;
																case 0.5: $show_tilte= '销售跟踪'; break;
																default: $show_tilte= ''; break;
																}*/
																switch ($orders['is_other_owner']){
																		case 2 : $show_tilte= '销售跟踪的订单'; break;
																		case 1 : $show_tilte= '系统自动掉单';break;
																		case 3 : $show_tilte= '客服帮客户下单';break;
																		default: $show_tilte= '客户自己带连接下单'; break;
																		}
																?> 
																<?php
															$show_tilte.= '   (工号):'.(($orders_owner_jobs_id==19)?'19':$orders_owner_jobs_id1);
															?>
											<td class="dataTableContent" nowrap
												title="<?php echo $show_tilte?>">
																
															<?php
															if($orders_owner_jobs_id1 !=''||$orders['is_other_owner']==1)
															{ 
															?>
																	
																	<span
												style="color: #666666; font-size: 10px; border: 1px dotted #999999;"><?php  if($orders_owner_jobs_id=='19'){echo '<font color="red">19</font>';} else {echo $orders_owner_jobs_id1;}?></span><br />
															<?php 
															} 
															?>
																	<?php echo $orders['orders_owners'];//tep_get_order_owner_admin($orders['orders_id']);?></td>

											<td class="dataTableContent"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>

											<td class="dataTableContent"><b><?php echo tep_get_date_of_departure($orders['orders_id']); ?></b>
											</td>

											<td class="dataTableContent" align="center"><span><?php echo get_datedifference($orders['last_modified']) . ' ' . TXT_AGO; ?></span>
											</td>

											<td class="dataTableContent" align="center"><span>
															<?php	
															$last_admin = tep_get_admin_customer_name($orders['admin_id_orders']);
															if($last_admin == ""){
																if(!tep_not_null($orders['last_status_modified_by'])){
																	$lsm_sql = tep_db_query("select osh.updated_by from ". TABLE_ORDERS_STATUS_HISTORY . " osh WHERE osh.orders_id=".(int)$orders['orders_id']." Order By osh.orders_status_history_id DESC Limit 1");
																	$lsm_row = tep_db_fetch_array($lsm_sql);
																	$last_admin = tep_get_admin_customer_name($lsm_row['updated_by']);
																}
															}
															$orders['last_status_modified_by'] = $last_admin;
															echo  tep_db_output($orders['last_status_modified_by']);														
															?>
															</span></td>

											<td class="dataTableContent" nowrap>
															 <?= tep_get_tours_codes_from($orders['orders_id']); ?>&nbsp;
															</td>

											<td class="dataTableContent"><?php echo tep_get_provider_tours_codes_from($orders['orders_id']); ?></td>
											<!--<td class="dataTableContent" ><?php echo tep_get_order_followup($orders['orders_id'] , true); ?></td>-->
											<td class="dataTableContent"><?php echo implode('<br />',tep_get_provider_last_re_time($orders['orders_id'], 'm/d/Y D')); ?></td>
											<td class="dataTableContent" nowrap><?php echo tep_get_orders_payment_status_name($orders['orders_id']);?></td>
											<td class="dataTableContent" nowrap>
															<?php if ($orders['need_next_admin']=="1"){ echo tep_get_admin_customer_name($orders['next_admin_id']).'<br />'.tep_get_need_next_urgency_name($orders['need_next_urgency']); }?>
															</td>
											<td class="dataTableContent" align="right">
															<?php
															if(!tep_not_null($orders['orders_status_name'])){
																$orders['orders_status_name'] = tep_get_orders_status_name($orders['orders_status'], (int)$languages_id);
															}
															echo $orders['orders_status_name'];
															?>
															</td>
											<?php
											// 只有主管以上才可以有这个功能，副主管及以下都不能用 by lwkai added 2013-05-15
											if ($can_packet_group == true) { ?>
											<td class="dataTableContent" align="right"><?php 
												if ($orders['is_packet_group'] == 0) {
													echo '<a class="a-btn" href="' . tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('action')) . 'action=set_packet_group&orders_id=' . $orders['orders_id']) . '">设置包团</a>';
												} else {
													echo '<a class="a-btn-active" href="' . tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('action')) . 'action=unset_packet_group&orders_id=' . $orders['orders_id']) . '">取消包团</a>';
												}
											?></td>
											<?php } ?>
											<td class="dataTableContent" align="right">
															<?php 
															echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . '<img src="images/icons/edit.gif" border="0"/></a> ';
															
															if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
                                                                echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                                                            } else {
                                                                echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                                                            } ?>&nbsp;
															<?php
																/*
																echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $orders['orders_id']) . '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>'*/
																?>
                                                            </td>
											</tr>            
            
                                                    <?php
                                                        }
                                                    ?>
    
                                                    <tr>

												<td colspan="16">
													<table border="0" width="100%" cellspacing="0"
														cellpadding="2">
														<tr>
															<td class="smallText" valign="top"><?php if($orders_total_sum>1){?>符合条件的定单总计：<b>$<?php echo $orders_total_sum;?></b><?php }?>
																；当前页总计：<b>$<?php echo number_format($page_order_total, 2, '.', ',');?></b>
																。
															</td>
														</tr>

													</table>


													<table border="0" width="100%" cellspacing="0"
														cellpadding="2">

														<tr>

															<td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>

															<td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
														</tr>

													</table>

												</td>
											</tr>

										</table>
									</td>    
    
<?php
                                                        $heading = array();

                                                        $contents = array();



                                                        switch ($action) {

                                                            case 'delete':

                                                                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');



                                                                $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));

                                                                $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>');

                                                                $contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');

                                                                $contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br>');

                                                                $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);

                                                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

                                                                break;


                                                            default:	//默认展开订单状态列表 start

                                                                if (isset($oInfo) && is_object($oInfo)) {

                                                                    $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');



                                                                    if (tep_not_null($oInfo->last_modified))
                                                                        $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
                                                                    
																	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>');

                                                                    $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' ' . $oInfo->payment_method);

//begin PayPal_Shopping_Cart_IPN V2.8 DMG

                                                                    if (strtolower($oInfo->payment_method) == 'paypal') {

                                                                        include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php');

                                                                        $contents[] = array('text' => TABLE_HEADING_PAYMENT_STATUS . ': ' . paypal_payment_status($oInfo->orders_id));
                                                                    }

//end PayPal_shopping_Cart_IPN

                                                                    //show right box status start
																	
																	$show_all_status = true;
																	
																	if($_GET['show_right_box_orders_status']=="1"){
																		$show_all_status = true;
																		$_SESSION['show_right_box_orders_status'] = $_GET['show_right_box_orders_status'];
																	}elseif($_GET['show_right_box_orders_status']=="0"){
																		$show_all_status = false;
																		$_SESSION['show_right_box_orders_status'] = $_GET['show_right_box_orders_status'];
																	}else{
																		if($_SESSION['show_right_box_orders_status']=="1"){
																			$show_all_status = true;
																		}
																		if($_SESSION['show_right_box_orders_status']=="0"){
																			$show_all_status = false;
																		}
																	}
																	
																	if($show_all_status == true){	//订单状态列表项 start
																		
																		if (isset($_GET['providers']) && tep_not_null($_GET['providers'])) {
																			$start_date_where.= " AND op.products_id IN (SELECT p.products_id FROM " . TABLE_PRODUCTS . " p WHERE p.agency_id = '" . tep_db_input($_GET['providers']) . "') ";
																			$search_extra_get_query_string = "&providers=" . $_GET['providers'];
																		}
																		$orders_contents = '';
																		$orders_contents_array = array();
																		$orders_status_query = tep_db_query("select osg.os_groups_id, osg.os_groups_name, osg.sort_id as os_sort_id, os.orders_status_name, os.orders_status_id, os.sort_id from " . TABLE_ORDERS_STATUS . " os, `orders_status_groups` osg where osg.os_groups_id = os.os_groups_id AND language_id = '" . $languages_id . "'  AND orders_status_display='1' AND orders_status_name !='' ORDER BY osg.sort_id ASC, os.sort_id ASC, orders_status_name ASC ");
																		
																		$_time_3 = time()+(3*86400);
																		$_time_7 = time()+(7*86400);
																		while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	
																			if($orders_status['orders_status_id']==100088 || $orders_status['orders_status_id']==100089 ){//出发前三天将此状态展示出来
																				$sohw_this_status = false;
																				$tmp_orders_sql = tep_db_query('SELECT count(*) as total FROM `orders` o, orders_products op WHERE o.orders_status="'.$orders_status['orders_status_id'].'" and  o.orders_id=op.orders_id and op.products_departure_date <="'.date("Y-m-d H:i:s",$_time_3).'" Limit 1');
																				$tmp_orders_row = tep_db_fetch_array($tmp_orders_sql);
																				if((int)$tmp_orders_row['total']){
																					$sohw_this_status = true;
																				}
																				if($sohw_this_status!=true){
																					continue;
																				}
																			}
																			if($orders_status['orders_status_id']==100036){//出发前7天将此状态展示出来
																				$sohw_this_status = false;
																				$tmp_orders_sql = tep_db_query('SELECT count(*) as total FROM `orders` o, orders_products op WHERE o.orders_status="'.$orders_status['orders_status_id'].'" and  o.orders_id=op.orders_id and op.products_departure_date <="'.date("Y-m-d H:i:s",$_time_7).'" Limit 1');
																				$tmp_orders_row = tep_db_fetch_array($tmp_orders_sql);
																				if((int)$tmp_orders_row['total']){
																					$sohw_this_status = true;
																				}
																				if($sohw_this_status!=true){
																					continue;
																				}
																			}
																			
																			if (isset($_GET['start_date']) && (tep_not_null($_GET['start_date']))) {
	
																				$start_date = tep_db_prepare_input($_GET['start_date']);
	
																				switch ($_GET['start_date']) {
																					case '3':
																						//$start_date_where = " and op.products_departure_date > '".date('Y-m-d')."'";
																						$start_date_where = " and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=3";
																						break;
																					case '7':
																						//$start_date_where = " and op.products_departure_date > '".date('Y-m-d')."'";
																						$start_date_where = " and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=7";
																						break;
																					case '14':
																						//$start_date_where = " and op.products_departure_date > '".date('Y-m-d')."'";
																						$start_date_where = " and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=14";
																						break;
																					case '31':
																						//$start_date_where = " and op.products_departure_date > '".date('Y-m-d')."'";
																						$start_date_where = " and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')>0 and DATEDIFF(op.products_departure_date,'" . date('Y-m-d') . "')<=31";
																						break;
																					case 'greater':
																						$start_date_where = " and op.products_departure_date > '" . date('Y-m-d') . "'";
																						break;
																					default:
																						$start_date_where = " ";
																						break;
																				}
																			} else if ((isset($_GET['dept_start_date']) && tep_not_null($_GET['dept_start_date'])) && (isset($_GET['dept_end_date']) && tep_not_null($_GET['dept_end_date']))) {
																				$start_date_where = ''; //vincent add for fixbug
																				//$make_start_date = $_GET['dept_start_date'] ;
																				$make_start_date = tep_get_date_db($_GET['dept_start_date']);
																				//$make_end_date = $_GET['dept_end_date'] ;
																				$make_end_date = tep_get_date_db($_GET['dept_end_date']);
																				$start_date_where .= " and  op.products_departure_date >= '" . $make_start_date . "' and op.products_departure_date <= '" . $make_end_date . "' ";
	
																				$search_extra_get .= "&dept_start_date=" . $_GET['dept_start_date'] . "&dept_end_date=" . $_GET['dept_end_date'];
																			} else if ((isset($_GET['dept_start_date']) && tep_not_null($_GET['dept_start_date'])) && (!isset($_GET['dept_end_date']) or !tep_not_null($_GET['dept_end_date']))) {
	
																				$make_start_date = tep_get_date_db($_GET['dept_start_date']);
	
																				$start_date_where .= " and  op.products_departure_date >= '" . $make_start_date . "' ";
	
																				$search_extra_get .= "&dept_start_date=" . $_GET['dept_start_date'];
																			} else if ((!isset($_GET['dept_start_date']) or !tep_not_null($_GET['dept_start_date'])) && (isset($_GET['dept_end_date']) && tep_not_null($_GET['dept_end_date']))) {
	
																				$make_end_date = tep_get_date_db($_GET['dept_end_date']);
	
																				$start_date_where .= " and  op.products_departure_date <= '" . $make_end_date . "' ";
																				$search_extra_get .= "&dept_end_date=" . $_GET['dept_end_date'];
																			}
																			
																			$extra_table = "";
																			$group_by = "";
																			if(tep_not_null($start_date_where) && (int)strpos( trim(strtolower($start_date_where)),'op.') ){
																				$extra_table .= ', '.TABLE_ORDERS_PRODUCTS.' op ';
																				$start_date_where .= " and op.orders_id=o.orders_id ";
																				$group_by .= " group by o.orders_id ";
																			}

																		//by vincent 订单避免重复更新管理优化 begin  ---------------------------------------------------------
																		//followup_team_type设置跟踪该订单的团队ID 0 Notset 1 US follow up 2 China follow up 3 China Night Shift Follow Up
																		//us follow up
																		//检查状态和followup不匹配的情况
																		/*$orders_contents_array_tag = array(
																			'1'=>array('groups_name'=>'US Follow Up' ,'color'=>'blue', 'data'=>array() , 'id'=>'flt1'),
																			'2'=>array('groups_name'=>'China Day Shift Follow Up' ,'color'=>'red', 'data'=>array() , 'id'=>'flt2'),
																			'3'=>array('groups_name'=>'China Night Shift Follow Up' ,'color'=>'#900', 'data'=>array() , 'id'=>'flt3'),
																		);*/
																		/*//显示到US Follow Up标签下的
																		$sql=tep_db_query("SELECT count(o.orders_id) as count ,os.orders_status_name AS status_name ,o.orders_status  FROM " . TABLE_ORDERS . " o ".$extra_table.",".TABLE_ORDERS_STATUS." AS os  WHERE o.orders_status = os.orders_status_id AND o.orders_status IN (".implode(',',$followup_array_cn).") AND o.followup_team_type = 1   AND o.orders_status NOT IN (".implode(',',$followup_exclude_arr).") ". $start_date_where.' GROUP BY o.orders_status'); 																
																		while($row =tep_db_fetch_array($sql)) {
																			$row['href_links'] = tep_href_link(FILENAME_ORDERS, 'from_follow_box=1&status=' . $row['orders_status'].'&followup_team_type=1'. $search_extra_get_query_string);
																			$orders_contents_array_tag['1']['data'][] = $row;
																		}
																		//显示到China Day Shift Follow Up标签下的
																		$sql=tep_db_query("SELECT count(o.orders_id) as count ,os.orders_status_name AS status_name ,o.orders_status  FROM " . TABLE_ORDERS . " o ".$extra_table.",".TABLE_ORDERS_STATUS." AS os  WHERE o.orders_status = os.orders_status_id AND o.orders_status IN (".implode(',',$followup_array_us).") AND o.followup_team_type = 2  AND o.orders_status NOT IN (".implode(',',$followup_exclude_arr).") ". $start_date_where.' GROUP BY o.orders_status'); //显示到China Follow Up标签下的																		
																		while($row =tep_db_fetch_array($sql)) {
																			$row['href_links'] = tep_href_link(FILENAME_ORDERS, 'from_follow_box=1&status=' . $row['orders_status'].'&followup_team_type=2'. $search_extra_get_query_string);
																			$orders_contents_array_tag['2']['data'][] = $row;
																		}
																		//显示到China Night Shift Follow Up标签下的
																		$sql=tep_db_query("SELECT count(o.orders_id) as count ,os.orders_status_name AS status_name ,o.orders_status  FROM " . TABLE_ORDERS . " o ".$extra_table.",".TABLE_ORDERS_STATUS." AS os  WHERE o.orders_status = os.orders_status_id AND o.followup_team_type = 3 ". $start_date_where.' AND o.orders_status NOT IN ('.implode(',',$followup_exclude_arr).')  GROUP BY o.orders_status'); //显示到China Follow Up标签下的																		
																		while($row =tep_db_fetch_array($sql)) {
																			$row['href_links'] = tep_href_link(FILENAME_ORDERS, 'from_follow_box=1&status=' . $row['orders_status'].'&followup_team_type=3'. $search_extra_get_query_string);
																			$orders_contents_array_tag['3']['data'][] = $row;
																		}*/
																		///读取不需要在订单状态统计中显示的订单编号
																		$exclude_id = array();
																		$sql = tep_db_query("SELECT o.orders_id FROM " . TABLE_ORDERS . " o ".$extra_table." WHERE o.orders_status IN (".implode(',',$followup_array_cn).") AND followup_team_type=1 ". $start_date_where); //US 
																		while($row = tep_db_fetch_array($sql))$exclude_id_array[] = $row['orders_id'] ;
																		$sql = tep_db_query("SELECT o.orders_id FROM " . TABLE_ORDERS . " o ".$extra_table." WHERE  o.orders_status IN (".implode(',',$followup_array_us).") AND followup_team_type=2 ". $start_date_where); //China 
																		while($row = tep_db_fetch_array($sql))$exclude_id_array[] =  $row['orders_id'] ;
																		if(count($exclude_id_array) > 0)
																			$execlude_id_str = ' AND  o.orders_id NOT IN('.implode(',',$exclude_id_array).') '; 
																		else 
																			$execlude_id_str = ' ';
																		////////////----------------------------------------------------------------------------------
	
																			
																			$orders_pending_query_str = "select count(o.orders_id) as total from " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_TOTAL . " as ot ".$extra_table." where ot.orders_id = o.orders_id and ot.class = 'ot_total' and o.orders_status = '" . $orders_status['orders_status_id'] . "'  AND o.followup_team_type <> 3 " . $start_date_where . $execlude_id_str.$group_by;
																			//echo $orders_pending_query_str."<br>";
																			$orders_pending_query = tep_db_query($orders_pending_query_str);
																			$orders_pending = tep_db_fetch_array($orders_pending_query);
																			//$count = tep_db_num_rows($orders_pending_query);
																			$count = $orders_pending['total'];
																			
																			if((int)$count){
																				//by vincent 订单避免重复更新管理优化 begin 
																				//设置订单的跟踪团队
																				if(in_array($orders_status['orders_status_id'],$followup_array_us)){
																					$color = 'blue';
																				}else if(in_array($orders_status['orders_status_id'],$followup_array_cn)){
																					$color = 'red';
																				}else{
																					$color="";
																				}
																				//订单避免重复更新管理优化 end 
																				$orders_contents_array[$orders_status['os_groups_id']][] = array(
																					  'status_id'=>$orders_status['orders_status_id']
																					,'status_name'=> $orders_status['orders_status_name']
																					, 'href_links'=> tep_href_link(FILENAME_ORDERS, 'status=' . $orders_status['orders_status_id'] . $search_extra_get_query_string)
																					, 'groups_name'=>$orders_status['os_groups_name']
																					, 'count'=> $count
																					, 'sort'=> $orders_status['sort_id'] 
																					,'gsort'=>$orders_status['os_sort_id']
																					,'color'=>$color
																					);
																			
																				/*//$orders_pending = tep_db_fetch_array($orders_pending_query);
																				if($tep_admin_check_boxes_true!=true){
																					$tep_admin_check_boxes_true = tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes');
																				}
																				if ($tep_admin_check_boxes_true == true) {
																					$orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'status=' . $orders_status['orders_status_id'] . $search_extra_get_query_string) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $count . ': '. $orders_status['os_groups_name'].'<br>'; // . $search_extra_get
																				} else {
																					$orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $count . '<br>';
																				}*/
																			}
	//Admin end
																		}
																		
																		
																		if($tep_admin_check_boxes_true!=true){
																			$tep_admin_check_boxes_true = tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes');
																		}
																		foreach((array)$orders_contents_array as $key => $val){
																			$group_title = '<div style="cursor:pointer;padding-left:5px; margin-top:5px;" onClick="jQuery(\'.FalseClass'.$key.'\').toggle(20)"><b>'.$val[0]['groups_name'].'</b>&nbsp;&nbsp;<span style="color:#666">%d</span> </div>';
																			$status_html = "";
																			$counts = 0;
																			for($i=0; $i<count($val); $i++){
																				if ($tep_admin_check_boxes_true == true){
																					$status_html .= '<div class="FalseClass'.$key.'" style="display:none; padding-left:10px;"><a href="'.$val[$i]['href_links'].'" style="color:'.$val[$i]['color'].'">'.$val[$i]['status_name'].'</a>:'.$val[$i]['count']."</div>";
																				}else{
																					$status_html .= '<div class="FalseClass'.$key.'" style="display:none; padding-left:10px;color:'.$val[$i]['color'].'">'.$val[$i]['status_name'].':'.$val[$i]['count']."</div>";
																				}
																				$counts += $val[$i]['count'];
																			}
																			$orders_contents .= sprintf($group_title,$counts).$status_html;
																		}
																		
																		//输出follow up 按钮
																		foreach((array)$orders_contents_array_tag as $key => $value){
																			$group_title = '<div style="cursor:pointer;padding-left:5px; margin-top:5px;" onClick="jQuery(\'.FalseClass'.$value['id'].'\').toggle(20)"><b>'.$value['groups_name'].'</b>&nbsp;&nbsp;<span style="color:#666">%d</span> </div>';
																			$status_html = "";
																			$counts = 0;
																			for($i=0; $i<count($value['data']); $i++){
																				$val = $value['data'];
																				if ($tep_admin_check_boxes_true == true){
																					$status_html .= '<div class="FalseClass'.$value['id'].'" style="display:none; padding-left:10px;"><a href="'.$val[$i]['href_links'].'" style="color:'.$value['color'].'">'.$val[$i]['status_name'].'</a>:'.$val[$i]['count']."</div>";
																				}else{
																					$status_html .= '<div class="FalseClass'.$value['id'].'" style="display:none; padding-left:10px;color:'.$value['color'].'">'.$val[$i]['status_name'].':'.$val[$i]['count']."</div>";
																				}
																				$counts += $val[$i]['count'];
																			}
																			$orders_contents .= sprintf($group_title,$counts).$status_html;
																		}
																		//订单避免重复更新管理优化 end
																		//print_r($orders_contents_array);
																		//$orders_contents = substr($orders_contents, 0, -4);
																		$orders_contents .= '<br><br><a href="'.tep_href_link('orders.php','show_right_box_orders_status=0&'.tep_get_all_get_params(array('show_right_box_orders_status'))).'">hidden all orders status</a>';

																	//订单状态列表项 end
																	}else{ //show right box status end
																		$orders_contents = '<a href="'.tep_href_link('orders.php','show_right_box_orders_status=1&'.tep_get_all_get_params(array('show_right_box_orders_status'))).'">show all orders status</a>';
																	}
																	

                                                                    $contents[] = array('text' => '<hr color="#333333" /><br><b>Orders Status:</b><br>' . $orders_contents);
                                                                }

                                                                break;	//默认展开订单状态列表 end
                                                        }



                                                        if ((tep_not_null($heading)) && (tep_not_null($contents))) {

                                                            echo '<td  valign="top" style="background:#DBDBDB"><img src="images/leftcontentnone.jpg"  style="cursor:pointer" onclick="if(this.src.indexOf(\'leftcontentshow.jpg\')!=-1){jQuery(\'#rbox\').show();this.src=\'images/leftcontentnone.jpg\';}else{jQuery(\'#rbox\').hide();this.src=\'images/leftcontentshow.jpg\';}"/></td><td valign="top"><div id="rbox" style="display:block;background:#fff" />' . "\n";
                                                            $box = new box;
                                                            echo $box->infoBox($heading, $contents);
                                                            echo '</div></td>' . "\n";
                                                        }
?>
                                                    </tr>


							</table>
						</td>
					</tr>


				</table> <!-- body_eof //--> <!-- footer //-->                
                
                                        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
                
                                    <!-- footer_eof //--> <br />

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



<script>

                        function LTrim(str){if(str==null){return null;}for(var i=0;str.charAt(i)==" ";i++);return str.substring(i,str.length);}

                        function RTrim(str){if(str==null){return null;}for(var i=str.length-1;str.charAt(i)==" ";i--);return str.substring(0,i+1);}

                        function Trim(str){return LTrim(RTrim(str));}

                        function validation_guest()

                        {

                            var i = 0 ;

                            for ( i= 0 ; i < window.document.etickets.elements.length ; i ++)

                            {

                                if(window.document.etickets.elements[i].name.substr(0,5) == "guest")

                                {

                                    var ch = Trim(window.document.etickets.elements[i].value);

                                    if(ch == "")

                                    {

                                        alert("Please Enter guest name")

                                        window.document.etickets.elements[i].focus();

                                        return false;

                                    }

                                }

                            }

                            return true;
                        }

                        function append_settle_payment_method(addvalue,addpayment){

                            var totalorders_payment_method=document.settele_order.orders_payment_method.value;
                            var totalorders_reference_comments=document.settele_order.reference_comments.value;
                            /*
                                    for(var i=0; i < document.settele_order.append_pmethod.length; i++)
                            {
                                            if(document.settele_order.append_pmethod[i].checked){			
                                            totalorders_payment_method += " + "+document.settele_order.append_pmethod[i].value;
                                            totalorders_reference_comments += "\n"+document.settele_order.append_pmethod[i].value + ": ";
			
                                            }
                            }
                             */
                            totalorders_payment_method += " + "+addpayment;
                            totalorders_reference_comments += "\n"+addvalue+ ": ";		
                            document.settele_order.orders_payment_method.value = totalorders_payment_method;
                            document.settele_order.reference_comments.value = totalorders_reference_comments;
                            document.settele_order.reference_comments.focus();

                        }
                        function show_travel_companion_tips(int_num, tips_id_mun){	var jiesong_info_tips = document.getElementById('travel_companion_tips_'+tips_id_mun);	if(jiesong_info_tips!=null){		if(int_num>0){			jiesong_info_tips.style.display="";		}else{			jiesong_info_tips.style.display="none";		}	}}
						</script>
<script type="text/javascript">
/*销售主管级别以上的已经阅读地接回复的内容*/
function op_has_see_provider_re(orders_id, thisBut){
	if(orders_id>0){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('orders.php','action=op_has_see_provider_re')) ?>");
		jQuery.post(url,{'action':"op_has_see_provider_re", 'ajax':"true", 'orders_id':orders_id },function(text){
				text = parseInt(text);
				if(text>0){
					jQuery(thisBut).attr('disabled', true);
					alert('操作成功！');
					//document.location.reload(document.location.href);
				}
			},'text');
	}
}
</script>