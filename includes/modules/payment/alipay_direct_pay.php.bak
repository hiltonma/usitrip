<?php
class alipay_direct_pay {
	var $code, $title, $description, $enabled, $currency;

	// class constructor
	function alipay_direct_pay() {
		$this->code = 'alipay_direct_pay';
		$this->title = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_TEXT_TITLE;
		$this->sort_order = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SORT_ORDER;
		$this->description = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_TEXT_DESCRIPTION;
		$this->email_footer = '';
		$this->enabled = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_STATUS;
		$this->currency = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_CURRENCY;

	}
	// class methods
	function javascript_validation() {
		return false;
	}

	function selection() {

		$cc_explain = '
		
		<div>
		<ul>
		<li>
		<img  src="'.MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR.'images/alipay.gif" border="0" />
		<img  src="'.MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR.'images/veriSign.gif" border="0" />
		</li>
	  
	  <li>
	  <b> 提示：</b>
	  </li>
	  <li>1. 支付宝用人民币支付，可以使用信用卡和网上银行支付！</li>
		<li>2. USITRIP是支付宝认证的信用商家  实时到帐，无任何手续费！支付宝通过国家权威安全认证！</li>
		<li>3. 如果您对汇率方面有任何疑问，请与我们联系。</li>
	  </ul>
	  </div>';
		$cc_explain = db_to_html($cc_explain);

		$fields[] = array('title' => '', //MODULE_PAYMENT_PAYPAL_TEXT_TITLE,
		'field' => $cc_explain /*.'<div><b>' . $paypal_cc_txt . '</b></div>' .*/  );

		$title_text = $this->title;

		$warm_tips = '<div>'.MODULE_PAYMENT_PAYPAL_TEXT_WARM_TIPS.'</div>';	//温馨提示栏

		return array('id' => $this->code,
		'module' => $title_text,
		'fields' => $fields,
		'warm_tips' => $warm_tips,
		'currency' => (tep_not_null($this->currency) ? $this->currency : 'CNY'));

	}
	//    function selection() {
	//      return false;
	//    }

	function pre_confirmation_check() {
		return false;
	}


	// I take no credit for this, I just hunted down variables, the actual code was stolen from the 2checkout
	// module.  About 20 minutes of trouble shooting and poof, here it is. -- Thomas Keats
	function confirmation() {
		global $HTTP_POST_VARS;

		$confirmation = array('title' => $this->title . ': ' . $this->check,
		'fields' => array(array('title' => MODULE_PAYMENT_ALIPAY_DIRECT_PAY_TEXT_DESCRIPTION)));

		return $confirmation;
	}

	// Below is the original pre-November snapshot code.  I have left it souly for the less technical minded might
	// be able to compare what some of the more indepth changes consisted of.  Perhaps it will facilitate more preNov
	// Snapshots to being modified to postNov snapshot compatibility -- Thomas Keats

	//    function confirmation() {
	//      $confirmation_string = '          <tr>' . "\n" .
	//                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_ALIPAY_DIRECT_PAY_TEXT_DESCRIPTION . $
	//                             '          </tr>' . "\n";
	//      return $confirmation_string;
	//    }

	function process_button() {
		return false;
	}

	/**
	 * 支付过程发送数据到支付宝，让其完成支付过程
	 *
	 * @return unknown
	 */
	function before_process() {	//支付宝的执行数据
		global $order, $order_totals, $insert_id;
		/*
		$outputs = false;
		
		//必填参数----------------------------------------------------------------------------------------
		$to_charset = 'utf-8';	//发送给支付宝服务器时采用的字符编码
		$out_trade_no = $insert_id.'_'.date('Ymdhis');	//发送到支付宝的订单号。_号之前是订单号，_后面是日期
		$subject = 'UsiTrip';	//订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
		$body = '';	//订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
		$total_fee = '';	//订单总金额，显示在支付宝收银台里的“应付总额”里
		foreach ($order_totals as $key => $val){
			if($order_totals[$key]['code']=='ot_total'){
				$total_fee = $order_totals[$key]['value'];
				break;
			}
		}
		$paymethod = '1';	//默认支付方式，取值见“即时到帐接口”技术文档中的请求参数列表
		$defaultbank  = '';	//默认网银代号，代号列表见“即时到帐接口”技术文档“附录”→“银行列表”
		
		//扩展功能参数——防钓鱼----------------------------------------------------------------------------
		$anti_phishing_key  = '';	//防钓鱼时间戳
		$exter_invoke_ip = tep_get_ip_address();	//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
		//注意：
		//1.请慎重选择是否开启防钓鱼功能
		//2.exter_invoke_ip、anti_phishing_key一旦被使用过，那么它们就会成为必填参数
		//3.开启防钓鱼功能后，服务器、本机电脑必须支持SSL，请配置好该环境。
		//示例：
		//$exter_invoke_ip = '202.1.1.1';
		//$ali_service_timestamp = new AlipayService($aliapy_config);
		//$anti_phishing_key = $ali_service_timestamp->query_timestamp();//获取防钓鱼时间戳函数
	
		//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
		$extra_common_param = '';		
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

		$input_parameter = array(
		"service"			=> "create_direct_pay_by_user",	
		"payment_type"		=> "1",	//支付类型
		"partner"			=> trim(MODULE_PAYMENT_ALIPAY_DIRECT_PAY_ID),	//合作身份者id，以2088开头的16位纯数字
		"_input_charset"	=> trim(strtolower($to_charset)),
		"seller_email"		=> trim(MODULE_PAYMENT_ALIPAY_DIRECT_PAY_EMAIL),	//签约支付宝账号或卖家支付宝帐户
		"return_url"		=> trim(MODULE_PAYMENT_ALIPAY_DIRECT_PAY_RETURN_URL),	//页面跳转同步通知页面路径
		"notify_url"		=> trim(MODULE_PAYMENT_ALIPAY_DIRECT_PAY_NOTIFY_URL),	//服务器异步通知页面路径
		"out_trade_no"		=> $out_trade_no,
		"subject"			=> $subject,
		"body"				=> $body,
		"total_fee"			=> $total_fee,
		"paymethod"			=> $paymethod,
		"defaultbank"		=> $defaultbank,
		"anti_phishing_key"	=> $anti_phishing_key,
		"exter_invoke_ip"	=> $exter_invoke_ip,
		"show_url"			=> trim(MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SHOW_URL),	//商品展示地址，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
		"extra_common_param"=> $extra_common_param,
		"royalty_type"		=> $royalty_type,
		"royalty_parameters"=> $royalty_parameters
		);
		$outputs.= tep_draw_form('alipaysubmit','https://mapi.alipay.com/gateway.do?_input_charset='.$to_charset,'get','id="alipaysubmit" ');
		foreach ($input_parameter as $key => $val){
			//echo tep_draw_hidden_field($key, $val);
			//if($val!=''){
				$outputs.=tep_draw_input_field($key, $val);
			//}
		}
		$outputs.= '<button type="submit">submit</button>';
		$outputs.= '</form>';
		//$outputs.= '<script type="text/javascript">document.getElementById("alipaysubmit").submit();</script>';
		return $outputs;
		//echo $outputs;
		*/
		return false;
	}

	function after_process() {
		return false;
	}

	function output_error() {
		return false;
	}

	function check() {
		if (!isset($this->check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_STATUS'");
			$this->check = tep_db_num_rows($check_query);
		}
		return $this->check;
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('是否启用此模块', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_STATUS', '1', '只有启用了此模块之后前台才能显示该支付模块。', '6', '1', 'tep_cfg_select_option_change_display(array(\'1\', \'0\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('支付宝接口路径', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_DIR', '".dirname(__FILE__)."/alipay/create_direct_pay_by_user_php_utf8/', '存放支付宝接口路径的目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('支付宝接口http路径', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR', '".HTTP_SERVER."/includes/modules/payment/alipay/create_direct_pay_by_user_php_utf8/', '存放支付宝接口路径的http目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('排序序号', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SORT_ORDER', '0', '在所有支付模块中排第几位？', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('合作身份者ID', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_ID', '2088102151235921', '合作身份者ID，以2088开头的16位纯数字', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('安全检验码', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_KEY', 'wtnnm4j45fdmed9ntbqsxy4uk10hh3dl', '安全检验码，以数字和字母组成的32位字符', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('卖家支付宝帐户', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_EMAIL', 'service@usitrip.com', '签约支付宝账号或卖家支付宝帐户', '6', '6', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('异步通知页面', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_NOTIFY_URL', '".HTTP_SERVER."/includes/modules/payment/alipay/create_direct_pay_by_user_php_utf8/notify_url.php', '交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数', '6', '7', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('付完款后跳转的页面', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_RETURN_URL', '".HTTP_SERVER."/includes/modules/payment/alipay/create_direct_pay_by_user_php_utf8/return_url.php', '付完款后跳转的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数', '6', '8', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('网站商品的展示地址', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SHOW_URL', '".HTTP_SERVER."/account_history.php', '网站商品的展示地址，不允许加?id=123这类自定义参数', '6', '9', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('收款方名称', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_MAIN_NAME', 'USITrip', '收款方名称，如：公司名称、网站名称、收款人姓名等', '6', '10', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('使用的币种', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_CURRENCY', 'CNY', '接收的币种如CNY是人民币', '6', '11', now())");
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_STATUS'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_DIR'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SORT_ORDER'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_ID'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_KEY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_EMAIL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_NOTIFY_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_RETURN_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SHOW_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_MAIN_NAME'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_CURRENCY'");
	}

	function keys() {
		$keys = array('MODULE_PAYMENT_ALIPAY_DIRECT_PAY_STATUS', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_DIR','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SORT_ORDER', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_ID', 'MODULE_PAYMENT_ALIPAY_DIRECT_PAY_KEY','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_EMAIL','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_NOTIFY_URL','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_RETURN_URL','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_SHOW_URL','MODULE_PAYMENT_ALIPAY_DIRECT_PAY_MAIN_NAME,MODULE_PAYMENT_ALIPAY_DIRECT_PAY_CURRENCY');
		return $keys;
	}
}
?>