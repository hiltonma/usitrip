<?php
/**
 * 登录旧站的功能
 * 临时应用，以后要弃用！
 * @package 
 */

class login_old{
	/**
	 * 当前登录的用户ID号，如果没登录此值小于1
	 */
	var $customer_id;

	function __construct(){
		if((int)$_SESSION['customer_id']){
			$this->customer_id = (int)$_SESSION['customer_id'];
		}
	}
	/**
	 * 加密数据
	 * 固定密钥：zhhlwk20*z
	 * 动态密钥：2位随机字符值
	 * 加密方式:md5
	 * @param $str 要加密码的原始字符串
	 */
	private function encrypt($str){
		$md5_str = '';
		$static_key = 'zhhlwk20*z';
		$dynamic_key = mt_rand(10,99);
		$md5_str = md5($str.$static_key.$dynamic_key).substr($dynamic_key,0,2);
		return $md5_str;
	}

	/**
	 * 输出登录表单
	 * @param $action 动作：index是去首页，ordersList是去订单列表，其它去用户中心首页
	 * @param $submit_button 提交按钮文字
	 * @param $submit_button_class 提交按钮的样式名称，默认为空！
	 */
	function output_from($action='index', $submit_button = '去旧版', $submit_button_class = ''){
		$form = '';
		$target_url = 'http://old.usitrip.com/';
		$email = $key = '';
		if((int)$this->customer_id > 0 && (int)$this->customer_id < 60000){
			$target_url = 'http://old.usitrip.com/outer_login.asp';
			$email = tep_get_customers_email($this->customer_id);
			$key = $this->encrypt($email);
			$form = '<form action="'.$target_url.'" method="post" target="_blank">
				<input type="hidden" name="email" value="'.$email.'"/>
				<input type="hidden" name="key" value="'.$key.'" />
				<input type="hidden" name="action" value="'.$action.'"/>
				<input class="'.$submit_button_class.'" type="submit" value="'.$submit_button.'"/>
				</form>
			';
		}elseif($action=='index'){
			$form = '<form action="'.$target_url.'" method="get" target="_blank"><input class="'.$submit_button_class.'" type="submit" value="'.$submit_button.'"/></form>';
		}
		return $form;
	}
	/**
	 * 退出登录
	 */
	function logoff(){
		$iframe = '';
		if((int)$this->customer_id < 60000){
			$url = 'http://old.usitrip.com/WebOld/logout.asp?notShowJsCode=1';
			$iframe = '<iframe src="'.$url.'" height="1" width="1" style="display:none;" ></iframe>';
		}
		return $iframe;
	}
}
?>