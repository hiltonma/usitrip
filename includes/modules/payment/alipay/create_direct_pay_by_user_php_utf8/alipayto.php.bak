<?php
/* *
 * 功能：即时到帐接口接入页
 * 版本：3.2
 * 修改日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
require_once("alipay.config.php");
require_once("lib/alipay_service.class.php");

//==取得订单资料========================================================={
//$sql = tep_db_query('SELECT ot.value, o.us_to_cny_rate FROM `orders` o, orders_total ot where o.orders_id=ot.orders_id and ot.class="ot_total" and o.orders_id="'.(int)$_GET['order_id'].'" ');
//$row = tep_db_fetch_array($sql);
//$cny_total = number_format($row['value'] * (max(1,$row['us_to_cny_rate'])), 2, '.', '');
$_totals = tep_get_need_pay_value((int)$_GET['order_id']);
$cny_total = $_totals[1];
//$cny_total = 0.10;

/*
if($cny_total<1){
	die("此笔订单已经付款！");
}*/

//==取得订单资料=========================================================}

/**************************请求参数**************************/

//必填参数//

//请与贵网站订单系统中的唯一订单号匹配
$out_trade_no = trim($_GET['order_id']).'_'.date('Ymdhis');	//_号之前是订单号，_后面是日期

//订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
$subject      = 'UsiTrip';
//订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
$body         = '';
//订单总金额，显示在支付宝收银台里的“应付总额”里
$total_fee    = $cny_total;

//扩展功能参数——默认支付方式//

//默认支付方式，取值见“即时到帐接口”技术文档中的请求参数列表
$paymethod    = '';
//默认网银代号，代号列表见“即时到帐接口”技术文档“附录”→“银行列表”
$defaultbank  = '';


//扩展功能参数——防钓鱼//

//防钓鱼时间戳
$anti_phishing_key  = '';
//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
$exter_invoke_ip = '';
//注意：
//1.请慎重选择是否开启防钓鱼功能
//2.exter_invoke_ip、anti_phishing_key一旦被使用过，那么它们就会成为必填参数
//3.开启防钓鱼功能后，服务器、本机电脑必须支持SSL，请配置好该环境。
//示例：
//$exter_invoke_ip = '202.1.1.1';
//$ali_service_timestamp = new AlipayService($aliapy_config);
//$anti_phishing_key = $ali_service_timestamp->query_timestamp();//获取防钓鱼时间戳函数


//扩展功能参数——其他//

//商品展示地址，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
//$show_url			= 'http://www.usitrip.com/order/myorder.php';
$show_url			= MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SHOW_URL;

//扩展功能参数——分润(若要使用，请按照注释要求的格式赋值)
$royalty_type		= "";			//提成类型，该值为固定值：10，不需要修改
$royalty_parameters	= "";
//注意：
//提成信息集，与需要结合商户网站自身情况动态获取每笔交易的各分润收款账号、各分润金额、各分润说明。最多只能设置10条
//各分润金额的总和须小于等于total_fee
//提成信息集格式为：收款方Email_1^金额1^备注1|收款方Email_2^金额2^备注2
//示例：
//royalty_type 		= "10"
//royalty_parameters= "111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二"

/************************************************************/

//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
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
//$total_fee = 0.10;
//添加结伴同游支付参数数组}

//构造要请求的参数数组
$parameter = array(
		"service"			=> "create_direct_pay_by_user",
		"payment_type"		=> "1",
		
		"partner"			=> trim($aliapy_config['partner']),
		"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
        "seller_email"		=> trim($aliapy_config['seller_email']),
        "return_url"		=> trim($aliapy_config['return_url']),
        "notify_url"		=> trim($aliapy_config['notify_url']),
		
		"out_trade_no"		=> $out_trade_no,
		"subject"			=> $subject,
		"body"				=> $body,
		"total_fee"			=> $total_fee,
		
		"paymethod"			=> $paymethod,
		"defaultbank"		=> $defaultbank,
		
		"anti_phishing_key"	=> $anti_phishing_key,
		"exter_invoke_ip"	=> $exter_invoke_ip,
		
		"show_url"			=> $show_url,
		"extra_common_param"=> $extra_common_param,
		
		"royalty_type"		=> $royalty_type,
		"royalty_parameters"=> $royalty_parameters
);


//构造即时到帐接口
$alipayService = new AlipayService($aliapy_config);
$html_text = $alipayService->create_direct_pay_by_user($parameter);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex,nofollow">
<title>正在连接目标网页......支付宝</title>
<link href="global.css" rel="stylesheet" type="text/css">

</head>

<body scroll="no" style="position:static;" bgcolor="#FFFFFF">
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

<div id="jump">
	<div class="usitrip color_orange">正在前往支付宝支付页面进行支付……</div>
    <div class="wait">
    	<p class="s_1" style="text-align:center;"><img src="images/alipay.gif" alt="alipay"/></p>
        <p class="s_2"><img src="images/loading.gif"></p>
    	<p class="s_3">
		<strong class="color_green font_bold">USITRIP走四方，伴您一起走四方！</strong>
		</p>
    </div>
	<div><?php echo $html_text;?></div>
</div>

</body>
</html>