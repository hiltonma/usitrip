<?php
/*
$Id: affiliate_checkout_process.php,v 1.1.1.1 2004/03/04 23:40:36 ccwjr Exp $

OSC-Affiliate

Contribution based on:

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 - 2003 osCommerce

Released under the GNU General Public License
*/
/**
 * 下单时销售联盟处理
 * @package 
 */

if(!(int)$_COOKIE['login_id']){	//销售在操作下单时不进入联盟记录处理
  $affiliate_ref = tep_not_null($_SESSION['affiliate_ref']) ? $_SESSION['affiliate_ref'] : $affiliate_ref ;
  
  // fetch the net total of an order
  $affiliate_total = 0;
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	  //新团购给推荐人1000积分(积分状态为待定)已经停用！
	  //Howard added for new Group Buy start {
	  if(0 && (int)is_group_buy_product(tep_get_prid($order->products[$i]['id']))){
		  if((int)$affiliate_ref && $affiliate_ref!=$customer_id && (int)$affiliate_ref) {
			  $affiliate_add_points = 1000;
			  $sql_data_array = array('customer_id' => (int)$affiliate_ref,
									  'points_pending' => $affiliate_add_points,
									  'date_added' => 'now()',
									  'points_comment' => 'TEXT_NEW_GROUP_BUY_REFERRAL',
									  'points_type' => 'RF',
									  'orders_id' => $insert_id,
									  'products_id' => tep_get_prid($order->products[$i]['id']),
									  'points_status' => 1);
			  tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
			  tep_auto_fix_customers_points((int)$affiliate_ref);	//自动校正用户积分
		  }
	  }
	  //Howard added for new Group Buy end }
	  $affiliate_total += $order->products[$i]['final_price'] * $order->products[$i]['qty'];
  }
  $affiliate_total = tep_round($affiliate_total, 2);
  
  // Check for individual commission
  $affiliate_percentage = 0;
  if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
	  $affiliate_commission_query = tep_db_query ("select affiliate_commission_percent from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_ref . "'");
	  $affiliate_commission = tep_db_fetch_array($affiliate_commission_query);
	  $affiliate_percent = $affiliate_commission['affiliate_commission_percent'];
  }
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
  
  if ((int)$affiliate_ref) {
  
	  //amit added to check self affiliate link start
	  $affiliate_isvalid = '1';
	  if ($affiliate_ref == $customer_id){
		  $affiliate_isvalid = '0';
	  }
	  //amit added to check self affiliate link end
	  //记录订单销售联盟信息
	  $sql_data_array = array('affiliate_id' => $affiliate_ref,
							  'affiliate_date' => $affiliate_clientdate,
							  'affiliate_browser' => $affiliate_clientbrowser,
							  'affiliate_ipaddress' => $affiliate_clientip,
							  'affiliate_value' => $affiliate_total,
							  'affiliate_payment' => $affiliate_payment,
							  'affiliate_orders_id' => $insert_id,
							  'affiliate_clickthroughs_id' => $affiliate_clickthroughs_id,
							  'affiliate_percent' => $affiliate_percent,
							  'affiliate_salesman' => $affiliate_ref,
							  'affiliate_isvalid' => $affiliate_isvalid
	  );
  
	if(!tep_db_get_field_value('affiliate_id', 'affiliate_sales ', 'affiliate_orders_id="' . (int)$insert_id . '" ')){
		tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
	}
  
	  if (AFFILATE_USE_TIER == 'true') {
		  $affiliate_tiers_query = tep_db_query ("SELECT aa2.affiliate_id, (aa2.affiliate_rgt - aa2.affiliate_lft) as height
														FROM affiliate_affiliate AS aa1, affiliate_affiliate AS aa2
														WHERE  aa1.affiliate_root = aa2.affiliate_root
															  AND aa1.affiliate_lft BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
															  AND aa1.affiliate_rgt BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
															  AND aa1.affiliate_id =  '" . $affiliate_ref . "'
														ORDER by height asc limit 1, " . AFFILIATE_TIER_LEVELS . "
												");
		  $affiliate_tier_percentage = split("[;]" , AFFILIATE_TIER_PERCENTAGE);
		  $i=0;
		  while ($affiliate_tiers_array = tep_db_fetch_array($affiliate_tiers_query)) {
  
			  $affiliate_percent = $affiliate_tier_percentage[$i];
			  $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
			  if ($affiliate_payment > 0) {
				  //记录订单销售联盟信息
				  $sql_data_array = array('affiliate_id' => $affiliate_tiers_array['affiliate_id'],
										  'affiliate_date' => $affiliate_clientdate,
										  'affiliate_browser' => $affiliate_clientbrowser,
										  'affiliate_ipaddress' => $affiliate_clientip,
										  'affiliate_value' => $affiliate_total,
										  'affiliate_payment' => $affiliate_payment,
										  'affiliate_orders_id' => $insert_id,
										  'affiliate_clickthroughs_id' => $affiliate_clickthroughs_id,
										  'affiliate_percent' => $affiliate_percent,
										  'affiliate_salesman' => $affiliate_ref);
				  if(!tep_db_get_field_value('affiliate_id', 'affiliate_sales ', 'affiliate_orders_id="' . (int)$insert_id . '" ')){
				  	tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
				  }
			  }
			  $i++;
		  }
	  }
  }

}
?>
