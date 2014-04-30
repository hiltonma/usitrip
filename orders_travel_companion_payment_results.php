<?php
//结伴同游结帐结果处理
require('includes/application_top.php');
 
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => 'orders_travel_companion_payment_results.php'));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

//print_r($_SESSION);
//exit;

if(tep_not_null($travel_companion_ids) && tep_not_null($pay_order_id) || (tep_not_null($_GET['travel_companion_ids']) && tep_not_null($_GET['pay_order_id']))){
	
	$orders_travel_companion_status = $_GET['orders_travel_companion_status'];
	$orders_travel_companion_id = (tep_not_null($_GET['travel_companion_ids']) && $_GET['travel_companion_ids']!='Array')? $_GET['travel_companion_ids'] : $_SESSION['travel_companion_ids'];

	$p_order_id = tep_not_null($_GET['pay_order_id']) ? $_GET['pay_order_id'] : $_SESSION['pay_order_id'];
	if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
	$payment = tep_not_null($_GET['payment']) ? $_GET['payment'] : $_SESSION['payment'];

	require_once(DIR_FS_CLASSES . 'payment.php');
	$payment_modules = new payment($payment);
	$selection = $payment_modules->selection();
	$payment_name = strip_tags($selection[0]['module']);
	$payment_name = str_replace('&nbsp;','',$payment_name);
	
	$_SESSION['travel_companion_ids'] ='';
	unset($_SESSION['travel_companion_ids']);
	$_SESSION['pay_order_id'] ='';
	unset($_SESSION['pay_order_id']);

	//echo $orders_travel_companion_id.'<br>';
	$str_or_array = $orders_travel_companion_id;
	if(is_array($str_or_array)){ $str_or_array = implode(',',$str_or_array); }
	
	if(!tep_not_null($str_or_array)){
		tep_redirect(tep_href_link('orders_travel_companion_info.php', 'order_id='.(int)$p_order_id, 'SSL'));
		exit;
	}
	
	//贝宝处理
	if($payment=='paypal'){
		if($_POST['payment_status']=='Pending' || $_POST['payment_status']=='Completed'){
			$orders_travel_companion_status = '1';
			
			//记录付款信息到客户付款记录，$_POST['payment_status']=="Completed"为已经安全收到款，"Pending"。//款项在途，目前Paypal有可能出现状态为Pending，实际上已经支付成功的情况。 
			$orders_id = (int)$_POST["invoice"];
			$usa_value = $_POST["payment_gross"];
			$orders_id_include_time = $_POST["invoice"];
			$comment = "交易状态：".$_POST['payment_status']."\n";
			$comment.= "美元：".$usa_value."\n";
			$comment.= "交易时间：".$_POST["payment_date"]."\n";
			$comment.= "流水号：".$_POST["txn_id"]."\n";
			$comment.= "订单号：".$_POST["invoice"]."\n";
			$comment.= "付款人的Payal账号：".$_POST["payer_email"]."\n";
			$comment.= "通知类型：（同步通知）\n".__FILE__;
			tep_payment_success_update($orders_id, $usa_value, 'paypal', $comment, 96, $orders_id_include_time);
			
		}
		$sql_date_array=array(
			'txn_type' => tep_db_input($_POST['txn_type']),
			'payment_type' => tep_db_input($_POST['payment_type']),
			'payment_status' => tep_db_input($_POST['payment_status']),
			'pending_reason' => tep_db_input($_POST['pending_reason']),
			'invoice' => tep_db_input($_POST['invoice']),
			'mc_currency' => tep_db_input($_POST['mc_currency']),
			'first_name' => tep_db_input($_POST['first_name']),
			'last_name' => tep_db_input($_POST['last_name']),
			'payer_email' => tep_db_input($_POST['payer_email']),
			'payer_id' => tep_db_input($_POST['payer_id']),
			'payer_status' => tep_db_input($_POST['payer_status']),
			'payment_date' => tep_db_input($_POST['payment_date']),
			'business' => tep_db_input($_POST['business']),
			'receiver_email' => tep_db_input($_POST['receiver_email']),
			'receiver_id' => tep_db_input($_POST['receiver_id']),
			'txn_id' => tep_db_input($_POST['txn_id']),
			'mc_gross' => tep_db_input($_POST['mc_gross']),
			'mc_fee' => tep_db_input($_POST['mc_fee']),
			'payment_gross' => tep_db_input($_POST['payment_gross']),
			'payment_fee' => tep_db_input($_POST['payment_fee']),
			'quantity' => tep_db_input($_POST['quantity']),
			'tax' => tep_db_input($_POST['tax']),
			'notify_version' => tep_db_input($_POST['tax']),
			'verify_sign' => tep_db_input($_POST['verify_sign']),
			'date_added' => date('Y-m-d H:i:s'),
			'orders_id' => (int)$p_order_id
			
		);
		tep_db_perform('paypal_travel_companion', $sql_date_array);
	}
	//贝宝处理 end
	
	//更新结伴同游信息
	$payment_description = "\n[".date("Y-m-d H:i")."] ".html_to_db($payment_name)." 付款！付款人:".tep_get_customers_email($customer_id);

	tep_db_query('UPDATE `orders_travel_companion` SET `orders_travel_companion_status` = "'.$orders_travel_companion_status.'",`payment` = "'.$payment.'",`payment_name` = "'.html_to_db($payment_name).'", payment_customers_id = "'.(int)$customer_id.'", payment_description = concat(ifnull(payment_description,"") , "'.$payment_description.'")  WHERE `orders_travel_companion_id`in('.$str_or_array.') AND orders_id="'.(int)$p_order_id.'" ');
	
	//写数据到订单状态历史
	$sql = tep_db_query('SELECT guest_name FROM `orders_travel_companion` where orders_travel_companion_id in('.$str_or_array.') ');
	$guest_names ='';
	while($rows = tep_db_fetch_array($sql)){
		$guest_names.= $rows['guest_name'].',';
	}
	$guest_names = substr($guest_names,0,strlen($guest_names)-1);
	
	$sql_date_array = array(
		'orders_id' => (int)$p_order_id,
		'orders_status_id' => '1',
		'date_added' => date('Y-m-d H:i:s'),
		'customer_notified' => '1',
		'comments' => '结伴同游客户付款，付款方式 '. html_to_db($payment_name).'。客户电子邮件 '.tep_get_customers_email($customer_id).'；参团人：'.$guest_names
	);
	tep_db_perform('orders_status_history', $sql_date_array);
	
	//amit added to fixed update order status to pending	
	 $sql_data_array = array('orders_status' => '1');
     tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int)$p_order_id . "'");
	//amit added to fixed update order status to pending	
	
	//给结伴同游成员发邮件
	send_travel_pay_staus_mail($p_order_id);
	
	//
	//print_r($_POST);
	//exit;
	$messageStack->add_session('travel_companion', db_to_html('付款信息成功提交，当前状态为付款审核中！'), 'success');
	tep_redirect(tep_href_link('orders_travel_companion_info.php', 'order_id='.(int)$p_order_id, 'SSL'));
	
}

tep_redirect(tep_href_link('orders_travel_companion.php', '', 'SSL'));

?>