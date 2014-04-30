<?php
/*
 * $Id: stats_products_purchased.php,v 1.1.1.1 2004/03/04 23:38:59 ccwjr Exp $
 * osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
 * Copyright (c) 2003 osCommerce Released under the GNU General Public License
 */

require ('includes/application_top.php');
require (DIR_WS_CLASSES . 'split_page_results_outer.php');

// 备注添加删除
if ($_GET['ajax'] == "true") {
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_products_purchased');
	$remark->checkAction($_GET['action'], $login_id); //添加删除动作，统一在方法里面处理了
}
$where = " and (o.orders_status <> 6 and o.orders_status <> 100060 and o.orders_status <> 100055)  ";
if (!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['date_min']) && !isset($HTTP_GET_VARS['date_min'])) {
	//$date_min = $_GET['date_min'] = $HTTP_GET_VARS['date_min'] = date('Y-m-01');
}
if (isset($HTTP_GET_VARS['active']) == "true" && isset($HTTP_GET_VARS['active'])) {
	if (isset($HTTP_GET_VARS['date_min']) && $HTTP_GET_VARS['date_min'] != '') {
		$where .= " and o.date_purchased >='" . $HTTP_GET_VARS['date_min'] . " 00:00:00' ";
	}
	if (isset($HTTP_GET_VARS['date_max']) && $HTTP_GET_VARS['date_max'] != '') {
		$where .= " and o.date_purchased <='" . $HTTP_GET_VARS['date_max'] . " 23:59:59' ";
	}
	if (isset($HTTP_GET_VARS['keyword']) && $HTTP_GET_VARS['keyword'] != '') {
		$where .= " and (op.products_model like '%" . $HTTP_GET_VARS['keyword'] . "%' or op.products_id like '%" . $HTTP_GET_VARS['keyword'] . "%' or op.products_name like '%" . $HTTP_GET_VARS['keyword'] . "%') ";
	}
	if (isset($HTTP_GET_VARS['provider']) && $HTTP_GET_VARS['provider'] != '') {
		$where .= " and p.agency_id='" . $HTTP_GET_VARS['provider'] . "'";
	}
	
	$product_query_raw_pop = "select o.orders_id,o.date_purchased,op.products_id,op.orders_id, final_price, op.final_price_cost FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, " . TABLE_TRAVEL_AGENCY . " as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id " . $where . " and op.products_id='" . (int) $HTTP_GET_VARS['product_id'] . "'";
	$products_query_raw_exe = tep_db_query($product_query_raw_pop);
	if (tep_db_num_rows($products_query_raw_exe) > 0) {
		?>
<table cellpadding="2" style="border: 1px solid #006666"
	bgcolor="#DDEEEB" cellspacing="2" class="dataTableContent"
	align="center">
	<tr style="background-color: #006666; color: #FFFFFF;">
		<td align="right" nowrap="nowrap"><strong>#Order ID</strong></td>
		<td><strong>Date of Purchase</strong></td>
		<td align="right"><strong>Revenue</strong></td>
				<?php
		if ($access_full_edit == 'true' || ($allow_cost_gp_on_other_reports == true && $login_groups_id != '5')) {
			?>
				<td align="right"><strong>Gross Profit</strong></td>
		<td align="right" nowrap="nowrap"><strong>Gross Profit<br>(%)
		</strong></td>
				<?php } ?>
				<td align="right"><?php echo tep_image(DIR_WS_IMAGES.'close_window.jpg','close',15,13,' onclick="javascript:close_div(\'show_info_popup_'.$HTTP_GET_VARS['product_id'].'\',\'none\');"'); ?></td>
	</tr>
			<?php
		$sum_price = '';
		$running_final_price = 0;
		$running_final_price_cost = 0;
		$running_gross_profit = 0;
		$running_margin = 0;
		$items_sold_cnt = 0;
		while ($row_products = tep_db_fetch_array($products_query_raw_exe)) {
			$running_final_price += $row_products['final_price'];
			$running_final_price_cost += $row_products['final_price_cost'];
			$gross_profit = number_format($row_products['final_price'] - $row_products['final_price_cost'], 2);
			$running_gross_profit += $gross_profit;
			if ($row_products['final_price'] != 0) {
				$margin = tep_round((((($row_products['final_price']) - ($row_products['final_price_cost'])) / ($row_products['final_price'])) * 100), 0);
			} else {
				$margin = 0;
			}
			$running_margin += $margin;
			?>
			<tr>
		<td align="right"><?php echo '<a class="col_a1" href='.tep_href_link(FILENAME_EDIT_ORDERS,'oID='.$row_products['orders_id']).' title="Edit" target="_blank"><b>'.$row_products['orders_id'].'</b></a>'; ?></td>
		<td align="right"><?php echo tep_date_short($row_products['date_purchased']); ?></td>
		<td align="right">$<?= number_format( $row_products['final_price'], 2 ); ?></td>
				<?php
			if ($access_full_edit == 'true' || ($allow_cost_gp_on_other_reports == true && $login_groups_id != '5')) {
				?>
				<td align="right"><?php if($gross_profit<=0){ echo '<span style="background-color:#FFFFCC;">'; } ?>$<?= $gross_profit;?></span></td>
		<td align="right"><?php echo $margin . '%';?></td>				
				<?php } ?>
				<td>&nbsp;</td>
	</tr>
			<?php
			$items_sold_cnt ++;
		}
		if ($items_sold_cnt > 0) {
			?>
			<tr style="background-color: #006666; color: #FFFFFF;">
		<td colspan="2" align="right"><strong>Total:</strong></td>
		<td align="right"><strong>$<?= number_format( $running_final_price, 2 ) ?></strong></td>
				<?php
			if ($access_full_edit == 'true' || ($allow_cost_gp_on_other_reports == true && $login_groups_id != '5')) {
				?>
				<td align="right"><strong>$<?= number_format( $running_gross_profit, 2 ) ?></strong></td>
		<td align="right"><strong><?= tep_round(($running_margin/$items_sold_cnt) , 0). '%'; ?></strong></td>
				<?php } ?>
				<td><?php echo tep_image(DIR_WS_IMAGES.'close_window.jpg','close',15,13,' onclick="javascript:close_div(\'show_info_popup_'.$HTTP_GET_VARS['product_id'].'\',\'none\');"'); ?></td>
	</tr>
			<?php } ?>
            </table>
<?php
	}
	echo $HTTP_FILES_VARS['product_id'];
	exit();
}

//默认列表数据
if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1))
	$rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS_ADMIN - MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
// $products_query_raw = "select p.products_id, p.products_ordered, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id. "' and p.products_ordered > 0 group by pd.products_id order by p.products_ordered DESC, pd.products_name";

$asc_desc = ($_GET["order"] == 'ascending' ? 'asc' : 'desc');
switch ($_GET["sort"]){
	case 'products_name':
		$sortorder .= 'op.products_name '.$asc_desc;
		break;
	case 'products_model':
		$sortorder .= 'op.products_model '.$asc_desc;
		break;
	case 'quantitysum':
		$sortorder .= 'quantitysum '.$asc_desc;
		break;
	case 'gross':
		$sortorder .= 'gross '.$asc_desc;
		break;
	case 'gross_profit':
		$sortorder .= 'gross_profit '.$asc_desc;
		break;
	case 'gross_profit_per':
		$sortorder .= 'gross_profit_per '.$asc_desc;
		break;
	case 'agency_name':
		$sortorder .= 'agency_name '.$asc_desc;
		break;
	default:
		$sortorder .= 'quantitysum DESC, op.products_model';
		break;
}

if (isset($_GET['provider']) && $_GET['provider'] != '') {
	$where .= " and p.agency_id='" . $_GET['provider'] . "'";
}

if (isset($_GET['tourcode']) && $_GET['tourcode'] != '') {
	$where .= " and op.products_model like '%" . $_GET['tourcode'] . "%'";
}

if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
	$where .= " and (op.products_model like '%" . $_GET['keyword'] . "%' or op.products_id like '%" . $_GET['keyword'] . "%' or op.products_name like '%" . $_GET['keyword'] . "%') ";
}

//添加团名关键字
if (isset($_GET['products_id']) && $_GET['products_id'] != '') {
	$where .= " and op.products_id like '" . $_GET['products_id'] . "%' ";
}
//添加根据出发日期搜索
if (isset($_GET['date_min']) && $_GET['date_min'] != '') {
	$where .= " and o.date_purchased >='" . $_GET['date_min'] . " 00:00:00' ";
}
if (isset($_GET['date_max']) && $_GET['date_max'] != '') {
	$where .= " and o.date_purchased <='" . $_GET['date_max'] . " 23:59:59' ";
}
//根据团天数搜索
if ($_GET['day'] && $_GET['day'] != '') {
	$where .= " and p.products_durations ='" . (int) $_GET['day'] . "' and p.products_durations_type ='0' ";
}
$products_query_raw = "select o.date_purchased, op.products_id, p.products_status, p.provider_tour_code,p.agency_id, p.products_date_added, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross, sum(op.final_price_cost*op.products_quantity) as gross_cost, (sum(op.final_price*op.products_quantity) - sum(op.final_price_cost*op.products_quantity)) as gross_profit, if(sum(op.final_price*op.products_quantity)!=0, ((((sum(op.final_price*op.products_quantity))-(sum(op.final_price_cost*op.products_quantity)))/(sum(op.final_price*op.products_quantity)))*100), 0) as gross_profit_per FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, " . TABLE_TRAVEL_AGENCY . " as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id " . $where . " group by op.products_id order by " . $sortorder . "";

//下载到本地
if($_GET['download']=='1'){
	$filename = basename(__FILE__, '.php').'_'.date("YmdHis").'.xls';
	header("Content-type: text/html; charset=utf-8");	//用utf-8格式下载才行
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
	$data_query = tep_db_query($products_query_raw);
	//排名 产品ID 产品名称 供应商团号 团号 当前状态 添加日期 最新销售日期 销量 Gross    Gross Profit    Gross Profit(%) 
	echo '<table border="1" cellpadding="0" cellspacing="0">';
	echo '<tr><td>排名</td><td>产品ID</td><td>产品名称</td><td>供应商团号</td><td>团号</td><td>当前状态</td><td>添加日期</td><td>最新销售日期</td><td>销量</td><td>Gross</td></tr>';
	$no = 0;
	while ($products = tep_db_fetch_array($data_query)){
		$no++;
		echo '<tr><td>'.$no.'</td><td>'.$products['products_id'].'</td><td>'.$products['products_name'].'</td><td>'.$products['provider_tour_code'].'</td><td>'.$products['products_model'].'</td><td>'.($products['products_status'] == '1' ? '开放':'关闭').'</td><td>'.tep_datetime_short($products['products_date_added']).'</td><td>'.tep_datetime_short(tep_get_last_purchase_date_of_tour_show_here($products['products_id'])).'</td><td>'.$products['quantitysum'].'</td><td>'.sprintf('%01.2f', $products['gross']).'</td></tr>';	
	}
	echo '</table>';
	echo iconv('gb2312','utf-8//IGNORE',ob_get_clean());
	exit;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript">
	function close_div(divid,displaytype){
		document.getElementById(divid).style.display=displaytype;
	}
</script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
	<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

	<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_products_purchased');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
			<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0"
					width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1"
					class="columnLeft">
					<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
				</table></td>
			<!-- body_text //-->
			<td width="100%" valign="top"><table border="0" width="100%"
					cellspacing="0" cellpadding="2">
					<tr>
						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top">
										<table width="99%" cellpadding="2" cellspacing="0" border="0">
											<tr>
												<td class="smallText" align="left"><?php echo tep_draw_form('search', FILENAME_STATS_PRODUCTS_PURCHASED, '', 'get'); ?>
										<table border="0" cellspacing="2" cellpadding="2">
														<tr>
															<td class="smallText"><?php echo TABLE_HEADING_AGENCY_NAME; ?>:</td>
															<td class="smallText"><?php
															echo tep_draw_hidden_field('action', 'view_report');
															// echo tep_draw_hidden_field('month', $_GET['month']);
															

															$provider_array = array(
																	array(
																			'id' => '', 
																			'text' => TEXT_NONE 
																	) 
															);
															$provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
															while ($provider_result = tep_db_fetch_array($provider_query)) {
																$provider_array[] = array(
																		'id' => $provider_result['agency_id'], 
																		'text' => $provider_result['agency_name'] 
																);
															}
															echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px; " '); //onChange="this.form.submit();" 															?>
											</td>
														</tr>
														<tr>
															<td colspan="2">
																<table width="100%" cellpadding="0" cellspacing="0">
																	<tr>
																		<td class="smallText">Date From:&nbsp;</td>
																		<td class="smallText">
												<?php
												
echo tep_draw_input_field('date_min', $_GET['date_min'], ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');
												?>
												</td>
																		<td class="smallText">To&nbsp;</td>
																		<td class="smallText">
												<?php
												
echo tep_draw_input_field('modify_end_date', $_GET['modify_end_date'], ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');
												?>											
												</td>
																		<td>&nbsp;</td>
																	</tr>
																</table>

															</td>
														</tr>

														<tr>
															<td class="smallText">Search</td>
															<td class="smallText" colspan="2"><?php echo tep_draw_input_field('keyword',$_GET['keyword']);?> Product Id/Code/Name</td>
														</tr>
														<tr>
															<td class="smallText">行程天数</td>
															<td class="smallText" colspan="2"><?php echo tep_draw_input_num_en_field('day',$_GET['day']);?>天</td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td><input type="submit" name="Submit" value="Search"> <a
																href="<?= tep_href_link('stats_products_purchased.php');?>">清空搜索条件</a>

																<a
																href="<?= tep_href_link('stats_products_purchased.php','download=1&'.tep_get_all_get_params(array('page','y','x', 'action', 'download')));?>">下载到本地</a>
															</td>
														</tr>
														<tr>
															<td height="3"></td>
														</tr>
													</table>										
										<?php
										echo '</form>';
										?>
										</td>
											</tr>
											<tr>
												<td colspan="2">
													<table width="100%" cellspacing="0" cellpadding="0">
														<tr>
															<td class="smalltext errorText">Some order statuses have
																been excluded from this report: CC Auth Failed,
																Cancelled, Payment Pending (Suspicious Status?).</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2">
											<?php 
/*
												echo tep_draw_form('tourcode_search', FILENAME_STATS_PRODUCTS_PURCHASED, '', 'get');
											?>
											<table align="left" cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="smallText" width="31%" valign="top">Tour Code:</td>
													<td class="smallText" align="left">
														<?php 
															echo tep_draw_input_field('tourcode',$_GET['tourcode']);
														?>		
													</td><td valign="middle">
														&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?>									
													</td>
												</tr>
											</table>
											<?php/*
										       * echo tep_draw_form('tourcode_search',
										       * FILENAME_STATS_PRODUCTS_PURCHASED, '',
										       * 'get'); ?> <table align="left"
										       * cellpadding="0" cellspacing="0"
										       * border="0"> <tr> <td class="smallText"
										       * width="31%" valign="top">Tour Code:</td>
										       * <td class="smallText" align="left"> <?php
										       * echo
										       * tep_draw_input_field('tourcode',$_GET['tourcode']);
										       * ?> </td><td valign="middle"> &nbsp;<?php
										       * echo
										       * tep_image_submit('button_search.gif',
										       * IMAGE_SEARCH).'&nbsp;'; ?> </td> </tr>
										       * </table> <?php echo '</form>';
										       */


											?>											
										</td>

											</tr>
											<!--<tr>
									<?php //echo tep_draw_form('search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get'); ?>
								
								<script type="text/javascript"><!--
var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "search", "order_date_purchased_from","btnDate3","<?php //echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "search", "order_date_purchased_to","btnDate4","<?php //echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
</script>
										<td class="smallText" colspan="2">Order Date From:
										<script type="text/javascript">order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> To:
											<script type="text/javascript">order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
										</td>
									</tr>
									
									<tr>-->

										</table>
										<table border="0" width="100%" cellspacing="0" cellpadding="3">
											<tr class="dataTableHeadingRow">
												<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
												<td class="dataTableHeadingContent">P ID</td>
												<td class="dataTableHeadingContent">
					<?php echo TABLE_HEADING_PRODUCTS; ?>
					<?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=products_name&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=products_name&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>&nbsp;'; ?>				</td>
												<td class="dataTableHeadingContent">供应商团号</td>
												<td class="dataTableHeadingContent">团号</td>
												<td class="dataTableHeadingContent" align="center">当前状态</td>
												<td class="dataTableHeadingContent" align="center">Date
													Added</td>
												<td class="dataTableHeadingContent" align="center"
													nowrap="nowrap">Last Purchased<br> Date
												</td>
												<td class="dataTableHeadingContent" align="center">销量&nbsp;
				<?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=quantitysum&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=quantitysum&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
												<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS; ?>
				<?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<?php
				if ($access_full_edit == 'true' || ($allow_cost_gp_on_other_reports == true && $login_groups_id != '5')) {
					?>
				<td class="dataTableHeadingContent" align="right">Gross Profit
					<?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross_profit&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross_profit&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
												<td class="dataTableHeadingContent" align="right">Gross Profit(%)
					<?php echo '<br><a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross_profit_per&order=ascending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array('page','sort','order','Submit')).'sort=gross_profit_per&order=decending', 'NONSSL').'"><img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<?php
				}
				?>
              </tr>
<?php
$products_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $products_query_raw, $products_query_numrows);

$rows = 0;
$products_query = tep_db_query($products_query_raw);
while ($products = tep_db_fetch_array($products_query)) {
	$rows ++;
	
	if (strlen($rows) < 2) {
		$rows = '0' . $rows;
	}
	
	?>              <tr class="dataTableRow" style="cursor:auto; background-color:<?php ($tr_color = ($tr_color == '#ECFFEC' ? '#F0F0F0' : '#ECFFEC')); echo $tr_color;?>" >
												<td class="dataTableContent" nowrap="nowrap"><?php if(isset($_GET['page']) && $_GET['page']>1) { echo $rows+(($HTTP_GET_VARS['page']-1)*MAX_DISPLAY_SEARCH_RESULTS_ADMIN); }else{ echo $rows; } ?>.</td>
												<td class="dataTableContent" nowrap="nowrap"><?php echo $products['products_id']?></td>
												<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'].'</a> '.$products['products_name_english'];	?>			  </td>
												<td class="dataTableContent"><?php echo $products['provider_tour_code']?></td>
												<td class="dataTableContent"><b><?php echo $products['products_model']?></b></td>
												<td class="dataTableContent" align="center">				
				<?php
	if ($products['products_status'] == '1') {
		echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10);
	} else {
		echo tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
	}
	?>
				</td>
												<td class="dataTableContent" align="center"><?php echo tep_datetime_short($products['products_date_added']); ?></td>
												<td class="dataTableContent" align="center"><?php echo tep_datetime_short(tep_get_last_purchase_date_of_tour_show_here($products['products_id'])); ?></td>
												<td class="dataTableContent" align="center"><?php echo tep_draw_form('views_'.$products['products_id'], FILENAME_STATS_PRODUCTS_PURCHASED,'', 'get', 'id="views_'.$products['products_id'].'"'); ?><?php echo '<a href="javascript:close_div(\'show_info_popup_'.$products['products_id'].'\',\'\');" onClick=" sendFormData(\'views_'.$products['products_id'].'\', \''.tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, tep_get_all_get_params(array()).'active=true&product_id='.$products['products_id']).'\', \'show_info_popup_'.$products['products_id'].'\',\'true\')" class="col_a1"><b>'.$products['quantitysum'].'</b></a>'?>&nbsp;
				<?php echo tep_draw_input_field('ajaxlast','ajaxCalled','','','hidden'); echo "</form>";?></td>
												<td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $products['gross']); ?>&nbsp;</td>
				<?php
	if ($access_full_edit == 'true' || ($allow_cost_gp_on_other_reports == true && $login_groups_id != '5')) {
		$gross_profit = sprintf('%01.2f', ($products['gross'] - $products['gross_cost']));
		if ($products['gross'] != 0) {
			$margin = tep_round((((($products['gross']) - ($products['gross_cost'])) / ($products['gross'])) * 100), 0);
		} else {
			$margin = 0;
		}
		?>
					<td class="dataTableContent" align="right">$<?= $gross_profit;?></td>
												<td class="dataTableContent" align="right"><?php echo $margin . '%';?></td>
					<?php
	}
	?>
              </tr>
											<tr class="dataTableRow">
												<td class="dataTableContent" colspan="6"></td>
												<td colspan="6" align="center" class="dataTableContent"><?php echo '<div id=\'show_info_popup_'.$products['products_id'].'\'></div>'?></td>
											</tr>
<?php
}
?>
            </table>
									</td>
								</tr>
								<tr>
									<td colspan="5"><table border="0" width="100%" cellspacing="0"
											cellpadding="2">
											<tr>
												<td class="smallText" valign="top"><?php echo  $products_split->display_count1($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
												<td class="smallText" align="right"><?php echo $products_split->display_links1($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?>&nbsp;</td>
											</tr>
										</table></td>
								</tr>
							</table></td>
					</tr>
				</table></td>
			<!-- body_text_eof //-->
		</tr>
	</table>
	<!-- body_eof //-->

	<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>