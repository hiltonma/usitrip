<?php
/*
$Id: checkout_payment.php,v 1.1.1.3 2004/03/04 23:37:55 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License

Updated for: Authorize.net Consolidated v1.7 - CRE loaded6 v4 edition
Date: April 10, 2004
Added: Rewrote checkout_payment.php for CRE loaded6 v4
Author: Austin Renfroe (Austin519)
Email: Austin519@aol.com
*/

require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!tep_session_is_registered('shipping')) {
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
	if ($cart->cartID != $cartID) {
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
	}
}

// if we have been here before and are coming back get rid of the credit covers variable
if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');  //ICW ADDED FOR CREDIT CLASS SYSTEM

// Stock Check
if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
	$products = $cart->get_products();
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			break;
		}
	}
}

// if no billing destination address was selected, use the customers own address as default
if (!tep_session_is_registered('billto')) {
	tep_session_register('billto');
	$billto = $customer_default_address_id;
} else {
	// verify the selected billing address
	$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
	$check_address = tep_db_fetch_array($check_address_query);

	if ($check_address['total'] != '1') {
		$billto = $customer_default_address_id;
		if (tep_session_is_registered('payment')) tep_session_unregister('payment');
	}
}

require(DIR_FS_CLASSES . 'order.php');
$order = new order;
require(DIR_FS_CLASSES . 'order_total.php');//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules = new order_total;//ICW ADDED FOR CREDIT CLASS SYSTEM

//ajax 检查用户输入的优惠券编码是否有问题 start {
if($_POST['ajax'] == 'true' && $_POST['action'] == 'checkCouponCode'){
	if($_POST['gv_redeem_code']){
		$order_total_modules->collect_posts();
		echo 'true';
		exit;
	}
}
//ajax 检查用户输入的优惠券编码是否有问题 end }

if (!tep_session_is_registered('comments')) tep_session_register('comments');  

for ($i=0, $n=sizeof($order->products); $i<$n; $i++){
	if (!tep_session_is_registered('airline_name['.$i.']')) tep_session_register('airline_name['.$i.']');
	if (!tep_session_is_registered('flight_no['.$i.']')) tep_session_register('flight_no['.$i.']');
	if (!tep_session_is_registered('airline_name_departure['.$i.']')) tep_session_register('airline_name_departure['.$i.']');
	if (!tep_session_is_registered('flight_no_departure['.$i.']')) tep_session_register('flight_no_departure['.$i.']');
	if (!tep_session_is_registered('airport_name['.$i.']')) tep_session_register('airport_name['.$i.']');
	if (!tep_session_is_registered('airport_name_departure['.$i.']')) tep_session_register('airport_name_departure['.$i.']');
	if (!tep_session_is_registered('arrival_date['.$i.']')) tep_session_register('arrival_date['.$i.']');
	if (!tep_session_is_registered('arrival_time['.$i.']')) tep_session_register('arrival_time['.$i.']');
	if (!tep_session_is_registered('departure_date['.$i.']')) tep_session_register('departure_date['.$i.']');
	if (!tep_session_is_registered('departure_time['.$i.']')) tep_session_register('departure_time['.$i.']');
}
$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();
$total_count = $cart->count_contents_virtual(); //ICW ADDED FOR CREDIT CLASS SYSTEM

// load all enabled payment modules
require(DIR_FS_CLASSES . 'payment.php');
$payment_modules = new payment;

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

$validation_include_js = 'true';
$content = CONTENT_CHECKOUT_PAYMENT;
$javascript = $content . '.js.php';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);


require(DIR_FS_INCLUDES . 'application_bottom.php');
?>

