<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.2
 * 日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 
 * TRADE_FINISHED(表示交易已经成功结束，为普通即时到帐的交易状态成功标识);
 * TRADE_SUCCESS(表示交易已经成功结束，为高级即时到帐的交易状态成功标识);
 */


require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
    $trade_no		= $_POST['trade_no'];	    	//获取支付宝交易号
    $total_fee		= $_POST['total_fee'];			//获取总价格
	$notify_time    = $_POST['notify_time'];
	$buyer_email    = $_POST['buyer_email'];
	
	$notify_type    = $_POST['notify_type'].'（异步通知）';

    if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {    //交易成功结束
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
        
		echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
		
			//记录成功结账信息，更新订单信息{
			$orders_id = (int)$out_trade_no;
				$usa_value = tep_cny_to_usd($orders_id, $total_fee);
				$payment_method = iconv('utf-8','gb2312','支付宝');
				//$comment = print_r($_POST, true);
				$comment = "\n";
				$comment .= '人民币：'.$total_fee."\n";
				$comment .= '交易时间：'.$notify_time."\n";
				$comment .= '支付宝交易号：'.$trade_no."\n";
				$comment .= '订单号：'.$out_trade_no."\n";
				$comment .= '付款人手机或电子邮箱：'.$buyer_email."\n";
				$comment .= '通知类型：'.$notify_type."\n".__FILE__;
				$comment = iconv('utf-8','gb2312',$comment);
				$update_action = tep_payment_success_update($orders_id, $usa_value, $payment_method, $comment, 96, $out_trade_no);
				//返回页
				$return_url_page = HTTP_SERVER.'/account_history_info.php?order_id='.(int)$orders_id;
				
				//结伴同游信息{
				if($update_action == true && isset($_POST['extra_common_param'])){
					$travelCompanionPayStr = $_POST['extra_common_param'];
					$travelCompanionPay = json_decode($travelCompanionPayStr,true);
					
					$orders_travel_companion_status = '1';
					if(number_format($usa_value,2,'.','') == number_format($travelCompanionPay['i_need_pay'],2,'.','')){
						$orders_travel_companion_status = '2';
					}
					//按份均分
					$averge_usa_value = $usa_value / (max(1, sizeof(explode(',',$travelCompanionPay['orders_travel_companion_ids']))));
					$averge_usa_value = number_format($averge_usa_value, 2,'.','');
					$sql_date_array = array(
											'last_modified' => date('Y-m-d H:i:s'),
											'orders_travel_companion_status' => $orders_travel_companion_status,	//2已付款
											'payment' => 'alipay_direct_pay',
											'payment_name' => $payment_method, 
											'payment_customers_id' => $travelCompanionPay['customer_id']
											);
					tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.(int)$orders_id.'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');
					tep_db_query('update orders_travel_companion set payment_description = CONCAT(`payment_description`,"\n '.tep_db_input($comment).'"), paid = paid+'.$averge_usa_value.' orders_id="'.(int)$orders_id.'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');
					// 返回订单页面
					$return_url_page = HTTP_SERVER.'/orders_travel_companion_info.php?order_id='.(int)$orders_id;
					//print_r($travelCompanionPay);
				}
				//结伴同游信息}
				//异步处理不需要跳转	header('Location: '.$return_url_page);
			//记录成功结账信息，更新订单信息}
        logResult(print_r($_POST,true));
    }
    else {
        echo "success";		//其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>