<?php
/*
$Id: checkout_shipping.php,v 1.1.1.1 2004/03/04 23:37:57 ccwjr Exp $
$Id: checkout_shipping.php,v 1.1.1.1 2004/03/04 23:37:57 ccwjr Exp $
$Id: checkout_shipping.php,v 1.1.1.1 2004/03/04 23:37:57 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
Linda McGrath - osCommerce@WebMakers.com
*/
require('includes/application_top.php');
require('includes/classes/http_client.php');

// BOF: WebMakers.com Added: Downloads Controller - Free Shipping
// Reset $shipping if free shipping is on and weight is not 0
if (tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') and $cart->show_weight()!=0) {
	tep_session_unregister('shipping');
}
// EOF: WebMakers.com Added: Downloads Controller - Free Shipping
// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
	$messageStack->add_session('login', NEXT_NEED_SIGN);
	//$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

$cart->restore_contents();
$products_shp_check = $cart->get_products();

//amit added to check valid departure date start
for ($i=0, $n=sizeof($products_shp_check); $i<$n; $i++) {

	if($products_shp_check[$i]['is_transfer'] != '1'){
		$display_depa_dt_error = '';
		//amit added to check validation start
		$tmp_dp_date = $products_shp_check[$i]['dateattributes'][0];
		$currect_sys_date = date('Y-m-d');
		if(preg_match("/,".$products_shp_check[$i]['agency_id'].",/i", ",".TXT_PROVIDERS_DTE_BTL_IDS.",")) {
			$currect_sys_date = GetWorkingDays(date('Y-m-d'),4);
			$currect_sys_date = date("Y-m-d", strtotime($currect_sys_date) + (24*60*60) );
		}
		//$currect_sys_date = date('Y-m-d', strtotime('+2 days'));
		/*if(tep_check_product_is_hotel((int)$products_shp_check[$i]['id'])==1){
		$currect_sys_date = date('Y-m-d');
		}else{
		$currect_sys_date = date('Y-m-d', strtotime('+2 days'));
		}*/
		if(@tep_get_compareDates($tmp_dp_date,$currect_sys_date) == "invalid" ){
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_shp_check[$i]['id'].'&error_departure_date=true'));
			exit();
		}
	}else{
		$msg = tep_transfer_validate(intval($products_shp_check[$i]['id']),tep_transfer_decode_info($products_shp_check[$i]['transfer_info']));
		if($msg!=''){
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'products_id=' . $products_shp_check[$i]['id'].'&error_transfer_info='.$msg));
		}
	}


	//amit added to check needed update price
	if (tep_session_is_registered('customer_id') && $_SESSION['updated_prod_id_once'.(int)$products_shp_check[$i]['id']] != 'updated'){
		$get_added_date_cust_basket_sql = "select customers_basket_date_added from ".TABLE_CUSTOMERS_BASKET." where customers_id ='".$customer_id."' and products_id='".$products_shp_check[$i]['id']."'";
		$get_added_date_cust_basket_query = tep_db_query($get_added_date_cust_basket_sql);

		if(tep_db_num_rows($get_added_date_cust_basket_query) > 0){
			$get_added_date_cust_basket_row = tep_db_fetch_array($get_added_date_cust_basket_query);
			$final_cart_comapredate = $get_added_date_cust_basket_row['customers_basket_date_added'];
			//$final_cart_comapredate = substr($cust_bkt_date_added, 0, 4).'-'.substr($cust_bkt_date_added, 4, 2).'-'.substr($cust_bkt_date_added, 6, 2);

			//get product last update date start
			$get_products_last_modified_sql = "select products_last_modified from ".TABLE_PRODUCTS." where  products_id='".(int)$products_shp_check[$i]['id']."'";
			$get_products_last_modified_query = tep_db_query($get_products_last_modified_sql);
			$get_products_last_modified_row = tep_db_fetch_array($get_products_last_modified_query);
			$final_product_modify_comapredate = str_replace('-','',substr($get_products_last_modified_row['products_last_modified'],0,10));

			if($final_cart_comapredate <= $final_product_modify_comapredate){
				tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
				exit();
			}
		}
	}
	//amit added to check needed update price
	if($products_shp_check[$i]['is_transfer'] != '1'){
		//howard added check minnumber 最少订团人数检查
		$total_no_guest_tour = $products_shp_check[$i]['roomattributes'][2];
		$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.(int)$products_shp_check[$i]['id'].'" limit 1');
		$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
		if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			exit();
		}
	}
	//howard added check minnumber end

	//剩余座位检查，超过剩余座位数返回购物车 tom added
	$check_sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.(int)$products_shp_check[$i]['id'].'" AND departure_date="'.$products_shp_check[$i]['dateattributes'][0].'"');
	if((int)tep_db_num_rows($check_sql)>'0'){
		$sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.(int)$products_shp_check[$i]['id'].'" AND departure_date="'.$products_shp_check[$i]['dateattributes'][0].'"');
		$row = tep_db_fetch_array($sql);
		if($row['remaining_seats_num']<$products_shp_check[$i]['roomattributes'][2]){
			$messageStack->add_session('header', db_to_html('你所下的订单人数超过该团剩余座位数，请重新下单！'), 'error');
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			exit();
		}
	}
	//剩余座位检查，超过剩余座位数返回购物车 end

	//howard added check departurelocation 接送地址检查
	$display_departure_region_combo = "";
	$query_region = "select * from products_departure where departure_region<>'' and products_id = ".(int)$products_shp_check[$i]['id']." group by departure_region";
	$row_region = tep_db_query($query_region);
	$totlaregioncount = tep_db_num_rows($row_region);

	$product_info_query = tep_db_query("select agency_id, display_pickup_hotels from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_shp_check[$i]['id'] . "' limit 1");
	$product_info = tep_db_fetch_array($product_info_query);

	if((int)$totlaregioncount > 1 || ($product_info['agency_id'] == 12 && $product_info['display_pickup_hotels'] == '1')  ){

		$display_departure_region_combo = "true";

	}else if((int)$totlaregioncount == 1){
		$display_departure_region_combo = "true";
	}

	if(!tep_not_null($products_shp_check[$i]['dateattributes'][2]) && $display_departure_region_combo=='true'){
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
		exit();
	}
	//howard added check departurelocation end
	//print_r($products_shp_check);

}



//amit added to check valid departure date end

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
// BOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller
// Validate Cart for checkout
$valid_to_checkout= true;
$cart->get_products(true);
if (!$valid_to_checkout) {
	$messageStack->add_session('header', 'Please update your order ...', 'error');
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
// EOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller

// if no shipping destination address was selected, use the customers own address as default
if (!tep_session_is_registered('sendto')) {
	tep_session_register('sendto');
	$sendto = $customer_default_address_id;
} else {
	// verify the selected shipping address
	$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
	$check_address = tep_db_fetch_array($check_address_query);

	if ($check_address['total'] != '1') {
		$sendto = $customer_default_address_id;
		if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
	}
}

require(DIR_FS_CLASSES . 'order.php');
$order = new order;

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
$cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
// ICW CREDIT CLASS GV AMENDE LINE BELOW
//  if ($order->content_type == 'virtual') {
if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
	if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
	//$shipping = false;
	//$sendto = false;
	//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
	//tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
}

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();

// load all enabled shipping modules
require(DIR_FS_CLASSES . 'shipping.php');
$shipping_modules = new shipping;

if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
	$pass = false;

	switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
		case 'national':
			if ($order->delivery['country_id'] == STORE_COUNTRY) {
				$pass = true;
			}
			break;
		case 'international':
			if ($order->delivery['country_id'] != STORE_COUNTRY) {
				$pass = true;
			}
			break;
		case 'both':
			$pass = true;
			break;
	}

	$free_shipping = false;
	if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
		$free_shipping = true;

		include(DIR_FS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
	}
} else {
	$free_shipping = false;
}

// process the selected shipping method
//amit added to by pass shipping module start
$HTTP_POST_VARS['action'] = 'process';
//amit added to by pass shipping module end
if ( isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') ) {
	if (!tep_session_is_registered('comments')) tep_session_register('comments');
	if (tep_not_null($HTTP_POST_VARS['comments'])) {
		$comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
	}

	if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

	if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
		if ( (isset($HTTP_POST_VARS['shipping'])) && (strpos($HTTP_POST_VARS['shipping'], '_')) ) {
			$shipping = $HTTP_POST_VARS['shipping'];

			list($module, $method) = explode('_', $shipping);
			if ( is_object($$module) || ($shipping == 'free_free') ) {
				if ($shipping == 'free_free') {
					$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
					$quote[0]['methods'][0]['cost'] = '0';
				} else {
					$quote = $shipping_modules->quote($method, $module);
				}
				if (isset($quote['error'])) {
					tep_session_unregister('shipping');
				} else {
					if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
						$shipping = array('id' => $shipping,
						'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
						'cost' => $quote[0]['methods'][0]['cost']);


						tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
						//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
					}
				}
			} else {
				tep_session_unregister('shipping');
			}
		}
	} else {
		$shipping = false;

		tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
		//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
	}
}

// get all available shipping quotes
$quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

$content = CONTENT_CHECKOUT_SHIPPING;
$javascript = $content . '.js';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
