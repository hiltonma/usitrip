<?php
/*
 * $Id: specials.php,v 1.1.1.1 2004/03/04 23:40:51 ccwjr Exp $ osCommerce, Open
 * Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */

////
// Sets the status of a special product
function tep_set_specials_status($specials_id, $status) {
	return tep_db_query("update " . TABLE_SPECIALS . " set status = '" . (int) $status . "', date_status_change = now() where specials_id = '" . (int) $specials_id . "'");
}

/**
 * 自动激活特价产品
 */
function tep_activate_specials() {
	$sql = tep_db_query("select specials_id from " . TABLE_SPECIALS . " where status = '0' and start_date<=now() and (expires_date>=now() || expires_date IS NULL ); ");
	while ($rows = tep_db_fetch_array($sql)) {
		if ($rows['specials_id']) {
			
			tep_set_specials_status($rows['specials_id'], '1');
		}
	}
}

/**
 * 自动关闭过期的特价产品
 */
function tep_expire_specials() {
	$specials_query = tep_db_query("select specials_id,products_id from " . TABLE_SPECIALS . " where status = '1' and expires_date<=now(); ");
	if (tep_db_num_rows($specials_query)) {
		while ($specials = tep_db_fetch_array($specials_query)) {
			$data[] = $specials['products_id'];
			tep_set_specials_status($specials['specials_id'], '0');
			del_customers_basket_for_products(specials_id_to_products_id($specials['specials_id']));
		}
	}
	if ($order_arr = need_stop_orders($data)) {
		foreach ((array)$order_arr as $value) {
			change_order_status($value, 6);
			add_change_into_history($value,6);
			send_order_email($value);
		}
	}
}

/**
 * 修改订单状态
 * @param int $order_id 订单ID
 * @param int $status_id 状态标号
 * @return number
 */
function change_order_status($order_id, $status_id) {
	$sql_data_array = array (
			'orders_status' => $status_id, 
			'last_modified' => date('Y-m-d H:i:s') 
	);
	tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int) $order_id . "'");
	return 0;
}
/**
 * 查找需要停掉的订单号
 * @param array $products_array 产品ID数组
 * @return array
 */
function need_stop_orders($products_array) {
	$data = array();
	foreach ((array)$products_array as $value) {
		$str_sql='select op.orders_id,opt.orders_value from orders_products op LEFT JOIN orders_payment_history opt ON op.orders_id=opt.orders_id,orders o where o.orders_id=op.orders_id and o.orders_status not in(6, 100006)  and (opt.orders_value IS NULL) and op.products_id='.$value;
		$sql = tep_db_query($str_sql);
		while ($rows = tep_db_fetch_array($sql)) {
			if ($rows['orders_id']) {
				$data[] = $rows['orders_id'];
			}
		}
	}
	//过滤重复值
	$data = array_unique((array)$data);
	return $data;
}
/**
 * 添加改变状态到历史
 * @param int $orders_id 订单ID
 */
function add_change_into_history($orders_id,$status) {
	$sql_date_array = array (
			'orders_id' => (int) $orders_id,
			'orders_status_id' => $status,
			'date_added' => date('Y-m-d H:i:s'),
			'customer_notified' => '1',
			'comments' => '因未在团购或特价有效时间内完成付款，订单' . $orders_id . '已自动取消，如有什么疑问请随时联系我们！',
			'updated_by' => '96' 
	);
	tep_db_perform('orders_status_history', $sql_date_array);
}

/**
 * 发送session邮件
 */
function send_order_email($orders_id) {
	$str_sql = 'select * from orders where orders_id=' . (int) $orders_id;
	$sql = tep_db_query($str_sql);
	$rows = tep_db_fetch_array($sql);
	$mail_to = $rows['customers_email_address'];
	//$mail_to='158761028@qq.com';
	$to_name = $rows['customers_name'];
	$mail_subject = db_to_html('走四方订单取消提醒，订单号：'.$orders_id.' ');
	$email_text = '因未在团购或特价有效时间内完成付款，订单' . $orders_id . '已自动取消，如有什么疑问请随时联系我们！';
	$email_text.= '<br><br>';
	$email_text.= nl2br(CONFORMATION_EMAIL_FOOTER);
	$var_num = (int) count($_SESSION['need_send_email']);
	$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
	$_SESSION['need_send_email'][$var_num]['to_email_address'] = $mail_to;
	$_SESSION['need_send_email'][$var_num]['email_subject'] = $mail_subject;
	$_SESSION['need_send_email'][$var_num]['email_text'] = db_to_html($email_text); // can is html or text
	$_SESSION['need_send_email'][$var_num]['from_email_name'] = db_to_html(STORE_OWNER);
	//$_SESSION['need_send_email'][0]['from_email_address'] = STORE_OWNER_EMAIL_ADDRESS;
	$_SESSION['need_send_email'][$var_num]['from_email_address'] = 'automail@usitrip.com';
	$_SESSION['need_send_email'][$var_num]['action_type'] = EMAIL_USE_HTML;
	$send_auto_notify_email_prodr = true;
}

/**
 * 删除购物车数据库内某个产品，只要在管理后台修改了产品信息（特别是价格信息）都要将购物车中的相关产品清除
 * @param unknown_type $product_id
 * @author Howard
 */
function del_customers_basket_for_products($product_id) {
	if ((int) $product_id) {
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where CONCAT(products_id,'{') Like '" . (int) $product_id . "{%'");
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where CONCAT(products_id,'{') Like '" . (int) $product_id . "{%'");
	}
}

/**
 * 根据特价表的特价ID取得产品的ID号
 * @param $specials_id
 * @author Howard
 */
function specials_id_to_products_id($specials_id) {
	$sql = tep_db_query('SELECT products_id FROM `specials` WHERE specials_id="' . (int) $specials_id . '" Limit 1');
	$row = tep_db_fetch_array($sql);
	return (int) $row['products_id'];
}

/**
 * 检测产品是否是特价或者团购
 * @param int $products_id 产品ID
 */
function special_detect($products_id) {
	if ((int) $products_id) {
		$sql = "select specials_id from specials where products_id=" . (int) $products_id . " and status=1 limit 1";
		$result = tep_db_query($sql);
		$temp = tep_db_fetch_array($result);
		return (int) $temp['specials_id'];
	}
	return false;
}
?>
