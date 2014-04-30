<?php
/**
 * 发送短信类(国际短信hi8d)
 * @example
 *  //引用我们的数据库操作函数
 *  require('includes/application_top.php');
 *  session_start();
 *  // 引用发送信类
 *  require('includes/classes/ensms.php');
 *  try {
 *  // 直实的发送地址
 *  //$a = new ensms('usitrip','a63b4be2106e3128057cae3ab7a6e2e4','http://www.sms01.com/ensms/servlet/WebSend','http://www.sms01.com/ensms/servlet/BalanceService',true);	
 *  // 测试地址
 *  $a = new ensms('usitrip','a63b4be2106e3128057cae3ab7a6e2e4','http://192.168.1.86/lvtu/ensms/servlet/WebSend','http://192.168.1.86/lvtu/ensms/servlet/BalanceService',true);
 *  // 如果是接收发送状态的页面，则判断是否有返回数据
 *  if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
 *  	// 记录返回的发送状态
 *  	$a->checkMsg($GLOBALS['HTTP_RAW_POST_DATA']);
 *  } else {
 *  	// 发送短信 有返回值 返回发送后 发送方返回的字符串
 *  	$rets = $a->addMsg('再测试一次我刚发了两条分别对两个手机测试短信发送！','gb2312')->send('8618098916029,8615013502817');
 *		
 *	}
 *	// 显示当前余额
 *	echo $a->getBalance();
 * } catch (Exception $e) {
 *	echo $e->getMessage();
 * }
 * @author lwkai 2013-1-23 下午2:51:00
 *
 */
class ensms {
	
	/**
	 * 帐号
	 * @var string
	 * @author lwkai 2013-1-10 下午1:20:08
	 */
	private $_user = '';
	
	/**
	 * 密码
	 * @var string
	 * @author lwkai 2013-1-10 下午1:20:27
	 */
	private $_pass = '';
	
	/**
	 * 余额查询接口地址
	 * @var string
	 * @author lwkai 2013-1-10 下午1:21:08
	 */
	private $_balance_url = '';
	
	/**
	 * 发送信息接口地址
	 * @var string
	 * @author lwkai 2013-1-23 上午11:09:04
	 */
	private $_send_url = '';
	
	/**
	 * URL后面跟的参数
	 * @var array
	 * @author lwkai 2013-1-10 下午3:57:05
	 */
	private $_param = array();
	
	/**
	 * 发送的GB2312编码的短信内容
	 * @var string
	 * @author lwkai 2013-1-23 下午12:16:31
	 */
	private $_content = '';
	
	/**
	 * 数据库操作
	 * @var ensmsdb
	 * @author lwkai 2013-1-23 下午12:00:40
	 */
	private $_db = null;
	
	/**
	 * 初始化发短信的类
	 * @param string $user 帐号
	 * @param string $pass 密码
	 * @param string $send_url 发送短信的URL
	 * @param string $balance_url 查询余额的URL
	 * @param boolean $md5 密码是否已经通过MD5加密
	 * @throws Exception
	 * @author lwkai 2013-1-23 下午3:04:30
	 */
	public function __construct($user='usitrip',$pass='a63b4be2106e3128057cae3ab7a6e2e4',$send_url='http://www.sms01.com/ensms/servlet/WebSend',$balance_url='http://www.sms01.com/ensms/servlet/BalanceService',$md5 = true) {
		$md5 = !!$md5;
		if ($user && $pass && $send_url && $balance_url) {
			$this->_user = $user;
			$this->_pass = $md5 ? $pass : md5($md5);
			$this->_send_url = in_array(substr($send_url,0,5), array("http:","https")) ? $send_url : "http://" . $send_url;
			$this->_balance_url = in_array(substr($balance_url,0,5), array('http:','https')) ? $balance_url : 'http://' . $balance_url;
			$this->_db = new ensmsdb();
		} else {
			throw new Exception('参数传进来错误！');
		}
	}
	
	/**
	 * 向URL发送请求
	 * @param unknown_type $url
	 * @return string
	 * @author lwkai 2013-1-23 下午2:58:54
	 */
	private function getInfo($url){
		/*$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		$cache = curl_exec ($ch);
		curl_close ($ch);
		//file_put_contents('/tmp/ensms.php.txt', $url . "\n\n" . $cache,FILE_APPEND);
		return (string)$cache;*/
		$str = file_get_contents($url);
		return $str;
	}
	
	/**
	 * 组合URL
	 * @param string $url 是什么请求的URL
	 * @return string
	 * @author lwkai 2013-1-11 上午11:21:08
	 */
	private function assemblyUrl($url) {
		$url .= '?userId=' . $this->_user . '&password=' . $this->_pass;
		foreach ($this->_param as $key => $val) {
			$url .= '&' . $key . '=' . urlencode(preg_replace("/\r|\n/","",$val));
		}
		return $url;
	}
	
	/**
	 * 取得当前余额
	 * @return string
	 * @author lwkai 2013-1-23 上午9:57:46
	 */
	public function getBalance() {
		$myGet = array();
		//$str = 'rspCode=0&rspDesc=查询成功&balance=199160';
		$str = $this->getInfo($this->assemblyUrl($this->_balance_url));
		//print_r($str);
		$str = explode('&', $str);
		foreach ($str as $key => $val) {
			$tmp = explode('=', $val);
			$myGet[$tmp[0]] = isset($tmp[1]) ? $tmp[1] : '';
		}
		$balance = $myGet['balance'] / 1000;
		return $balance . '元';
	}
	
	/**
	 * 添加要发送的消息
	 * @param string $msg 要发送的消息内容
	 * @param string $msg_charset 发送的消息文字编码
	 * @return ensms
	 * @author lwkai 2013-1-23 上午10:12:16
	 */
	public function addMsg($msg,$msg_charset = 'gb2312') {
		$this->_param['content'] = iconv($msg_charset,'utf-8//IGNORE',$msg).iconv('gb2312','utf-8//IGNORE',"【请勿直接回复，走四方网】");
		$this->_content = $msg_charset == 'gb2312' ? $msg : iconv($msg_charset,'gb2312//IGNORE',$msg);
		return $this;
	}
	
	/**
	 * 发送消息
	 * @param string $mobile 发送给哪个号码，支持多个号，用逗号隔开
	 * @return array
	 * @author lwkai 2013-1-23 上午10:13:15
	 */
	public function send($mobile) {
		$rtn = '';
		$mobile = str_replace('，',',',$mobile);
		$arr = explode(',',$mobile);
		$url = $this->assemblyUrl($this->_send_url);
		$temp = $this->getInfo($url . '&mobile=' . $mobile);
		preg_match("/msgId=(\d+)/", $temp,$match);
		$rtn = $match[1];
		foreach ($arr as $key => $val) {
			$this->_db->insertMsg($match[1], $this->_content, $val); 
		}
		$this->_param = array();
		$this->_content = '';
		return $rtn;
	}
	
	/**
	 * 接收信息发送方推送过来的发送结果
	 * <?xml version="1.0" encoding="utf-8"?>
	 * <reports>
	 * <report>
	 *	<userId>test</userId>
	 *	<msgId>001</msgId>
	 *	<mobile>13434343434</mobile>
	 *	<status>2</status>
	 * </report>
	 * <report>
	 *	<userId>test</userId>
	 *	<msgId>002</msgId>
	 *	<mobile>13434343435</mobile>
	 *	<status>1</status>
	 * </report>
	 * </reports>';
	 * @param string $text 推送过来的字符内容
	 * @author lwkai 2013-1-23 上午10:49:48
	 */
	public function checkMsg($text) {
		if (empty($text)) return;
		$xml = simplexml_load_string($text);
		foreach($xml->report as $key=>$val) {
			//echo $val->msgId . '<br/>';
			if ($val->userId != $this->_user) {
				//throw new Exception('返回的不是我们帐号的信息！');
			} else {
				$this->_db->checkMsg($val->msgId, $val->mobile, $val->status);
			}
		}
	}
}

class ensmsdb {
	
	/**
	 * 添加发送的短信到表
	 * @param string $id 服务器返回的消息ID
	 * @param string $msg 发送的消息内容
	 * @param string $mobile 接收的手机号码
	 * @author lwkai 2013-1-23 下午1:21:10
	 */
	public function insertMsg($id,$msg,$mobile){
		$data = array();
		$data['msg_id'] = $id;
		$data['to_phone'] = $mobile;
		$data['to_content'] = $msg;
		$data['add_date'] = date('Y-m-d H:i:s');
		$rtn = tep_db_perform('cpunc_sms_hi8d_history',$data);
	}
	
	/**
	 * 插入发送结果
	 * @param string $msg_id 消息ID
	 * @param string $mobile 手机号
	 * @param string $status 发送状态
	 * @author lwkai 2013-1-23 下午1:20:08
	 */
	public function checkMsg($msg_id,$mobile,$status) {
		$data = array();
		$data['msg_status'] = strtoupper($status) == 'DELIVRD' ? 0 : $status;
		$data['check_data'] = date('Y-m-d H:i:s');
		$rtn = tep_db_perform('cpunc_sms_hi8d_history', $data,'update'," msg_id='" . $msg_id . "' and to_phone='" . $mobile . "'");
	}
}
