<?php
/**
 * 前台客户自动登录类
 * @author Howard
 */
class auto_login{
	/**
	 * 自动登录的有效期秒！默认为一天86400
	 * @var int
	 */
	public $expired = 86400;
	/**
	 * 自动登录的GET参数名称
	 * @var string
	 */
	private $get_tag = 'autologin';
	/**
	 * 初始化时载入当前的页面url地址
	 * @param string $get GET数组
	 */
	public function __construct($get){
		if($get[$this->get_tag]){	//自动登录判断
			$json = $this->decryption(rawurldecode($get[$this->get_tag]));
			$array = json_decode($json, true);
			if($array['id'] && $array['email'] && (floor($array['time']) + $this->expired) > time()){
				if($this->do_auto_login($array['id'], $array['email']) === true){
					//跳转到无autologin参数页面
					$url = ($_SERVER['SERVER_PORT']=='443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.tep_get_all_get_params(array($this->get_tag));
					tep_redirect($url);
					exit;
				}
			}
		}
	}
	/**
	 * 执行自动登录
	 * @param int $customers_id 客户id
	 * @param string $email 客户email地址
	 */
	private function do_auto_login($customers_id, $email){
		if($_SESSION['customer_id']) return false;
		$check_customer_query = tep_db_query('select * from ' . TABLE_CUSTOMERS . ' where customers_id="'.(int)$customers_id.'" and customers_email_address="'.tep_db_input(tep_db_prepare_input($email)).'" and customers_state="1" ');
		$check_customer = tep_db_fetch_array($check_customer_query);
		if($check_customer['customers_id'] && $check_customer['customers_id']==$customers_id){	//写session记录，与登录页面的主程序大体相同
			$_SESSION['customer_id'] = $check_customer['customers_id'];
			$_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
			$_SESSION['customer_default_ship_address_id'] = $check_customer['customers_default_ship_address_id'];
			$_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
			$_SESSION['customer_email_address'] = $check_customer['customers_email_address'];
			$_SESSION['customer_validation'] = $check_customer['customers_validation'];
			$_SESSION['customers_group'] = $check_customer['customers_group'];
			return true;
		}
	}
	/**
	 * 创建自动登录的网址
	 * @param string $url 源url
	 * @param int $customers_id 要自动登录的客户id
	 * @return string 生成有登录信息之后的新url地址
	 */
	public function make_url($url, $customers_id){
		if(!(int)$customers_id) return $url;
		//$email='xmzhh2000@126.com.cn';
		$email = tep_db_get_field_value('customers_email_address','customers','customers_id="'.(int)$customers_id.'" ');
		if(strpos($email,'@') === false) return $url;
		$json = json_encode(array('id'=>$customers_id, 'email'=>$email, 'time'=>time()));
		$json_encryption = rawurlencode($this->encryption($json));

		$tail = $this->get_tag.'='.$json_encryption;
		$new_url = $url;
		$parse_url = parse_url($new_url);
		if(!$parse_url['query']){
			$new_url = preg_replace('/\?.*/','',$new_url).'?'.$tail;
		}else {
			$gets = explode('&', $parse_url['query']);
			foreach ($gets as $key => $str){
				if(preg_match('/^'.$this->get_tag.'=/', $str) || strpos($str,'?')!==false){
					$gets[$key]='';
				}
			}
			$new_url = preg_replace('/\?.*/','',$new_url) . '?'.implode('&',$gets).'&'.$tail;
		}
		return $new_url;
	}
	/**
	 * 加密字符串
	 * @param string $str 要加密码的字符串
	 */
	private function encryption($str){
		return scs_cc_encrypt($str);
	}
	/**
	 * 解密
	 * @param string $encryption_str 已经由encryption加密的字符串
	 */
	public function decryption($encryption_str){
		return scs_cc_decrypt($encryption_str);
	}
	
	/**
	 * 根据客户是否登录状态输出信息(放到页面顶部的id="TopMiniLoginBox"处显示)
	 */
	public function displayTopMiniLoginBox(){
		$str = '<a rel="nofollow" href="'.tep_href_link("login.php","", "SSL").'" class="baizi login">会员登录</a>|<a rel="nofollow" href="'.tep_href_link("create_account.php","", "SSL").'" class="baizi registered">免费注册</a>';
		if(tep_session_is_registered('customer_id') && $_SESSION['customer_id']){
			$str = '您好<a href="'.tep_href_link(FILENAME_ACCOUNT,"", "SSL").'">'.$_SESSION['customer_first_name'].'</a>';
			$str.= '|<a href="'.tep_href_link(FILENAME_LOGOFF).'">退出</a>';
		}
		return $str;
	}
}
?>