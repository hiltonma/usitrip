<?php
//echo $_SERVER['PHP_AUTH_USER'];
//echo $_SERVER['PHP_AUTH_PW'];
if(!defined('INCLUDE_AUTH_PASS') || INCLUDE_AUTH_PASS != true){ die('No Permissions'); }
$passed = false;
$error = false;
if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){
	$passed = true;
}elseif(isset($_SERVER['PHP_AUTH_USER']) || isset($_POST['PHP_AUTH_USER']) || isset($_GET['PHP_AUTH_USER'])){
	if($_POST['PHP_AUTH_USER']){
		$PHP_AUTH_USER = $_POST['PHP_AUTH_USER'];
		$PHP_AUTH_PW = $_POST['PHP_AUTH_PW'];
	}elseif(isset($_GET['PHP_AUTH_USER'])){
		$PHP_AUTH_USER = $_GET['PHP_AUTH_USER'];
		$PHP_AUTH_PW = $_GET['PHP_AUTH_PW'];
	}else{
		$PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
		$PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
	}
	
	// check customer
	$check_customer_query = tep_db_query("select customers_id, customers_password, customers_state from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($PHP_AUTH_USER) . "' ");
	if(!tep_db_num_rows($check_customer_query)){
		$error .= 'Not exists the user!';
	}else{
		$check_customer = tep_db_fetch_array($check_customer_query);
		if (!tep_validate_password($PHP_AUTH_PW, $check_customer['customers_password'])) {
			$error .= 'Password error!';
		} elseif ((int) $check_customer['customers_state'] < 1) {
			$error .= 'Stop use the user!';
		}else{
			$passed = true;
			if (SESSION_RECREATE == 'True') {
				tep_session_recreate();
			}
			$check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $check_customer['customers_id'] . "' and address_book_id = '" . (int) $check_customer['customers_default_address_id'] . "'");
			$check_country = tep_db_fetch_array($check_country_query);
	
			$customer_id = $check_customer['customers_id'];
			$customer_default_address_id = $check_customer['customers_default_address_id'];
			$customer_default_ship_address_id = $check_customer['customers_default_ship_address_id'];
			$customer_first_name = $check_customer['customers_firstname'];
			$customer_country_id = $check_country['entry_country_id'];
			$customer_zone_id = $check_country['entry_zone_id'];
			$customer_email_address = $check_customer['customers_email_address'];
			$customer_validation = $check_customer['customers_validation'];
			$affiliate_id = $check_customer['customers_id'];
			$customers_group=$check_customer['customers_group'];
			tep_session_register('customer_id');
			tep_session_register('customer_default_address_id');
			tep_session_register('customer_default_ship_address_id');
			tep_session_register('customer_first_name');
			tep_session_register('customer_country_id');
			tep_session_register('customer_zone_id');
			tep_session_register('customer_email_address');
			tep_session_register('customer_validation');
			tep_session_register('affiliate_id');
			tep_session_register('customers_group');
			
			//读取用户的最后一次登录时间
			$lastloginRow = tep_db_fetch_array(tep_db_query('SELECT customers_info_date_of_last_logon  FROM '.TABLE_CUSTOMERS_INFO." WHERE  customers_info_id = ".(int)$customer_id));
			$customer_lastlogin	= trim($lastloginRow['customers_info_date_of_last_logon']); 
			tep_session_register('customer_lastlogin');
		}
	}
	
}

if($passed == true){
	//取得合作伙伴的产品ID号
	if(!tep_session_is_registered('api_categories_id') || !(int)$api_categories_id){
		$aff_id = (int)$affiliate_id ? $affiliate_id : $customer_id;
		$aff_row = tep_db_fetch_array(tep_db_query('SELECT api_categories_id FROM `affiliate_affiliate` WHERE affiliate_id="'.$aff_id.'" '));
		$api_categories_id = $aff_row['api_categories_id'];
		tep_session_register('api_categories_id');
	}
}
if($passed == false){
	header("WWW-Authenticate: Basic realm=\"Login API\"");
	header("HTTP/1.0 401 Unauthorized");
	echo "Unauthorized ".mt_rand(1000, 9999);
	exit;
}
?>