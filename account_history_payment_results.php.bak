<?php
//我的账号中Paypal支付结果处理
require('includes/application_top.php');
$payment = $_GET['payment'];
if($payment=='paypal'){	
	if(($_POST['payment_status']=="Completed" || $_POST['payment_status']=="Pending")){
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
		//成功后去订单详细页面
		tep_redirect(tep_href_link('account_history_info.php', 'order_id='.$orders_id, 'SSL'));
	}
}
?>