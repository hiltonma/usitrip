<?php
/*
积分相关的函数 (注意后台也调用了本文件的函数，在使用这些函数时注意它们在后台的兼容性)
$Id: redemptions.php, V2.1rc2a 2008/OCT/03 12:30:04 dsa_ Exp $
created by Ben Zukrel, Deep Silver Accessories
http://www.deep-silver.com

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2005 osCommerce

Released under the GNU General Public License
*/


/**
 * 统计某客户的总积分(有效积分)
 * 客户的总有效积分 = 已确认积分 - 已兑换积分
 */
function tep_get_customers_points_total($customers_id){
	if((int)POINTS_AUTO_EXPIRES){
		$start_date = date('Y-m-d 00:00:00', strtotime('- '. POINTS_AUTO_EXPIRES .' month'));
		$_ex_where .= ' AND date_added >= "'.$start_date.'" ';
		//在有效期内已用积分
		$has_use_sql = tep_db_query('SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id .'" '. $_ex_where.' AND points_status="4" ');
		$has_use = tep_db_fetch_array($has_use_sql);
		$has_points = abs($has_use['total']);
		//在有效期内得到的积分
		$get_sql = tep_db_query('SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id .'" '. $_ex_where.' AND points_status="2" ');
		$has_get = tep_db_fetch_array($get_sql);
		$get_points = $has_get['total'];
		//$sql_str = 'SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id . $_ex_where.'" AND ( points_status="2" || points_status="4") ';
		$row['total'] = $get_points - $has_points;
	}else{
		$sql_str = 'SELECT sum( points_pending ) AS total FROM `customers_points_pending` WHERE customer_id ="'.(int)$customers_id.'" AND ( points_status="2" || points_status="4") ';
		$sql = tep_db_query($sql_str);
		$row = tep_db_fetch_array($sql);
	}
	$row['total'] = max(0,$row['total']);
	return (int)$row['total'];
}

/**
 * 自动校正客户表的积分值
 *
 * @param unknown_type $customers_id
 */
function tep_auto_fix_customers_points($customers_id){
	$customers_shopping_points_total = tep_get_customers_points_total((int)$customers_id);
	tep_db_query("update " . TABLE_CUSTOMERS . " set customers_shopping_points = '". $customers_shopping_points_total ."' where customers_id = '". (int)$customers_id."'");
}
// shopping points the customer currently has
/**
 * 取得某客的当前总积分
 *
 * @param int $id 客户用户id
 * @param unknown_type $check_session 是否检查session，已经弃用
 * @return int|0
 */
function tep_get_shopping_points($id, $check_session = true) {
	if ((int)$id) {	
		return tep_get_customers_points_total((int)$id);	//这个可能会慢，但比下面的代码准确，在积分兑换无限制的情况下用这个比较合适
	}
	return 0;
	/*
	global $customer_id;

	if (is_numeric($id) == false) {
		if (tep_session_is_registered('customer_id')) {
			$id = $customer_id;
		} else {
			return 0;
		}
	}

	if ($check_session == true) {
		if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
			return 0;
		}
	}
	*/
	
	//$points_query = tep_db_query("select customers_shopping_points from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$id . "' limit 1");
	//$points = tep_db_fetch_array($points_query);
	//return $points['customers_shopping_points'];
}

// calculate the shopping points value for the customer
/**
 * 取得积分可换取的现金数额
 *
 * @param unknown_type $points
 * @return unknown
 */
function tep_calc_shopping_pvalue($points) {

	//return tep_round(((float)$points * (float)REDEEM_POINT_VALUE), POINTS_DECIMAL_PLACES);
	return tep_round(((float)$points * (float)REDEEM_POINT_VALUE) , 2);
}

// calculate the products shopping points tax value if any
/**
 * 根据价格信息取得要计积分的价格
 *
 * @param unknown_type $products_price
 * @param unknown_type $products_tax
 * @param unknown_type $quantity
 * @return unknown
 */
function tep_display_points($products_price, $products_tax, $quantity = 1) {

	if ((DISPLAY_PRICE_WITH_TAX == 'true') && (USE_POINTS_FOR_TAX == 'true')) {
		$products_price_points_query = tep_add_tax($products_price, $products_tax) * $quantity;
	} else {
		$products_price_points_query = $products_price * $quantity;
	}

	return $products_price_points_query;
}

// calculate the shopping points for any products price
/**
 * 根据消费金额取得赠多少积分的数额
 *
 * @param unknown_type $products_price_points_query
 * @return unknown
 */
function tep_calc_products_price_points($products_price_points_query) {

	$products_points_total = $products_price_points_query * POINTS_PER_AMOUNT_PURCHASE;

	return $products_points_total;
}

// calculate the shopping points value for any products price
function tep_calc_price_pvalue($products_points_total) {

	$products_points_value = tep_calc_shopping_pvalue($products_points_total);

	return($products_points_value);
}

// products restriction by model.
function get_redemption_rules($order) {

	if (tep_not_null(RESTRICTION_MODEL)||tep_not_null(RESTRICTION_PID)||tep_not_null(RESTRICTION_PATH)) {
		if (tep_not_null(RESTRICTION_MODEL))
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			if (!(substr($order->products[$i]['model'], 0, 10) == RESTRICTION_MODEL)) {
				return false;
			}
			return true;
		}

		if (tep_not_null(RESTRICTION_PID))
		for ($i=0; $i<sizeof($order->products); $i++) {
			$p_ids = split("[,]", RESTRICTION_PID);
			for ($ii = 0; $ii < count($p_ids); $ii++) {
				if ($order->products[$i]['id'] == $p_ids[$ii]) {
					return true;
				}
			}
		}

		if (tep_not_null(RESTRICTION_PATH))
		for ($i=0; $i<sizeof($order->products); $i++) {
			$cat_ids = split("[,]", RESTRICTION_PATH);
			$sub_cat_ids = split("[_]", tep_get_product_path($order->products[$i]['id']));
			for ($iii = 0; $iii < count($sub_cat_ids); $iii++) {
				for ($ii = 0; $ii < count($cat_ids); $ii++) {
					if ($sub_cat_ids[$iii] == $cat_ids[$ii]) {
						return true;
					}
				}
			}
		}
		return false;
	} else {
		return true;
	}
}

// check to see if to add pending points for specials.
function get_award_discounted($order) {

	if (USE_POINTS_FOR_SPECIALS == 'false') {
		for ($i=0; $i<sizeof($order->products); $i++) {
			if (tep_get_products_special_price($order->products[$i]['id']) >0) {
				return true;
			}
		}
		return false;
	} else {
		return true;
	}
}

// products pending points to add.
/**
 * 取得订单中要添加的积分
 *
 * @param unknown_type $order 订单信息
 * @return unknown
 */
function get_points_toadd($order) {
	//bug fixed
	if(!is_numeric($order->info['total'])){ $_order_info_total = $order->info['total_value']; }else { $_order_info_total = $order->info['total']; }
	if ($_order_info_total > 0) {
		$use_total_operation = true;
		$points_toadd = 0;

		//若有特殊活动积分需要每个产品分开计算无就按旧方式算。以下算法一般情况没有问题，但是遇到特价不给积分或有其它的优惠时结果就不太准确了。
		$the_day = date('Y-m-d');
		if(N_MULTIPLE_POINTS_SWITCH=='true' && date('Y-m-d',strtotime(N_MULTIPLE_POINTS_START_DATE))<=$the_day && date('Y-m-d',strtotime(N_MULTIPLE_POINTS_END_DATE))>=$the_day && sizeof($order->products)>1 ){
			for($i=0; $i<sizeof($order->products); $i++) {
				if(!in_array((int)$order->products[$i]['id'], array_trim(explode(',',NOT_GIFT_POINTS_PRODUCTS)))){	//排除不送积分的产品
					if (USE_POINTS_FOR_TAX == 'true') {
						$old_num = tep_add_tax($order->products[$i]['final_price'],$order->products[$i]['tax'])*$order->products[$i]['qty'];
						$new_num = get_n_multiple_points($old_num , tep_get_prid($order->products[$i]['id']));
						$points_toadd += $new_num;
						if($old_num!=$new_num){
							$use_total_operation = false;
						}
					} else {
						$old_num = $order->products[$i]['final_price']*$order->products[$i]['qty'];
						$new_num = get_n_multiple_points($old_num , tep_get_prid($order->products[$i]['id']));
						$points_toadd += $new_num;
						if($old_num!=$new_num){
							$use_total_operation = false;
						}
					}
				}
			}
		}
		////////
		if($use_total_operation==true){
			$order_integer_total = str_replace(',','',$_order_info_total);
			if((USE_POINTS_FOR_SHIPPING == 'false') && (USE_POINTS_FOR_TAX == 'false')){
				$points_toadd = $order_integer_total - $order->info['shipping_cost'] - $order->info['tax'];
			}else if ((USE_POINTS_FOR_SHIPPING == 'false') && (USE_POINTS_FOR_TAX == 'true')){
				$points_toadd = $order_integer_total - $order->info['shipping_cost'];
			}else if ((USE_POINTS_FOR_SHIPPING == 'true') && (USE_POINTS_FOR_TAX == 'false')){
				$points_toadd = $order_integer_total - $order->info['tax'];
			}else{
				$points_toadd = $order_integer_total;
			}
			//排除不送积分的产品，要把这部分的积分删除
			for($i=0; $i<sizeof($order->products); $i++) {
				if(in_array((int)$order->products[$i]['id'], array_trim(explode(',',NOT_GIFT_POINTS_PRODUCTS)))){
					$points_toadd = $points_toadd - $order->products[$i]['final_price'] * POINTS_PER_AMOUNT_PURCHASE;
				}
			}
			if(sizeof($order->products)==1){
				$points_toadd = get_n_multiple_points($points_toadd , tep_get_prid($order->products[0]['id']));
			}
		}

		if (USE_POINTS_FOR_SPECIALS == 'false') {
			for ($i=0; $i<sizeof($order->products); $i++) {
				if(!in_array((int)$order->products[$i]['id'], array_trim(explode(',',NOT_GIFT_POINTS_PRODUCTS)))){
					if (tep_get_products_special_price(tep_get_prid($order->products[$i]['id'])) >0) {
						if (USE_POINTS_FOR_TAX == 'true') {
							$points_toadd = $points_toadd - (tep_add_tax($order->products[$i]['final_price'],$order->products[$i]['tax'])*$order->products[$i]['qty']);
						} else {
							$points_toadd = $points_toadd - ($order->products[$i]['final_price']*$order->products[$i]['qty']);
						}
					}
				}
			}
		}


		return $points_toadd;
	} else {
		return false;
	}
}

/**
 * 设置结伴同游帖置顶扣除积分 返回真或假
 *
 * @param unknown_type $customer_id
 * @param unknown_type $set_top_day_num
 * @param unknown_type $t_companion_id
 * @return unknown
 */
function tep_sub_points_for_travel_companion_bbs_to_top($customer_id, $set_top_day_num, $t_companion_id){
	$can_use_point_num = tep_get_shopping_points($customer_id);
	$points_awarded = USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION * (int)$set_top_day_num;
	if($can_use_point_num < $points_awarded){
		return false;
	}

	$points_comment = 'USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT';
	$sql_data_array = array('customer_id' => (int)$customer_id,
	'points_pending' => - $points_awarded,
	'date_added' => 'now()',
	'points_comment' => $points_comment,
	'points_type' => 'TP',
	'points_status' => 4,
	't_companion_id'=> (int)$t_companion_id);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
	return true;

}
/**
 * 添加登录积分，用户登录一次添加50分
 */
function tep_add_pending_points_pointcard_login($customer_id){
	$addpoint = 50;
	$sql_data_array = array('customer_id' => (int)$customer_id,
	'points_pending' => $addpoint,
	'date_added' => 'now()',
	'points_comment' => 'TEXT_POINTCARD_LOGIN',
	'points_type' => 'PC',
	'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	$unique_id = tep_db_insert_id();

	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}
/**
 * 使用会员积分卡注册的用户增加注册积分 200 完善个人资料积分 800
 */
function tep_add_pending_points_pointcard_register($customer_id){
	$addpoint = 200;
	$sql_data_array = array('customer_id' => (int)$customer_id,
	'points_pending' => $addpoint,
	'date_added' => 'now()',
	'points_comment' => 'TEXT_POINTCARD_REGISTER',
	'points_type' => 'PC',
	'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	$unique_id = tep_db_insert_id();

	$addpoint = 800;
	$sql_data_array = array('customer_id' => (int)$customer_id,
	'points_pending' => $addpoint,
	'date_added' => 'now()',
	'points_comment' => 'TEXT_POINTCARD_PROFILE',
	'points_type' => 'PC',
	'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	$unique_id = tep_db_insert_id();

	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
	$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");
	return $unique_id;
}
// 老客户推荐朋友活动获得确认积分 2010年的新年活动
function tep_add_pending_points_old_customer_refriend($old_customer_id, $old_customer_add_points){
	$sql_data_array = array('customer_id' => (int)$old_customer_id,
	'points_pending' => $old_customer_add_points,
	'date_added' => 'now()',
	'points_comment' => 'TEXT_DEFAULT_REFERRAL',
	'points_type' => 'RF',
	'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	$unique_id = tep_db_insert_id();
	
	tep_auto_fix_customers_points((int)$old_customer_id);	//自动校正用户积分

	$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");
	return $unique_id;
}

/**
 * 添加待定积分
 *
 * @param unknown_type $customer_id 客户id
 * @param unknown_type $insert_id 订单id
 * @param unknown_type $points_toadd 积分数
 * @param unknown_type $points_comment 积分说明
 * @param unknown_type $points_type 积分类型
 * @param unknown_type $feedback_url 
 * @param unknown_type $products_id 
 * @return unknown
 */
function tep_add_pending_points($customer_id, $insert_id, $points_toadd, $points_comment, $points_type, $feedback_url='', $products_id='') {

	$points_awarded = ($points_type != 'SP') ? $points_toadd : $points_toadd * POINTS_PER_AMOUNT_PURCHASE;

	//特殊时段发表评论或相片取得N倍积分
	if((($points_type=='RV' && N_MULTIPLE_POINTS_INCLUDE_REVIEW=='true') || ($points_type == 'PH' && N_MULTIPLE_POINTS_INCLUDE_PHOTO=='true')) && (int)$products_id){
		$points_awarded = get_n_multiple_points($points_awarded , (int)$products_id);
	}


	//if (POINTS_AUTO_ON == '0' || $points_comment=='TEXT_DEFAULT_REFERRAL') {	//推荐朋友自动获得确定的积分
	/*
	特别注意：数据表字段'orders_id'并不全是订单的orders_id，评论、照片等ID也是写在TABLE_CUSTOMERS_POINTS_PENDING的orders_id中，
	我们需要通过$points_type来判断在'orders_id'的值到底是什么字段的ID。
	*/
	if (POINTS_AUTO_ON == '0' && $points_comment!='TEXT_DEFAULT_REFERRAL') {	//推荐朋友获得待定的积分

		$sql_data_array = array('customer_id' => (int)$customer_id,
		'orders_id' => (int)$insert_id,
		'products_id' => (int)$products_id,
		'points_pending' => $points_awarded,
		'date_added' => 'now()',
		'points_comment' => $points_comment,
		'points_type' => $points_type,
		'points_status' => 2,
		'feedback_other_site_url' => $feedback_url);

		tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
		$unique_id = tep_db_insert_id();

		$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");

	} else {
		$sql_data_array = array('customer_id' => (int)$customer_id,
								'orders_id' => (int)$insert_id,
								'products_id' => (int)$products_id,
								'points_pending' => $points_awarded,
								'date_added' => 'now()',
								'points_comment' => $points_comment,
								'points_type' => $points_type,
								'points_status' => 1,
								'feedback_other_site_url' => $feedback_url);
		tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
		$unique_id = tep_db_insert_id();

		$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");
	}
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
	return $unique_id;	//返回被插入的unique_id
}

// balance customer points account & record the customers redeemed_points
/**
 * 兑换积分
 *
 * @param unknown_type $customer_id 客户ID
 * @param unknown_type $insert_id 订单ID号
 * @param unknown_type $customer_shopping_points_spending 积分
 */
function tep_redeemed_points($customer_id, $insert_id, $customer_shopping_points_spending) {
	$customer_shopping_points = tep_get_shopping_points($customer_id);

	if (($customer_shopping_points - $customer_shopping_points_spending) > 0) {
	} else {
		tep_db_query("update " . TABLE_CUSTOMERS . " set customers_points_expires = null where customers_id = '". (int)$customer_id ."' limit 1");
	}

	if (DISPLAY_POINTS_REDEEMED == 'true') {
		$sql_data_array = array('customer_id' => (int)$customer_id,
								'orders_id' => (int)$insert_id,
								'points_pending' => - $customer_shopping_points_spending,
								'date_added' => 'now()',
								'points_comment' => 'TEXT_DEFAULT_REDEEMED',
								'points_type' => 'SP',
								'points_status' => 4);
		tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);

		$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");
	}
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}

// sets the new signup customers welcome points
function tep_add_welcome_points($customer_id) {

	$welcome_points = NEW_SIGNUP_POINT_AMOUNT;

	$sql_data_array = array('customer_id' => (int)$customer_id,
							'points_pending' => $welcome_points,
							'date_added' => 'now()',
							'points_comment' => 'TEXT_WELCOME_POINTS_COMMENT',
							'points_type' => 'RG',
							'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}

// sets the customer email validation points
function tep_add_validation_points($customer_id){
	$points = VALIDATION_ACCOUNT_POINT_AMOUNT;
	$sql_data_array = array('customer_id' => (int)$customer_id,
							'points_pending' => $points,
							'date_added' => 'now()',
							'points_comment' => 'TEXT_VALIDATION_ACCOUNT_POINT_COMMENT',
							'points_type' => 'VA',
							'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}

// sets the Vote System customers points
function tep_add_vote_points($customer_id, $points, $v_s_id) {

	$points = (int)$points;
	if(!(int)$points){ return false; }

	$sql_data_array = array('customer_id' => (int)$customer_id,
							'v_s_id' => (int)$v_s_id,
							'points_pending' => $points,
							'date_added' => 'now()',
							'points_comment' => 'TEXT_VOTE_POINTS_COMMENT',
							'points_type' => 'VT',
							'points_status' => 2);

	tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
	tep_auto_fix_customers_points((int)$customer_id);	//自动校正用户积分
}



// get the last update value for any key
function tep_get_last_date($key) {

	$key_date_query = tep_db_query("select last_modified from " . TABLE_CONFIGURATION . " where configuration_key = '". $key ."' limit 1");
	$key_date = tep_db_fetch_array($key_date_query);

	//return tep_date_long($key_date['last_modified']);
	//$last_modified_date = date("l F jS, Y",strtotime($key_date['last_modified']));
	$last_modified_date = chardate($key_date['last_modified'], 'D');
	return $last_modified_date;
}

// products discounted restriction if enabled.
function get_points_rules_discounted($order) {

	if (REDEMPTION_DISCOUNTED == 'true') {
		for ($i=0; $i<sizeof($order->products); $i++) {
			if (tep_get_products_special_price($order->products[$i]['id']) >0) {
				return false;
			}
		}
		return true;
	} else {
		return true;
	}
}

function get_redemption_awards($customer_shopping_points_spending) {
	global $order;

	if (USE_POINTS_FOR_REDEEMED == 'false') {
		if (!$customer_shopping_points_spending) {
			return true;
		}
		return false;
	} else {
		return true;
	}
}

/**
 * 计算优惠的最大积分
 *
 * @param unknown_type $customer_shopping_points
 * @return unknown
 */
function calculate_max_points($customer_shopping_points) {
	global $currencies, $order;

	$total_success_purchased_cnt = max(1, intval($_SESSION['total_pur_suc_nos_of_cnt']));	//客人是第几次购买？
	//bug fixed
	if(!is_numeric($order->info['total'])){ $_order_info_total = $order->info['total_value']; }else{ $_order_info_total = $order->info['total']; }
	$max_points = $_order_info_total/REDEEM_POINT_VALUE;

	$total_discount_max_price = 0;
	if($total_success_purchased_cnt>0){
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			//$total_discount_max_price += $order->products[$i]['final_price']  * ((  $order->products[$i]['products_margin'] *   (POINTS_START_DISCOUNT_VALUE + (POINTS_INCREAMENT_ON_START_DISCOUNT_VALUE * ($total_success_purchased_cnt-1))   )  )/100);
			if(!in_array((int)$order->products[$i]['id'],array_trim(explode(',',NOT_USE_BOOKING_POINTS_PRODUCTS)))){	//先排除不能用积分兑换的产品
				if($order->products[$i]['products_margin']<=20 && $order->products[$i]['products_margin']>0){	//如果产品的利润为0或小于0则没有积分优惠，大于0小于20%就按以下优惠
					$discount_percentage = explode(',',POINTS_MAX_VALUE_0_20);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $order->products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
				else if($order->products[$i]['products_margin']>20 && $order->products[$i]['products_margin']<=30){
					$discount_percentage = explode(',',POINTS_MAX_VALUE_20_30);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $order->products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
				else if($order->products[$i]['products_margin']>30){
					$discount_percentage = explode(',',POINTS_MAX_VALUE_30_PLUS);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $order->products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
			}
		}
	}//end if
	//echo $total_discount_max_price;
	//$points_max_value1 = $_order_info_total*POINTS_MAX_VALUE/100;
	$one_dolor_points = 1/REDEEM_POINT_VALUE;
	$points_max_value = $total_discount_max_price*$one_dolor_points;
	$max_points = $max_points > $points_max_value ? $points_max_value : $max_points;
	$max_points = $customer_shopping_points > $max_points ? $max_points : $customer_shopping_points;
	if($total_success_purchased_cnt=='1'){	//第一次购买有200积分不能使用
		$max_points -= FIRST_BOOKING_MIN_POINTS_UP_CAN_USE;
		$max_points = max(0, $max_points);
		$total_discount_max_price -= (FIRST_BOOKING_MIN_POINTS_UP_CAN_USE * REDEEM_POINT_VALUE);
		$total_discount_max_price = max(0, $total_discount_max_price);		
	}
	return $max_points.'-#-'.$total_discount_max_price;
}

/**
 * 预算最大积分优惠用于购物车显示
 *
 * @param unknown_type $customer_shopping_points
 * @return unknown
 */
function calculate_max_points_shopping_display($customer_shopping_points) {
	global $currencies, $cart;

	$cart->restore_contents();
	$products = $cart->get_products();
	$sub_total = $cart->show_total();

	$total_success_purchased_cnt = max(1, intval($_SESSION['total_pur_suc_nos_of_cnt']));	//客人是第几次购买？

	$max_points = $sub_total/REDEEM_POINT_VALUE;

	$total_discount_max_price = 0;
	if($total_success_purchased_cnt>0){
		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			if(!in_array((int)$products[$i]['id'],array_trim(explode(',',NOT_USE_BOOKING_POINTS_PRODUCTS)))){	//先排除不能用积分兑换的产品
				if($products[$i]['products_margin']<=20 && $products[$i]['products_margin']>0){	//如果产品的利润为0或小于0则没有积分优惠，大于0小于20%就按以下优惠
					$discount_percentage = explode(',',POINTS_MAX_VALUE_0_20);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
				else if($products[$i]['products_margin']>20 && $products[$i]['products_margin']<=30){
					$discount_percentage = explode(',',POINTS_MAX_VALUE_20_30);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
				else if($products[$i]['products_margin']>30){
					$discount_percentage = explode(',',POINTS_MAX_VALUE_30_PLUS);
					$POINTS_MAX_DISCOUNT_LEVEL = sizeof($discount_percentage);
					if($total_success_purchased_cnt > $POINTS_MAX_DISCOUNT_LEVEL){
						$total_success_purchased_cnt = $POINTS_MAX_DISCOUNT_LEVEL;
					}
					$total_discount_max_price += $products[$i]['final_price']  * ($discount_percentage[$total_success_purchased_cnt-1]);
				}
			}
		}
	}//end if
	//echo $total_discount_max_price;

	$one_dolor_points = 1/REDEEM_POINT_VALUE;
	$points_max_value = $total_discount_max_price*$one_dolor_points;
	$max_points = $max_points > $points_max_value ? $points_max_value : $max_points;
	$max_points = $customer_shopping_points > $max_points ? $max_points : $customer_shopping_points;
	if($total_success_purchased_cnt=='1'){	//第一次购买有200积分不能使用
		$max_points -= FIRST_BOOKING_MIN_POINTS_UP_CAN_USE;
		$max_points = max(0, $max_points);
		$total_discount_max_price -= (FIRST_BOOKING_MIN_POINTS_UP_CAN_USE * REDEEM_POINT_VALUE);
		$total_discount_max_price = max(0, $total_discount_max_price);
	}
	return $max_points.'-#-'.$total_discount_max_price;
}

/**
 * 在页面显示积分和兑换信息
 *
 */
function points_selection() {
	global $currencies, $order, $cart;
	
	$total_pur_suc_nos_of_cnt = max(1, intval($_SESSION['total_pur_suc_nos_of_cnt']));	//客人是第几次购买？
	
	//bug fixed
	if(!is_numeric($order->info['total'])){ $order_info_total = $order->info['total_value']; }else{ $order_info_total = $order->info['total']; }
	if (($customer_shopping_points = tep_get_shopping_points($_SESSION['customer_id'])) && $customer_shopping_points > 0 && $total_pur_suc_nos_of_cnt>0) {
		if (get_redemption_rules($order) && (get_points_rules_discounted($order) || get_cart_mixed($order))) {
			if ($customer_shopping_points >= POINTS_LIMIT_VALUE) {
				if ((POINTS_MIN_AMOUNT == '') || ($cart->show_total() >= POINTS_MIN_AMOUNT) ) {
					if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');

					$max_points_string = calculate_max_points($customer_shopping_points);
					$max_points1 = explode("-#-",$max_points_string);
					$max_points = $max_points1[0];
					$total_allowable_discount = $max_points1[1];

					if ($order_info_total > tep_calc_shopping_pvalue($max_points)) {
						$note = '<br /><small>' . TEXT_REDEEM_SYSTEM_NOTE .'</small>';
					}					
					
					//第一次购买能兑换的积分检查
					$max_points = tep_first_booking_min_points_check($max_points, $total_pur_suc_nos_of_cnt, $customer_shopping_points);
					
					$customer_shopping_points_spending = $max_points;
?>
	        
					
					<?php echo R4F_REDEMPTIONS_BALANCE.'&nbsp;<b>'.round($customer_shopping_points,POINTS_DECIMAL_PLACES).'</b>'. R4F_REDEMPTIONS_POINTS.' (<b>'.$currencies->format(tep_calc_shopping_pvalue($customer_shopping_points)).'</b>)'; ?>
					&nbsp;&nbsp;
					<?php 
					$total_allowable_discount_points = round($total_allowable_discount,POINTS_DECIMAL_PLACES)/REDEEM_POINT_VALUE;
					if($total_allowable_discount_points > 0){
						echo R4F_REDEMPTIONS_MAX_DISCOUNT.'&nbsp;<b>'.round($total_allowable_discount_points,POINTS_DECIMAL_PLACES).'</b>'. R4F_REDEMPTIONS_POINTS.' (<b>'.$currencies->format($total_allowable_discount).'</b>)';
					}else{
						echo db_to_html('本订单不可以使用积分购买！');
					}
					
					if((int)$total_pur_suc_nos_of_cnt <= 1 && abs(FIRST_BOOKING_MIN_POINTS_UP_CAN_USE) >0 ){	//第一次购买能兑换的积分信息提示
						$can_use_points = round($customer_shopping_points - FIRST_BOOKING_MIN_POINTS_UP_CAN_USE);
						if($total_allowable_discount_points > $can_use_points){
							$can_use_usd = $currencies->format(tep_calc_shopping_pvalue($can_use_points));
							$can_use_points_use_str = '';
							if(($can_use_points * REDEEM_POINT_VALUE) >= 1){
								$can_use_points_use_str = '即<b>'.$can_use_points.html_to_db(R4F_REDEMPTIONS_POINTS).' ('.$can_use_usd.')</b>。';
							}
							echo db_to_html('<br />注意：由于您是第'.$total_pur_suc_nos_of_cnt.'次购买，所以您只能使用'.FIRST_BOOKING_MIN_POINTS_UP_CAN_USE.'以上的积分部分，'.$can_use_points_use_str.'第2次或以上购买就可以自由使用了。');
						}
					}

					if( isset($GLOBALS['customer_shopping_points_spending']) && ( $GLOBALS['customer_shopping_points_spending']  == $customer_shopping_points_spending )){
						$show_tot_price_redeem_div = '';
						$show_tot_price_redeem_button_div = 'style="display:none"';
					}else{
						$show_tot_price_redeem_div = 'style="display:none"';
						$show_tot_price_redeem_button_div = '';
					}
					?>
					<div id="show_total_before_discount" <?php echo $show_tot_price_redeem_button_div;?> >
					<script type="text/javascript">
					function change_prace_total(){
						jQuery('#total_prace').html('<?php echo $currencies->format(($order_info_total - tep_calc_shopping_pvalue($max_points)));?>');
					}
					</script>
					<?php
					echo tep_template_image_button('button_redeem_rewards.gif', "Redeem Rewards",' style="cursor:pointer" onclick="submitFunctionPoint(\''.$customer_shopping_points_spending.'\'); toggel_div_show(\'show_total_after_discount\');toggel_div(\'show_total_before_discount\');change_prace_total();"');
					?>
					</div>
					<div id="show_total_after_discount" <?php echo $show_tot_price_redeem_div;?>>
						<b>
						<span class="sp1">
						<?php 
						echo tep_draw_hidden_field('customer_shopping_points_spending'); //, $customer_shopping_points_spending
						echo R4F_REDEMPTIONS_TOTAL_AFTER_DISCOUNT.'&nbsp;&nbsp;<b id="prace_total">'.$currencies->format(($order_info_total - tep_calc_shopping_pvalue($max_points))).'</b>'; ?>
						</span>
						</b>
					</div>
            
<?php 
				}
			}
		}
	}else{
		echo '<div style="padding:10px;">'.db_to_html('您暂无积分可用！').'</div>';
	}
}


function referral_input() {

	if (tep_not_null(USE_REFERRAL_SYSTEM)) {
?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><b><?php echo TABLE_HEADING_REFERRAL; ?></b></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="5" cellpadding="2">
                <tr>
                  <td class="main"><?php echo TEXT_REFERRAL_REFERRED; ?></td>
                  <td class="main"><?php echo tep_draw_input_field('customer_referred', $customer_referred); ?></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
<?php
	}
}

// products discounted and normal products in cart?
function get_cart_mixed($order) {

	if (sizeof($order->products) >1) {
		$special = false;
		$normal = false;
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			if (tep_get_products_special_price($order->products[$i]['id']) >0) {
				$special = true;
			} else {
				$normal = true;
			}
		}

		if ($special == true && $normal == true) {
			return true;
		} else {
			return false;
		}

		return false;
	}
}

/**
 * 添加N倍积分
 * 参数说明：$old_number = 原本需要添加的积分（也可能是积分基数），$multiple倍数，$products_id
 * @param unknown_type $old_number
 * @param unknown_type $products_id
 * @return unknown
 */
function get_n_multiple_points($old_number, $products_id = 0){

	$products_id = tep_get_prid($products_id);
	if($products_id==0 || defined('N_MULTIPLE_POINTS_SWITCH')!=true ){ return $old_number;}
	//检查日期
	$the_day = date('Y-m-d');
	if(date('Y-m-d',strtotime(N_MULTIPLE_POINTS_START_DATE))>$the_day || date('Y-m-d',strtotime(N_MULTIPLE_POINTS_END_DATE))<$the_day){
		return $old_number;
	}
	//判断产品
	$products = explode(',',N_MULTIPLE_POINTS_PRODUCTS);
	foreach((array)$products as $key => $val){
		$val = str_replace(' ','',$val);
		if($val == $products_id && (int)$products_id){
			//echo $old_number.':::'.($old_number * N_MULTIPLE_POINTS_NVALUE);
			//exit;
			return ($old_number * N_MULTIPLE_POINTS_NVALUE);
			break;
		}
	}
	//判断目录
	$catalogs = explode(',',N_MULTIPLE_POINTS_CATEGORIES);
	$subcategories_array = $catalogs;
	$p_cata = get_product_categories($products_id);
	foreach((array)$catalogs as $key => $val){
		$val = str_replace(' ','',$val);
		if((int)$val>0){
			tep_get_subcategories($subcategories_array, (int)$val);
		}
	}
	//print_r($p_cata);
	//print_r($subcategories_array);
	//exit;

	foreach((array)$subcategories_array as $key => $val){
		if($val>0){
			if(array_search($val,$p_cata)===false){
			}else{
				//echo $old_number.':::'.($old_number * N_MULTIPLE_POINTS_NVALUE);
				//exit;
				return ($old_number * N_MULTIPLE_POINTS_NVALUE);
				break;
			}
		}
	}

	return $old_number;
}

/**
 * 取得特殊积分活动提示文字，主要用于订单邮件或评论和相片发表成功。$parameter为大于0的任何整数
 *
 * @param unknown_type $products_id
 * @param unknown_type $parameter
 * @return unknown
 */
function get_n_multiple_points_notes($products_id, $parameter = 1){
	if(get_n_multiple_points($parameter, $products_id) > $parameter){
		return N_MULTIPLE_POINTS_NOTES;
	}
	return '';
}

/**
 * 取得某客户今天发表的产品评论总数
 *
 * @param unknown_type $customers_id
 */
function tep_get_customers_reviews_total_today($customers_id){
	$sql = tep_db_query('SELECT count(*) as total FROM `reviews` WHERE customers_id ="'.(int)$customers_id.'" AND date_added Like "'.date('Y-m-d').'%" ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['total'];
}

/**
 * 用户第一次订购产品的可兑换积分检查，返回检查过滤后的积分
 *
 * @param unknown_type $points 积分
 * @param unknown_type $total_pur_suc_nos_of_cnt 第几次购物
 * @param unknown_type $customer_shopping_points 用户当前的可用积分数
 */
function tep_first_booking_min_points_check($points, $total_pur_suc_nos_of_cnt, $customer_shopping_points){
	if((int)$total_pur_suc_nos_of_cnt <= 1 && abs(FIRST_BOOKING_MIN_POINTS_UP_CAN_USE) >0 ){
		$can_use_points = round($customer_shopping_points - FIRST_BOOKING_MIN_POINTS_UP_CAN_USE);
		if($points > $can_use_points){
			$points = $can_use_points;
		}
	}
	$points += 0;
	return max(0, $points);
}


?>