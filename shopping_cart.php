<?php
//currency=CNY
/*
  $Id: shopping_cart.php,v 1.1.1.1 2004/03/04 23:38:03 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/

  require("includes/application_top.php");

  //ajax提交的购物车box操作{
  if($_GET['ajax']=="true" && tep_not_null($_GET['shopping_cart_action'])){
  	switch($_GET['shopping_cart_action']){
		case "remove":
			$cart->remove($_GET['products_id']);
			$cart_sum = count($cart->contents);
			$js_str = 'jQuery("#CarSumTop").html('.($cart_sum).');';	//购物车总数
			$js_str .= 'jQuery("#CarSumTop1").html('.($cart_sum).');';	//购物车总数
			$js_str .= 'jQuery("#cartBoxTotal").html("'.$currencies->format($cart->show_total()).'");';	//总价
			$js_str .= 'jQuery("#cartBoxList'.tep_get_prid($_GET['products_id']).'").remove();';	//删除列表
			echo '[JS]'.$js_str.'[/JS]';
		break;
	}
	exit;
  }
  //}

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
// BOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller
// Validate Cart for checkout
  $valid_to_checkout= true;
  $cart->get_products(true);
  if (!$valid_to_checkout) {
//    $messageStack->add_session('header', 'Please update your order ...', 'error');
//    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
// EOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller

if(!tep_session_is_registered('HTTP_REFERER_TWO')){
	$HTTP_REFERER_TWO = $_SERVER['HTTP_REFERER'];
	tep_session_register('HTTP_REFERER_TWO');
}
	/*
	if ($cart->count_contents() < 1) {
		tep_session_unregister('HTTP_REFERER_TWO');
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
		die();
	}*/
	//清除夏威夷自助游的临时记录
	if(isset($_SESSION['hawaii_self'])){
		$_SESSION['hawaii_self'] = array();
	}

  $content = CONTENT_SHOPPING_CART;
  
   $javascript_external = $content . '.js';
   function getOldOrders(){
   		if(!isset($_SESSION['customer_id']))
   			return false;
   		$data=array();
   		$str_sql='select o.orders_id from orders o where o.customers_id='.$_SESSION['customer_id'].' and o.orders_status in(100154,1,100143,100144,100145,100094,100054,100060,100092,100119,100120,100123,100085,100086,100087,100088,100020,100136,100140)';
   		$sql_query=tep_db_query($str_sql);
   		while($row=tep_db_fetch_array($sql_query)){
   			$data[]=$row;
   		}
   		return $data;
   }
//    print_r(getOldOrders());
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>