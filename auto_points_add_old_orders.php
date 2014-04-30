<?php
require_once('includes/application_top.php');
if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {

	//credit welcome points to all old customers
	$find_old_customers = "select customers_id from ".TABLE_CUSTOMERS." where customers_id not in(select customer_id from ".TABLE_CUSTOMERS_POINTS_PENDING." where points_type='RG')";
	$res_old_customers = tep_db_query($find_old_customers);
	while($row_old_customers = tep_db_fetch_array($res_old_customers)){

		$customer_id = $row_old_customers['customers_id'];
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

	// customer pending points added
	$find_old_orders = "select o.orders_id, o.customers_id, ot.value, o.orders_status from ".TABLE_ORDERS." o, ".TABLE_ORDERS_TOTAL." ot where ot.class='ot_total' and o.orders_id=ot.orders_id group by o.orders_id"; // and o.customers_id='5747' and o.customers_id='6626' and o.orders_status='100006'
	$res_find_old_orders = tep_db_query($find_old_orders);
	while($row_find_old_orders = tep_db_fetch_array($res_find_old_orders)){

		$order_total = $row_find_old_orders['value'];

		//check that points already added for this order or not
		$check_query = tep_db_query("select orders_id from ".TABLE_CUSTOMERS_POINTS_PENDING." where orders_id = '".$row_find_old_orders['orders_id']."' and points_status!=4");

		if (($order_total > 0) && (tep_db_num_rows($check_query)==0)) {
			$points_toadd = $order_total;
			$points_comment = 'TEXT_DEFAULT_COMMENT';
			$points_type = 'SP';
			$points_awarded = $points_toadd * POINTS_PER_AMOUNT_PURCHASE;
			if($row_find_old_orders['orders_status']=='100006'){
				$points_status = 2;
			}else{
				$points_status = 1;
			}
			if(($row_find_old_orders['orders_status']!='100005') && ($row_find_old_orders['orders_status']!='6')){
				echo 'order id:'.$row_find_old_orders['orders_id'].'-->'.$order_total.'-->Points to add:'.$points_awarded.'-->point status:'.$points_status.'-->Customer id:'.$row_find_old_orders['customers_id'].'<br><br>';
				$sql_data_array = array('customer_id' => (int)$row_find_old_orders['customers_id'],
										'orders_id' => (int)$row_find_old_orders['orders_id'],
										'points_pending' => $points_awarded,
										'date_added' => 'now()',
										'points_comment' => $points_comment,
										'points_type' => $points_type,
										'points_status' => $points_status);

				tep_db_perform(TABLE_CUSTOMERS_POINTS_PENDING, $sql_data_array);
				tep_auto_fix_customers_points((int)$row_find_old_orders['customers_id']);	//自动校正用户积分
			}//end if status


		}//end if


	}//end while
}
?>