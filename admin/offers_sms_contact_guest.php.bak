<?php
set_time_limit(0);
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('offers_sms_contact_guest');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

/* admin_files表 里面增加  offers_sms_contact_guest.php 0 5 1,28  */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/jquery-1.3.2/merger.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/javascript/calendar.js"></script>

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('offers_sms_contact_guest');
$list = $listrs->showRemark();
?>
<?php
if(!tep_not_null($_GET['action']) || $_GET['action']=='filter'){
	$phone = '';
	if($_GET['action']=='filter'){
		$status = 0;
		$where = '';
		//用户名
		if(tep_not_null($_POST['customers_email_address'])){
			$status = 1;
			$where .= " and customers_email_address like '".trim($_POST['customers_email_address'])."%'";
		}
		/*//注册时间
		if(tep_not_null($_POST['customers_info_date_account_created1']) && tep_not_null($_POST['customers_info_date_account_created2'])){
			$status = 1;
			$where .= " and customers_id in (select customers_info_id from customers_info where customers_info_date_account_created>='".trim($_POST['customers_info_date_account_created1'])."' and customers_info_date_account_created<='".trim($_POST['customers_info_date_account_created2'])."')";
		}*/
		/*//所在区域
		if(tep_not_null($_POST['country'])){
			$status = 1;
			$where .= " and customers_id in (select customers_id from address_book where entry_country_id=".trim($_POST['country']);
			if(tep_not_null($_POST['state'])){
				$where .= " and entry_state='".trim($_POST['state'])."'";
				if(tep_not_null($_POST['city'])){
					$where .= " and entry_city='".trim($_POST['city'])."'";
				}
			}
			$where .= ")";
		}*/
		/*//订单数量
		if(tep_not_null($_POST['order_num1']) && tep_not_null($_POST['order_num2'])){
			$status = 1;
			$where .= " and customers_id in (select customers_id from orders group by customers_id having count(customers_id)>=".trim($_POST['order_num1'])." and count(customers_id)<=".trim($_POST['order_num2']).")";
		}*/
		/*//下单时间
		if(tep_not_null($_POST['order_time1']) && tep_not_null($_POST['order_time2'])){
			$status = 1;
			$where .= " and customers_id in (select customers_id from orders where date_purchased>='".trim($_POST['order_time1'])."' and date_purchased<='".trim($_POST['order_time2'])."')";
		}*/
		/*//订单金额
		if(tep_not_null($_POST['order_money1']) && tep_not_null($_POST['order_money2'])){
			$customers_id = '';
			$order_money_sql = 'select customers_id from customers where 1'.$where;
			$order_money_query = tep_db_query($order_money_sql);
			while($order_money_result = tep_db_fetch_array($order_money_query)){
				$sub_order_money_sql = "select sum(value) as sum_value from orders_total where class='ot_total' and orders_id in (select orders_id from orders where customers_id=".$order_money_result['customers_id'].")";
				$sub_order_money_query = tep_db_query($sub_order_money_sql);
				$sub_order_money_result = tep_db_fetch_array($sub_order_money_query);
				if($sub_order_money_result['sum_value']>=trim($_POST['order_money1']) && $sub_order_money_result['sum_value']<=trim($_POST['order_money2'])){
					$customers_id .= ','.$order_money_result['customers_id'];
				}
			}
			if(tep_not_null($customers_id)){
				$customers_id = substr($customers_id, 1);
			}
		}*/
		//出发日期
		if(tep_not_null($_POST['dept_date']) && tep_not_null($_POST['dept_date'])){
			$status = 1;
			//以下写法极费cpu
			//$where .= " and customers_id in (select o.customers_id from orders o, orders_products op where op.orders_id = o.orders_id AND op.products_departure_date ='".date('Y-m-d H:i:s', strtotime($_POST['dept_date']))."' group by o.customers_id )";
			$tmp_sql = tep_db_query("select o.customers_id from orders o, orders_products op where op.orders_id = o.orders_id AND op.products_departure_date ='".date('Y-m-d H:i:s', strtotime($_POST['dept_date']))."' group by o.customers_id");
			$_cids = array(0);
			while($tmp_rows = tep_db_fetch_array($tmp_sql)){
				$_cids[] = (int)$tmp_rows['customers_id'];
			}
			
			$where .= " and customers_id in (".implode(',', $_cids).") ";
			
		}
		
		if($status){
			$sql = '';
			if(tep_not_null($customers_id)){
				$sql = 'select confirmphone, customers_cellphone, customers_mobile_phone, customers_telephone from customers where customers_id in ('.$customers_id.')';
			}
			else{
				$sql = 'select confirmphone, customers_cellphone, customers_mobile_phone, customers_telephone from customers where 1'.$where;
			}
			//echo $sql;exit;
			$row_customer_query = tep_db_query($sql);
			while($row_customer_info = tep_db_fetch_array($row_customer_query)){
				$result_phone = check_phone($row_customer_info['confirmphone']);
				if (!empty($result_phone))$phone .= ','.$result_phone[0];
				else {
					$result_phone = check_phone($row_customer_info['customers_cellphone']);
					if (!empty($result_phone))$phone .= ','.$result_phone[0];
					else {
						$result_phone = check_phone($row_customer_info['customers_mobile_phone']);
						if (!empty($result_phone))$phone .= ','.$result_phone[0];
						else {
							$result_phone = check_phone($row_customer_info['customers_telephone']);
							if (!empty($result_phone))$phone .= ','.$result_phone[0];
						}
					}
				}
			}
			if(tep_not_null($phone)){
				$phone = substr($phone, 1);
			}
		}
	}
?>
<table border="0" width="60%" cellspacing="2" cellpadding="2">
  <tr><td align="center" style="font-size:24px;"><br><b><?php echo db_to_html('短信联系客户'); ?></b><br></td></tr>
</table>
<form action="offers_sms_contact_guest.php?action=filter" method="post" name="name">
<table border="0" width="100%" cellspacing="10" cellpadding="2">
  <tr><td class="col_b1">用户email账号：<input name="customers_email_address" value="<?php echo $_POST['customers_email_address']?>" size="50">&nbsp; 注：注册时的email地址</td></tr>
  <?php if($can_top_sms_contact_guest === true){?>
  <tr><td class="col_b1">注册时间：<input name="customers_info_date_account_created1" value="<?php echo $_POST['customers_info_date_account_created1']?>" size="15"> - <input name="customers_info_date_account_created2" value="<?php echo $_POST['customers_info_date_account_created2']?>" size="15">&nbsp; 注：用户的注册时间，格式yyyy-mm-dd</td></tr>
  <tr><td class="col_b1">所在区域：<span id="defaultAddress"><?php echo tep_draw_full_address_input('defaultAddress', $_POST['country'], $_POST['state'], $_POST['city']);?></span>&nbsp; 注：用户所在的区域</td></tr>
  <tr><td class="col_b1">订单数量：<input name="order_num1" value="<?php echo $_POST['order_num1']?>" size="10"> - <input name="order_num2" value="<?php echo $_POST['order_num2']?>" size="10">&nbsp; 注：用户的订单总数</td></tr>
  <tr><td class="col_b1">订单金额：$<input name="order_money1" value="<?php echo $_POST['order_money1']?>" size="10"> - $<input name="order_money2" value="<?php echo $_POST['order_money2']?>" size="10">&nbsp; 注：用户的订单总金额，如：879.63</td></tr>
  <tr><td class="col_b1">下单时间：<input name="order_time1" value="<?php echo $_POST['order_time1']?>" size="15"> - <input name="order_time2" value="<?php echo $_POST['order_time2']?>" size="15">&nbsp; 注：用户的下单时间，格式yyyy-mm-dd</td></tr>
  <?php }?>
  
  <tr><td class="col_b1">出团日期：<input name="dept_date" value="<?php echo $_POST['dept_date']?>" size="15" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);">
  	&nbsp; 注：格式yyyy-mm-dd</td></tr>
  
  <tr><td class="col_b1"><input type="submit" value=' 筛 选 ' /></td></tr>
</table>
<script type="text/javascript">
//自动区分https和http取值
function url_ssl(url){
	var SSL_ = false;
	if(document.URL.search(/^https:\/\//)>-1){
		SSL_ = true;
	}
	var new_url = url;
	if(SSL_==true){
		new_url = url.replace(/^http:\/\//,"https://");
	}else{
		new_url = url.replace(/^https:\/\//,"http://");
	}
	return new_url;
}
<?php echo tep_draw_full_address_input_js()?>
</script>
</form>

<table border="0" width="66%" cellspacing="0" cellpadding="0">
  <tr><td colspan="2" height="8"></td></tr>
  <tr><td width="10"></td><td><hr></td></tr>
</table>

<form action="offers_sms_contact_guest.php?action=send" method="post" name="name">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr><td width="10"></td><td class="col_b1">手机号码：<textarea rows="3" name="phone" cols="80"><?php echo $phone;?></textarea>&nbsp;注：多个号码用英文逗号分开</td></tr>
  <tr><td colspan="2" height="12"></td></tr>
  <tr><td width="10"></td><td class="col_b1">短信内容：<textarea rows="4" name="content" cols="80"></textarea>&nbsp;注：内容末尾已默认添加“【走四方网】”<br><br></td></tr>
  <tr><td width="10"></td><td class="col_b1"><input type="submit" value=' 发 送 ' /></td></tr>
  </table>
</form>
<?php
}
elseif(preg_match('/'.preg_quote('[短信联系客户]').'/',CPUNC_USE_RANGE)){
	$phone_array = check_phone($_POST['phone']);
	if(count($phone_array)<1){
		echo '<script type="text/javascript">alert("输入的手机号码有误，多个请用英文逗号分开！");location.href="offers_sms_contact_guest.php";</script>';
	}
	elseif(!tep_not_null($_POST['content'])){
		echo '<script type="text/javascript">alert("短信内容不能为空！");location.href="offers_sms_contact_guest.php";</script>';
	}
	else{
		$content = $_POST['content'];
		$str_phone_success = '';
		$str_phone_fail = '';
		include('sms_send.php');
		foreach($phone_array as $phone){
		    if(sms_send($phone,$content)=='1'){
		    	$str_phone_success .= $phone.',';
			}
			else{
				$str_phone_fail .= $phone.',';
			}
		}
		$alert_str = '';
		if(tep_not_null($str_phone_success)){
			$alert_str = substr($str_phone_success, 0, -1)."的短信发送成功！";
			if(tep_not_null($str_phone_fail))$alert_str .= "\n";
		}
		if(tep_not_null($str_phone_fail)){
			$alert_str .= substr($str_phone_fail, 0, -1)."的短信发送失败！";
		}
		echo '<script type="text/javascript">alert("'.$alert_str.'");location.href="offers_sms_contact_guest.php";</script>';
	}
}
else{
	echo '<script type="text/javascript">alert("此短信功能已关闭，如需使用请先打开！");location.href="offers_sms_contact_guest.php";</script>';
}
?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>