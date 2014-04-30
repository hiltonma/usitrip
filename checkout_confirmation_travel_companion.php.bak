<?php
/*
$Id: checkout_confirmation.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot(array('mode' => 'SSL', 'page' => 'orders_travel_companion.php'));
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

//清除session订单信息
$order = array();

//付款方式
$payment = $_POST['payment'];

//存储付款总额
$i_need_pay = $_POST['payables_total'];
//added to apply 2% for specific payment method
if($payment =='transfer'  || $payment =='moneyorder' || $payment =='cashdeposit' ){
	$discount_per_set_for_pay_method = 0;//用线下支付方式优惠百分多少
	$i_need_pay = $i_need_pay - (($i_need_pay * $discount_per_set_for_pay_method) / 100);
}
//added to apply 2% for specific payment method
$orders_id = (int)$_POST['order_id'];

$txn_signature = $_POST['orders_travel_companion_ids'];

//写session信息用于结帐结果页面处理
$travel_companion_ids = $_POST['orders_travel_companion_ids'];
$pay_order_id = $orders_id;
tep_session_register('travel_companion_ids');
tep_session_register('pay_order_id');

if (!tep_session_is_registered('payment')) tep_session_register('payment');
if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];
// load the selected payment module
require(DIR_FS_CLASSES . 'payment.php');
$payment_modules = new payment($payment);

$selection = $payment_modules->selection();
$payment_name = strip_tags($selection[0]['module']);
$payment_name = str_replace('&nbsp;','',$payment_name);

// $payment_modules->update_status();

//if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) && (!$customer_shopping_points_spending) ) {
	tep_redirect(tep_href_link('orders_travel_companion.php', 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
}
########  Points/Rewards Module V2.1rc2a EOF #################*/

/*if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) ) {
tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
}*/

//设置错误返回页面
$error_back_page = 'travel_companion_pay.php';

/* billing info update - start */
if(isset($HTTP_POST_VARS['billto'])){

	$firstname = $customer_first_name;
	$lastname = $customer_last_name;
	$street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']); //.' , '.tep_db_prepare_input($HTTP_POST_VARS['street_address_line2']);
	if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
	$postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
	$city = tep_db_prepare_input($HTTP_POST_VARS['city']);
	$country = tep_db_prepare_input($HTTP_POST_VARS['country']);
	//$telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['telephone']);
	//$fax = tep_db_prepare_input($HTTP_POST_VARS['fax_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['fax']);
	if($country<1){
		$error = true;
		$messageStack->add_session('travel_companion', ENTRY_COUNTRY.ENTRY_COUNTRY_ERROR);
	}

	if (ACCOUNT_STATE == 'true') {
		if(tep_not_null($HTTP_POST_VARS['state']) && $HTTP_POST_VARS['state']!="s"){
			$state = tep_db_prepare_input($HTTP_POST_VARS['state']);
		}else if(tep_not_null($HTTP_POST_VARS['ship_state']) && $HTTP_POST_VARS['ship_state']!="s"){
			$state = tep_db_prepare_input($HTTP_POST_VARS['ship_state']);
			$country = tep_db_prepare_input($HTTP_POST_VARS['ship_country']);
		}
		if(strlen($state)<ENTRY_STATE_MIN_LENGTH){
			$error = true;
			$messageStack->add_session('travel_companion', ENTRY_STATE_ERROR);
		}
	}

	if(strlen($city)<ENTRY_CITY_MIN_LENGTH){
		if(strlen($_POST['ship_city']) >= ENTRY_CITY_MIN_LENGTH){
			$city = tep_db_prepare_input($HTTP_POST_VARS['ship_city']);
		}else{
			$error = true;
			$messageStack->add_session('travel_companion', ENTRY_CITY_ERROR);
		}
	}
	if(strlen($street_address)<ENTRY_STREET_ADDRESS_MIN_LENGTH){
		if(strlen($_POST['ship_street_address']) >= ENTRY_STREET_ADDRESS_MIN_LENGTH){
			$street_address = tep_db_prepare_input($HTTP_POST_VARS['ship_street_address']);
		}else{
			$error = true;
			$messageStack->add_session('travel_companion', ENTRY_STREET_ADDRESS_ERROR);
		}
	}
	if(strlen($postcode)<ENTRY_POSTCODE_MIN_LENGTH){
		if(strlen($_POST['ship_postcode'])>=ENTRY_POSTCODE_MIN_LENGTH){
			$postcode = tep_db_prepare_input($HTTP_POST_VARS['ship_postcode']);
		}else{
			$error = true;
			$messageStack->add_session('travel_companion', ENTRY_POST_CODE_ERROR);
		}
	}

	if($error == true){
		$tmp_array = explode(',',$HTTP_POST_VARS['orders_travel_companion_ids']);
		$error_back_page_get_parameters = '';
		for($i=0; $i<count($tmp_array); $i++){
			if((int)$tmp_array[$i]){
				$error_back_page_get_parameters .= '&orders_travel_companion_ids%5B%5D='.$tmp_array[$i];
			}
		}
		$error_back_page_get_parameters .= '&authorizenet_cc_owner='.$_POST['authorizenet_cc_owner'];
		$error_back_page_get_parameters .= '&authorizenet_cc_number='.$_POST['authorizenet_cc_number'];
		$error_back_page_get_parameters .= '&cvv='.$_POST['cvv'];
		$error_back_page_get_parameters .= '&authorizenet_cc_expires_year='.$_POST['authorizenet_cc_expires_year'];
		$error_back_page_get_parameters .= '&authorizenet_cc_expires_month='.$_POST['authorizenet_cc_expires_month'];
		$error_back_page_get_parameters .= '&credit_card_type='.$_POST['credit_card_type'];

		$error_back_page_get_parameters .= '&order_id='.(int)$HTTP_POST_VARS['order_id'];

		tep_redirect(tep_href_link($error_back_page, $error_back_page_get_parameters, 'SSL'));

		tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
	}

	if ($error == false) {
		$sql_data_array = array('customers_id' => $customer_id,
		//'entry_firstname' => $firstname,
		//'entry_lastname' => $lastname,
		'entry_street_address' => ($street_address),
		'entry_postcode' => ($postcode),
		'entry_city' => ($city),
		'entry_country_id' => ($country));

		//if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = ($company);
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = ($suburb);
		if (ACCOUNT_STATE == 'true') {
			/*howard added*/
			$get_zone_sql = tep_db_query('SELECT zone_id FROM `zones` WHERE zone_name="'.html_to_db(($state)).'" limit 1');
			$get_row = tep_db_fetch_array($get_zone_sql);
			if((int)$get_row['zone_id']){
				$sql_data_array['entry_state'] = '';
				$sql_data_array['entry_zone_id'] = (int)$get_row['zone_id'];
			}else{
				$sql_data_array['entry_state'] = ($state);
				$sql_data_array['entry_zone_id'] = '0';
			}
			/*howard added end*/
		}

		$sql_data_array = html_to_db($sql_data_array);

		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$HTTP_POST_VARS['billto'] . "'");

	}
}
/* billing info update - end */

/*add or update ship address - start*/
if(isset($_POST['shipto']) && $error == false){
	$ship_street_address = tep_db_prepare_input($_POST['ship_street_address']);
	$ship_postcode = tep_db_prepare_input($_POST['ship_postcode']);
	$ship_city = tep_db_prepare_input($_POST['ship_city']);
	$ship_country = tep_db_prepare_input($_POST['ship_country']);
	$ship_state = tep_db_prepare_input($_POST['ship_state']);

	$sql_data_array = array('customers_id' => $customer_id,
	'entry_firstname' => $firstname,
	'entry_lastname' => $lastname,
	'entry_street_address' => html_to_db($ship_street_address),
	'entry_postcode' => $ship_postcode,
	'entry_city' => html_to_db($ship_city),
	'entry_country_id' => $ship_country);
	if (ACCOUNT_STATE == 'true') {
		$get_zone_sql = tep_db_query('SELECT zone_id FROM `zones` WHERE zone_name="'.html_to_db($ship_state).'" limit 1');
		$get_row = tep_db_fetch_array($get_zone_sql);
		if((int)$get_row['zone_id']){
			$sql_data_array['entry_state'] = '';
			$sql_data_array['entry_zone_id'] = (int)$get_row['zone_id'];
		}else{
			$sql_data_array['entry_state'] = html_to_db($ship_state);
			$sql_data_array['entry_zone_id'] = '0';
		}
	}

	if((int)$_POST['shipto']){

		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_POST['shipto'] . "'");
	}else{

		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
		$customer_default_ship_address_id = tep_db_insert_id();
		$shipto = $customer_default_ship_address_id;
		if(!tep_session_is_registered('customer_default_ship_address_id')){
			tep_session_register('customer_default_ship_address_id');
		}

		tep_db_query('update '.TABLE_CUSTOMERS.' SET customers_default_ship_address_id ="'.(int)$customer_default_ship_address_id.'" WHERE customers_id="'.(int)$customer_id.'"');
	}

	// howard fixed
	$customers_telephone = '';
	$customers_fax = '';
	$customers_mobile_phone = '';
	if(strlen($HTTP_POST_VARS['telephone'])>3){
		$customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['telephone']);
	}
	if(strlen($HTTP_POST_VARS['fax'])>3){
		$customers_fax = tep_db_prepare_input($HTTP_POST_VARS['fax_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['fax']);
	}
	if(strlen($HTTP_POST_VARS['mobile_phone'])>3){
		$customers_mobile_phone = tep_db_prepare_input($HTTP_POST_VARS['mobile_phone_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['mobile_phone']);
	}

	$sql_data_array_customers = array('customers_telephone' => $customers_telephone,
	'customers_fax' => $customers_fax,
	'customers_mobile_phone' => $customers_mobile_phone);

	tep_db_perform(TABLE_CUSTOMERS, $sql_data_array_customers, 'update', "customers_id = '" . (int)$customer_id . "'");

}
/*add or update ship address - end*/

if (is_array($payment_modules->modules)) {
	$payment_modules->pre_confirmation_check();
}

if (tep_session_is_registered('customer_id')) {
	$is_my_account = true;
	$breadcrumb->add(db_to_html('我的账号'), tep_href_link('account.php', '', 'SSL'));
}
$breadcrumb->add(db_to_html('结伴同游订单'), tep_href_link('orders_travel_companion.php', '', 'SSL'));
$breadcrumb->add(db_to_html('结伴同游付款'), tep_href_link('travel_companion_pay.php', '', 'SSL'));

$validation_include_js = 'true';

$is_my_account = true;
$content = 'checkout_confirmation_travel_companion';

$javascript = 'checkout_confirmation.js.php';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
