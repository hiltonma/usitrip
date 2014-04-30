<?php
header('Content-type: text/html; charset=gb2312');
include_once("config.php");
//加载 netpayclient 组件
include_once("netpayclient.php");

//导入公钥文件
$flag = buildKey(PUB_KEY);
if(!$flag) {
	echo "导入公钥文件失败！";
	exit;
}

//获取交易应答的各项值
$merid = $_REQUEST["merid"];
$orderno = $_REQUEST["orderno"];
$transdate = $_REQUEST["transdate"];
$amount = $_REQUEST["amount"];
$currencycode = $_REQUEST["currencycode"];
$transtype = $_REQUEST["transtype"];
$status = $_REQUEST["status"];
$checkvalue = $_REQUEST["checkvalue"];
$gateId = $_REQUEST["GateId"];
$priv1 = $_REQUEST["Priv1"];

echo "商户号: [$merid]<br/>";
echo "订单号: [$orderno]<br/>";
echo "订单日期: [$transdate]<br/>";
echo "订单金额: [$amount]<br/>";
echo "货币代码: [$currencycode]<br/>";
echo "交易类型: [$transtype]<br/>";
echo "交易状态: [$status]<br/>";
echo "网关号: [$gateId]<br/>";
echo "备注: [$priv1]<br/>";
echo "签名值: [$checkvalue]<br/>";
echo "===============================<br/>";

//验证签名值，true 表示验证通过
$flag = verifyTransResponse($merid, $orderno, $amount, $currencycode, $transdate, $transtype, $status, $checkvalue);
if(!flag) {
	echo "<h2>验证签名失败！</h2>";
	exit;
}
echo "<h2>验证签名成功！</h2>";
//交易状态为1001表示交易成功，其他为各类错误，如卡内余额不足等
if ($status == '1001'){
	echo "<h3>交易成功！</h3>";
	//您的处理逻辑请写在这里，如更新数据库等。
	//注意：如果您在提交时同时填写了页面返回地址和后台返回地址，且地址相同，请在这里先做一次数据库查询判断订单状态，以防止重复处理该笔订单

	$orders_id = (int)substr($orderno,0,10);	//定长16位，任意数字组合，一天内不允许重复 必填(前面10位是我们系统的订单号后6位是时间His)
	$out_trade_no = $orderno;
	$total_fee = $amount / 100;	//银联在线是以分为单位，转化成元要除100
	$notify_time = date("Y-m-d H:i:s");
	$notify_type = '实时通知';
	$extra_common_param = $priv1;	//json数组信息
	//记录成功结账信息，更新订单信息{
	$usa_value = tep_cny_to_usd($orders_id, $total_fee);
	$payment_method = '银联在线';
	$comment = "\n";
	$comment .= '人民币：'.$total_fee."\n";
	$comment .= '交易时间：'.$notify_time."\n";
	//$comment .= 'MD5校验值：'.$v_md5str."\n";
	$comment .= '订单号：'.$out_trade_no."\n";
	//$comment .= '付款人手机或电子邮箱：'.$buyer_email."\n";
	$comment .= '通知类型：'.$notify_type."\n".__FILE__;
	//$comment = iconv('utf-8','gb2312',$comment);
	$update_action = tep_payment_success_update($orders_id, $usa_value, $payment_method, $comment, 96, $out_trade_no);
	//返回页
	$return_url_page = HTTP_SERVER.'/account_history_info.php?order_id='.(int)$orders_id;

	//结伴同游信息{
	if($update_action==true && isset($extra_common_param) && $extra_common_param!="" && strlen($extra_common_param)<60 ){	//变态的银联在线，只能存60个字符在priv1
		$travelCompanionPayStr = base64_decode($extra_common_param);
		$travelCompanionPay = json_decode($travelCompanionPayStr,true);

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
	header('Location: '.$return_url_page);
	//记录成功结账信息，更新订单信息}


} else {
	echo "<h3>交易失败！</h3>";
}

/*
?>
<title>支付应答</title>
<h1>支付应答</h1>

<h5><a href="query_submit.php?transdate=<?php echo $transdate; ?>&ordid=<?php echo $orderno; ?>" target="_blank">查询该笔订单</a></h5>

<h5><a href="refund_submit.php?priv1=<?php echo date('YmdHis');?>&transdate=<?php echo $transdate; ?>&ordid=<?php echo $orderno; ?>&refundamount=<?php echo $amount; ?>&transtype=0002" target="_blank">发起全额退款</a></h5>

*/
?>