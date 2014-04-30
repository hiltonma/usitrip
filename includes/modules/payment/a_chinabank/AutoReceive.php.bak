<?
//****************************************	//MD5密钥要跟订单提交页相同，如Send.asp里的 key = "test" ,修改""号内 test 为您的密钥
											//如果您还没有设置MD5密钥请登陆我们为您提供商户后台，地址：https://merchant3.chinabank.com.cn/
	$key = MODULE_PAYMENT_A_CHINABANK_KEY;	//登陆后在上面的导航栏里可能找到“B2C”，在二级导航栏里有“MD5密钥设置”
											//建议您设置一个16位以上的密钥或更高，密钥最多64位，但设置16位已经足够了
//****************************************

$v_oid     =trim($_POST['v_oid']);      
$v_pmode   =trim($_POST['v_pmode']);      
$v_pstatus =trim($_POST['v_pstatus']);      
$v_pstring =trim($_POST['v_pstring']);      
$v_amount  =trim($_POST['v_amount']);     
$v_moneytype  =trim($_POST['v_moneytype']);     
$remark1   =trim($_POST['remark1']);     
$remark2   =trim($_POST['remark2']);     
$v_md5str  =trim($_POST['v_md5str']);     
/**
 * 重新计算md5的值
 */
                           
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //拼凑加密串
if ($v_md5str==$md5string)
{
	
   if($v_pstatus=="20")
	{
	   //支付成功
		//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......
		$orders_id = (int)$v_oid;
		$out_trade_no = $v_oid;
		$total_fee = $v_amount;
		$notify_time = date("Y-m-d H:i:s");
		$notify_type = '异步通知';
		$extra_common_param = $remark2;	//json数组信息
		//记录成功结账信息，更新订单信息{
			$usa_value = tep_cny_to_usd($orders_id, $total_fee);
			$payment_method = '网银在线';
			//$comment = print_r($_POST, true);
			$comment = "\n";
			$comment .= '人民币：'.$total_fee."\n";
			$comment .= '交易时间：'.$notify_time."\n";
			$comment .= 'MD5校验值：'.$v_md5str."\n";
			$comment .= '订单号：'.$out_trade_no."\n";
			//$comment .= '付款人手机或电子邮箱：'.$buyer_email."\n";
			$comment .= '通知类型：'.$notify_type."\n".__FILE__;
			//$comment = iconv('utf-8','gb2312',$comment);
			$update_action = tep_payment_success_update($orders_id, $usa_value, $payment_method, $comment, 96, $out_trade_no);
			//返回页
			$return_url_page = HTTP_SERVER.'/account_history_info.php?order_id='.(int)$orders_id;
			
			//结伴同游信息{
			if($update_action==true && isset($extra_common_param) && $extra_common_param!="" && strlen($extra_common_param)<150 ){	//变态的网银在线，只能存150个字符在remark2
				$travelCompanionPayStr = base64_decode($extra_common_param);
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
										'orders_travel_companion_status' => $orders_travel_companion_status,
										'payment' => 'alipay_direct_pay',
										'payment_name' => $payment_method, 
										'payment_customers_id' => $travelCompanionPay['customer_id']
										);
				tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.(int)$orders_id.'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');
				tep_db_query('update orders_travel_companion set payment_description = CONCAT(`payment_description`,"\n '.tep_db_input($comment).'"), paid = paid+'.$averge_usa_value.' where orders_id="'.(int)$orders_id.'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');

				// 返回订单页面
				$return_url_page = HTTP_SERVER.'/orders_travel_companion_info.php?order_id='.(int)$orders_id;
				//print_r($travelCompanionPay);
			}
			//结伴同游信息}
			//异步处理不需要跳转	header('Location: '.$return_url_page);
		//记录成功结账信息，更新订单信息}

	}
  echo "ok";
	
}else{
	echo "error";
}
?>