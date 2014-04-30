<?php
/*
$Id: affiliate_affiliates.php,v 1.1.1.1 2004/03/04 23:38:06 ccwjr Exp $

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
	$remark = new Remark('affiliate_affiliate');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}


//require(DIR_FS_CATALOG.'includes/classes/affiliate.php');
// 验证优惠券通过审核 by lwkai add 2013-08-07
if ($_GET['action'] == 'verifyCouponCode') {
	$v = isset($_GET['v']) ? intval($_GET['v']) : 2;
	$aid = isset($_GET['aid']) ? intval($_GET['aid']) : '0';
	if (($v == 0 || $v == 1) && $aid > 0) { //只有 0 1 两种状态
		$data = array();
		$data['coupon_code_verify'] = $v;
		tep_db_fast_update('affiliate_affiliate', "affiliate_id='" . $aid . "'", $data);
		echo '<script type="text/javascript">alert("操作成功!");location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	
	} else {
		echo '<script type="text/javascript">alert("操作失败!");location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	}
	exit;
}
// 验证优惠券 end 
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

if ($_GET['action']) {
	switch ($_GET['action']) {
		case 'update':
			$affiliate_id = tep_db_prepare_input($_GET['acID']);
			$affiliate_gender = tep_db_prepare_input($_POST['affiliate_gender']);
			$affiliate_firstname = tep_db_prepare_input($_POST['affiliate_firstname']);
			$affiliate_lastname = tep_db_prepare_input($_POST['affiliate_lastname']);
			$affiliate_dob = tep_db_prepare_input($_POST['affiliate_dob']);
			$affiliate_email_address = tep_db_prepare_input($_POST['affiliate_email_address']);
			$affiliate_company = tep_db_prepare_input($_POST['affiliate_company']);
			$affiliate_company_taxid = tep_db_prepare_input($_POST['affiliate_company_taxid']);
			$affiliate_payment_check = tep_db_prepare_input($_POST['affiliate_payment_check']);
			$affiliate_payment_paypal = tep_db_prepare_input($_POST['affiliate_payment_paypal']);
			$affiliate_payment_bank_name = tep_db_prepare_input($_POST['affiliate_payment_bank_name']);
			$affiliate_payment_bank_branch_number = tep_db_prepare_input($_POST['affiliate_payment_bank_branch_number']);
			$affiliate_payment_bank_swift_code = tep_db_prepare_input($_POST['affiliate_payment_bank_swift_code']);
			$affiliate_payment_bank_account_name = tep_db_prepare_input($_POST['affiliate_payment_bank_account_name']);
			$affiliate_payment_bank_account_number = tep_db_prepare_input($_POST['affiliate_payment_bank_account_number']);
			$affiliate_street_address = tep_db_prepare_input($_POST['affiliate_street_address']);
			$affiliate_suburb = tep_db_prepare_input($_POST['affiliate_suburb']);
			$affiliate_postcode=tep_db_prepare_input($_POST['affiliate_postcode']);
			$affiliate_city = tep_db_prepare_input($_POST['affiliate_city']);
			$affiliate_country_id=tep_db_prepare_input($_POST['affiliate_country_id']);
			$affiliate_telephone=tep_db_prepare_input($_POST['affiliate_telephone']);
			$affiliate_fax=tep_db_prepare_input($_POST['affiliate_fax']);
			$affiliate_homepage=tep_db_prepare_input($_POST['affiliate_homepage']);
			$affiliate_state = tep_db_prepare_input($_POST['affiliate_state']);
			$affiliatey_zone_id = tep_db_prepare_input($_POST['affiliate_zone_id']);
			$affiliate_commission_percent = tep_db_prepare_input($_POST['affiliate_commission_percent']);
			$affiliate_commission_percent_coupons = tep_db_prepare_input($_POST['affiliate_commission_percent_coupons']);
			$api_categories_id = (int)$_POST['api_categories_id'];
			$verified = (int)$_POST['verified'];
			$affiliate_type = (int)$_POST['affiliate_type'];

			if ($affiliate_zone_id > 0) $affiliate_state = '';
			// If someone uses , instead of .
			$affiliate_commission_percent = str_replace (',' , '.' , $affiliate_commission_percent);

			$sql_data_array = array('affiliate_firstname' => $affiliate_firstname,
			'affiliate_lastname' => $affiliate_lastname,
			'affiliate_email_address' => $affiliate_email_address,
			'affiliate_payment_check' => $affiliate_payment_check,
			'affiliate_payment_paypal' => $affiliate_payment_paypal,
			'affiliate_payment_bank_name' => $affiliate_payment_bank_name,
			'affiliate_payment_bank_branch_number' => $affiliate_payment_bank_branch_number,
			'affiliate_payment_bank_swift_code' => $affiliate_payment_bank_swift_code,
			'affiliate_payment_bank_account_name' => $affiliate_payment_bank_account_name,
			'affiliate_payment_bank_account_number' => $affiliate_payment_bank_account_number,
			'affiliate_street_address' => $affiliate_street_address,
			'affiliate_postcode' => $affiliate_postcode,
			'affiliate_city' => $affiliate_city,
			'affiliate_country_id' => $affiliate_country_id,
			'affiliate_telephone' => $affiliate_telephone,
			'affiliate_fax' => $affiliate_fax,
			'affiliate_homepage' => $affiliate_homepage,
			'affiliate_commission_percent' => $affiliate_commission_percent,
			'affiliate_commission_percent_coupons' => $affiliate_commission_percent_coupons,
			'affiliate_agb' => '1',
			'api_categories_id' => $api_categories_id,
			'verified' => $verified,
			'affiliate_type' => $affiliate_type,
			);

			if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = tep_date_raw($affiliate_dob);
			if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $affiliate_gender;
			if (ACCOUNT_COMPANY == 'true') {
				$sql_data_array['affiliate_company'] = $affiliate_company;
				$sql_data_array['affiliate_company_taxid'] =  $affiliate_company_taxid;
			}
			if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $affiliate_suburb;
			if (ACCOUNT_STATE == 'true') {
				$sql_data_array['affiliate_state'] = $affiliate_state;
				$sql_data_array['affiliate_zone_id'] = $affiliate_zone_id;
			}

			$sql_data_array['affiliate_date_account_last_modified'] = 'now()';

			tep_db_perform(TABLE_AFFILIATE, $sql_data_array, 'update', "affiliate_id = '" . tep_db_input($affiliate_id) . "'");

			tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate_id));
			break;
		case 'deleteconfirm':
			$affiliate_id = tep_db_prepare_input($_GET['acID']);

			affiliate_delete(tep_db_input($affiliate_id));

			tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action'))));
			break;
	}
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
<script language="javascript" src="includes/general.js"></script>
<?php
if ($_GET['action'] == 'edit') {
?>
<script language="javascript"><!--
function resetStateText(theForm) {
	theForm.affiliate_state.value = '';
	if (theForm.affiliate_zone_id.options.length > 1) {
		theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
	}
}

function resetZoneSelected(theForm) {
	if (theForm.affiliate_state.value != '') {
		theForm.affiliate_zone_id.selectedIndex = '0';
		if (theForm.affiliate_zone_id.options.length > 1) {
			theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
		}
	}
}

function update_zone(theForm) {
	var NumState = theForm.affiliate_zone_id.options.length;
	var SelectedCountry = '';

	while(NumState > 0) {
		NumState--;
		theForm.affiliate_zone_id.options[NumState] = null;
	}

	SelectedCountry = theForm.affiliate_country_id.options[theForm.affiliate_country_id.selectedIndex].value;

	<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'affiliate_zone_id'); ?>

	resetStateText(theForm);
}

function check_form() {
	//不检查任何内容直接通过
	return true;

	var error = 0;
	var error_message = "<?php echo JS_ERROR; ?>";

	var affiliate_firstname = document.affiliate.affiliate_firstname.value;
	var affiliate_lastname = document.affiliate.affiliate_lastname.value;
	<?php if (ACCOUNT_COMPANY == 'true') echo 'var affiliate_company = document.affiliate.affiliate_company.value;' . "\n"; ?>
	var affiliate_email_address = document.affiliate.affiliate_email_address.value;
	var affiliate_street_address = document.affiliate.affiliate_street_address.value;
	var affiliate_postcode = document.affiliate.affiliate_postcode.value;
	var affiliate_city = document.affiliate.affiliate_city.value;
	var affiliate_telephone = document.affiliate.affiliate_telephone.value;

	<?php if (ACCOUNT_GENDER == 'true') { ?>
	if (document.affiliate.affiliate_gender[0].checked || document.affiliate.affiliate_gender[1].checked) {
	} else {
		error_message = error_message + "<?php echo JS_GENDER; ?>";
		error = 1;
	}
	<?php } ?>

	if (affiliate_firstname = "" || affiliate_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
		error = 1;
	}

	if (affiliate_lastname = "" || affiliate_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
		error = 1;
	}

	if (affiliate_email_address = "" || affiliate_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
		error = 1;
	}

	if (affiliate_street_address = "" || affiliate_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_ADDRESS; ?>";
		error = 1;
	}

	if (affiliate_postcode = "" || affiliate_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_POST_CODE; ?>";
		error = 1;
	}

	if (affiliate_city = "" || affiliate_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_CITY; ?>";
		error = 1;
	}

	<?php if (ACCOUNT_STATE == 'true') { ?>
	if (document.affiliate.affiliate_zone_id.options.length <= 1) {
		if (document.affiliate.affiliate_state.value == "" || document.affiliate.affiliate_state.length < 4 ) {
			error_message = error_message + "<?php echo JS_STATE; ?>";
			error = 1;
		}
	} else {
		document.affiliate.affiliate_state.value = '';
		if (document.affiliate.affiliate_zone_id.selectedIndex == 0) {
			error_message = error_message + "<?php echo JS_ZONE; ?>";
			error = 1;
		}
	}
	<?php } ?>

	if (document.affiliate.affiliate_country_id.value == 0) {
		error_message = error_message + "<?php echo JS_COUNTRY; ?>";
		error = 1;
	}

	if (affiliate_telephone = "" || affiliate_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
		error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
		error = 1;
	}

	if (error == 1) {
		alert(error_message);
		return false;
	} else {
		return true;
	}
}
//--></script>
<?php
}
?>
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
<?php
if ($_GET['action'] == 'edit') {
	$affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $_GET['acID'] . "'");
	$affiliate = tep_db_fetch_array($affiliate_query);
	$aInfo = new objectInfo($affiliate);
	?>
	<tr>
	<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr><?php echo tep_draw_form('affiliate', FILENAME_AFFILIATE, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
	<td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
	</tr>
	<tr>
	<td class="formArea"><table border="0" cellspacing="2" cellpadding="2" sensitive="true">
	<?php
	if (ACCOUNT_GENDER == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_GENDER; ?></td>
		<td class="main"><?php echo tep_draw_radio_field('affiliate_gender', 'm', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_gender', 'f', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . FEMALE; ?></td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_firstname', $aInfo->affiliate_firstname, 'maxlength="32"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_lastname', $aInfo->affiliate_lastname, 'maxlength="32"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_email_address', $aInfo->affiliate_email_address, 'maxlength="96"', true); ?></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<?php
	if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
		?>
		<tr>
		<td class="formAreaTitle"><?php echo CATEGORY_COMMISSION; ?></td>
		</tr>
		<tr>
		<td class="formArea"><table border="0" cellspacing="2" cellpadding="2" sensitive="true">
		<tr>
		<td class="main">推广方式：</td>
		<td class="main">
		<?php echo tep_draw_pull_down_menu('affiliate_type', array(array('id'=>0, 'text'=>'普通'),array('id'=>1, 'text'=>'链接+优惠码')), $aInfo->affiliate_type);?>
		&nbsp;
		</td>
		</tr>
		<tr>
		<td class="main">link推广佣金：</td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_commission_percent', $aInfo->affiliate_commission_percent, 'maxlength="5"'); ?>%</td>
		</tr>
		<tr>
		<td class="main">优惠码推广佣金：</td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_commission_percent_coupons', $aInfo->affiliate_commission_percent_coupons, 'maxlength="5"'); ?>%</td>
		</tr>
		</table></td>
		</tr>
		<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
	</tr>
	<tr>
	<td class="formArea"><table border="0" cellspacing="2" cellpadding="2" sensitive="true">
	<tr>
	<td class="main"><?php echo ENTRY_COMPANY; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_company', $aInfo->affiliate_company, 'maxlength="32"'); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_AFFILIATE_COMPANY_TAXID; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_company_taxid', $aInfo->affiliate_company_taxid, 'maxlength="64"'); ?></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
	<td class="formAreaTitle"><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
	</tr>
	<tr>
	<td class="formArea"><table border="0" cellspacing="2" cellpadding="2" sensitive="true">
	<?php
	if (AFFILIATE_USE_CHECK == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_check', $aInfo->affiliate_payment_check, 'maxlength="100"'); ?></td>
		</tr>
		<?php
	}
	if (AFFILIATE_USE_PAYPAL == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_PAYPAL; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_paypal', $aInfo->affiliate_payment_paypal, 'maxlength="64"'); ?></td>
		</tr>
		<?php
	}
	if (AFFILIATE_USE_BANK == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_NAME; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_name', $aInfo->affiliate_payment_bank_name, 'maxlength="64"'); ?></td>
		</tr>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_branch_number', $aInfo->affiliate_payment_bank_branch_number, 'maxlength="64"'); ?></td>
		</tr>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_swift_code', $aInfo->affiliate_payment_bank_swift_code, 'maxlength="64"'); ?></td>
		</tr>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_account_name', $aInfo->affiliate_payment_bank_account_name, 'maxlength="64"'); ?></td>
		</tr>
		<tr>
		<td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_account_number', $aInfo->affiliate_payment_bank_account_number, 'maxlength="64"'); ?></td>
		</tr>
		<?php
	}
	?>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
	<td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
	</tr>
	<tr>
	<td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	<tr>
	<td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_street_address', $aInfo->affiliate_street_address, 'maxlength="64"', true); ?></td>
	</tr>
	<?php
	if (ACCOUNT_SUBURB == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_SUBURB; ?></td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_suburb', $aInfo->affiliate_suburb, 'maxlength="64"', false); ?></td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td class="main"><?php echo ENTRY_CITY; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_city', $aInfo->affiliate_city, 'maxlength="32"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_POST_CODE; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_postcode', $aInfo->affiliate_postcode, 'maxlength="8"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_COUNTRY; ?></td>
	<td class="main"><?php echo tep_draw_pull_down_menu('affiliate_country_id', tep_get_countries(), $aInfo->affiliate_country_id, 'onChange="update_zone(this.form);"'); ?></td>
	</tr>
	<?php
	if (ACCOUNT_STATE == 'true') {
		?>
		<tr>
		<td class="main"><?php echo ENTRY_STATE; ?></td>
		<td class="main"><?php echo tep_draw_pull_down_menu('affiliate_zone_id', tep_prepare_country_zones_pull_down($aInfo->affiliate_country_id), $aInfo->affiliate_zone_id, 'onChange="resetStateText(this.form);"'); ?></td>
		</tr>
		<tr>
		<td class="main">&nbsp;</td>
		<td class="main"><?php echo tep_draw_input_field('affiliate_state', $aInfo->affiliate_state, 'maxlength="32" onChange="resetZoneSelected(this.form);"'); ?></td>
		</tr>
		<?php
	}
	?>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
	<td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
	</tr>
	<tr>
	<td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
	<tr>
	<td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_telephone', $aInfo->affiliate_telephone, 'maxlength="32"'); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_fax', $aInfo->affiliate_fax, 'maxlength="32"'); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_AFFILIATE_HOMEPAGE; ?></td>
	<td class="main"><?php echo tep_draw_input_field('affiliate_homepage', $aInfo->affiliate_homepage, 'maxlength="64"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo ENTRY_API_CATEGORIES_ID; ?></td>
	<td class="main"><?php echo tep_draw_input_field('api_categories_id', $aInfo->api_categories_id, 'maxlength="64"', true); ?></td>
	</tr>
	<tr>
	<td class="main"><?php echo 'Verified:'; ?></td>
	<td class="main"><?php echo tep_draw_input_field('verified', $aInfo->verified, 'maxlength="64" readonly="true"', true); ?></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
	<td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';?></td>
	</tr></form>
	<?php
} else {
	?>
	<tr>
	<td>
	<?php echo tep_draw_form('search', FILENAME_AFFILIATE, '', 'get'); ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	<td class="smallText" align="right">
	<?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?>
	按优惠码编号查找：<?php echo tep_draw_input_field('affiliate_coupon_code'); ?>
	是否已验证：<?php echo tep_draw_pull_down_menu('verified',array(array('id'=>'','text'=>'--不限--'), array('id'=>'1','text'=>'已验证'),array('id'=>'0', 'text'=>'未验证')));?>
	是否已转换：<?php echo tep_draw_pull_down_menu('changed',array(array('id'=>'','text'=>'--不限--'), array('id'=>'1','text'=>'已转换'),array('id'=>'0', 'text'=>'没转换')));?>
	<button type="submit"> 搜索 </button>
	<a href="<?= tep_href_link(FILENAME_AFFILIATE);?>">清除搜索选项</a>
	</td>

	</tr>
	</table>
	</form>
	</td>
	</tr>
	<tr>
	<td>
	<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('affiliate_affiliate');
$list = $listrs->showRemark();
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
	<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_ID . '</br><a href="' . $_SERVER['PHP_SELF'] . '?sort=affid&order=ascending"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sort=affid&order=decending">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; ?></td>
	<td class="dataTableHeadingContent">合作方式</td>
	<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME . '</br><a href="' . $_SERVER['PHP_SELF'] . '?sort=lastname&order=ascending"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sort=lastname&order=decending">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; ?></td>
	<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME . '</br><a href="' . $_SERVER['PHP_SELF'] . '?sort=firstname&order=ascending"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sort=firstname&order=decending">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; ?></td>
	<td class="dataTableHeadingContent" align="right">优惠码编号</td>
	<td class="dataTableHeadingContent" align="right">审核优惠码</td>
	<td class="dataTableHeadingContent" align="right">link推广佣金</td>
	<td class="dataTableHeadingContent" align="right">优惠码推广佣金</td>
	<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_COMMISSION; ?></td>
	<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PENDING_COMMISSION . '</br><a href="' . $_SERVER['PHP_SELF'] . '?sort=pendingpayment&order=ascending"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sort=pendingpayment&order=decending">&nbsp;<img src="images/arrow_down.gif" border="0"></a>'; ?></td>
	<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAID_COMMISSION . '</br><a href="' . $_SERVER['PHP_SELF'] . '?sort=paidpayment&order=ascending"><img src="images/arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?sort=paidpayment&order=decending">&nbsp;<img src="images/arrow_down.gif" border="0"></a>';  ?></td>
	<td class="dataTableHeadingContent" align="center"><?php echo '# of Referrals'; ?></td>
	<td class="dataTableHeadingContent" align="center"><?php echo 'Verified'; ?>&nbsp;</td>

	<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
	</tr>
	<?php
	$search_where = '';	//这个变量在这里是搜索的条件
	if ( ($_GET['search']) && (tep_not_null($_GET['search'])) ) {
		$keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
		$search_where = " and ( a.affiliate_id like '" . $keywords . "' or c.customers_firstname like '" . $keywords . "' or c.customers_lastname like '" . $keywords . "' or c.customers_email_address like '" . $keywords . "' ) ";
	}

	$search_coupon_code_where = '';
	if ( ($_GET['affiliate_coupon_code']) && (tep_not_null($_GET['affiliate_coupon_code'])) ){
		$affiliate_coupon_code = tep_db_input(tep_db_prepare_input($_GET['affiliate_coupon_code']));
		$search_coupon_code_where = " and ( a.affiliate_coupon_code like '" . $affiliate_coupon_code . "%' ) ";
	}

	$other_where = '';
	if($_GET['verified']=='1'){
		$other_where.= ' and a.verified=1 ';
	}elseif($_GET['verified']==='0'){
		$other_where.= ' and a.verified=0 ';
	}
	if($_GET['changed']=='1'){
		$other_where.= ' and a.changed=1 ';
	}elseif($_GET['changed']==='0'){
		$other_where.= ' and a.changed=0 and a.affiliate_coupon_code!="" ';
	}

	switch ($_GET["sort"]) {
		case 'lastname':
			if($_GET["order"]=='ascending') {
				$sortorder = ' order by c.customers_lastname  asc';
			} else {
				$sortorder = ' order by c.customers_lastname  desc';
			}
			break;
		case 'firstname':
			if($_GET["order"]=='ascending') {
				$sortorder = ' order by c.customers_firstname  asc';
			} else {
				$sortorder = ' order by c.customers_firstname  desc';
			}
			break;
		case 'affid':
			if($_GET["order"]=='ascending') {
				$sortorder = ' order by a.affiliate_id   asc';
			} else {
				$sortorder = ' order by a.affiliate_id  desc';
			}
			break;

		case 'pendingpayment':
			if($_GET["order"]=='ascending') {
				//$sortorder = ' order by panding_payment  asc';
				$sortorder = ' order by payment  asc';
				$extradd = "AND p.affiliate_payment_status = '0' ";
				$caseadd = " case p.affiliate_payment_status when 0 then sum(p.affiliate_payment) else 0 end as payment ";
			} else {
				$sortorder = ' order by payment  desc';
				$extradd = "AND p.affiliate_payment_status = '0' ";
				$caseadd = " case p.affiliate_payment_status when 0 then sum(p.affiliate_payment) else 0 end as payment ";
			}
			break;
		case 'paidpayment':
			if($_GET["order"]=='ascending') {
				$sortorder = ' order by payment asc';
				$extradd = "AND p.affiliate_payment_status = '1' ";
				$caseadd = " case p.affiliate_payment_status when 1 then sum(p.affiliate_payment) else 0 end as payment ";
			} else {
				$sortorder = ' order by payment desc';
				$extradd = "AND p.affiliate_payment_status = '1' ";
				$caseadd = " case p.affiliate_payment_status when 1 then sum(p.affiliate_payment) else 0 end as payment ";
			}
			break;

		default:
			if($_GET["order"]=='ascending') {
				$sortorder = ' order by a.affiliate_id  asc';
			} else {
				$sortorder = ' order by a.affiliate_id  desc';
			}
			break;
	}

	if($_GET["sort"] == "pendingpayment" || $_GET["sort"] == "paidpayment"){
		$affiliate_query_raw = "select a.*, c.customers_lastname, c.customers_firstname, ".$caseadd." from customers as c, affiliate_affiliate as a left join ( affiliate_payment as p ) on ( a.affiliate_id = p.affiliate_id  " .$extradd . " ) where c.customers_id = a.affiliate_id group by a.affiliate_id  ". $sortorder;

	}else{
		$affiliate_query_raw = "select a.*, c.customers_lastname,c.customers_firstname   from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id ". $search_where . $search_coupon_code_where . $other_where . " ".$sortorder;
	}


	$affiliate_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $affiliate_query_raw, $affiliate_query_numrows);


	$affiliate_query = tep_db_query($affiliate_query_raw);
	while ($affiliate = tep_db_fetch_array($affiliate_query)) {
		$info_query = tep_db_query("select affiliate_commission_percent, affiliate_date_account_created as date_account_created, affiliate_date_account_last_modified as date_account_last_modified, affiliate_date_of_last_logon as date_last_logon, affiliate_number_of_logons as number_of_logons from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate['affiliate_id'] . "'");
		$info = tep_db_fetch_array($info_query);


		//amit added to select country id
		//$affiliate['affiliate_country_id'] = $customerdetails['entry_country_id'];

		if (((!$_GET['acID']) || (@$_GET['acID'] == $affiliate['affiliate_id'])) && (!$aInfo)) {
			$country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $affiliate['affiliate_country_id'] . "'");
			$country = tep_db_fetch_array($country_query);

			$affiliate_info = array_merge((array)$country, (array)$info);

			$aInfo_array = array_merge((array)$affiliate, (array)$affiliate_info);
			$aInfo = new objectInfo($aInfo_array);
		}

		if ( (is_object($aInfo)) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) {
			echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '\'">' . "\n";
		} else {
			echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '\'">' . "\n";
		}
		if (substr($affiliate['affiliate_homepage'],0,7) != "http://") $affiliate['affiliate_homepage']="http://".$affiliate['affiliate_homepage'];
		?>
		<td class="dataTableContent"><?php echo $affiliate['affiliate_id']; ?></td>
		<td class="dataTableContent"><?php echo ($affiliate['affiliate_type']=='1' ? '链接+优惠码': '普通')?></td>
		<td class="dataTableContent"><?php echo tep_db_output($affiliate['customers_lastname']); ?></td>
		<td class="dataTableContent"><?php echo tep_db_output($affiliate['customers_firstname']); ?></td>
		<td class="dataTableContent" align="right">
		<?php
		echo $affiliate['affiliate_coupon_code'];
		?>
		</td>
		<td class="dataTableContent" align="right" title="<?php echo showAffiliatePaypalInfoToTitle($affiliate['affiliate_id'])?>">
		<?php if ($affiliate['coupon_code_verify'] == 1) {?><a href="<?php echo tep_href_link('affiliate_affiliates.php','aid=' . $affiliate['affiliate_id'] . '&action=verifyCouponCode&v=0')?>" title="点击通过审核">未审核</a><?php } else {?><a href="<?php echo tep_href_link('affiliate_affiliates.php','aid=' . $affiliate['affiliate_id'] . '&action=verifyCouponCode&v=1')?>" title="点击取消审核">已审核</a><?php }?></td>
		<td class="dataTableContent" align="right">
		<?php
		$affiliate_commission_percent = $affiliate['affiliate_commission_percent'] > AFFILIATE_PERCENT ? $affiliate['affiliate_commission_percent'] :  AFFILIATE_PERCENT;
		echo round($affiliate_commission_percent, 2);
		?> %
		</td>
		<td class="dataTableContent" align="right">
		<?php		
		echo round($affiliate['affiliate_commission_percent_coupons'], 2);
		?> %
		</td>
		<td class="dataTableContent" align="right"><?php echo $currencies->display_price(tep_get_affiliate_total_commission($affiliate['affiliate_id']),''); ?></td>
		<td class="dataTableContent" align="right"><?php echo $currencies->display_price(tep_get_affiliate_pending_commission($affiliate['affiliate_id']),''); ?></td>
		<td class="dataTableContent" align="right"><?php echo $currencies->display_price(tep_get_affiliate_paid_commission($affiliate['affiliate_id']),''); ?></td>
		<td class="dataTableContent" align="center">
		<?php
		//		echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate['affiliate_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>'; echo '<a href="' . $affiliate['affiliate_homepage'] . '" target="_blank">' . $affiliate['affiliate_homepage'] . '</a>';
		echo tep_get_count_affiliate_referrals($affiliate['affiliate_id']);
		?>
		</td>
		<td class="dataTableContent" align="center">
		<?php
		echo (int)$affiliate['verified'] ? '<b>已验证</b>': '没验证';
		?>
		</td>

		<td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . tep_image(DIR_WS_ICONS . 'statistics.gif', ICON_STATISTICS) . '</a>&nbsp;'; if ( (is_object($aInfo)) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td colspan="60"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
	<td class="smallText" valign="top"><?php echo $affiliate_split->display_count($affiliate_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_AFFILIATES); ?></td>
	<td class="smallText" align="right"><?php echo $affiliate_split->display_links($affiliate_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'acID'))); ?></td>
	</tr>
	<?php
	if (tep_not_null($_GET['search'])) {
		?>
		<tr>
		<td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
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
	switch ($_GET['action']) {
		case 'confirm':
			$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

			$contents = array('form' => tep_draw_form('affiliate', FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=deleteconfirm'));
			$contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');
			$contents[] = array('align' => 'center', 'text' => '<br>' . ($login_groups_id =='1' ? tep_image_submit('button_delete.gif', IMAGE_DELETE):'') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
			break;
		default:
			if (is_object($aInfo)) {
				$customerdetails = tep_get_customers_info($aInfo->affiliate_id);

				//amit added extra info from customer table start
				$aInfo->affiliate_firstname = $customerdetails['customers_firstname'];
				$aInfo->affiliate_lastname = $customerdetails['customers_lastname'];
				$aInfo->affiliate_email_address  = $customerdetails['customers_email_address'];
				$aInfo->date_account_created = $customerdetails['customers_info_date_account_created'];
				$aInfo->date_account_last_modified = $customerdetails['customers_info_date_account_last_modified'];
				$aInfo->date_last_logon = $customerdetails['customers_info_date_of_last_logon'];
				$aInfo->number_of_logons = $customerdetails['customers_info_number_of_logons'];

				$aInfo->countries_name = tep_get_country_name($customerdetails['entry_country_id']);
				//amit added extra info from customer table end


				$heading[] = array('text' => '<b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');

				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=confirm') . '">' . ( $login_groups_id =='1' ? tep_image_button('button_delete.gif', IMAGE_DELETE): '') . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, 'selected_box=affiliate&affiliate=' . $aInfo->affiliate_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
				//amit added code start
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, tep_get_all_get_params(array('acID','page', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . tep_image_button('button_affiliate_sales.gif','Sales Report' ) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('acID','page', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . tep_image_button('button_affiliate_payment.gif','Payment Report' ) . '</a>');
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, tep_get_all_get_params(array('acID','page', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . tep_image_button('button_affiliate_clickthroughs.gif','Clickthroughs Report') . '</a>');

				//amit added code end

				$affiliate_sales_raw = "select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) where o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " and  affiliate_id = '" . $aInfo->affiliate_id . "'";
				$affiliate_sales_values = tep_db_query($affiliate_sales_raw);
				$affiliate_sales = tep_db_fetch_array($affiliate_sales_values);

				$contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($aInfo->date_account_created));
				$contents[] = array('text' => '' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($aInfo->date_account_last_modified));
				$contents[] = array('text' => '' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($aInfo->date_last_logon));
				$contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $aInfo->number_of_logons);
				$contents[] = array('text' => '' . TEXT_INFO_COMMISSION . ' ' . $aInfo->affiliate_commission_percent . ' %');
				$contents[] = array('text' => '' . TEXT_INFO_COUNTRY . ' ' . $aInfo->countries_name);
				$contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_SALES . ' ' . $affiliate_sales['count'],'');
				$contents[] = array('text' => '' . TEXT_INFO_SALES_TOTAL . ' ' . $currencies->display_price($affiliate_sales['total'],''));
				$contents[] = array('text' => '' . TEXT_INFO_AFFILIATE_TOTAL . ' ' . $currencies->display_price($affiliate_sales['payment'],''));
				$contents[] = array('text' => nl2br(showAffiliatePaypalInfoToTitle($aInfo->affiliate_id)));
			}
			break;
	}

	if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
		echo '            <td width="25%" valign="top">' . "\n";

		$box = new box;
		echo $box->infoBox($heading, $contents);

		echo '            </td>' . "\n";
	}
	?>
	</tr>
	</table></td>
	</tr>
	<?php
}
?>
</table></td>
<!-- body_text_eof //-->
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
