<?php
/*
$Id: account_history_info.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
/*
 * 检查是否有权限读取邀请函
 */
function checkHaveInvitation($opid){
	$email=tep_get_customers_email($_SESSION['customer_id']);
	$str_sql='select orders_product_eticket_guest_id from orders_product_eticket_guest where guest_email="'.$email.'" and orders_products_id='.$opid;
	$sql_query=tep_db_query($str_sql);
	return tep_db_num_rows($sql_query);
}
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (!(int)$_GET['order_id']) {
	tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
}

//支付成功给客人发通知邮件
if(tep_not_null($_GET['need_send_payment_success_email']) && tep_not_null($_GET['success_payment'])){
	tep_send_payment_success_email((int)$_GET['order_id'], (string)$_GET['success_payment']);
}

$customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$_GET['order_id'] . "'");
$customer_info = tep_db_fetch_array($customer_info_query);
if ($customer_info['customers_id'] != $customer_id) {
	tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
}

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);
require(DIR_FS_LANGUAGES . $language . '/' . 'checkout_payment.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
$breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$_GET['order_id'], 'SSL'));

require_once(DIR_FS_CLASSES . 'order.php');
$order_id = (int)$_POST['order_id'] ? $_POST['order_id'] : $_GET['order_id'];
$order = new order($_GET['order_id']);

//ajax 提交 start {
if($_POST['ajax'] == 'true'){
	require_once(DIR_FS_LANGUAGES . $language . '/' . 'modules/order_total/ot_coupon.php');
	require_once(DIR_FS_INCLUDES . 'modules/order_total/ot_coupon.php');
	$redeem_code = new ot_coupon;
	$error = false;
	//检查能否再优惠
	if($_POST['action']=='submitCouponCode' || $_POST['action']=='submitPoint'){
		if((int)$order->info['orders_paid']){
			$error = true;
			$error_msn = '已付款(包括部分付款)的订单不可再使用优惠券或积分抵扣！';
		}else{
			for($i=0, $n=sizeof($order->totals); $i<$n; $i++){
				if(in_array($order->totals[$i]['class'], array('ot_coupon', 'ot_redemptions'))){
					$error = true;
					$error_msn = '本订单已经是优惠后的订单了，所以不可再使用优惠券或积分打折！';
				}
				if($order->totals[$i]['class']=='ot_total'){
					$ot_total = $order->totals[$i]['value'];
				}
			}
		}
	}
	
	if($error == false){
		switch ($_POST['action']){
			case 'submitCouponCode':	//提交用户的优惠码
				if($_POST['acc_gv_redeem_code']){
					$cc_id = tep_db_get_field_value('coupon_id','coupons','coupon_code="'.tep_db_input(tep_db_prepare_input($_POST['acc_gv_redeem_code'])).'"');
					$amount = 0;
					//检查优惠券 start{
					$redeem_code->collect_posts();					
					//检查优惠券 end}
					if($error == false){
						$amount = $redeem_code->calculate_credit($ot_total);
						if($amount > 0){
							$ot_total = $ot_total - $amount;
							tep_db_query('UPDATE `orders_total`  set `text`="'.'<b>' . $currencies->format($ot_total) . '</b>'.'" ,`value`="'.$ot_total.'"  WHERE `orders_id`="'.$order_id.'" AND `class`="ot_total" ');
							tep_db_query('INSERT INTO `orders_total`  set `orders_id`="'.$order_id.'" ,`title`="'.html_to_db($redeem_code->title). ':' . $redeem_code->coupon_code .':'.'" ,`text`="'.'<b>-' . $currencies->format($amount) . '</b>'.'" ,`value`="'.$amount.'" ,`class`="'.$redeem_code->code.'" ,`sort_order`="'.$redeem_code->sort_order.'" ');
							//给销售联盟推荐人添加记录
							$_tmp_affiliate_id = tep_db_get_field_value('affiliate_id','`coupons`','coupon_code="'.strip_tags($_POST['acc_gv_redeem_code']).'" ');
							if((int)$_tmp_affiliate_id){
								unset($_SESSION['affiliate_ref']);
								$affiliate_ref = $_tmp_affiliate_id;
								$insert_id = $order_id;
								require(DIR_FS_INCLUDES . 'affiliate_checkout_process.php');
								//根据sofia要求，生成一次单后清除销售联盟变量
								servers_sales_track::clear_ref_info();
							}
						}
						//更新价格
						echo 'true';
					}
				}
				unset($_SESSION['acc_gv_redeem_code']);	//预防用户下新单时漏洞
				tep_session_unregister('acc_gv_redeem_code');
			break;

			case 'submitPoint':	//提交积分兑换
				require_once(DIR_FS_LANGUAGES . $language . '/' . 'modules/order_total/ot_redemptions.php');
				require_once(DIR_FS_INCLUDES . 'modules/order_total/ot_redemptions.php');
				$redemptions = new ot_redemptions;
				$_customer_shopping_points_spending = $_POST['_customer_shopping_points_spending'];
				//一定要检查该用户能用多少积分：取本订单最高可用积分、用户本人最高可用积分、用户输入的兑换积分 的最小值。
				$customer_shopping_points = tep_get_shopping_points($_SESSION['customer_id']);
				$max_points_string = calculate_max_points($customer_shopping_points);
				$max_points1 = explode("-#-",$max_points_string);
				$order_max_points = (int)$max_points1[0];	//最大允许的积分
				//$total_allowable_discount = $max_points1[1];	//最大允许的被抵扣价格
	
				$_customer_shopping_points_spending = min($order_max_points, $_customer_shopping_points_spending);
				if ($_customer_shopping_points_spending > 0) {	//兑换积分
					tep_redeemed_points($customer_id, $order_id, $_customer_shopping_points_spending);
					$amount = tep_calc_shopping_pvalue($_customer_shopping_points_spending);
					$ot_total = $ot_total - $amount;
					tep_db_query('UPDATE `orders_total`  set `text`="'.'<b>' . $currencies->format($ot_total) . '</b>'.'" ,`value`="'.$ot_total.'"  WHERE `orders_id`="'.$order_id.'" AND `class`="ot_total" ');
					tep_db_query('INSERT INTO `orders_total`  set `orders_id`="'.$order_id.'" ,`title`="'.html_to_db($redemptions->title). ':'.'" ,`text`="'.'<span style=\"color:#FF0000\">-' . $currencies->format($amount) . '</span>'.'" ,`value`="'.$amount.'" ,`class`="'.$redemptions->code.'" ,`sort_order`="'.$redemptions->sort_order.'" ');
	
					echo 'true';
				}else{
					$error = true;
					$error_msn = "积分兑换失败！请检查以下原因：\n（1）您的积分够不够？\n（2）此订单产品是否允许用积分？";
				}
				unset($_SESSION['_customer_shopping_points_spending']);	//预防用户下新单时漏洞
				tep_session_unregister('_customer_shopping_points_spending');
			break;
		}
	}

	if($error == true){
		echo db_to_html($error_msn);
	}
	exit;
}
//ajax 提交 end }

//{
//优惠券等模块，在结伴同游状态下不适用
//在有团购行程的状态也不可用
//在有特价行程也不可用
//有禁止优惠的产品也不可用
//在商务中心员工身份帮客人下单时也不可用
//Howard added by 2012-11-12
$enable_coupon_points = true;	//默认开放优惠码和积分使用功能
$without_products_ids = explode(',', DISABLED_COUPON_PRODUCTS_IDS);
if((int)$Admin->login_id){
	$enable_coupon_points = false;
}else{
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++){
		if($order->products[$i]['roomattributes'][5]=='1'){
			$enable_coupon_points = false;
		}
		if((int)is_group_buy_product((int)$order->products[$i]['id'])){
			$enable_coupon_points = false;
		}
		if(check_is_specials((int)$order->products[$i]['id'])){
			$enable_coupon_points = false;
		}
		if(in_array((int)$order->products[$i]['id'], $without_products_ids)){
			$enable_coupon_points = false;
		}
	}
}
//}

//添加被销售破坏了的订单广告跟踪ID和联盟id
if(!(int)$Admin->login_id){
	tep_update_orders_ad_click_id($order_id, $customers_ad_click_id);
	tep_update_orders_affiliate_info($order_id, $_COOKIE['affiliate_ref']);
	tep_update_orders_from($order_id);
}

$content = CONTENT_ACCOUNT_HISTORY_INFO;
$javascript = 'popup_window.js';

$is_my_account = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
