<?php
/*
 * 本统计表以订单为中心，可能有重复的客户产生
 */

require ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_analysis_order');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
if (isset($_GET['b_time']) && $_GET['b_time'] != '') {
	$b_time = $_GET['b_time'];
} else {
	$b_time = date('Y-m-1');
}
if (isset($_GET['e_time']) && $_GET['e_time'] != '') {
	$e_time = $_GET['e_time'];
} else {
	$e_time = date('Y-m-d');
}
$order_number = $sql_order = $order_money='';
$order_n = isset($_GET['order_number']) && $_GET['order_number'] != '';
$order_m = isset($_GET['order_money']) && $_GET['order_money'] != '';

if ($order_n) {
	$order_number = $_GET['order_number'];
	unset($_GET['order_number']);
	$sql_order = ' order by number ' . $order_number;
}
$order_number = ($order_number == 'ASC' || $order_number == '') ? 'DESC' : 'ASC';
if ($order_m) {
	$order_money = $_GET['order_money'];
	unset($_GET['order_money']);
	$sql_order = ' order by money ' . $order_money;
}
$order_money = ($order_money == '' || $order_money == 'ASC') ? 'DESC' : 'ASC';
$url = '';
foreach ($_GET as $key => $value) {
	$url .= "&&$key=$value";
}
$url = $url ? substr($url, 2) : '';
$url_number=$url?'?'.$url.'&&order_number='.$order_number:'?order_number='.$order_number;
$url_money=$url?'?'.$url.'&&order_money='.$order_money:'?order_money='.$order_money;

$str_sql = 'SELECT count(orders_id) as number,sum(orders_paid) as money,customers_advertiser FROM orders WHERE date_purchased BETWEEN "' . $b_time . '" AND "' . $e_time . '" GROUP BY customers_advertiser '.$sql_order;
$sql_query = tep_db_query($str_sql);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css"
	href="includes/jquery-1.3.2/jquery_ui.css" />
<style type="text/css">
.dataTableHeadingContent {
	font-family: Verdana, Arial, sans-serif;
	font-size: 12px;
	font-weight: bold;
	background: #0099CC
}
</style>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js"></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>

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
$listrs = new Remark('stats_analysis_order');
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
									<td class="pageHeading">订单统计</td>
									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<form method="get" action="" name="search">
							<td>时间：从<input type="text" name="b_time"
								onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"
								value="<?=$b_time?>" />到<input type="text" name="e_time"
								onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"
								value="<?=$e_time?>" /><input type="submit" value="send"/>
								<input type="button" value="reset" /></td>

						</form>
					</tr>
					<tr>
						<td><legend align="left"> Stats Results </legend>

							<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr class="dataTableRow">
									<td class="dataTableHeadingContent">订单来源</td>
									<td class="dataTableHeadingContent"><a href="<?=$url_number?>">订单数量</a></td>
									<td class="dataTableHeadingContent"><a href="<?=$url_money?>">订单金额</a></td>
								</tr>
        <?php while ($row=tep_db_fetch_array($sql_query)){?>
        <tr bgcolor="#CDDADA">
									<td class="dataTableContent"><?=$row['customers_advertiser'] ?></td>
									<td class="dataTableContent"><?=$row['number'] ?></td>
									<td class="dataTableContent"><?=$row['money'] ?></td>
								</tr>
        <?php }?>
    </table> <!-- body_text_eof //-->
				
				</table> <!-- body_eof //--> <!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
