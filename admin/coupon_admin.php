<?php
/*
$Id: coupon_admin.php,v 1.2 2004/03/09 17:56:06 ccwjr Exp $
$Id: coupon_admin.php,v 1.2 2004/03/09 17:56:06 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('coupon_admin');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

if ($_GET['selected_box']) {
	$_GET['action']='';
	$_GET['old_action']='';
}

if (($_GET['action'] == 'send_email_to_user') && ($_POST['customers_email_address']) && (!$_POST['back_x'])) {
	switch ($_POST['customers_email_address']) {
		case '***':
			$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
			$mail_sent_to = TEXT_ALL_CUSTOMERS;
			break;
		case '**D':
			$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
			$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
			break;
		default:
			$customers_email_address = tep_db_prepare_input($_POST['customers_email_address']);
			$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
			$mail_sent_to = $_POST['customers_email_address'];
			break;
	}
	$coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'");
	$coupon_result = tep_db_fetch_array($coupon_query);
	$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $languages_id . "'");
	$coupon_name = tep_db_fetch_array($coupon_name_query);

	$from = tep_db_prepare_input($_POST['from']);
	$subject = tep_db_prepare_input($_POST['subject']);
	while ($mail = tep_db_fetch_array($mail_query)) {
		$message = tep_db_prepare_input($_POST['message']);
		$message .= "\n\n" . TEXT_TO_REDEEM . "\n\n";
		$message .= TEXT_VOUCHER_IS . $coupon_result['coupon_code'] . "\n\n";
		$message .= TEXT_REMEMBER . "\n\n";
		$message .= TEXT_VISIT . "\n\n";

		//Let's build a message object using the email class
		$mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
		// add the message to the object
		// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Email when WYSIWYG Disabled)
		if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {
			$mimemessage->add_text($message);
		} else {
			$mimemessage->add_html($message);
		}
		// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Email when WYSIWYG Enabled)
		$mimemessage->build_message();
		$mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
	}

	tep_redirect(tep_href_link(FILENAME_COUPON_ADMIN, 'mail_sent_to=' . urlencode($mail_sent_to)));
}

if ( ($_GET['action'] == 'preview_email') && (!$_POST['customers_email_address']) ) {
	$_GET['action'] = 'email';
	$messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
}

if ($_GET['mail_sent_to']) {
	$messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
}

switch ($_GET['action']) {
	case 'batchdelete':
		//  批量删除优惠券
		$coupon_id = $_GET['coupon_id'];
		$coupon_id_arr = array_filter(explode(',', $coupon_id));

		for ($i=0; $i<count($coupon_id_arr); $i++){

			$sql = tep_db_query('select coupon_name from '.TABLE_COUPONS_DESCRIPTION.' where coupon_id="'.$coupon_id_arr[$i].'" ');
			$row = tep_db_fetch_array($sql);
			$loop_sql = tep_db_query('select coupon_id from '.TABLE_COUPONS_DESCRIPTION.' where coupon_name="'.$row['coupon_name'].'" ');
			while($loop= tep_db_fetch_array($loop_sql)){
				$delete_query=tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id='".$coupon_id_arr[$i]."' || coupon_id='".$loop['coupon_id']."' ");
				//echo $row['coupon_name'];
				//exit;
			}

		}
		break;
	case 'confirmdelete':
		//删除start
		$sql = tep_db_query('select coupon_name from '.TABLE_COUPONS_DESCRIPTION.' where coupon_id="'.$_GET['cid'].'" ');
		$row = tep_db_fetch_array($sql);
		$loop_sql = tep_db_query('select coupon_id from '.TABLE_COUPONS_DESCRIPTION.' where coupon_name="'.$row['coupon_name'].'" ');
		while($loop= tep_db_fetch_array($loop_sql)){
			$delete_query=tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id='".$_GET['cid']."' || coupon_id='".$loop['coupon_id']."' ");
			//echo $row['coupon_name'];
			//exit;
		}
		//删除end
		break;
	case 'update':
		
		// get all _POST and validate
		$_POST['email_mark']=trim($_POST['email_mark']);
		$_POST['coupon_code'] = trim($_POST['coupon_code']);
		$languages = tep_get_languages();
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$language_id = $languages[$i]['id'];
			$_POST['coupon_name'][$language_id] = trim($_POST['coupon_name'][$language_id]);
			$_POST['coupon_desc'][$language_id] = trim($_POST['coupon_desc'][$language_id]);
		}
		$_POST['coupon_amount'] = trim($_POST['coupon_amount']);
		$update_errors = 0;
		if (!$_POST['coupon_name']) {
			$update_errors = 1;
			$messageStack->add(ERROR_NO_COUPON_NAME, 'error');
		}
		if ((!$_POST['coupon_amount']) && (!$_POST['coupon_free_ship'])) {
			$update_errors = 1;
			$messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
		}
		if (!$_POST['coupon_code']) {
			$coupon_code = create_coupon_code();
		}
		if ($_POST['coupon_code']) $coupon_code = $_POST['coupon_code'];

		$same_name_sql = tep_db_query('select coupon_id from '.TABLE_COUPONS_DESCRIPTION.' where coupon_name="'.tep_db_prepare_input($_POST['coupon_name'][$language_id]).'" Limit 1');

		$query1 = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . tep_db_prepare_input($coupon_code) . "' ");
		if ((tep_db_num_rows($query1) || tep_db_num_rows($same_name_sql)) && $_POST['coupon_code'] && $_GET['oldaction'] != 'voucheredit')  {
			$update_errors = 1;
			$messageStack->add(ERROR_COUPON_EXISTS, 'error');
		}

		if(substr(preg_replace('/.*\D/','',$coupon_code),0,1)=="0" && (int)$_POST["biluk_add_num"]){
			$update_errors = 1;
			$messageStack->add(JS_BILUK_ADD_NOTES, 'error');
		}

		if ($update_errors != 0) {
			$_GET['action'] = 'new';
		} else {
			
			$_GET['action'] = 'update_preview';
		}
		break;
	case 'update_confirm':
		if ( ($_POST['back_x']) || ($_POST['back_y']) ) {
			$_GET['action'] = 'new';
		} else {
			
			$coupon_type = "F";
			if (substr($_POST['coupon_amount'], -1) == '%') $coupon_type='P';
			if ($_POST['coupon_free_ship']) $coupon_type = 'S';
			$sql_data_array = array('coupon_code' => tep_db_prepare_input($_POST['coupon_code']),
									'coupon_amount' => tep_db_prepare_input($_POST['coupon_amount']),
									'coupon_type' => tep_db_prepare_input($coupon_type),
									'uses_per_coupon' => tep_db_prepare_input($_POST['coupon_uses_coupon']),
									'uses_per_user' => tep_db_prepare_input($_POST['coupon_uses_user']),
									'coupon_minimum_order' => tep_db_prepare_input($_POST['coupon_min_order']),
									'coupon_order_min_customers_num' => tep_db_prepare_input($_POST['coupon_order_min_customers_num']),
									'restrict_to_products' => tep_db_prepare_input($_POST['coupon_products']),
									'restrict_to_categories' => tep_db_prepare_input($_POST['coupon_categories']),
									'coupon_start_date' => $_POST['coupon_startdate'],
									'coupon_expire_date' => $_POST['coupon_finishdate'],
									'date_created' => 'now()',
									'date_modified' => 'now()',
									'check_email'=>tep_db_prepare_input($_POST['email_mark']),
									'need_customers_active' => (int)$_POST['need_customers_active']); 
			$languages = tep_get_languages();
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
				$language_id = $languages[$i]['id'];
				$sql_data_marray[$i] = array('coupon_name' => tep_db_prepare_input($_POST['coupon_name'][$language_id]),
											'coupon_description' => tep_db_prepare_input($_POST['coupon_desc'][$language_id]),
											'use_range' => tep_db_prepare_input($_POST['use_range'])
											);
			}
			//        $query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . tep_db_prepare_input($_POST['coupon_code']) . "'");
			//        if (!tep_db_num_rows($query)) {
			if ($_GET['oldaction']=='voucheredit') {	//update
				$coupon_id_string = $_GET['cid'];
				//批量操作 start
				if($_POST['Bulk_Action'] == '1'){
					$sql = tep_db_query('select coupon_name from '.TABLE_COUPONS_DESCRIPTION.' where coupon_id="'.$_GET['cid'].'" ');
					$row = tep_db_fetch_array($sql);
					$loop_sql = tep_db_query('select coupon_id from '.TABLE_COUPONS_DESCRIPTION.' where coupon_name="'.$row['coupon_name'].'" ');
					while($loop= tep_db_fetch_array($loop_sql)){
						$coupon_id_string .= ','.$loop['coupon_id'];
					}
				}
				//批量操作 end
				unset($sql_data_array['coupon_code']);	//howard fixed 不可以批量修改优惠券编号
				tep_db_perform(TABLE_COUPONS, $sql_data_array, 'update', "coupon_id in(".$coupon_id_string.") ");
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
					$language_id = $languages[$i]['id'];
					$update = tep_db_query("update " . TABLE_COUPONS_DESCRIPTION . " set coupon_name = '" . tep_db_prepare_input($_POST['coupon_name'][$language_id]) . "', coupon_description = '" . tep_db_prepare_input($_POST['coupon_desc'][$language_id]) . "' where coupon_id in(".$coupon_id_string.") and language_id = '" . $language_id . "'");
					//            tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i], 'update', "coupon_id='" . $_GET['cid']."'");
				}
			} else {	//added
				$biluk_add_num = (int)$_POST["biluk_add_num"];
				$suffix_random_number_length = (int)$_POST["suffix_random_number_length"];
				preg_match('/(\d+)$/',$_POST['coupon_code'],$m);
				$num_start = $end_code = $m[1];
				if((int)$biluk_add_num){	//批量添加判断
					$end_code = $num_start+$biluk_add_num;
				}
				if($num_start == $end_code){ $end_code++; }
				for($i = $num_start; $i<$end_code; $i++){
					$sql_data_array['coupon_code'] = tep_db_prepare_input(preg_replace('/'.$m[1].'/','',$_POST['coupon_code']).$i);
					//添加随机数
					if($suffix_random_number_length>0){
						$_random_number = mt_rand(str_pad(1,$suffix_random_number_length,1,STR_PAD_RIGHT), str_pad(9,$suffix_random_number_length,9,STR_PAD_RIGHT));
						$sql_data_array['coupon_code'] .= '-'.$_random_number;
					}
					$query = tep_db_perform(TABLE_COUPONS, $sql_data_array);
					$insert_id = tep_db_insert_id($query);

					for ($j = 0, $n = sizeof($languages); $j < $n; $j++) {
						$language_id = $languages[$j]['id'];
						$sql_data_marray[$j]['coupon_id'] = $insert_id;
						$sql_data_marray[$j]['language_id'] = $language_id;
						tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$j]);
					}
				}

				//        }
			}
		}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
  <script language="Javascript1.2"><!-- // load htmlarea
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <head>
_editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
<?php if (HTML_AREA_WYSIWYG_BASIC_EMAIL == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
	document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
	document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
<?php } else{ ?> if (win_ie_ver >= 5.5) {
	document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
	document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
<?php }?>
// --></script>
       <script language="JavaScript" src="htmlarea/validation.js"></script>
       <script language="JavaScript">
       <!-- Begin
       function init() {
       	define('customers_email_address', 'string', 'Customer or Newsletter Group');
       }

       //check_coupon
       function check_coupon(){
       	var c_from = document.getElementById("coupon");
       	var b_num = c_from.elements["biluk_add_num"];
       	if(typeof(b_num)!="undefined" && b_num.value!=""){
       		if(b_num.value.search(/^\d+$/)==-1){
       		alert("<?=JS_BILUK_ADD_NOTES_NUM?>");
       		return false;
       	}
       	b_num_value = parseInt(b_num.value);
       	var CouponCode = c_from.elements["coupon_code"];
       	if(CouponCode.value.search(/\d$/)==-1 && CouponCode.value!=""){
       		alert("<?=JS_BILUK_ADD_NOTES?>");
       		CouponCode.focus();
       		CouponCode.select();
       		return false;
       	}
       }
       return true;
}

function from_check(){
	var f = document.getElementById("coupon");
	var error = check_coupon();
	if(error==0){
		return false;
	}else{
		f.submit();
	}
}
//  End -->
</script>
</head>
<body OnLoad="init()" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('coupon_admin');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<?php
switch ($_GET['action']) {
	case 'voucherreport':
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo CUSTOMER_NAME; ?></td>
                <td class="dataTableHeadingContent">Order ID</td>
                <td class="dataTableHeadingContent">Orders Total</td>
                <td class="dataTableHeadingContent" align="center"><?php echo IP_ADDRESS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo REDEEM_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
$cc_query_raw = "select crt.*, ot.value from " . TABLE_COUPON_REDEEM_TRACK . " crt, `orders` o, `orders_total` ot where coupon_id = '" . $_GET['cid'] . "' AND crt.order_id=o.orders_id AND ot.orders_id = o.orders_id AND o.orders_status !='6' AND ot.class ='ot_total' ";
$cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $cc_query_raw, $cc_query_numrows);
$cc_query = tep_db_query($cc_query_raw);
while ($cc_list = tep_db_fetch_array($cc_query)) {
	$rows++;
	if (strlen($rows) < 2) {
		$rows = '0' . $rows;
	}
	if (((!$_GET['uid']) || (@$_GET['uid'] == $cc_list['unique_id'])) && (!$cInfo)) {
		$cInfo = new objectInfo($cc_list);
	}
	if ( (is_object($cInfo)) && ($cc_list['unique_id'] == $cInfo->unique_id) ) {
		echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cInfo->coupon_id . '&action=voucherreport&uid=' . $cinfo->unique_id) . '\'">' . "\n";
	} else {
		echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cc_list['coupon_id'] . '&action=voucherreport&uid=' . $cc_list['unique_id']) . '\'">' . "\n";
	}
	$customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cc_list['customer_id'] . "'");
	$customer = tep_db_fetch_array($customer_query);

?>
                <td class="dataTableContent"><?php echo $cc_list['customer_id']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $customer['customers_firstname'] . ' ' . $customer['customers_lastname']; ?></td>
               <td class="dataTableContent"><a href="<?php echo tep_href_link('orders.php','action=edit&oID='.$cc_list['order_id']);?>" target="_blank"><?php echo $cc_list['order_id']; ?></a></td>
			   <td class="dataTableContent"><?php echo $currencies->format($cc_list['value']);?></td>
			    <td class="dataTableContent" align="center"><?php echo $cc_list['redeem_ip']; ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($cc_list['redeem_date']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list['unique_id'] == $cInfo->unique_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$total += $cc_list['value'];
}
?>

            <tr>
                <td class="dataTableHeadingContent" style="color:#000">订单总计:<?php echo intval($rows); ?></td>
                <td  class="dataTableHeadingContent" style="color:#000">金额总计:<?php echo $currencies->format($total); ?></td>
            </tr>

             </table></td>
<?php
$heading = array();
$contents = array();
$coupon_description_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $languages_id . "'");
$coupon_desc = tep_db_fetch_array($coupon_description_query);
$count_customers = tep_db_query("select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $_GET['cid'] . "' and customer_id = '" . $cInfo->customer_id . "'");

$heading[] = array('text' => '<b>[' . $_GET['cid'] . ']' . COUPON_NAME . ' ' . $coupon_desc['coupon_name'] . '</b>');
$contents[] = array('text' => '<b>' . TEXT_REDEMPTIONS . '</b>');
$contents[] = array('text' => TEXT_REDEMPTIONS_TOTAL . '=' . tep_db_num_rows($cc_query));
$contents[] = array('text' => TEXT_REDEMPTIONS_CUSTOMER . '=' . tep_db_num_rows($count_customers));
$contents[] = array('text' => '');
?>
    
<?php
echo '<td width="25%" valign="top">';
$box = new box;
echo $box->infoBox($heading, $contents);
echo '            </td>' . "\n";
?>
<?php
break;
	case 'preview_email':
		$coupon_query = tep_db_query("select coupon_code from " .TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'");
		$coupon_result = tep_db_fetch_array($coupon_query);
		$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $languages_id . "'");
		$coupon_name = tep_db_fetch_array($coupon_name_query);
		switch ($_POST['customers_email_address']) {
			case '***':
				$mail_sent_to = TEXT_ALL_CUSTOMERS;
				break;
			case '**D':
				$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				break;
			default:
				$mail_sent_to = $_POST['customers_email_address'];
				break;
		}
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <tr><?php echo tep_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=send_email_to_user&cid=' . $_GET['cid']); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br><?php echo $coupon_name['coupon_name']; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo tep_htmlspecialchars(stripslashes($_POST['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo tep_htmlspecialchars(stripslashes($_POST['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { echo (stripslashes($_POST['message'])); } else { echo tep_htmlspecialchars(stripslashes($_POST['message'])); } ?></b></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
reset($_POST);
while (list($key, $value) = each($_POST)) {
	if (!is_array($_POST[$key])) {
		echo tep_draw_hidden_field($key, tep_htmlspecialchars(stripslashes($value)));
	}
}
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php ?>&nbsp;</td>
                     <tr>
                     <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>

                           </tr>
                    <td class="smallText">
                <?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable'){echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                } ?><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {echo(TEXT_EMAIL_BUTTON_HTML);
                 } else { echo(TEXT_EMAIL_BUTTON_TEXT); } ?>
                    </td>
                  </tr>
                </table></td>
             </tr>
            </table></td>
          </form></tr>
<?php
break;
	case 'email':
		$coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'");
		$coupon_result = tep_db_fetch_array($coupon_query);
		$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $languages_id . "'");
		$coupon_name = tep_db_fetch_array($coupon_name_query);
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>

          <tr><?php echo tep_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=preview_email&cid='. $_GET['cid']); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
$customers = array();
$customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
$customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
$customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
$mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
while($customers_values = tep_db_fetch_array($mail_query)) {
	$customers[] = array('id' => $customers_values['customers_email_address'],
	'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
}
?>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_COUPON; ?>&nbsp;&nbsp;</td>
                <td><?php echo $coupon_name['coupon_name']; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?>&nbsp;&nbsp;</td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FROM; ?>&nbsp;&nbsp;</td>
                <td><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
/*
<tr>
<td class="main"><?php echo TEXT_RESTRICT; ?>&nbsp;&nbsp;</td>
<td><?php echo tep_draw_checkbox_field('customers_restrict', $customers_restrict);?></td>
</tr>
<tr>
<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
</tr>
*/
?>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?>&nbsp;&nbsp;</td>
                <td><?php echo tep_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>

<?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { ?>
          <script language="JavaScript1.2" defer>
          // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email - <body>
          var config = new Object();  // create new config object
          config.width = "<?php echo EMAIL_AREA_WYSIWYG_WIDTH; ?>px";
          config.height = "<?php echo EMAIL_AREA_WYSIWYG_HEIGHT; ?>px";
          config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
          config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
          editor_generate('message',config);
          <?php }
          // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <body>
          ?>
          </script>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                 <td colspan="2" align="right">
                 <?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable'){ echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
                 } else {
                echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); }?>
                </td>
              </tr>
            </table></td>
          </form></tr>

      </tr>
      </td>
<?php
break;
	case 'update_preview':
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php echo tep_draw_form('coupon', 'coupon_admin.php', 'action=update_confirm&oldaction=' . $_GET['oldaction'] . '&cid=' . $_GET['cid']); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_NAME; ?></td>
        <td align="left"><?php echo $_POST['coupon_name'][$language_id]; ?></td>
      </tr>
<?php
}
?>
<?php
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_DESC; ?></td>
        <td align="left"><?php echo $_POST['coupon_desc'][$language_id]; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo $_POST['coupon_amount']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo $_POST['coupon_min_order']; ?></td>
      </tr>
	  
      <tr>
        <td align="left">订单最少人数</td>
        <td align="left"><?php echo $_POST['coupon_order_min_customers_num']; ?></td>
      </tr>

      <?php
      $use_ship=false;
      if($use_ship==true){
	  ?>
	  <tr>
        <td align="left"><?php echo COUPON_FREE_SHIP; ?></td>
<?php
if ($_POST['coupon_free_ship']) {
?>
        <td align="left"><?php echo TEXT_FREE_SHIPPING; ?></td>
<?php
} else {
?>
        <td align="left"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
<?php
}
?>
      </tr>
	  <?php
      }
	  ?>
	  
      <tr>
        <td align="left"><?php echo COUPON_CODE; ?></td>
<?php
if ($_POST['coupon_code']) {
	$c_code = $_POST['coupon_code'];
} else {
	$c_code = $coupon_code;
}
?>
        <td align="left"><?php echo $coupon_code; ?></td>
      </tr>
	  
	  <?php
	  if($_POST['biluk_add_num']>0){
	  ?>
	  <tr>
	  <td align="left"><?php echo BILUK_ADD; ?></td>
	  <td align="left">
	  <?php
	  preg_match('/(\d+)$/',$_POST['coupon_code'],$m);
	  $num_start = $m[1];
	  $end_code = $num_start+$_POST['biluk_add_num']-1;
	  echo BILUK_ADD_START_NUM.$_POST['coupon_code']."&nbsp;&nbsp;".BILUK_ADD_START_END.preg_replace('/'.$m[1].'/','',$_POST['coupon_code']).$end_code;
	  ?>
	  </td>
	  </tr>
	  <?php
	  }
	  ?>
	  
	  <?php
	  if($_POST['suffix_random_number_length']>0){
	  ?>
	  <tr>
	  <td align="left"><?php echo SUFFIX_RANDOM_NUMBER_LENGTH; ?></td>
	  <td align="left">
	  <?php
	  echo $_POST['suffix_random_number_length'];
	  ?>
	  </td>
	  </tr>
	  <?php
	  }
	  ?>

      <tr>
        <td align="left"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_coupon']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_user']; ?></td>
      </tr>

       <tr>
        <td align="left"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo $_POST['coupon_products']; ?></td>
      </tr>


      <tr>
        <td align="left"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo $_POST['coupon_categories']; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo USE_RANGE; ?></td>
        <td align="left"><?php echo $_POST['use_range']; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo NEED_USER_ACTIVE; ?></td>
        <td align="left"><?php echo $_POST['need_customers_active']; ?></td>
      </tr>
	  <tr>
        <td align="left"><?php echo db_to_html('需要用到邮箱结尾标记'); ?></td>
        <td align="left"><?php echo $_POST['email_mark']; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo COUPON_STARTDATE; ?></td>
<?php
$start_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $start_date; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_FINISHDATE; ?></td>
<?php
$finish_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] ));
echo date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $finish_date; ?></td>
      </tr>
<?php
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$language_id = $languages[$i]['id'];
	echo tep_draw_hidden_field('coupon_name[' . $languages[$i]['id'] . ']', $_POST['coupon_name'][$language_id]);
	echo tep_draw_hidden_field('coupon_desc[' . $languages[$i]['id'] . ']', $_POST['coupon_desc'][$language_id]);
}
echo tep_draw_hidden_field('coupon_amount', $_POST['coupon_amount']);
echo tep_draw_hidden_field('coupon_min_order', $_POST['coupon_min_order']);
echo tep_draw_hidden_field('coupon_order_min_customers_num', $_POST['coupon_order_min_customers_num']);

echo tep_draw_hidden_field('coupon_free_ship', $_POST['coupon_free_ship']);
echo tep_draw_hidden_field('coupon_code', $c_code);
echo tep_draw_hidden_field('coupon_uses_coupon', $_POST['coupon_uses_coupon']);
echo tep_draw_hidden_field('coupon_uses_user', $_POST['coupon_uses_user']);
echo tep_draw_hidden_field('coupon_products', $_POST['coupon_products']);
echo tep_draw_hidden_field('coupon_categories', $_POST['coupon_categories']);
echo tep_draw_hidden_field('use_range', $_POST['use_range']);
echo tep_draw_hidden_field('need_customers_active', $_POST['need_customers_active']);

echo tep_draw_hidden_field('coupon_startdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] )));
echo tep_draw_hidden_field('coupon_finishdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] )));
echo tep_draw_hidden_field('biluk_add_num', $_POST['biluk_add_num']);
echo tep_draw_hidden_field('suffix_random_number_length', $_POST['suffix_random_number_length']);
echo tep_draw_hidden_field('email_mark',$_POST['email_mark']);
?>
     <tr>
        <td align="left"><label><input type="checkbox" name="Bulk_Action" value="1" />同时更新与此券同名的优惠券</label> <?php echo tep_image_submit('button_confirm.gif',COUPON_BUTTON_CONFIRM); ?></td>
        <td align="left"><?php echo tep_image_submit('button_back.gif',COUPON_BUTTON_BACK, 'name=back'); ?></td>
      </tr>

      </table></form>
      </tr>

      </table></td>
<?php

break;
	case 'voucheredit':
		$languages = tep_get_languages();
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$language_id = $languages[$i]['id'];
			$coupon_query = tep_db_query("select coupon_name,coupon_description,use_range from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" .  $_GET['cid'] . "' and language_id = '" . $language_id . "'");
			$coupon = tep_db_fetch_array($coupon_query);
			$coupon_name[$language_id] = $coupon['coupon_name'];
			$coupon_desc[$language_id] = $coupon['coupon_description'];
			$use_range = $coupon['use_range'];
		}
		$coupon_query=tep_db_query("select coupon_code, coupon_amount, coupon_type, coupon_minimum_order, coupon_start_date, coupon_expire_date, uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories, need_customers_active, coupon_order_min_customers_num,check_email from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'");
		$coupon=tep_db_fetch_array($coupon_query);
		$coupon_amount = $coupon['coupon_amount'];
		if ($coupon['coupon_type']=='P') {
			$coupon_amount .= '%';
		}
		if ($coupon['coupon_type']=='S') {
			$coupon_free_ship .= true;
		}
		$coupon_min_order = $coupon['coupon_minimum_order'];
		$coupon_order_min_customers_num = $coupon['coupon_order_min_customers_num'];
		$coupon_code = $coupon['coupon_code'];
		$coupon_uses_coupon = $coupon['uses_per_coupon'];
		$coupon_uses_user = $coupon['uses_per_user'];
		$coupon_products = $coupon['restrict_to_products'];
		$coupon_categories = $coupon['restrict_to_categories'];
		$coupon_startdate = $coupon['coupon_start_date'];
		$coupon_expiredate = $coupon['coupon_expire_date'];
		$need_customers_active = $coupon['need_customers_active'];
		$coupon_email=$coupon['check_email'];

	case 'new':
		// set some defaults
		if (!$coupon_uses_user) $coupon_uses_user=1;
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php
echo tep_draw_form('coupon', 'coupon_admin.php', 'action=update&oldaction='.$_GET['action'] . '&cid=' . $_GET['cid'], 'post', ' id="coupon" onsubmit="from_check(); return false;" ');
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
      <tr>
        <td align="left" class="main"><?php echo COUPON_CODE; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_code', $coupon_code, ' onBlur="check_coupon()" '); ?></td>
        <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
      </tr>
     <?php
     //批量添加选项 start
     if($action=="new" || $oldaction=="new"){
	 ?> 
	  <tr>
        <td class="main" align="left"><?php echo BILUK_ADD; ?></td>
        <td align="left">
		<?php echo tep_draw_input_field('biluk_add_num','',' style="ime-mode: disabled;" onBlur="check_coupon()" ')?>
      </td>
      <td class="main"><?php echo BILUK_ADD_HELP; ?></td>
	  </tr>
	  <tr>
        <td class="main" align="left"><?php echo SUFFIX_RANDOM_NUMBER_LENGTH; ?></td>
        <td align="left">		
		<?php echo tep_draw_input_num_en_field('suffix_random_number_length');?>
      </td>
      <td class="main"><?php echo SUFFIX_RANDOM_NUMBER_LENGTH_HELP; ?></td>
	  </tr>
	  <?php
     }
     //批量添加选项 end
	  ?>
	  
<?php
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left" class="main"><?php if ($i==0) echo COUPON_NAME; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', $coupon_name[$language_id]) . '&nbsp;' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
        <td align="left" class="main" width="40%"><?php if ($i==0) echo COUPON_NAME_HELP; ?></td>
      </tr>
<?php
}
?>
<?php
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$language_id = $languages[$i]['id'];
?>

      <tr>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC; ?></td>
        <td align="left" valign="top"><?php echo tep_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']','physical','24','3', $coupon_desc[$language_id]) . '&nbsp;' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC_HELP; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left" class="main"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_amount', $coupon_amount); ?></td>
        <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_min_order', $coupon_min_order); ?></td>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main">订单最少人数</td>
        <td align="left"><?php echo tep_draw_input_field('coupon_order_min_customers_num', $coupon_order_min_customers_num); ?></td>
        <td align="left" class="main">当行程总人数最少需要达到多少才能使用此订单！例如最少要3人才能使用此券则输入3，如果为0或不填则代表不限制最少人数。</td>
      </tr>
     <?php if($use_ship==true){ ?>
	  <tr>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP; ?></td>
        <td align="left"><?php echo tep_draw_checkbox_field('coupon_free_ship', $coupon_free_ship); ?></td>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
      </tr>
	  <?php
     }
	  ?>
	  
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_uses_coupon', $coupon_uses_coupon); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_uses_user', $coupon_uses_user); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
      </tr>
       <tr>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_products', $coupon_products); ?> <A HREF="validproducts.php" TARGET="_blank" ONCLICK="window.open('validproducts.php', 'Valid_Products', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo tep_draw_input_field('coupon_categories', $coupon_categories); ?> <A HREF="validcategories.php" TARGET="_blank" ONCLICK="window.open('validcategories.php', 'Valid_Categories', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES_HELP; ?></td>
      </tr>
      <tr>
<?php
if(tep_not_null($_POST['coupon_startdate'])) {
	$coupon_startdate = split("[-]", $_POST['coupon_startdate']);
}elseif(tep_not_null($coupon_startdate)) {
	$coupon_startdate = split("[-]", $coupon_startdate);
}else{
	$coupon_startdate = split("[-]", date('Y-m-d'));
}
if (tep_not_null($_POST['coupon_finishdate'])) {
	$coupon_finishdate = split("[-]", $_POST['coupon_finishdate']);
}elseif(tep_not_null($coupon_expiredate)){
	$coupon_finishdate = split("[-]", $coupon_expiredate);
}else {
	$coupon_finishdate = split("[-]", date('Y-m-d'));
	$coupon_finishdate[0] = $coupon_finishdate[0] + 1;
}
?>
        <td align="left" class="main"><?php echo COUPON_STARTDATE; ?></td>
        <td align="left"><?php echo tep_draw_date_selector('coupon_startdate', mktime(0,0,0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0], 0)); ?></td>
        <td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE; ?></td>
        <td align="left"><?php echo tep_draw_date_selector('coupon_finishdate', mktime(0,0,0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0], 0)); ?></td>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo USE_RANGE; ?></td>
        <td align="left"><?php echo tep_draw_input_field('use_range', $use_range); ?></td>
        <td align="left" class="main"><?php echo USE_RANGE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo NEED_USER_ACTIVE; ?></td>
        <td align="left">
		<?php
		if($need_customers_active!="1"){
			$need_customers_active = 0;
		}

		echo tep_draw_radio_field('need_customers_active',"1")." YES&nbsp;&nbsp;";
		echo tep_draw_radio_field('need_customers_active',"0")." NO";
		?>
		</td>
        <td align="left" class="main"><?php echo NEED_USER_ACTIVE_HELP; ?></td>
      </tr>
	  <tr>
	  	<td>邮箱结束标记：</td>
		<td><input type="text" name="email_mark" value="<?=$coupon_email ?>" /></td>
		<td>用于标记邮箱的结束符，比如.edu,.qq 用英文的逗号隔开，如果不启用此功能，请不填写</td>
	  </tr>
	  <tr>
        <td align="left"><?php echo tep_image_submit('button_preview.gif',COUPON_BUTTON_PREVIEW); ?></td>
        <td align="left"><?php echo '&nbsp;&nbsp;<a href="' . tep_href_link('coupon_admin.php', '').'">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>
      </td>
      <td>&nbsp;</td>
	  </tr>
	  
      </table></form>
      </tr>

      </table></td>
<?php
break;
	default:
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <?php echo tep_draw_form('status', FILENAME_COUPON_ADMIN, '', 'get'); ?>
            <td class="main">            
<?php
$status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
$status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
$status_array[] = array('id' => 'O', 'text' => '已产生订单');
$status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);


if ($_GET['status']) {
	$status = tep_db_prepare_input($_GET['status']);
} else {
	$status = 'Y';
}
//echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', $status_array, $status, 'onChange="this.form.submit();"');
echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', $status_array, $status);
?>
              
           </td>
        <!-- by panda 优化优惠券 start -->
           <td>           
            
            <?php 
            $query =  tep_db_query("SELECT DISTINCT `coupon_name` FROM ".TABLE_COUPONS_DESCRIPTION);
            $k = 0;
            while($coupon = tep_db_fetch_array($query)){
            	$coupon_arr[$k]['id'] = $coupon['coupon_name'];
            	$coupon_arr[$k]['text'] = $coupon['coupon_name'];
            	$k++;
            }

            if ($_GET['coupon_name']){
            	$coupon_name = tep_db_prepare_input($_GET['coupon_name']);
            }else{
            	$coupon_name = '';
            }
            if (tep_not_null($_GET['coupon_code'])){
            	$coupon_code = $_GET['coupon_code'];
            }

            echo 'Coupons Name:' . tep_draw_pull_down_menu('coupon_name', $coupon_arr, $coupon_name);
            echo '&nbsp;Coupons Id:'.tep_draw_input_field('coupon_code', $coupon_code);
            ?>
            <input type="submit" name="submit" value="Send"/>
           </td>
        <!-- by panda 优化优惠券 end -->
            </form>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>        
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;</td>
                <td class="dataTableHeadingContent">已有订单</td>
                <td class="dataTableHeadingContent"><?php echo COUPON_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo USE_RANGE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_AMOUNT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>

<?php

$cc_query_raw = "select c.coupon_id, c.coupon_code, c.coupon_amount, c.coupon_type, c.coupon_start_date,c.coupon_expire_date,c.uses_per_user,c.uses_per_coupon,c.restrict_to_products, c.restrict_to_categories, c.date_created,c.date_modified from " . TABLE_COUPONS .' AS c, '.TABLE_COUPONS_DESCRIPTION. ' AS cd ';
$where_exec = " WHERE c.coupon_id = cd.coupon_id ";
if ($_GET['page'] > 1) $rows = $_GET['page'] * 20 - 20;
if (tep_not_null(tep_db_prepare_input($_GET['coupon_name']))){
	$coupon_name = tep_db_prepare_input($_GET['coupon_name']);
	$where_exec .= " AND cd.coupon_name = Binary('". $coupon_name ."') ";
}
if ($status != '*' && $status != 'O') {
	$where_exec .= " AND c.coupon_active='". tep_db_input($status) ."'  AND coupon_type != 'G' ";
} else {
	$where_exec .= "  AND coupon_type != 'G' ";
}
if (tep_not_null($_GET['coupon_code'])){
	$cc_query_raw = $cc_query_raw . $where_exec ." AND c.coupon_code Like '%".$_GET['coupon_code']."%'";
}else{
	$cc_query_raw = $cc_query_raw . $where_exec;
}
if ($status != 'O'){
	$cc_split = new splitPageResults($_GET['page'],60, $cc_query_raw, $cc_query_numrows);
	$cc_query = tep_db_query($cc_query_raw);
}else{
	$cc_query = tep_db_query($cc_query_raw);
}


while ($cc_list = tep_db_fetch_array($cc_query)) {

	$rows++;
	if (strlen($rows) < 2) {
		$rows = '0' . $rows;
	}
	if (((!$_GET['cid']) || (@$_GET['cid'] == $cc_list['coupon_id'])) && (!$cInfo)) {
		$cInfo = new objectInfo($cc_list);
	}

	$coupon_description_query = tep_db_query("select coupon_name, use_range from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cc_list['coupon_id'] . "' and language_id = '" . $languages_id . "'");
	$coupon_desc = tep_db_fetch_array($coupon_description_query);

	// 此优惠券是否有订单
	$o_query_raw = "select crt.*, ot.value from " . TABLE_COUPON_REDEEM_TRACK . " crt, `orders` o, `orders_total` ot where coupon_id = '" . $cc_list['coupon_id'] . "' AND crt.order_id=o.orders_id AND ot.orders_id = o.orders_id AND o.orders_status !='6' AND ot.class ='ot_total' ";
	$o_query = tep_db_query($o_query_raw);
	$o_listes = tep_db_fetch_array($o_query);
	if ($status != 'O'){




?>
        <tr  class="dataTableRowSelected">
                <td class="dataTableContent"><input type="checkbox" value="<?php echo $cc_list['coupon_id']; ?>" name="coupon_ids[]"></td>
                <td class="dataTableContent">
                <?php 


                if (count($o_listes) > 1){
                	echo '<span>Yes</span>';
                }
                ?>
                </td>
    <?php 
    if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) {
    	echo '          <td class="dataTableContent"  onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
    }else{
    	echo '          <td class="dataTableContent"  onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cc_list['coupon_id']) . '\'">' . "\n";
    }
    ?>
                <?php echo $coupon_desc['coupon_name']; ?>
                </td>
				<td class="dataTableContent"><?php echo $coupon_desc['use_range']; ?></td>
                <td class="dataTableContent" align="center">
<?php
if ($cc_list['coupon_type'] == 'P') {
	echo $cc_list['coupon_amount'] . '%';
} elseif ($cc_list['coupon_type'] == 'S') {
	echo TEXT_FREE_SHIPPING;
} else {
	echo $currencies->format($cc_list['coupon_amount']);
}
?>
            &nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $cc_list['coupon_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
	}else{

		if (count($o_listes) > 1){
    ?>
        
        <tr  class="dataTableRowSelected">
                <td class="dataTableContent"><input type="checkbox" value="<?php echo $cc_list['coupon_id']; ?>" name="coupon_ids[]"></td>
                <td class="dataTableContent">
                <?php 


                if (count($o_listes) > 1){
                	echo '<span>Yes</span>';
                }
                ?>
                </td>
    <?php 
    if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) {
    	echo '          <td class="dataTableContent"  onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
    }else{
    	echo '          <td class="dataTableContent"  onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cc_list['coupon_id']) . '\'">' . "\n";
    }
    ?>
                <?php echo $coupon_desc['coupon_name']; ?>
                </td>
				<td class="dataTableContent"><?php echo $coupon_desc['use_range']; ?></td>
                <td class="dataTableContent" align="center">
<?php
if ($cc_list['coupon_type'] == 'P') {
	echo $cc_list['coupon_amount'] . '%';
} elseif ($cc_list['coupon_type'] == 'S') {
	echo TEXT_FREE_SHIPPING;
} else {
	echo $currencies->format($cc_list['coupon_amount']);
}
?>
            &nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $cc_list['coupon_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
    <?php 
		}
	}
}

?>
        <!-- 全选删除start -->
          <tr>
            <td>
                <script type="text/javascript">

                jQuery(function() {
                	jQuery("#checkall").click(function() {
                		if (jQuery(this).attr("checked") == true) { // 全选
                			jQuery("input[name='coupon_ids[]']").each(function() {
                				jQuery(this).attr("checked", true);
                				var checkallids = jQuery("#checkall_ids").val();
                				if (checkallids != ''){
                					jQuery("#checkall_ids").val(checkallids+','+ jQuery(this).attr("value"));
                				}else{
                					jQuery("#checkall_ids").val(jQuery(this).attr("value"));
                				}
                			});
                		} else { // 取消全选
                			jQuery("input[name='coupon_ids[]']").each(function() {
                				jQuery(this).attr("checked", false);
                				jQuery("#checkall_ids").val('');
                			});
                		}
                	});
                });
                function set_delete_coupon_id(){
                	var arrCommChk = jQuery("input[name='coupon_ids[]']:checked");
                	var value = '';
                	for (var i=0;i<arrCommChk.length;i++)
                	{
                		if (i == arrCommChk.length - 1){
                			value += arrCommChk[i].value;
                		}else{
                			value += arrCommChk[i].value + ',';
                		}

                	}
                	if (value != ''){
                		jQuery("#checkall_ids").val(value);
                		jQuery("#delurl").attr("href","<?php echo tep_href_link('coupon_admin.php', 'page=' . $_GET['page'].'&action=batchdelete') ?>" + "&coupon_id=" + value);
                		return true;

                	}else{
                		return false;
                	}

                }
                </script>
                
                <input type="checkbox"  name="checkall" id="checkall">全选&nbsp;
                <input type="hidden"  name="checkall_ids" id="checkall_ids">
                <?php  echo '<a id="delurl" onclick="return set_delete_coupon_id();return false;" href="' . tep_href_link('coupon_admin.php', 'page=' . $_GET['page'].'&action=batchdelete') . '">' . tep_image_button('button_delete.gif', 'Delete') . '</a>'; ?>
                
            </td>
          </tr>
        <!-- 全选删除end -->  
          <tr>
            <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php 
            if ($status != 'O'){


            ?>
              <tr>
                <td class="smallText">&nbsp;<?php echo $cc_split->display_count($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo $cc_split->display_links($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            <?php 
            }
            ?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link('coupon_admin.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>

<?php

$heading = array();
$contents = array();

switch ($_GET['action']) {
	case 'release':
		break;
	case 'voucherreport':
		$heading[] = array('text' => '<b>' . TEXT_HEADING_COUPON_REPORT . '</b>');
		$contents[] = array('text' => TEXT_NEW_INTRO);
		break;
	case 'neww':
		$heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');
		$contents[] = array('text' => TEXT_NEW_INTRO);
		$contents[] = array('text' => '<br>' . COUPON_NAME . '<br>' . tep_draw_input_field('name'));
		$contents[] = array('text' => '<br>' . COUPON_AMOUNT . '<br>' . tep_draw_input_field('voucher_amount'));
		$contents[] = array('text' => '<br>' . COUPON_CODE . '<br>' . tep_draw_input_field('voucher_code'));
		$contents[] = array('text' => '<br>' . COUPON_USES_COUPON . '<br>' . tep_draw_input_field('voucher_number_of'));
		break;
	default:
		$heading[] = array('text'=>'['.$cInfo->coupon_id.']  '.$cInfo->coupon_code);
		$amount = $cInfo->coupon_amount;
		if ($cInfo->coupon_type == 'P') {
			$amount .= '%';
		} else {
			$amount = $currencies->format($amount);
		}
		if ($_GET['action'] == 'voucherdelete') {
			$contents[] = array('text'=> TEXT_CONFIRM_DELETE . '</br></br>' .
			'<a href="'.tep_href_link('coupon_admin.php','action=confirmdelete&cid='.$_GET['cid'],'NONSSL').'">'.tep_image_button('button_confirm.gif','Confirm Delete Voucher').'</a>' .
			'<a href="'.tep_href_link('coupon_admin.php','cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_cancel.gif','Cancel').'</a>'
			);
		} else {
			$prod_details = NONE;
			if ($cInfo->restrict_to_products) {
				$prod_details = '<A HREF="listproducts.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listproducts.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
			}
			$cat_details = NONE;
			if ($cInfo->restrict_to_categories) {
				$cat_details = '<A HREF="listcategories.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listcategories.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
			}
			$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cInfo->coupon_id . "' and language_id = '" . $languages_id . "'");
			$coupon_name = tep_db_fetch_array($coupon_name_query);
			$contents[] = array('text'=>COUPON_NAME . '&nbsp;::&nbsp; ' . $coupon_name['coupon_name'] . '<br>' .
			COUPON_AMOUNT . '&nbsp;::&nbsp; ' . $amount . '<br>' .
			COUPON_STARTDATE . '&nbsp;::&nbsp; ' . tep_date_short($cInfo->coupon_start_date) . '<br>' .
			COUPON_FINISHDATE . '&nbsp;::&nbsp; ' . tep_date_short($cInfo->coupon_expire_date) . '<br>' .
			COUPON_USES_COUPON . '&nbsp;::&nbsp; ' . $cInfo->uses_per_coupon . '<br>' .
			COUPON_USES_USER . '&nbsp;::&nbsp; ' . $cInfo->uses_per_user . '<br>' .
			COUPON_PRODUCTS . '&nbsp;::&nbsp; ' . $prod_details . '<br>' .
			COUPON_CATEGORIES . '&nbsp;::&nbsp; ' . $cat_details . '<br>' .
			DATE_CREATED . '&nbsp;::&nbsp; ' . tep_date_short($cInfo->date_created) . '<br>' .
			DATE_MODIFIED . '&nbsp;::&nbsp; ' . tep_date_short($cInfo->date_modified) . '<br><br>' .
			'<center><a href="'.tep_href_link('coupon_admin.php','action=email&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_email.gif','Email Voucher').'</a>' .
			'<a href="'.tep_href_link('coupon_admin.php','action=voucheredit&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_edit.gif','Edit Voucher').'</a>' .
			'<a href="'.tep_href_link('coupon_admin.php','action=voucherdelete&cid='.$cInfo->coupon_id,'NONSSL').'&page='.$page.'">'.tep_image_button('button_delete.gif','Delete Voucher').'</a>' .
			'<br><a href="'.tep_href_link('coupon_admin.php','action=voucherreport&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_report.gif','Voucher Report').'</a></center>'
			);
		}
		break;
}
?>
    <td width="25%" valign="top">
<?php
$box = new box;
echo $box->infoBox($heading, $contents);
echo '            </td>' . "\n";
}
?>
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
