<?php
/*
$Id: checkout_confirmation.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
说明：
$_POST['action_page_name']是从checkout_info.php提交过来的变量，如果出错则需要返回到该页面
*/

require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
	if ($cart->cartID != $cartID) {
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
	}
}

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!tep_session_is_registered('shipping')) {
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
}

if (!tep_session_is_registered('payment')) tep_session_register('payment');
if (isset($_POST['payment'])) $payment = $_POST['payment'];

if (!tep_session_is_registered('comments')) tep_session_register('comments');
if (tep_not_null($_POST['comments'])) {
	$comments = tep_db_prepare_input($_POST['comments']);
}

/* billing info update - start */
if(isset($_POST['billto'])){
	$process = true;

	$firstname = $customer_first_name;
	$lastname = $customer_last_name;
	if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
	if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
	$street_address = tep_db_prepare_input($_POST['street_address']); //.' , '.tep_db_prepare_input($_POST['street_address_line2'])
	if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
	$postcode = tep_db_prepare_input($_POST['postcode']);
	$city = tep_db_prepare_input($_POST['city']);
	$country = tep_db_prepare_input($_POST['country']);
	$telephone = tep_db_prepare_input($_POST['telephone_cc']).'-'.tep_db_prepare_input($_POST['telephone']);
	$fax = tep_db_prepare_input($_POST['fax_cc']).'-'.tep_db_prepare_input($_POST['fax']);

	$mobilephone = tep_db_prepare_input($_POST['mobile_phone_cc']).'-'.tep_db_prepare_input($_POST['mobile_phone']);

	if (ACCOUNT_STATE == 'true') {
		/*if (isset($_POST['zone_id'])) {
		$zone_id = tep_db_prepare_input($_POST['zone_id']);
		} else {
		$zone_id = false;
		}*/
		$state = tep_db_prepare_input($_POST['state']);
	}
	/*if (ACCOUNT_STATE == 'true') {
	$zone_id = 0;
	$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
	$check = tep_db_fetch_array($check_query);
	$entry_state_has_zones = ($check['total'] > 0);
	if ($entry_state_has_zones == true) {
	//State abbreviation bug fix applied. 10/1/2004
	//Now accepts abbreviation - no complaints about capitalization
	$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' OR zone_code = '" . tep_db_input($state) . "')");
	if (tep_db_num_rows($zone_query) == 1) {
	$zone = tep_db_fetch_array($zone_query);
	$zone_id = $zone['zone_id'];
	} else {
	$error = true;

	$messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT);
	}
	}
	}*/
	if ($error == false) {
		$sql_data_array = array('customers_id' => $customer_id,
		'entry_firstname' => $firstname,
		'entry_lastname' => $lastname,
		'entry_street_address' => html_to_db($street_address),
		'entry_postcode' => $postcode,
		'entry_city' => html_to_db($city),
		'entry_country_id' => $country);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		if (ACCOUNT_STATE == 'true') {
			/*howard added*/
			$get_zone_sql = tep_db_query('SELECT zone_id FROM `zones` WHERE zone_name="'.html_to_db($state).'" limit 1');
			$get_row = tep_db_fetch_array($get_zone_sql);
			if((int)$get_row['zone_id']){
				$sql_data_array['entry_state'] = '';
				$sql_data_array['entry_zone_id'] = (int)$get_row['zone_id'];
			}else{
				$sql_data_array['entry_state'] = html_to_db($state);
				$sql_data_array['entry_zone_id'] = '0';
			}
			/*howard added end*/
		}

		tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_POST['billto'] . "'");

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

	//记录联系电话信息
	$sql_data_array_customers = array();
	if(strlen($telephone)>8){
		$sql_data_array_customers['customers_telephone'] = $telephone;
	}
	if(strlen($fax)>8){
		$sql_data_array_customers['customers_fax'] = $fax;
	}
	if(strlen($mobilephone)>8){	//移动电话
		$sql_data_array_customers['customers_mobile_phone'] = $mobilephone;
	}
	if((int)count($sql_data_array_customers)){
		tep_db_perform(TABLE_CUSTOMERS, $sql_data_array_customers, 'update', "customers_id = '" . (int)$customer_id . "'");
	}
}
/*add or update ship address - end*/

//amit added for emergancy contect number start
if($_POST['customers_cellphone'] != '') {
	$customers_cellphone=tep_db_prepare_input($_POST['customers_cellphone']);
	tep_db_query("update  ".TABLE_CUSTOMERS." set customers_cellphone='$customers_cellphone' where customers_id = ".$customer_id."  ");
}

//amit added for emergancy contect number end

foreach($_POST as $key=>$val)
{
	//echo "<br>$key=>$val";
	if(strstr($key,'arrival_date'))
	{
		if (!tep_session_is_registered($key)) tep_session_register($key);
		if (tep_not_null($val)) {
			$_SESSION[$key] = tep_db_prepare_input($val);
		}
	}

	if(strstr($key,'departure_date'))
	{
		if (!tep_session_is_registered($key)) tep_session_register($key);
		if (tep_not_null($val)) {
			$_SESSION[$key] = tep_db_prepare_input($val);
		}
	}

}


if (!tep_session_is_registered('airline_name')) tep_session_register('airline_name');
if (tep_not_null($_POST['airline_name'])) {
	$airline_name = tep_db_prepare_input($_POST['airline_name']);
}
if (!tep_session_is_registered('flight_no')) tep_session_register('flight_no');
if (tep_not_null($_POST['flight_no'])) {
	$flight_no = tep_db_prepare_input($_POST['flight_no']);
}
if (!tep_session_is_registered('airline_name_departure')) tep_session_register('airline_name_departure');
if (tep_not_null($_POST['airline_name_departure'])) {
	$airline_name_departure = tep_db_prepare_input($_POST['airline_name_departure']);
}
if (!tep_session_is_registered('flight_no_departure')) tep_session_register('flight_no_departure');
if (tep_not_null($_POST['flight_no_departure'])) {
	$flight_no_departure = tep_db_prepare_input($_POST['flight_no_departure']);
}

if (!tep_session_is_registered('airport_name')) tep_session_register('airport_name');
if (tep_not_null($_POST['airport_name'])) {
	$airport_name = tep_db_prepare_input($_POST['airport_name']);
}
if (!tep_session_is_registered('airport_name_departure')) tep_session_register('airport_name_departure');
if (tep_not_null($_POST['airport_name_departure'])) {
	$airport_name_departure = tep_db_prepare_input($_POST['airport_name_departure']);
}

if (!tep_session_is_registered('arrival_time')) tep_session_register('arrival_time');
if (tep_not_null($_POST['arrival_time'])) {
	$arrival_time = tep_db_prepare_input($_POST['arrival_time']);
}

if (!tep_session_is_registered('departure_time')) tep_session_register('departure_time');
if (tep_not_null($_POST['departure_time'])) {
	$departure_time = tep_db_prepare_input($_POST['departure_time']);
}

foreach($_POST as $key=>$val)
{
	//echo "<br>$key=>$val";
	if(strstr($key,'GuestEmail') || strstr($key,'PayerMe'))
	{
		$_SESSION[$key] = $val;

	}

	if(strstr($key,'guestname'))
	{
		$_SESSION[$key] = $val;

	}

	if(strstr($key,'GuestEngXing'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'GuestEngXing') && strlen($val)>0)
	{
		$_SESSION[$key] = $val;

	}

	if(strstr($key,'GuestEngName'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'GuestEngName') && strlen($val)>0)
	{
		$_SESSION[$key] = $val;

	}


	if(strstr($key,'guestbodyweight'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'guestweighttype'))
	{
		$_SESSION[$key] = $val;
	}

	if(strstr($key,'guestchildage'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'SingleName'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'SingleGender'))
	{
		$_SESSION[$key] = $val;
	}

	if(strstr($key,'guestgender'))
	{
		$_SESSION[$key] = $val;
	}

	if(strstr($key,'guestdob'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'guestbodyheight'))
	{
		$_SESSION[$key] = $val;
	}
	if(strstr($key,'radio_is_hotel_pickup_info_'))
	{
		if($val == 1){
			$_SESSION[str_replace('radio_','',$key)] = $_POST[str_replace('radio_','',$key)];
		}else{
			$_SESSION[str_replace('radio_','',$key)] = '';
		}
	}
}


// load the selected payment module
require(DIR_FS_CLASSES . 'payment.php');
if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
$payment_modules = new payment($payment);
//ICW ADDED FOR CREDIT CLASS SYSTEM
require(DIR_FS_CLASSES . 'order_total.php');

require(DIR_FS_CLASSES . 'order.php');
$order = new order;

$payment_modules->update_status();
//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules = new order_total;
//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules->collect_posts();
//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules->pre_confirmation_check();



function checkmiltrytime($checkstring){
	$validationcheck = true;
	if(preg_match('/^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/', $checkstring, $matchArray)) {
		$hour = $matchArray[1];
		$minute = $matchArray[2];
		if ($hour < 0  || $hour > 23) {
			$validationcheck = false;
		}
		if ($minute < 0 || $minute > 59) {
			$validationcheck = false;
		}

	}else{
		$validationcheck = false;
	}
	return $validationcheck;
}

//check for guest name code by bhavik
foreach($_POST as $key=>$val)
{
	if(strstr($key,'GuestEngXing') || strstr($key,'GuestEngName'))
	{
		foreach($val as $key1=>$val1)
		{
			if(strlen(trim($val1))<1 || $val == ''){
				$messageStack->add_session('global', db_to_html("顾客护照英文名有误：请按要求输入英文姓名！"), 'error');

				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}
		}
	}

	if(strstr($key,'guestbodyweight'))
	{
		foreach($val as $key1=>$val1)
		{
			if(strlen(trim($val1))<1 || $val == ''){
				$messageStack->add_session('global', db_to_html("请输入顾客体重！"), 'error');
				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}
		}
	}

	if(strstr($key,'guestchildage')){
		foreach($val as $key1=>$val1)
		{
			if(strlen(trim($val1))<1 || $val == ''){
				$messageStack->add_session('global', db_to_html("请输入小孩的出生日期，格式如12/26/2008！"), 'error');
				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}

			if(!tep_checkdate($val1, 'mm/dd/yyyy', $child_age_date_available_array)) {
				$messageStack->add_session('global', db_to_html("出生日期格式有误，正确的格式是mm/dd/yyyy，如：12/26/2008！"), 'error');
				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}else{
				$date_array = explode('/',$val1);
				$yyymmdd_date = $date_array[2].$date_array[0].$date_array[1];
				if($yyymmdd_date>date("Ymd")){
					$messageStack->add_session('global', db_to_html("出生日期不能在当前日期以后！"), 'error');
					if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
						tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
					}
					tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
				}
			}
		}
	}

	if(strstr($key,'arrival_time'))
	{
		foreach($val as $key1=>$val1)
		{
			if(strlen(trim($val1))>1 && checkmiltrytime($val1) == false ){
				$messageStack->add_session('global', db_to_html("请输入有效的航班抵达时间！"), 'error');
				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}
		}
	}

	if(strstr($key,'departure_time'))
	{
		foreach($val as $key1=>$val1)
		{
			if(strlen(trim($val1))>1 && checkmiltrytime($val1) == false ){
				$messageStack->add_session('global', db_to_html("请输入有效的航班出发时间！"), 'error');
				if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

			}
		}
	}

}

//Howard added to check SingleName and SingleGender Start
//如果客户选择了单人部分配房选项，必须输入配房者姓名及性别，同时配房者必须是客人中的其中一位
for($i=0, $n=sizeof($order->products); $i<$n; $i++){
	//if($order->products[$i]['roomattributes'][6]=="1" && preg_match('/###1!!0/',$order->products[$i]['roomattributes'][3])){
	if($_POST['is_Single_AU'][$i]=='1'){
		//check
		if(!tep_not_null($_SESSION['SingleName'][$i])){
			$error = true;
			$messageStack->add_session('global', db_to_html("配房者姓名不可为空！"), 'error');
		}else{
			$tmp_confirm = false;
			if($_POST['action_page_name']=='checkout_info'){
				foreach($_POST as $key=>$val){
					if(strstr($key,'guestname')){
						if(str_replace(' ','',$_POST[$key][$i]) == str_replace(' ','',$_SESSION['SingleName'][$i])){
							$tmp_confirm = true;
							break;
						}
					}
				}
			}else{
				foreach($_SESSION as $key=>$val){
					if(strstr($key,'guestname')){
						if(str_replace(' ','',$_SESSION[$key][$i]) == str_replace(' ','',$_SESSION['SingleName'][$i])){
							$tmp_confirm = true;
							break;
						}
					}
				}
			}

			if($tmp_confirm == false){
				$error = true;
				$messageStack->add_session('global', db_to_html("配房者姓名必须是参加该团的顾客成员之一！"), 'error');
			}
		}
		if(!tep_not_null($_SESSION['SingleGender'][$i])){
			$error = true;
			$messageStack->add_session('global', db_to_html("配房者姓性别不可为空！"), 'error');
		}

		if($error == true){
			tep_redirect(tep_href_link('checkout_info.php','', 'SSL'));
		}
	}
}
//Howard added to check SingleName and SingleGender End

//amit added to check validation for child age max allow
for ($i=0, $n=sizeof($order->products); $i<$n; $i++)
{
	$hh=0;
	foreach($_POST as $key=>$val) {
		if(strstr($key,'guestname'))
		{
			if($_POST['guestchildage'.$hh][$i] != ''){
				$age_diff_at_time_travel = (int)((strtotime(tep_get_date_disp(substr($order->products[$i]['dateattributes'][0],0,10))) - strtotime(trim($_POST['guestchildage'.$hh][$i])))/(86400*365)) ;
				if($age_diff_at_time_travel > $order->products[$i]['max_allow_child_age'] && $order->products[$i]['max_allow_child_age'] != ''){
					$messageStack->add_session('global', $_POST['guestchildage'.$hh][$i].":".sprintf(TEXT_CHILD_BIRTH_ERROR_MSG,$_POST['guestchildage'.$hh][$i],$order->products[$i]['max_allow_child_age']), 'error');
					if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
						tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
					}
					tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
				}else{
					//检查小孩年龄，如果其年龄小于min_watch_age规定的值就提示错误信息
					$age_sql = tep_db_query('SELECT min_watch_age FROM `products_show` WHERE products_id="'.tep_get_prid($order->products[$i]['id']).'" Limit 1');
					$age_row = tep_db_fetch_array($age_sql);
					if($age_diff_at_time_travel < $age_row['min_watch_age']){
						$messageStack->add_session('global', $_POST['guestchildage'.$hh][$i].":".sprintf(TEXT_CHILD_BIRTH_ERROR_MIN_MSG,$_POST['guestchildage'.$hh][$i],$age_row['min_watch_age']), 'error');
						if($_POST['action_page_name']=='checkout_info'){	//返回checkout_info页
							tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
						}
						tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
					}
				}
			}
			$hh++;
		}
	}
}
//amit added to check validation for child age max allow
// ICW CREDIT CLASS Amended Line
//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {


if($authorizenet_failed_cntr>=3 && $_POST['payment'] == 'authorizenet'){
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'authorize_max_attempt_over_error=true' .$error_back_page_get_parameters, 'SSL', true, false));
}

// this is added for credit issue -START
if(isset($_POST['customer_apply_credit_bal']) && $_POST['customer_apply_credit_bal'] == 1){
	$customer_apply_credit_bal = $_POST['customer_apply_credit_bal'];
	if (!tep_session_is_registered('customer_apply_credit_bal')) tep_session_register('customer_apply_credit_bal');

	$customer_apply_credit_amt = $_POST['customer_apply_credit_amt'];
	if (!tep_session_is_registered('customer_apply_credit_amt')) tep_session_register('customer_apply_credit_amt');

	if ($order->info['total'] - $_POST['customer_apply_credit_amt'] <= 0 ) {
		if(!tep_session_is_registered('credit_applied_covers')) tep_session_register('credit_applied_covers');
		$credit_applied_covers = true;
	}else{
		$credit_applied_covers = false;
	}
}else{
	$customer_apply_credit_bal = 0;
	//$customer_apply_credit_amt = 0;
	tep_session_unregister('customer_apply_credit_amt');
	$credit_applied_covers = false;
}
// this is added for credit issue -END

##### Points/Rewards Module V2.1rc2a check for error BOF #######
if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
	//$_POST['customer_shopping_points_spending']是表单传过来的兑换积分值，小心检查此值
	$customer_shopping_points = tep_get_shopping_points($_SESSION['customer_id']);
	if($_POST['customer_shopping_points_spending'] > $customer_shopping_points){
		$_POST['customer_shopping_points_spending'] = $customer_shopping_points;
	}

	//第一次购买能兑换的积分检查
	$_POST['customer_shopping_points_spending'] = tep_first_booking_min_points_check($_POST['customer_shopping_points_spending'], $total_pur_suc_nos_of_cnt, $customer_shopping_points);

	if (isset($_POST['customer_shopping_points_spending']) && is_numeric($_POST['customer_shopping_points_spending']) && ($_POST['customer_shopping_points_spending'] > 0)) {
		$customer_shopping_points_spending = false;
		if (tep_calc_shopping_pvalue($_POST['customer_shopping_points_spending']) < $order->info['total'] && !is_object($$payment) && $credit_applied_covers != true) {
			$customer_shopping_points_spending = false;
			$messageStack->add_session('global', REDEEM_SYSTEM_ERROR_POINTS_NOT, 'error');
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
		} else {
			$customer_shopping_points_spending = $_POST['customer_shopping_points_spending'];
			if (!tep_session_is_registered('customer_shopping_points_spending')) tep_session_register('customer_shopping_points_spending');
		}
	}

	/*if (tep_not_null(USE_REFERRAL_SYSTEM)) {
	if (isset($_POST['customer_referred']) && tep_not_null($_POST['customer_referred'])) {
	$customer_referral = false;
	$check_mail = trim($_POST['customer_referred']);
	if (tep_validate_email($check_mail) == false) {
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_VALID), 'SSL'));
	} else {
	$valid_referral_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $check_mail . "' limit 1");
	$valid_referral = tep_db_fetch_array($valid_referral_query);
	if (!tep_db_num_rows($valid_referral_query)) {
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
	}

	if ($check_mail == $order->customer['email_address']) {
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_SELF), 'SSL'));
	} else {
	$customer_referral = $valid_referral['customers_id'];
	if (!tep_session_is_registered('customer_referral')) tep_session_register('customer_referral');
	}
	}
	}
	}*/
}
//if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) && (!$credit_applied_covers) && (!$customer_shopping_points_spending) ) {
	if(!isset($_POST['action_page_name']) || $_POST['action_page_name']!='checkout_info'){	//如果不是从checkout_ifo过来的页面则把没有选择支付方式的错误返回到checkout_payment页面
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
	}
}
########  Points/Rewards Module V2.1rc2a EOF #################*/

/*if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) ) {
tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
}*/

if (is_array($payment_modules->modules)) {
	$payment_modules->pre_confirmation_check();
}

// load the selected shipping module
require(DIR_FS_CLASSES . 'shipping.php');
$shipping_modules = new shipping($shipping);
//ICW Credit class amendment Lines below repositioned
//  require(DIR_FS_CLASSES . 'order_total.php');
//  $order_total_modules = new order_total;

// Stock Check
$any_out_of_stock = false;
if (STOCK_CHECK == 'true') {
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
			$any_out_of_stock = true;
		}
	}
	// Out of Stock
	if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	}
}


if(isset($_POST['action_page_name']) && $_POST['action_page_name']=='checkout_info'){//如果checkout_info的信息都正确则到支付页面FILENAME_CHECKOUT_PAYMENT
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2);
$validation_include_js = 'true';
$content = CONTENT_CHECKOUT_CONFIRMATION;

$javascript = 'checkout_confirmation.js.php';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
