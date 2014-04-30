<?php
/**
 * 银联在线支付接口前端类
 * @package 
 */

class netpay {
	var $code, $title, $description, $enabled, $currency;

	// class constructor
	function netpay() {
		$this->code = 'netpay';
		$this->title = MODULE_PAYMENT_NETPAY_TEXT_TITLE;
		$this->sort_order = MODULE_PAYMENT_NETPAY_SORT_ORDER;
		$this->description = MODULE_PAYMENT_NETPAY_TEXT_DESCRIPTION;
		$this->email_footer = MODULE_PAYMENT_NETPAY_TEXT_EMAIL_FOOTER;
		$this->enabled = MODULE_PAYMENT_NETPAY_STATUS;
		$this->currency = MODULE_PAYMENT_NETPAY_CURRENCY;

	}
	// class methods
	function javascript_validation() {
		return false;
	}

	function selection() {

		$cc_explain = '<div>
		<ul>
		<li>
		<img  src="'.HTTP_SERVER.'/includes/modules/payment/netpay/images/logo.gif" border="0" />
		</li>
	  
	  <li>
	  <b> 提示：</b>
	  </li>
	  <li>
		1. 银联在线用人民币支付，可以使用信用卡和网上银行支付！
		</li>
		<li>
		2. 如果您对汇率方面有任何疑问，请与我们联系。
		</li>
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
		'currency' => (tep_not_null($this->currency) ? $this->currency : 'CNY') );

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
		'fields' => array(array('title' => MODULE_PAYMENT_NETPAY_TEXT_DESCRIPTION)));

		return $confirmation;
	}

	// Below is the original pre-November snapshot code.  I have left it souly for the less technical minded might
	// be able to compare what some of the more indepth changes consisted of.  Perhaps it will facilitate more preNov
	// Snapshots to being modified to postNov snapshot compatibility -- Thomas Keats

	//    function confirmation() {
	//      $confirmation_string = '          <tr>' . "\n" .
	//                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_NETPAY_TEXT_DESCRIPTION . $
	//                             '          </tr>' . "\n";
	//      return $confirmation_string;
	//    }

	function process_button() {
		return false;
	}

	/**
	 * 支付过程发送数据到银联在线，让其完成支付过程
	 *
	 * @return unknown
	 */
	function before_process() {	//银联在线的执行数据
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
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_STATUS'");
			$this->check = tep_db_num_rows($check_query);
		}
		return $this->check;
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('是否启用此模块', 'MODULE_PAYMENT_NETPAY_STATUS', '1', '只有启用了此模块之后前台才能显示该支付模块。', '6', '1', 'tep_cfg_select_option_change_display(array(\'1\', \'0\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('银联在线接口路径', 'MODULE_PAYMENT_NETPAY_API_DIR', '".dirname(__FILE__)."/netpay/', '存放银联在线接口路径的目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('银联在线接口http路径', 'MODULE_PAYMENT_NETPAY_API_WEB_DIR','".HTTP_SERVER."/includes/modules/payment/netpay/', '存放银联在线接口路径的http目录路径', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('排序序号', 'MODULE_PAYMENT_NETPAY_SORT_ORDER', '102', '在所有支付模块中排第几位？', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('私钥文件名', 'MODULE_PAYMENT_NETPAY_PRI_KEY', 'MerPrk.key', '私钥文件名', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('公钥文件名', 'MODULE_PAYMENT_NETPAY_PUB_KEY', 'PgPubk.key', '公钥文件名', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('付完款后跳转的页面', 'MODULE_PAYMENT_NETPAY_RETURN_URL', '".HTTP_SERVER."/includes/modules/payment/netpay/order_feedback.php', '请填写返回url,地址应为绝对路径,带有http协议，切记不能超过80字符。', '6', '8', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('支付请求地址', 'MODULE_PAYMENT_NETPAY_REQ_URL_PAY', 'https://payment.ChinaPay.com/pay/TransGet', '支付请求地址：(生产)https://payment.ChinaPay.com/pay/TransGet，(测试)http://payment-test.ChinaPay.com/pay/TransGet', '6', '9', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种', 'MODULE_PAYMENT_NETPAY_CURRENCY', 'CNY', '币种', '6', '11', now())");
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_STATUS'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_API_DIR'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_SORT_ORDER'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_PRI_KEY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_PUB_KEY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_RETURN_URL'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_REQ_URL_PAY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_CURRENCY'");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NETPAY_API_WEB_DIR'");
	}

	function keys() {
		$keys = array('MODULE_PAYMENT_NETPAY_STATUS', 'MODULE_PAYMENT_NETPAY_API_DIR', 'MODULE_PAYMENT_NETPAY_SORT_ORDER', 'MODULE_PAYMENT_NETPAY_PRI_KEY', 'MODULE_PAYMENT_NETPAY_PUB_KEY','MODULE_PAYMENT_NETPAY_RETURN_URL','MODULE_PAYMENT_NETPAY_REQ_URL_PAY','MODULE_PAYMENT_NETPAY_CURRENCY','MODULE_PAYMENT_NETPAY_API_WEB_DIR');
		return $keys;
	}
}
?>