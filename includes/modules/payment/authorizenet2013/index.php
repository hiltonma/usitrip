<?php
/**
 * 2013版AuthorizeNet支付接口
 * utf-8格式
 * 支付金额在我们许可的范围内
 */
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

if(__DIR__ == '__DIR__'){
	define('__DIR__', dirname(__FILE__));
}
//Howard added{
define('INCLUDES_DIR',preg_replace('@/includes/.*@','',__DIR__)."/includes/");
require_once(INCLUDES_DIR."configure.php");
require_once(INCLUDES_DIR."database_tables.php");
require_once(INCLUDES_DIR."functions/database.php");
tep_db_connect() or die('Unable to connect to database server!');
//导入一些必要的库
require_once(INCLUDES_DIR."functions/general.php");
require_once(INCLUDES_DIR."functions/password_funcs.php");
require_once(INCLUDES_DIR."functions/webmakers_added_functions.php");
require_once(INCLUDES_DIR."classes/order.php");
require_once __DIR__ . '/AuthorizeNet.php';
//Howard added}

//从数据库中取得常量
$const_sql = tep_db_query('SELECT configuration_key, configuration_value FROM `configuration` WHERE configuration_key LIKE "MODULE_PAYMENT_AUTHORIZENET2013_%" ');
while($consts = tep_db_fetch_array($const_sql)){
	define($consts['configuration_key'], $consts['configuration_value']);
}

class AuthorizeNetRequestUsitrip {
	/**
	 * html代码的换行符
	 * @var PHP_EOL
	 */
	const EOL = PHP_EOL;
	/**
	 * 订单对象
	 * @var order
	 */
	private $order;
	/**
	 * 订单号
	 * @var int
	 */
	private $orders_id;
	/**
	 * 账号
	 * @var string
	 */
	private $api_login_id = MODULE_PAYMENT_AUTHORIZENET2013_API_ID;
	/**
	 * 密码
	 * @var string
	 */
	private $transaction_key = MODULE_PAYMENT_AUTHORIZENET2013_API_KEY;
	/**
	 * 是否是测试模式
	 * @var bool
	 */
	private $test_mode = true;
	/**
	 * 网关地址
	 * @var string
	 */
	private $gateway_url = 'https://secure.authorize.net/gateway/transact.dll';
	/**
	 * 是否以指定页面地址的方式返回数据(在正式站不用此方式)
	 * @var bool
	 */
	private $use_relay = false;
	/**
	 * 接收返回信息的页面地址(在正式站不用此方式)
	 * @var string
	 */
	private $relay_url = 'https://test.usitrip.com/includes/modules/payment/authorizenet2013/index.php?action=relay';
	/**
	 * 结伴同游信息
	 * @var base64_encode(json_encode(array))
	 */
	private $travelCompanionPay = '';
	/**
	 * 结伴同游解码后的数组
	 * @var Array ( [i_need_pay] => 588 [orders_travel_companion_ids] => 330 [customer_id] => 60476 )
	 */
	private $travelCompanionPayDecode = array();
	/**
	 * 支付结果信息
	 * @var array
	 */
	private $Result = array();
	public function __construct(){		
		if(strtolower(MODULE_PAYMENT_AUTHORIZENET2013_IS_LIVE) == 'true'){	//生产站是否是生产模式判断
			$this->test_mode = false;
		}
		if(strtolower(MODULE_PAYMENT_AUTHORIZENET2013_STATUS) != 'true'){	//判断是否关闭了此支付方式
			die('The Payment has disable.');
		}
		
		$action = $_POST['action'] ? $_POST['action'] : $_GET['action'];
		$this->orders_id = $_POST['orders_id'] ? (int)$_POST['orders_id'] : (int)$_GET['order_id'];
		if(!$this->orders_id){
			die('没订单号！');
		}
		if($this->use_relay === true){
			$this->relay_url.= '&order_id='.$this->orders_id.'&microtime='.microtime(true);
		}
		$this->travelCompanionPay = ($_POST['travelCompanionPay'] ? $_POST['travelCompanionPay'] : ($_GET['travelCompanionPay'] ? $_GET['travelCompanionPay'] : ''));
		if($this->travelCompanionPay){
			$this->travelCompanionPayDecode = json_decode(base64_decode($this->travelCompanionPay), true);
			//print_r($this->travelCompanionPayDecode);exit;
		}
		$this->order = new order($this->orders_id);
		$this->action($action);
	}
	
	/**
	 * 支付的交互动作
	 * @param string $action
	 */
	public function action($action){
		switch ($action){
			default:				//默认调入信用卡付款表单页面
				header("Content-type: text/html; charset=utf-8");
				printf('<!DOCTYPE HTML>'.self::EOL.'<html lang="zh-CN">'.self::EOL.'<head>'.self::EOL.'<meta charset="utf-8" />'.self::EOL.'<title>走四方 - 信用卡支付系统</title>'.self::EOL.'%s'.self::EOL.'%s'.self::EOL.'</head>'.self::EOL.'<body>'.self::EOL.'%s'.self::EOL.'</body>'.self::EOL.'</html>', $this->css(), $this->js(), $this->paymentPage($_GET));
				exit;
			break;
			case 'paymentConfirm':	//确认支付并返回结果数据
				if($this->orderChecks($_POST)){
					$this->Result = $this->paymentConfirm($_POST);
				}
				if($this->Result){
					if($this->Result['x_response_code']=='1'){	//成功
						$this->paymentSuccess($this->Result);
					}
					echo json_encode($this->Result);
				}else{
					echo '';
				}
				/* if($Result){
					echo '<pre>';
					print_r($Result);
					echo '</pre>';
				} */
				exit;
			break;
			case 'relay':			//以指定页面地址的方式时返回数据
				echo $this->relay($_POST);
			break;
		}
	}
	/**
	 * 检查订单是否需要支付，以及最少要支付多少钱等信息！
	 * @return true|false 通过检查则返回true
	 */
	public function orderChecks($formData){
		//检查是否需付款
		$paid = 0;
		$_row = tep_db_fetch_array(tep_db_query('SELECT sum(orders_value) as paid FROM `orders_payment_history` WHERE orders_id="'.$this->orders_id.'" '));
		if($_row['paid']){
			$paid = $_row['paid'];
		}
		if($this->getTotalAmount() <= $paid){
			$this->Result['x_response_code'] = '999';
			$this->Result['x_response_reason_code'] = '2';
			$this->Result['x_response_reason_text'] = '此订单已无须付款！';
			return false;
		}
		//检查最小付款金额
		$min = $this->getMinPayAmount(2);
		if(number_format($formData['pay_amount'], 2, '.', '') < $min){
			$this->Result['x_response_code'] = '999';
			$this->Result['x_response_reason_code'] = '3';
			$this->Result['x_response_reason_text'] = '此订单本次最少应付款$'.$min.'';
			return false;
		}
		return true;
	}
	/**
	 * 确认支付
	 * cURL的方式提交并取得结果
	 * @return array
	 */
	public function paymentConfirm($post){
		$data = array();
		$post_url = $this->gateway_url;
		
		$post = (array)$post;
		$data['x_test_request'] = ($this->test_mode === true ? 'true' : 'false');	//测试模式填写true,生产模式写false或不写此参数
		$data['x_first_name'] = $post['x_first_name'];	//持卡人名
		$data['x_last_name'] = $post['x_last_name'];	//持卡人姓
		$data['x_card_num'] = $post['x_card_num'];		//卡号
		$data['x_exp_date'] = $post['x_exp_date'];		//有效日期mmyy
		$data['x_card_code'] = $post['x_card_code'];	//CCV号
		//$data['x_currency_code'] = $post['x_currency_code'];	//货币代号美元。不使用货币代号
		$data['x_amount'] = $post['pay_amount'];		//金额
		
		if($this->use_relay === true){
			$data['x_relay_response'] = "TRUE";			//以我们提供的URL返回结果数组
			$data['x_relay_url'] = $this->relay_url; 	//返回结果数组的URL
		}
		$data['x_delim_char'] = ',';					//交易数据分隔符
		//$data['x_delim_data'] = 'FALSE';				//返回交易结果数据
		$data['x_delim_data'] = 'TRUE';					//返回交易结果数据
		
		$data['x_fp_timestamp'] = $post['x_fp_timestamp'];
		$data['x_login'] = $this->api_login_id;
		$data['x_version'] = '3.1';
		$data['x_fp_hash'] = $post['x_fp_hash'];
		$data['x_description'] = $post['x_description'];		
		
		$x_fp_sequence = '600154_123456789'; //rand(1, 1000);	//订单号+时间
		$x_fp_hash = AuthorizeNetSIM_Form::getFingerprint($this->api_login_id, $this->transaction_key, $data['x_amount'], $x_fp_sequence, $post['x_fp_timestamp']);
		$data['x_fp_hash'] = $x_fp_hash;
		$data['x_fp_sequence'] = $x_fp_sequence;
		
		$dataString = '';
		// concatenate order information variables to $data
		foreach($data as $key => $value){
			$dataString .= $key . '=' . rawurlencode($value) . '&';
		}		
		// take the last & out for the string
		$dataString = substr($dataString, 0, -1);
		
		//print_r($post);exit;
		//$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
		$curl_request = curl_init($post_url);
		curl_setopt($curl_request, CURLOPT_POST, 1);
		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $dataString);
		curl_setopt($curl_request, CURLOPT_HEADER, 0);	//0,不返回头信息，千万别返回头信息！
		curl_setopt($curl_request, CURLOPT_TIMEOUT, 120);
		//curl_setopt($curl_request, CURLOPT_USERAGENT, $agent);
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, 2);	//2 false
		
		$response = curl_exec($curl_request);
		
		curl_close($curl_request);
		
		if($this->use_relay === true){
			$data = json_decode($response, true);
		}else{
			$data = $this->responseFormart($response, $data['x_delim_char']);
		}
				
		return $data;
		
	}
	/**
	 * 支付成功后处理订单数据
	 * @param array $Result
	 */
	public function paymentSuccess(array $Result){
		$orders_id = $this->orders_id;
		$comment = '';
		//记录付款信息到客户付款记录。
		$usa_value = $Result["x_amount"];
		$orders_id_include_time = $orders_id.'_'.date("YmdHis", $_POST['x_fp_timestamp']);
		$comment = "\n";
		$comment.= "流水号：".$Result["x_trans_id"]."\n";
		$comment.= "交易状态：".$Result["x_response_reason_text"]."\n";
		$comment.= "付款人：".$Result["x_first_name"]." ".$Result["x_last_name"]."\n";
		$comment.= "金额： ".$usa_value."\n";
		$comment.= "卡号：".$Result["x_card_type"]. ' ' .$Result["x_account_number"]."\n";
		$comment.= "付款表单生成时间：".date('Y-m-d H:i:s', $_POST['x_fp_timestamp'])."\n";
		$comment.= "实际交易时间：[".$Result["timestamp"]."]\n";
		$comment.= "订单号：".$orders_id_include_time."\n";
		$comment.= "md5码：".$Result["x_MD5_Hash"]."\n";
		$comment.= "通知类型：（实时通知）\n".__FILE__;
		$comment = iconv('utf-8', 'gb2312//IGNORE', $comment);
		$payment_name = iconv('utf-8', 'gb2312//IGNORE','信用卡AUTHORIZE');
		$payment = 'authorizenet2013';
		//echo ($orders_id.$comment);exit;
		$this->Result['goToUrl'] = HTTP_SERVER.'/account_history_info.php?order_id='.$orders_id.'&need_send_payment_success_email=1&success_payment='.$payment;
		if($this->travelCompanionPayDecode){	//结伴同游
			$orders_travel_companion_status = '2';			
			//按份均分
			$averge_usa_value = $usa_value / (max(1, sizeof(explode(',',$this->travelCompanionPayDecode['orders_travel_companion_ids']))));
			$averge_usa_value = number_format($averge_usa_value, 2,'.','');
			$sql_date_array = array(
					'last_modified' => date('Y-m-d H:i:s'),
					'orders_travel_companion_status' => $orders_travel_companion_status,	//付款状态
					'payment' => $payment,
					'payment_name' => $payment_name,
					'payment_customers_id' => $this->travelCompanionPayDecode['customer_id']
			);
			tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.$orders_id.'" AND orders_travel_companion_id in('.$this->travelCompanionPayDecode['orders_travel_companion_ids'].') ');
			tep_db_query('update orders_travel_companion set payment_description = CONCAT(`payment_description`,"\n '.tep_db_input($comment).'"), paid = paid+'.$averge_usa_value.' where orders_id="'.$orders_id.'" AND orders_travel_companion_id in('.$this->travelCompanionPayDecode['orders_travel_companion_ids'].') ');
			$this->Result['goToUrl'] = HTTP_SERVER.'/orders_travel_companion_info.php?order_id='.$orders_id;
		}
		return tep_payment_success_update($orders_id, $usa_value, $payment_name, $comment, 96, $orders_id_include_time);
	}
		
	/**
	 * 取得应付总金额
	 * @param int $precision 返回的小数位数默认2位
	 */
	public function getTotalAmount($precision = 2){
		$_amount = $this->order->info['total_value'];
		$data = $this->order->totals;
		foreach((array)$data as $rows){
			if($rows['class']=='ot_total'){
				$_amount = $rows['value'];
				break;
			}
		}
		return number_format($_amount, $precision, '.', '');		
	}
	/**
	 * 取得允许最小的付款金额
	 * @param int $precision 返回的小数位数默认2位
	 */
	public function getMinPayAmount($precision = 2){
		$_min = $this->order->info['allow_pay_min_money'];
		$_max_time = strtotime($this->order->info['allow_pay_min_money_deadline']);
		$need_paid = $this->getTotalAmount() - $this->order->info['orders_paid'];
		if($this->travelCompanionPayDecode['i_need_pay']){
			$need_paid = $this->travelCompanionPayDecode['i_need_pay'];
		}
		if($_min > '0.01' && $_min < $need_paid && $_max_time > time()){
			return number_format($_min, $precision, '.', '');
		}
		return number_format($need_paid, $precision, '.', '');
	}
	/**
	 * 支付页面表单
	 */
	public function paymentPage($get){
		$order_id = $this->orders_id;
		$orders_total = $this->getTotalAmount();
		if(!($amount = $this->getMinPayAmount())){
			$amount = $orders_total;
		}
		$time = time();
		//以下所有字段的信息都应是英文的，中文第三方公司无法显示
		$travelCompanionPay = $this->travelCompanionPay;
		$sim = new AuthorizeNetSIM_Form(
				array(
						'x_fp_timestamp'  => $time,
						'x_description'   => '600154描述处写订单号',		//描述处写订单号
						'action'          => 'paymentConfirm',			//提交支付动作
						'orders_id'		  => $order_id,					//订单号
						'travelCompanionPay' => $travelCompanionPay     //结伴同游标记
				)
		);
		if(IS_LIVE_SITES === true){
			$autocomplete = ' autocomplete="off" ';							//生产站上禁用自动完成
		}
		$form_action = basename(__FILE__);		
		$html ='<div id="bodyDiv">'.self::EOL;
		$html.= '<h1>走四方旅游网信用卡在线付款</h1><img src="usitrip/image/card_logo.gif" alt="信用卡在线付款">'.self::EOL;
		$html.= '<form id="formAuthorize" method="post" action="'.$form_action.'">'.self::EOL;
		$html.= $sim->getHiddenFieldString().self::EOL;
		$html.= '<ul>';
		$html.= '<li><span>名(first name)：</span><input	name="x_first_name" type="text" value="" '.$autocomplete.'></li>'.self::EOL;
		$html.= '<li><span>姓(last name)：</span><input 	name="x_last_name" 	type="text" value="" '.$autocomplete.'></li>'.self::EOL;
		$html.= '<li><span>信用卡号：</span><input 		name="x_card_num" 	type="text" value="" '.$autocomplete.'></li>'.self::EOL;	//4367455023358810
		$html.= '<li><span>信用卡有效期：</span><input 	name="x_exp_date" 	type="text" value="" '.$autocomplete.'></li>'.self::EOL;	//0616
		$html.= '<li><span>信用卡认证号码：</span><input 	name="x_card_code" 	type="text" value="522" '.$autocomplete.'></li>'.self::EOL;	//0522
		$html.= '<li><span>订单号：</span>'.$order_id.'</li>'.self::EOL;
		$html.= '<li><span>订单金额总额：</span><b>$'.$orders_total.'</b></li>'.self::EOL;
		$html.= '<li><span>本次支付金额：</span><b>$<input name="pay_amount" type="text" value="'.$amount.'" '.$autocomplete.'></b></li>'.self::EOL;
		$html.= '<li><span> </span><button id="submitAuthorize" type="submit">确定付款</button></li>'.self::EOL;
		//$html.= '<input type="radio" name="ttt" value="0" /><input type="radio" name="ttt" value="1" /><input type="radio" name="ttt" value="2" /><input type="checkbox" name="checkb[]" value="1" /><input type="checkbox" name="checkb[]" value="2" /><input type="checkbox" name="checkb[]" value="3" />'.self::EOL;
		$html.= '</ul>'.self::EOL;
		$html.= '</form>'.self::EOL;
		$html.= '<p class="noti">注意：付款前请认准本页网址是以https://www.usitrip.com/或http://www.usitrip.com/开头的地址才付款！付款后请耐心等待不要刷新页面，以免重复支付。</p>'.self::EOL;
		if($_COOKIE['login_id']){	//后台人员给客人发送付款URL的地址
			$html.= '<div id="frameDiv"><iframe style="display:block; border:none;" width="720" height="85" src="/auto_login_url.php?order_id='.$order_id.'"></iframe></div>';
		}
		$html.= '</div>'.self::EOL;
		return $html;
	}
	/**
	 * css文件
	 */
	public function css(){
		$css = '<link href="usitrip/css/main.css?d='.date("YmdHis").'" rel="stylesheet" type="text/css" />';
		return $css;
	}
	/**
	 * js代码
	 */
	public function js(){
		$jq = '<script type="text/javascript" src="/jquery-1.3.2/merger/merger.min.js"></script>';
		$js = '<script type="text/javascript" src="usitrip/js/main.js?d='.date("YmdHis").'"></script>';
		return $jq.$js;
	}
	/**
	 * 根据x_relay_url返回POST响应信息
	 * @param array $data
	 */
	public function relay($data){
		/*
		[x_response_code] => 响应码：1代表交易成功。其它均为失败
		[x_response_reason_code] => 响应原因码
		[x_response_reason_text] => (TESTMODE) 响应结果文字信息
		[x_amount] => 0.01	实际交易的金额
		[x_MD5_Hash] => B38E1162C22AB48D3C52B317F85A4CB0
		[x_test_request] => true 是否是测试模式
		...................
		*/
		$j = $data ? $data : array();
		return json_encode($j);
		//die(__METHOD__.' is Stop!');
	}
	/**
	 * 格式化响应数据
	 * @see class AuthorizeNetAIM_Response
	 * @param string $string 返回的结果字符串
	 * @param string $delimiter 分隔字符
	 * @return array
	 */
	public function responseFormart($string, $delimiter){
		$data = array();
		$array = explode($delimiter, $string);
		$data['x_response_code']        = $array[0];	//响应码：1 = 成功Approved， 2 = 拒绝Declined， 3 = 错误Error， 4 = Held for Review
		$data['x_response_reason_code'] = $array[2];	//响应原因码
		$data['x_response_reason_text'] = $this->responseTranslate($array[3]);	//响应结果文字说明
		$data['x_avs_code']         	= $array[5];	//响应参数
		$data['x_auth_code']            = $array[4];	//授权码
		$data['x_trans_id']      		= $array[6];	//流水id(在生产站中有效)
		$data['x_description']          = $array[8];	//表单发送过去的同名字段内容
		$data['x_amount']               = $array[9];	//交易金额
		$data['x_method']             	= $array[10];	//CC是信用卡
		$data['x_type']     			= $array[11];	//交易类型
		$data['x_MD5_Hash']             = $array[37];	//md5码
		$data['x_cavv_response']       	= $array[39];	//
		$data['x_account_number']       = $array[50];	//信用卡后几位
		$data['x_card_type']            = $array[51];	//发卡组织
		$data['x_first_name']           = $array[13];	//刷卡人名
		$data['x_last_name']            = $array[14];	//刷卡人姓
		$data['timestamp']			= date('Y-m-d H:i:s');	//交易时间
		//x_test_request
		return $data;
	}
	/**
	 * 返回的文本翻译成中文
	 * @param string $text 待译文字
	 * @param string $ouput_chartset 默认返回utf-8格式字符
	 * @return string 返回中文字符串
	 */
	public function responseTranslate($text, $ouput_chartset = 'utf-8'){
		$RC = require_once __DIR__.'/doc/ResponseCodeTranslate.php';
		if($this->test_mode == true){
			$text = str_replace('(TESTMODE) ', '', $text);
			$o_text = $RC[$text] ? '[测试模式] '.$RC[$text] : $text;
		}else{
			$o_text = $RC[$text] ? $RC[$text] : $text;
		}
		if(strtolower($ouput_chartset)!='utf-8'){
			$o_text = iconv('utf-8', $ouput_chartset.'//IGNORE', $o_text);
		}
		return $o_text;
	}
}

new AuthorizeNetRequestUsitrip();

