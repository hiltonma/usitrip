<?php
/*
$Id: customers_points_pending.php, V2.1rc2a 2008/SEP/29 17:09:41 dsa_ Exp $
created by Ben Zukrel, Deep Silver Accessories
http://www.deep-silver.com

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
	switch ($action) {
		case 'confirm_points':
			$uID = tep_db_prepare_input($_GET['uID']);
			$customer_id = $_POST['customers_id'];
			$order_id = $_POST['orders_id'];
			$date_added = $_POST['date_purchased'];
			$points_pending = tep_db_prepare_input($_POST['points_pending']);
			$order_status = $_POST['orders_status_name'];

			$customer_notified = '0';
			if (isset($_POST['notify_confirm']) && ($_POST['notify_confirm'] == 'on')) {
				$customer_query = tep_db_query("select customers_gender, customers_lastname, customers_firstname, customers_shopping_points, customers_points_expires from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "' limit 1");
				$customer = tep_db_fetch_array($customer_query);
				$balance = $customer['customers_shopping_points'];
				$customer_balance = sprintf(EMAIL_TEXT_BALANCE, number_format($balance,POINTS_DECIMAL_PLACES), $currencies->format($balance * REDEEM_POINT_VALUE));
				$gender = $customer['customers_gender'];
				$first_name = $customer['customers_firstname'];
				$last_name = $customer['customers_lastname'];
				$name = $first_name . ' ' . $last_name;

				if (ACCOUNT_GENDER == 'true') {
					if ($gender == 'm') {
						$greet = sprintf(EMAIL_GREET_MR, $last_name);
					} else {
						$greet = sprintf(EMAIL_GREET_MS, $last_name);
					}
				} else {

					$greet = sprintf(EMAIL_GREET_NONE, $first_name);
				}
				$can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;

				$email_text = $greet  . "\n" . EMAIL_TEXT_INTRO . "\n" . EMAIL_TEXT_BALANCE_CONFIRMED . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($date_added) . "\n" . EMAIL_TEXT_ORDER_STAUTS . ' ' . $order_status . "\n" . TABLE_HEADING_POINTS . ' = ' . number_format($points_pending,POINTS_DECIMAL_PLACES) . "\n" . TABLE_HEADING_POINTS_VALUE . ' ' . $currencies->format($points_pending * REDEEM_POINT_VALUE) . "\n" . EMAIL_SEPARATOR . "\n" . $customer_balance  . $points_expire_date . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";

				tep_mail($name, $customers_email_address, EMAIL_TEXT_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

				$customer_notified = '1';
				$messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $name . '(' . $customers_email_address . ').'), 'success');
			}

			if (isset($_POST['queue_confirm'])) {
				tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2 where unique_id = '". (int)$uID ."' limit 1");
			} else {
				$messageStack->add_session(NOTICE_RECORED_REMOVED, 'warning');
				tep_db_query("delete from " . TABLE_CUSTOMERS_POINTS_PENDING . " where unique_id = '". (int)$uID ."' limit 1");
			}
			$messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
			tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action'))));
			break;
		case 'cancel_points':
			$uID = tep_db_prepare_input($_GET['uID']);
			$comment_cancel = tep_db_prepare_input($_POST['comment_cancel']);
			$order_id = $_POST['orders_id'];
			$customer_id = $_POST['customers_id'];
			$date_added = $_POST['date_purchased'];
			$points_pending = tep_db_prepare_input($_POST['points_pending']);
			$order_status = $_POST['orders_status_name'];

			$customer_notified = '0';
			if (isset($_POST['notify_cancel']) && ($_POST['notify_cancel'] == 'on')) {
				$customer_query = tep_db_query("select customers_gender, customers_lastname, customers_firstname, customers_shopping_points, customers_points_expires from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
				$customer = tep_db_fetch_array($customer_query);
				$gender = $customer['customers_gender'];
				$first_name = $customer['customers_firstname'];
				$last_name = $customer['customers_lastname'];
				$name = $first_name . ' ' . $last_name;

				$notify_comment = '';
				if (isset($_POST['comment_cancel']) && tep_not_null($comment_cancel)) {
					$notify_comment = sprintf(EMAIL_TEXT_COMMENT, $comment_cancel) . "\n";
				}

				if (ACCOUNT_GENDER == 'true') {
					if ($gender == 'm') {
						$greet = sprintf(EMAIL_GREET_MR, $last_name);
					} else {
						$greet = sprintf(EMAIL_GREET_MS, $last_name);
					}
				} else {
					$greet = sprintf(EMAIL_GREET_NONE, $first_name);
				}
				if ($customer['customers_shopping_points']> 0) {
					$balance = $customer['customers_shopping_points'];
					$customer_balance = sprintf(EMAIL_TEXT_BALANCE, number_format($balance,POINTS_DECIMAL_PLACES), $currencies->format($balance * REDEEM_POINT_VALUE));
					$can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;
					if (tep_not_null(POINTS_AUTO_EXPIRES)){
						$points_expire_date = "\n" . sprintf(EMAIL_TEXT_EXPIRE, tep_date_short($customer['customers_points_expires']));
					}
				}

				$email_text = $greet  . "\n" . EMAIL_TEXT_INTRO . "\n" . EMAIL_TEXT_BALANCE_CANCELLED . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($date_added) . "\n" . EMAIL_TEXT_ORDER_STAUTS . ' ' . $order_status . "\n" . TABLE_HEADING_POINTS . ' = ' . number_format($points_pending,POINTS_DECIMAL_PLACES) . "\n" . TABLE_HEADING_POINTS_VALUE . ' ' . $currencies->format($points_pending * REDEEM_POINT_VALUE) . "\n" . EMAIL_SEPARATOR . "\n" . $notify_comment . $customer_balance . $points_expire_date . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";

				tep_mail($name, $customers_email_address, EMAIL_TEXT_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

				$customer_notified = '1';
				$messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $name . '(' . $customers_email_address . ').'), 'success');
			}
			$database_queue = '0';
			if (isset($_POST['queue_cancel'])) {
				if (isset($_POST['comment_cancel']) && tep_not_null($comment_cancel)) {
					$set_comment = ", points_comment = '" . $comment_cancel . "'";
				}
				tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 3 " . $set_comment . " where unique_id = '". (int)$uID ."' limit 1");
				$database_queue = '1';
				$messageStack->add_session(SUCCESS_DATABASE_UPDATED, 'success');
			} else {
				tep_db_query("delete from " . TABLE_CUSTOMERS_POINTS_PENDING . " where unique_id = '". (int)$uID ."' limit 1");
			}

			tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分

			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING));
			break;
		case 'adjustpoints':
			$uID = tep_db_prepare_input($_GET['uID']);
			$adjust = tep_db_prepare_input($_POST['points_to_aj']);

			$points_adjusted = false;
			if (tep_not_null($adjust)) {
				tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_pending = '" . $adjust . "' where unique_id = '". (int)$uID ."' limit 1");
			} else {
				$messageStack->add_session(WARNING_DATABASE_NOT_UPDATED, 'warning');
			}
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('cID', 'action'))));
			break;
		case 'pe_rollback':
			$uID = tep_db_prepare_input($_GET['uID']);
			$comment_roll = tep_db_prepare_input($_POST['comment_roll']);
			$order_id = $_POST['orders_id'];
			$customer_id = tep_db_prepare_input($_POST['customers_id']);
			$date_added = $_POST['date_purchased'];
			$points_pending = tep_db_prepare_input($_POST['points_pending']);
			$order_status = $_POST['orders_status_name'];

			if (isset($_POST['comment_roll']) && tep_not_null($comment_roll)) {
				$set_comment = ", points_comment = '" . $comment_roll . "'";
			}

			tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 1 " . $set_comment . " where unique_id = '". (int)$uID ."' limit 1");
			tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
			$customer_notified = '0';
			if (isset($_POST['notify_roll']) && ($_POST['notify_roll'] == 'on')) {
				$customer_query = tep_db_query("select customers_gender, customers_lastname, customers_firstname, customers_shopping_points, customers_points_expires from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "' limit 1");
				$customer = tep_db_fetch_array($customer_query);
				$gender = $customer['customers_gender'];
				$first_name = $customer['customers_firstname'];
				$last_name = $customer['customers_lastname'];
				$name = $first_name . ' ' . $last_name;

				$notify_comment = '';
				if (isset($_POST['comment_roll']) && tep_not_null($comment_roll)) {
					$notify_comment = sprintf(EMAIL_TEXT_ROLL_COMMENT . ' ' . $comment_roll) . "\n";
				}

				if (ACCOUNT_GENDER == 'true') {
					if ($gender == 'm') {
						$greet = sprintf(EMAIL_GREET_MR, $last_name);
					} else {
						$greet = sprintf(EMAIL_GREET_MS, $last_name);
					}
				} else {
					$greet = sprintf(EMAIL_GREET_NONE, $first_name);
				}
				if ($customer['customers_shopping_points']> 0) {
					$balance = $customer['customers_shopping_points'];
					$customer_balance = sprintf(EMAIL_TEXT_BALANCE, number_format($balance,POINTS_DECIMAL_PLACES), $currencies->format($balance * REDEEM_POINT_VALUE));
					$can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;
					if (tep_not_null(POINTS_AUTO_EXPIRES)){
						$points_expire_date = "\n" . sprintf(EMAIL_TEXT_EXPIRE, tep_date_short($customer['customers_points_expires']));
					}
				}

				$email_text = $greet . "\n" . EMAIL_TEXT_INTRO . "\n" . EMAIL_TEXT_BALANCE_ROLL_BACK . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($date_added) . "\n" . EMAIL_TEXT_ORDER_STAUTS . ' ' . $order_status . "\n" . TABLE_HEADING_POINTS . ' = ' . number_format($points_pending,POINTS_DECIMAL_PLACES) . "\n" . TABLE_HEADING_POINTS_VALUE . ' ' . $currencies->format($points_pending * REDEEM_POINT_VALUE) . "\n" . EMAIL_SEPARATOR . "\n" . $notify_comment . $customer_balance . $points_expire_date . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";


				tep_mail($name, $customers_email_address, EMAIL_TEXT_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

				$customer_notified = '1';
				$messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $name . '(' . $customers_email_address . ').'), 'success');
			}

			$messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING));
			break;
		case 'delete_points':
			$uID = tep_db_prepare_input($_GET['uID']);

			tep_db_query("delete from " . TABLE_CUSTOMERS_POINTS_PENDING . " where unique_id = '". (int)$uID ."' limit 1");
			$messageStack->add_session(NOTICE_RECORED_REMOVED, 'warning');

			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action'))));
			break;
	}
}

include(DIR_WS_CLASSES . 'order.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function validate(field) {
	var valid = "0123456789."
	var ok = "yes";
	var temp;
	for (var i=0; i<field.value.length; i++) {
		temp = "" + field.value.substring(i, i+1);
		if (valid.indexOf(temp) == "-1") ok = "no";
	}
	if (ok == "no") {
		alert("<?php echo POINTS_ENTER_JS_ERROR; ?>");
		field.focus();
		field.value = "";
	}
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
require(DIR_WS_INCLUDES . 'header.php');
?>
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
<?php
$orders_statuses = array();
$orders_status_array = array();
$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	$orders_statuses[] = array('id' => $orders_status['orders_status_id'],
	'text' => $orders_status['orders_status_name']);
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}

// drop-down filter array
$filter_array = array( array('id' => '1', 'text' => TEXT_POINTS_PENDING),
array('id' => '2', 'text' => TEXT_POINTS_CONFIRMED),
array('id' => '3', 'text' => TEXT_POINTS_CANCELLED),
array('id' => '4', 'text' => TEXT_POINTS_REDEEMED),
array('id' => '5', 'text' => TEXT_SHOW_ALL));

$point_or_points = ((POINTS_PER_AMOUNT_PURCHASE > 1) ? HEADING_POINTS : HEADING_POINT);
?>
<tr>
<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="pageHeading"><?php echo HEADING_TITLE . '<br /><span class="smallText">' . HEADING_RATE . '&nbsp;&nbsp;&nbsp;' .  HEADING_AWARDS . $currencies->format(1) . ' = ' . number_format(POINTS_PER_AMOUNT_PURCHASE,2) .'&nbsp;' . $point_or_points . '&nbsp;&nbsp;&nbsp;' . HEADING_REDEEM  .  number_format(POINTS_PER_AMOUNT_PURCHASE,2) . '&nbsp;' . $point_or_points . ' = ' . $currencies->format(POINTS_PER_AMOUNT_PURCHASE * REDEEM_POINT_VALUE);
//POINTS_DECIMAL_PLACES
?>
</td>
<td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><?php echo tep_draw_form('orders', FILENAME_CUSTOMERS_POINTS_PENDING, '', 'get'); ?>
<td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search', '', 'size="12"'); ?></td>
</form></tr>
<tr><?php echo tep_draw_form('status', FILENAME_CUSTOMERS_POINTS_PENDING, '', 'get'); ?>
<td class="smallText" align="right"><?php echo TABLE_HEADING_ORDERS_STATUS . ': ' .tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_SHOW_ALL)), $orders_statuses)). '<br>'. TABLE_HEADING_POINTS_STATUS . ':'. tep_draw_pull_down_menu('filter', $filter_array, '', 'onChange="this.form.submit();"'); ?></td>
</form></tr>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr class="dataTableHeadingRow">
<td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=c_name-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_CUSTOMERS . TABLE_HEADING_SORT_UA . '">+</a>&nbsp;' . TABLE_HEADING_CUSTOMERS . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=c_name-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_CUSTOMERS . TABLE_HEADING_SORT_DA; ?>">-</a></td>
                <td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=ot-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDER_TOTAL . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_ORDER_TOTAL . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=ot-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDER_TOTAL . TABLE_HEADING_SORT_D1; ?>">-</a></td>
<td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=points-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_POINTS . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=points-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS . TABLE_HEADING_SORT_D1; ?>">-</a></td>
                <td class="dataTableHeadingContent" align="right">&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=points-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_VALUE . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_POINTS_VALUE . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=points-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_VALUE . TABLE_HEADING_SORT_D1; ?>">-</a></td>
<td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=o_status-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDERS_STATUS . TABLE_HEADING_SORT_UA . '">+</a>&nbsp;' . TABLE_HEADING_ORDERS_STATUS . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=o_status-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDERS_STATUS . TABLE_HEADING_SORT_DA; ?>">-</a></td>
                <td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=p_status-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_STATUS . TABLE_HEADING_SORT_UA . '">+</a>&nbsp;' . TABLE_HEADING_POINTS_STATUS . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params() . 'viewedSort=p_status-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_STATUS . TABLE_HEADING_SORT_DA; ?>">-</a></td>
<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
</tr>
<?php
//sort view
if (isset($_GET['viewedSort'])){
	$viewedSort = $_GET['viewedSort'];
	tep_session_register('viewedSort');
}
if (isset($_GET['page'])) {
	$page = $_GET['page'];
	tep_session_register('page');
}
if(!isset($page)) $page = 1;

switch ($viewedSort) {
	case "c_name-asc":
		$sort .= "o.customers_name";
		break;
	case "c_name-desc":
		$sort .= "o.customers_name desc";
		break;
	case "ot-asc":
		$sort .= "ot.text";
		break;
	case "ot-desc":
		$sort .= "ot.text desc";
		break;
	case "points-asc":
		$sort .= "points_pending";
		break;
	case "points-desc":
		$sort .= "points_pending desc";
		break;
	case "o_status-asc":
		$sort .= "orders_status_name";
		break;
	case "o_status-desc":
		$sort .= "orders_status_name desc";
		break;
	case "p_status-asc":
		$sort .= "points_status";
		break;
	case "p_status-desc":
		$sort .= "points_status desc";
		break;
	default:
		$sort .= "o.orders_id desc";
}

if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
	$sorders = tep_db_input(tep_db_prepare_input($_GET['search']));
	$pending_points_query_raw = "select o.orders_id, o.customers_id, o.customers_name, o.customers_email_address, o.payment_method, o.date_purchased, o.orders_status, s.orders_status_name, ot.text as order_total, pp.unique_id, pp.points_pending, pp.points_comment, pp.points_status, pp.points_type from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " . TABLE_CUSTOMERS_POINTS_PENDING . " pp where o.orders_id = '" . (int)$sorders . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
} elseif (isset($_GET['filter']) && is_numeric($_GET['filter']) && ($_GET['filter'] > 0)) {
	$filter = 'and pp.points_status = ' . (int)tep_db_prepare_input($_GET['filter']);
	if ($_GET['filter'] == '5') $filter = 'and pp.points_status != 0';
	if (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0)) {
		$status = tep_db_prepare_input($_GET['status']);
		$pending_points_query_raw = "select o.orders_id, o.customers_id, o.customers_name, o.customers_email_address, o.payment_method, o.date_purchased, o.orders_status, s.orders_status_name, ot.text as order_total, pp.unique_id, pp.points_pending, pp.points_comment, pp.points_status, pp.points_type from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " . TABLE_CUSTOMERS_POINTS_PENDING . " pp where o.orders_id = pp.orders_id and o.orders_status = '" . (int)$status . "' " . $filter . " and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by $sort";
	} else {
		$pending_points_query_raw = "select o.orders_id, o.customers_id, o.customers_name, o.customers_email_address, o.payment_method, o.date_purchased, o.orders_status, s.orders_status_name, ot.text as order_total, pp.unique_id, pp.points_pending, pp.points_comment, pp.points_status, pp.points_type from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " . TABLE_CUSTOMERS_POINTS_PENDING . " pp where points_type = 'SP' " . $filter . " and (pp.orders_id = o.orders_id and ot.class = 'ot_total' and o.orders_id = ot.orders_id and o.orders_status = s.orders_status_id) and s.language_id = '" . (int)$languages_id . "' order by $sort";
	}

} else {
	$pending_points_query_raw = "select o.orders_id, o.customers_id, o.customers_name, o.customers_email_address, o.payment_method, o.date_purchased, o.orders_status, s.orders_status_name, ot.text as order_total, pp.unique_id, pp.points_pending, pp.points_comment, pp.points_status, pp.points_type from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " . TABLE_CUSTOMERS_POINTS_PENDING . " pp where points_type = 'SP' and pp.points_status = 1 and (pp.orders_id = o.orders_id and ot.class = 'ot_total' and o.orders_id = ot.orders_id and o.orders_status = s.orders_status_id) and s.language_id = '" . (int)$languages_id . "' order by $sort";
}




$pending_points_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $pending_points_query_raw, $pending_points_query_numrows);
$pending_points_query = tep_db_query($pending_points_query_raw);

while ($pending_points = tep_db_fetch_array($pending_points_query)) {

	if ((!isset($_GET['uID']) || (isset($_GET['uID']) && ($_GET['uID'] == $pending_points['unique_id']))) && !isset($uInfo)) {
		$uInfo = new objectInfo($pending_points);
	}

	if (isset($uInfo) && is_object($uInfo) && ($pending_points['unique_id'] == $uInfo->unique_id)) {
		echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=edit') . '\'">' . "\n";
	} else {
		echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID')) . 'uID=' . $pending_points['unique_id']) . '\'">' . "\n";
	}

	if ($pending_points['points_status'] == 1) $points_status_name = TEXT_POINTS_PENDING;
	if ($pending_points['points_status'] == 2) $points_status_name = TEXT_POINTS_CONFIRMED;
	if ($pending_points['points_status'] == 3) $points_status_name = '<font color="FF0000">' . TEXT_POINTS_CANCELLED . '</font>';
	if ($pending_points['points_status'] == 4) $points_status_name = '<font color="0000FF">' . TEXT_POINTS_REDEEMED . '</font>';

	$display_points = ($pending_points['points_pending'] >0) ? number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES) : str_replace('-', '', number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES));

	?>
	<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS . '?oID=' . $uInfo->orders_id . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW_EDIT) . '</a>&nbsp;' . $pending_points['customers_name']; ?></td>
	<td class="dataTableContent" align="right"><?php echo strip_tags($pending_points['order_total']); ?></td>
	<td class="dataTableContent" align="right"><?php echo str_replace('-', '', number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES)); ?></td>
	<td class="dataTableContent" align="right"><?php echo str_replace('-', '', $currencies->format($pending_points['points_pending'] * REDEEM_POINT_VALUE)); ?></td>
	<td class="dataTableContent" align="center"><?php echo $pending_points['orders_status_name']; ?></td>
	<td class="dataTableContent" align="center"><?php echo $points_status_name; ?></td>
	<td class="dataTableContent" align="right"><?php if (isset($uInfo) && is_object($uInfo) && ($pending_points['unique_id'] == $uInfo->unique_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID')) . 'uID=' . $pending_points['unique_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
	</tr>
	<?php
}
?>
<tr>
<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="smallText" valign="top"><?php echo $pending_points_split->display_count($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
<td class="smallText" align="right"><?php echo $pending_points_split->display_links($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'uID', 'action'))); ?></td>
</tr>
<!-- Yes, you may remove this advertising clause. //-->
<?php /* ?><tr>
<td class="smallText" align="center"><br><br><?php echo TEXT_LINK_CREDIT . '<br><br>POINTS AND REWARDS MODULE ' . MOD_VER; ?>&nbsp;&nbsp;<a href="http://www.deep-silver.com" target="_blank">Copyright &copy; Deep Silver Accessory</a></td>
</tr><?php */ ?>
<!-- advertising_eof //-->
<?php
if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
	?>
	<tr>
	<td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
	</tr>
	<?php
}
?>
</table></td>
</tr>
</table></td>
<?php
$heading = array();
$contents = array();

switch ($action) {
	case 'confirm':
		$heading[] = array('text' => '<b>' . TEXT_CONFIRM_POINTS . '</b>');

		$contents = array('form' => tep_draw_form('points_confirm', FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=confirm_points'));
		$value_field = TEXT_CONFIRM_POINTS_LONG. '<br>';
		$contents[] = array('text' => $value_field);
		$contents[] = array('text' => tep_draw_checkbox_field('notify_confirm', '', true) . ' ' . TEXT_NOTIFY_CUSTOMER);
		$contents[] = array('text' => tep_draw_checkbox_field('queue_confirm', '', true) . ' ' . TEXT_QUEUE_POINTS_TABLE);
		$contents[] = array('text' => tep_draw_hidden_field('orders_id', $uInfo->orders_id) . tep_draw_hidden_field('customers_id', $uInfo->customers_id) . tep_draw_hidden_field('customers_email_address', $uInfo->customers_email_address) . tep_draw_hidden_field('date_purchased', $uInfo->date_purchased) . tep_draw_hidden_field('orders_status_name', $uInfo->orders_status_name) . tep_draw_hidden_field('points_pending', $uInfo->points_pending));

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_confirm_points.gif', BUTTON_TEXT_CONFIRM_PENDING_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'cancel':
		$heading[] = array('text' => '<b>' . TEXT_CANCEL_POINTS . '</b>');

		$contents = array('form' => tep_draw_form('points_cancel', FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=cancel_points'));
		$contents[] = array('text' => TEXT_CANCEL_POINTS_LONG);
		$value_field = TEXT_CANCELLATION_REASON .'<br>'. tep_draw_input_field('comment_cancel', 0);
		$contents[] = array('text' => $value_field);
		$contents[] = array('text' => tep_draw_checkbox_field('notify_cancel', '', true) . ' ' . TEXT_NOTIFY_CUSTOMER);
		$contents[] = array('text' => tep_draw_checkbox_field('queue_cancel', '', true) . ' ' . TEXT_QUEUE_POINTS_TABLE);
		$contents[] = array('text' => tep_draw_hidden_field('orders_id', $uInfo->orders_id) . tep_draw_hidden_field('customers_id', $uInfo->customers_id) . tep_draw_hidden_field('customers_email_address', $uInfo->customers_email_address) . tep_draw_hidden_field('date_purchased', $uInfo->date_purchased) . tep_draw_hidden_field('orders_status_name', $uInfo->orders_status_name) . tep_draw_hidden_field('points_pending', $uInfo->points_pending));

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_cancel_points.gif', BUTTON_TEXT_CANCEL_PENDING_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'adjust':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_ADJUST_POINTS . '</b>');

		$contents = array('form' => tep_draw_form('points_adjust', FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=adjustpoints'));
		$contents[] = array('text' => '<b>'. TEXT_INFO_HEADING_ADJUST_POINTS . '</b><br>');
		$value_field = TEXT_ADJUST_INTRO . '<br><br>' . TEXT_POINTS_TO_ADJUST . '<br>'. tep_draw_input_field('points_to_aj', '' , 'onkeyup="validate(this)"');
		$contents[] = array('text' => $value_field);

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'rollback':
		$heading[] = array('text' => '<b>' . TEXT_ROLL_POINTS . '</b>');

		$contents = array('form' => tep_draw_form('points_roll', FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=pe_rollback'));
		$contents[] = array('text' => '<b>'. TEXT_ROLL_POINTS . '</b><br>');
		$value_field = TEXT_ROLL_POINTS_LONG. '<br>';
		$contents[] = array('text' => $value_field);
		$value_field = TEXT_ROLL_REASON .'<br>'. tep_draw_input_field('comment_roll', 0);
		$contents[] = array('text' => $value_field);
		$contents[] = array('text' => tep_draw_checkbox_field('notify_roll', '', true) . ' ' . TEXT_NOTIFY_CUSTOMER);
		$contents[] = array('text' => tep_draw_hidden_field('orders_id', $uInfo->orders_id) . tep_draw_hidden_field('customers_id', $uInfo->customers_id) . tep_draw_hidden_field('customers_email_address', $uInfo->customers_email_address) . tep_draw_hidden_field('date_purchased', $uInfo->date_purchased) . tep_draw_hidden_field('orders_status_name', $uInfo->orders_status_name) . tep_draw_hidden_field('points_pending', $uInfo->points_pending));

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_rollback_points.gif', BUTTON_TEXT_ROLL_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'delete':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_RECORD . '</b>');

		$contents = array('form' => tep_draw_form('points_delete', FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=delete_points'));
		$contents[] = array('text' => TEXT_DELETE_INTRO );

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', BUTTON_TEXT_REMOVE_RECORD) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;

	default:
		if (isset($uInfo) && is_object($uInfo)) {
			$heading[] = array('text' => TEXT_INFO_HEADING_PENDING_NO .'<b>' . $uInfo->orders_id . '</b>');

			if ($uInfo->points_status == 1) {
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=confirm') . '">' . tep_image_button('button_confirm_points.gif', BUTTON_TEXT_CONFIRM_PENDING_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=cancel') . '">' . tep_image_button('button_cancel_points.gif', BUTTON_TEXT_CANCEL_PENDING_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=adjust') . '">' . tep_image_button('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $uInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', BUTTON_TEXT_REMOVE_RECORD) . '</a>');
			}
			if ($uInfo->points_status == 2) {
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('action')) . 'uID=' . $uInfo->unique_id . '&action=rollback') . '">' . tep_image_button('button_rollback_points.gif', BUTTON_TEXT_ROLL_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $uInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', BUTTON_TEXT_REMOVE_RECORD) . '</a>');
			}
			if ($uInfo->points_status == 3) {
				$contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=confirm') . '">' . tep_image_button('button_confirm_points.gif', BUTTON_TEXT_CONFIRM_PENDING_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=adjust') . '">' . tep_image_button('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS . '?oID=' . $uInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $uInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', BUTTON_TEXT_REMOVE_RECORD) . '</a>');
			}
			if ($uInfo->points_status == 4) {
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS . '?oID=' . $uInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $uInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, tep_get_all_get_params(array('uID', 'action')) . 'uID=' . $uInfo->unique_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', BUTTON_TEXT_REMOVE_RECORD) . '</a>');
			}

			if ($uInfo->points_comment == 'TEXT_DEFAULT_COMMENT') {
				$uInfo->points_comment = TEXT_DEFAULT_COMMENT;
			}

			if ($uInfo->points_comment == 'TEXT_DEFAULT_REDEEMED') {
				$uInfo->points_comment = TEXT_DEFAULT_REDEEMED;
			}

			if ($uInfo->points_comment == 'TEXT_DEFAULT_REFERRAL') {
				$uInfo->points_comment = TEXT_DEFAULT_REFERRAL;
			}

			$contents[] = array('text' => '<br><b>' . TEXT_INFO_POINTS_COMMENT . '</b><br>' . $uInfo->points_comment);
			$contents[] = array('text' => '<br>' . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_short($uInfo->date_purchased));
			$contents[] = array('text' => '<br><b>' . TEXT_INFO_PAYMENT_METHOD . '</b><br>' . $uInfo->payment_method);
		}
		break;
}

if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
	echo '<td width="25%" valign="top">' . "\n";

	$box = new box;
	echo $box->infoBox($heading, $contents);

	echo '</td>' . "\n";
}
?>
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
