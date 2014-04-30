<?php
require ('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>订单短信</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css"
	href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js"></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js"></script>
</head>

<body>
<?php
require (DIR_WS_INCLUDES . 'header.php');
$place_array = array (
		'cpunc_sms_history' => array (
				'color' => 'blue',
				'name' => '中国' 
		),
		'cpunc_sms_hi8d_history' => array (
				'color' => 'red',
				'name' => '美国' 
		) 
);
$cn = isset($_GET['cn']) ? $_GET['cn'] : '';
$en = isset($_GET['en']) ? $_GET['en'] : '';
$orders_id = isset($_GET['orders_id']) ? $_GET['orders_id'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$phone_number = isset($_GET['phone_number']) ? $_GET['phone_number'] : '';
$str_sql = 'SELECT orders_sms_history_id,orders_id,table_name,added_date,admin_id,sms_content,sms_phone FROM orders_sms_history WHERE 1 ';
if (!(($cn && $en) || (!$cn && !$en))) {
	$cn ? $str_sql .= ' AND table_name="cpunc_sms_history"' : '';
	$en ? $str_sql .= ' AND table_name="cpunc_sms_hi8d_history"' : '';
}
$type ? $str_sql .= ' AND table_name="' . $type . '"' : '';
$orders_id ? $str_sql .= ' AND orders_id=' . $orders_id : '';
$phone_number ? $str_sql .= ' AND sms_phone like "%' . $phone_number . '%"' : '';
$str_sql .= ' ORDER BY key_id_name,added_date DESC';
$track_query_numrows = 0;
$max_rows_every_page = MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
$track_split = new splitPageResults($HTTP_GET_VARS['page'], $max_rows_every_page, $str_sql, $track_query_numrows);
$a = $track_split->display_links($track_query_numrows, $max_rows_every_page, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array (
		'page',
		'y',
		'x',
		'action' 
)));
$b = $track_split->display_count($track_query_numrows, $max_rows_every_page, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
?>
<h1>(商务中心)订单短信</h1>
	<br />
	<br />
	<fieldset>
	<legend style="text-align:left">搜索区域</legend>
	<form method="get" action="" name="search">
		<table width="100%" border="1">
			<tr>
				<td>中国电话<input type="checkbox" name="cn"
					<?php if($cn) echo 'checked';?> /></td>
				<td>美国电话<input type="checkbox" name="en"
					<?php if($en) echo 'checked';?> /></td>
				<td>订单号</td>
				<td><input type="text" name="orders_id" value="<?=$orders_id?>" /></td>
				<td>电话号码</td>
				<td><input type="text" name="phone_number"
					value="<?=$phone_number?>" /></td>
				<td><input type="submit" value="search" /></td>
			</tr>
		</table>
	</form>
	</fieldset>
	<br />
	<br />
	<br />
	<fieldset>
	<legend style="text-align:left">列表区域</legend>
	<table width="100%" border="1">
		<tr>
			<td><p>订单号</p></td>
			<td><p>地区</p></td>
			<?php if($can_see_orders_phone_number){?><td><p>发送号码</p></td><?php }?>
			<td><p>短信内容</p></td>
			<td><p>操作工号</p></td>
			<td align="center"><p>发送时间</p></td>
		</tr>
  <?php
		
$query = tep_db_query($str_sql);
		while ($info = tep_db_fetch_array($query)) {
			?>
  <tr>
			<td><?=$info['orders_id']?></td>
			<td><font color="<?=$place_array[$info['table_name']]['color']?>"><?=$place_array[$info['table_name']]['name']?></font></td>
			<?php if($can_see_orders_phone_number){?><td><?=$info['sms_phone']?></td><?php }?>
			<td><?=$info['sms_content']?></td>
			<td><?=tep_get_job_number_from_admin_id($info['admin_id'])?></td>
			<td align="center"><?=$info['added_date']?></td>
		</tr>
  <?php }?>
  <tr>
			<td colspan="3"><?=$a?></td>
			<td colspan="3"><?=$b?></td>
		</tr>
	</table>
	</fieldset>
<?php
require (DIR_WS_INCLUDES . 'application_bottom.php'); 
?>