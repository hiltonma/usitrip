<?php
//====================网银在线接口配置文件=============================================
// Howard added {
// set timezone
ini_set('date.timezone','UTC-07:00');
// set the level of error reporting
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

header("Content-type: text/html; charset=gb2312");
define('INCLUDES_DIR',preg_replace('@/includes/.*@','',dirname(__FILE__))."/includes/");
require_once(INCLUDES_DIR."configure.php");
require_once(INCLUDES_DIR."functions/database.php");
tep_db_connect() or die('Unable to connect to database server!');
//取得网银在线定义在数据库中的常量
$key_in = " 'MODULE_PAYMENT_NETPAY_STATUS', 'MODULE_PAYMENT_NETPAY_API_DIR', 'MODULE_PAYMENT_NETPAY_SORT_ORDER', 'MODULE_PAYMENT_NETPAY_PRI_KEY', 'MODULE_PAYMENT_NETPAY_PUB_KEY','MODULE_PAYMENT_NETPAY_RETURN_URL','MODULE_PAYMENT_NETPAY_REQ_URL_PAY' ";

$sql = tep_db_query("select configuration_key, configuration_value from configuration where configuration_key in (".$key_in.") ");
while($rows = tep_db_fetch_array($sql)){
	define($rows['configuration_key'], $rows['configuration_value']);
}
//导入一些必要的函数库
require_once(INCLUDES_DIR."functions/general.php");
require_once(INCLUDES_DIR."functions/webmakers_added_functions.php");

// Howard added }

/*请按照您的实际情况配置以下各参数*/

//私钥文件，在CHINAPAY申请商户号时获取，请相应修改此处，可填相对路径，下同
define("PRI_KEY", MODULE_PAYMENT_NETPAY_PRI_KEY); //"MerPrk.key"
//公钥文件，示例中已经包含
define("PUB_KEY", MODULE_PAYMENT_NETPAY_PUB_KEY); //"PgPubk.key"

/*如您已有生产密钥，请修改以下配置，默认为测试环境*/

//支付请求地址(测试)
//define("REQ_URL_PAY","http://payment-test.ChinaPay.com/pay/TransGet"); //此地址返回Error 500: 错误，不知道怎么回事。
//支付请求地址(生产)
define("REQ_URL_PAY",MODULE_PAYMENT_NETPAY_REQ_URL_PAY); //"https://payment.ChinaPay.com/pay/TransGet"



//查询请求地址(测试)
//define("REQ_URL_QRY","http://payment-test.chinapay.com/QueryWeb/processQuery.jsp");
//查询请求地址(生产)
define("REQ_URL_QRY","http://console.chinapay.com/QueryWeb/processQuery.jsp");

//退款请求地址(测试)
//define("REQ_URL_REF","http://payment-test.chinapay.com/refund/SingleRefund.jsp");
//退款请求地址(生产)
define("REQ_URL_REF","https://bak.chinapay.com/refund/SingleRefund.jsp");

function getcwdOL(){
	$total = $_SERVER['PHP_SELF']; //$_SERVER[PHP_SELF];
	$file = explode("/", $total);
	$file = $file[sizeof($file)-1];
	return substr($total, 0, strlen($total)-strlen($file)-1);
}

function getSiteUrl(){
	$host = $_SERVER['SERVER_NAME'];
	$port = ($_SERVER['SERVER_PORT']=="80")?"":":{$_SERVER['SERVER_PORT']}";
	return "http://" . $host . $port . getcwdOL();
}

function traceLog($file, $log){
	$f = fopen($file, 'a');
	if($f){
		fwrite($f, date('Y-m-d H:i:s') . " => {$log}\n");
		fclose($f);
	}
}

//取得本示例安装位置
$site_url = getSiteUrl();
?>