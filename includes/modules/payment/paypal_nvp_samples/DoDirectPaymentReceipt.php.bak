<?php
/***********************************************************
DoDirectPaymentReceipt.php

Submits a credit card transaction to PayPal using a
DoDirectPayment request.

The code collects transaction parameters from the form
displayed by DoDirectPayment.php then constructs and sends
the DoDirectPayment request string to the PayPal server.
The paymentType variable becomes the PAYMENTACTION parameter
of the request string.

After the PayPal server returns the response, the code
displays the API request and response in the browser.
If the response from PayPal was a success, it displays the
response parameters. If the response was an error, it
displays the errors.

Called by DoDirectPayment.php.

Calls CallerService.php and APIError.php.

***********************************************************/

//Howard added{ 
define('INCLUDES_DIR',preg_replace('@/includes/.*@','',dirname(__FILE__))."/includes/");
require_once(INCLUDES_DIR."configure.php");
require_once(INCLUDES_DIR."functions/database.php");
tep_db_connect() or die('Unable to connect to database server!');
//导入一些必要的函数库
require_once(INCLUDES_DIR."functions/general.php");
require_once(INCLUDES_DIR."functions/webmakers_added_functions.php");

//Howard added}

require_once 'CallerService.php';
session_start();

$comment = '';

function tep_paypal_success_update($post){
	global $comment;
	//记录付款信息到客户付款记录。 
	$orders_id = (int)$_POST['order_id'];
	$usa_value = $post["AMT"];
	$orders_id_include_time = $_POST['order_id'].'_'.date("YmdHis");
	$comment = "\n";
	$comment.= "交易状态：".$post["ACK"]."\n";
	$comment.= "付款人：".$_POST["firstName"]." ".$_POST["lastName"]."\n";
	$comment.= "金额：".$post["CURRENCYCODE"]." ".$usa_value."\n";
	$comment.= "交易时间(财务对账)：".date('Y-m-d H:i:s',strtotime($post["TIMESTAMP"]))."\n";
	$comment.= "交易时间(源)：[".$post["TIMESTAMP"]."]\n";
	$comment.= "流水号：".$post["TRANSACTIONID"]."\n";
	$comment.= "订单号：".$orders_id_include_time."\n";
	$comment.= "付款人的BUILD：".$post["BUILD"]."\n";
	$comment.= "通知类型：（实时通知）\n".__FILE__;
	return tep_payment_success_update($orders_id, $usa_value, '信用卡', $comment, 96, $orders_id_include_time);
}


/**
 * Get required parameters from the web form for the request
 */
$paymentType =urlencode( $_POST['paymentType']);
$firstName =urlencode( $_POST['firstName']);
$lastName =urlencode( $_POST['lastName']);
$creditCardType =urlencode( $_POST['creditCardType']);
$creditCardNumber = urlencode($_POST['creditCardNumber']);
$expDateMonth =urlencode( $_POST['expDateMonth']);

// Month must be padded with leading zero
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

$expDateYear =urlencode( $_POST['expDateYear']);
$cvv2Number = urlencode($_POST['cvv2Number']);
$address1 = urlencode($_POST['address1']);
$address2 = urlencode($_POST['address2']);
$city = urlencode($_POST['city']);
$state =urlencode( $_POST['state']);
$zip = urlencode($_POST['zip']);
$amount = urlencode($_POST['amount']);
//$currencyCode=urlencode($_POST['currency']);
$currencyCode="USD";
$paymentType=urlencode($_POST['paymentType']);

/* Construct the request string that will be sent to PayPal.
   The variable $nvpstr contains all the variables and is a
   name value pair string with & as a delimiter */
$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";



/* Make the API call to PayPal, using API signature.
   The API response is stored in an associative array called $resArray */
$resArray=hash_call("doDirectPayment",$nvpstr);

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);

//print_r($resArray);
//print_r($_POST);
//exit;

if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  {	//信用卡支付失败

	$_SESSION['reshash']=$resArray;
	$remark2Get = '';
	if($_POST['remark2']!=""){
		$remark2Get = '&remark2='.$_POST['remark2'];
	}
	//记录日志以备与财务对账[除了tmp文件夹可写之外，其它文件夹都不可写]
	$error_log_file = DIR_FS_CATALOG.'tmp/DoDirectPaymentReceipt.error_log.txt';
	$error_notes.= 'date:'.date("Y-m-d H:i:s")."\n";
	$error_notes.= 'order_id:'.$_POST['order_id']."\n";
	$error_notes.= print_r($resArray, true)."\n";
	if($handle = fopen($error_log_file, 'ab')){
		fwrite($handle, $error_notes);
		fclose($handle);
	}
	//重定向页面
	$location = "APIError.php?order_id=".$_POST['order_id'].$remark2Get;
	header("Location: $location");
}else{	//信用卡支付成功处理页面
/*
$resArray => Array
(
    [TIMESTAMP] =&gt; 2012-02-25T13:51:00Z //交易时间截
    [CORRELATIONID] =&gt; 9eedd8edd601c
    [ACK] =&gt; Success	//交易状态
    [VERSION] =&gt; 65.1	//版本
    [BUILD] =&gt; 2571254	
    [AMT] =&gt; 0.01	//交易金额
    [CURRENCYCODE] =&gt; USD	//收到的币种
    [AVSCODE] =&gt; G
    [CVV2MATCH] =&gt; M
    [TRANSACTIONID] =&gt; 7U783924M5600562A	//交易流水号（非常重要的指标）
)*/
	$update_action = tep_paypal_success_update($resArray);
	
	$return_url_page = HTTP_SERVER.'/account_history_info.php?order_id='.(int)$_POST['order_id'].'&need_send_payment_success_email=1&success_payment=paypal_nvp_samples';
	//结伴同游信息{
	$extra_common_param = $_POST['remark2'];
	if($update_action==true && isset($extra_common_param) && $extra_common_param!=""){
		$travelCompanionPayStr = base64_decode($extra_common_param);
		$travelCompanionPay = json_decode($travelCompanionPayStr,true);
		
		$orders_travel_companion_status = '1';
		if(number_format($resArray['AMT'],2,'.','') == number_format($travelCompanionPay['i_need_pay'],2,'.','')){
			$orders_travel_companion_status = '2';
		}
		//按份均分
		$averge_usa_value = $usa_value / (max(1, sizeof(explode(',',$travelCompanionPay['orders_travel_companion_ids']))));
		$averge_usa_value = number_format($averge_usa_value, 2,'.','');		
		$sql_date_array = array(
								'last_modified' => date('Y-m-d H:i:s'),
								'orders_travel_companion_status' => $orders_travel_companion_status,	//付款状态
								'payment' => 'paypal_nvp_samples',
								'payment_name' => '信用卡', 
								'payment_customers_id' => $travelCompanionPay['customer_id']
								);
		tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.(int)$_POST['order_id'].'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');
		tep_db_query('update orders_travel_companion set payment_description = CONCAT(`payment_description`,"\n '.tep_db_input($comment).'"), paid = paid+'.$averge_usa_value.' where orders_id="'.(int)$_POST['order_id'].'" AND orders_travel_companion_id in('.$travelCompanionPay['orders_travel_companion_ids'].') ');
		
		// 返回订单页面
		$return_url_page = HTTP_SERVER.'/orders_travel_companion_info.php?order_id='.(int)$_POST['order_id'];
		//print_r($travelCompanionPay);
	}
	//结伴同游信息}
	header('Location: '.$return_url_page);


?>

<html>
<head>
    <title>您已经成功支付！</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<br>
	<center>
	<font size=2 color=black face=Verdana><b>Do Direct Payment</b></font>
	<br><br>

	<h1>恭喜，您已经成功支付！5秒后将自动返回此笔支付的订单……</h1><br><br>
	
   <table width=400>

        <?php 
   		 	require_once 'ShowAllResponse.php';
   		 ?>
    </table>
    </center>
    <a class="home" id="CallsLink" href="index.html">Home</a>
</body>

</html>


<?php }?>