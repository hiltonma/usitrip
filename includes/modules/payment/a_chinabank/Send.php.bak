<?php
//=======================================================================================我们添加的程序============================start {
require_once("chinabank.config.php");
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
//$total_fee = 0.11;
//添加结伴同游支付参数数组}

$_POST['v_oid'] = trim($_GET['order_id']).'_'.date('Ymdhis');	//_号之前是订单号，_后面是日期
$_POST['v_amount'] = $total_fee;	//支付金额
$_POST['remark2'] = $_GET['travelCompanionPay']; //结伴同游的数组串
//==取得订单资料=========================================================}
//=======================================================================================我们添加的程序============================end }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN"><head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta name="robots" content="noindex,nofollow">
<title>正在连接目标网页......网银在线</title>
<link href="global.css" rel="stylesheet" type="text/css">

</head>

<body scroll="no" style="position:static;" bgcolor="#FFFFFF" onLoad="javascript:document.E_FORM.submit()">
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
	<div class="usitrip color_orange">正在前往网银在线支付页面进行支付&hellip;&hellip;</div>
    <div class="wait">
    	<p class="s_1" style="text-align:center;"><img src="images/chinabank_logo.gif" alt="chinabank_logo"/></p>
        <p class="s_2"><img src="images/loading.gif"></p>
    	<p class="s_3">
		<strong class="color_green font_bold">USITRIP走四方，伴您一起走四方！</strong>
		</p>
    </div>
	<div>
<?php
//****************************************
	$v_mid = MODULE_PAYMENT_A_CHINABANK_ID;				// 商户号，这里为测试商户号1001，替换为自己的商户号(老版商户号为4位或5位,新版为8位)即可
	$v_url = MODULE_PAYMENT_A_CHINABANK_RETURN_URL;		// 请填写返回url,地址应为绝对路径,带有http协议
	$key   = MODULE_PAYMENT_A_CHINABANK_KEY;			// 如果您还没有设置MD5密钥请登陆我们为您提供商户后台，地址：https://merchant3.chinabank.com.cn/
														// 登陆后在上面的导航栏里可能找到"B2C"，在二级导航栏里有"MD5密钥设置" 
														// 建议您设置一个16位以上的密钥或更高，密钥最多64位，但设置16位已经足够了
//****************************************


if(trim($_POST['v_oid'])<>"")					//判断是否有传递订单号
{
	   $v_oid = trim($_POST['v_oid']); 
}
else
{
	   $v_oid = date('Ymd',time())."-".$v_mid."-".date('His',time());//订单号，建议构成格式 年月日-商户号-小时分钟秒

}
	 
	$v_amount = trim($_POST['v_amount']);                   //支付金额                 
    $v_moneytype = "CNY";                                            //币种目前只支持人民币CNY

	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;        //md5加密拼凑串,注意顺序不能变
    $v_md5info = strtoupper(md5($text));                             //md5函数加密并转化成大写字母

	 $remark1 = trim($_POST['remark1']);					 //备注字段1
	 $remark2 = trim($_POST['remark2']);                    //备注字段2



	$v_rcvname   = trim($_POST['v_rcvname'])  ;		// 收货人
	$v_rcvaddr   = trim($_POST['v_rcvaddr'])  ;		// 收货地址
	$v_rcvtel    = trim($_POST['v_rcvtel'])   ;		// 收货人电话
	$v_rcvpost   = trim($_POST['v_rcvpost'])  ;		// 收货人邮编
	$v_rcvemail  = trim($_POST['v_rcvemail']) ;		// 收货人邮件
	$v_rcvmobile = trim($_POST['v_rcvmobile']);		// 收货人手机号

	$v_ordername   = trim($_POST['v_ordername'])  ;	// 订货人姓名
	$v_orderaddr   = trim($_POST['v_orderaddr'])  ;	// 订货人地址
	$v_ordertel    = trim($_POST['v_ordertel'])   ;	// 订货人电话
	$v_orderpost   = trim($_POST['v_orderpost'])  ;	// 订货人邮编
	$v_orderemail  = trim($_POST['v_orderemail']) ;	// 订货人邮件
	$v_ordermobile = trim($_POST['v_ordermobile']);	// 订货人手机号 

?>

<!--以下信息为标准的 HTML 格式 + ASP 语言 拼凑而成的 网银在线 支付接口标准演示页面 无需修改-->

<form method="post" name="E_FORM" action="https://Pay3.chinabank.com.cn/PayGate">
	<input type="hidden" name="v_mid"         value="<?php echo $v_mid;?>">
	<input type="hidden" name="v_oid"         value="<?php echo $v_oid;?>">
	<input type="hidden" name="v_amount"      value="<?php echo $v_amount;?>">
	<input type="hidden" name="v_moneytype"   value="<?php echo $v_moneytype;?>">
	<input type="hidden" name="v_url"         value="<?php echo $v_url;?>">
	<input type="hidden" name="v_md5info"     value="<?php echo $v_md5info;?>">
 
 <!--以下几项项为网上支付完成后，随支付反馈信息一同传给信息接收页 -->	
	
	<input type="hidden" name="remark1"       value="<?php echo $remark1;?>">
	<input type="hidden" name="remark2"       value="<?php echo $remark2;?>">



<!--以下几项只是用来记录客户信息，可以不用，不影响支付 -->
	<input type="hidden" name="v_rcvname"      value="<?php echo $v_rcvname;?>">
	<input type="hidden" name="v_rcvtel"       value="<?php echo $v_rcvtel;?>">
	<input type="hidden" name="v_rcvpost"      value="<?php echo $v_rcvpost;?>">
	<input type="hidden" name="v_rcvaddr"      value="<?php echo $v_rcvaddr;?>">
	<input type="hidden" name="v_rcvemail"     value="<?php echo $v_rcvemail;?>">
	<input type="hidden" name="v_rcvmobile"    value="<?php echo $v_rcvmobile;?>">

	<input type="hidden" name="v_ordername"    value="<?php echo $v_ordername;?>">
	<input type="hidden" name="v_ordertel"     value="<?php echo $v_ordertel;?>">
	<input type="hidden" name="v_orderpost"    value="<?php echo $v_orderpost;?>">
	<input type="hidden" name="v_orderaddr"    value="<?php echo $v_orderaddr;?>">
	<input type="hidden" name="v_ordermobile"  value="<?php echo $v_ordermobile;?>">
	<input type="hidden" name="v_orderemail"   value="<?php echo $v_orderemail;?>">
<input type="submit" value="确定" />

</form>

	</div>
</div>

</body>
</html>