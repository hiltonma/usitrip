<?php
class a_chinabank {
	var $code, $title, $description, $enabled, $currency;

	// class constructor
	function a_chinabank() {
		$this->code = 'a_chinabank';
		$this->title = MODULE_PAYMENT_A_CHINABANK_TEXT_TITLE;
		$this->sort_order = MODULE_PAYMENT_A_CHINABANK_SORT_ORDER;
		$this->description = MODULE_PAYMENT_A_CHINABANK_TEXT_DESCRIPTION;
		$this->email_footer = MODULE_PAYMENT_A_CHINABANK_TEXT_EMAIL_FOOTER;
		$this->enabled = MODULE_PAYMENT_A_CHINABANK_STATUS;
		$this->currency = MODULE_PAYMENT_A_CHINABANK_CURRENCY;

	}
	// class methods
	function javascript_validation() {
		return false;
	}

	function selection() {

		$cc_explain = '<div><img  src="'.MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR.'images/chinabank_logo.gif" border="0" />
	  
	  <br>
	  <b> 提示：</b>
	  <br>
		1. 网银在线用人民币支付，可以使用信用卡和网上银行支付！<br>
		2. 实时到帐，无任何手续费，usitrip.com是银联在线的信用商家 <br>
		3. 如果您对汇率方面有任何疑问，请与我们联系。<br>
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
		'fields' => array(array('title' => MODULE_PAYMENT_A_CHINABANK_TEXT_DESCRIPTION)));

		return $confirmation;
	}

	// Below is the original pre-November snapshot code.  I have left it souly for the less technical minded might
	// be able to compare what some of the more indepth changes consisted of.  Perhaps it will facilitate more preNov
	// Snapshots to being modified to postNov snapshot compatibility -- Thomas Keats

	//    function confirmation() {
	//      $confirmation_string = '          <tr>' . "\n" .
	//                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_A_CHINABANK_TEXT_DESCRIPTION . $
	//                             '          </tr>' . "\n";
	//      return $confirmation_string;
	//    }

	function process_button() {
		return false;
	}

	/**
	 * 支付过程发送数据到网银在线，让其完成支付过程
	 *
	 * @return unknown
	 */
	function before_process() {	//网银在线的执行数据
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
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_STATUS'");
			$this->check = tep_db_num_rows($check_query);
		}
		return $this->check;
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('是否启用此模块', 'MODULE_PAYMENT_A_CHINABANK_STATUS', '1', '只有启用了此模块之后前台才能显示该支付模块。', '6', '1', 'tep_cfg_select_option_change_display(array(\'1\', \'0\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('网银在线接口路径', 'MODULE_PAYMENT_A_CHINABANK_API_DIR', '".dirname(__FILE__)."/a_chinabank/', '存放网银在线接口路径的目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('网银在线接口http路径', 'MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR', '".HTTP_SERVER."/includes/modules/payment/a_chinabank/', '存放网银在线接口路径的http目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('排序序号', 'MODULE_PAYMENT_A_CHINABANK_SORT_ORDER', '101', '在所有支付模块中排第几位？', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('8位商户编号', 'MODULE_PAYMENT_A_CHINABANK_ID', '22232056', '8位商户编号，如20008686', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('付完款后跳转的页面', 'MODULE_PAYMENT_A_CHINABANK_RETURN_URL', '".HTTP_SERVER."/includes/modules/payment/a_chinabank/Receive.php', '请填写返回url,地址应为绝对路径,带有http协议', '6', '8', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('md5私钥值', 'MODULE_PAYMENT_A_CHINABANK_KEY', 'HUY5897NgeiwhjkL437jdT', 'md5私钥值，此私钥需要登陆网银后台，在后台b2c，md5密钥设置中自行设置', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('网站商品的展示地址', 'MODULE_PAYMENT_A_CHINABANK_SHOW_URL', '".HTTP_SERVER."/account_history.php', '网站商品的展示地址，不允许加?id=123这类自定义参数', '6', '9', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('收款方名称', 'MODULE_PAYMENT_A_CHINABANK_MAIN_NAME', 'USITrip', '收款方名称，如：公司名称、网站名称、收款人姓名等', '6', '10', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种', 'MODULE_PAYMENT_A_CHINABANK_CURRENCY', 'CNY', '币种', '6', '11', now())");
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_STATUS'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_API_DIR'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_SORT_ORDER'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_ID'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_KEY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_RETURN_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_SHOW_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_MAIN_NAME'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_A_CHINABANK_CURRENCY'");
	}

	function keys() {
		$keys = array('MODULE_PAYMENT_A_CHINABANK_STATUS', 'MODULE_PAYMENT_A_CHINABANK_API_DIR','MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR', 'MODULE_PAYMENT_A_CHINABANK_SORT_ORDER', 'MODULE_PAYMENT_A_CHINABANK_ID', 'MODULE_PAYMENT_A_CHINABANK_KEY','MODULE_PAYMENT_A_CHINABANK_RETURN_URL','MODULE_PAYMENT_A_CHINABANK_SHOW_URL','MODULE_PAYMENT_A_CHINABANK_MAIN_NAME','MODULE_PAYMENT_A_CHINABANK_CURRENCY');
		return $keys;
	}
}
?>