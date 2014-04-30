<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
switch($_GET['action']){
	case 'CC_check':	//CC冻结超过15天未处理 
		$OldDateTime = date('Y-m-d 23:59:59', (time()-(15*24*60*60)) );
		$cc_check_sql = tep_db_query('SELECT orders_id FROM `orders` WHERE orders_status not in (0,6,100005,100058,100006) AND date_purchased < "'.$OldDateTime.'" AND cc_number !="" AND cc_number is not null ');
		//筛选
		$array_1 = array();
		while($cc_check = tep_db_fetch_array($cc_check_sql)){
			$check_oh_sql = tep_db_query('SELECT orders_id FROM `orders_status_history` WHERE orders_id="'.(int)$cc_check['orders_id'].'" AND orders_status_id ="100006" limit 1');
			$check_oh_row = tep_db_fetch_array($check_oh_sql);
			if(!(int)$check_oh_row['orders_id']){
				$array_1[] = $cc_check['orders_id'];
			}
		}
		$count_row = count($array_1);
		if((int)$count_row){		 
			$array_to_str = implode(',',$array_1);
			$sql_str = 'SELECT * FROM `orders` WHERE orders_id in('.$array_to_str.') Order By date_purchased Limit 10000 ';
		}
		define('HEADING_TITLE','CC冻结超过15天未处理['.$count_row.']');
		break;
	case 'flight_check':
		//$count_sql = tep_db_query('SELECT count(*) as count FROM `orders_product_flight` WHERE show_warning_on_admin="1" ');
		$count_sql = tep_db_query('SELECT count(*) as count
			FROM orders_product_flight f, orders o
			WHERE f.orders_id = o.orders_id AND f.show_warning_on_admin = "1" AND (
			o.orders_status <>6 AND o.orders_status <>100058 AND o.orders_status <>100002 AND o.orders_status <>100005 AND o.orders_status <>100006)');
		$count_row = tep_db_fetch_array($count_sql);
		define('HEADING_TITLE','航班信息更新['.$count_row['count'].']');
		//$sql_str = 'SELECT * FROM `orders_product_flight` WHERE show_warning_on_admin="1" ';
		$sql_str='SELECT *
			FROM orders_product_flight f, orders o
			WHERE f.orders_id = o.orders_id AND f.show_warning_on_admin = "1" AND (
			o.orders_status <>6 AND o.orders_status <>100058 AND o.orders_status <>100002 AND o.orders_status <>100005 AND o.orders_status <>100006)';
		break;
	//点航班信息已阅
	case 'hidden_flight':
		if((int)$_GET['orders_flight_id']){
			$admin_job_number = tep_get_admin_customer_name($login_id);
			$admin_date = date('Y-m-d H:i:s');
			tep_db_query('update `orders_product_flight` set show_warning_on_admin="0",admin_job_number="' . $admin_job_number . '",admin_time="' . $admin_date . '" where orders_flight_id="'.(int)$_GET['orders_flight_id'].'" ');
			// by lwkai added 2013-07-31 检测非订单归属人点已阅按钮是否需要把当前点击人加入订单归属
			if (isset($_GET['products_id'],$_GET['orders_id'],$_GET['orders_products_id'])) {
				require_once DIR_FS_CLASSES . 'vesting_orders.class.php';
				$vo = new vesting_orders();
				$vo->confirm_flights($admin_job_number, intval($_GET['orders_id']), intval($_GET['orders_products_id'])); 
			}
			// by lwkai added 2013-07-31 结束
			if ($_GET['ajax'] == 'true') {
				echo 'success';
				exit;
			}
			$messageStack->add_session(db_to_html('已经关闭'), 'success');
			tep_redirect(tep_href_link('orders_warning.php', tep_get_all_get_params(array('orders_flight_id', 'action')) .'action=flight_check' ));
		}
		break;
	//客人留言 点 设为已读
	case 'hidden_message':
		if((int)$_GET['message_id']){
			tep_db_query('update `orders_message` set has_read="1", read_admin_id="'.(int)$login_id.'",read_time="'.date('Y-m-d H:i:s').'" where `id`="'.(int)$_GET['message_id'].'" ');
			// by lwkai added 2013-07-31 检测非订单归属人点客人留言中设为已读 是否需要把当前点击人加入订单归属
			if (isset($_GET['orders_id'])) {
				require_once DIR_FS_CLASSES . 'vesting_orders.class.php';
				$vo = new vesting_orders();
				$admin_job_number = tep_get_admin_customer_name($login_id);
				$vo->confirm_message($admin_job_number, intval($_GET['message_id']), intval($_GET['orders_id']));
			}
			// by lwkai added 2013-07-31 结束
			$messageStack->add_session(db_to_html('已阅设置成功！'), 'success');
			tep_redirect(tep_href_link('orders_warning.php', tep_get_all_get_params(array('message_id', 'action')) .'action=flight_check' ));
		}
	break;
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
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
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">
function write_ref_id(ref_id, cus_id){
	if(document.getElementById('td_ref_' + cus_id)!=null){
		document.getElementById('td_ref_' + cus_id).focus();
	}
	var url = "<?php echo preg_replace($p,$r,tep_href_link('stats_order_analysis_ajax.php','action=1')) ?>" +"&customers_id="+ cus_id +"&customers_referer_type=" + ref_id;
	ajax.open("GET", url, true);
	ajax.send(null); 
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert('修改成功！');
		}		
	}
}
</script>
<script type="text/javascript"><!--

var Date_Reg_start = new ctlSpiffyCalendarBox("Date_Reg_start", "form_search", "reg_start_date","btnDate1","<?php echo ($reg_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Reg_end = new ctlSpiffyCalendarBox("Date_Reg_end", "form_search", "reg_end_date","btnDate2","<?php echo ($reg_end_date); ?>",scBTNMODE_CUSTOMBLUE);

var Date_Buy_start = new ctlSpiffyCalendarBox("Date_Buy_start", "form_search", "buy_start_date","btnDate3","<?php echo ($buy_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Buy_end = new ctlSpiffyCalendarBox("Date_Buy_end", "form_search", "buy_end_date","btnDate4","<?php echo ($buy_end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
		</table></td>
<!-- body_text //-->
	<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
			<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		</table></td>
	  </tr>
	  
	  <tr>
		<td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>

		<?php
		switch($_GET['action']){
			case 'CC_check':	//CC冻结
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		  <tr>
			<td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
			  <tr class="dataTableHeadingRow">
				<td class="dataTableHeadingContent" nowrap="nowrap">Order ID</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">Order Status</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">Date Purchased</td>
			  </tr>
<?php
  
  $orders_query =  tep_db_query($sql_str);
  while ($orders = tep_db_fetch_array($orders_query)) {
	$rows++;

	if (strlen($rows) < 2) {
	  $rows = '0' . $rows;
	}
	
	$bg_color = "#F0F0F0";
	if((int)$rows %2 ==0 && (int)$rows){
		$bg_color = "#ECFFEC";
	}
?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
				<td class="dataTableContent"><a href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&action=edit');?>"><?php echo $orders['orders_id']?></a></td>
				<td class="dataTableContent"><?php echo tep_get_orders_status_name($orders['orders_status']);?></td>
				<td class="dataTableContent"><?php echo substr($orders['date_purchased'],0,10)?></td>
			  </tr>
			  
<?php
  }
?>
			</table></td>
		  </tr>
		  <tr>
			<td colspan="3">&nbsp;</td>
		  </tr>
		</table>
		<?php
			break;
			case 'flight_check':	//航班信息更新
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		  <tr>
			<td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2" id="TableList">
			  <tr class="">
				<th class="" nowrap="nowrap">Order ID</th>
				<th class="" nowrap="nowrap">Products ID</th>
				<th class="" nowrap="nowrap">订单归属工号</th>
				<th class="" nowrap="nowrap">销售链接工号</th>
				<th class="" nowrap="nowrap">Airline Name</th>
				<th class="" nowrap="nowrap">出团时间</th>
				<th class="" nowrap="nowrap">Action</th>
			  </tr>
<?php
  
  $orders_query =  tep_db_query($sql_str);
  while ($orders = tep_db_fetch_array($orders_query)) {
	$rows++;

	if (strlen($rows) < 2) {
	  $rows = '0' . $rows;
	}
	
	$bg_color = "#F0F0F0";
	if((int)$rows %2 ==0 && (int)$rows){
		$bg_color = "#ECFFEC";
	}
?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
				<td class="dataTableContent"><a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&action=edit');?>"><?php echo $orders['orders_id']?></a></td>
				<td class="dataTableContent"><a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&products_id='.(int)$orders['products_id'].'&action=eticket&i=0');?>"><?php echo $orders['products_id'];?></a></td>
				<td class="dataTableContent"><b><a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&action=edit');?>"><?= $orders['orders_owners'];?></a></b></td>
				<td class="dataTableContent"><b><a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&action=edit');?>"><?= tep_get_order_owner_admin((int)$orders['orders_id']);?></a></b></td>
				<td class="dataTableContent"><?php echo tep_db_output($orders['airline_name']);?></td>
				<td class="dataTableContent"><?php echo tep_get_date_of_departure($orders['orders_id'])?></td>
				<td class="dataTableContent">
				<a href="<?php echo tep_href_link('orders_warning.php','action=hidden_flight&orders_flight_id='.(int)$orders['orders_flight_id'])?>">[关闭]</a>
				&nbsp;&nbsp;
				<a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$orders['orders_id'].'&action=edit');?>">[详细]</a>
				</td>
			  </tr>
			  
<?php
  }
?>
			<tr><td colspan="7" style="text-align:left">
			<?php //未读留言订单列表start{?>
			以下订单有未读留言：</td></tr>
			<?php
			$sql_str = 'SELECT om.orders_id, o.orders_owners FROM `orders_message` as om,`orders` as o WHERE om.orders_id = o.orders_id and om.has_read!="1" order by om.`id` ';
			$sql_query = tep_db_query($sql_str);
			while($rows = tep_db_fetch_array($sql_query)){
				echo '<tr><td><a target="_blank" href="'.tep_href_link('edit_orders.php','oID='.(int)$rows['orders_id'].'&action=edit').'">['.$rows['orders_id'].']</a></td>
		  		<td>&nbsp;</td>';
		  		echo '<td><b><a target="_blank" href="' . tep_href_link('edit_orders.php','oID='.(int)$rows['orders_id'].'&action=edit') . '">' . $rows['orders_owners'] . '</a></b></td><td>&nbsp;</td><td>&nbsp;</td>
		  		<td>';
		  		echo tep_get_date_of_departure($rows['orders_id']);
		  		echo '</td><td>&nbsp;</td></tr>
		  		';
			}
			?>
			
			
			<?php //未读留言订单列表end}?>
			</table>
			</td>
		  </tr>
		</table>
		<?php 
			break;
		}
		?>
		</fieldset>
		</td>
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
