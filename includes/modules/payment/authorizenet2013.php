<?php
/**
 * 2013版的Authorizenet支付模块
 * @author Howard Administrator
 * Login ID:Usit133
 * Password:United$8239
 * Current API Login ID:6bqTF24ebz8V
 * Current Transaction Key:98LE3JXmdB6uB28N
 */
class authorizenet2013{
	/**
	 * 该支付方式的唯一判断标记
	 * @var string
	 */
	public $code = 'authorizenet2013';
	/**
	 * 支付方式名称
	 * @var string
	 */
	public $title = 'Authorizenet信用卡2013版';
	/**
	 * 描述文字
	 * @var text
	 */
	public $description = 'Authorize信用卡2013版';
	/**
	 * 状态，若为true就是开，false为关闭
	 * @var bool
	 */
	public $enabled;
	/**
	 * 币种标识
	 * @var string
	 */
	public $currency;
	/**
	 * 在所有支付方式中的排序序号
	 * @var int
	 */
	public $sort_order;
	/**
	 * 是否是生产环境
	 * @var bool
	 */
	public $is_live;
	/**
	 * 2013版的Authorizenet支付模块
	 */
	public function __construct() {
		$this->title = MODULE_PAYMENT_AUTHORIZENET2013_TEXT_TITLE;	//Authorizenet信用卡2013版
		$this->description = MODULE_PAYMENT_AUTHORIZENET2013_TEXT_DESCRIPTION;	//Authorize信用卡2013版
		$this->sort_order = MODULE_PAYMENT_AUTHORIZENET2013_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_AUTHORIZENET2013_STATUS == 'True') ? true : false);
		$this->currency = MODULE_PAYMENT_AUTHORIZENET2013_CURRENCY;
		$this->email_footer = '';
		$this->is_live = ((MODULE_PAYMENT_AUTHORIZENET2013_IS_LIVE == 'True') ? true : false);
	}
	public function authorizenet2013(){
		return $this->__construct();
	}

	/**
	 * 更新状态
	 * 啥都没做
	 */
	function update_status() {
		return true;
	}
	/**
	 * js验证代码
	 * @return boolean
	 */
	function javascript_validation() {
		return false;
	}
	/**
	 * 在支付方式列表中显示的内容
	 * @return multitype:string
	 */
	function selection() {
		//温馨提示栏
		$warm_tips =
		'<div>
		<ul>		
	  	<li>
	  	<b> 提示：</b>
	  	</li>
	  	<li>1. 我们接受Visa、MasterCard、American Express、Discover及Debit卡，支持币种为美元；</li>
		<li>2. 本网站已安装SSL证书，已安全认证。实时到账，无任何手续费；</li>
		<li>3. 请确保信用卡剩余额度足够本次消费，并开通网上支付功能。</li>
	  	</ul>
	  	</div>';
		$warm_tips = db_to_html($warm_tips);
		return array('id' => $this->code,
		'module' => $this->title,
		'warm_tips' => $warm_tips,
		'currency' => (tep_not_null($this->currency) ? $this->currency : 'USD'));
	}
	/**
	 * 确认时检查
	 * @return boolean
	 */
	function pre_confirmation_check() {
		return false;
	}
	
	/**
	 * 支付方式确认
	 * @return multitype:string
	 */
	function confirmation() {
		return array('title' => $this->description);
	}
	
	/**
	 * 订单提交按钮，废的！
	 * @return boolean
	 */
	function process_button() {
		return false;
	}
	
	/**
	 * 支付过程
	 * @return boolean
	 */
	function before_process() {
		return false;
	}
	
	/**
	 * 提交支付后要做什么事？也废
	 * @return boolean
	 */
	function after_process() {
		return false;
	}
	
	/**
	 * 取得错误信息，好像也废。
	 * @return boolean
	 */
	function get_error() {
		return false;
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET2013_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}
	
	/**
	 * 安装
	 */
	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('总开关', 'MODULE_PAYMENT_AUTHORIZENET2013_STATUS', 'True', '是否启用此支付模块', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Login ID:', 'MODULE_PAYMENT_AUTHORIZENET2013_API_ID', '6bqTF24ebz8V', '输入API登录账号', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API验证码:', 'MODULE_PAYMENT_AUTHORIZENET2013_API_KEY', '92gEmL92Xm6yv6RV', '输入Transaction Key', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Url路径:', 'MODULE_PAYMENT_AUTHORIZENET2013_API_WEB_DIR', '".HTTP_SERVER."/includes/modules/payment/authorizenet2013/', '此支付方式的url根路径', '6', '1', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('币种:', 'MODULE_PAYMENT_AUTHORIZENET2013_CURRENCY', 'USD', '币种', '6', '100', now());");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_AUTHORIZENET2013_SORT_ORDER', '400', '排序顺序显示。最低的是首先显示。', '6', '0', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('是否是生产环境:', 'MODULE_PAYMENT_AUTHORIZENET2013_IS_LIVE', 'False', '是否是在生产环境使用，如果是就选择true，如果是测试环境就选false', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
	}
	
	/**
	 * 卸载
	 */
	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}
	/**
	 * 本支付模块的常量
	 * @return array
	 */
	function keys() {
		$array = array('MODULE_PAYMENT_AUTHORIZENET2013_API_ID', 'MODULE_PAYMENT_AUTHORIZENET2013_API_KEY', 'MODULE_PAYMENT_AUTHORIZENET2013_STATUS', 'MODULE_PAYMENT_AUTHORIZENET2013_SORT_ORDER','MODULE_PAYMENT_AUTHORIZENET2013_CURRENCY', 'MODULE_PAYMENT_AUTHORIZENET2013_IS_LIVE', 'MODULE_PAYMENT_AUTHORIZENET2013_API_WEB_DIR');
		return $array;
	}
}
?>