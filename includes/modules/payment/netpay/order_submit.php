<?php
	header('Content-type: text/html; charset=gb2312');
	include_once("config.php");
	
	//加载 netpayclient 组件
	include_once("netpayclient.php");
	
	//导入私钥文件, 返回值即为您的商户号，长度15位
	$merid = buildKey(PRI_KEY);
	if(!$merid) {
		echo "导入私钥文件失败！";
		exit;
	}
	
	//==取得订单资料========================================================={
	$_totals = tep_get_need_pay_value((int)$_GET['order_id']);
	$total_fee = $_totals[1];
	
	$extra_common_param = '';
	//添加结伴同游支付参数数组{
	if(isset($_GET['travelCompanionPay'])){
		$travelCompanionPayStr = base64_decode($_GET['travelCompanionPay']);
		$extra_common_param = $travelCompanionPayStr;
		$travelCompanionPay = json_decode($travelCompanionPayStr,true);
		$i_need_pay = tep_usd_to_cny((int)$_GET['order_id'], $travelCompanionPay['i_need_pay']);
		$total_fee = $i_need_pay;
		$body = '结伴同游订单';
	}
	//$total_fee = 0.01;
	//添加结伴同游支付参数数组}
	
	$ordid = str_pad(trim($_GET['order_id']), 10, 0, STR_PAD_LEFT) .date('His');	//定长16位，任意数字组合，一天内不允许重复 必填(前面10位是我们系统的订单号后6位是时间His)
	$transamt = padstr($total_fee * 100,12);	//支付金额 定长12位，以分为单位，不足左补0，必填
	$priv1 = "";//$_GET['travelCompanionPay']; //结伴同游的数组串
	//==取得订单资料=========================================================}
	
	//生成订单号，定长16位，任意数字组合，一天内不允许重复，本例采用当前时间戳，必填
	//$ordid = "00" . date('YmdHis');
	//订单金额，定长12位，以分为单位，不足左补0，必填
	//$transamt = padstr('1',12);
	//货币代码，3位，境内商户固定为156，表示人民币，必填
	$curyid = "156";
	//订单日期，本例采用当前日期，必填
	$transdate = date('Ymd');
	//交易类型，0001 表示支付交易，0002 表示退款交易
	$transtype = "0001";
	//接口版本号，境内支付为 20070129，必填
	$version = "20070129";
	//页面返回地址(您服务器上可访问的URL)，最长80位，当用户完成支付后，银行页面会自动跳转到该页面，并POST订单结果信息，可选
	$pagereturl = MODULE_PAYMENT_NETPAY_RETURN_URL; //; "{$site_url}/order_feedback.php";
	//后台返回地址(您服务器上可访问的URL)，最长80位，当用户完成支付后，我方服务器会POST订单结果信息到该页面，必填
	$bgreturl = MODULE_PAYMENT_NETPAY_RETURN_URL; //"{$site_url}/order_feedback.php";
	
	/************************
	页面返回地址和后台返回地址的区别：
	后台返回从我方服务器发出，不受用户操作和浏览器的影响，从而保证交易结果的送达。
	************************/
	
	//支付网关号，4位，上线时建议留空，以跳转到银行列表页面由用户自由选择，本示例选用0001农商行网关便于测试，可选
	//$gateid = "0001";
	$gateid = "";
	//备注，最长60位，交易成功后会原样返回，可用于额外的订单跟踪等，可选
	//$priv1 = "memo";
	
	//按次序组合订单信息为待签名串
	$plain = $merid . $ordid . $transamt . $curyid . $transdate . $transtype . $priv1;
	//生成签名值，必填
	$chkvalue = sign($plain);
	if (!$chkvalue) {
		echo "签名失败！";
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN"><head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta name="robots" content="noindex,nofollow">
<title>正在连接目标网页......银联在线</title>
<link href="global.css" rel="stylesheet" type="text/css">
<style type="text/css">
.wait{ height:auto; padding:10px 50px 20px 50px;}
.wait .s_1 img{ margin:0 auto;}
/*跳转*/
#jump{	width:860px;height:388px;position:absolute;left:50%;top:50%;z-index:5; margin:-229px 0 0 -430px;text-align:center; padding-top:70px; border:1px solid #e9e9e9;}
#jump h1{ display:inline;}
#jump .usitrip,#jump .usitrip h1{ font-size:24px; font-family:"微软雅黑",Arial, Helvetica, sans-serif; }
.wait{ width:243px; padding:0 40px; border:1px solid #dadada; margin:62px auto;} 
.pop_copyright{ background:#f9f9f9; padding:20px 0; line-height:24px; color:#888;}
.wait .s_1{ padding:12px 0;}
.wait .s_2{ background:#ececec; height:16px; font-size:0; line-height:0; margin-bottom:7px;}
.wait .s_2 img{ width:243px; height:16px;}

</style>
</head>
<body scroll="no" style="position:static;" bgcolor="#FFFFFF" onLoad="javascript:document.netpay_form.submit()">
<div id="jump">
	<div class="usitrip color_orange">正在前往银联在线支付页面进行支付&hellip;&hellip;</div>
    <div class="wait">
    	<p class="s_1" style="text-align:center;"><img src="images/logo.gif" alt="chinabank_logo"/></p>
        <p class="s_2"><img src="images/loading.gif"></p>
    	<p class="s_3">
		<strong class="color_green font_bold">USITRIP走四方，伴您一起走四方！</strong>
		</p>
    </div>
	<div>
	</div>
</div>

<form style="display:none;" action="<?php echo REQ_URL_PAY; ?>" method="post" name="netpay_form" target="_self">
<label>商户号</label><br/>
<input type="text" name="MerId" value="<? echo $merid; ?>" readonly/><br/>
<label>支付版本号</label><br/>
<input type="text" name="Version" value="<? echo $version; ?>" readonly/><br/>
<label>订单号</label><br/>
<input type="text" name="OrdId" value="<? echo $ordid; ?>" readonly/><br/>
<label>订单金额</label><br/>
<input type="text" name="TransAmt" value="<? echo $transamt; ?>" readonly/><br/>
<label>货币代码</label><br/>
<input type="text" name="CuryId" value="<? echo $curyid; ?>" readonly/><br/>
<label>订单日期</label><br/>
<input type="text" name="TransDate" value="<? echo $transdate; ?>" readonly/><br/>
<label>交易类型</label><br/>
<input type="text" name="TransType" value="<? echo $transtype; ?>" readonly/><br/>
<label>后台返回地址</label><br/>
<input type="text" name="BgRetUrl" value="<? echo $bgreturl; ?>"/><br/>
<label>页面返回地址</label><br/>
<input type="text" name="PageRetUrl" value="<? echo $pagereturl; ?>"/><br/>
<label>网关号</label><br/>
<input type="text" name="GateId" value="<? echo $gateid; ?>"/><br/>
<label>备注</label><br/>
<input type="text" name="Priv1" value="<? echo $priv1; ?>" readonly/><br/>
<label>签名值</label><br/>
<input type="text" name="ChkValue" value="<? echo $chkvalue; ?>" readonly/><br/>
<input type="submit" value="支付">
</form>
</body>
</html>
