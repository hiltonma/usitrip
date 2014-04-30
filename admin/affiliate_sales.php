<?php
/*
  $Id: affiliate_sales.php,v 1.1.1.1 2004/03/04 23:38:09 ccwjr Exp $
  OSC-Affiliate
  Contribution based on:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 - 2003 osCommerce
  Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('affiliate_sales');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$search_orders_status = $_GET['orders_status'];
require(DIR_FS_CATALOG.'includes/classes/affiliate.php');
$Affiliate = new affiliate();
	 //amit added to fileter 
/*if (isset($_GET['aff_filter']) && $_GET['aff_filter'] != '' && $_GET['aff_filter'] != '0') {
	$extra_aff_fileter_where = " where a.affiliate_homepage != '' ";
	//$extra_aff_fileter_select = " a.affiliate_homepage, ";  
} else {
	$extra_aff_fileter_where = " WHERE 1 ";// o.orders_status='100006'  ";
}*/
switch ($search_orders_status) {
	case '100002' :
		$extra_aff_fileter_where = " where o.orders_status='100002' ";
		break;
	case 'other' :
		$extra_aff_fileter_where = " where o.orders_status<>'100006' and o.orders_status<>'100002' ";
		break;
	case '100006' :
	default :
		$search_orders_status = '100006';
		$extra_aff_fileter_where = " where o.orders_status='100006' ";
}
  
//amit added to check valid invalid status start
$action = isset($_GET['action']) ? $_GET['action'] : '';
if (tep_not_null($action)) {
	switch ($action) {
		case 'setflagf':
			if ($_GET['flag'] == '0' || $_GET['flag'] == '1') {
				if (isset($_GET['sID']) && isset($_GET['aoid'])) {
					tep_db_query("update " . TABLE_AFFILIATE_SALES . " set affiliate_isvalid = '" . $_GET['flag'] . "' where  affiliate_id = '" . $_GET['sID'] . "' and  affiliate_orders_id  = '" . $_GET['aoid'] . "'");
				}
			}
			tep_redirect(tep_href_link(FILENAME_AFFILIATE_SALES, tep_get_all_get_params(array('action', 'info', 'x', 'y', 'sID','flag','aoid'))));
			break;
	}
} ///end of if
//amit added to check valid invalid status end


$sortorder = ' order by asale.affiliate_date  desc';
//amit added to make order status start
switch ($_GET["sorts"]) {
	case 'lname':
		if ($_GET["order"] == 'ascending') {
			$sortorder = ' order by c.customers_lastname  asc';
		} else {
			$sortorder = ' order by c.customers_lastname  desc';
		}
		break;
	case 'fname':
		if ($_GET["order"] == 'ascending') {
			$sortorder = ' order by c.customers_firstname  asc';
		} else {
			$sortorder = ' order by c.customers_firstname  desc';
		}
		break;
	case 'adate':
		if ($_GET["order"] == 'ascending') {
			$sortorder = ' order by asale.affiliate_date   asc';
		} else {
			$sortorder = ' order by asale.affiliate_date  desc';
		}
		break;
	case 'avalue':
		if($_GET["order"]=='ascending') {
			$sortorder = ' order by asale.affiliate_value   asc';
		} else {
			$sortorder = ' order by asale.affiliate_value  desc';
		}
		break;
	case 'asales':
		if($_GET["order"]=='ascending') {
			$sortorder = ' order by asale.affiliate_payment  asc';
		} else {
			$sortorder = ' order by asale.affiliate_payment desc';
		}
		break;
	default:
		if($_GET["order"]=='ascending') {
			$sortorder = ' order by asale.affiliate_date  asc';
		} else {
			$sortorder = ' order by asale.affiliate_date  desc';
		}
		break;
}

	
/**
 * 检查COUPON是否归属联盟指定的ID号，如果COUPON与当前联盟ID不一致，则设置联盟中该记录为无效状态
 * @param string $coupon 优惠码
 * @param number $affiliate_id 当前要检查的COUPON所在的订单所归属的联盟会员ID
 * @param number $orders_id 订单ID
 * @return number|boolean
 * @author lwkai 2013-1-9 下午3:16:29
 */
function check_coupon_is_affiliate($coupon,$affiliate_id,$orders_id) {
	$result = tep_db_query("select affiliate_id from affiliate_affiliate where affiliate_coupon_code='" . $coupon . "'");
	$rs = tep_db_fetch_array($result);
	if ($rs) {
		if (intval($rs['affiliate_id']) != intval($affiliate_id)) { //如果优惠码的联盟ID不等于传进来的联盟ID
			$data = array();
			$data['affiliate_isvalid']='0';
			tep_db_fast_update('affiliate_sales', "affiliate_id='" . intval($affiliate_id) . "' and affiliate_orders_id='" . intval($orders_id) . "'", $data);
			return -1;
		}
		return $rs['affiliate_id'];
	}
	return 0;
}
	
$select = "select asale.*,ot.title, os.orders_status_name as orders_status, a.affiliate_id, c.customers_firstname as affiliate_firstname, c.customers_lastname as affiliate_lastname";
$from = " from " . TABLE_AFFILIATE_SALES . " asale 
		left join " . TABLE_ORDERS . " o on (asale.affiliate_orders_id = o.orders_id) 
		left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = " . $languages_id . ") 
		left join " . TABLE_AFFILIATE . " a on (a.affiliate_id = asale.affiliate_id ) 
		left join " . TABLE_CUSTOMERS . " c  on (c.customers_id = asale.affiliate_id)
		left join " . TABLE_ORDERS_TOTAL . " ot on (ot.orders_id = o.orders_id and ot.class='ot_coupon')";
$where = " ";
$groupby = "";
$orderby = "";
//amit added to make order status end
if(tep_not_null($_GET['search'])){
	$search = tep_db_prepare_input($_GET['search']);
	if(is_numeric($_GET['search'])){
		$where_exc .= " and c.customers_id = '".$_GET['search']."'  ";
	}else{
		$where_exc .= " and (c.customers_firstname like '%" . tep_db_input($search) . "%' or c.customers_lastname like '%" . tep_db_input($search) . "%' or ot.title like '%" . tep_db_input($search) . "%') ";
	}
}

if (tep_not_null($_GET['departure_date_start']) || tep_not_null($_GET['departure_date_end'])) {
	if(tep_not_null($_GET['departure_date_start'])){
		$departure_date_start = tep_db_prepare_input($_GET['departure_date_start']);
		$where_exc .= " and op.products_departure_date >= '" . date('Y-m-d 00:00:00',strtotime(tep_db_input($departure_date_start))) . "' ";
	}
	if(tep_not_null($_GET['departure_date_end'])){
		$departure_date_end = tep_db_prepare_input($_GET['departure_date_end']);
		$where_exc .= " and op.products_departure_date <= '" . date('Y-m-d 23:59:59',strtotime(tep_db_input($departure_date_end))) . "' ";
	}
	
	$from .= " left join " . TABLE_ORDERS_PRODUCTS . " op on (op.orders_id = o.orders_id)";
	$groupby = "  group by op.orders_id ";
}
if(tep_not_null($_GET['start_date'])){
	$start_date = trim($_GET['start_date']);
	$where_exc .= ' AND asale.affiliate_date >="'.$start_date.' 00:00:00" ';
}
if(tep_not_null($_GET['end_date'])){
	$end_date = trim($_GET['end_date']);
	$where_exc .= ' AND asale.affiliate_date <="'.$end_date.' 23:59:59" ';
}

if ($_GET['acID'] > 0) {
	$where .= $extra_aff_fileter_where." and asale.affiliate_id = '" . $_GET['acID'] . "' " . $where_exc . "";
	$affiliate_sales_raw = $select . $from . $where . $groupby . $sortorder; 
	$aff_sql = $affiliate_sales_raw;
} else {
	$where = $extra_aff_fileter_where . " " . $where_exc . " ";	
	$affiliate_sales_raw = $select . $from . $where . $groupby . $sortorder;
	$aff_sql = $affiliate_sales_raw;
	// echo $affiliate_sales_raw;
}

$affiliate_sql = explode('from',$affiliate_sales_raw);
$affiliate_sql = "select sum(asale.affiliate_payment) as payment_total from " . $affiliate_sql[1];
$affiliate_sql = preg_replace('/group\s+by\s+[^\s]+/im', '', $affiliate_sql);
$payment_total = tep_db_query($affiliate_sql);
$payment_total = tep_db_fetch_array($payment_total);
$payment_total = $payment_total['payment_total'];

$affiliate_sales_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $affiliate_sales_raw, $affiliate_sales_numrows);

// 导出Excel文件
if (tep_not_null($_GET['OutputExcel']) && $_GET['OutputExcel'] == 'Output Excel') {
	$query = tep_db_query($aff_sql);	   
	$filename = '洛杉矶华人网_'.$_GET['start_date'].'-'.$_GET['end_date'];	   
	$table = "<tr>";
	$table .= "<th>Last Name</th>
				   <th>First Name</th>
				   <th>Coupon</th>
				   <th>Date</th>
				   <th>Departure Date</th>
				   <th>Order_ID</th>
				   <th>Value</th>				   
				   <th>Commission Rate</th>
				   <th>Sales</th>
				   <th>Status</th>";
	$table .= "</tr>";	  
	   
	while ($affiliate_sales = tep_db_fetch_array($query)) {
		$table .= "<tr>";
		$table .= "<td>". $affiliate_sales["affiliate_lastname"] ."</td>";
		$table .= "<td>". $affiliate_sales["affiliate_firstname"] ."</td>";
		$table .= "<td>";
		$tmp =  explode(':',$affiliate_sales['title']);
		if (isset($tmp[1])) {
			$coupon_affiliate_id = check_coupon_is_affiliate($tmp[1],$affiliate_sales['affiliate_id'],$affiliate_sales['affiliate_orders_id']);
			if ($coupon_affiliate_id) {
				$table .= $tmp[1];
			}
		}
		$table .= "</td>";
		$table .= "<td>". tep_date_short($affiliate_sales["affiliate_date"]) ."</td>";
		$table .= "<td>" . tep_get_date_of_departure($affiliate_sales['affiliate_orders_id']) . "</td>";
		$table .= "<td>". $affiliate_sales["affiliate_orders_id"] ."</td>";
		$table .= "<td>". $currencies->display_price($affiliate_sales["affiliate_value"],'') ."</td>";
		$table .= "<td>". $affiliate_sales["affiliate_percent"] . '%' ."</td>";
		$table .= "<td>". $currencies->display_price($affiliate_sales['affiliate_payment'],'') ."</td>";
		$table .= "<td>". $affiliate_sales["orders_status"] ."</td>";
		$table .= "</tr>";
	}
	$table .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . $currencies->display_price($payment_total,'') . "</td><td></td></tr>";
	$html = '<html xmlns:o="urn:schemas-microsoft-com:office:office"  
		xmlns:x="urn:schemas-microsoft-com:office:excel"  
		xmlns="http://www.w3.org/TR/REC-html40">  
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
		<html>  
			<head>  
				<meta http-equiv="Content-type" content="text/html;charset=gbk" />  
				<style id="Classeur1_16681_Styles"></style>  
			</head>  
			<body>  
				<div id="Classeur1_16681" align=center x:publishsource="Excel">  
					<table x:str border=1 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">  
						'.
						 $table   
						.'
					</table>  
				</div>  
			</body>  
		</html>';
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" .$filename.'.xls' );
	header('Cache-Control:   must-revalidate,   post-check=0,   pre-check=0');
	header('Expires:   0');
	header('Pragma:   public');
	echo $html;
	exit;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<style type="text/css">
.uifix:after, #head:after, #hbody:after, #body:after {
    clear: both;
    content: "";
    display: block;
    font-size: 0;
    height: 0;
    visibility: hidden;
}
#small_menu{margin:0;padding:0;}
#small_menu li{float:left;border:1px solid #eee;background:#fdfdfd;margin-left:-1px;}
#small_menu li.curr{
	background:-webkit-gradient(linear, left top, left bottom, from(#eee), to(#00abed));/* Chrome, Saf4+ */
	background:-moz-linear-gradient(top, #eee, #00abed); /* Firefox */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#00abed', GradientType='0');
}
#small_menu li.curr a{color:#1436bd;}
#small_menu li.curr a:hover{color:#1436bd;cursor:default;background:none;}
#small_menu li a{display:block;width:150px;height:25px;line-height:25px;text-align:center;}
#small_menu li a{text-decoration:none;}
#small_menu li a:hover{background:#00ABED;color:#fff;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td><div class="pageHeading" title="<?php echo HEADING_TITLE; ?>">销售联盟（销售报表）</div>
		<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('affiliate_sales');
$list = $listrs->showRemark();
?>
		<!-- search start -->
		<fieldset>
			<legend style="text-align:left">工具栏</legend>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading">搜索：</td>
					<td align="left">
						<?php  echo tep_draw_form('search', FILENAME_AFFILIATE_SALES, '', 'get'); ?>
						<table>
							<tr> 
								<td class="main">关键字：<?php echo tep_draw_input_field('search',$_GET['searchkey'],' title="也可搜优惠码、人名、用户ID" ');?></td>
								<td class="main">&nbsp;&nbsp;创建日期:
									
									<?php echo tep_draw_input_field('start_date',$_GET['start_date'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');?>
									至
								<?php echo tep_draw_input_field('end_date',$_GET['end_date'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');?>
								</td>
								<td class="main">
								出团日期:
								<?php echo tep_draw_input_field('departure_date_start',$_GET['departure_date_start'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');?>
								至
								<?php echo tep_draw_input_field('departure_date_end',$_GET['departure_date_end'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');?>
								</td>
								<td class="main">&nbsp;<input type="submit" value="搜索"/> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onClick="location.href='<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('search','start_date','end_date','departure_date_start','departure_date_end'))?>'" value="清除搜索条件"/>&nbsp;&nbsp;&nbsp;&nbsp; <span  class="pageHeading">导出到EXCEL：</span><input type="button" style="width:100px; height:20px;" value="Output Excel" name="OutputExcel" onClick="location.href='<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('OutputExcel'=>'Output Excel'))?>'"></td>
							</tr>
						</table>
						</form>
					</td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					<td class="pageHeading" align="right"><?php echo '<input type="button" onClick="location.href=\'' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '') . '\'" value="返回联盟汇总" />'; ?></td>
					<td align="right">
						<table  cellpadding="0" cellspacing="0">
							<tr>
								<td class="main" nowrap></td>
								<td nowrap class="main	">
									<?php //include("affiliate_filter_form.php"); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<!-- search end -->
		</td>
	</tr>
	<tr>
		<td valign="top">
			<ul id="small_menu" class="uifix">
			<li <?php if ($search_orders_status == '100006') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('orders_status'=>'100006'),array('x','y'))?>">已出团</a></li>
			<li <?php if ($search_orders_status == '100002') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('orders_status'=>'100002'),array('x','y'))?>">已发电子参团凭证-未出团</a></li>
			<li <?php if ($search_orders_status == 'other') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('orders_status'=>'other'),array('x','y'))?>">其它</a></li>
			</ul>
			<div>
			<table border="0" width="100%" cellspacing="0" cellpadding="4">
				<tr class="dataTableHeadingRow">
					<!-- <td class="dataTableHeadingContent">
						<?php 
						//echo '序号';
						//echo TABLE_HEADING_LASTNAME;
						//echo '</br><a href="' . $_SERVER['PHP_SELF'] . '?sorts=lname&order=ascending'.(isset($_GET['acID']) ? '&acID=' . $_GET['acID'] . '' : '').(isset($_GET['aff_filter']) ? '&aff_filter=' . $_GET['aff_filter'] . '' : '').'"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sorts=lname&order=decending'.(isset($_GET['acID']) ? '&acID=' . $_GET['acID'] . '' : '').(isset($_GET['aff_filter']) ? '&aff_filter=' . $_GET['aff_filter'] . '' : '').'">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; 
						?>
					</td> -->
					<td class="dataTableHeadingContent">
						<?php 
						echo '<span title="' . TABLE_HEADING_FIRSTNAME .'">姓名</span>&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' .  tep_get_all_get_params_fix(array('sorts'=>'fname','order'=>'ascending')) . '"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'fname','order'=>'decending')) . '">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; 
						?>
					</td>
					<td class="dataTableHeadingContent" align="center">
						状态
					</td>
					<td class="dataTableHeadingContent" align="center" title="注意：并不是所有联盟成员带来的订单都有优惠码的！有优惠码推荐和会员id代码推广两种方式如http://www.uiaaa.com/">
						优惠码
					</td>
					<td class="dataTableHeadingContent" align="center"><?php echo '<span title="' . TABLE_HEADING_SALES_STATUS . '">是否有效</span>'; ?></td>
					<td class="dataTableHeadingContent" align="center">
						<?php 
							echo '<span title="' . TABLE_HEADING_DATE .'">创建日期</span>&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'adate','order'=>'ascending')).'"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'adate','order'=>'decending')).'">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; 
						?>
					</td>
					<td class="dataTableHeadingContent" align="center">
					出团日期:
					</td>
					<td class="dataTableHeadingContent" align="right">
						<?php echo '<span title="' . TABLE_HEADING_ORDER_ID . '">订单号</span>'; ?>
					</td>
					<td class="dataTableHeadingContent" align="right">
						<?php 
							echo '<span title="' . TABLE_HEADING_VALUE . '">订单金额</span>' . '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'avalue','order'=>'ascending')).'"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'avalue','order'=>'decending')).'">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; 
						?>
					</td>
					<td class="dataTableHeadingContent" align="right">
						<?php echo '<span title="' . TABLE_HEADING_PERCENTAGE . '">佣金率</span>'; ?>
					</td>
					<td class="dataTableHeadingContent" align="right">
						<?php 
							echo '<span title="' . TABLE_HEADING_SALES . '">佣金(总额:' . $currencies->display_price($payment_total,'') . ')</span>';
							echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'asales','order'=>'ascending')) . '">';
							echo '<img src="images/arrow_up.gif" border="0"></a>';
							echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'asales','order'=>'decending')) . '">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; 
						?>
					</td>
					<td class="dataTableHeadingContent" align="center"><?php echo '<span title="' . TABLE_HEADING_STATUS . '">订单状态</span>'; ?></td> 
					
					<!--td class="dataTableHeadingContent" align="center">操作</td--> 
				</tr>
				<?php
				if ($affiliate_sales_numrows > 0) {
					$affiliate_sales_values = tep_db_query($affiliate_sales_raw);
					$number_of_sales = '0';
					while ($affiliate_sales = tep_db_fetch_array($affiliate_sales_values)) {
						$number_of_sales++;
						if (($number_of_sales / 2) == floor($number_of_sales / 2)) {
							echo '		  <tr class="dataTableRowSelected">';
						} else {
							echo '		  <tr class="dataTableRow">';
						}
						$link_to = '<a href="orders.php?action=edit&oID=' . $affiliate_sales['affiliate_orders_id'] . '">' . $affiliate_sales['affiliate_orders_id'] . '</a>';
						?>
						<!-- <td class="dataTableContent">
							<?php 			
							//echo $affiliate_sales['affiliate_lastname'];			
							?>
						</td> -->
						<td class="dataTableContent"><a href="<?php echo tep_href_link('affiliate_affiliates.php','search=' . rawurlencode($affiliate_sales['affiliate_firstname']))?>" target="_blank">
							<?php 			
							echo $affiliate_sales['affiliate_firstname']; 
							?></a>
						</td>
						<td class="dataTableContent" align="center">
						<?php echo $Affiliate->getPaymentStatus($affiliate_sales['affiliate_payment_id']);?>
						</td>
						<td class="dataTableContent" align="center" title="优惠码">
						<?php 
						$tmp =  explode(':',$affiliate_sales['title']);
						if (isset($tmp[1])) {
							$coupon_affiliate_id = check_coupon_is_affiliate($tmp[1],$affiliate_sales['affiliate_id'],$affiliate_sales['affiliate_orders_id']);
							if ($coupon_affiliate_id >= 0) {
								echo $tmp[1];
							} elseif ($coupon_affiliate_id < 0) {
								echo '<span title="检测到COUPON不是该用户的，此记录自动关闭" style="color:red">'.$tmp[1].'</span>';
							}
						}else{
							//如无优惠码就要提醒财务去检查订单来源页
						?>
							<b style="color:#F00">注意：此单没有优惠码。请检查此单的来源页，如果没有效的来源页请设置为无效！</b>
						<?php
						}
						 ?>
						</td>
						<td class="dataTableContent" align="center" sensitive="true">
							<?php
							if ($affiliate_sales['affiliate_isvalid'] == '1') {
								echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, 'action=setflagf&flag=0&sID=' . $affiliate_sales['affiliate_id'] . '&aoid=' . $affiliate_sales['affiliate_orders_id'].'&'.tep_get_all_get_params(array('action', 'info', 'x', 'y', 'sID','flag','aoid'))  ) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
							} else {
								echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, 'action=setflagf&flag=1&sID=' . $affiliate_sales['affiliate_id'] . '&aoid=' . $affiliate_sales['affiliate_orders_id'].'&'.tep_get_all_get_params(array('action', 'info', 'x', 'y', 'sID','flag','aoid')) ) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
							}
							?>
						</td>
						<td class="dataTableContent" align="center"><?php echo tep_date_short($affiliate_sales['affiliate_date']); ?></td>
						<td class="dataTableContent" align="center"><?php echo tep_get_date_of_departure($affiliate_sales['affiliate_orders_id'])?></td>
						<td class="dataTableContent" align="right"><?php echo $link_to; ?></td>
						<td class="dataTableContent" align="right">&nbsp;&nbsp;<?php echo $currencies->display_price($affiliate_sales['affiliate_value'], ''); ?></td>
						<td class="dataTableContent" align="right"><?php echo $affiliate_sales['affiliate_percent'] . "%" ; ?></td>
						<td class="dataTableContent" align="right">&nbsp;&nbsp;<?php echo $currencies->display_price($affiliate_sales['affiliate_payment'], ''); ?></td>
						<td class="dataTableContent" align="center">
							<?php 
							if ($affiliate_sales['orders_status']) 
								echo $affiliate_sales['orders_status']; 
							else 
								echo TEXT_DELETED_ORDER_BY_ADMIN; 
							?>
						</td>
						
						</tr>
						<?php
					}
				} else {
					?>
					<tr class="dataTableRowSelected">
						<td colspan="8" class="smallText"><?php echo TEXT_NO_SALES; ?></td>
					</tr>
					<?php
				}
				if ($affiliate_sales_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
					?>
					<tr>
						<td colspan="8">
							<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
									<td class="smallText" valign="top"><?php echo $affiliate_sales_split->display_count($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SALES); ?></td>
									<td class="smallText" align="right"><?php echo $affiliate_sales_split->display_links($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			</div>
		</td>
	</tr>
</table>
<!-- body_eof //-->

<script type="text/javascript"> 
<?php if($can_copy_affiliate_sensitive_information !== true){?>
//元素中的属性sensitive="true"的元素为敏感元素
if (window.sidebar){	//其它浏览器
	var disabledObj = $('[sensitive="true"]');
	$(disabledObj).mousedown(function(){ return false; });
	$(disabledObj).click(function(){ return false; });
}else{	//IE浏览器
	var disabledObj = document.getElementsByTagName('*');
	for(var i=0; i<disabledObj.length; i++){
		if(disabledObj[i].sensitive == "true"){
			disabledObj[i].onselectstart = new Function ("return false");
		}
	}
}
<?php }?>
</script>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
