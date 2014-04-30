<?php
/*
  $Id: affiliate_payment.php,v 1.1.1.1 2004/03/04 23:38:09 ccwjr Exp $

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
	$remark = new Remark('affiliate_payment');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require(DIR_WS_CLASSES . 'currencies.php');

$currencies = new currencies();

$status = isset($_GET['status']) ? (int)tep_db_prepare_input($_GET['status']) : '0';
$showdel = isset($_GET['showdel']) ? $_GET['showdel'] : '';

//amit added to fileter 
if (isset($_GET['aff_filter']) && $_GET['aff_filter'] != '' && $_GET['aff_filter'] != '0') {
	$extra_aff_fileter_where = " and a.affiliate_homepage != '' ";
	//$extra_aff_fileter_select = " a.affiliate_homepage, ";
}
  
$payments_statuses = array(); 
$payments_status_array = array();
$payments_status_query = tep_db_query("select affiliate_payment_status_id, affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT_STATUS . " where affiliate_language_id = '" . $languages_id . "'");
while ($payments_status = tep_db_fetch_array($payments_status_query)) {
	$payments_statuses[] = array(
		'id'   => $payments_status['affiliate_payment_status_id'],
		'text' => $payments_status['affiliate_payment_status_name']
	);
	$payments_status_array[$payments_status['affiliate_payment_status_id']] = $payments_status['affiliate_payment_status_name'];
}

switch ($_GET['action']) {
	case 'start_billing':
		// Billing can be a lengthy process
	  	tep_set_time_limit(0);
	  	//AFFILIATE_BILLING_TIME常量已经无效，不再用了		
		
		//Sofia要求本月计上月(1日到上月最后一天)的佣金，不管天数，只管月份！同时以订单最迟的产品出团日期为准 fixed by Howard 2013-01-10
		//原理：(1)先查本次生成的payment报表的affiliate_id(联盟成员id)数据；(2)根据这些成员id去取他们的未计入payment报表的推荐订单数值统计出哪些需要支付的
		$last_month_end_time = date('Y-m-t 23:59:59', strtotime('last month'));
		
		$_join_sql = "SELECT op.orders_id, MAX( op.products_departure_date ) AS products_departure_date	FROM ".TABLE_ORDERS_PRODUCTS." op where op.products_departure_date >= '".date('Y-m-d H:i:s',strtotime(AFFILIATE_LAST_SUCCESS_BILLING_TIME))."'  GROUP BY op.orders_id ";
		
 	 	$sql="SELECT a.affiliate_id, sum(a.affiliate_payment) FROM " . TABLE_AFFILIATE_SALES . " a, " . TABLE_ORDERS . " o   JOIN (".$_join_sql.") AS o22 ON o.orders_id = o22.orders_id
			WHERE a.affiliate_billing_status != 1 and a.affiliate_orders_id = o.orders_id and a.affiliate_isvalid = 1 and o.orders_status = '100006' and 
			o22.products_departure_date <= '".$last_month_end_time."'  GROUP by a.affiliate_id having sum(a.affiliate_payment) >= '" . AFFILIATE_THRESHOLD . "'";
		$affiliate_payment_query = tep_db_query($sql);

		// Start Billing:
		while ($affiliate_payment = tep_db_fetch_array($affiliate_payment_query)) {
		
			$sql="SELECT a.affiliate_orders_id  
				  FROM " . TABLE_AFFILIATE_SALES . " a, " . TABLE_ORDERS . " o   
				  JOIN (".$_join_sql.") AS o22 
				  ON o.orders_id = o22.orders_id
				  WHERE a.affiliate_billing_status!=1 and a.affiliate_orders_id=o.orders_id and a.affiliate_isvalid = 1  and o.orders_status = '100006' and 
				  	  		a.affiliate_id='" . $affiliate_payment['affiliate_id'] . "' and o22.products_departure_date <= '".$last_month_end_time."' ";
		
			$affiliate_orders_query = tep_db_query($sql);
			$orders_id ="(";
			while ($affiliate_orders = tep_db_fetch_array($affiliate_orders_query)) {
		  		$orders_id .= $affiliate_orders['affiliate_orders_id'] . ",";
			}
			$orders_id = substr($orders_id, 0, -1) .")";

			// Set the Sales to Temp State (it may happen that an order happend while billing)
			$sql="UPDATE " . TABLE_AFFILIATE_SALES . " 
				set affiliate_billing_status=99 
		  		where affiliate_id='" .  $affiliate_payment['affiliate_id'] . "' 
		  			and affiliate_orders_id in " . $orders_id . "";
			tep_db_query ($sql);

			// Get Sum of payment (Could have changed since last selects);
			$sql="
				SELECT sum(affiliate_payment) as affiliate_payment
		  		FROM " . TABLE_AFFILIATE_SALES . " 
		  		WHERE affiliate_id='" .  $affiliate_payment['affiliate_id'] . "' and  affiliate_billing_status=99";
			$affiliate_billing_query = tep_db_query ($sql);
			$affiliate_billing = tep_db_fetch_array($affiliate_billing_query);
			// Get affiliate Informations
			$sql="
				SELECT a.*, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id 
		  		from " . TABLE_AFFILIATE . " a 
		  		left join " . TABLE_ZONES . " z on (a.affiliate_zone_id  = z.zone_id) 
		  		left join " . TABLE_COUNTRIES . " c on (a.affiliate_country_id = c.countries_id)
		  		WHERE affiliate_id = '" . $affiliate_payment['affiliate_id'] . "' 
			";
			$affiliate_query=tep_db_query ($sql);
			$affiliate = tep_db_fetch_array($affiliate_query);

			//amit added to get customer info text start

			$customerdetails = tep_get_customers_info($affiliate_payment['affiliate_id']);

			$affiliate['affiliate_firstname'] = $customerdetails['customers_firstname'];
			$affiliate['affiliate_lastname'] = $customerdetails['customers_lastname'];
			$affiliate['affiliate_street_address'] = $customerdetails['entry_street_address'];
			$affiliate['affiliate_suburb'] = $customerdetails['entry_suburb'];
			$affiliate['affiliate_city'] = $customerdetails['entry_city'];
			$affiliate['countries_name'] = tep_get_country_name($customerdetails['entry_country_id']);
			$affiliate['affiliate_postcode'] = $customerdetails['entry_postcode'];
			$affiliate['affiliate_company'] = $customerdetails['entry_company'];
			$affiliate['affiliate_country_id'] = $customerdetails['entry_country_id'];
			$affiliate['affiliate_state'] = $customerdetails['entry_state'];
			$affiliate['affiliate_zone_id'] = $customerdetails['entry_zone_id'];
			if($affiliate['address_format_id'] == ''){
				$affiliate['address_format_id'] = '1';
			}
			//	amit added to get customer info text end

			// 	Get need tax informations for the affiliate
			$affiliate_tax_rate = tep_get_affiliate_tax_rate(AFFILIATE_TAX_ID, $affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id']);
			$affiliate_tax = tep_round(($affiliate_billing['affiliate_payment'] * $affiliate_tax_rate / 100), 2); // Netto-Provision
			$affiliate_payment_total = $affiliate_billing['affiliate_payment'] + $affiliate_tax;
			// Bill the order
			$affiliate['affiliate_state'] = tep_get_zone_code($affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id'], $affiliate['affiliate_state']);
			$sql_data_array = array(
				'affiliate_id' => $affiliate_payment['affiliate_id'],
				'affiliate_payment' => $affiliate_billing['affiliate_payment'],
				'affiliate_payment_tax' => $affiliate_tax,
				'affiliate_payment_total' => $affiliate_payment_total,
				'affiliate_payment_date' => 'now()',
				'affiliate_payment_status' => '0',
				'affiliate_firstname' => $affiliate['affiliate_firstname'],
				'affiliate_lastname' => $affiliate['affiliate_lastname'],
				'affiliate_street_address' => $affiliate['affiliate_street_address'],
				'affiliate_suburb' => $affiliate['affiliate_suburb'],
				'affiliate_city' => $affiliate['affiliate_city'],
				'affiliate_country' => $affiliate['countries_name'],
				'affiliate_postcode' => $affiliate['affiliate_postcode'],
				'affiliate_company' => $affiliate['affiliate_company'],
				'affiliate_state' => $affiliate['affiliate_state'],
				'affiliate_address_format_id' => $affiliate['address_format_id']
			);
			tep_db_perform(TABLE_AFFILIATE_PAYMENT, $sql_data_array);
			$insert_id = tep_db_insert_id(); 
			// Set the Sales to Final State 
			tep_db_query(
				"update " . TABLE_AFFILIATE_SALES . " 
				set affiliate_payment_id = '" . $insert_id . "', affiliate_billing_status = 1, affiliate_payment_date = now() 
				where affiliate_id = '" . $affiliate_payment['affiliate_id'] . "' and affiliate_billing_status = 99"
			);

			//Notify Affiliate
			if (AFFILIATE_NOTIFY_AFTER_BILLING == 'true') {
				$check_status_query = tep_db_query("select af.affiliate_email_address, ap.affiliate_lastname, ap.affiliate_firstname, ap.affiliate_payment_status, ap.affiliate_payment_date, ap.affiliate_payment_date from " . TABLE_AFFILIATE_PAYMENT . " ap, " . TABLE_AFFILIATE . " af where affiliate_payment_id  = '" . $insert_id . "' and af.affiliate_id = ap.affiliate_id ");
				$check_status = tep_db_fetch_array($check_status_query);
				$check_status['affiliate_email_address'] = $customerdetails['customers_email_address'];
				$email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_AFFILIATE_PAYMENT_INFO, 'payment_id=' . $insert_id, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['affiliate_payment_date']) . "\n\n" . EMAIL_TEXT_NEW_PAYMENT;
				//tep_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
			}
		}
		$messageStack->add_session(SUCCESS_BILLING, 'success');

		tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
		break;
	case 'update_payment':
		$pID = tep_db_prepare_input($_GET['pID']);
		$status = tep_db_prepare_input($_POST['status']);
		$comment = tep_db_prepare_input($_POST['comment']);
		$affiliate_paid_date = tep_db_prepare_input($_POST['affiliate_paid_date']);
		
		$payment_updated = false;
		$check_status_query = tep_db_query("select ap.affiliate_id, af.affiliate_email_address, ap.affiliate_lastname, ap.affiliate_firstname, ap.affiliate_payment_status, ap.affiliate_payment_date, ap.affiliate_payment_date, ap.comment from " . TABLE_AFFILIATE_PAYMENT . " ap, " . TABLE_AFFILIATE . " af where affiliate_payment_id = '" . tep_db_input($pID) . "' and af.affiliate_id = ap.affiliate_id ");
		$check_status = tep_db_fetch_array($check_status_query);
		//amit added to fixed notify start
		$check_status['affiliate_email_address'] = tep_get_customers_email($check_status['affiliate_id']);
		$check_status['affiliate_firstname']. $check_status['affiliate_lastname'];

		//amit added to fixed notify end
	  
		if ($check_status['affiliate_payment_status'] != $status || $check_status['comment'] != $comment) {
			
			tep_db_query("update " . TABLE_AFFILIATE_PAYMENT . " set affiliate_payment_status = '" . tep_db_input($status) . "', comment='" .tep_db_input($comment) . "', affiliate_last_modified = now(), affiliate_payment_last_modified = now(), affiliate_paid_date ='".tep_db_input($affiliate_paid_date)."' where affiliate_payment_id = '" . tep_db_input($pID) . "'");
			$affiliate_notified = '0';
			// Notify Affiliate
			if ($_POST['notify'] == 'on') {// 发送邮件通知
		  		$email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $pID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_AFFILIATE_PAYMENT_INFO, 'payment_id=' . $pID, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['affiliate_payment_date']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $payments_status_array[$status]);
		  		tep_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
		  		$affiliate_notified = '1';
			}
			//echo "insert into " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " (affiliate_payment_id, affiliate_new_value, affiliate_old_value, affiliate_date_added, affiliate_notified) values ('" . tep_db_input($pID) . "', '" . tep_db_input($status) . "', '" . $check_status['affiliate_payment_status'] . "', now(), '" . $affiliate_notified . "')";
			tep_db_query("insert into " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " (affiliate_payment_id, affiliate_new_value, affiliate_old_value, comment_new_value, comment_old_value, affiliate_date_added, affiliate_notified,update_by) values ('" . tep_db_input($pID) . "', '" . tep_db_input($status) . "', '" . $check_status['affiliate_payment_status'] . "','" . tep_db_input($comment) . "','" . tep_db_input($check_status['comment']) . "', now(), '" . $affiliate_notified . "','".$login_id."')");
			$order_updated = true;
	  	}

	  	if ($order_updated) {
	   		$messageStack->add_session(SUCCESS_PAYMENT_UPDATED, 'success');
	  	}

	  	tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
	  	break;
	case 'deleteconfirm':
		$pID = tep_db_prepare_input($_GET['pID']);
		$sql = "select affiliate_id,affiliate_orders_id from affiliate_sales where affiliate_payment_id='" . tep_db_input($pID) . "'";
		$result = tep_db_query($sql);
		$ids = array();
		while ($row = tep_db_fetch_array($result)) {
			$ids[] = array('affiliate_id' => $row['affiliate_id'], 'affiliate_orders_id' => $row['affiliate_orders_id']);
		}
		$ids = serialize($ids);
		//tep_db_query("delete from " . TABLE_AFFILIATE_PAYMENT . " where affiliate_payment_id = '" . tep_db_input($pID) . "'");
		//tep_db_query("delete from " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " where affiliate_payment_id = '" . tep_db_input($pID) . "'");
		tep_db_query("update " . TABLE_AFFILIATE_PAYMENT . " set is_delete='1',count_ids='" . $ids . "',delete_admin_id='" . $login_id . "',delete_date='" . date('Y-m-d H:i:s') . "' where affiliate_payment_id='" . tep_db_input($pID) . "'");
		tep_db_query("update " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " set is_delete='1' where affiliate_payment_id='" . tep_db_input($pID) . "'");
		tep_db_query("update affiliate_sales set affiliate_payment_id = 0,affiliate_billing_status=0 where affiliate_payment_id='" . tep_db_input($pID) . "'");
		tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action'))));
		break;
}

if (($_GET['action'] == 'edit') && tep_not_null($_GET['pID'])) {
	$pID = tep_db_prepare_input($_GET['pID']);
	$payments_query = tep_db_query("select p.*,  a.affiliate_payment_check, a.affiliate_payment_paypal, a.affiliate_payment_bank_name, a.affiliate_payment_bank_branch_number, a.affiliate_payment_bank_swift_code, a.affiliate_payment_bank_account_name, a.affiliate_payment_bank_account_number,affiliate_default_payment_method from " .  TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE . " a where affiliate_payment_id = '" . tep_db_input($pID) . "' and a.affiliate_id = p.affiliate_id");
	$payments_exists = true;
	if (!$payments = tep_db_fetch_array($payments_query)) {
		$payments_exists = false;
		$messageStack->add(sprintf(ERROR_PAYMENT_DOES_NOT_EXIST, $pID), 'error');
	}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
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
#small_menu li.curr a{color:#1436bd;width:225px;}
#small_menu li.curr a:hover{color:#1436bd;cursor:default;background:none;}
#small_menu li a{display:block;width:150px;height:25px;line-height:25px;text-align:center;}
#small_menu li a{text-decoration:none;}
#small_menu li a:hover{background:#00ABED;color:#fff;}
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
$listrs = new Remark('affiliate_payment');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
if ( ($_GET['action'] == 'edit') && ($payments_exists) ) {
	$affiliate_address['firstname'] = $payments['affiliate_firstname'];
	$affiliate_address['lastname'] = $payments['affiliate_lastname'];
	$affiliate_address['street_address'] = $payments['affiliate_street_address'];
	$affiliate_address['suburb'] = $payments['affiliate_suburb'];
	$affiliate_address['city'] = $payments['affiliate_city'];
	$affiliate_address['state'] = $payments['affiliate_state'];
	$affiliate_address['country'] = $payments['affiliate_country'];
	$affiliate_address['postcode'] = $payments['affiliate_postcode'];
	?>
	<tr>
		<td width="100%">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					<td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td class="main" valign="top"><b title="<?php echo TEXT_AFFILIATE; ?>">姓名：<br/><br/>地址：</b></td>
								<td class="main"><?php echo tep_address_format($payments['affiliate_address_format_id'], $affiliate_address, 1, '&nbsp;', '<br>'); ?></td>
							</tr>
							<tr>
								<td class="main"><b title="E-mail">邮箱：</b></td>
								<td class="main"><?php echo tep_get_customers_email($payments['affiliate_id'])?></td>
							</tr>
							<tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							</tr>
							<tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							</tr>
							<tr>
								<td class="main"><b title="<?php echo TEXT_AFFILIATE_PAYMENT.' (excl.)'; ?>">支付金额：</b></td>
								<td class="main">&nbsp;<?php 
									// echo $currencies->format($payments['affiliate_payment_total']); 
									echo $currencies->format($payments['affiliate_payment']); 
									?>
								</td>
							</tr>
							<tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							</tr>
							<tr>
								<td class="main"><b title="<?php echo TEXT_AFFILIATE_BILLED; ?>">记录日期：</b></td>
								<td class="main">&nbsp;<?php echo tep_date_short($payments['affiliate_payment_date']); ?></td>
							</tr>
							<tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							</tr>
							<tr>
								<td class="main" valign="top" style="font-weight:700;" title="<?php echo TEXT_AFFILIATE_PAYING_POSSIBILITIES; ?>">支付途径:</td>
								<td class="main">
									<table border="1" cellspacing="0" cellpadding="5">
										<tr>
											<?php
											if (AFFILIATE_USE_BANK == 'true' && $payments['affiliate_default_payment_method'] == 'Bank') {
											?>
											<td class="main"  valign="top">
												<?php 
												echo '<b title="' . TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER . '">银行汇款</b>';
												echo '<p style="margin:0;padding:5px 0;"><span title="' . TEXT_AFFILIATE_PAYMENT_BANK_NAME . '">开户银行:</span> ' . $payments['affiliate_payment_bank_name'] . '</p>';
												echo '<p style="display:none"><span title="' . TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER . '">付款银行分行号码: </span>' . $payments['affiliate_payment_bank_branch_number'] . '</p>';
												echo '<p style="display:none"><span>' . TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE . '</span> ' . $payments['affiliate_payment_bank_swift_code'] . '</p>';
												echo '<p style="margin:0;padding:5px 0;"><span title="' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME . '">收款人姓名:</span> ' . $payments['affiliate_payment_bank_account_name'] . '</p>';
												echo '<p style="margin:0;padding:5px 0;"><span title="' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER . '">银行帐号:</span> ' . $payments['affiliate_payment_bank_account_number'] . '</p>'; ?>
											</td>
											<?php
											}
											if (AFFILIATE_USE_PAYPAL == 'true' && $payments['affiliate_default_payment_method'] == 'Paypal') {
											?>
											<td class="main"  valign="top">
												<?php 
												echo '<p style="margin:0;padding:5px 0;font-weight:700;" title="' . TEXT_AFFILIATE_PAYMENT_PAYPAL . '">PayPal:</p>';
												echo '<p style="margin:0;padding:5px 0;" title="' . TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL . '">帐号:' . $payments['affiliate_payment_paypal'] . '</p>';
												echo '<p style="margin:0;padding:5px 0;">姓名:' . $payments['affiliate_payment_check'] . '</p>';
												?>
											</td>
											<?php
											}
											if (AFFILIATE_USE_CHECK == 'true' && $payments['affiliate_default_payment_method'] == 'Alipay') {
											?>
											<td class="main"  valign="top">
												<?php echo '<p>' . TEXT_AFFILIATE_PAYMENT_CHECK . '</b><br><br>' . TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE . '<br>' . $payments['affiliate_payment_check'] . '<br>'; ?>
											</td>
											<?php
											}
											?>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td>
			<?php echo tep_draw_form('status', FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=update_payment'); ?>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td class="main">
									<b title="<?php echo PAYMENT_STATUS; ?>">支付状态：</b> <?php echo tep_draw_pull_down_menu('status', $payments_statuses, $payments['affiliate_payment_status'], 'onChange="if(this.value==1){ $(\'#paid_date\').show(); }else{ $(\'#paid_date\').hide(); }" '); ?>
								</td>
							</tr>
							<tr id="paid_date" style=" <?= $payments['affiliate_payment_status']=='1' ? '': 'display:none'?> ">
								<td class="main">
									<b>支付日期：</b> <?php echo tep_draw_input_num_en_field('affiliate_paid_date',($payments['affiliate_paid_date']=='0000-00-00' ? '' : $payments['affiliate_paid_date']), 'onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"' );?>
								</td>
							</tr>
							<tr>
								<td class="main">
								<b title="备注">备&nbsp;&nbsp;&nbsp;&nbsp;注：</b><?php echo tep_draw_textarea_field('comment', '', '25', '5',$payments['comment'],'',false)?>
								</td>
							</tr>
							<tr>
								<td class="main"><b title="<?php echo PAYMENT_NOTIFY_AFFILIATE; ?>">发送邮件通知</b><?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
							</tr>
						</table>
					</td>
					<td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main">
			<table border="1" cellspacing="0" cellpadding="5">
				<tr>
					<td class="smallText" align="center"><b title="<?php echo TABLE_HEADING_NEW_VALUE; ?>">新的状态</b></td>
					<td class="smallText" align="center"><b title="<?php echo TABLE_HEADING_OLD_VALUE; ?>">旧的状态</b></td>
					<td class="smallText" align="center"><b>新的备注</b></td>
					<td class="smallText" align="center"><b>旧的备注</b></td>
					<td class="smallText" align="center"><b title="<?php echo TABLE_HEADING_DATE_ADDED; ?>">操作日期</b></td>
					<td class="smallText" align="center"><b title="<?php echo TABLE_HEADING_UPDATED; ?>">操作人工号</b></td>
					<td class="smallText" align="center"><b title="<?php echo TABLE_HEADING_AFFILIATE_NOTIFIED; ?>">发送了通知邮件</b></td>
				</tr>
				<?php
				$affiliate_history_query = tep_db_query("select affiliate_new_value, affiliate_old_value,comment_new_value,comment_old_value, update_by, affiliate_date_added, affiliate_notified from " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " where affiliate_payment_id = '" . tep_db_input($pID) . "' order by affiliate_status_history_id desc");
				if (tep_db_num_rows($affiliate_history_query)) {
					while ($affiliate_history = tep_db_fetch_array($affiliate_history_query)) {
						echo '		  <tr>' . "\n" .
					'			<td class="smallText">' . $payments_status_array[$affiliate_history['affiliate_new_value']] . '</td>' . "\n" .
					'			<td class="smallText">' . (tep_not_null($affiliate_history['affiliate_old_value']) ? $payments_status_array[$affiliate_history['affiliate_old_value']] : '&nbsp;') . '</td>' . "\n" .
					'			<td class="smallText">' . tep_db_output($affiliate_history['comment_new_value']) . '&nbsp;</td>' .  "\n" .
					'			<td class="smallText">' . tep_db_output($affiliate_history['comment_old_value']) . '&nbsp;</td>' . "\n"  .
					'			<td class="smallText" align="center">' . $affiliate_history['affiliate_date_added'] . '</td>' . "\n" .
					'			<td class="smallText" align="center">' . tep_get_admin_customer_name($affiliate_history['update_by']) . '</td>' . "\n" .
					'			<td class="smallText" align="center">';
						if ($affiliate_history['affiliate_notified'] == '1') {
							echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
						} else {
							echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
						}
						echo '		  </tr>' . "\n";
					}
				} else {
					echo '		  <tr>' . "\n" .
						'			<td class="smallText" colspan="4">' . TEXT_NO_PAYMENT_HISTORY . '</td>' . "\n" .
						'		  </tr>' . "\n";
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_INVOICE, 'pID=' . $_GET['pID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
		</td>
	</tr>
	<?php
} else {
//列表开始
	?>
	<tr>
		<td width="100%">
		<fieldset>
			<legend style="text-align:left">工具栏</legend>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading">销售联盟（支付报表）</td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					<td class="pageHeading">
					<?php echo '<input type="button" onClick="location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, 'pID=' . $pInfo->affiliate_payment_id. '&action=start_billing' ) . '\'" title="建议每月最多点一次即可，不用天天点！" value="生成需要支付的报表" style="height:25px;"/>'; ?><span style="font-size:12px;color:red">只针对本月之前(不含本月)已出团的订单，并且每个联盟成员所有未支付佣金的订单总金额合计大于指定值的（全站配置中配置的最小支付值）当前是[<?php echo AFFILIATE_THRESHOLD?>]；若订单有多个产品则以最后的产品出发日期为准。</span></td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="pageHeading">&nbsp;</td>
					<td class="pageHeading" align="right">&nbsp;</td>
					<td class="pageHeading">
		<?php 
			echo tep_draw_form('orders', FILENAME_AFFILIATE_PAYMENT, '', 'get'); 
			echo '<span title="' . HEADING_TITLE_SEARCH . '">搜索姓名：</span> ',
				tep_draw_input_field('sID', '', 'size="12"') ,
				tep_draw_hidden_field('action', 'edit');
				echo '&nbsp;&nbsp;支付开始日期：';
				echo tep_draw_input_field('start_date',$_GET['start_date'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');
				echo '支付结束日期：';
				echo tep_draw_input_field('end_date',$_GET['end_date'], ' onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime" ');
			if ($showdel != '') {
				echo tep_draw_hidden_field('showdel',$showdel);
			}
			if (isset($_GET['status'])) {
				echo tep_draw_hidden_field('status',$_GET['status']);
			} 
		?><input type="submit" value="搜索"/>&nbsp;&nbsp;
		<?php
		if (tep_not_null($_GET['sID']) || tep_not_null($_GET['start_date']) || tep_not_null($_GET['end_date'])) { ?> 
		<a class="a_btn" href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('sID','start_date','end_date'))?>">清除搜索条件</a>
		<?php } ?>
			</form>
					</td>
					<td class="pageHeading" align="right">&nbsp;</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
		<legend style="text-align:left">统计信息</legend>
		<?php
			//上月和本月已支付佣金总额
			$public_sql = 'SELECT sum(affiliate_payment_total) as total FROM `affiliate_payment` where 1 and affiliate_payment_status="1" ';
			//本月
			$this_month = date('Y-m');
			$this_month_where = ' and affiliate_paid_date Like "'.$this_month.'%" ';
			$sumsql = tep_db_query($public_sql.$this_month_where);
			$this_month_row = tep_db_fetch_array($sumsql);
			//上月
			$last_month = date('Y-m',strtotime('last month'));
			$last_month_where = ' and affiliate_paid_date Like "'.$last_month.'%" ';
			$sumsql = tep_db_query($public_sql.$last_month_where);
			$last_month_row = tep_db_fetch_array($sumsql);
			?>
		<div class="pageHeading">本月已付佣金：$<?php echo number_format($this_month_row['total'],2,'.','');?>&nbsp;&nbsp;&nbsp;&nbsp;上月已付佣金：$<?php echo number_format($last_month_row['total'],2,'.','');?>&nbsp;&nbsp;&nbsp;&nbsp;本月=<?php echo date("Y年m月")?></div>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td>
		<?php 
		if (isset($_GET['acID']) && $_GET['acID'] != '' ){
			$addwhere = " and  p.affiliate_id =  '" . $_GET['acID'] . "' ";
		}
		$sortorder = ' order by p.affiliate_payment_id desc ';
		//amit added to make order status start
		switch ($_GET["sorts"]) {
			case 'lname':
				if ($_GET["order"] == 'ascending') {
					$sortorder = ' order by p.affiliate_lastname  asc';
				} else {
					$sortorder = ' order by p.affiliate_lastname  desc';
				}
				break;
			case 'fname':
				if ($_GET["order"] == 'ascending') {
					$sortorder = ' order by p.affiliate_firstname  asc';
				} else {
					$sortorder = ' order by p.affiliate_firstname  desc';
				}
				break;
			case 'abdate':
				if	($_GET["order"] == 'ascending') {
					$sortorder = ' order by p.affiliate_payment_date   asc';
				} else {
					$sortorder = ' order by p.affiliate_payment_date  desc';
				}
				break;
			case 'pstatus':
				if ($_GET["order"] == 'ascending') {
					$sortorder = ' order by s.affiliate_payment_status_name   asc';
				} else {
					$sortorder = ' order by s.affiliate_payment_status_name  desc';
				}
				break;
			case 'payment':
				if ($_GET["order"] == 'ascending') {
					$sortorder = ' order by p.affiliate_payment   asc';
				} else {
					$sortorder = ' order by p.affiliate_payment  desc';
				}
				break;
			default:
				if ($_GET["order"] == 'ascending') {
					$sortorder = ' order by p.affiliate_payment_id asc';
				} else {
					$sortorder = ' order by p.affiliate_payment_id desc ';
				}
				break;
		}
		//amit added to make order status end
		
		//".$extra_aff_fileter_where."   p.affiliate_id
		
		if ((isset($_GET['sID']) && !empty($_GET['sID'])  && $_GET['sID'] != '') || (isset($_GET['start_date']) && !empty($_GET['start_date']) && $_GET['start_date'] != '') || (isset($_GET['end_date']) && !empty($_GET['end_date']) && $_GET['end_date'] != '')) {
			// Search only payment_id by now
			$sID = tep_db_prepare_input($_GET['sID']);
			$start_date = tep_db_prepare_input($_GET['start_date']);
			$end_date = tep_db_prepare_input($_GET['end_date']);
		
			$payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s, " . TABLE_AFFILIATE . " a where p.affiliate_id=a.affiliate_id " . $extra_aff_fileter_where;
			if (isset($_GET['sID']) && $_GET['sID'] != '' && !empty($_GET['sID'])) {
				$payments_query_raw .= " and p.affiliate_firstname like '%" . tep_db_input($sID) . "%' ";
			}
			if (isset($_GET['start_date']) && $_GET['start_date'] != '' && !empty($_GET['start_date'])) {
				$payments_query_raw .= " and p.affiliate_paid_date>='" . $start_date . "' ";
			}
			if (isset($_GET['end_date']) && $_GET['end_date'] != '' && !empty($_GET['end_date'])) {
				$payments_query_raw .= " and p.affiliate_paid_date<='" . $end_date . "' ";
			}
			$payments_query_raw .= $addwhere." and p.affiliate_payment_status = s.affiliate_payment_status_id";
			if (isset($_GET['status']) && $showdel != 'true') {
				$payments_query_raw .= " and s.affiliate_payment_status_id = '" . tep_db_input($status) . "'";
			}
			if ($showdel == 'true') {
				$payments_query_raw .= " and p.is_delete=1 ";
			} else {
				$payments_query_raw .= " and p.is_delete=0 ";
			}
			$payments_query_raw .= " and s.affiliate_language_id = '" . $languages_id . "' ".$sortorder." ";
		} elseif (is_numeric($status) && $showdel != 'true') {//is_numeric($_GET['status'])
			//$status = tep_db_prepare_input($_GET['status']);
			$payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s, " . TABLE_AFFILIATE . " a where p.affiliate_id=a.affiliate_id  ".$extra_aff_fileter_where." and s.affiliate_payment_status_id = '" . tep_db_input($status) . "' ".$addwhere." and p.affiliate_payment_status = s.affiliate_payment_status_id and p.is_delete=0 and s.affiliate_language_id = '" . $languages_id . "' ".$sortorder."";
		} elseif ($showdel == 'true') {
			$payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s, " . TABLE_AFFILIATE . " a where p.affiliate_id=a.affiliate_id ".$extra_aff_fileter_where." and p.is_delete=1 and p.affiliate_payment_status = s.affiliate_payment_status_id ".$addwhere." and s.affiliate_language_id = '" . $languages_id . "' ".$sortorder."";
		}

		$count_sql = explode('from',$payments_query_raw);
		$count_sql = "select sum(p.affiliate_payment) as payment_total from " . $count_sql[1];
		$count_sql = preg_replace('/group\s+by\s+[^\s]+/im', '', $count_sql);
		$payment_total = tep_db_query($count_sql);
		$payment_total = tep_db_fetch_array($payment_total);
		$payment_total = $payment_total['payment_total'];
			
		$payments_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $payments_query_raw, $payments_query_numrows);
		//print_r($payments_query_raw);
		$payments_query = tep_db_query($payments_query_raw);
		?>
			<ul id="small_menu" class="uifix">
				<li <?php if ($status == '0' && $showdel<>'true' ) {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('status'=>'0'),array('x','y','showdel'))?>">未付款
			<?php 
			if ($status == '0' && $showdel<>'true') {
				echo '&nbsp;<span style="color:#f00">(总额:' . $currencies->format(strip_tags($payment_total)) . ')</span>';
			}
			?>
			</a></li>
				<li <?php if ($status == '1' && $showdel<>'true') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('status'=>'1'),array('x','y','showdel'))?>">已付款
			<?php 
			if ($status == '1' && $showdel<>'true') {
				echo '&nbsp;<span style="color:#f00">(总额:' . $currencies->format(strip_tags($payment_total)) . ')</span>';
			}
			?>
			</a></li>
				<li <?php if ($status == '2' && $showdel != 'true') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('status'=>'2'),array('x','y','showdel'))?>">联系不上
			<?php
			if ($status == '2' && $showdel != 'true') {
echo '&nbsp;<span style="color:#f00">(总额:' . $currencies->format(strip_tags($payment_total)) . ')</span>';
			}
			?>
			</a></li>
				<li <?php if ($showdel == 'true') {
				echo ' class="curr"';
			}?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('showdel'=>'true'),array('x','y','status'))?>">已删除
				<?php 		if ($showdel == 'true') {
echo '&nbsp;<span style="color:#f00">(总额:' . $currencies->format(strip_tags($payment_total)) . ')</span>';
			}?>
			</a></li>
			</ul>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
						<table width="100%" id="TableList">
							<tr class="dataTableHeadingRow">
								<th> 
									<?php /*echo TABLE_HEADING_LASTNAME .'</br><a href="' . $_SERVER['PHP_SELF'] . '?sorts=lname&order=ascending'.(isset($_GET['acID']) ? '&acID=' . $_GET['acID'] . '' : '').(isset($_GET['aff_filter']) ? '&aff_filter=' . $_GET['aff_filter'] . '' : '').'"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sorts=lname&order=decending'.(isset($_GET['acID']) ? '&acID=' . $_GET['acID'] . '' : '').(isset($_GET['aff_filter']) ? '&aff_filter=' . $_GET['aff_filter'] . '' : '').'">&nbsp;<img src="images/arrow_down.gif" border="0"></a>';*/
										echo '序号'; 
									?>
								</th>
								<th>
									<?php 
										echo '姓名';//TABLE_HEADING_FIRSTNAME; 
										echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'fname','order'=>'ascending')) . '"><img src="images/arrow_up.gif" border="0"></a>';
										echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'fname','order'=>'decending')) . '"><img src="images/arrow_down.gif" border="0"></a>'; 
									?>
								</th>
								<th><?php

								echo '佣金总额';//TABLE_HEADING_NET_PAYMENT.
									echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'payment','order'=>'ascending')) . '"><img src="images/arrow_up.gif" border="0"></a>';
									echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'payment','order'=>'decending')) . '"><img src="images/arrow_down.gif" border="0"></a>';?>
								</th>
								<th style="display:none"><?php echo TABLE_HEADING_PAYMENT; ?></th>
								<th>已付佣金</th>
								<th>付款日期</th>
								<th>未付佣金</th>
								<th style="display:none"><?php 
									echo TABLE_HEADING_DATE_BILLED .'&nbsp;';
									echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'abdate','order'=>'ascending')) . '"><img src="images/arrow_up.gif" border="0"></a>';
									echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'abdate','order'=>'decending')) . '"><img src="images/arrow_down.gif" border="0"></a>'; ?>
								</th>
								<th><?php 
									echo '<span title="' . TABLE_HEADING_STATUS . '">付款状态</span>';
									echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'pstatus','order'=>'ascending')) . '"><img src="images/arrow_up.gif" border="0"></a>';
									echo '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params_fix(array('sorts'=>'pstatus','order'=>'decending')) . '"><img src="images/arrow_down.gif" border="0"></a>';?>
								</th>
								<?php if ($showdel == 'true') {?>
								<th align="center">删除时间</th>
								<th align="center">删除工号</th>
								<th align="center">统计的订单</th>
								<?php } else { ?>
								<th align="center"><?php echo '<span title="' . TABLE_HEADING_ACTION . '">操作</span>'; ?>&nbsp;</th>
								<?php } ?>
							</tr>
							<?php
							
							while ($payments = tep_db_fetch_array($payments_query)) {
								/*if (((!$_GET['pID']) || ($_GET['pID'] == $payments['affiliate_payment_id'])) && (!$pInfo)) {
									$pInfo = new objectInfo($payments);
								}*/
								/*if ((is_object($pInfo)) && ($payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id)) {
									echo '			  <tr class="dataTableRowSelected"  >' . "\n";/* onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '\'" * /
								} else {*/
									echo '			  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';" onmouseout="this.className=\'dataTableRow\'" style="cursor:default">' . "\n"; /* this.style.cursor=\'hand\' onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '\'"*/
								//}
								?>
								<td class="dataTableContent">
									<?php
									echo $payments['affiliate_payment_id']; 
									//echo $payments['affiliate_lastname']; ?>
								</td>
								<td class="dataTableContent"><?php 
								//if ($showdel == 'true') {
								
								//} else {
								?><a href="<?php echo tep_href_link('affiliate_affiliates.php','search=' . rawurlencode($payments['affiliate_firstname']))?>" target="_blank">
								<?php /*?><a href="<?php  
									//if ( (is_object($pInfo)) && ($payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id) ) {
									//	echo tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit');
									//} else {
									
									//	echo tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID','action')) . 'pID=' . $payments['affiliate_payment_id'] . '&action=edit');
									//}
									?>"><?php*/ 
									//}
									echo $payments['affiliate_firstname']; 
									//if ($showdel == 'true') {

									//} else {
									?>
									</a>
									<?php //} ?>
								</td>
								<td class="dataTableContent" align="right">
									<?php 
										$pay_number = $currencies->format(strip_tags($payments['affiliate_payment'])); 
										if ((float)strip_tags($payments['affiliate_payment']) >= 50) {
											echo '<span style="color:red">' . $pay_number . '</span>';
										} else {
											echo $pay_number;
										}
									?>
								</td>
								<td class="dataTableContent" align="right" style="display:none">
									<?php echo $currencies->format(strip_tags($payments['affiliate_payment'] + $payments['affiliate_payment_tax'])); ?>
								</td>
								<td class="dataTableContent" align="center">
									<?php 
									if ($payments['affiliate_payment_status_name'] == 'Paid(已付款)') {
										if ($showdel == 'true') {
											echo $pay_number;
										} else {
											echo '<a href="' . tep_href_link('affiliate_payment_list.php','action=ispay&affiliate_id=' . $payments['affiliate_id']) . '">' . $pay_number . '</a>';
										}
									} else {
										echo '0';
									}?>
								</td>
								<td class="dataTableContent" align="center">
								<?php echo $payments['affiliate_paid_date']=='0000-00-00' ? '&nbsp;' : $payments['affiliate_paid_date'];?>
								</td>
								<td class="dataTableContent" align="center"><?php 
									if ($payments['affiliate_payment_status_name'] == 'Paid(已付款)') {
										echo '0';
									} else {
										if ($showdel == 'true') {
											echo $pay_number;
										} else {
											echo '<a href="' . tep_href_link('affiliate_payment_list.php','action=needpay&affiliate_id=' . $payments['affiliate_id']) . '&affiliate_payment_id='.$payments['affiliate_payment_id'].'" target="_blank">' . $pay_number . '</a>';
										}
									}?>
								</td>
								<td class="dataTableContent" align="center" style="display:none"><?php echo tep_date_short($payments['affiliate_payment_date']); ?></td>
								<td class="dataTableContent" align="right"><?php echo $payments['affiliate_payment_status_name']; ?></td>
								<?php if ($showdel == 'true') {?>
								<td class="dataTableContent" align="center"><?php echo $payments['delete_date'];?></td>
								<td class="dataTableContent" align="center"><?php echo tep_get_job_number_from_admin_id($payments['delete_admin_id']);?></td>
								<td class="dataTableContent" align="right"><?php 
								$del_orders = unserialize($payments['count_ids']);
								foreach($del_orders as $key => $val) {
									echo $val['affiliate_orders_id'] . '<br/>';
								}?></td>
								<?php } else { ?>
								<td class="dataTableContent" align="right" sensitive="true"><?php 
									//if ((is_object($pInfo)) && ($payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id)) { 
										//echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
									//} else { 
										//echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
									//}
									if($can_copy_affiliate_sensitive_information === true){
										echo '<a class="a_btn" href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $payments['affiliate_payment_id'] . '&action=edit') . '" title="' . IMAGE_EDIT . '">编辑</a>';
										if ($status != 1) {
										echo '&nbsp;<a class="a_btn" onclick="if(confirm(\'确认删除?\n\n删除后不可还原!!!\')) {location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $payments['affiliate_payment_id']  . '&action=deleteconfirm') . '\';}" href="javascript:void(0)" title="' . IMAGE_DELETE . '">删除</a>';
										}
										echo '&nbsp;<a class="a_btn" href="' . tep_href_link(FILENAME_AFFILIATE_INVOICE, 'pID=' . $payments['affiliate_payment_id']) . '" TARGET="_blank" title="' . IMAGE_ORDERS_INVOICE . '">发票</a>';
									}
									?>
								</td>
								<?php } ?>
							</tr>
							
							<?php
						}
							?>
						</table>
						<table width="100%">
						<tr>
						<td class="smallText" valign="center" width="50%" align="center">
									<?php echo $payments_split->display_count($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?>
								</td>
								<td class="smallText" align="center" width="50%">
									<?php echo $payments_split->display_links($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'pID', 'action'))); ?>
								</td>
								</tr>
						</table>
					</td>
					<?php
					/*$heading = array();
					$contents = array();
					switch ($_GET['action']) {
						case 'delete':
							$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PAYMENT . '</b>');
							$contents = array('form' => tep_draw_form('payment', FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id. '&action=deleteconfirm'));
							$contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br>');
							$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
							break;
						default:
							if (is_object($pInfo)) {
								$heading[] = array('text' => '<b>[' . $pInfo->affiliate_payment_id . ']&nbsp;&nbsp;' . tep_datetime_short($pInfo->affiliate_payment_date) . '</b>');
								$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id  . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
								$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_INVOICE, 'pID=' . $pInfo->affiliate_payment_id ) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> ');
							}
							break;
					}*/

					/*if ((tep_not_null($heading)) && (tep_not_null($contents))) {
						echo '			<td  width="25%" valign="top">' . "\n";
						$box = new box;
						//print_r($contents);
						echo $box->infoBox($heading, $contents);
						echo '			</td>' . "\n";
					}*/
					?>
				</tr>
			</table>
		</td>
	</tr>
	<?php
}
?>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
	require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
