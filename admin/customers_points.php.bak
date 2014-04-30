<?php
/*
$Id: customers_points.php, V2.1rc2a 2008/SEP/29 09:00:46 dsa_ Exp $
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
		case 'addconfirm':
			$customers_id = tep_db_prepare_input($_GET['cID']);
			$pointstoadd = tep_db_prepare_input($_POST['points_to_add']);
			//$comment = nl2br(tep_db_prepare_input($_POST['comment']));
			$comment = html_to_db(tep_db_prepare_input($_POST['comment']));

			$points_added = false;
			if ($pointstoadd > 0) {
				$expire_date = "\n" . sprintf(EMAIL_TEXT_EXPIRE, tep_date_short($_POST['customers_points_expires']));

				$customer_notified = '0';
				if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
					$balance = ($_POST['customers_shopping_points'] + $pointstoadd);
					$customer_balance = sprintf(EMAIL_TEXT_BALANCE, number_format($balance,POINTS_DECIMAL_PLACES), $currencies->format($balance * REDEEM_POINT_VALUE));
					$gender = $_POST['customers_gender'];
					$first_name = $_POST['customers_firstname'];
					$last_name = $_POST['customers_lastname'];
					$name = $first_name . ' ' . $last_name;

					$notify_comment = '';
					if (isset($_POST['comment']) && tep_not_null($comment)) {
						$notify_comment = sprintf(EMAIL_TEXT_COMMENT, $comment) . "\n";
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
					if (tep_not_null(POINTS_AUTO_EXPIRES)){
						$points_expire_date = $expire_date;
					}
					$can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;
					$email_text = $greet  . "\n" . EMAIL_TEXT_INTRO . "\n" . sprintf(EMAIL_TEXT_BALANCE_ADD, $pointstoadd, $currencies->format($pointstoadd * REDEEM_POINT_VALUE)) . "\n" . $notify_comment . $customer_balance  . $points_expire_date . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";

					tep_mail($name, $customers_email_address, EMAIL_TEXT_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

					$customer_notified = '1';
					$messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $name . '(' . $customers_email_address . ').'), 'success');
				}

				$database_queue = '0';
				//if (isset($_POST['queue_add']) && ($_POST['queue_add'] == 'on')) {

				$sql_data_array = array('unique_id' => '',
										'customer_id' => (int)$customers_id,
										'orders_id' => 0,
										'points_comment' => $comment,
										'points_pending' => $pointstoadd,
										'date_added' => 'now()',
										'points_status' => 2,
										'admin_id' => $login_id);

				tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);

				$sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";

				$database_queue = '1';
				$messageStack->add_session(SUCCESS_DATABASE_UPDATED, 'success');
				//}
				$points_added = true;
			}
			if ($points_added == true) {
				$messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
			} else {
				$messageStack->add_session(WARNING_DATABASE_NOT_UPDATED, 'warning');
			}
			
			tep_auto_fix_customers_points((int)$customers_id);	//自动校正用户积分
			
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('oID', 'action'))));
			break;
		case 'delconfirm':
			$customers_id = tep_db_prepare_input($_GET['cID']);
			$pointstodel = tep_db_prepare_input($_POST['points_to_delete']);
			//$comment = nl2br(tep_db_prepare_input($_POST['comment']));
			$comment = html_to_db(tep_db_prepare_input($_POST['comment']));
			//$comment = tep_db_prepare_input($_POST['comment']);
			$balance = $_POST['customers_shopping_points'] - $pointstodel;
			$Cexpire_date = tep_db_prepare_input($_POST['customers_points_expires']);

			$points_deleted = false;
			if ($pointstodel > 0) {
				$exp = ($balance > 0) ? $Cexpire_date : 'null';
				$expire_date = "\n" . sprintf(EMAIL_TEXT_EXPIRE, tep_date_short($_POST['customers_points_expires']));

				$customer_notified = '0';

				if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
					$gender = $_POST['customers_gender'];
					$first_name = $_POST['customers_firstname'];
					$last_name = $_POST['customers_lastname'];
					$name = $first_name . ' ' . $last_name;

					$notify_comment = '';
					if (isset($_POST['comment']) && tep_not_null($comment)) {
						$notify_comment = sprintf(EMAIL_TEXT_COMMENT, $comment) . "\n";
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

					if ($balance> 0) {
						$customer_balance = sprintf(EMAIL_TEXT_BALANCE, number_format($balance,POINTS_DECIMAL_PLACES), $currencies->format($balance * REDEEM_POINT_VALUE));
						$can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;
					}
					if (tep_not_null(POINTS_AUTO_EXPIRES)){
						$points_expire_date = $expire_date;
					}

					$email_text = $greet  . "\n" . EMAIL_TEXT_INTRO . "\n" . sprintf(EMAIL_TEXT_BALANCE_DEL, $pointstodel, $currencies->format($pointstodel * REDEEM_POINT_VALUE)) . "\n" . $notify_comment . $customer_balance  . $points_expire_date . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";

					tep_mail($name, $customers_email_address, EMAIL_TEXT_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

					$customer_notified = '1';
					$messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $name . '(' . $customers_email_address . ').'), 'success');
				}

				$database_queue = '0';
				//if (isset($_POST['queue_add']) && ($_POST['queue_add'] == 'on')) {

				$sql_data_array = array('customer_id' => $customers_id,
										'orders_id' => 0,
										'points_comment' => $comment,
										'points_pending' => -$pointstodel,
										'date_added' => 'now()',
										'points_status' => 2,
										'admin_id' => $login_id);

				tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);

				$sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";

				$database_queue = '1';
				$messageStack->add_session(SUCCESS_DATABASE_UPDATED, 'success');
				//}
				$points_added = true;
			}
			if ($points_added == true) {
				$messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
			} else {
				$messageStack->add_session(WARNING_DATABASE_NOT_UPDATED, 'warning');
			}
			
			tep_auto_fix_customers_points((int)$customers_id);	//自动校正用户积分
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('oID', 'action'))));
			break;
		case 'adjustpoints':
			$customers_id = tep_db_prepare_input($_GET['cID']);
			$adjust = tep_db_prepare_input($_POST['points_to_aj']);

			if (tep_not_null($adjust) && is_numeric($adjust) && ($adjust>=0)) {
				if ($adjust != 0) {
				} else {
					tep_db_query("update " . TABLE_CUSTOMERS . " set customers_points_expires = 'null' where customers_id = '". (int)$customers_id ."'");
				}
			}
			tep_auto_fix_customers_points((int)$customers_id);	//自动校正用户积分
			tep_redirect(tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('oID', 'action'))));
			break;
	}
}

//drop-down filter array
$filter_array = array( array('id' => '1', 'text' => TEXT_SHOW_ALL),
array('id' => '2', 'text' => TEXT_SORT_POINTS),
array('id' => '3', 'text' => TEXT_SORT_NO_POINTS)
//array('id' => '4', 'text' => TEXT_SORT_BIRTH),
//array('id' => '5', 'text' => TEXT_SORT_BIRTH_NEXT),
//array('id' => '6', 'text' => TEXT_SORT_EXPIRE),
//array('id' => '7', 'text' => TEXT_SORT_EXPIRE_NEXT),
//array('id' => '8', 'text' => TEXT_SORT_EXPIRE_WIN)
);

$point_or_points = ((POINTS_PER_AMOUNT_PURCHASE > 1) ? HEADING_POINTS : HEADING_POINT);
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="pageHeading"><?php echo HEADING_TITLE . '<br /><span class="smallText">' . HEADING_RATE . '&nbsp;&nbsp;&nbsp;' .  HEADING_AWARDS . $currencies->format(1) . ' = ' . number_format(POINTS_PER_AMOUNT_PURCHASE,2) .'&nbsp;' . $point_or_points . '&nbsp;&nbsp;&nbsp;' . HEADING_REDEEM  .  number_format(POINTS_PER_AMOUNT_PURCHASE,2) . '&nbsp;' . $point_or_points . ' = ' . $currencies->format(POINTS_PER_AMOUNT_PURCHASE * REDEEM_POINT_VALUE);
//POINTS_DECIMAL_PLACES
?></td>
<td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0"><?php echo tep_draw_form('orders', FILENAME_CUSTOMERS_POINTS, '', 'get'); ?>
<td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
</form>
<tr><?php echo tep_draw_form('status', FILENAME_CUSTOMERS_POINTS, '', 'get'); ?>
<td class="smallText" align="right"><?php echo '&nbsp;'. TEXT_SORT_CUSTOMERS . ':&nbsp;'. tep_draw_pull_down_menu('filter', $filter_array, '', 'onChange="this.form.submit();"'); ?></td>
</form></tr>
</table></td>
</tr>
</table></td>
</tr>
<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr class="dataTableHeadingRow">
<td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=lastname-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_LASTNAME . TABLE_HEADING_SORT_UA . '">+</a>&nbsp;' . TABLE_HEADING_LASTNAME . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=lastname-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_LASTNAME . TABLE_HEADING_SORT_DA; ?>">-</a></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=firstname-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_FIRSTNAME . TABLE_HEADING_SORT_UA . '">+</a>&nbsp;' . TABLE_HEADING_FIRSTNAME . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=firstname-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_FIRSTNAME . TABLE_HEADING_SORT_DA; ?>">-</a></td>
<td class="dataTableHeadingContent">All Actions</td>
<?php /* ?><td class="dataTableHeadingContent" align="center"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=date-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_DOB . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_DOB . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=date-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_DOB . TABLE_HEADING_SORT_D1; ?>">-</a></td><?php */ ?>
<td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=points-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_POINTS . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=points-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS . TABLE_HEADING_SORT_D1; ?>">-</a></td>
                <td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=points-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_VALUE . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_POINTS_VALUE . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=points-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_VALUE . TABLE_HEADING_SORT_D1; ?>">-</a></td>
<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_POINTS_REDEEMED; ?></td>
<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_POINTS_VALUE; ?></td>
<?php /* ?><td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=expires-asc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_EXPIRES . TABLE_HEADING_SORT_U1 . '">+</a>&nbsp;' . TABLE_HEADING_POINTS_EXPIRES . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('viewedSort','cID','page')) . 'viewedSort=expires-desc') . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_POINTS_EXPIRES . TABLE_HEADING_SORT_D1; ?>">-</a></td><?php */ ?>
<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
</tr>
<?php
$search = '';
if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
	$keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
	$search = "where customers_email_address ='".$keywords."' or customers_id LIKE '%" . $keywords . "%' or customers_lastname like '%" . $keywords . "%' or customers_firstname LIKE '%" . $keywords . "%' or customers_points_expires like '%" . date("Y-". $keywords) . "%'";
}

$filter = $_GET['filter'];
switch ($filter) {
	case '1':
		$filter = '';
		break;
	case '2':
		$filter = "where customers_shopping_points > 0";
		break;
	case '3':
		$filter = "where customers_shopping_points = 0";
		break;
		/*case '4':
		$filter = "where MONTH(customers_dob) = MONTH(DATE_ADD(NOW(),INTERVAL 0 MONTH))";
		break;
		case '5':
		$filter = "where MONTH(customers_dob) = MONTH(DATE_ADD(NOW(),INTERVAL 1 MONTH))";
		break;
		case '6':
		$filter = "where customers_points_expires like '%" . date('Y-m') . "%'";
		break;
		case '7':
		$filter = "where customers_points_expires like '%" . date('Y-m', strtotime('+ 1 month')) . "%'";
		break;
		case '8':
		$filter = "where customers_points_expires = DATE_ADD(NOW(),INTERVAL 1 MONTH)";
		break;*/
}

//sort view  bof
if (isset($_GET['viewedSort'])) {
	$viewedSort = $_GET['viewedSort'];
} else {
	$viewedSort = "customers_lastname";
}

switch ($viewedSort) {
	case "lastname-asc":
		$sort .= "customers_lastname";
		break;
	case "lastname-desc":
		$sort .= "customers_lastname DESC";
		break;
	case "firstname-asc":
		$sort .= "customers_firstname ";
		break;
	case "firstname-desc":
		$sort .= "customers_firstname DESC";
		break;
	case "date-asc":
		$sort .= "customers_dob";
		break;
	case "date-desc":
		$sort .= "customers_dob DESC";
		break;
	case "points-asc":
		$sort .= "customers_shopping_points";
		break;
	case "points-desc":
		$sort .= "customers_shopping_points DESC";
		break;
	case "expires-asc":
		$sort .= "customers_points_expires";
		break;
	case "expires-desc":
		$sort .= "customers_points_expires DESC";
		break;
	default:
		$sort .= "customers_lastname";
}
//sort view  bof
/*
if($search=='' && $filter==''){
$select_redeem_sum = ", sum(cp.points_pending) as points_redeemed";
//$filter = " where c.customers_id = cp.customer_id group by c.customers_id";
}else{
$select_redeem_sum = ", sum(cp.points_pending) as points_redeemed";
}
*/

//echo $customers_query_raw = "select c.customers_id, c.customers_gender, c.customers_lastname, c.customers_firstname, c.customers_dob, c.customers_email_address, c.customers_shopping_points, c.customers_points_expires".$select_redeem_sum." from " . TABLE_CUSTOMERS . " c left join ".TABLE_CUSTOMERS_POINTS_PENDING." cp on c.customers_id = cp.customer_id and cp.points_status=4 " . $search . " " . $filter . " group by c.customers_id order by $sort";

$customers_query_raw = "select customers_id, customers_gender, customers_lastname, customers_firstname, customers_dob, customers_email_address, customers_shopping_points, customers_points_expires from " . TABLE_CUSTOMERS . " " . $search . " " . $filter . " order by $sort";
$customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $customers_query_raw, $customers_query_numrows);
$customers_query = tep_db_query($customers_query_raw);

while ($customers = tep_db_fetch_array($customers_query)) {
	$info_query = tep_db_query("select sum(op.products_quantity * op.final_price) as ordersum from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where customers_id = '" . (int)$customers['customers_id'] . "' and o.orders_id = op.orders_id group by customers_id ");
	$info = tep_db_fetch_array($info_query);

	if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
		$pending_query = tep_db_query("select sum(points_pending) as pending_total from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 1 and customer_id = '" . (int)$customers['customers_id'] . "'");
		$pending = tep_db_fetch_array($pending_query);

		if (is_array($info)) {
			$cInfo_array = array_merge($customers, $pending, $info);
		} else {
			$cInfo_array = array_merge($customers, $pending);
		}
		$cInfo = new objectInfo($cInfo_array);
	}

	if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
		echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
	} else {
		echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
	}
	?>
	<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $customers['customers_lastname']; ?></td>
	<td class="dataTableContent"><?php echo $customers['customers_firstname']; ?></td>
	<?php /* ?><td class="dataTableContent" align="center"><?php echo tep_date_short($customers['customers_dob']); ?></td><?php */ ?>
	<td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_ACTIONS_HISTORY,'customers_id='.$customers['customers_id'].'&'.tep_get_all_get_params(array('customers_id'))); ?>">View</a></td>
	<?php
	$points_redeemed = tep_calculate_customer_redeemed_points($customers['customers_id']);
	?>
	<td class="dataTableContent" align="right"><?php echo number_format($customers['customers_shopping_points'],POINTS_DECIMAL_PLACES); ?></td>
	<td class="dataTableContent" align="right"><?php if ($customers['customers_shopping_points'] > 0) echo $currencies->format($customers['customers_shopping_points'] * REDEEM_POINT_VALUE); ?></td>

	<td class="dataTableContent" align="right"><?php echo number_format($points_redeemed,POINTS_DECIMAL_PLACES); ?></td>
	<td class="dataTableContent" align="right"><?php if ($points_redeemed > 0) echo $currencies->format($points_redeemed * REDEEM_POINT_VALUE); ?></td>
	<?php /* ?><td class="dataTableContent" align="right"><?php if ($customers['customers_points_expires'] > 0) echo tep_date_short($customers['customers_points_expires']); ?></td><?php */ ?>
	<td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
	</tr>
	<?php
}
?>
<tr>
<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
<td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
</tr>
<!-- Yes, you may remove this advertising clause. //-->
<?php /* ?><tr>
<td class="smallText" align="center"><br><br><?php echo TEXT_LINK_CREDIT . '<br><br>POINTS AND REWARDS MODULE V' . MOD_VER; ?>&nbsp;&nbsp;<a href="http://www.deep-silver.com" target="_blank">Copyright &copy; Deep Silver Accessory</a></td>
</tr><?php */ ?>
<!-- advertising_eof //-->
<?php
if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
	?>
	<tr>
	<td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
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
	case 'addpoints':
		
		if($can_edit_customers_points === true){
			$heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
	
			$contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params() . 'cID=' . $cInfo->customers_id . '&action=addconfirm'));
			$value_field = '<b>'. TEXT_ADD_POINTS . '</b><br>'. TEXT_ADD_POINTS_LONG . '<br><br>' . TEXT_POINTS_TO_ADD . '<br>'. tep_draw_input_field('points_to_add', '' , 'onBlur="validate(this)"');
			$contents[] = array('text' => $value_field);
			//$value_field = TEXT_COMMENT. '<br>'. tep_draw_input_field('comment', 0, ' style="height:50px; width:220px;"');
			$value_field = TEXT_COMMENT. '<br>'. tep_draw_textarea_field('comment', 'soft', '35', '5','');
			$contents[] = array('text' => $value_field);
			$contents[] = array('text' => tep_draw_checkbox_field('notify', '', true) . ' ' . TEXT_NOTIFY_CUSTOMER);
			//$contents[] = array('text' => tep_draw_checkbox_field('queue_add') . ' ' . TEXT_QUEUE_POINTS_TABLE);
			$contents[] = array('text' => tep_draw_hidden_field('customers_firstname', $cInfo->customers_firstname) . tep_draw_hidden_field('customers_lastname', $cInfo->customers_lastname) . tep_draw_hidden_field('customers_gender', $cInfo->customers_gender) . tep_draw_hidden_field('customers_email_address', $cInfo->customers_email_address) . tep_draw_hidden_field('customers_shopping_points', $cInfo->customers_shopping_points) . tep_draw_hidden_field('customers_points_expires', $cInfo->customers_points_expires));
	
			$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_add_points.gif', BUTTON_TEXT_ADD_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
			break;
		}
	
	case 'adjust':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_ADJUST_POINTS . '</b>');

		$contents = array('form' => tep_draw_form('points', FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=adjustpoints'));
		$contents[] = array('text' => '<b>'. TEXT_INFO_HEADING_ADJUST_POINTS . '</b><br>');
		$value_field = TEXT_ADJUST_INTRO . '<br><br>' . TEXT_POINTS_TO_ADJUST . '<br>'. tep_draw_input_field('points_to_aj', '' , 'onkeyup="validate(this)"');
		$contents[] = array('text' => $value_field);
		$contents[] = array('text' => tep_draw_hidden_field('customers_points_expires', $cInfo->customers_points_expires));

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
	
	break;
	case 'deletepoints':
		$heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

		$contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=delconfirm'));
		$value_field = '<b>'. TEXT_DELETE_POINTS . '</b><br>'. TEXT_DELETE_POINTS_LONG . '<br><br>' . TEXT_POINTS_TO_DELETE . '<br>'. tep_draw_input_field('points_to_delete', '' , 'onBlur="validate(this)"');
		$contents[] = array('text' => $value_field);
		//$value_field = TEXT_COMMENT. '<br>'. tep_draw_input_field('comment', 0, ' style="height:50px; width:220px;"');
		$value_field = TEXT_COMMENT. '<br>'. tep_draw_textarea_field('comment', 'soft', '35', '5','');
		$contents[] = array('text' => $value_field);
		//$contents[] = array('text' => tep_draw_checkbox_field('queue_delete') . ' ' . TEXT_QUEUE_POINTS_TABLE);
		$contents[] = array('text' => tep_draw_checkbox_field('notify', '', true) . ' ' . TEXT_NOTIFY_CUSTOMER);
		$contents[] = array('text' => tep_draw_hidden_field('customers_firstname', $cInfo->customers_firstname) . tep_draw_hidden_field('customers_lastname', $cInfo->customers_lastname) . tep_draw_hidden_field('customers_gender', $cInfo->customers_gender) . tep_draw_hidden_field('customers_email_address', $cInfo->customers_email_address) . tep_draw_hidden_field('customers_shopping_points', $cInfo->customers_shopping_points) . tep_draw_hidden_field('customers_points_expires', $cInfo->customers_points_expires));

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete_points.gif', BUTTON_TEXT_DELETE_POINTS) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	default:
		if (isset($cInfo) && is_object($cInfo)) {
			$heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

			if($can_edit_customers_points === true){
				if ($cInfo->customers_shopping_points > 0) {
					$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=addpoints') . '">' . tep_image_button('button_add_points.gif', BUTTON_TEXT_ADD_POINTS) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deletepoints') . '">' . tep_image_button('button_delete_points.gif', BUTTON_TEXT_DELETE_POINTS) . '</a>  <a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
					/*<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=adjust') . '">' . tep_image_button('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . '</a>*/
				} else {
					$contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=addpoints') . '">' . tep_image_button('button_add_points.gif', BUTTON_TEXT_ADD_POINTS) . '</a>  <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
					/*<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=adjust') . '">' . tep_image_button('button_adjust_points.gif', BUTTON_TEXT_ADJUST_POINTS) . '</a>*/
				}
			}
			
			$contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_ORDERS . ' ' . $currencies->format($cInfo->ordersum));
			$contents[] = array('text' => TEXT_INFO_NUMBER_OF_PENDING . ' ' . number_format($cInfo->pending_total,POINTS_DECIMAL_PLACES));
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
